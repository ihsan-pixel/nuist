import 'dart:async';
import 'dart:convert';
import 'dart:io';

import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:flutter_map/flutter_map.dart';
import 'package:geocoding/geocoding.dart';
import 'package:geolocator/geolocator.dart';
import 'package:image_picker/image_picker.dart';
import 'package:latlong2/latlong.dart';

import 'attendance_face_scan_page.dart';
import '../../services/teacher_mobile_repository.dart';
import '../../widgets/app/app_section_card.dart';

const _attendancePrimary = Color(0xFF04A512);
const _attendancePrimaryDark = Color(0xFF037A0D);
const _attendancePrimarySoft = Color(0xFFE7F8E9);
const _attendancePrimaryBorder = Color(0xFFBFEAC4);
const _attendanceText = Color(0xFF0E4D16);
const _attendanceMuted = Color(0xFF6A8870);
const _attendanceMapDefaultZoom = 15.6;
const _attendanceMapUserZoom = 18.4;

class TeacherAttendancePage extends StatefulWidget {
  const TeacherAttendancePage({
    super.key,
    required this.repository,
    required this.onBackToHome,
    required this.onOpenAttendanceHistory,
    required this.isActive,
  });

  final TeacherMobileRepository repository;
  final VoidCallback onBackToHome;
  final Future<void> Function() onOpenAttendanceHistory;
  final bool isActive;

  @override
  State<TeacherAttendancePage> createState() => _TeacherAttendancePageState();
}

