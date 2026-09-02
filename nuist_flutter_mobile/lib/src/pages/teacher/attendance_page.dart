import 'dart:async';
import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:flutter_map/flutter_map.dart';
import 'package:geocoding/geocoding.dart';
import 'package:geolocator/geolocator.dart';
import 'package:latlong2/latlong.dart';

import 'attendance_face_scan_page.dart';
import '../../services/teacher_mobile_repository.dart';
import '../../widgets/app/app_section_card.dart';

const _attendanceBackground = Color(0xFFF7F9FC);
const _attendanceSurface = Color(0xFFFFFFFF);
const _attendancePrimary = Color(0xFF00745A);
const _attendancePrimaryDark = Color(0xFF00553F);
const _attendancePrimarySoft = Color(0xFFE5F5F0);
const _attendancePrimaryBorder = Color(0xFFDCE7E3);
const _attendanceText = Color(0xFF172A24);
const _attendanceMuted = Color(0xFF64746E);
const _attendanceDanger = Color(0xFFEF4444);
const _attendanceCardShadow = Color(0x14172A24);
const _attendanceMapDefaultZoom = 15.6;
const _attendanceMapUserZoom = 18.4;

bool _isCompatibleBiometricRegistered(Map<String, dynamic> status) {
  final profile = Map<String, dynamic>.from(
    (status['profile'] as Map?) ?? const <String, dynamic>{},
  );
  final registered = status['registered'] == true;
  final engine = profile['engine']?.toString().trim();
  final model = profile['model']?.toString().trim();
  final modelVersion = profile['model_version']?.toString().trim();
  final dimension = int.tryParse(profile['dimension']?.toString().trim() ?? '');
  final statusValue = profile['status']?.toString().trim();

  if (kDebugMode) {
    debugPrint(
      '[BIOMETRIC_GATE][API_RESPONSE] '
      'registered=$registered '
      'profilePresent=${profile.isNotEmpty} '
      'engine=${engine ?? ""} '
      'model=${model ?? ""} '
      'modelVersion=${modelVersion ?? ""} '
      'dimension=${profile['dimension'] ?? ""} '
      'status=${statusValue ?? ""}',
    );
  }

  if (!registered || profile.isEmpty) {
    return false;
  }

  final engineMatch = engine == 'opencv';
  final modelMatch = model == 'sface';
  final versionMatch = modelVersion == 'v1';
  final dimensionMatch = dimension == 128;
  final statusMatch = statusValue == 'active';
  final compatible = engineMatch &&
      modelMatch &&
      versionMatch &&
      dimensionMatch &&
      statusMatch;

  if (kDebugMode) {
    debugPrint(
      '[BIOMETRIC_GATE][COMPATIBILITY] '
      'registered=$registered '
      'engineMatch=$engineMatch '
      'modelMatch=$modelMatch '
      'versionMatch=$versionMatch '
      'dimensionMatch=$dimensionMatch '
      'statusMatch=$statusMatch '
      'compatible=$compatible',
    );
  }

  return compatible;
}

class TeacherAttendancePage extends StatefulWidget {
  const TeacherAttendancePage({
    super.key,
    required this.repository,
    required this.onBackToHome,
    required this.onOpenAttendanceHistory,
    required this.isActive,
    this.currentUserId,
  });

  final TeacherMobileRepository repository;
  final VoidCallback onBackToHome;
  final Future<void> Function() onOpenAttendanceHistory;
  final bool isActive;
  final int? currentUserId;

  @override
  State<TeacherAttendancePage> createState() => _TeacherAttendancePageState();
}

