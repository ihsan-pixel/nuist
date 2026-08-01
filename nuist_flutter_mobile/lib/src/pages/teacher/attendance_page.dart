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

import '../../services/teacher_mobile_repository.dart';
import '../../widgets/app/app_empty_state.dart';
import '../../widgets/app/app_section_card.dart';
import '../../widgets/app/teacher_page_header.dart';

const _attendancePrimary = Color(0xFF1F6B52);
const _attendancePrimaryDark = Color(0xFF174C3D);
const _attendanceText = Color(0xFF1E463A);
const _attendanceMuted = Color(0xFF7A8F8C);
const _attendanceSoft = Color(0xFFEAF4EF);

class TeacherAttendancePage extends StatefulWidget {
  const TeacherAttendancePage({
    super.key,
    required this.repository,
    required this.onBackToHome,
    required this.isActive,
  });

  final TeacherMobileRepository repository;
  final VoidCallback onBackToHome;
  final bool isActive;

  @override
  State<TeacherAttendancePage> createState() => _TeacherAttendancePageState();
}

class _TeacherAttendancePageState extends State<TeacherAttendancePage>
    with WidgetsBindingObserver {
  final ImagePicker _imagePicker = ImagePicker();

  late Future<Map<String, dynamic>> _future;
  Position? _position;
  XFile? _selfieFile;
  String? _locationAddress;
  String? _locationError;
  List<Map<String, dynamic>> _locationReadings = const [];
  bool _loadingLocation = false;
  bool _capturingSelfie = false;
  bool _submitting = false;
  late DateTime _now;
  Timer? _clockTimer;

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addObserver(this);
    _future = widget.repository.getAttendance();
    _now = DateTime.now();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      _handlePageReactivated(refreshRemoteData: false);
    });
    _clockTimer = Timer.periodic(const Duration(seconds: 1), (_) {
      if (!mounted) {
        return;
      }
      setState(() {
        _now = DateTime.now();
      });
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
    _clockTimer?.cancel();
    WidgetsBinding.instance.removeObserver(this);
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

    setState(() {
      _now = DateTime.now();
    });

    if (refreshRemoteData) {
      await _refresh();
    }

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
        throw Exception('Izin lokasi ditolak. Presensi memerlukan akses lokasi.');
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

    if (verificationMode != 'selfie') {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text(
            'Mode scan wajah sedang aktif. Gunakan presensi mobile web sampai aplikasi native mendukung scan wajah.',
          ),
        ),
      );
      return false;
    }

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

    if (_selfieFile == null) {
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
      final payload = {
        'presensi_mode': mode,
        'latitude': _position!.latitude,
        'longitude': _position!.longitude,
        'lokasi': _locationAddress ?? _coordinateLabel(_position!),
        'accuracy': _position!.accuracy,
        'altitude': _position!.altitude,
        'speed': _position!.speed,
        'device_info': 'flutter_mobile_${defaultTargetPlatform.name}',
        'location_readings': _locationReadings,
        'selfie_data': await _buildSelfieData(_selfieFile!),
      };

      final result = await widget.repository.submitAttendance(payload: payload);
      if (!mounted) {
        return false;
      }

      setState(() {
        _selfieFile = null;
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

  Future<void> _openAttendanceSheet(Map<String, dynamic> data) async {
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
              if (mounted) {
                setModalState(() {});
              }
            }

            Future<void> handleCaptureSelfie() async {
              await _captureSelfie();
              if (mounted) {
                setModalState(() {});
              }
            }

            Future<void> handleSubmit() async {
              final success = await _submitAttendance(data);
              if (mounted) {
                setModalState(() {});
              }
              if (success && mounted) {
                Navigator.of(sheetContext).pop();
              }
            }

            return _AttendanceSubmitSheet(
              position: _position,
              locationError: _locationError,
              locationReadingsCount: _locationReadings.length,
              loadingLocation: _loadingLocation,
              onCaptureLocation: handleCaptureLocation,
              selfieFile: _selfieFile,
              capturingSelfie: _capturingSelfie,
              onCaptureSelfie: handleCaptureSelfie,
              onClearSelfie: () {
                setState(() {
                  _selfieFile = null;
                });
                setModalState(() {});
              },
              submitting: _submitting,
              data: data,
              onSubmit: handleSubmit,
            );
          },
        );
      },
    );
  }

  Future<String> _buildSelfieData(XFile file) async {
    final bytes = await file.readAsBytes();
    final lowerPath = file.path.toLowerCase();
    final mime = lowerPath.endsWith('.png') ? 'image/png' : 'image/jpeg';
    return 'data:$mime;base64,${base64Encode(bytes)}';
  }

  Future<bool?> _confirmEarlyCheckout({
    required String? pulangStart,
  }) {
    final pulangLabel =
        pulangStart != null && pulangStart.trim().isNotEmpty
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

  @override
  Widget build(BuildContext context) {
    return FutureBuilder<Map<String, dynamic>>(
      future: _future,
      builder: (context, snapshot) {
        return RefreshIndicator(
          onRefresh: _refresh,
          child: ListView(
            padding: const EdgeInsets.fromLTRB(16, 16, 16, 28),
            children: [
              TeacherPageHeader(
                title: 'Presensi',
                onBack: widget.onBackToHome,
              ),
              const SizedBox(height: 14),
              if (snapshot.connectionState == ConnectionState.waiting)
                const _PageLoading()
              else if (snapshot.hasError)
                _PageError(
                  message: snapshot.error.toString(),
                  onRetry: _refresh,
                )
              else
                _AttendanceContent(
                  data: snapshot.data ?? const <String, dynamic>{},
                  now: _now,
                  submitting: _submitting,
                  onSubmit: () => _openAttendanceSheet(
                    snapshot.data ?? const <String, dynamic>{},
                  ),
                ),
            ],
          ),
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
    required this.onSubmit,
  });

  final Map<String, dynamic> data;
  final DateTime now;
  final bool submitting;
  final VoidCallback onSubmit;

  @override
  Widget build(BuildContext context) {
    final today = Map<String, dynamic>.from(
      (data['today_attendance'] as Map?) ?? const <String, dynamic>{},
    );
    final form = Map<String, dynamic>.from(
      (data['form'] as Map?) ?? const <String, dynamic>{},
    );
    final timeRanges = Map<String, dynamic>.from(
      (data['time_ranges'] as Map?) ?? const <String, dynamic>{},
    );
    final verification = Map<String, dynamic>.from(
      (data['verification'] as Map?) ?? const <String, dynamic>{},
    );
    final recent = ((data['recent'] as List?) ?? const [])
        .whereType<Map>()
        .map((item) => Map<String, dynamic>.from(item))
        .toList();
    final canSubmit = form['can_submit'] == true;
    final nextModeLabel =
        form['next_mode_label'] as String? ?? 'Presensi Hari Ini';
    final verificationLabel = verification['label'] as String? ?? 'Selfie';
    final verificationDescription =
        verification['description'] as String? ??
        'Presensi mobile memakai selfie kamera depan.';
    final nativeSupportsVerification =
        (verification['mode'] as String? ?? 'selfie') == 'selfie';

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        _AttendanceHeroCard(
          teacherName: data['teacher_name'] as String? ?? '-',
          schoolName: data['school_name'] as String? ?? '-',
          todayLabel: data['today_label'] as String? ?? '-',
          currentTime: _timeLabel(now),
          statusLabel: today['status_label'] as String? ?? 'Belum presensi',
          nextModeLabel: nextModeLabel,
          status: today['status'] as String? ?? 'belum_presensi',
        ),
        const SizedBox(height: 18),
        _AttendanceActionSection(
          canSubmit: canSubmit,
          nativeSupportsVerification: nativeSupportsVerification,
          verificationLabel: verificationLabel,
          verificationDescription: verificationDescription,
          nextModeLabel: nextModeLabel,
          blockedMessage: form['blocked_message'] as String?,
          timeRanges: timeRanges,
          submitting: submitting,
          onSubmit: onSubmit,
          onOpenHistory: () {
            Navigator.of(context).push(
              MaterialPageRoute<void>(
                builder: (_) => _AttendanceHistoryPage(items: recent),
              ),
            );
          },
        ),
        const SizedBox(height: 18),
        _GuidanceCard(
          guidance: ((form['guidance'] as List?) ?? const [])
              .whereType<String>()
              .toList(),
        ),
      ],
    );
  }
}