class _TeacherAttendancePageState extends State<TeacherAttendancePage>
    with WidgetsBindingObserver {
  final ImagePicker _imagePicker = ImagePicker();

  late Future<Map<String, dynamic>> _future;
  DateTime _now = DateTime.now();
  Timer? _clockTimer;
  Position? _position;
  XFile? _selfieFile;
  Map<String, dynamic>? _faceScanResult;
  String? _locationAddress;
  String? _locationError;
  List<Map<String, dynamic>> _locationReadings = const [];
  bool _loadingLocation = false;
  bool _capturingSelfie = false;
  bool _submitting = false;

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addObserver(this);
    _clockTimer = Timer.periodic(const Duration(seconds: 1), (_) {
      if (!mounted) {
        return;
      }
      setState(() {
        _now = DateTime.now();
      });
    });
    _future = widget.repository.getAttendance();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      _handlePageReactivated(refreshRemoteData: false);
    });
  }

  @override
  void didUpdateWidget(covariant TeacherAttendancePage oldWidget) {
    super.didUpdateWidget(oldWidget);
    if (!oldWidget.isActive && widget.isActive) {
      unawaited(_handlePageReactivated());
    }
  }

  @override
  void didChangeAppLifecycleState(AppLifecycleState state) {
    if (state == AppLifecycleState.resumed && widget.isActive) {
      unawaited(_handlePageReactivated());
    }
  }

  @override
  void dispose() {
    WidgetsBinding.instance.removeObserver(this);
    _clockTimer?.cancel();
    super.dispose();
  }

  Future<void> _refresh() async {
    final future = widget.repository.getAttendance();
    setState(() {
      _future = future;
    });
    await future;
  }

  Future<void> _handlePageReactivated({
    bool refreshRemoteData = true,
  }) async {
    if (!mounted) {
      return;
    }

    if (refreshRemoteData) {
      await _refresh();
    }

    setState(() {
      _now = DateTime.now();
    });
    _triggerAutoLocationCapture();
  }

  void _triggerAutoLocationCapture() {
    if (!mounted || !widget.isActive || _loadingLocation) {
      return;
    }

    unawaited(_captureLocation());
  }

  Future<void> _captureLocation() async {
    setState(() {
      _loadingLocation = true;
      _locationError = null;
    });

    try {
      final serviceEnabled = await Geolocator.isLocationServiceEnabled();
      if (!serviceEnabled) {
        throw Exception('GPS belum aktif. Nyalakan lokasi lalu coba lagi.');
      }

      var permission = await Geolocator.checkPermission();
      if (permission == LocationPermission.denied) {
        permission = await Geolocator.requestPermission();
      }

      if (permission == LocationPermission.denied ||
          permission == LocationPermission.deniedForever) {
        throw Exception(
            'Izin lokasi ditolak. Presensi memerlukan akses lokasi.');
      }

      final readings = <Map<String, dynamic>>[];
      Position? latestPosition;

      for (var index = 0; index < 3; index++) {
        final sampled = await Geolocator.getCurrentPosition(
          desiredAccuracy: LocationAccuracy.best,
        );
        latestPosition = sampled;
        readings.add({
          'latitude': sampled.latitude,
          'longitude': sampled.longitude,
          'accuracy': sampled.accuracy,
          'timestamp': DateTime.now().millisecondsSinceEpoch,
        });

        if (index < 2) {
          await Future<void>.delayed(const Duration(milliseconds: 900));
        }
      }

      if (latestPosition == null) {
        throw Exception('Lokasi tidak berhasil dibaca.');
      }

      final address = await _resolveAddress(latestPosition);

      if (!mounted) {
        return;
      }

      setState(() {
        _position = latestPosition;
        _locationAddress = address;
        _locationError = null;
        _locationReadings = readings;
      });
    } catch (error) {
      if (!mounted) {
        return;
      }
      setState(() {
        _locationError = error.toString().replaceFirst('Exception: ', '');
      });
    } finally {
      if (mounted) {
        setState(() {
          _loadingLocation = false;
        });
      }
    }
  }

  Future<void> _captureSelfie() async {
    setState(() {
      _capturingSelfie = true;
    });

    try {
      final file = await _imagePicker.pickImage(
        source: ImageSource.camera,
        preferredCameraDevice: CameraDevice.front,
        imageQuality: 70,
        maxWidth: 1600,
      );

      if (!mounted || file == null) {
        return;
      }

      setState(() {
        _selfieFile = file;
        _faceScanResult = null;
      });
    } catch (error) {
      if (!mounted) {
        return;
      }
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(
            error.toString().replaceFirst('Exception: ', ''),
          ),
        ),
      );
    } finally {
      if (mounted) {
        setState(() {
          _capturingSelfie = false;
        });
      }
    }
  }

  Future<void> _captureFaceScan(Map<String, dynamic> data) async {
    final verification = Map<String, dynamic>.from(
      (data['verification'] as Map?) ?? const <String, dynamic>{},
    );
    final isFaceEnrolled = verification['face_enrolled'] == true ||
        verification['enrolled'] == true;

    if (!isFaceEnrolled) {
      final message = verification['message'] as String? ??
          'Data wajah belum terdaftar. Hubungi admin untuk aktivasi scan wajah.';
      if (!mounted) {
        return;
      }
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(message)),
      );
      return;
    }

    final result = await Navigator.of(context).push<Map<String, dynamic>>(
      MaterialPageRoute(
        fullscreenDialog: true,
        builder: (_) => const AttendanceFaceScanPage(
          title: 'Scan Wajah',
          description: 'Selesaikan verifikasi biometrik untuk presensi.',
        ),
      ),
    );

    if (!mounted || result == null) {
      return;
    }

    final normalizedResult = _normalizeFaceScanResult(result);
    if (normalizedResult == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text(
            'Hasil scan wajah belum lengkap. Silakan ulangi scan.',
          ),
        ),
      );
      return;
    }

    setState(() {
      _selfieFile = null;
      _faceScanResult = normalizedResult;
    });
  }

  Future<void> _captureVerification(Map<String, dynamic> data) async {
    if (_requiresFaceScan(data)) {
      await _captureFaceScan(data);
      return;
    }

    await _captureSelfie();
  }

  Future<bool> _submitAttendance(Map<String, dynamic> data) async {
    final form = Map<String, dynamic>.from(
      (data['form'] as Map?) ?? const <String, dynamic>{},
    );
    final verification = Map<String, dynamic>.from(
      (data['verification'] as Map?) ?? const <String, dynamic>{},
    );
    final timeRanges = Map<String, dynamic>.from(
      (data['time_ranges'] as Map?) ?? const <String, dynamic>{},
    );
    final mode = form['next_mode'] as String?;
    final verificationMode = verification['mode'] as String? ?? 'selfie';

    if (mode == null || mode.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Mode presensi hari ini tidak tersedia.')),
      );
      return false;
    }

    if (_position == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(
            _locationError?.trim().isNotEmpty == true
                ? _locationError!
                : 'Lokasi belum tersedia. Tunggu GPS selesai dibaca lalu coba lagi.',
          ),
        ),
      );
      return false;
    }

    if (verificationMode == 'face_scan') {
      if (_faceScanResult == null) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('Selesaikan scan wajah biometrik terlebih dahulu.'),
          ),
        );
        return false;
      }
    } else if (_selfieFile == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Ambil foto selfie terlebih dahulu.')),
      );
      return false;
    }

    if (mode == 'keluar') {
      final shouldConfirmEarlyCheckout = _isBeforeCheckoutTime(
        now: DateTime.now(),
        pulangStart: timeRanges['pulang_start'] as String?,
      );
      if (shouldConfirmEarlyCheckout) {
        final confirmed = await _confirmEarlyCheckout(
          pulangStart: timeRanges['pulang_start'] as String?,
        );
        if (confirmed != true || !mounted) {
          return false;
        }
      }
    }

    setState(() {
      _submitting = true;
    });

    try {
      final payload = <String, dynamic>{
        'presensi_mode': mode,
        'latitude': _position!.latitude,
        'longitude': _position!.longitude,
        'lokasi': _locationAddress ?? _coordinateLabel(_position!),
        'accuracy': _position!.accuracy,
        'altitude': _position!.altitude,
        'speed': _position!.speed,
        'device_info': 'flutter_mobile_${defaultTargetPlatform.name}',
        'location_readings': _locationReadings,
      };

      if (verificationMode == 'face_scan') {
        payload.addAll({
          'selfie_data': _faceScanResult!['selfie_data'],
          'face_descriptor': _faceScanResult!['face_descriptor'],
          'liveness_score': _faceScanResult!['liveness_score'],
          'liveness_challenges': _faceScanResult!['liveness_challenges'],
        });
      } else {
        payload['selfie_data'] = await _buildSelfieData(_selfieFile!);
      }

      final result = await widget.repository.submitAttendance(payload: payload);
      if (!mounted) {
        return false;
      }

      setState(() {
        _selfieFile = null;
        _faceScanResult = null;
      });

      await _refresh();

      if (!mounted) {
        return false;
      }

      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(
            (result['_message'] as String?) ?? 'Presensi berhasil dikirim.',
          ),
        ),
      );
      return true;
    } catch (error) {
      if (!mounted) {
        return false;
      }
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(error.toString().replaceFirst('Exception: ', '')),
        ),
      );
      return false;
    } finally {
      if (mounted) {
        setState(() {
          _submitting = false;
        });
      }
    }
  }

  Future<String> _buildSelfieData(XFile file) async {
    final bytes = await file.readAsBytes();
    final lowerPath = file.path.toLowerCase();
    final mime = lowerPath.endsWith('.png') ? 'image/png' : 'image/jpeg';
    return 'data:$mime;base64,${base64Encode(bytes)}';
  }

  Map<String, dynamic>? _normalizeFaceScanResult(Map<String, dynamic> raw) {
    final selfieData =
        raw['captured_image'] as String? ?? raw['selfie_data'] as String?;
    final faceDescriptor = raw['face_descriptor'];
    final livenessScore = raw['liveness_score'];
    final livenessChallenges = raw['liveness_challenges'];

    if (selfieData == null ||
        selfieData.trim().isEmpty ||
        faceDescriptor is! List ||
        livenessChallenges is! List) {
      return null;
    }

    return {
      'selfie_data': selfieData,
      'face_descriptor': List<dynamic>.from(faceDescriptor),
      'liveness_score': livenessScore,
      'liveness_challenges': List<dynamic>.from(livenessChallenges),
    };
  }

  bool _requiresFaceScan(Map<String, dynamic> data) {
    final verification = Map<String, dynamic>.from(
      (data['verification'] as Map?) ?? const <String, dynamic>{},
    );

    return (verification['mode'] as String? ?? 'selfie') == 'face_scan';
  }

  bool _hasVerificationCapture(Map<String, dynamic> data) {
    if (_requiresFaceScan(data)) {
      return _faceScanResult != null;
    }

    return _selfieFile != null;
  }

  Future<bool?> _confirmEarlyCheckout({
    required String? pulangStart,
  }) {
    final pulangLabel = pulangStart != null && pulangStart.trim().isNotEmpty
        ? pulangStart.trim()
        : null;

    return showDialog<bool>(
      context: context,
      builder: (context) {
        return AlertDialog(
          title: const Text('Konfirmasi Pulang Awal'),
          content: Text(
            pulangLabel == null
                ? 'Jam pulang belum tiba. Apakah Anda yakin ingin pulang lebih awal?'
                : 'Jam pulang dimulai pukul $pulangLabel. Apakah Anda yakin ingin pulang lebih awal?',
          ),
          actions: [
            TextButton(
              onPressed: () => Navigator.of(context).pop(false),
              child: const Text('Batal'),
            ),
            ElevatedButton(
              onPressed: () => Navigator.of(context).pop(true),
              style: ElevatedButton.styleFrom(
                backgroundColor: _attendancePrimary,
                foregroundColor: Colors.white,
              ),
              child: const Text('Ya, lanjutkan'),
            ),
          ],
        );
      },
    );
  }

  bool _isBeforeCheckoutTime({
    required DateTime now,
    required String? pulangStart,
  }) {
    final parsedCheckoutTime = _parseTodayTime(pulangStart, now);
    if (parsedCheckoutTime == null) {
      return false;
    }
    return now.isBefore(parsedCheckoutTime);
  }

  DateTime? _parseTodayTime(String? value, DateTime anchor) {
    final raw = value?.trim() ?? '';
    if (raw.isEmpty) {
      return null;
    }

    final parts = raw.split(':');
    if (parts.length < 2) {
      return null;
    }

    final hour = int.tryParse(parts[0]);
    final minute = int.tryParse(parts[1]);
    final second = parts.length > 2 ? int.tryParse(parts[2]) ?? 0 : 0;
    if (hour == null || minute == null) {
      return null;
    }

    return DateTime(
      anchor.year,
      anchor.month,
      anchor.day,
      hour,
      minute,
      second,
    );
  }

  Future<String> _resolveAddress(Position position) async {
    try {
      final placemarks = await placemarkFromCoordinates(
        position.latitude,
        position.longitude,
      );

      if (placemarks.isEmpty) {
        return _coordinateLabel(position);
      }

      final first = placemarks.first;
      final parts = [
        first.street,
        first.subLocality,
        first.locality,
        first.subAdministrativeArea,
      ]
          .whereType<String>()
          .map((part) => part.trim())
          .where((part) => part.isNotEmpty)
          .toList();

      return parts.isEmpty ? _coordinateLabel(position) : parts.join(', ');
    } catch (_) {
      return _coordinateLabel(position);
    }
  }

  int _attendanceFlowInitialStep(Map<String, dynamic> data) {
    if (_position == null) {
      return 0;
    }

    if (!_hasVerificationCapture(data)) {
      return 1;
    }

    return 2;
  }

  Future<void> _openAttendanceFlow(Map<String, dynamic> data) async {
    final pageController = PageController(
      initialPage: _attendanceFlowInitialStep(data),
    );
    var currentStep = _attendanceFlowInitialStep(data);

    Future<void> jumpToStep(
      int step,
      void Function(void Function()) setModalState,
    ) async {
      currentStep = step;
      setModalState(() {});
      await pageController.animateToPage(
        step,
        duration: const Duration(milliseconds: 260),
        curve: Curves.easeOutCubic,
      );
    }

    await showModalBottomSheet<void>(
      context: context,
      isScrollControlled: true,
      useSafeArea: true,
      backgroundColor: Colors.transparent,
      builder: (sheetContext) {
        return StatefulBuilder(
          builder: (context, setModalState) {
            Future<void> handleCaptureLocation() async {
              await _captureLocation();
              if (!mounted) {
                return;
              }
              setModalState(() {});
              if (_position != null && currentStep == 0) {
                await jumpToStep(1, setModalState);
              }
            }

            Future<void> handleCaptureVerification() async {
              await _captureVerification(data);
              if (!mounted) {
                return;
              }
              setModalState(() {});
              if (_hasVerificationCapture(data) && currentStep <= 1) {
                await jumpToStep(2, setModalState);
              }
            }

            Future<void> handleSubmit() async {
              final success = await _submitAttendance(data);
              if (!mounted) {
                return;
              }
              setModalState(() {});
              if (success) {
                Navigator.of(sheetContext).pop();
              }
            }

            return _AttendanceFlowSheet(
              pageController: pageController,
              currentStep: currentStep,
              onStepChange: (step) {
                currentStep = step;
                setModalState(() {});
              },
              position: _position,
              locationAddress: _locationAddress,
              locationError: _locationError,
              locationReadingsCount: _locationReadings.length,
              loadingLocation: _loadingLocation,
              onCaptureLocation: handleCaptureLocation,
              selfieFile: _selfieFile,
              faceScanResult: _faceScanResult,
              capturingSelfie: _capturingSelfie,
              onCaptureSelfie: handleCaptureVerification,
              onClearSelfie: () {
                setState(() {
                  _selfieFile = null;
                  _faceScanResult = null;
                });
                setModalState(() {});
              },
              submitting: _submitting,
              data: data,
              onSubmit: handleSubmit,
              onPreviousStep: currentStep == 0
                  ? null
                  : () async {
                      await jumpToStep(currentStep - 1, setModalState);
                    },
              onNextStep: () async {
                if (currentStep == 0 && _position != null) {
                  await jumpToStep(1, setModalState);
                  return;
                }
                if (currentStep == 1 && _hasVerificationCapture(data)) {
                  await jumpToStep(2, setModalState);
                }
              },
            );
          },
        );
      },
    );
    pageController.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return FutureBuilder<Map<String, dynamic>>(
      future: _future,
      builder: (context, snapshot) {
        if (snapshot.connectionState == ConnectionState.waiting) {
          return const ColoredBox(
            color: Colors.white,
            child: _PageLoading(),
          );
        }

        if (snapshot.hasError) {
          return SafeArea(
            child: Padding(
              padding: const EdgeInsets.fromLTRB(16, 16, 16, 28),
              child: _PageError(
                message: snapshot.error.toString(),
                onRetry: _refresh,
              ),
            ),
          );
        }

        return _AttendanceContent(
          data: snapshot.data ?? const <String, dynamic>{},
          now: _now,
          submitting: _submitting,
          position: _position,
          locationAddress: _locationAddress,
          locationError: _locationError,
          locationReadingsCount: _locationReadings.length,
          loadingLocation: _loadingLocation,
          onCaptureLocation: _captureLocation,
          selfieFile: _selfieFile,
          faceScanResult: _faceScanResult,
          capturingSelfie: _capturingSelfie,
          onCaptureSelfie: () => _captureVerification(
            snapshot.data ?? const <String, dynamic>{},
          ),
          onClearSelfie: () {
            setState(() {
              _selfieFile = null;
              _faceScanResult = null;
            });
          },
          onOpenAttendanceFlow: () => _openAttendanceFlow(
            snapshot.data ?? const <String, dynamic>{},
          ),
          onBackToHome: widget.onBackToHome,
          onOpenAttendanceHistory: widget.onOpenAttendanceHistory,
          onRefreshData: _refresh,
        );
      },
    );
  }
}