class _TeacherAttendancePageState extends State<TeacherAttendancePage>
    with WidgetsBindingObserver {
  late Future<Map<String, dynamic>> _future;
  late Future<Map<String, dynamic>> _biometricStatusFuture;
  late Future<Map<String, dynamic>> _combinedFuture;
  DateTime _now = DateTime.now();
  Timer? _clockTimer;
  Position? _position;
  Map<String, dynamic>? _faceScanResult;
  String? _locationAddress;
  String? _locationError;
  List<Map<String, dynamic>> _locationReadings = const [];
  bool _loadingLocation = false;
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
    _biometricStatusFuture = widget.repository.getBiometricProfileStatus();
    _combinedFuture = _loadCombinedState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      _handlePageReactivated(refreshRemoteData: true);
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
    final biometricFuture = widget.repository.getBiometricProfileStatus();
    setState(() {
      _future = future;
      _biometricStatusFuture = biometricFuture;
      _combinedFuture = _loadCombinedState();
      _faceScanResult = null;
    });
    await Future.wait([future, biometricFuture]);
  }

  Future<Map<String, dynamic>> _loadCombinedState() async {
    final attendance = await _future;
    final biometricStatus = await _biometricStatusFuture;
    return {
      'attendance': attendance,
      'biometric_status': biometricStatus,
    };
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
      final positions = <Position>[];
      final lastKnownPosition = await Geolocator.getLastKnownPosition();

      for (var index = 0; index < 3; index++) {
        try {
          final sampled = await Geolocator.getCurrentPosition(
            locationSettings: const LocationSettings(
              accuracy: LocationAccuracy.best,
              timeLimit: Duration(seconds: 8),
            ),
          );
          positions.add(sampled);
          readings.add({
            'latitude': sampled.latitude,
            'longitude': sampled.longitude,
            'accuracy': sampled.accuracy,
            'timestamp': DateTime.now().millisecondsSinceEpoch,
          });
        } catch (error) {
          if (positions.isEmpty && lastKnownPosition != null) {
            positions.add(lastKnownPosition);
            readings.add({
              'latitude': lastKnownPosition.latitude,
              'longitude': lastKnownPosition.longitude,
              'accuracy': lastKnownPosition.accuracy,
              'timestamp': DateTime.now().millisecondsSinceEpoch,
            });
          }
          if (positions.isEmpty) {
            rethrow;
          }
          break;
        }

        if (index < 2) {
          await Future<void>.delayed(const Duration(milliseconds: 450));
        }
      }

      if (positions.isEmpty) {
        throw Exception('Lokasi tidak berhasil dibaca.');
      }

      // Prefer the most accurate fresh fix instead of blindly using the last.
      final latestPosition = positions.reduce(
        (best, candidate) => candidate.accuracy < best.accuracy ? candidate : best,
      );
      debugPrint(
        '[LOCATION][FIX] '
        'accuracy=${latestPosition.accuracy} '
        'samples=${positions.length} '
        'mocked=${latestPosition.isMocked}',
      );

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

  Future<Map<String, dynamic>?> _captureFaceScan(Map<String, dynamic> data) async {
    final biometricStatus = Map<String, dynamic>.from(
      (data['biometric_status'] as Map?) ?? const <String, dynamic>{},
    );
    final isFaceEnrolled = _isCompatibleBiometricRegistered(biometricStatus);

    if (!isFaceEnrolled) {
      final message = biometricStatus['message'] as String? ??
          'Data wajah belum terdaftar. Hubungi admin untuk aktivasi scan wajah.';
      if (!mounted) {
        return null;
      }
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(message)),
      );
      return null;
    }

    setState(() {
      _faceScanResult = null;
    });

    final result = await Navigator.of(context).push<Map<String, dynamic>>(
      MaterialPageRoute(
        fullscreenDialog: true,
        builder: (_) => AttendanceFaceScanPage(
          title: 'Scan Wajah',
          description: 'Selesaikan verifikasi biometrik untuk presensi.',
          repository: widget.repository,
        ),
      ),
    );

    if (!mounted || result == null) {
      return null;
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
      return null;
    }

    return normalizedResult;
  }

  Future<Map<String, dynamic>?> _captureVerification(Map<String, dynamic> data) async {
    return _captureFaceScan(data);
  }

  Future<void> _openFaceEnrollment(Map<String, dynamic> data) async {
    final biometricStatus = Map<String, dynamic>.from(
      (data['biometric_status'] as Map?) ?? const <String, dynamic>{},
    );
    final enrolled = _isCompatibleBiometricRegistered(biometricStatus);
    if (enrolled) {
      return;
    }

    if (!mounted) {
      return;
    }
    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(
        content: Text('Pendaftaran wajah dilakukan oleh operator melalui Kiosk 2.'),
      ),
    );
  }

  Future<bool> _submitAttendance(
    Map<String, dynamic> data,
    Map<String, dynamic> faceScanResult,
  ) async {
    final form = Map<String, dynamic>.from(
      (data['form'] as Map?) ?? const <String, dynamic>{},
    );
    final today = Map<String, dynamic>.from(
      (data['today_attendance'] as Map?) ?? const <String, dynamic>{},
    );
    final timeRanges = Map<String, dynamic>.from(
      (data['time_ranges'] as Map?) ?? const <String, dynamic>{},
    );
    final mode = _resolveAttendanceMode(
      form['next_mode'] as String?,
      today: today,
    );

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
      final submitStartedAt = DateTime.now();
      final verification = faceScanResult['verification'];
      final livenessChallenges = _normalizedChallenges(
        faceScanResult['liveness_challenges'],
      );
      final embeddingCount = faceScanResult['face_embedding'] is List
          ? (faceScanResult['face_embedding'] as List).length
          : 0;
      final challengeNames = livenessChallenges.join(',');
      debugPrint(
        '[ATTENDANCE_V2][HANDOFF] '
        'liveness_score=${faceScanResult['liveness_score']} '
        'challenge_count=${livenessChallenges.length} '
        'challenge_names=$challengeNames '
        'embedding_count=$embeddingCount '
        'engine=${verification is Map ? verification['engine'] ?? "" : ""} '
        'model=${verification is Map ? verification['model'] ?? "" : ""} '
        'model_version=${verification is Map ? verification['model_version'] ?? "" : ""} '
        'dimension=${verification is Map ? verification['dimension'] ?? "" : ""}',
      );
      final payload = <String, dynamic>{
        'presensi_mode': mode,
        'latitude': _position!.latitude,
        'longitude': _position!.longitude,
        'lokasi': _locationAddress ?? _coordinateLabel(_position!),
        'accuracy': _position!.accuracy,
        'is_mocked': _position!.isMocked,
        'altitude': _position!.altitude,
        'speed': _position!.speed,
        'device_info': 'flutter_mobile_${defaultTargetPlatform.name}',
        'location_readings': _locationReadings,
        'selfie_data': faceScanResult['selfie_data'],
        'liveness_challenges': livenessChallenges,
        if (faceScanResult['face_embedding'] != null)
          'face_embedding': faceScanResult['face_embedding'],
        'liveness_score': faceScanResult['liveness_score'],
        if (faceScanResult['verification'] is Map) ...{
          'engine': (faceScanResult['verification'] as Map)['engine'],
          'model': (faceScanResult['verification'] as Map)['model'],
          'model_version': (faceScanResult['verification'] as Map)['model_version'],
          'dimension': (faceScanResult['verification'] as Map)['dimension'],
        },
      };

      debugPrint(
        '[ATTENDANCE_SUBMIT][START] '
        'mode=$mode '
        'has_selfie=${payload['selfie_data'] is String} '
        'embedding_count=${(payload['face_embedding'] as List?)?.length ?? 0} '
        'liveness_score=${payload['liveness_score']}',
      );
      final result = await widget.repository.submitAttendance(payload: payload);
      if (!mounted) {
        return false;
      }

      debugPrint(
        '[ATTENDANCE_SUBMIT][SUCCESS] '
        'elapsed_ms=${DateTime.now().difference(submitStartedAt).inMilliseconds} '
        'message=${result['_message'] ?? ''}',
      );

      try {
        await _refresh();
      } catch (refreshError) {
        debugPrint('[ATTENDANCE_SUBMIT][REFRESH_FAILED] $refreshError');
      }

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

  Map<String, dynamic>? _normalizeFaceScanResult(Map<String, dynamic> raw) {
    final verification = raw['verification'] is Map
        ? Map<String, dynamic>.from(raw['verification'] as Map)
        : const <String, dynamic>{};
    final selfieData =
        raw['captured_image'] as String? ?? raw['selfie_data'] as String?;
    final faceEmbedding = raw['face_embedding'] ?? verification['face_embedding'];
    final livenessScore = raw['liveness_score'] ?? verification['liveness_score'];
    final livenessChallenges =
        raw['liveness_challenges'] ?? verification['liveness_challenges'] ?? const <dynamic>[];

    if (selfieData == null ||
        selfieData.trim().isEmpty ||
        faceEmbedding is! List ||
        livenessChallenges is! List) {
      debugPrint(
        '[ATTENDANCE_V2][SCAN_HANDOFF_INVALID] '
        'has_selfie=${selfieData != null && selfieData.trim().isNotEmpty} '
        'embedding_type=${faceEmbedding.runtimeType} '
        'embedding_count=${faceEmbedding is List ? faceEmbedding.length : 0} '
        'challenges_type=${livenessChallenges.runtimeType}',
      );
      return null;
    }

    // The verify response identifies the provider but the attendance API
    // requires the stable profile metadata explicitly.
    final normalizedVerification = <String, dynamic>{
      ...verification,
      'engine': verification['engine']?.toString().trim().isNotEmpty == true
          ? verification['engine']
          : 'opencv',
      'model': verification['model']?.toString().trim().isNotEmpty == true
          ? verification['model']
          : 'sface',
      'model_version':
          verification['model_version']?.toString().trim().isNotEmpty == true
              ? verification['model_version']
              : 'v1',
      'dimension': verification['dimension'] is num
          ? verification['dimension']
          : faceEmbedding.length,
    };

    return {
      'selfie_data': selfieData,
      'face_embedding': List<dynamic>.from(faceEmbedding),
      'liveness_score': livenessScore,
      'liveness_challenges': _normalizedChallenges(livenessChallenges),
      'verification': normalizedVerification,
    };
  }

  List<String> _normalizedChallenges(dynamic raw) {
    final items = <String>[];
    if (raw is Iterable) {
      for (final item in raw) {
        final value = item is Map
            ? (item['type'] ?? item['name'])?.toString().trim()
            : item?.toString().trim();
        if (value != null && value.isNotEmpty) {
          items.add(value.length > 64 ? value.substring(0, 64) : value);
        }
      }
    }

    return items.toSet().toList(growable: false);
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
          backgroundColor: _attendanceSurface,
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(22),
            side: const BorderSide(color: _attendancePrimaryBorder),
          ),
          title: const Text('Konfirmasi Pulang Awal'),
          content: Text(
            pulangLabel == null
                ? 'Jam pulang belum tiba. Apakah Anda yakin ingin pulang lebih awal?'
                : 'Jam pulang dimulai pukul $pulangLabel. Apakah Anda yakin ingin pulang lebih awal?',
            style: const TextStyle(
              color: _attendanceMuted,
              height: 1.45,
            ),
          ),
          actions: [
            TextButton(
              onPressed: () => Navigator.of(context).pop(false),
              child: const Text(
                'Batal',
                style: TextStyle(
                  color: _attendanceMuted,
                  fontWeight: FontWeight.w700,
                ),
              ),
            ),
            ElevatedButton(
              onPressed: () => Navigator.of(context).pop(true),
              style: ElevatedButton.styleFrom(
                backgroundColor: _attendancePrimary,
                foregroundColor: Colors.white,
                elevation: 0,
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

  String? _resolveAttendanceMode(
    String? backendMode, {
    required Map<String, dynamic> today,
  }) {
    // The displayed attendance state is the safest source for preventing a
    // second INSERT when the cached form state is stale.
    final checkInRecorded = _hasAttendanceValue(
      today,
      const ['check_in', 'waktu_masuk', 'checkIn'],
    );
    final checkOutRecorded = _hasAttendanceValue(
      today,
      const ['check_out', 'waktu_keluar', 'checkOut'],
    );

    if (checkInRecorded && checkOutRecorded) {
      return null;
    }

    if (checkInRecorded && !checkOutRecorded) {
      final normalizedBackendMode = backendMode?.trim();
      if (normalizedBackendMode != null && normalizedBackendMode != 'keluar') {
        debugPrint(
          '[ATTENDANCE_MODE][CONFLICT] '
          'backend_mode=$normalizedBackendMode '
          'today_check_in=true today_check_out=false forced_mode=keluar',
        );
      }
      return 'keluar';
    }

    if (backendMode != null && backendMode.trim().isNotEmpty) {
      return backendMode.trim();
    }

    if (!kDebugMode) {
      return null;
    }

    final checkIn = today['waktu_masuk']?.toString().trim();
    final checkOut = today['waktu_keluar']?.toString().trim();

    if (checkIn == null || checkIn.isEmpty) {
      return 'masuk';
    }

    if (checkOut == null || checkOut.isEmpty) {
      return 'keluar';
    }

    return null;
  }

  bool _hasAttendanceValue(
    Map<String, dynamic> today,
    List<String> keys,
  ) {
    bool hasValue(Map<String, dynamic> source) {
      final value = _findFirstMapValue(source, keys);
      return value != null && _formatAttendanceTime(value) != '--:--';
    }

    if (hasValue(today)) {
      return true;
    }

    final entries = today['entries'];
    if (entries is List) {
      return entries
          .whereType<Map>()
          .map((entry) => Map<String, dynamic>.from(entry))
          .any(hasValue);
    }

    return false;
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
      final placemarks = await Geocoding().placemarkFromCoordinates(
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

  Future<void> _openAttendanceFlow(Map<String, dynamic> data) async {
    // Do not rely on the FutureBuilder snapshot: another successful request
    // may have changed today's mode since the page was rendered.
    Map<String, dynamic> attendanceData = Map<String, dynamic>.from(
      (data['attendance'] as Map?) ?? const <String, dynamic>{},
    );
    try {
      final freshAttendance = await widget.repository.getAttendance();
      attendanceData = freshAttendance;
      debugPrint(
        '[ATTENDANCE_MODE][REFRESHED] '
        'next_mode=${(freshAttendance['form'] as Map?)?['next_mode'] ?? ''} '
        'check_in=${(freshAttendance['today_attendance'] as Map?)?['check_in'] ?? ''} '
        'check_out=${(freshAttendance['today_attendance'] as Map?)?['check_out'] ?? ''}',
      );
    } catch (error) {
      debugPrint('[ATTENDANCE_MODE][REFRESH_FAILED] $error');
    }

    final faceScanResult = await _captureVerification({
      ...data,
      'attendance': attendanceData,
    });
    if (!mounted || faceScanResult == null) {
      return;
    }

    setState(() {
      _faceScanResult = faceScanResult;
    });

    await _submitAttendance(attendanceData, faceScanResult);
  }

  @override
  Widget build(BuildContext context) {
    return FutureBuilder<Map<String, dynamic>>(
      future: _combinedFuture,
      builder: (context, snapshot) {
        if (snapshot.connectionState == ConnectionState.waiting) {
          return const ColoredBox(
            color: _attendanceBackground,
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

        final combined = snapshot.data ?? const <String, dynamic>{};
        final attendanceData = Map<String, dynamic>.from(
          (combined['attendance'] as Map?) ?? const <String, dynamic>{},
        );
        final biometricStatus = Map<String, dynamic>.from(
          (combined['biometric_status'] as Map?) ?? const <String, dynamic>{},
        );
        return _AttendanceContent(
          data: attendanceData,
          biometricStatus: biometricStatus,
          now: _now,
          submitting: _submitting,
          position: _position,
          locationAddress: _locationAddress,
          locationError: _locationError,
          locationReadingsCount: _locationReadings.length,
          loadingLocation: _loadingLocation,
          onCaptureLocation: _captureLocation,
          faceScanResult: _faceScanResult,
          capturingSelfie: false,
          onCaptureSelfie: () => _captureVerification(attendanceData),
          onClearSelfie: () {
            setState(() {
              _faceScanResult = null;
            });
          },
          onOpenAttendanceFlow: () => _openAttendanceFlow(
            snapshot.data ?? const <String, dynamic>{},
          ),
          onOpenFaceEnrollment: () => _openFaceEnrollment(
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
    required this.biometricStatus,
    required this.now,
    required this.submitting,
    required this.position,
    required this.locationAddress,
    required this.locationError,
    required this.locationReadingsCount,
    required this.loadingLocation,
    required this.onCaptureLocation,
    required this.faceScanResult,
    required this.capturingSelfie,
    required this.onCaptureSelfie,
    required this.onClearSelfie,
    required this.onOpenAttendanceFlow,
    required this.onOpenFaceEnrollment,
    required this.onBackToHome,
    required this.onOpenAttendanceHistory,
    required this.onRefreshData,
  });

  final Map<String, dynamic> data;
  final Map<String, dynamic> biometricStatus;
  final DateTime now;
  final bool submitting;
  final Position? position;
  final String? locationAddress;
  final String? locationError;
  final int locationReadingsCount;
  final bool loadingLocation;
  final Future<void> Function() onCaptureLocation;
  final Map<String, dynamic>? faceScanResult;
  final bool capturingSelfie;
  final Future<void> Function() onCaptureSelfie;
  final VoidCallback onClearSelfie;
  final VoidCallback onOpenAttendanceFlow;
  final Future<void> Function() onOpenFaceEnrollment;
  final VoidCallback onBackToHome;
  final Future<void> Function() onOpenAttendanceHistory;
  final Future<void> Function() onRefreshData;

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
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
    final biometricRegistered = _isCompatibleBiometricRegistered(biometricStatus);
    final canSubmit = form['can_submit'] == true ||
        (kDebugMode && now.weekday == DateTime.sunday);
    final nextModeLabel =
        form['next_mode_label'] as String? ?? 'Presensi Masuk';
    final checkInRecorded = _formatAttendanceTime(
          _findFirstMapValue(today, const ['check_in', 'waktu_masuk', 'checkIn']),
        ) !=
        '--:--';
    final checkOutRecorded = _formatAttendanceTime(
          _findFirstMapValue(today, const ['check_out', 'waktu_keluar', 'checkOut']),
        ) !=
        '--:--';
    final attendanceComplete = checkInRecorded && checkOutRecorded;
    final verificationMode = verification['mode'] as String? ?? 'selfie';
    final isFaceScan = verificationMode == 'face_scan';
    final isFaceEnrolled = !isFaceScan || biometricRegistered;
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
    final canOpenFlow = !attendanceComplete &&
        canSubmit &&
        isFaceEnrolled &&
        !submitting &&
        position != null;
    final requiresFaceEnrollment = isFaceScan && !isFaceEnrolled;
    final primaryActionLabel = attendanceComplete
        ? 'Presensi Lengkap'
        : requiresFaceEnrollment
            ? 'Daftar Wajah'
            : nextModeLabel;

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
                      Container(
                        padding: const EdgeInsets.symmetric(
                          horizontal: 16,
                          vertical: 10,
                        ),
                        decoration: BoxDecoration(
                          color: _attendanceSurface.withValues(alpha: 0.94),
                          borderRadius: BorderRadius.circular(999),
                          border: Border.all(color: _attendancePrimaryBorder),
                          boxShadow: const [
                            BoxShadow(
                              color: _attendanceCardShadow,
                              blurRadius: 18,
                              offset: Offset(0, 8),
                            ),
                          ],
                        ),
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
                  color: _attendanceSurface.withValues(alpha: 0.97),
                  borderRadius: BorderRadius.circular(28),
                  border: Border.all(color: _attendancePrimaryBorder),
                  boxShadow: const [
                    BoxShadow(
                      color: _attendanceCardShadow,
                      blurRadius: 24,
                      offset: Offset(0, 12),
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
              DecoratedBox(
                decoration: const BoxDecoration(
                  borderRadius: BorderRadius.all(Radius.circular(18)),
                  boxShadow: [
                    BoxShadow(
                      color: _attendanceCardShadow,
                      blurRadius: 18,
                      offset: Offset(0, 10),
                    ),
                  ],
                ),
                child: SizedBox(
                  width: double.infinity,
                  child: FilledButton(
                    onPressed: attendanceComplete
                        ? null
                        : requiresFaceEnrollment
                        ? () async {
                            await onOpenFaceEnrollment();
                          }
                        : (canOpenFlow ? onOpenAttendanceFlow : null),
                    style: FilledButton.styleFrom(
                      backgroundColor: requiresFaceEnrollment
                          ? _attendancePrimaryDark
                          : _attendancePrimary,
                      disabledBackgroundColor: _attendancePrimaryBorder,
                      disabledForegroundColor: _attendanceMuted,
                      foregroundColor: Colors.white,
                      padding: const EdgeInsets.symmetric(vertical: 16),
                      elevation: 0,
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(18),
                      ),
                    ),
                    child: Text(
                      primaryActionLabel,
                      style: const TextStyle(
                        fontWeight: FontWeight.w800,
                        fontSize: 13,
                      ),
                    ),
                  ),
                ),
              ),
              if (isFaceScan && !isFaceEnrolled) ...[
                const SizedBox(height: 8),
                Text(
                  biometricStatus['message'] as String? ??
                      'Data wajah belum terdaftar. Silakan daftar wajah terlebih dahulu.',
                  textAlign: TextAlign.center,
                  style: theme.textTheme.labelSmall?.copyWith(
                    color: Colors.white,
                    fontWeight: FontWeight.w700,
                  ),
                ),
              ] else if (isFaceScan && isFaceEnrolled) ...[
                const SizedBox(height: 8),
                Text(
                  'Data wajah terdaftar. Tombol presensi sudah tersedia.',
                  textAlign: TextAlign.center,
                  style: theme.textTheme.labelSmall?.copyWith(
                    color: _attendancePrimaryDark,
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
                  color: _attendanceSurface.withValues(alpha: 0.96),
                  borderRadius: BorderRadius.circular(999),
                  border: Border.all(color: _attendancePrimaryBorder),
                  boxShadow: const [
                    BoxShadow(
                      color: _attendanceCardShadow,
                      blurRadius: 16,
                      offset: Offset(0, 8),
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
              color: _attendancePrimary.withValues(alpha: 0.18),
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
                    color: _attendancePrimary.withValues(alpha: 0.7),
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
                    Colors.black.withValues(alpha: 0.015),
                    Colors.black.withValues(alpha: 0.12),
                  ],
                ),
              ),
            ),
          ),
        ),
        if (widget.loadingLocation)
          Positioned(
            left: 0,
            right: 0,
            bottom: 260,
            child: Center(
              child: Card(
                color: _attendanceSurface.withValues(alpha: 0.96),
                elevation: 0,
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(18),
                  side: const BorderSide(color: _attendancePrimaryBorder),
                ),
                child: const Padding(
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
                          color: _attendanceText,
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
      color: _attendanceSurface.withValues(alpha: 0.95),
      borderRadius: BorderRadius.circular(999),
      shadowColor: Colors.transparent,
      child: InkWell(
        borderRadius: BorderRadius.circular(999),
        onTap: onTap,
        child: Ink(
          decoration: BoxDecoration(
            borderRadius: BorderRadius.circular(999),
            border: Border.all(color: _attendancePrimaryBorder),
            boxShadow: const [
              BoxShadow(
                color: _attendanceCardShadow,
                blurRadius: 14,
                offset: Offset(0, 6),
              ),
            ],
          ),
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
              color: _attendanceDanger,
              fontWeight: FontWeight.w700,
            ),
          ),
          const SizedBox(height: 12),
          OutlinedButton(
            onPressed: onRetry,
            style: OutlinedButton.styleFrom(
              foregroundColor: _attendancePrimaryDark,
              side: const BorderSide(color: _attendancePrimaryBorder),
            ),
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
