import 'dart:async';

import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:flutter_map/flutter_map.dart';
import 'package:geocoding/geocoding.dart';
import 'package:geolocator/geolocator.dart';
import 'package:latlong2/latlong.dart';

import '../../services/teacher_mobile_repository.dart';
import '../../widgets/app/app_empty_state.dart';
import '../../widgets/app/app_section_card.dart';
import '../../widgets/app/teacher_page_header.dart';

const _journalPrimary = Color(0xFFF49637);
const _journalPrimaryDark = Color(0xFFC96A19);
const _journalText = Color(0xFF7A4212);
const _journalMuted = Color(0xFF6F8580);
const _journalSoft = Color(0xFFF2FBF7);
const _journalWarning = Color(0xFFF4A12A);
const _journalDanger = Color(0xFFB42318);

class TeacherTeachingJournalPage extends StatefulWidget {
  const TeacherTeachingJournalPage({
    super.key,
    required this.repository,
    required this.onBackToHome,
    required this.isActive,
    required this.scheduleRevision,
  });

  final TeacherMobileRepository repository;
  final VoidCallback onBackToHome;
  final bool isActive;
  final int scheduleRevision;

  @override
  State<TeacherTeachingJournalPage> createState() =>
      _TeacherTeachingJournalPageState();
}