class _AttendanceHeroCard extends StatelessWidget {
  const _AttendanceHeroCard({
    required this.teacherName,
    required this.schoolName,
    required this.todayLabel,
    required this.currentTime,
    required this.statusLabel,
    required this.nextModeLabel,
    required this.status,
  });

  final String teacherName;
  final String schoolName;
  final String todayLabel;
  final String currentTime;
  final String statusLabel;
  final String nextModeLabel;
  final String status;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: _attendancePrimaryDark,
        borderRadius: BorderRadius.circular(24),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Expanded(
                child: Text(
                  todayLabel,
                  style: TextStyle(
                    color: Colors.white.withOpacity(0.82),
                    fontSize: 11,
                    fontWeight: FontWeight.w700,
                  ),
                ),
              ),
              Container(
                padding:
                    const EdgeInsets.symmetric(horizontal: 10, vertical: 7),
                decoration: BoxDecoration(
                  color: Colors.white.withOpacity(0.18),
                  borderRadius: BorderRadius.circular(999),
                ),
                child: Text(
                  currentTime,
                  style: const TextStyle(
                    color: Colors.white,
                    fontWeight: FontWeight.w800,
                    fontSize: 11,
                  ),
                ),
              ),
            ],
          ),
          const SizedBox(height: 10),
          Text(
            teacherName,
            style: const TextStyle(
              color: Colors.white,
              fontSize: 20,
              fontWeight: FontWeight.w800,
              height: 1.05,
            ),
          ),
          const SizedBox(height: 4),
          Text(
            schoolName,
            style: TextStyle(
              color: Colors.white.withOpacity(0.85),
              fontSize: 12,
              fontWeight: FontWeight.w700,
            ),
          ),
          const SizedBox(height: 14),
          Wrap(
            spacing: 8,
            runSpacing: 8,
            children: [
              _HeroInfoPill(
                icon: Icons.verified_user_outlined,
                label: statusLabel,
              ),
              _HeroInfoPill(
                icon: Icons.bolt_rounded,
                label: nextModeLabel,
              ),
            ],
          ),
        ],
      ),
    );
  }
}

