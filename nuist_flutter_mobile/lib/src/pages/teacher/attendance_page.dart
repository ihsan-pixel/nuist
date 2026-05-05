import 'dart:async';
import 'dart:convert';
import 'dart:io';

import 'package:flutter_map/flutter_map.dart';
import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:geocoding/geocoding.dart';
import 'package:geolocator/geolocator.dart';
import 'package:image_picker/image_picker.dart';
import 'package:latlong2/latlong.dart';

import '../../services/teacher_mobile_repository.dart';
import '../../widgets/app/app_empty_state.dart';
import '../../widgets/app/app_section_card.dart';
import '../../widgets/app/teacher_page_header.dart';

const _attendancePrimary = Color(0xFFF49637);
const _attendancePrimaryDark = Color(0xFFC96A19);
const _attendanceText = Color(0xFF7A4212);
const _attendanceMuted = Color(0xFF7A8F8C);
const _attendanceSoft = Color(0xFFFFF4E8);

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

class _TeacherAttendancePageState extends State<TeacherAttendancePage> {
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
    _future = widget.repository.getAttendance();
    _now = DateTime.now();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      _triggerAutoLocationCapture();
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
      _triggerAutoLocationCapture();
    }
  }

  @override
  void dispose() {
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

  Future<void> _submitAttendance(Map<String, dynamic> data) async {
    final form = Map<String, dynamic>.from(
      (data['form'] as Map?) ?? const <String, dynamic>{},
    );
    final mode = form['next_mode'] as String?;

    if (mode == null || mode.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Mode presensi hari ini tidak tersedia.')),
      );
      return;
    }

    if (_position == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Ambil lokasi terlebih dahulu.')),
      );
      return;
    }

    if (_selfieFile == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Ambil foto selfie terlebih dahulu.')),
      );
      return;
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
        return;
      }

      setState(() {
        _selfieFile = null;
      });

      await _refresh();

      if (!mounted) {
        return;
      }

      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(
            (result['_message'] as String?) ?? 'Presensi berhasil dikirim.',
          ),
        ),
      );
    } catch (error) {
      if (!mounted) {
        return;
      }
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(error.toString().replaceFirst('Exception: ', '')),
        ),
      );
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
            padding: const EdgeInsets.all(16),
            children: [
              TeacherPageHeader(
                title: 'Presensi',
                onBack: widget.onBackToHome,
              ),
              const SizedBox(height: 18),
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
                  position: _position,
                  locationAddress: _locationAddress,
                  selfieFile: _selfieFile,
                  locationError: _locationError,
                  locationReadingsCount: _locationReadings.length,
                  loadingLocation: _loadingLocation,
                  capturingSelfie: _capturingSelfie,
                  submitting: _submitting,
                  onCaptureLocation: _captureLocation,
                  onCaptureSelfie: _captureSelfie,
                  onClearSelfie: () {
                    setState(() {
                      _selfieFile = null;
                    });
                  },
                  onSubmit: () => _submitAttendance(
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
    required this.position,
    required this.locationAddress,
    required this.selfieFile,
    required this.locationError,
    required this.locationReadingsCount,
    required this.loadingLocation,
    required this.capturingSelfie,
    required this.submitting,
    required this.onCaptureLocation,
    required this.onCaptureSelfie,
    required this.onClearSelfie,
    required this.onSubmit,
  });

  final Map<String, dynamic> data;
  final DateTime now;
  final Position? position;
  final String? locationAddress;
  final XFile? selfieFile;
  final String? locationError;
  final int locationReadingsCount;
  final bool loadingLocation;
  final bool capturingSelfie;
  final bool submitting;
  final Future<void> Function() onCaptureLocation;
  final Future<void> Function() onCaptureSelfie;
  final VoidCallback onClearSelfie;
  final VoidCallback onSubmit;

  @override
  Widget build(BuildContext context) {
    final today = Map<String, dynamic>.from(
      (data['today_attendance'] as Map?) ?? const <String, dynamic>{},
    );
    final form = Map<String, dynamic>.from(
      (data['form'] as Map?) ?? const <String, dynamic>{},
    );
    final recent = ((data['recent'] as List?) ?? const [])
        .whereType<Map>()
        .map((item) => Map<String, dynamic>.from(item))
        .toList();
    final canSubmit = form['can_submit'] == true;
    final nextModeLabel =
        form['next_mode_label'] as String? ?? 'Presensi Hari Ini';

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
        Row(
          children: [
            Expanded(
              child: _MiniAttendanceCard(
                icon: Icons.login_rounded,
                label: 'Masuk',
                value: today['check_in'] as String? ?? '-',
              ),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: _MiniAttendanceCard(
                icon: Icons.logout_rounded,
                label: 'Keluar',
                value: today['check_out'] as String? ?? '-',
              ),
            ),
          ],
        ),
        // const SizedBox(height: 14),
        // _AttendanceActionCard(
        //   form: form,
        //   timeRanges: timeRanges,
        // ),
        const SizedBox(height: 14),
        _AttendanceLocationCard(
          position: position,
          locationAddress: locationAddress,
          locationError: locationError,
          locationReadingsCount: locationReadingsCount,
          loadingLocation: loadingLocation,
          onRefreshLocation: onCaptureLocation,
        ),
        const SizedBox(height: 14),
        _AttendanceSelfieCard(
          selfieFile: selfieFile,
          capturingSelfie: capturingSelfie,
          onCaptureSelfie: onCaptureSelfie,
          onClearSelfie: onClearSelfie,
        ),
        const SizedBox(height: 14),
        AppSectionCard(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const Text(
                'Kirim Presensi',
                style: TextStyle(
                  color: _attendanceText,
                  fontSize: 16,
                  fontWeight: FontWeight.w800,
                ),
              ),
              const SizedBox(height: 6),
              Text(
                canSubmit
                    ? 'Lokasi dan selfie wajib tersedia sebelum presensi dikirim.'
                    : (form['blocked_message'] as String? ??
                        'Presensi untuk hari ini belum dapat dilakukan.'),
                style: const TextStyle(
                  color: _attendanceMuted,
                  fontSize: 12,
                  height: 1.4,
                ),
              ),
              const SizedBox(height: 14),
              SizedBox(
                width: double.infinity,
                child: ElevatedButton.icon(
                  onPressed: canSubmit && !submitting ? onSubmit : null,
                  style: ElevatedButton.styleFrom(
                    backgroundColor: _attendancePrimary,
                    disabledBackgroundColor: const Color(0xFFF8E3CD),
                    foregroundColor: Colors.white,
                    padding: const EdgeInsets.symmetric(vertical: 15),
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(18),
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
            ],
          ),
        ),
        const SizedBox(height: 18),
        _GuidanceCard(
          guidance: ((form['guidance'] as List?) ?? const [])
              .whereType<String>()
              .toList(),
        ),
        const SizedBox(height: 18),
        const _PageSectionHeading(
          eyebrow: 'Riwayat',
          title: 'Riwayat Presensi',
        ),
        const SizedBox(height: 12),
        if (recent.isEmpty)
          const AppSectionCard(
            child: AppEmptyState(
              title: 'Belum ada riwayat presensi',
              message: 'Riwayat presensi akan tampil di sini.',
              icon: Icons.history_toggle_off_rounded,
            ),
          )
        else
          ...recent.map(
            (item) => Padding(
              padding: const EdgeInsets.only(bottom: 12),
              child: _AttendanceHistoryTile(item: item),
            ),
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
    final statusColor = _statusColor(status);

    return Container(
      padding: const EdgeInsets.all(18),
      decoration: BoxDecoration(
        gradient: const LinearGradient(
          colors: [_attendancePrimary, _attendancePrimaryDark],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
        borderRadius: BorderRadius.circular(28),
        boxShadow: const [
          BoxShadow(
            color: Color(0x12003B39),
            blurRadius: 18,
            offset: Offset(0, 8),
          ),
        ],
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
                    fontSize: 12,
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
                    fontSize: 12,
                  ),
                ),
              ),
            ],
          ),
          const SizedBox(height: 12),
          Text(
            teacherName,
            style: const TextStyle(
              color: Colors.white,
              fontSize: 22,
              fontWeight: FontWeight.w800,
              height: 1.05,
            ),
          ),
          const SizedBox(height: 6),
          Text(
            schoolName,
            style: TextStyle(
              color: Colors.white.withOpacity(0.85),
              fontSize: 13,
              fontWeight: FontWeight.w700,
            ),
          ),
          const SizedBox(height: 16),
          Row(
            children: [
              Expanded(
                child: _HeroInfoPill(
                  icon: Icons.verified_user_outlined,
                  label: statusLabel,
                ),
              ),
              const SizedBox(width: 10),
              Expanded(
                child: Container(
                  padding:
                      const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
                  decoration: BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(18),
                  ),
                  child: Row(
                    children: [
                      Icon(
                        Icons.bolt_rounded,
                        color: statusColor,
                        size: 18,
                      ),
                      const SizedBox(width: 8),
                      Expanded(
                        child: Text(
                          nextModeLabel,
                          style: TextStyle(
                            color: statusColor,
                            fontWeight: FontWeight.w800,
                            fontSize: 12,
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
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
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
      decoration: BoxDecoration(
        color: Colors.white.withOpacity(0.16),
        borderRadius: BorderRadius.circular(18),
      ),
      child: Row(
        children: [
          const Icon(
            Icons.verified_user_outlined,
            color: Colors.white,
            size: 18,
          ),
          const SizedBox(width: 8),
          Expanded(
            child: Text(
              label,
              style: const TextStyle(
                color: Colors.white,
                fontWeight: FontWeight.w800,
                fontSize: 12,
              ),
            ),
          ),
        ],
      ),
    );
  }
}

class _MiniAttendanceCard extends StatelessWidget {
  const _MiniAttendanceCard({
    required this.icon,
    required this.label,
    required this.value,
  });

  final IconData icon;
  final String label;
  final String value;

  @override
  Widget build(BuildContext context) {
    return AppSectionCard(
      padding: const EdgeInsets.all(14),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.center,
        children: [
          Container(
            width: 36,
            height: 36,
            decoration: BoxDecoration(
              color: _attendanceSoft,
              borderRadius: BorderRadius.circular(14),
            ),
            child: Icon(
              icon,
              color: _attendancePrimary,
              size: 18,
            ),
          ),
          const SizedBox(height: 14),
          Text(
            value,
            textAlign: TextAlign.center,
            style: const TextStyle(
              color: _attendanceText,
              fontSize: 20,
              fontWeight: FontWeight.w800,
            ),
          ),
          const SizedBox(height: 4),
          Text(
            label,
            textAlign: TextAlign.center,
            style: const TextStyle(
              color: _attendanceMuted,
              fontWeight: FontWeight.w700,
              fontSize: 12,
            ),
          ),
        ],
      ),
    );
  }
}

// class _AttendanceActionCard extends StatelessWidget {
//   const _AttendanceActionCard({
//     required this.form,
//     required this.timeRanges,
//   });
//
//   final Map<String, dynamic> form;
//   final Map<String, dynamic> timeRanges;
//
//   @override
//   Widget build(BuildContext context) {
//     final blockedMessage = form['blocked_message'] as String?;
//     final nextModeLabel = form['next_mode_label'] as String? ?? '-';
//
//     return AppSectionCard(
//       child: Column(
//         crossAxisAlignment: CrossAxisAlignment.start,
//         children: [
          // const Text(
          //   'Status Presensi',
          //   style: TextStyle(
          //     color: _attendanceText,
          //     fontWeight: FontWeight.w800,
          //     fontSize: 16,
          //   ),
          // ),
          // const SizedBox(height: 10),
          // Container(
          //   width: double.infinity,
          //   padding: const EdgeInsets.all(14),
          //   decoration: BoxDecoration(
          //     color: blockedMessage == null
          //         ? _attendanceSoft
          //         : const Color(0xFFFFF2F0),
          //     borderRadius: BorderRadius.circular(18),
          //     border: Border.all(
          //       color: blockedMessage == null
          //           ? const Color(0xFFF8D7B1)
          //           : const Color(0xFFFFD0CB),
          //     ),
          //   ),
          //   child: Text(
          //     blockedMessage ?? 'Mode aktif saat ini: $nextModeLabel',
          //     style: TextStyle(
          //       color: blockedMessage == null
          //           ? _attendanceText
          //           : const Color(0xFFB42318),
          //       height: 1.4,
          //       fontWeight: FontWeight.w700,
          //       fontSize: 13,
          //     ),
          //   ),
          // ),
          // const SizedBox(height: 12),
          // Wrap(
          //   spacing: 8,
          //   runSpacing: 8,
          //   children: [
          //     _RangeChip(
          //       label: 'Masuk ${timeRanges['masuk_start'] ?? '-'}',
          //     ),
          //     _RangeChip(
          //       label: 'Pulang ${timeRanges['pulang_start'] ?? '-'}',
          //     ),
          //     if ((timeRanges['pulang_end'] as String?)?.isNotEmpty == true)
          //       _RangeChip(
          //         label: 'Batas ${timeRanges['pulang_end']}',
          //       ),
          //   ],
          // ),
//         ],
//       ),
//     );
//   }
// }

class _AttendanceLocationCard extends StatelessWidget {
  const _AttendanceLocationCard({
    required this.position,
    required this.locationAddress,
    required this.locationError,
    required this.locationReadingsCount,
    required this.loadingLocation,
    required this.onRefreshLocation,
  });

  final Position? position;
  final String? locationAddress;
  final String? locationError;
  final int locationReadingsCount;
  final bool loadingLocation;
  final Future<void> Function() onRefreshLocation;

  @override
  Widget build(BuildContext context) {
    return AppSectionCard(
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Container(
                width: 40,
                height: 40,
                decoration: BoxDecoration(
                  color: _attendanceSoft,
                  borderRadius: BorderRadius.circular(14),
                ),
                child: const Icon(
                  Icons.location_on_outlined,
                  color: _attendancePrimary,
                ),
              ),
              const SizedBox(width: 12),
              const Expanded(
                child: Text(
                  'Lokasi Presensi',
                  style: TextStyle(
                    color: _attendanceText,
                    fontWeight: FontWeight.w800,
                    fontSize: 16,
                  ),
                ),
              ),
              TextButton(
                onPressed: loadingLocation ? null : onRefreshLocation,
                child: Text(
                  loadingLocation ? 'Memuat...' : 'Ambil Lokasi',
                  style: const TextStyle(
                    color: _attendancePrimary,
                    fontWeight: FontWeight.w800,
                  ),
                ),
              ),
            ],
          ),
          const SizedBox(height: 12),
          if (position == null)
            Text(
              locationError ??
                  'Lokasi belum diambil. Tekan tombol di atas untuk mengambil posisi terbaru.',
              style: TextStyle(
                color: locationError == null
                    ? _attendanceMuted
                    : const Color(0xFFB42318),
                fontSize: 13,
                height: 1.45,
              ),
            )
          else
            Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Container(
                  width: double.infinity,
                  padding: const EdgeInsets.all(12),
                  decoration: BoxDecoration(
                    color: _attendanceSoft,
                    borderRadius: BorderRadius.circular(18),
                    border: Border.all(color: const Color(0xFFF8D7B1)),
                  ),
                  child: Text(
                    locationAddress ?? _coordinateLabel(position!),
                    style: const TextStyle(
                      color: _attendanceText,
                      fontWeight: FontWeight.w800,
                      fontSize: 13,
                      height: 1.4,
                    ),
                  ),
                ),
                const SizedBox(height: 12),
                ClipRRect(
                  borderRadius: BorderRadius.circular(20),
                  child: SizedBox(
                    height: 170,
                    width: double.infinity,
                    child: FlutterMap(
                      options: MapOptions(
                        initialCenter:
                            LatLng(position!.latitude, position!.longitude),
                        initialZoom: 16,
                        interactionOptions: const InteractionOptions(
                          flags: InteractiveFlag.drag |
                              InteractiveFlag.pinchZoom |
                              InteractiveFlag.doubleTapZoom,
                        ),
                      ),
                      children: [
                        TileLayer(
                          urlTemplate:
                              'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
                          userAgentPackageName: 'nuist_flutter_mobile',
                        ),
                        MarkerLayer(
                          markers: [
                            Marker(
                              point:
                                  LatLng(position!.latitude, position!.longitude),
                              width: 54,
                              height: 54,
                              child: Container(
                                decoration: const BoxDecoration(
                                  color: _attendancePrimary,
                                  shape: BoxShape.circle,
                                  boxShadow: [
                                    BoxShadow(
                                      color: Color(0x22003B39),
                                      blurRadius: 14,
                                      offset: Offset(0, 6),
                                    ),
                                  ],
                                ),
                                child: const Icon(
                                  Icons.location_on_rounded,
                                  color: Colors.white,
                                  size: 28,
                                ),
                              ),
                            ),
                          ],
                        ),
                      ],
                    ),
                  ),
                ),
                const SizedBox(height: 12),
                Text(
                  _coordinateLabel(position!),
                  style: const TextStyle(
                    color: _attendanceText,
                    fontWeight: FontWeight.w800,
                    fontSize: 14,
                  ),
                ),
                const SizedBox(height: 6),
                Text(
                  'Akurasi ${position!.accuracy.toStringAsFixed(1)} m • Altitude ${position!.altitude.toStringAsFixed(1)} m • Sampel GPS $locationReadingsCount',
                  style: const TextStyle(
                    color: _attendanceMuted,
                    fontSize: 12,
                    height: 1.4,
                  ),
                ),
              ],
            ),
        ],
      ),
    );
  }
}