class _TeacherTeachingJournalPageState
    extends State<TeacherTeachingJournalPage> with WidgetsBindingObserver {
  late Future<Map<String, dynamic>> _future;
  Position? _position;
  String? _locationAddress;
  String? _locationError;
  List<Map<String, dynamic>> _locationReadings = const [];
  bool _loadingLocation = false;
  late DateTime _now;
  Timer? _clockTimer;
  String? _submissionFeedbackMessage;
  bool? _submissionFeedbackSuccess;

  final TextEditingController _materiController = TextEditingController();
  final TextEditingController _classTotalController = TextEditingController();
  final TextEditingController _presentController = TextEditingController();

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addObserver(this);
    _future = widget.repository.getTeachingJournal();
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
  void didUpdateWidget(covariant TeacherTeachingJournalPage oldWidget) {
    super.didUpdateWidget(oldWidget);
    if (oldWidget.scheduleRevision != widget.scheduleRevision) {
      unawaited(_refresh());
    }
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
    _materiController.dispose();
    _classTotalController.dispose();
    _presentController.dispose();
    super.dispose();
  }

  Future<void> _refresh() async {
    final future = widget.repository.getTeachingJournal();
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

  void _setSubmissionFeedback({
    required String message,
    required bool isSuccess,
  }) {
    if (!mounted) {
      return;
    }

    setState(() {
      _submissionFeedbackMessage = message;
      _submissionFeedbackSuccess = isSuccess;
    });
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
          'Izin lokasi ditolak. Jurnal mengajar memerlukan akses lokasi.',
        );
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

  Future<String> _submitTeachingAttendance({
    required Map<String, dynamic> schedule,
    required String materi,
    required int presentStudents,
    int? classTotalStudents,
  }) async {
    if (_position == null) {
      throw Exception('Lokasi belum tersedia. Ambil lokasi terlebih dahulu');
    }

    final locationCheck = await widget.repository.checkTeachingJournalLocation(
      scheduleId: (schedule['id'] as num).toInt(),
      latitude: _position!.latitude,
      longitude: _position!.longitude,
    );

    if (locationCheck['is_within_polygon'] != true) {
      throw Exception(
        (locationCheck['_message'] as String?) ??
            'Lokasi Anda berada di luar area sekolah.',
      );
    }

    final result = await widget.repository.submitTeachingJournalAttendance(
      payload: {
        'teaching_schedule_id': schedule['id'],
        'latitude': _position!.latitude,
        'longitude': _position!.longitude,
        'lokasi': _locationAddress ?? _coordinateLabel(_position!),
        'accuracy': _position!.accuracy,
        'altitude': _position!.altitude,
        'speed': _position!.speed,
        'device_info': 'flutter_mobile_${defaultTargetPlatform.name}',
        'location_readings': _locationReadings,
        'materi': materi,
        'present_students': presentStudents,
        'class_total_students': classTotalStudents,
      },
    );

    await _refresh();

    return (result['_message'] as String?) ??
        'Presensi mengajar berhasil dicatat.';
  }

  Future<void> _openAttendanceSheet(Map<String, dynamic> schedule) async {
    final storedClassTotal =
        (schedule['class_total_students'] as num?)?.toInt();
    _materiController.clear();
    _classTotalController.clear();
    _presentController.clear();

    if (!mounted) {
      return;
    }

    bool isSubmitting = false;

    await showModalBottomSheet<void>(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (sheetContext) {
        return StatefulBuilder(
          builder: (context, sheetSetState) {
            final classTotal = storedClassTotal ??
                int.tryParse(_classTotalController.text.trim());
            final presentStudents =
                int.tryParse(_presentController.text.trim());
            final canSubmit = _position != null &&
                !isSubmitting &&
                _materiController.text.trim().isNotEmpty &&
                classTotal != null &&
                classTotal > 0 &&
                presentStudents != null &&
                presentStudents >= 0 &&
                presentStudents <= classTotal;

            final percentage = classTotal != null &&
                    classTotal > 0 &&
                    presentStudents != null &&
                    presentStudents >= 0 &&
                    presentStudents <= classTotal
                ? ((presentStudents / classTotal) * 100).toStringAsFixed(1)
                : null;

            return AnimatedPadding(
              duration: const Duration(milliseconds: 180),
              curve: Curves.easeOut,
              padding: EdgeInsets.only(
                bottom: MediaQuery.of(context).viewInsets.bottom,
              ),
              child: Container(
                decoration: const BoxDecoration(
                  color: Color(0xFFF7FBFA),
                  borderRadius: BorderRadius.vertical(top: Radius.circular(28)),
                ),
                child: SafeArea(
                  top: false,
                  child: SingleChildScrollView(
                    padding: const EdgeInsets.fromLTRB(16, 16, 16, 24),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Center(
                          child: Container(
                            width: 46,
                            height: 4,
                            decoration: BoxDecoration(
                              color: const Color(0xFFD7E5E1),
                              borderRadius: BorderRadius.circular(999),
                            ),
                          ),
                        ),
                        const SizedBox(height: 16),
                        const Text(
                          'Presensi Mengajar',
                          style: TextStyle(
                            color: _journalText,
                            fontSize: 20,
                            fontWeight: FontWeight.w800,
                          ),
                        ),
                        const SizedBox(height: 6),
                        const Text(
                          'Lengkapi materi, kehadiran siswa, dan pastikan lokasi valid sebelum mengirim.',
                          style: TextStyle(
                            color: _journalMuted,
                            fontSize: 12,
                            height: 1.45,
                          ),
                        ),
                        const SizedBox(height: 16),
                        AppSectionCard(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(
                                schedule['subject'] as String? ?? '-',
                                style: const TextStyle(
                                  color: _journalText,
                                  fontSize: 16,
                                  fontWeight: FontWeight.w800,
                                ),
                              ),
                              const SizedBox(height: 6),
                              Text(
                                '${schedule['class_name'] ?? '-'} • ${schedule['school_name'] ?? '-'}',
                                style: const TextStyle(
                                  color: _journalMuted,
                                  fontWeight: FontWeight.w700,
                                ),
                              ),
                              const SizedBox(height: 10),
                              _MiniBadge(
                                label:
                                    '${schedule['start_time'] ?? '-'} - ${schedule['end_time'] ?? '-'}',
                                color: _journalPrimary,
                              ),
                            ],
                          ),
                        ),
                        const SizedBox(height: 14),
                        AppSectionCard(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Row(
                                children: [
                                  const Expanded(
                                    child: Text(
                                      'Lokasi Saat Ini',
                                      style: TextStyle(
                                        color: _journalText,
                                        fontSize: 15,
                                        fontWeight: FontWeight.w800,
                                      ),
                                    ),
                                  ),
                                  TextButton(
                                    onPressed: _loadingLocation
                                        ? null
                                        : () async {
                                            await _captureLocation();
                                            if (sheetContext.mounted) {
                                              sheetSetState(() {});
                                            }
                                          },
                                    child: Text(
                                      _loadingLocation
                                          ? 'Memuat...'
                                          : 'Perbarui',
                                      style: const TextStyle(
                                        color: _journalPrimary,
                                        fontWeight: FontWeight.w800,
                                      ),
                                    ),
                                  ),
                                ],
                              ),
                              const SizedBox(height: 8),
                              if (_position == null)
                                Text(
                                  _locationError ??
                                      'Lokasi belum tersedia. Ambil lokasi terlebih dahulu.',
                                  style: TextStyle(
                                    color: _locationError == null
                                        ? _journalMuted
                                        : _journalDanger,
                                    fontSize: 12,
                                    height: 1.45,
                                  ),
                                )
                              else ...[
                                Container(
                                  width: double.infinity,
                                  padding: const EdgeInsets.all(12),
                                  decoration: BoxDecoration(
                                    color: _journalSoft,
                                    borderRadius: BorderRadius.circular(18),
                                    border: Border.all(
                                      color: const Color(0xFFD5EBE2),
                                    ),
                                  ),
                                  child: Text(
                                    _locationAddress ??
                                        _coordinateLabel(_position!),
                                    style: const TextStyle(
                                      color: _journalText,
                                      fontWeight: FontWeight.w700,
                                      height: 1.45,
                                    ),
                                  ),
                                ),
                                const SizedBox(height: 8),
                                Text(
                                  'Akurasi ${_position!.accuracy.toStringAsFixed(1)} m • Sampel GPS ${_locationReadings.length}',
                                  style: const TextStyle(
                                    color: _journalMuted,
                                    fontSize: 11,
                                  ),
                                ),
                              ],
                            ],
                          ),
                        ),
                        const SizedBox(height: 14),
                        AppSectionCard(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              const Text(
                                'Kehadiran Siswa',
                                style: TextStyle(
                                  color: _journalText,
                                  fontSize: 15,
                                  fontWeight: FontWeight.w800,
                                ),
                              ),
                              const SizedBox(height: 10),
                              if (storedClassTotal != null)
                                Container(
                                  width: double.infinity,
                                  padding: const EdgeInsets.all(12),
                                  decoration: BoxDecoration(
                                    color: _journalSoft,
                                    borderRadius: BorderRadius.circular(18),
                                    border: Border.all(
                                      color: const Color(0xFFD5EBE2),
                                    ),
                                  ),
                                  child: Text(
                                    'Jumlah siswa kelas sudah tersimpan: $storedClassTotal siswa.',
                                    style: const TextStyle(
                                      color: _journalText,
                                      fontWeight: FontWeight.w700,
                                    ),
                                  ),
                                )
                              else
                                TextField(
                                  controller: _classTotalController,
                                  keyboardType: TextInputType.number,
                                  onChanged: (_) => sheetSetState(() {}),
                                  decoration: const InputDecoration(
                                    labelText: 'Jumlah siswa di kelas',
                                    hintText: 'Contoh: 32',
                                  ),
                                ),
                              const SizedBox(height: 12),
                              TextField(
                                controller: _presentController,
                                keyboardType: TextInputType.number,
                                onChanged: (_) => sheetSetState(() {}),
                                decoration: const InputDecoration(
                                  labelText: 'Jumlah siswa hadir',
                                  hintText: 'Contoh: 30',
                                ),
                              ),
                              const SizedBox(height: 10),
                              Container(
                                width: double.infinity,
                                padding: const EdgeInsets.all(12),
                                decoration: BoxDecoration(
                                  color: percentage == null
                                      ? const Color(0xFFF6F8F8)
                                      : presentStudents != null &&
                                              classTotal != null &&
                                              presentStudents <= classTotal
                                          ? const Color(0xFFE8F8EE)
                                          : const Color(0xFFFFF2F0),
                                  borderRadius: BorderRadius.circular(18),
                                ),
                                child: Text(
                                  percentage == null
                                      ? 'Isi jumlah siswa hadir untuk melihat persentase.'
                                      : presentStudents != null &&
                                              classTotal != null &&
                                              presentStudents <= classTotal
                                          ? 'Kehadiran siswa: $presentStudents/$classTotal ($percentage%)'
                                          : 'Jumlah siswa hadir tidak boleh melebihi jumlah siswa di kelas.',
                                  style: TextStyle(
                                    color: percentage == null
                                        ? _journalMuted
                                        : presentStudents != null &&
                                                classTotal != null &&
                                                presentStudents <= classTotal
                                            ? _journalPrimary
                                            : _journalDanger,
                                    fontWeight: FontWeight.w700,
                                  ),
                                ),
                              ),
                            ],
                          ),
                        ),
                        const SizedBox(height: 14),
                        AppSectionCard(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              const Text(
                                'Materi atau Topik',
                                style: TextStyle(
                                  color: _journalText,
                                  fontSize: 15,
                                  fontWeight: FontWeight.w800,
                                ),
                              ),
                              const SizedBox(height: 10),
                              TextField(
                                controller: _materiController,
                                minLines: 3,
                                maxLines: 5,
                                maxLength: 1000,
                                onChanged: (_) => sheetSetState(() {}),
                                decoration: const InputDecoration(
                                  hintText:
                                      'Contoh: Persamaan linear satu variabel',
                                ),
                              ),
                            ],
                          ),
                        ),
                        const SizedBox(height: 16),
                        SizedBox(
                          width: double.infinity,
                          child: ElevatedButton.icon(
                            onPressed: canSubmit
                                ? () async {
                                    final resolvedClassTotal =
                                        storedClassTotal ??
                                            int.tryParse(
                                              _classTotalController.text.trim(),
                                            );
                                    final resolvedPresent = int.tryParse(
                                      _presentController.text.trim(),
                                    );

                                    if (resolvedClassTotal == null ||
                                        resolvedClassTotal <= 0 ||
                                        resolvedPresent == null ||
                                        resolvedPresent < 0 ||
                                        resolvedPresent > resolvedClassTotal ||
                                        _materiController.text.trim().isEmpty) {
                                      sheetSetState(() {});
                                      return;
                                    }

                                    sheetSetState(() {
                                      isSubmitting = true;
                                    });

                                    try {
                                      final message =
                                          await _submitTeachingAttendance(
                                        schedule: schedule,
                                        materi: _materiController.text.trim(),
                                        presentStudents: resolvedPresent,
                                        classTotalStudents:
                                            storedClassTotal == null
                                                ? resolvedClassTotal
                                                : null,
                                      );

                                      if (!sheetContext.mounted) {
                                        return;
                                      }

                                      Navigator.of(sheetContext).pop();

                                      _setSubmissionFeedback(
                                        message: message,
                                        isSuccess: true,
                                      );
                                      if (mounted) {
                                        ScaffoldMessenger.of(context)
                                            .showSnackBar(
                                          SnackBar(content: Text(message)),
                                        );
                                      }
                                    } catch (error) {
                                      if (!sheetContext.mounted) {
                                        return;
                                      }

                                      Navigator.of(sheetContext).pop();

                                      final message = error
                                          .toString()
                                          .replaceFirst('Exception: ', '');
                                      _setSubmissionFeedback(
                                        message: message,
                                        isSuccess: false,
                                      );
                                      if (mounted) {
                                        ScaffoldMessenger.of(context)
                                            .showSnackBar(
                                          SnackBar(content: Text(message)),
                                        );
                                      }
                                    }
                                  }
                                : null,
                            style: ElevatedButton.styleFrom(
                              backgroundColor: _journalPrimary,
                              disabledBackgroundColor: const Color(0xFFD7E5E1),
                              foregroundColor: Colors.white,
                              padding: const EdgeInsets.symmetric(vertical: 15),
                              shape: RoundedRectangleBorder(
                                borderRadius: BorderRadius.circular(18),
                              ),
                              elevation: 0,
                            ),
                            icon: isSubmitting
                                ? const SizedBox(
                                    width: 18,
                                    height: 18,
                                    child: CircularProgressIndicator(
                                      strokeWidth: 2.2,
                                      color: Colors.white,
                                    ),
                                  )
                                : const Icon(
                                    Icons.check_circle_outline_rounded),
                            label: Text(
                              isSubmitting
                                  ? 'Mengirim...'
                                  : 'Kirim Presensi Mengajar',
                              style: const TextStyle(
                                fontWeight: FontWeight.w800,
                              ),
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
              ),
            );
          },
        );
      },
    );
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
                title: 'Jurnal Mengajar',
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
                _JournalContent(
                  data: snapshot.data ?? const <String, dynamic>{},
                  now: _now,
                  submissionFeedbackMessage: _submissionFeedbackMessage,
                  submissionFeedbackSuccess: _submissionFeedbackSuccess,
                  position: _position,
                  locationAddress: _locationAddress,
                  locationError: _locationError,
                  loadingLocation: _loadingLocation,
                  locationReadingsCount: _locationReadings.length,
                  onRefreshLocation: _captureLocation,
                  onOpenAttendanceSheet: _openAttendanceSheet,
                ),
            ],
          ),
        );
      },
    );
  }
}