class _HeroInfoPill extends StatelessWidget {
  const _HeroInfoPill({
    required this.icon,
    required this.label,
  });

  final IconData icon;
  final String label;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 9),
      decoration: BoxDecoration(
        color: Colors.white.withOpacity(0.16),
        borderRadius: BorderRadius.circular(999),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(
            icon,
            color: Colors.white,
            size: 16,
          ),
          const SizedBox(width: 8),
          Text(
            label,
            style: const TextStyle(
              color: Colors.white,
              fontWeight: FontWeight.w800,
              fontSize: 11,
            ),
          ),
        ],
      ),
    );
  }
}

class _AttendanceActionSection extends StatelessWidget {
  const _AttendanceActionSection({
    required this.canSubmit,
    required this.nativeSupportsVerification,
    required this.verificationLabel,
    required this.verificationDescription,
    required this.nextModeLabel,
    required this.blockedMessage,
    required this.timeRanges,
    required this.submitting,
    required this.onSubmit,
    required this.onOpenHistory,
  });

  final bool canSubmit;
  final bool nativeSupportsVerification;
  final String verificationLabel;
  final String verificationDescription;
  final String nextModeLabel;
  final String? blockedMessage;
  final Map<String, dynamic> timeRanges;
  final bool submitting;
  final VoidCallback onSubmit;
  final VoidCallback onOpenHistory;

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        const _PageSectionHeading(
          eyebrow: 'Aksi',
          title: 'Presensi',
        ),
        const SizedBox(height: 10),
        Text(
          '$verificationLabel aktif. $verificationDescription',
          style: const TextStyle(
            color: _attendanceMuted,
            fontSize: 12,
            height: 1.4,
          ),
        ),
        const SizedBox(height: 10),
        Wrap(
          spacing: 8,
          runSpacing: 8,
          children: [
            _RangeChip(label: nextModeLabel),
            if ((timeRanges['masuk_start'] as String?)?.trim().isNotEmpty == true)
              _RangeChip(label: 'Masuk ${timeRanges['masuk_start']}'),
            if ((timeRanges['pulang_start'] as String?)?.trim().isNotEmpty == true)
              _RangeChip(label: 'Pulang ${timeRanges['pulang_start']}'),
          ],
        ),
        const SizedBox(height: 10),
        Text(
          canSubmit
              ? (nativeSupportsVerification
                  ? 'Lokasi dan selfie diisi saat tombol presensi dibuka.'
                  : 'Submit dari aplikasi native dinonaktifkan saat mode scan wajah aktif.')
              : (blockedMessage ?? 'Presensi untuk hari ini belum dapat dilakukan.'),
          style: TextStyle(
            color: !canSubmit ? const Color(0xFFB42318) : _attendanceMuted,
            fontSize: 12,
            height: 1.4,
            fontWeight: !canSubmit ? FontWeight.w700 : FontWeight.w600,
          ),
        ),
        const SizedBox(height: 14),
        SizedBox(
          width: double.infinity,
          child: ElevatedButton.icon(
            onPressed: canSubmit && nativeSupportsVerification && !submitting
                ? onSubmit
                : null,
            style: ElevatedButton.styleFrom(
              backgroundColor: _attendancePrimary,
              disabledBackgroundColor: const Color(0xFFD2E4DC),
              foregroundColor: Colors.white,
              padding: const EdgeInsets.symmetric(vertical: 14),
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(16),
              ),
              elevation: 0,
            ),
            icon: submitting
                ? const SizedBox(
                    width: 18,
                    height: 18,
                    child: CircularProgressIndicator(
                      strokeWidth: 2.2,
                      color: Colors.white,
                    ),
                  )
                : const Icon(Icons.check_circle_outline_rounded),
            label: Text(
              submitting ? 'Mengirim...' : nextModeLabel,
              style: const TextStyle(
                fontWeight: FontWeight.w800,
              ),
            ),
          ),
        ),
        const SizedBox(height: 10),
        SizedBox(
          width: double.infinity,
          child: OutlinedButton.icon(
            onPressed: onOpenHistory,
            style: OutlinedButton.styleFrom(
              foregroundColor: _attendancePrimary,
              side: const BorderSide(color: Color(0xFFD7E3DD)),
              padding: const EdgeInsets.symmetric(vertical: 14, horizontal: 14),
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(16),
              ),
            ),
            icon: const Icon(Icons.history_rounded, size: 18),
            label: const Text(
              'Cek Riwayat Presensi',
              style: TextStyle(
                fontWeight: FontWeight.w800,
                fontSize: 13,
              ),
            ),
          ),
        ),
      ],
    );
  }
}