class _AttendanceContent extends StatelessWidget {
  const _AttendanceContent({
    required this.data,
    required this.now,
    required this.submitting,
    required this.position,
    required this.locationAddress,
    required this.locationError,
    required this.locationReadingsCount,
    required this.loadingLocation,
    required this.onCaptureLocation,
    required this.selfieFile,
    required this.faceScanResult,
    required this.capturingSelfie,
    required this.onCaptureSelfie,
    required this.onClearSelfie,
    required this.onOpenAttendanceFlow,
    required this.onBackToHome,
    required this.onOpenAttendanceHistory,
    required this.onRefreshData,
  });

  final Map<String, dynamic> data;
  final DateTime now;
  final bool submitting;
  final Position? position;
  final String? locationAddress;
  final String? locationError;
  final int locationReadingsCount;
  final bool loadingLocation;
  final Future<void> Function() onCaptureLocation;
  final XFile? selfieFile;
  final Map<String, dynamic>? faceScanResult;
  final bool capturingSelfie;
  final Future<void> Function() onCaptureSelfie;
  final VoidCallback onClearSelfie;
  final VoidCallback onOpenAttendanceFlow;
  final VoidCallback onBackToHome;
  final Future<void> Function() onOpenAttendanceHistory;
  final Future<void> Function() onRefreshData;

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final colorScheme = theme.colorScheme;
    final mediaQuery = MediaQuery.of(context);
    final today = Map<String, dynamic>.from(
      (data['today_attendance'] as Map?) ?? const <String, dynamic>{},
    );
    final form = Map<String, dynamic>.from(
      (data['form'] as Map?) ?? const <String, dynamic>{},
    );
    final timeRanges = Map<String, dynamic>.from(
      (data['time_ranges'] as Map?) ?? const <String, dynamic>{},
    );
    final recent = (data['recent'] as List?) ?? const [];
    final verification = Map<String, dynamic>.from(
      (data['verification'] as Map?) ?? const <String, dynamic>{},
    );
    final canSubmit = form['can_submit'] == true;
    final nextModeLabel =
        form['next_mode_label'] as String? ?? 'Presensi Masuk';
    final verificationMode = verification['mode'] as String? ?? 'selfie';
    final nativeSupportsVerification =
        verificationMode == 'selfie' || verificationMode == 'face_scan';
    final isFaceScan = verificationMode == 'face_scan';
    final isFaceEnrolled = verification['face_enrolled'] == true ||
        verification['enrolled'] == true ||
        !isFaceScan;
    final schoolName = data['school_name'] as String? ?? '-';
    final schoolLatitude = _findNumericValue(
      [data, form, today],
      const ['school_latitude', 'latitude', 'lat'],
    );
    final schoolLongitude = _findNumericValue(
      [data, form, today],
      const ['school_longitude', 'longitude', 'lng', 'lon'],
    );
    final mapCenter = _mapViewportCenter(
      userPosition: position,
      schoolLatitude: schoolLatitude,
      schoolLongitude: schoolLongitude,
    );
    final canOpenFlow = canSubmit &&
        nativeSupportsVerification &&
        isFaceEnrolled &&
        !submitting;