class _JournalContent extends StatelessWidget {
  const _JournalContent({
    required this.data,
    required this.now,
    required this.submissionFeedbackMessage,
    required this.submissionFeedbackSuccess,
    required this.position,
    required this.locationAddress,
    required this.locationError,
    required this.loadingLocation,
    required this.locationReadingsCount,
    required this.onRefreshLocation,
    required this.onOpenAttendanceSheet,
  });

  final Map<String, dynamic> data;
  final DateTime now;
  final String? submissionFeedbackMessage;
  final bool? submissionFeedbackSuccess;
  final Position? position;
  final String? locationAddress;
  final String? locationError;
  final bool loadingLocation;
  final int locationReadingsCount;
  final Future<void> Function() onRefreshLocation;
  final Future<void> Function(Map<String, dynamic> schedule)
      onOpenAttendanceSheet;

  @override
  Widget build(BuildContext context) {
    final summary = Map<String, dynamic>.from(
      (data['summary'] as Map?) ?? const <String, dynamic>{},
    );
    final todaySummary = Map<String, dynamic>.from(
      (data['today_summary'] as Map?) ?? const <String, dynamic>{},
    );
    final approvedIzin = (data['approved_izin_today'] as Map?) == null
        ? null
        : Map<String, dynamic>.from(data['approved_izin_today'] as Map);
    final todaySchedules = ((data['today_schedules'] as List?) ?? const [])
        .whereType<Map>()
        .map((item) => Map<String, dynamic>.from(item))
        .toList();
    final items = ((data['items'] as List?) ?? const [])
        .whereType<Map>()
        .map((item) => Map<String, dynamic>.from(item))
        .toList();

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        if (submissionFeedbackMessage != null &&
            submissionFeedbackMessage!.trim().isNotEmpty) ...[
          _InfoBanner(
            color: submissionFeedbackSuccess == true
                ? const Color(0xFF2E8B57)
                : _journalDanger,
            icon: submissionFeedbackSuccess == true
                ? Icons.check_circle_rounded
                : Icons.error_rounded,
            message: submissionFeedbackMessage!,
          ),
          const SizedBox(height: 14),
        ],
        _JournalHeroCard(
          todayLabel: data['today_label'] as String? ?? '-',
          currentTime: _timeLabel(now),
          totalSchedules: '${todaySummary['total_schedules'] ?? 0}',
          completedSchedules: '${todaySummary['completed_schedules'] ?? 0}',
        ),
        if (approvedIzin != null) ...[
          const SizedBox(height: 14),
          _InfoBanner(
            color: const Color(0xFF0EA5E9),
            icon: Icons.info_outline_rounded,
            message: approvedIzin['message'] as String? ??
                'Anda tercatat izin disetujui hari ini.',
            note: approvedIzin['note'] as String?,
          ),
        ],
        const SizedBox(height: 18),
        Row(
          children: [
            Expanded(
              child: _JournalSummaryTile(
                label: 'Jadwal Hari Ini',
                value: '${todaySummary['total_schedules'] ?? 0}',
                accent: _journalPrimary,
                icon: Icons.calendar_today_rounded,
              ),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: _JournalSummaryTile(
                label: 'Sudah Presensi',
                value: '${todaySummary['completed_schedules'] ?? 0}',
                accent: const Color(0xFF1F9D73),
                icon: Icons.check_circle_rounded,
              ),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: _JournalSummaryTile(
                label: 'Total Jurnal',
                value: '${summary['total_entries'] ?? 0}',
                accent: _journalWarning,
                icon: Icons.menu_book_rounded,
              ),
            ),
          ],
        ),
        const SizedBox(height: 18),
        _AttendanceLocationCard(
          position: position,
          locationAddress: locationAddress,
          locationError: locationError,
          locationReadingsCount: locationReadingsCount,
          loadingLocation: loadingLocation,
          onRefreshLocation: onRefreshLocation,
        ),
        const SizedBox(height: 18),
        const _PageSectionHeading(
          eyebrow: 'Hari Ini',
          title: 'Jadwal Mengajar',
        ),
        const SizedBox(height: 12),
        if (todaySchedules.isEmpty)
          const AppSectionCard(
            child: AppEmptyState(
              title: 'Tidak ada jadwal mengajar hari ini',
              message: 'Jadwal aktif untuk hari ini akan tampil di sini.',
              icon: Icons.calendar_month_outlined,
            ),
          )
        else
          ...todaySchedules.map(
            (schedule) => Padding(
              padding: const EdgeInsets.only(bottom: 12),
              child: _TeachingScheduleTile(
                item: schedule,
                onTakeAttendance: schedule['can_submit'] == true
                    ? () => onOpenAttendanceSheet(schedule)
                    : null,
              ),
            ),
          ),
        const SizedBox(height: 18),
        const _PageSectionHeading(
          eyebrow: 'Riwayat',
          title: 'Jurnal Bulan Ini',
        ),
        const SizedBox(height: 12),
        if (items.isEmpty)
          const AppSectionCard(
            child: AppEmptyState(
              title: 'Belum ada jurnal mengajar',
              message: 'Riwayat presensi mengajar akan tampil di sini.',
              icon: Icons.menu_book_outlined,
            ),
          )
        else
          ...items.map(
            (item) => Padding(
              padding: const EdgeInsets.only(bottom: 12),
              child: _JournalEntryTile(item: item),
            ),
          ),
      ],
    );
  }
}