class _RangeChip extends StatelessWidget {
  const _RangeChip({
    required this.label,
  });

  final String label;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 7),
      decoration: BoxDecoration(
        color: const Color(0xFFF3F7F4),
        borderRadius: BorderRadius.circular(999),
      ),
      child: Text(
        label,
        style: const TextStyle(
          color: Colors.black,
          fontSize: 11,
          fontWeight: FontWeight.w700,
        ),
      ),
    );
  }
}

class _AttendanceSubmitSheet extends StatelessWidget {
  const _AttendanceSubmitSheet({
    required this.position,
    required this.locationError,
    required this.locationReadingsCount,
    required this.loadingLocation,
    required this.onCaptureLocation,
    required this.selfieFile,
    required this.capturingSelfie,
    required this.onCaptureSelfie,
    required this.onClearSelfie,
    required this.submitting,
    required this.data,
    required this.onSubmit,
  });

  final Position? position;
  final String? locationError;
  final int locationReadingsCount;
  final bool loadingLocation;
  final Future<void> Function() onCaptureLocation;
  final XFile? selfieFile;
  final bool capturingSelfie;
  final Future<void> Function() onCaptureSelfie;
  final VoidCallback onClearSelfie;
  final bool submitting;
  final Map<String, dynamic> data;
  final Future<void> Function() onSubmit;