    return Stack(
      children: [
        Positioned.fill(
          child: _AttendanceMapLayer(
            center: mapCenter,
            userPosition: position,
            schoolName: schoolName,
            schoolLatitude: schoolLatitude,
            schoolLongitude: schoolLongitude,
            loadingLocation: loadingLocation,
          ),
        ),
        Positioned(
          top: mediaQuery.padding.top + 10,
          left: 14,
          right: 14,
          child: Row(
            children: [
              _MapHeaderButton(
                icon: Icons.arrow_back_ios_new_rounded,
                onTap: onBackToHome,
              ),
              Expanded(
                child: Center(
                  child: Column(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      Text(
                        'Presensi Hari Ini',
                        style: theme.textTheme.titleSmall?.copyWith(
                          color: _attendanceText,
                          fontWeight: FontWeight.w800,
                          fontSize: 13,
                        ),
                      ),
                      const SizedBox(height: 2),
                      Text(
                        _formatRunningTime(now),
                        style: theme.textTheme.labelSmall?.copyWith(
                          color: _attendancePrimaryDark,
                          fontWeight: FontWeight.w800,
                          fontSize: 10,
                          letterSpacing: 0.2,
                        ),
                      ),
                    ],
                  ),
                ),
              ),
              PopupMenuButton<String>(
                onSelected: (value) async {
                  if (value == 'refresh') {
                    await onRefreshData();
                    return;
                  }
                  if (value == 'history') {
                    await onOpenAttendanceHistory();
                  }
                },
                itemBuilder: (context) => const [
                  PopupMenuItem<String>(
                    value: 'history',
                    child: Text('Riwayat Presensi'),
                  ),
                  PopupMenuItem<String>(
                    value: 'refresh',
                    child: Text('Refresh'),
                  ),
                ],
                child: const _MapHeaderButton(
                  icon: Icons.more_horiz_rounded,
                ),
              ),
            ],
          ),
        ),
        Positioned(
          left: 18,
          right: 18,
          bottom: 20 + mediaQuery.padding.bottom,
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              Container(
                width: double.infinity,
                padding:
                    const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
                decoration: BoxDecoration(
                  color: colorScheme.surface.withOpacity(0.96),
                  borderRadius: BorderRadius.circular(24),
                  boxShadow: [
                    BoxShadow(
                      color: Colors.black.withOpacity(0.08),
                      blurRadius: 22,
                      offset: const Offset(0, 10),
                    ),
                  ],
                ),
                child: Row(
                  crossAxisAlignment: CrossAxisAlignment.center,
                  children: [
                    Expanded(
                      child: _AttendanceTopMetric(
                        title: 'Jam Masuk',
                        value: _resolveAttendanceDisplayTime(
                          today,
                          form: form,
                          recent: recent,
                          now: now,
                          startTime: timeRanges['masuk_start'] as String?,
                          isCheckIn: true,
                        ),
                        caption: (timeRanges['masuk_start'] as String?)
                                    ?.trim()
                                    .isNotEmpty ==
                                true
                            ? 'Mulai ${timeRanges['masuk_start']}'
                            : 'Belum tercatat',
                      ),
                    ),
                    const SizedBox(width: 14),
                    Container(
                      width: 1,
                      height: 44,
                      color: _attendancePrimaryBorder,
                    ),
                    const SizedBox(width: 14),
                    _AttendanceCenterStatus(
                      statusLabel: _formatCurrentDate(now),
                      subtitle: '',
                      metric: null,
                      metricLabel: '',
                    ),
                    const SizedBox(width: 14),
                    Container(
                      width: 1,
                      height: 44,
                      color: _attendancePrimaryBorder,
                    ),
                    const SizedBox(width: 14),
                    Expanded(
                      child: _AttendanceTopMetric(
                        title: 'Jam Pulang',
                        value: _resolveAttendanceDisplayTime(
                          today,
                          form: form,
                          recent: recent,
                          now: now,
                          startTime: timeRanges['pulang_start'] as String?,
                          isCheckIn: false,
                        ),
                        caption: (timeRanges['pulang_start'] as String?)
                                    ?.trim()
                                    .isNotEmpty ==
                                true
                            ? 'Mulai ${timeRanges['pulang_start']}'
                            : 'Belum tercatat',
                        align: CrossAxisAlignment.end,
                        textAlign: TextAlign.end,
                      ),
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 12),
              SizedBox(
                width: double.infinity,
                child: FilledButton(
                  onPressed: canOpenFlow ? onOpenAttendanceFlow : null,
                  style: FilledButton.styleFrom(
                    backgroundColor: _attendancePrimary,
                    disabledBackgroundColor: _attendancePrimaryBorder,
                    foregroundColor: Colors.white,
                    padding: const EdgeInsets.symmetric(vertical: 16),
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(14),
                    ),
                  ),
                  child: Text(
                    nextModeLabel,
                    style: const TextStyle(
                      fontWeight: FontWeight.w800,
                      fontSize: 13,
                    ),
                  ),
                ),
              ),
              if (isFaceScan && !isFaceEnrolled) ...[
                const SizedBox(height: 8),
                Text(
                  verification['message'] as String? ??
                      'Data wajah belum terdaftar.',
                  textAlign: TextAlign.center,
                  style: theme.textTheme.labelSmall?.copyWith(
                    color: Colors.white,
                    fontWeight: FontWeight.w700,
                  ),
                ),
              ],
            ],
          ),
        ),
      ],
    );
  }
}

class _AttendanceFlowSheet extends StatelessWidget {
  const _AttendanceFlowSheet({
    required this.pageController,
    required this.currentStep,
    required this.onStepChange,
    required this.position,
    required this.locationAddress,
    required this.locationError,
    required this.locationReadingsCount,
    required this.loadingLocation,
    required this.onCaptureLocation,
    required this.selfieFile,
    required this.faceScanResult,
    required this.capturingSelfie,
    required this.onCaptureSelfie,
    required this.onClearSelfie,
    required this.submitting,
    required this.data,
    required this.onSubmit,
    required this.onPreviousStep,
    required this.onNextStep,
  });

  final PageController pageController;
  final int currentStep;
  final ValueChanged<int> onStepChange;
  final Position? position;
  final String? locationAddress;
  final String? locationError;
  final int locationReadingsCount;
  final bool loadingLocation;
  final Future<void> Function() onCaptureLocation;
  final XFile? selfieFile;
  final Map<String, dynamic>? faceScanResult;
  final bool capturingSelfie;
  final Future<void> Function() onCaptureSelfie;
  final VoidCallback onClearSelfie;
  final bool submitting;
  final Map<String, dynamic> data;
  final Future<void> Function() onSubmit;
  final Future<void> Function()? onPreviousStep;
  final Future<void> Function()? onNextStep;

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final mediaQuery = MediaQuery.of(context);
    final form = Map<String, dynamic>.from(
      (data['form'] as Map?) ?? const <String, dynamic>{},
    );
    final today = Map<String, dynamic>.from(
      (data['today_attendance'] as Map?) ?? const <String, dynamic>{},
    );
    final recent = (data['recent'] as List?) ?? const [];
    final currentNow = DateTime.now();
    final verification = Map<String, dynamic>.from(
      (data['verification'] as Map?) ?? const <String, dynamic>{},
    );
    final isFaceScan =
        (verification['mode'] as String? ?? 'selfie') == 'face_scan';
    final nextModeLabel =
        form['next_mode_label'] as String? ?? 'Presensi Masuk';
    final verificationDescription = verification['description'] as String? ??
        'Presensi mobile memakai selfie kamera depan.';
    final hasLocation = position != null;
    final hasVerificationCapture =
        isFaceScan ? faceScanResult != null : selfieFile != null;
    final verificationLabel = isFaceScan ? 'Scan Wajah' : 'Selfie';
    final verificationActionLabel = isFaceScan ? 'Buka Scan' : 'Buka Kamera';
    final verificationRetryLabel = isFaceScan ? 'Ulangi Scan' : 'Ambil Ulang';
    final verificationReadyText = isFaceScan
        ? 'Scan wajah biometrik sudah siap digunakan.'
        : 'Selfie sudah siap digunakan untuk presensi.';
    final verificationPendingText =
        isFaceScan ? verificationDescription : verificationDescription;
    final faceScanPreview = _previewBytesFromDataUrl(
      faceScanResult?['selfie_data'] as String?,
    );