class _AttendanceSelfieCard extends StatelessWidget {
  const _AttendanceSelfieCard({
    required this.selfieFile,
    required this.capturingSelfie,
    required this.onCaptureSelfie,
    required this.onClearSelfie,
  });

  final XFile? selfieFile;
  final bool capturingSelfie;
  final Future<void> Function() onCaptureSelfie;
  final VoidCallback onClearSelfie;

  @override
  Widget build(BuildContext context) {
    return AppSectionCard(
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Container(
                width: 40,
                height: 40,
                decoration: BoxDecoration(
                  color: _attendanceSoft,
                  borderRadius: BorderRadius.circular(14),
                ),
                child: const Icon(
                  Icons.camera_alt_outlined,
                  color: _attendancePrimary,
                ),
              ),
              const SizedBox(width: 12),
              const Expanded(
                child: Text(
                  'Selfie Presensi',
                  style: TextStyle(
                    color: _attendanceText,
                    fontWeight: FontWeight.w800,
                    fontSize: 16,
                  ),
                ),
              ),
              TextButton(
                onPressed: capturingSelfie ? null : onCaptureSelfie,
                child: Text(
                  capturingSelfie ? 'Membuka...' : 'Ambil Selfie',
                  style: const TextStyle(
                    color: _attendancePrimary,
                    fontWeight: FontWeight.w800,
                  ),
                ),
              ),
            ],
          ),
          const SizedBox(height: 12),
          if (selfieFile == null)
            Container(
              width: double.infinity,
              padding: const EdgeInsets.symmetric(vertical: 26, horizontal: 14),
              decoration: BoxDecoration(
                color: _attendanceSoft,
                borderRadius: BorderRadius.circular(20),
                border: Border.all(color: const Color(0xFFF8D7B1)),
              ),
              child: const Column(
                children: [
                  Icon(
                    Icons.account_circle_outlined,
                    size: 42,
                    color: _attendancePrimary,
                  ),
                  SizedBox(height: 8),
                  Text(
                    'Belum ada selfie',
                    style: TextStyle(
                      color: _attendanceText,
                      fontWeight: FontWeight.w800,
                    ),
                  ),
                  SizedBox(height: 4),
                  Text(
                    'Gunakan kamera depan untuk mengambil selfie presensi.',
                    textAlign: TextAlign.center,
                    style: TextStyle(
                      color: _attendanceMuted,
                      fontSize: 12,
                      height: 1.4,
                    ),
                  ),
                ],
              ),
            )
          else
            Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                ClipRRect(
                  borderRadius: BorderRadius.circular(20),
                  child: AspectRatio(
                    aspectRatio: 1,
                    child: Image.file(
                      File(selfieFile!.path),
                      fit: BoxFit.cover,
                    ),
                  ),
                ),
                const SizedBox(height: 10),
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
                    _StatusPill(label: item['status'] as String? ?? '-'),
                  ],
                ),
                const SizedBox(height: 6),
                Text(
                  '${item['check_in'] ?? '-'} • ${item['check_out'] ?? '-'}',
                  style: const TextStyle(
                    color: Color(0xFF4D6663),
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
              ],
            ),
          ),
        ],
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
          eyebrow.toUpperCase(),
          style: const TextStyle(
            color: _attendancePrimary,
            fontSize: 11,
            fontWeight: FontWeight.w800,
            letterSpacing: 0.6,
          ),
        ),
        const SizedBox(height: 4),
        Text(
          title,
          style: const TextStyle(
            color: _attendanceText,
            fontSize: 17,
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