  @override
  Widget build(BuildContext context) {
    final verification = Map<String, dynamic>.from(
      (data['verification'] as Map?) ?? const <String, dynamic>{},
    );
    final form = Map<String, dynamic>.from(
      (data['form'] as Map?) ?? const <String, dynamic>{},
    );
    final verificationMode = verification['mode'] as String? ?? 'selfie';
    final verificationLabel = verification['label'] as String? ?? 'Selfie';
    final verificationDescription =
        verification['description'] as String? ??
        'Presensi mobile memakai selfie kamera depan.';
    final canSubmit = form['can_submit'] == true;
    final hasLocation = position != null;
    final requiresSelfie = verificationMode == 'selfie';
    final hasSelfie = !requiresSelfie || selfieFile != null;
    final isReadyToSubmit = canSubmit && hasLocation && hasSelfie;

    return Container(
      decoration: const BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.vertical(top: Radius.circular(28)),
      ),
      child: SafeArea(
        top: false,
        child: Padding(
          padding: EdgeInsets.fromLTRB(
            16,
            12,
            16,
            16 + MediaQuery.of(context).viewInsets.bottom,
          ),
          child: SingleChildScrollView(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Center(
                  child: Container(
                    width: 44,
                    height: 5,
                    decoration: BoxDecoration(
                      color: const Color(0xFFD7E3DD),
                      borderRadius: BorderRadius.circular(999),
                    ),
                  ),
                ),
                const SizedBox(height: 14),
                const Text(
                  'Presensi',
                  style: TextStyle(
                    color: _attendanceText,
                    fontSize: 18,
                    fontWeight: FontWeight.w800,
                  ),
                ),
                const SizedBox(height: 4),
                Text(
                  '$verificationLabel aktif. $verificationDescription',
                  style: const TextStyle(
                    color: _attendanceMuted,
                    fontSize: 12,
                    height: 1.4,
                  ),
                ),
                const SizedBox(height: 16),
                _SimpleModalBlock(
                  icon: Icons.my_location_rounded,
                  title: 'Lokasi',
                  actionLabel: loadingLocation ? 'Memuat...' : 'Perbarui',
                  onActionTap: loadingLocation ? null : onCaptureLocation,
                  child: position == null
                      ? Text(
                          locationError?.trim().isNotEmpty == true
                              ? locationError!
                              : 'Lokasi belum tersedia. Tekan perbarui untuk membaca GPS.',
                          style: TextStyle(
                            color: locationError?.trim().isNotEmpty == true
                                ? const Color(0xFFB42318)
                                : _attendanceMuted,
                            fontSize: 12,
                            height: 1.45,
                          ),
                        )
                      : Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            ClipRRect(
                              borderRadius: BorderRadius.circular(16),
                              child: SizedBox(
                                height: 140,
                                width: double.infinity,
                                child: FlutterMap(
                                  options: MapOptions(
                                    initialCenter: LatLng(
                                      position!.latitude,
                                      position!.longitude,
                                    ),
                                    initialZoom: 16,
                                    interactionOptions:
                                        const InteractionOptions(
                                      flags: InteractiveFlag.drag |
                                          InteractiveFlag.pinchZoom |
                                          InteractiveFlag.doubleTapZoom,
                                    ),
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
                                          width: 44,
                                          height: 44,
                                          child: Container(
                                            decoration: const BoxDecoration(
                                              color: _attendancePrimary,
                                              shape: BoxShape.circle,
                                            ),
                                            child: const Icon(
                                              Icons.location_on_rounded,
                                              color: Colors.white,
                                              size: 24,
                                            ),
                                          ),
                                        ),
                                      ],
                                    ),
                                  ],
                                ),
                              ),
                            ),
                            const SizedBox(height: 10),
                            Text(
                              _coordinateLabel(position!),
                              style: const TextStyle(
                                color: Colors.black,
                                fontSize: 12,
                                fontWeight: FontWeight.w700,
                              ),
                            ),
                            const SizedBox(height: 4),
                            Text(
                              'Akurasi ${position!.accuracy.toStringAsFixed(1)} m • Sampel GPS $locationReadingsCount',
                              style: const TextStyle(
                                color: _attendanceMuted,
                                fontSize: 11,
                              ),
                            ),
                          ],
                        ),
                ),
                const SizedBox(height: 12),
                _SimpleModalBlock(
                  icon: Icons.camera_alt_outlined,
                  title: verificationMode == 'selfie'
                      ? 'Foto Selfie'
                      : 'Scan Wajah Tidak Tersedia',
                  actionLabel: verificationMode == 'selfie'
                      ? (capturingSelfie ? 'Membuka...' : 'Ambil')
                      : 'Mode Web',
                  onActionTap: verificationMode == 'selfie' && !capturingSelfie
                      ? onCaptureSelfie
                      : null,
                  child: selfieFile == null
                      ? Text(
                          verificationMode == 'selfie'
                              ? 'Belum ada foto selfie.'
                              : 'Gunakan presensi mobile web untuk mode scan wajah.',
                          style: const TextStyle(
                            color: _attendanceMuted,
                            fontSize: 12,
                            height: 1.4,
                          ),
                        )
                      : Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            ClipRRect(
                              borderRadius: BorderRadius.circular(16),
                              child: AspectRatio(
                                aspectRatio: 4 / 5,
                                child: Image.file(
                                  File(selfieFile!.path),
                                  fit: BoxFit.cover,
                                ),
                              ),
                            ),
                            const SizedBox(height: 8),
                            Align(
                              alignment: Alignment.centerRight,
                              child: TextButton.icon(
                                onPressed: onClearSelfie,
                                icon: const Icon(Icons.delete_outline_rounded),
                                label: const Text('Hapus'),
                                style: TextButton.styleFrom(
                                  foregroundColor: const Color(0xFFB42318),
                                ),
                              ),
                            ),
                          ],
                        ),
                ),
                if (!isReadyToSubmit) ...[
                  const SizedBox(height: 12),
                  Container(
                    width: double.infinity,
                    padding: const EdgeInsets.all(12),
                    decoration: BoxDecoration(
                      color: const Color(0xFFFFF7ED),
                      borderRadius: BorderRadius.circular(14),
                      border: Border.all(color: const Color(0xFFF3D6AE)),
                    ),
                    child: Text(
                      !hasLocation
                          ? 'Lokasi belum lengkap. Ambil atau perbarui lokasi terlebih dahulu.'
                          : 'Foto selfie belum lengkap. Ambil foto selfie terlebih dahulu.',
                      style: const TextStyle(
                        color: Color(0xFF9A5B12),
                        fontSize: 12,
                        fontWeight: FontWeight.w700,
                        height: 1.4,
                      ),
                    ),
                  ),
                ],
                const SizedBox(height: 16),
                SizedBox(
                  width: double.infinity,
                  child: ElevatedButton.icon(
                    onPressed: isReadyToSubmit && !submitting ? onSubmit : null,
                    style: ElevatedButton.styleFrom(
                      backgroundColor: _attendancePrimary,
                      disabledBackgroundColor: const Color(0xFFD2E4DC),
                      foregroundColor: Colors.white,
                      padding: const EdgeInsets.symmetric(vertical: 14),
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(16),
                      ),
                      elevation: 0,
                    ),
                    icon: submitting
                        ? const SizedBox(
                            width: 18,
                            height: 18,
                            child: CircularProgressIndicator(
                              strokeWidth: 2.2,
                              color: Colors.white,
                            ),
                          )
                        : const Icon(Icons.check_circle_outline_rounded),
                    label: Text(
                      submitting
                          ? 'Mengirim...'
                          : (form['next_mode_label'] as String? ??
                              'Kirim Presensi'),
                      style: const TextStyle(fontWeight: FontWeight.w800),
                    ),
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}