    return Container(
      decoration: const BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.vertical(top: Radius.circular(30)),
      ),
      child: Padding(
        padding: EdgeInsets.fromLTRB(
          18,
          14,
          18,
          16 + mediaQuery.padding.bottom,
        ),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Container(
              width: 42,
              height: 5,
              decoration: BoxDecoration(
                color: const Color(0xFFD7DEE6),
                borderRadius: BorderRadius.circular(999),
              ),
            ),
            const SizedBox(height: 14),
            Row(
              children: [
                Text(
                  nextModeLabel,
                  style: theme.textTheme.titleMedium?.copyWith(
                    color: _attendanceText,
                    fontWeight: FontWeight.w800,
                    fontSize: 15,
                  ),
                ),
                const Spacer(),
                Text(
                  'Langkah ${currentStep + 1}/3',
                  style: theme.textTheme.bodySmall?.copyWith(
                    color: _attendanceMuted,
                    fontWeight: FontWeight.w700,
                    fontSize: 10.5,
                  ),
                ),
              ],
            ),
            const SizedBox(height: 12),
            Row(
              children: List.generate(
                3,
                (index) => Expanded(
                  child: AnimatedContainer(
                    duration: const Duration(milliseconds: 180),
                    height: 5,
                    margin: EdgeInsets.only(right: index == 2 ? 0 : 6),
                    decoration: BoxDecoration(
                      color: index <= currentStep
                          ? _attendancePrimary
                          : const Color(0xFFE7EBF0),
                      borderRadius: BorderRadius.circular(999),
                    ),
                  ),
                ),
              ),
            ),
            const SizedBox(height: 16),
            SizedBox(
              height: mediaQuery.size.height * 0.44,
              child: PageView(
                controller: pageController,
                physics: const NeverScrollableScrollPhysics(),
                onPageChanged: onStepChange,
                children: [
                  _AttendanceStepCard(
                    title: '1. Ambil Lokasi',
                    subtitle:
                        'Pastikan GPS aktif. Setelah lokasi terbaca, langkah akan lanjut otomatis.',
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        if (position != null)
                          ClipRRect(
                            borderRadius: BorderRadius.circular(18),
                            child: SizedBox(
                              height: 148,
                              width: double.infinity,
                              child: FlutterMap(
                                options: MapOptions(
                                  initialCenter: LatLng(
                                    position!.latitude,
                                    position!.longitude,
                                  ),
                                  initialZoom: 16,
                                ),
                                children: [
                                  TileLayer(
                                    urlTemplate:
                                        'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
                                    userAgentPackageName:
                                        'nuist_flutter_mobile',
                                  ),
                                  MarkerLayer(
                                    markers: [
                                      Marker(
                                        point: LatLng(
                                          position!.latitude,
                                          position!.longitude,
                                        ),
                                        width: 38,
                                        height: 38,
                                        child: Container(
                                          decoration: const BoxDecoration(
                                            color: _attendancePrimary,
                                            shape: BoxShape.circle,
                                          ),
                                          child: const Icon(
                                            Icons.location_on_rounded,
                                            color: Colors.white,
                                            size: 20,
                                          ),
                                        ),
                                      ),
                                    ],
                                  ),
                                ],
                              ),
                            ),
                          )
                        else
                          Container(
                            height: 148,
                            width: double.infinity,
                            decoration: BoxDecoration(
                              color: const Color(0xFFF4F7FA),
                              borderRadius: BorderRadius.circular(18),
                            ),
                            child: const Center(
                              child: Icon(
                                Icons.map_outlined,
                                size: 32,
                                color: _attendanceMuted,
                              ),
                            ),
                          ),
                        const SizedBox(height: 12),
                        Text(
                          locationError?.trim().isNotEmpty == true
                              ? locationError!
                              : (locationAddress?.trim().isNotEmpty == true
                                  ? locationAddress!
                                  : hasLocation
                                      ? _coordinateLabel(position!)
                                      : 'Lokasi belum tersedia.'),
                          style: theme.textTheme.bodySmall?.copyWith(
                            color: locationError?.trim().isNotEmpty == true
                                ? const Color(0xFFB42318)
                                : _attendanceText,
                            fontWeight: FontWeight.w700,
                            fontSize: 11,
                            height: 1.4,
                          ),
                        ),
                        if (hasLocation) ...[
                          const SizedBox(height: 6),
                          Text(
                            'Akurasi ${position!.accuracy.toStringAsFixed(1)} m • Sampel GPS $locationReadingsCount',
                            style: theme.textTheme.bodySmall?.copyWith(
                              color: _attendanceMuted,
                              fontSize: 10.5,
                              fontWeight: FontWeight.w700,
                            ),
                          ),
                        ],
                        const SizedBox(height: 14),
                        SizedBox(
                          width: double.infinity,
                          child: FilledButton.tonalIcon(
                            onPressed: loadingLocation
                                ? null
                                : () async {
                                    await onCaptureLocation();
                                  },
                            icon: loadingLocation
                                ? const SizedBox(
                                    width: 16,
                                    height: 16,
                                    child: CircularProgressIndicator(
                                      strokeWidth: 2,
                                    ),
                                  )
                                : const Icon(Icons.my_location_rounded),
                            label: Text(
                              hasLocation ? 'Perbarui Lokasi' : 'Ambil Lokasi',
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                  _AttendanceStepCard(
                    title: isFaceScan ? '2. Scan Wajah' : '2. Ambil Selfie',
                    subtitle: isFaceScan
                        ? 'Lakukan verifikasi biometrik. Setelah scan berhasil, langkah akan lanjut otomatis.'
                        : 'Gunakan kamera depan. Setelah foto berhasil, langkah akan lanjut otomatis.',
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        ClipRRect(
                          borderRadius: BorderRadius.circular(18),
                          child: AspectRatio(
                            aspectRatio: 16 / 10,
                            child: !hasVerificationCapture
                                ? Container(
                                    color: const Color(0xFFF4F7FA),
                                    child: Center(
                                      child: Icon(
                                        isFaceScan
                                            ? Icons
                                                .face_retouching_natural_rounded
                                            : Icons.photo_camera_front_rounded,
                                        size: 34,
                                        color: _attendanceMuted,
                                      ),
                                    ),
                                  )
                                : isFaceScan
                                    ? (faceScanPreview == null
                                        ? Container(
                                            color: _attendancePrimarySoft,
                                            alignment: Alignment.center,
                                            child: const Icon(
                                              Icons.verified_user_rounded,
                                              size: 36,
                                              color: _attendancePrimaryDark,
                                            ),
                                          )
                                        : Image.memory(
                                            faceScanPreview,
                                            fit: BoxFit.cover,
                                          ))
                                    : Image.file(
                                        File(selfieFile!.path),
                                        fit: BoxFit.cover,
                                      ),
                          ),
                        ),
                        const SizedBox(height: 12),
                        Text(
                          hasVerificationCapture
                              ? verificationReadyText
                              : verificationPendingText,
                          style: theme.textTheme.bodySmall?.copyWith(
                            color: _attendanceText,
                            fontWeight: FontWeight.w700,
                            fontSize: 11,
                            height: 1.4,
                          ),
                        ),
                        const SizedBox(height: 14),
                        Row(
                          children: [
                            Expanded(
                              child: FilledButton.tonalIcon(
                                onPressed: capturingSelfie
                                    ? null
                                    : () async {
                                        await onCaptureSelfie();
                                      },
                                icon: capturingSelfie
                                    ? const SizedBox(
                                        width: 16,
                                        height: 16,
                                        child: CircularProgressIndicator(
                                          strokeWidth: 2,
                                        ),
                                      )
                                    : Icon(
                                        isFaceScan
                                            ? Icons
                                                .face_retouching_natural_rounded
                                            : Icons.camera_alt_rounded,
                                      ),
                                label: Text(
                                  hasVerificationCapture
                                      ? verificationRetryLabel
                                      : verificationActionLabel,
                                ),
                              ),
                            ),
                            if (hasVerificationCapture) ...[
                              const SizedBox(width: 10),
                              IconButton.filledTonal(
                                onPressed: onClearSelfie,
                                icon: const Icon(Icons.delete_outline_rounded),
                              ),
                            ],
                          ],
                        ),
                      ],
                    ),
                  ),
                  _AttendanceStepCard(
                    title: '3. Konfirmasi Presensi',
                    subtitle:
                        'Periksa lokasi dan selfie. Jika sudah benar, langsung kirim presensi.',
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        _AttendanceSummaryRow(
                          label: 'Status',
                          value: today['status_label'] as String? ??
                              'Belum presensi',
                        ),
                        _AttendanceSummaryRow(
                          label: 'Jam Masuk',
                          value: _resolveAttendanceDisplayTime(
                            today,
                            form: form,
                            recent: recent,
                            now: currentNow,
                            startTime: null,
                            isCheckIn: true,
                          ),
                        ),
                        _AttendanceSummaryRow(
                          label: 'Jam Pulang',
                          value: _resolveAttendanceDisplayTime(
                            today,
                            form: form,
                            recent: recent,
                            now: currentNow,
                            startTime: null,
                            isCheckIn: false,
                          ),
                        ),
                        _AttendanceSummaryRow(
                          label: 'Lokasi',
                          value: hasLocation ? 'Siap' : 'Belum lengkap',
                        ),
                        _AttendanceSummaryRow(
                          label: verificationLabel,
                          value:
                              hasVerificationCapture ? 'Siap' : 'Belum lengkap',
                        ),
                        const SizedBox(height: 12),
                        Container(
                          width: double.infinity,
                          padding: const EdgeInsets.all(12),
                          decoration: BoxDecoration(
                            color: const Color(0xFFF4F7FA),
                            borderRadius: BorderRadius.circular(16),
                          ),
                          child: Text(
                            hasVerificationCapture
                                ? '$verificationLabel sudah siap. Tinggal klik $nextModeLabel.'
                                : '$verificationLabel belum ada. Kembali ke langkah sebelumnya jika perlu.',
                            style: theme.textTheme.bodySmall?.copyWith(
                              color: _attendanceText,
                              fontSize: 10.5,
                              fontWeight: FontWeight.w700,
                              height: 1.4,
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 16),
            Row(
              children: [
                if (currentStep > 0)
                  Expanded(
                    child: OutlinedButton(
                      onPressed: onPreviousStep == null
                          ? null
                          : () async {
                              await onPreviousStep!.call();
                            },
                      child: const Text('Kembali'),
                    ),
                  ),
                if (currentStep > 0) const SizedBox(width: 10),
                Expanded(
                  child: FilledButton(
                    onPressed: currentStep == 2
                        ? (hasLocation && hasVerificationCapture && !submitting
                            ? () async {
                                await onSubmit();
                              }
                            : null)
                        : (currentStep == 0 && hasLocation) ||
                                (currentStep == 1 && hasVerificationCapture)
                            ? () async {
                                await onNextStep?.call();
                              }
                            : null,
                    style: FilledButton.styleFrom(
                      backgroundColor: _attendancePrimary,
                      foregroundColor: Colors.white,
                      padding: const EdgeInsets.symmetric(vertical: 15),
                    ),
                    child: currentStep == 2 && submitting
                        ? const SizedBox(
                            width: 18,
                            height: 18,
                            child: CircularProgressIndicator(
                              strokeWidth: 2,
                              color: Colors.white,
                            ),
                          )
                        : Text(
                            currentStep == 2
                                ? nextModeLabel
                                : currentStep == 0
                                    ? 'Lanjut ke ${isFaceScan ? 'Scan' : 'Selfie'}'
                                    : 'Lanjut ke Konfirmasi',
                            style: const TextStyle(
                              fontWeight: FontWeight.w800,
                              fontSize: 12.5,
                            ),
                          ),
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }
}

class _AttendanceStepCard extends StatelessWidget {
  const _AttendanceStepCard({
    required this.title,
    required this.subtitle,
    required this.child,
  });

  final String title;
  final String subtitle;
  final Widget child;

  @override
  Widget build(BuildContext context) {
    return SingleChildScrollView(
      child: Container(
        width: double.infinity,
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: const Color(0xFFFBFCFD),
          borderRadius: BorderRadius.circular(22),
          border: Border.all(color: const Color(0xFFE8EDF3)),
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              title,
              style: Theme.of(context).textTheme.titleSmall?.copyWith(
                    color: _attendanceText,
                    fontWeight: FontWeight.w800,
                    fontSize: 14,
                  ),
            ),
            const SizedBox(height: 4),
            Text(
              subtitle,
              style: Theme.of(context).textTheme.bodySmall?.copyWith(
                    color: _attendanceMuted,
                    fontSize: 10.5,
                    fontWeight: FontWeight.w600,
                    height: 1.4,
                  ),
            ),
            const SizedBox(height: 14),
            child,
          ],
        ),
      ),
    );
  }
}

class _AttendanceSummaryRow extends StatelessWidget {
  const _AttendanceSummaryRow({
    required this.label,
    required this.value,
  });

  final String label;
  final String value;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 10),
      child: Row(
        children: [
          Expanded(
            child: Text(
              label,
              style: Theme.of(context).textTheme.bodySmall?.copyWith(
                    color: _attendanceMuted,
                    fontSize: 10.5,
                    fontWeight: FontWeight.w700,
                  ),
            ),
          ),
          const SizedBox(width: 12),
          Flexible(
            child: Text(
              value,
              textAlign: TextAlign.end,
              style: Theme.of(context).textTheme.bodySmall?.copyWith(
                    color: _attendanceText,
                    fontSize: 11,
                    fontWeight: FontWeight.w800,
                  ),
            ),
          ),
        ],
      ),
    );
  }
}

class _AttendanceCenterStatus extends StatelessWidget {
  const _AttendanceCenterStatus({
    required this.statusLabel,
    required this.subtitle,
    required this.metric,
    required this.metricLabel,
  });

  final String statusLabel;
  final String subtitle;
  final String? metric;
  final String metricLabel;

  @override
  Widget build(BuildContext context) {
    final displaySubtitle = (metric != null ? metricLabel : subtitle).trim();

    return Container(
      constraints: const BoxConstraints(minWidth: 104, maxWidth: 120),
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 9),
      decoration: BoxDecoration(
        color: _attendancePrimarySoft,
        borderRadius: BorderRadius.circular(18),
        border: Border.all(color: _attendancePrimaryBorder),
      ),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          if (metric != null) ...[
            Text(
              metric!,
              textAlign: TextAlign.center,
              style: Theme.of(context).textTheme.labelMedium?.copyWith(
                    color: _attendanceText,
                    fontWeight: FontWeight.w800,
                    fontSize: 10,
                  ),
            ),
            const SizedBox(height: 2),
          ],
          Text(
            statusLabel,
            textAlign: TextAlign.center,
            maxLines: 2,
            overflow: TextOverflow.ellipsis,
            style: Theme.of(context).textTheme.bodySmall?.copyWith(
                  color: _attendanceText,
                  fontWeight: FontWeight.w800,
                  fontSize: metric == null ? 10 : 11,
                  height: 1.25,
                ),
          ),
          if (displaySubtitle.isNotEmpty) ...[
            const SizedBox(height: 4),
            Text(
              displaySubtitle,
              textAlign: TextAlign.center,
              maxLines: 1,
              overflow: TextOverflow.ellipsis,
              style: Theme.of(context).textTheme.labelSmall?.copyWith(
                    color: _attendanceMuted,
                    fontWeight: FontWeight.w700,
                    fontSize: 9,
                  ),
            ),
          ],
        ],
      ),
    );
  }
}

class _AttendanceMapLayer extends StatefulWidget {
  const _AttendanceMapLayer({
    required this.center,
    required this.userPosition,
    required this.schoolName,
    required this.schoolLatitude,
    required this.schoolLongitude,
    required this.loadingLocation,
  });

  final LatLng center;
  final Position? userPosition;
  final String schoolName;
  final double? schoolLatitude;
  final double? schoolLongitude;
  final bool loadingLocation;

  @override
  State<_AttendanceMapLayer> createState() => _AttendanceMapLayerState();
}

class _AttendanceMapLayerState extends State<_AttendanceMapLayer> {
  late final MapController _mapController;

  @override
  void initState() {
    super.initState();
    _mapController = MapController();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      _syncViewport();
    });
  }

  @override
  void didUpdateWidget(covariant _AttendanceMapLayer oldWidget) {
    super.didUpdateWidget(oldWidget);
    if (_viewportChanged(oldWidget, widget)) {
      WidgetsBinding.instance.addPostFrameCallback((_) {
        _syncViewport();
      });
    }
  }

  bool _viewportChanged(
    _AttendanceMapLayer oldWidget,
    _AttendanceMapLayer newWidget,
  ) {
    return oldWidget.center.latitude != newWidget.center.latitude ||
        oldWidget.center.longitude != newWidget.center.longitude ||
        oldWidget.userPosition?.latitude != newWidget.userPosition?.latitude ||
        oldWidget.userPosition?.longitude !=
            newWidget.userPosition?.longitude ||
        oldWidget.schoolLatitude != newWidget.schoolLatitude ||
        oldWidget.schoolLongitude != newWidget.schoolLongitude;
  }

  void _syncViewport() {
    if (!mounted) {
      return;
    }

    _mapController.move(
      widget.center,
      widget.userPosition != null
          ? _attendanceMapUserZoom
          : _attendanceMapDefaultZoom,
    );
  }

  @override
  Widget build(BuildContext context) {
    final routePoints = <LatLng>[
      if (widget.schoolLatitude != null && widget.schoolLongitude != null)
        LatLng(widget.schoolLatitude!, widget.schoolLongitude!),
      if (widget.userPosition != null)
        LatLng(widget.userPosition!.latitude, widget.userPosition!.longitude),
    ];
    final markers = <Marker>[
      if (widget.schoolLatitude != null && widget.schoolLongitude != null)
        Marker(
          point: LatLng(widget.schoolLatitude!, widget.schoolLongitude!),
          width: 140,
          height: 58,
          child: Column(
            children: [
              Container(
                padding: const EdgeInsets.symmetric(
                  horizontal: 10,
                  vertical: 6,
                ),
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(999),
                  boxShadow: [
                    BoxShadow(
                      color: Colors.black.withOpacity(0.12),
                      blurRadius: 16,
                      offset: const Offset(0, 8),
                    ),
                  ],
                ),
                child: Text(
                  widget.schoolName,
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                  style: const TextStyle(
                    color: _attendanceText,
                    fontSize: 11,
                    fontWeight: FontWeight.w800,
                  ),
                ),
              ),
              const SizedBox(height: 4),
              const Icon(
                Icons.location_on_rounded,
                color: _attendancePrimaryDark,
                size: 28,
              ),
            ],
          ),
        ),
      if (widget.userPosition != null)
        Marker(
          point: LatLng(
            widget.userPosition!.latitude,
            widget.userPosition!.longitude,
          ),
          width: 48,
          height: 48,
          child: Container(
            decoration: BoxDecoration(
              shape: BoxShape.circle,
              color: _attendancePrimary.withOpacity(0.18),
            ),
            padding: const EdgeInsets.all(6),
            child: Container(
              decoration: const BoxDecoration(
                color: _attendancePrimary,
                shape: BoxShape.circle,
              ),
              child: const Icon(
                Icons.my_location_rounded,
                color: Colors.white,
                size: 20,
              ),
            ),
          ),
        ),
    ];

    return Stack(
      children: [
        FlutterMap(
          mapController: _mapController,
          options: MapOptions(
            initialCenter: widget.center,
            initialZoom: widget.userPosition != null
                ? _attendanceMapUserZoom
                : _attendanceMapDefaultZoom,
            interactionOptions: const InteractionOptions(
              flags: InteractiveFlag.drag |
                  InteractiveFlag.pinchZoom |
                  InteractiveFlag.doubleTapZoom,
            ),
          ),
          children: [
            TileLayer(
              urlTemplate: 'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
              userAgentPackageName: 'nuist_flutter_mobile',
            ),
            if (routePoints.length >= 2)
              PolylineLayer(
                polylines: [
                  Polyline(
                    points: routePoints,
                    strokeWidth: 4,
                    color: _attendancePrimary.withOpacity(0.7),
                  ),
                ],
              ),
            if (markers.isNotEmpty) MarkerLayer(markers: markers),
          ],
        ),
        Positioned.fill(
          child: IgnorePointer(
            child: DecoratedBox(
              decoration: BoxDecoration(
                gradient: LinearGradient(
                  begin: Alignment.topCenter,
                  end: Alignment.bottomCenter,
                  colors: [
                    Colors.transparent,
                    Colors.black.withOpacity(0.02),
                    Colors.black.withOpacity(0.14),
                  ],
                ),
              ),
            ),
          ),
        ),
        if (widget.loadingLocation)
          const Positioned(
            left: 0,
            right: 0,
            bottom: 260,
            child: Center(
              child: Card(
                child: Padding(
                  padding: EdgeInsets.symmetric(horizontal: 12, vertical: 10),
                  child: Row(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      SizedBox(
                        width: 16,
                        height: 16,
                        child: CircularProgressIndicator(strokeWidth: 2),
                      ),
                      SizedBox(width: 10),
                      Text(
                        'Membaca lokasi...',
                        style: TextStyle(
                          fontSize: 12,
                          fontWeight: FontWeight.w700,
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            ),
          ),
      ],
    );
  }
}

class _MapHeaderButton extends StatelessWidget {
  const _MapHeaderButton({
    required this.icon,
    this.onTap,
  });

  final IconData icon;
  final VoidCallback? onTap;

  @override
  Widget build(BuildContext context) {
    return Material(
      color: Colors.white.withOpacity(0.95),
      borderRadius: BorderRadius.circular(999),
      child: InkWell(
        borderRadius: BorderRadius.circular(999),
        onTap: onTap,
        child: SizedBox(
          width: 36,
          height: 36,
          child: Icon(
            icon,
            color: _attendanceText,
            size: 18,
          ),
        ),
      ),
    );
  }
}

class _AttendanceTopMetric extends StatelessWidget {
  const _AttendanceTopMetric({
    required this.title,
    required this.value,
    required this.caption,
    this.align = CrossAxisAlignment.start,
    this.textAlign = TextAlign.start,
  });

  final String title;
  final String value;
  final String caption;
  final CrossAxisAlignment align;
  final TextAlign textAlign;

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: align,
      children: [
        Text(
          title,
          textAlign: textAlign,
          style: Theme.of(context).textTheme.labelSmall?.copyWith(
                color: _attendanceMuted,
                fontWeight: FontWeight.w700,
                fontSize: 10,
              ),
        ),
        const SizedBox(height: 4),
        Text(
          value,
          textAlign: textAlign,
          style: Theme.of(context).textTheme.titleMedium?.copyWith(
                color: _attendanceText,
                fontWeight: FontWeight.w900,
                fontSize: 17,
              ),
        ),
        const SizedBox(height: 3),
        Text(
          caption,
          textAlign: textAlign,
          style: Theme.of(context).textTheme.bodySmall?.copyWith(
                color: _attendanceMuted,
                fontWeight: FontWeight.w600,
                fontSize: 10,
              ),
        ),
      ],
    );
  }
}

class _PageLoading extends StatelessWidget {
  const _PageLoading();

  @override
  Widget build(BuildContext context) {
    return const Center(
      child: Padding(
        padding: EdgeInsets.only(top: 120),
        child: CircularProgressIndicator(
          color: _attendancePrimary,
        ),
      ),
    );
  }
}

class _PageError extends StatelessWidget {
  const _PageError({
    required this.message,
    required this.onRetry,
  });

  final String message;
  final Future<void> Function() onRetry;

  @override
  Widget build(BuildContext context) {
    return AppSectionCard(
      child: Column(
        children: [
          Text(
            message,
            textAlign: TextAlign.center,
            style: const TextStyle(
              color: Color(0xFF9F1239),
              fontWeight: FontWeight.w700,
            ),
          ),
          const SizedBox(height: 12),
          OutlinedButton(
            onPressed: onRetry,
            child: const Text('Coba Lagi'),
          ),
        ],
      ),
    );
  }
}

String _coordinateLabel(Position position) {
  return 'Lat ${position.latitude.toStringAsFixed(6)}, Lng ${position.longitude.toStringAsFixed(6)}';
}

LatLng _mapViewportCenter({
  required Position? userPosition,
  required double? schoolLatitude,
  required double? schoolLongitude,
}) {
  if (userPosition != null) {
    return LatLng(userPosition.latitude, userPosition.longitude);
  }

  final points = <LatLng>[
    if (schoolLatitude != null && schoolLongitude != null)
      LatLng(schoolLatitude, schoolLongitude),
  ];

  if (points.isEmpty) {
    return const LatLng(-6.200000, 106.816666);
  }

  if (points.length == 1) {
    return points.first;
  }

  final latitudes = points.map((point) => point.latitude).toList()..sort();
  final longitudes = points.map((point) => point.longitude).toList()..sort();
  final minLat = latitudes.first;
  final maxLat = latitudes.last;
  final minLng = longitudes.first;
  final maxLng = longitudes.last;
  return LatLng((minLat + maxLat) / 2, (minLng + maxLng) / 2);
}

double? _findNumericValue(
  Iterable<Map<String, dynamic>> maps,
  List<String> keys,
) {
  for (final map in maps) {
    for (final key in keys) {
      final value = _toDouble(map[key]);
      if (value != null) {
        return value;
      }
    }
  }

  return null;
}

double? _toDouble(dynamic value) {
  if (value is num) {
    return value.toDouble();
  }

  if (value is String) {
    return double.tryParse(value.trim());
  }

  return null;
}

String _formatRunningTime(DateTime value) {
  final base = _formatHourMinute(value);
  final second = value.second.toString().padLeft(2, '0');
  return '$base:$second';
}

String _formatAttendanceTime(dynamic value) {
  if (value is Map) {
    for (final key in const [
      'time',
      'formatted_time',
      'check_time',
      'value',
    ]) {
      final nested = value[key];
      final formattedNested = _formatAttendanceTime(nested);
      if (formattedNested != '--:--') {
        return formattedNested;
      }
    }
    return '--:--';
  }

  final raw = value?.toString().trim() ?? '';
  if (raw.isEmpty) {
    return '--:--';
  }

  final parsed = DateTime.tryParse(raw);
  if (parsed != null) {
    final hour = parsed.hour.toString().padLeft(2, '0');
    final minute = parsed.minute.toString().padLeft(2, '0');
    return '$hour:$minute';
  }

  final match = RegExp(r'(\d{1,2}:\d{2})(?::(\d{2}))?').firstMatch(raw);
  if (match != null) {
    final base = match.group(1);
    if (base == null) {
      return '--:--';
    }
    return base;
  }

  return '--:--';
}

String _resolveAttendanceDisplayTime(
  Map<String, dynamic> today, {
  required Map<String, dynamic> form,
  required List recent,
  required DateTime now,
  required String? startTime,
  required bool isCheckIn,
}) {
  final directKeys = isCheckIn
      ? const ['check_in', 'waktu_masuk', 'checkIn']
      : const ['check_out', 'waktu_keluar', 'checkOut'];

  final directValue = _findFirstMapValue(today, directKeys);
  final directTime = _formatAttendanceTime(directValue);
  if (directTime != '--:--') {
    return directTime;
  }

  final formValue = _findFirstMapValue(
    form,
    isCheckIn
        ? const ['current_check_in', 'check_in', 'waktu_masuk']
        : const ['current_check_out', 'check_out', 'waktu_keluar'],
  );
  final formTime = _formatAttendanceTime(formValue);
  if (formTime != '--:--') {
    return formTime;
  }

  final nestedKeys = isCheckIn ? const ['masuk'] : const ['pulang', 'keluar'];
  for (final key in nestedKeys) {
    final nestedValue = today[key];
    final nestedTime = _formatAttendanceTime(nestedValue);
    if (nestedTime != '--:--') {
      return nestedTime;
    }
  }

  final entries = today['entries'];
  if (entries is List) {
    final iterable = isCheckIn ? entries : entries.reversed;
    for (final item in iterable) {
      if (item is! Map) {
        continue;
      }

      final entry = Map<String, dynamic>.from(item);
      final entryValue = _findFirstMapValue(entry, directKeys);
      final entryTime = _formatAttendanceTime(entryValue);
      if (entryTime != '--:--') {
        return entryTime;
      }

      for (final key in nestedKeys) {
        final nestedEntryTime = _formatAttendanceTime(entry[key]);
        if (nestedEntryTime != '--:--') {
          return nestedEntryTime;
        }
      }
    }
  }

  final todayDate = _formatIsoDate(now);
  for (final item in recent) {
    if (item is! Map) {
      continue;
    }

    final recentItem = Map<String, dynamic>.from(item);
    final dateValue = recentItem['date']?.toString().trim();
    if (dateValue != todayDate) {
      continue;
    }

    final recentValue = _findFirstMapValue(recentItem, directKeys);
    final recentTime = _formatAttendanceTime(recentValue);
    if (recentTime != '--:--') {
      return recentTime;
    }

    for (final key in nestedKeys) {
      final nestedRecentTime = _formatAttendanceTime(recentItem[key]);
      if (nestedRecentTime != '--:--') {
        return nestedRecentTime;
      }
    }
  }

  if (isCheckIn) {
    final inferredTime = _inferCheckInTimeFromNote(
      today: today,
      recent: recent,
      now: now,
      startTime: startTime,
    );
    if (inferredTime != null) {
      return inferredTime;
    }
  }

  return '--:--';
}

dynamic _findFirstMapValue(
  Map<String, dynamic> source,
  List<String> keys,
) {
  for (final key in keys) {
    if (source.containsKey(key) && source[key] != null) {
      return source[key];
    }
  }

  return null;
}

String? _inferCheckInTimeFromNote({
  required Map<String, dynamic> today,
  required List recent,
  required DateTime now,
  required String? startTime,
}) {
  final candidates = <String?>[
    today['note']?.toString(),
    if (today['entries'] is List)
      ...((today['entries'] as List)
          .whereType<Map>()
          .map((item) => item['note']?.toString())),
    ...recent.whereType<Map>().map((item) {
      final dateValue = item['date']?.toString().trim();
      if (dateValue != _formatIsoDate(now)) {
        return null;
      }
      return item['note']?.toString();
    }),
  ];

  final lateNote =
      candidates.whereType<String>().map((item) => item.trim()).firstWhere(
            (item) => item.toLowerCase().contains('terlambat'),
            orElse: () => '',
          );

  if (lateNote.isEmpty) {
    return null;
  }

  final minuteMatch =
      RegExp(r'(\d+)\s*menit', caseSensitive: false).firstMatch(lateNote);
  final lateMinutes =
      minuteMatch == null ? null : int.tryParse(minuteMatch[1]!);
  if (lateMinutes == null) {
    return null;
  }

  final parsedStart = _parseClockTime(startTime ?? '07:00');
  if (parsedStart == null) {
    return null;
  }

  final inferred = DateTime(
    now.year,
    now.month,
    now.day,
    parsedStart.$1,
    parsedStart.$2,
  ).add(Duration(minutes: lateMinutes));

  return _formatHourMinute(inferred);
}

(int, int)? _parseClockTime(String value) {
  final match = RegExp(r'(\d{1,2}):(\d{2})').firstMatch(value.trim());
  if (match == null) {
    return null;
  }

  final hour = int.tryParse(match.group(1)!);
  final minute = int.tryParse(match.group(2)!);
  if (hour == null || minute == null) {
    return null;
  }

  return (hour, minute);
}

String _formatHourMinute(DateTime value) {
  final hour = value.hour.toString().padLeft(2, '0');
  final minute = value.minute.toString().padLeft(2, '0');
  return '$hour:$minute';
}

String _formatCurrentDate(DateTime value) {
  const months = <String>[
    'Jan',
    'Feb',
    'Mar',
    'Apr',
    'Mei',
    'Jun',
    'Jul',
    'Agu',
    'Sep',
    'Okt',
    'Nov',
    'Des',
  ];

  return '${value.day} ${months[value.month - 1]} ${value.year}';
}

String _formatIsoDate(DateTime value) {
  final year = value.year.toString().padLeft(4, '0');
  final month = value.month.toString().padLeft(2, '0');
  final day = value.day.toString().padLeft(2, '0');
  return '$year-$month-$day';
}

Uint8List? _previewBytesFromDataUrl(String? value) {
  final raw = value?.trim() ?? '';
  if (raw.isEmpty) {
    return null;
  }

  final payload = raw.contains(',') ? raw.substring(raw.indexOf(',') + 1) : raw;

  try {
    return base64Decode(payload);
  } catch (_) {
    return null;
  }
}