class _JournalHeroCard extends StatelessWidget {
  const _JournalHeroCard({
    required this.todayLabel,
    required this.currentTime,
    required this.totalSchedules,
    required this.completedSchedules,
  });

  final String todayLabel;
  final String currentTime;
  final String totalSchedules;
  final String completedSchedules;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(18),
      decoration: BoxDecoration(
        gradient: const LinearGradient(
          colors: [_journalPrimaryDark, _journalPrimary],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
        borderRadius: BorderRadius.circular(28),
        boxShadow: const [
          BoxShadow(
            color: Color(0x14003B39),
            blurRadius: 20,
            offset: Offset(0, 10),
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
                    color: Colors.white.withOpacity(0.84),
                    fontSize: 12,
                    fontWeight: FontWeight.w700,
                  ),
                ),
              ),
              Container(
                padding:
                    const EdgeInsets.symmetric(horizontal: 12, vertical: 7),
                decoration: BoxDecoration(
                  color: Colors.white.withOpacity(0.16),
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
          const SizedBox(height: 14),
          const Text(
            'Jurnal Mengajar',
            style: TextStyle(
              color: Colors.white,
              fontSize: 24,
              fontWeight: FontWeight.w800,
            ),
          ),
          const SizedBox(height: 6),
          Text(
            'Pantau jadwal hari ini, lokasi presensi, dan kirim jurnal mengajar dari satu halaman.',
            style: TextStyle(
              color: Colors.white.withOpacity(0.84),
              fontSize: 12,
              height: 1.45,
            ),
          ),
          const SizedBox(height: 16),
          Row(
            children: [
              Expanded(
                child: _HeroMetric(
                  label: 'Jadwal Hari Ini',
                  value: totalSchedules,
                ),
              ),
              const SizedBox(width: 10),
              Expanded(
                child: _HeroMetric(
                  label: 'Sudah Presensi',
                  value: completedSchedules,
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }
}

class _HeroMetric extends StatelessWidget {
  const _HeroMetric({
    required this.label,
    required this.value,
  });

  final String label;
  final String value;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: Colors.white.withOpacity(0.14),
        borderRadius: BorderRadius.circular(18),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            value,
            style: const TextStyle(
              color: Colors.white,
              fontSize: 20,
              fontWeight: FontWeight.w800,
            ),
          ),
          const SizedBox(height: 3),
          Text(
            label,
            style: TextStyle(
              color: Colors.white.withOpacity(0.82),
              fontSize: 11,
              fontWeight: FontWeight.w700,
            ),
          ),
        ],
      ),
    );
  }
}

class _JournalSummaryTile extends StatelessWidget {
  const _JournalSummaryTile({
    required this.label,
    required this.value,
    required this.accent,
    required this.icon,
  });