class _SimpleModalBlock extends StatelessWidget {
  const _SimpleModalBlock({
    required this.icon,
    required this.title,
    required this.child,
    this.actionLabel,
    this.onActionTap,
  });

  final IconData icon;
  final String title;
  final Widget child;
  final String? actionLabel;
  final Future<void> Function()? onActionTap;

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: const Color(0xFFF7FAF8),
        borderRadius: BorderRadius.circular(18),
        border: Border.all(color: const Color(0xFFE0EBE5)),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Container(
                width: 34,
                height: 34,
                decoration: BoxDecoration(
                  color: _attendanceSoft,
                  borderRadius: BorderRadius.circular(12),
                ),
                child: Icon(
                  icon,
                  color: _attendancePrimary,
                  size: 18,
                ),
              ),
              const SizedBox(width: 10),
              Expanded(
                child: Text(
                  title,
                  style: const TextStyle(
                    color: _attendanceText,
                    fontSize: 14,
                    fontWeight: FontWeight.w800,
                  ),
                ),
              ),
              if (actionLabel != null)
                TextButton(
                  onPressed: onActionTap == null
                      ? null
                      : () async {
                          await onActionTap!.call();
                        },
                  style: TextButton.styleFrom(
                    padding: EdgeInsets.zero,
                    minimumSize: const Size(0, 0),
                    tapTargetSize: MaterialTapTargetSize.shrinkWrap,
                  ),
                  child: Text(
                    actionLabel!,
                    style: const TextStyle(
                      color: _attendancePrimary,
                      fontSize: 12,
                      fontWeight: FontWeight.w800,
                    ),
                  ),
                ),
            ],
          ),
          const SizedBox(height: 10),
          child,
        ],
      ),
    );
  }
}

class _GuidanceCard extends StatelessWidget {
  const _GuidanceCard({
    required this.guidance,
  });

  final List<String> guidance;

  @override
  Widget build(BuildContext context) {
    return AppSectionCard(
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const _PageSectionHeading(
            eyebrow: 'Panduan',
            title: 'Langkah Presensi',
          ),
          const SizedBox(height: 12),
          if (guidance.isEmpty)
            const Text(
              'Tidak ada panduan tambahan untuk hari ini.',
              style: TextStyle(
                color: _attendanceMuted,
                fontSize: 12,
              ),
            )
          else
            ...guidance.asMap().entries.map(
                  (entry) => Padding(
                    padding: EdgeInsets.only(
                      bottom: entry.key == guidance.length - 1 ? 0 : 10,
                    ),
                    child: Row(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Container(
                          width: 24,
                          height: 24,
                          decoration: BoxDecoration(
                            color: _attendanceSoft,
                            borderRadius: BorderRadius.circular(999),
                          ),
                          child: Center(
                            child: Text(
                              '${entry.key + 1}',
                              style: const TextStyle(
                                color: _attendancePrimary,
                                fontWeight: FontWeight.w800,
                                fontSize: 11,
                              ),
                            ),
                          ),
                        ),
                        const SizedBox(width: 10),
                        Expanded(
                          child: Text(
                            entry.value,
                            style: const TextStyle(
                              color: _attendanceMuted,
                              fontSize: 12,
                              height: 1.4,
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
        ],
      ),
    );
  }
}

class _AttendanceHistoryTile extends StatelessWidget {
  const _AttendanceHistoryTile({
    required this.item,
  });

  final Map<String, dynamic> item;

  @override
  Widget build(BuildContext context) {
    final status = (item['status'] as String? ?? '-').toLowerCase();
    final statusLabel = item['status_label'] as String? ?? item['status'] as String? ?? '-';
    final isAutoPresent = item['is_auto_present'] == true;
    final statusColor = _statusColor(status);

    return AppSectionCard(
      padding: const EdgeInsets.all(14),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            width: 42,
            height: 42,
            decoration: BoxDecoration(
              color: statusColor.withOpacity(0.12),
              borderRadius: BorderRadius.circular(16),
            ),
            child: Icon(
              status == 'hadir'
                  ? Icons.verified_rounded
                  : status == 'izin'
                      ? Icons.schedule_rounded
                      : Icons.cancel_rounded,
              color: statusColor,
              size: 20,
            ),
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  children: [
                    Expanded(
                      child: Text(
                        item['date_label'] as String? ?? '-',
                        style: const TextStyle(
                          color: _attendanceText,
                          fontWeight: FontWeight.w800,
                          fontSize: 14,
                        ),
                      ),
                    ),
                    _StatusPill(label: statusLabel),
                  ],
                ),
                const SizedBox(height: 6),
                Text(
                  '${item['check_in'] ?? '-'} • ${item['check_out'] ?? '-'}',
                  style: const TextStyle(
                    color: Colors.black,
                    fontWeight: FontWeight.w700,
                    fontSize: 12,
                  ),
                ),
                if ((item['location'] as String?)?.trim().isNotEmpty == true) ...[
                  const SizedBox(height: 5),
                  Text(
                    item['location'] as String,
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                    style: const TextStyle(
                      color: _attendanceMuted,
                      fontSize: 12,
                    ),
                  ),
                ],
                if ((item['note'] as String?)?.trim().isNotEmpty == true) ...[
                  const SizedBox(height: 5),
                  Text(
                    item['note'] as String,
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                    style: const TextStyle(
                      color: Color(0xFF9A6A33),
                      fontSize: 12,
                    ),
                  ),
                ],
                if (isAutoPresent) ...[
                  const SizedBox(height: 6),
                  const Text(
                    'Presensi hadir ini berasal dari izin tugas luar yang sudah disetujui.',
                    style: TextStyle(
                      color: Colors.black,
                      fontSize: 11,
                      fontWeight: FontWeight.w700,
                      height: 1.35,
                    ),
                  ),
                ],
              ],
            ),
          ),
        ],
      ),
    );
  }
}