  final String label;
  final String value;
  final Color accent;
  final IconData icon;

  @override
  Widget build(BuildContext context) {
    return AppSectionCard(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 14),
      child: Column(
        children: [
          Container(
            width: 34,
            height: 34,
            decoration: BoxDecoration(
              color: accent.withOpacity(0.12),
              borderRadius: BorderRadius.circular(12),
            ),
            child: Icon(icon, color: accent, size: 18),
          ),
          const SizedBox(height: 10),
          Text(
            value,
            style: const TextStyle(
              color: _journalText,
              fontSize: 18,
              fontWeight: FontWeight.w800,
            ),
          ),
          const SizedBox(height: 4),
          Text(
            label,
            textAlign: TextAlign.center,
            style: const TextStyle(
              color: _journalMuted,
              fontSize: 11,
              fontWeight: FontWeight.w700,
            ),
          ),
        ],
      ),
    );
  }
}

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
                  color: _journalSoft,
                  borderRadius: BorderRadius.circular(14),
                ),
                child: const Icon(
                  Icons.location_on_outlined,
                  color: _journalPrimary,
                ),
              ),
              const SizedBox(width: 12),
              const Expanded(
                child: Text(
                  'Lokasi Presensi Mengajar',
                  style: TextStyle(
                    color: _journalText,
                    fontWeight: FontWeight.w800,
                    fontSize: 16,
                  ),
                ),
              ),
              TextButton(
                onPressed: loadingLocation ? null : onRefreshLocation,
                child: Text(
                  loadingLocation ? 'Memuat...' : 'Perbarui',
                  style: const TextStyle(
                    color: _journalPrimary,
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
                  'Lokasi belum diambil. Halaman ini akan mencoba mengambil lokasi otomatis saat dibuka.',
              style: TextStyle(
                color: locationError == null ? _journalMuted : _journalDanger,
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
                    color: _journalSoft,
                    borderRadius: BorderRadius.circular(18),
                    border: Border.all(color: const Color(0xFFD5EBE2)),
                  ),
                  child: Text(
                    locationAddress ?? _coordinateLabel(position!),
                    style: const TextStyle(
                      color: _journalText,
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
                              point: LatLng(
                                  position!.latitude, position!.longitude),
                              width: 54,
                              height: 54,
                              child: Container(
                                decoration: const BoxDecoration(
                                  color: _journalPrimary,
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
                    color: _journalText,
                    fontWeight: FontWeight.w800,
                    fontSize: 14,
                  ),
                ),
                const SizedBox(height: 6),
                Text(
                  'Akurasi ${position!.accuracy.toStringAsFixed(1)} m • Altitude ${position!.altitude.toStringAsFixed(1)} m • Sampel GPS $locationReadingsCount',
                  style: const TextStyle(
                    color: _journalMuted,
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

class _TeachingScheduleTile extends StatelessWidget {
  const _TeachingScheduleTile({
    required this.item,
    required this.onTakeAttendance,
  });

  final Map<String, dynamic> item;
  final VoidCallback? onTakeAttendance;

  @override
  Widget build(BuildContext context) {
    final attendance = (item['attendance'] as Map?) == null
        ? null
        : Map<String, dynamic>.from(item['attendance'] as Map);
    final timeState = item['time_state'] as String? ?? 'after';
    final status = item['status'] as String? ?? 'pending';
    final canSubmit = item['can_submit'] == true;

    return AppSectionCard(
      padding: const EdgeInsets.all(14),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Container(
                width: 44,
                height: 44,
                decoration: BoxDecoration(
                  color: _journalSoft,
                  borderRadius: BorderRadius.circular(16),
                ),
                child: const Icon(
                  Icons.cast_for_education_rounded,
                  color: _journalPrimary,
                  size: 22,
                ),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      item['subject'] as String? ?? '-',
                      style: const TextStyle(
                        color: _journalText,
                        fontWeight: FontWeight.w800,
                        fontSize: 15,
                      ),
                    ),
                    const SizedBox(height: 4),
                    Text(
                      item['class_name'] as String? ?? '-',
                      style: const TextStyle(
                        color: _journalMuted,
                        fontWeight: FontWeight.w700,
                        fontSize: 12,
                      ),
                    ),
                    const SizedBox(height: 4),
                    Text(
                      '${item['start_time'] ?? '-'} - ${item['end_time'] ?? '-'} • ${item['school_name'] ?? '-'}',
                      style: const TextStyle(
                        color: _journalMuted,
                        fontSize: 11,
                        height: 1.35,
                      ),
                    ),
                  ],
                ),
              ),
              _StatusPill(
                label: item['status_label'] as String? ?? 'Belum Presensi',
                color: status == 'hadir'
                    ? const Color(0xFF1F9D73)
                    : status == 'izin'
                        ? const Color(0xFF0EA5E9)
                        : _journalWarning,
              ),
            ],
          ),
          const SizedBox(height: 12),
          if (attendance != null)
            Container(
              width: double.infinity,
              padding: const EdgeInsets.all(12),
              decoration: BoxDecoration(
                color: status == 'izin'
                    ? const Color(0xFFF0F9FF)
                    : const Color(0xFFEFFAF4),
                borderRadius: BorderRadius.circular(18),
                border: Border.all(
                  color: status == 'izin'
                      ? const Color(0xFFBAE6FD)
                      : const Color(0xFFB9E5C8),
                ),
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    status == 'izin'
                        ? 'Jadwal ini tercatat sebagai izin.'
                        : 'Presensi berhasil pada ${attendance['time'] ?? '-'}',
                    style: TextStyle(
                      color: status == 'izin'
                          ? const Color(0xFF0369A1)
                          : const Color(0xFF166534),
                      fontWeight: FontWeight.w800,
                    ),
                  ),
                  const SizedBox(height: 8),
                  Text(
                    attendance['materi'] as String? ?? '-',
                    style: const TextStyle(
                      color: _journalText,
                      height: 1.45,
                    ),
                  ),
                  if (attendance['present_students'] != null &&
                      attendance['class_total_students'] != null) ...[
                    const SizedBox(height: 10),
                    Wrap(
                      spacing: 8,
                      runSpacing: 8,
                      children: [
                        _MiniBadge(
                          label:
                              'Hadir ${attendance['present_students']}/${attendance['class_total_students']}',
                          color: _journalPrimary,
                        ),
                        if (attendance['student_attendance_percentage'] != null)
                          _MiniBadge(
                            label:
                                '${attendance['student_attendance_percentage']}%',
                            color: _journalPrimary,
                          ),
                      ],
                    ),
                  ],
                ],
              ),
            )
          else if (status == 'izin')
            const _InfoBanner(
              color: Color(0xFF0EA5E9),
              icon: Icons.info_outline_rounded,
              message:
                  'Anda tidak dapat melakukan presensi mengajar karena izin hari ini sudah disetujui.',
            )
          else
            Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  item['time_message'] as String? ??
                      'Presensi belum dapat dilakukan.',
                  style: TextStyle(
                    color: timeState == 'within'
                        ? _journalPrimary
                        : timeState == 'before'
                            ? _journalWarning
                            : _journalMuted,
                    fontSize: 12,
                    fontWeight: FontWeight.w700,
                    height: 1.45,
                  ),
                ),
                const SizedBox(height: 12),
                SizedBox(
                  width: double.infinity,
                  child: ElevatedButton.icon(
                    onPressed: canSubmit ? onTakeAttendance : null,
                    style: ElevatedButton.styleFrom(
                      backgroundColor: _journalPrimary,
                      disabledBackgroundColor: const Color(0xFFD7E5E1),
                      foregroundColor: Colors.white,
                      padding: const EdgeInsets.symmetric(vertical: 13),
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(16),
                      ),
                      elevation: 0,
                    ),
                    icon: Icon(
                      canSubmit
                          ? Icons.check_circle_outline_rounded
                          : timeState == 'before'
                              ? Icons.schedule_rounded
                              : Icons.lock_clock_rounded,
                    ),
                    label: Text(
                      canSubmit
                          ? 'Lakukan Presensi'
                          : timeState == 'before'
                              ? 'Menunggu Waktu Mengajar'
                              : 'Waktu Mengajar Berakhir',
                      style: const TextStyle(
                        fontWeight: FontWeight.w800,
                      ),
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

class _JournalEntryTile extends StatelessWidget {
  const _JournalEntryTile({
    required this.item,
  });

  final Map<String, dynamic> item;

  @override
  Widget build(BuildContext context) {
    final percentage = item['student_attendance_percentage'];

    return AppSectionCard(
      padding: const EdgeInsets.all(14),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Container(
                width: 44,
                height: 44,
                decoration: BoxDecoration(
                  color: _journalSoft,
                  borderRadius: BorderRadius.circular(16),
                ),
                child: const Icon(
                  Icons.menu_book_rounded,
                  color: _journalPrimary,
                  size: 22,
                ),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      item['subject'] as String? ?? '-',
                      style: const TextStyle(
                        color: _journalText,
                        fontWeight: FontWeight.w800,
                        fontSize: 15,
                      ),
                    ),
                    const SizedBox(height: 4),
                    Text(
                      '${item['class_name'] ?? '-'} • ${item['date_label'] ?? '-'}',
                      style: const TextStyle(
                        color: _journalMuted,
                        fontWeight: FontWeight.w700,
                        fontSize: 12,
                      ),
                    ),
                  ],
                ),
              ),
              _StatusPill(
                label: item['status_label'] as String? ?? 'Hadir',
                color: (item['status'] as String?) == 'izin'
                    ? const Color(0xFF0EA5E9)
                    : const Color(0xFF1F9D73),
              ),
            ],
          ),
          const SizedBox(height: 12),
          Container(
            width: double.infinity,
            padding: const EdgeInsets.all(12),
            decoration: BoxDecoration(
              color: const Color(0xFFFCFDFC),
              borderRadius: BorderRadius.circular(18),
              border: Border.all(color: const Color(0xFFE2ECE5)),
            ),
            child: Text(
              item['materi'] as String? ?? '-',
              style: const TextStyle(
                color: Color(0xFF385452),
                fontSize: 12,
                height: 1.4,
              ),
            ),
          ),
          const SizedBox(height: 12),
          Wrap(
            spacing: 8,
            runSpacing: 8,
            children: [
              if (item['present_students'] != null &&
                  item['class_total_students'] != null)
                _MiniBadge(
                  label:
                      'Hadir ${item['present_students']}/${item['class_total_students']}',
                  color: _journalPrimary,
                ),
              if (percentage != null)
                _MiniBadge(
                  label: '$percentage%',
                  color: _journalPrimary,
                ),
              if ((item['time'] as String?)?.trim().isNotEmpty == true)
                _MiniBadge(
                  label: item['time'] as String,
                  color: _journalPrimary,
                ),
            ],
          ),
        ],
      ),
    );
  }
}