class _AttendanceHistoryPage extends StatelessWidget {
  const _AttendanceHistoryPage({
    required this.items,
  });

  final List<Map<String, dynamic>> items;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      body: SafeArea(
        child: ListView(
          padding: const EdgeInsets.fromLTRB(16, 16, 16, 28),
          children: [
            TeacherPageHeader(
              title: 'Riwayat Presensi',
              onBack: () => Navigator.of(context).pop(),
            ),
            const SizedBox(height: 14),
            if (items.isEmpty)
              const AppSectionCard(
                child: AppEmptyState(
                  title: 'Belum ada riwayat presensi',
                  message: 'Riwayat presensi akan tampil di sini.',
                  icon: Icons.history_toggle_off_rounded,
                ),
              )
            else
              ...items.map(
                (item) => Padding(
                  padding: const EdgeInsets.only(bottom: 12),
                  child: _AttendanceHistoryTile(item: item),
                ),
              ),
          ],
        ),
      ),
    );
  }
}

class _StatusPill extends StatelessWidget {
  const _StatusPill({
    required this.label,
  });

  final String label;

  @override
  Widget build(BuildContext context) {
    final color = _statusColor(label.toLowerCase());

    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
      decoration: BoxDecoration(
        color: color.withOpacity(0.12),
        borderRadius: BorderRadius.circular(999),
      ),
      child: Text(
        label,
        style: TextStyle(
          color: color,
          fontWeight: FontWeight.w800,
          fontSize: 11,
        ),
      ),
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

class _PageSectionHeading extends StatelessWidget {
  const _PageSectionHeading({
    required this.eyebrow,
    required this.title,
  });

  final String eyebrow;
  final String title;

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          eyebrow,
          style: const TextStyle(
            color: Colors.black,
            fontSize: 12,
            fontWeight: FontWeight.w800,
            letterSpacing: 0.2,
          ),
        ),
        const SizedBox(height: 4),
        Text(
          title,
          style: const TextStyle(
            color: _attendanceText,
            fontSize: 16,
            fontWeight: FontWeight.w800,
          ),
        ),
      ],
    );
  }
}

Color _statusColor(String status) {
  switch (status) {
    case 'hadir':
      return _attendancePrimary;
    case 'izin':
      return const Color(0xFFE3A320);
    case 'alpha':
      return const Color(0xFFB42318);
    default:
      return _attendanceMuted;
  }
}

String _timeLabel(DateTime value) {
  final hours = value.hour.toString().padLeft(2, '0');
  final minutes = value.minute.toString().padLeft(2, '0');
  final seconds = value.second.toString().padLeft(2, '0');
  return '$hours:$minutes:$seconds';
}

String _coordinateLabel(Position position) {
  return 'Lat ${position.latitude.toStringAsFixed(6)}, Lng ${position.longitude.toStringAsFixed(6)}';
}