class _MiniBadge extends StatelessWidget {
  const _MiniBadge({
    required this.label,
    required this.color,
  });

  final String label;
  final Color color;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 7),
      decoration: BoxDecoration(
        color: color.withOpacity(0.1),
        borderRadius: BorderRadius.circular(999),
      ),
      child: Text(
        label,
        style: TextStyle(
          color: color,
          fontSize: 11,
          fontWeight: FontWeight.w800,
        ),
      ),
    );
  }
}

class _InfoBanner extends StatelessWidget {
  const _InfoBanner({
    required this.color,
    required this.icon,
    required this.message,
    this.note,
  });

  final Color color;
  final IconData icon;
  final String message;
  final String? note;

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: color.withOpacity(0.1),
        borderRadius: BorderRadius.circular(18),
        border: Border.all(color: color.withOpacity(0.2)),
      ),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Icon(icon, color: color),
          const SizedBox(width: 10),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  message,
                  style: TextStyle(
                    color: color,
                    fontWeight: FontWeight.w800,
                    height: 1.4,
                  ),
                ),
                if (note?.trim().isNotEmpty == true) ...[
                  const SizedBox(height: 4),
                  Text(
                    note!,
                    style: TextStyle(
                      color: color,
                      fontSize: 12,
                      height: 1.4,
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
    required this.color,
  });

  final String label;
  final Color color;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 7),
      decoration: BoxDecoration(
        color: color.withOpacity(0.12),
        borderRadius: BorderRadius.circular(999),
      ),
      child: Text(
        label,
        style: TextStyle(
          color: color,
          fontSize: 11,
          fontWeight: FontWeight.w800,
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
          color: _journalPrimary,
        ),
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
            color: _journalPrimary,
            fontSize: 11,
            fontWeight: FontWeight.w800,
            letterSpacing: 0.6,
          ),
        ),
        const SizedBox(height: 4),
        Text(
          title,
          style: const TextStyle(
            color: _journalText,
            fontSize: 18,
            fontWeight: FontWeight.w800,
          ),
        ),
      ],
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
              color: _journalDanger,
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

String _timeLabel(DateTime value) {
  final hour = value.hour.toString().padLeft(2, '0');
  final minute = value.minute.toString().padLeft(2, '0');
  final second = value.second.toString().padLeft(2, '0');
  return '$hour:$minute:$second';
}

String _coordinateLabel(Position position) {
  return '${position.latitude.toStringAsFixed(6)}, '
      '${position.longitude.toStringAsFixed(6)}';
}
