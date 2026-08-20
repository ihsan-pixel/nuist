import 'dart:async';

import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:geocoding/geocoding.dart';
import 'package:geolocator/geolocator.dart';

import '../../services/teacher_mobile_repository.dart';
import '../../widgets/app/app_empty_state.dart';
import '../../widgets/app/app_section_card.dart';
import '../../widgets/app/teacher_page_header.dart';

const _journalSurface = Color(0xFFFFFFFF);
const _journalPrimary = Color(0xFF00745A);
const _journalPrimaryDark = Color(0xFF00553F);
const _journalText = Color(0xFF172A24);
const _journalMuted = Color(0xFF64746E);
const _journalSoft = Color(0xFFE5F5F0);
const _journalSoftGreen = Color(0xFFE5F5F0);
const _journalSoftRed = Color(0xFFFEE2E2);
const _journalBorder = Color(0xFFDCE7E3);
const _journalWarning = Color(0xFFF59E0B);
const _journalDanger = Color(0xFFEF4444);
const _journalInfo = Color(0xFF00745A);

Duration _durationFromTime(String value) {
  final parts = value.split(':');
  if (parts.length < 2) {
    return Duration.zero;
  }

  final hours = int.tryParse(parts[0]) ?? 0;
  final minutes = int.tryParse(parts[1]) ?? 0;
  final seconds = parts.length > 2 ? int.tryParse(parts[2]) ?? 0 : 0;
  return Duration(hours: hours, minutes: minutes, seconds: seconds);
}

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

class _TeacherTeachingJournalPageState extends State<TeacherTeachingJournalPage>
    with WidgetsBindingObserver {
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
          locationSettings: const LocationSettings(
            accuracy: LocationAccuracy.best,
          ),
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

  Future<String> _submitTeachingAttendance({
    required Map<String, dynamic> schedule,
    required String materi,
    required int presentStudents,
    int? classTotalStudents,
    required bool isLateJournal,
  }) async {
    if (_position == null) {
      throw Exception(
        _locationError?.trim().isNotEmpty == true
            ? _locationError!
            : 'Lokasi belum tersedia. Tunggu GPS selesai dibaca lalu coba lagi.',
      );
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
        'attendance_mode': isLateJournal ? 'late' : 'regular',
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
    final timeState = schedule['time_state'] as String? ?? 'after';
    final isLateJournal = timeState == 'after';
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
            final submitLabel = isLateJournal
                ? 'Kirim Jurnal Susulan'
                : 'Kirim Presensi Mengajar';
            final submitHint = isLateJournal
                ? 'Jurnal ini tercatat sebagai terlambat/susulan karena jadwal sudah lewat.'
                : 'Presensi ini mengikuti jadwal aktif saat ini.';

            return AnimatedPadding(
              duration: const Duration(milliseconds: 180),
              curve: Curves.easeOut,
              padding: EdgeInsets.only(
                bottom: MediaQuery.of(context).viewInsets.bottom,
              ),
              child: Container(
                decoration: const BoxDecoration(
                  color: _journalSurface,
                  borderRadius: BorderRadius.vertical(top: Radius.circular(28)),
                ),
                child: SafeArea(
                  top: false,
                  child: SingleChildScrollView(
                    padding: const EdgeInsets.fromLTRB(14, 14, 14, 18),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Center(
                          child: Container(
                            width: 46,
                            height: 4,
                            decoration: BoxDecoration(
                              color: _journalBorder,
                              borderRadius: BorderRadius.circular(999),
                            ),
                          ),
                        ),
                        const SizedBox(height: 12),
                        const Text(
                          'Presensi Mengajar',
                          style: TextStyle(
                            color: _journalText,
                            fontSize: 18,
                            fontWeight: FontWeight.w800,
                          ),
                        ),
                        const SizedBox(height: 4),
                        const Text(
                          'Lengkapi materi, kehadiran siswa, dan pastikan lokasi valid sebelum mengirim.',
                          style: TextStyle(
                            color: _journalMuted,
                            fontSize: 12,
                            height: 1.45,
                          ),
                        ),
                        const SizedBox(height: 12),
                        AppSectionCard(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(
                                schedule['subject'] as String? ?? '-',
                                style: const TextStyle(
                                  color: _journalText,
                                  fontSize: 15,
                                  fontWeight: FontWeight.w800,
                                ),
                              ),
                              const SizedBox(height: 4),
                              Text(
                                '${schedule['class_name'] ?? '-'} • ${schedule['school_name'] ?? '-'}',
                                style: const TextStyle(
                                  color: _journalMuted,
                                  fontWeight: FontWeight.w700,
                                ),
                              ),
                              const SizedBox(height: 8),
                              _MiniBadge(
                                label:
                                    '${schedule['start_time'] ?? '-'} - ${schedule['end_time'] ?? '-'}',
                                color: _journalPrimary,
                              ),
                            ],
                          ),
                        ),
                        const SizedBox(height: 12),
                        AppSectionCard(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              const Text(
                                'Kehadiran Siswa',
                                style: TextStyle(
                                  color: _journalText,
                                  fontSize: 14,
                                  fontWeight: FontWeight.w800,
                                ),
                              ),
                              const SizedBox(height: 8),
                              if (storedClassTotal != null)
                                Container(
                                  width: double.infinity,
                                  padding: const EdgeInsets.all(10),
                                  decoration: BoxDecoration(
                                    color: _journalSoft,
                                    borderRadius: BorderRadius.circular(18),
                                    border: Border.all(
                                      color: _journalBorder,
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
                              const SizedBox(height: 10),
                              TextField(
                                controller: _presentController,
                                keyboardType: TextInputType.number,
                                onChanged: (_) => sheetSetState(() {}),
                                decoration: const InputDecoration(
                                  labelText: 'Jumlah siswa hadir',
                                  hintText: 'Contoh: 30',
                                ),
                              ),
                              const SizedBox(height: 8),
                              Container(
                                width: double.infinity,
                                padding: const EdgeInsets.all(10),
                                decoration: BoxDecoration(
                                  color: percentage == null
                                      ? _journalSurface
                                      : presentStudents != null &&
                                              classTotal != null &&
                                              presentStudents <= classTotal
                                          ? _journalSoftGreen
                                          : _journalSoftRed,
                                  borderRadius: BorderRadius.circular(18),
                                  border: Border.all(color: _journalBorder),
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
                        const SizedBox(height: 12),
                        AppSectionCard(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              const Text(
                                'Materi atau Topik',
                                style: TextStyle(
                                  color: _journalText,
                                  fontSize: 14,
                                  fontWeight: FontWeight.w800,
                                ),
                              ),
                              const SizedBox(height: 8),
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
                              const SizedBox(height: 12),
                              Container(
                                width: double.infinity,
                                padding: const EdgeInsets.all(12),
                                decoration: BoxDecoration(
                                  color: isLateJournal
                                      ? const Color(0xFFFFF7ED)
                                      : const Color(0xFFF5FAF8),
                                  borderRadius: BorderRadius.circular(16),
                                  border: Border.all(
                                    color: isLateJournal
                                        ? const Color(0xFFF6C177)
                                        : _journalBorder,
                                  ),
                                ),
                                child: Row(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Icon(
                                      isLateJournal
                                          ? Icons.assignment_late_rounded
                                          : Icons.schedule_rounded,
                                      color: isLateJournal
                                          ? _journalWarning
                                          : _journalPrimary,
                                      size: 18,
                                    ),
                                    const SizedBox(width: 8),
                                    Expanded(
                                      child: Text(
                                        submitHint,
                                        style: TextStyle(
                                          color: isLateJournal
                                              ? const Color(0xFF9A6700)
                                              : _journalMuted,
                                          fontSize: 11.5,
                                          fontWeight: FontWeight.w700,
                                          height: 1.35,
                                        ),
                                      ),
                                    ),
                                  ],
                                ),
                              ),
                              const SizedBox(height: 12),
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
                                        isLateJournal: isLateJournal,
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
                              disabledBackgroundColor: _journalBorder,
                              disabledForegroundColor: _journalMuted,
                              foregroundColor: Colors.white,
                              padding: const EdgeInsets.symmetric(vertical: 14),
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
                                  : submitLabel,
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
    final bottomNavInset = MediaQuery.paddingOf(context).bottom + 128;

    return FutureBuilder<Map<String, dynamic>>(
      future: _future,
      builder: (context, snapshot) {
        return Column(
          children: [
            TeacherOverlayPageHeader(
              title: 'Jurnal Mengajar',
              onBack: widget.onBackToHome,
            ),
            Expanded(
              child: RefreshIndicator(
                onRefresh: _refresh,
                child: ListView(
                  padding: EdgeInsets.only(bottom: bottomNavInset),
                  children: [
              Transform.translate(
                offset: const Offset(0, -8),
                child: Container(
                  width: double.infinity,
                  padding: const EdgeInsets.fromLTRB(14, 22, 14, 16),
                  decoration: const BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.only(
                      topLeft: Radius.circular(18),
                      topRight: Radius.circular(18),
                    ),
                  ),
                  child: snapshot.connectionState == ConnectionState.waiting
                      ? const _PageLoading()
                      : snapshot.hasError
                          ? _PageError(
                              message: snapshot.error.toString(),
                              onRetry: _refresh,
                            )
                          : _JournalContent(
                              data: snapshot.data ?? const <String, dynamic>{},
                              now: _now,
                              submissionFeedbackMessage:
                                  _submissionFeedbackMessage,
                              submissionFeedbackSuccess:
                                  _submissionFeedbackSuccess,
                              onOpenAttendanceSheet: _openAttendanceSheet,
                            ),
                ),
              ),
                  ],
                ),
              ),
            ),
          ],
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
    required this.onOpenAttendanceSheet,
  });

  final Map<String, dynamic> data;
  final DateTime now;
  final String? submissionFeedbackMessage;
  final bool? submissionFeedbackSuccess;
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
    final items = [
      ...((data['items'] as List?) ?? const [])
          .whereType<Map>()
          .map((item) => Map<String, dynamic>.from(item)),
    ]
        .whereType<Map>()
        .map((item) => Map<String, dynamic>.from(item))
        .toList()
      ..sort((a, b) {
        final aDate = DateTime.tryParse('${a['date'] ?? ''}');
        final bDate = DateTime.tryParse('${b['date'] ?? ''}');
        final aTime = a['time'] as String? ?? '00:00:00';
        final bTime = b['time'] as String? ?? '00:00:00';
        final aKey = DateTime(
          aDate?.year ?? 1970,
          aDate?.month ?? 1,
          aDate?.day ?? 1,
        ).add(_durationFromTime(aTime));
        final bKey = DateTime(
          bDate?.year ?? 1970,
          bDate?.month ?? 1,
          bDate?.day ?? 1,
        ).add(_durationFromTime(bTime));
        return bKey.compareTo(aKey);
      });

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        if (submissionFeedbackMessage != null &&
            submissionFeedbackMessage!.trim().isNotEmpty) ...[
          _InfoBanner(
            color: submissionFeedbackSuccess == true
                ? _journalPrimary
                : _journalDanger,
            icon: submissionFeedbackSuccess == true
                ? Icons.check_circle_rounded
                : Icons.error_rounded,
            message: submissionFeedbackMessage!,
          ),
          const SizedBox(height: 10),
        ],
        _JournalDateTimeBar(
          todayLabel: data['today_label'] as String? ?? '-',
          currentTime: _timeLabel(now),
        ),
        if (approvedIzin != null) ...[
          const SizedBox(height: 10),
          _InfoBanner(
            color: _journalInfo,
            icon: Icons.info_outline_rounded,
            message: approvedIzin['message'] as String? ??
                'Anda tercatat izin disetujui hari ini.',
            note: approvedIzin['note'] as String?,
          ),
        ],
        const SizedBox(height: 14),
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
            const SizedBox(width: 10),
            Expanded(
              child: _JournalSummaryTile(
                label: 'Selesai / Izin',
                value: '${todaySummary['completed_schedules'] ?? 0}',
                accent: _journalPrimaryDark,
                icon: Icons.check_circle_rounded,
              ),
            ),
            const SizedBox(width: 10),
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
        const SizedBox(height: 14),
        const _PageSectionHeading(
          eyebrow: 'Hari Ini',
          title: 'Presensi Jurnal Mengajar',
        ),
        const SizedBox(height: 10),
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
              padding: const EdgeInsets.only(bottom: 10),
              child: _TeachingScheduleTile(
                item: schedule,
                onTakeAttendance:
                    (schedule['can_submit'] == true ||
                            (schedule['time_state'] as String? ?? 'after') ==
                                'after')
                        ? () => onOpenAttendanceSheet(schedule)
                        : null,
              ),
            ),
          ),
        const SizedBox(height: 14),
        const _PageSectionHeading(
          eyebrow: 'Riwayat',
          title: 'Jurnal Bulan Ini',
        ),
        const SizedBox(height: 10),
        if (items.isEmpty)
          const AppSectionCard(
            child: AppEmptyState(
              title: 'Belum ada jurnal mengajar',
              message: 'Riwayat presensi mengajar akan tampil di sini.',
              icon: Icons.menu_book_outlined,
            ),
          )
        else
          _JournalHistoryTable(items: items),
      ],
    );
  }
}

class _JournalDateTimeBar extends StatelessWidget {
  const _JournalDateTimeBar({
    required this.todayLabel,
    required this.currentTime,
  });

  final String todayLabel;
  final String currentTime;

  @override
  Widget build(BuildContext context) {
    return AppSectionCard(
      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 12),
      child: Row(
        children: [
          Container(
            width: 38,
            height: 38,
            decoration: BoxDecoration(
              color: _journalSoft,
              borderRadius: BorderRadius.circular(12),
            ),
            child: const Icon(
              Icons.calendar_today_rounded,
              size: 18,
              color: _journalPrimaryDark,
            ),
          ),
          const SizedBox(width: 10),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Text(
                  'Hari ini',
                  style: TextStyle(
                    color: _journalMuted,
                    fontSize: 10.5,
                    fontWeight: FontWeight.w700,
                  ),
                ),
                const SizedBox(height: 2),
                Text(
                  todayLabel,
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                  style: const TextStyle(
                    color: _journalText,
                    fontSize: 13,
                    fontWeight: FontWeight.w800,
                  ),
                ),
              ],
            ),
          ),
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 8),
            decoration: BoxDecoration(
              color: _journalPrimaryDark,
              borderRadius: BorderRadius.circular(12),
            ),
            child: Row(
              mainAxisSize: MainAxisSize.min,
              children: [
                const Icon(
                  Icons.access_time_rounded,
                  color: Colors.white,
                  size: 14,
                ),
                const SizedBox(width: 5),
                Text(
                  currentTime,
                  style: const TextStyle(
                    color: Colors.white,
                    fontSize: 11.5,
                    fontWeight: FontWeight.w800,
                  ),
                ),
              ],
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
      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 12),
      child: Column(
        children: [
          Container(
            width: 30,
            height: 30,
            decoration: BoxDecoration(
              color: accent.withValues(alpha: 0.12),
              borderRadius: BorderRadius.circular(10),
              border: Border.all(color: _journalBorder),
            ),
            child: Icon(icon, color: accent, size: 16),
          ),
          const SizedBox(height: 8),
          Text(
            value,
            style: const TextStyle(
              color: _journalText,
              fontSize: 16,
              fontWeight: FontWeight.w800,
            ),
          ),
          const SizedBox(height: 2),
          Text(
            label,
            textAlign: TextAlign.center,
            style: const TextStyle(
              color: _journalMuted,
              fontSize: 10.5,
              fontWeight: FontWeight.w700,
            ),
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
    final isLateJournal =
        timeState == 'after' && attendance == null && status != 'izin';
    final shouldShowAction = canSubmit || isLateJournal;
    final statusIsIzin = status == 'izin';

    return AppSectionCard(
      padding: const EdgeInsets.all(14),
      backgroundColor: _journalPrimaryDark,
      borderColor: _journalPrimary,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Container(
                width: 40,
                height: 40,
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(14),
                  border: Border.all(color: Colors.white.withValues(alpha: 0.28)),
                ),
                child: const Icon(
                  Icons.cast_for_education_rounded,
                  color: _journalPrimaryDark,
                  size: 20,
                ),
              ),
              const SizedBox(width: 10),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      item['subject'] as String? ?? '-',
                      style: const TextStyle(
                        color: Colors.white,
                        fontWeight: FontWeight.w800,
                        fontSize: 14,
                      ),
                    ),
                    const SizedBox(height: 3),
                    Text(
                      item['class_name'] as String? ?? '-',
                      style: TextStyle(
                        color: Colors.white.withValues(alpha: 0.82),
                        fontWeight: FontWeight.w700,
                        fontSize: 11.5,
                      ),
                    ),
                    const SizedBox(height: 3),
                    Text(
                      '${item['start_time'] ?? '-'} - ${item['end_time'] ?? '-'} • ${item['school_name'] ?? '-'}',
                      style: TextStyle(
                        color: Colors.white.withValues(alpha: 0.70),
                        fontSize: 10.5,
                        height: 1.35,
                      ),
                    ),
                  ],
                ),
              ),
              _StatusPill(
                label: item['status_label'] as String? ?? 'Belum Presensi',
                color: status == 'hadir'
                    ? Colors.white
                    : statusIsIzin
                        ? _journalSoft
                        : _journalWarning,
              ),
            ],
          ),
          const SizedBox(height: 10),
          if (attendance != null)
            Container(
              width: double.infinity,
              padding: const EdgeInsets.all(10),
              decoration: BoxDecoration(
                color: Colors.white.withValues(alpha: 0.12),
                borderRadius: BorderRadius.circular(18),
                border: Border.all(
                  color: Colors.white.withValues(alpha: 0.18),
                ),
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    status == 'izin'
                        ? 'Jadwal ini dibebaskan karena izin sudah disetujui.'
                        : 'Presensi berhasil pada ${attendance['time'] ?? '-'}',
                    style: TextStyle(
                      color: status == 'izin' ? _journalSoft : Colors.white,
                      fontWeight: FontWeight.w800,
                    ),
                  ),
                  const SizedBox(height: 6),
                  Text(
                    attendance['materi'] as String? ?? '-',
                    style: TextStyle(
                      color: Colors.white.withValues(alpha: 0.90),
                      height: 1.45,
                    ),
                  ),
                  if (attendance['present_students'] != null &&
                      attendance['class_total_students'] != null) ...[
                    const SizedBox(height: 8),
                    Wrap(
                      spacing: 8,
                      runSpacing: 8,
                      children: [
                        _MiniBadge(
                          label:
                              'Hadir ${attendance['present_students']}/${attendance['class_total_students']}',
                          color: Colors.white,
                        ),
                        if (attendance['student_attendance_percentage'] != null)
                          _MiniBadge(
                            label:
                                '${attendance['student_attendance_percentage']}%',
                            color: Colors.white,
                          ),
                      ],
                    ),
                  ],
                ],
              ),
            )
          else if (statusIsIzin)
            _InfoBanner(
              color: _journalSoft,
              icon: Icons.info_outline_rounded,
              message:
                  'Jadwal mengajar hari ini dibebaskan karena izin sudah disetujui.',
            )
          else if (shouldShowAction)
            Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  item['time_message'] as String? ??
                      'Presensi belum dapat dilakukan.',
                  style: TextStyle(
                    color: timeState == 'within'
                        ? _journalSoft
                        : timeState == 'before'
                            ? _journalWarning
                            : Colors.white.withValues(alpha: 0.72),
                    fontSize: 11.5,
                    fontWeight: FontWeight.w700,
                    height: 1.45,
                  ),
                ),
                const SizedBox(height: 10),
                SizedBox(
                  width: double.infinity,
                  child: ElevatedButton.icon(
                    onPressed: onTakeAttendance,
                    style: ElevatedButton.styleFrom(
                      backgroundColor: Colors.white,
                      disabledBackgroundColor: Colors.white.withValues(alpha: 0.16),
                      disabledForegroundColor: Colors.white.withValues(alpha: 0.54),
                      foregroundColor: _journalPrimaryDark,
                      padding: const EdgeInsets.symmetric(vertical: 12),
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(16),
                      ),
                      elevation: 0,
                    ),
                    icon: Icon(
                      isLateJournal
                          ? Icons.assignment_late_rounded
                          : Icons.check_circle_outline_rounded,
                    ),
                    label: Text(
                      isLateJournal ? 'Jurnal Susulan' : 'Lakukan Presensi',
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

class _JournalHistoryTable extends StatelessWidget {
  const _JournalHistoryTable({required this.items});

  final List<Map<String, dynamic>> items;

  @override
  Widget build(BuildContext context) {
    return AppSectionCard(
      padding: EdgeInsets.zero,
      child: Column(
        children: [
          Container(
            width: double.infinity,
            decoration: BoxDecoration(
              color: _journalSoft,
              borderRadius: const BorderRadius.vertical(top: Radius.circular(20)),
            ),
          padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 9),
          child: const _JournalHistoryRow(
            date: 'TANGGAL / JAM',
            className: 'KELAS',
            subject: 'MAPEL',
            material: 'MATERI',
            attendance: 'HADIR',
            status: 'header',
            isHeader: true,
          ),
        ),
          ...items.asMap().entries.map(
            (entry) => Container(
              padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 9),
              decoration: BoxDecoration(
                border: entry.key == items.length - 1
                    ? null
                    : const Border(
                        bottom: BorderSide(color: _journalBorder),
                      ),
              ),
              child: _JournalHistoryRow.fromItem(entry.value),
            ),
          ),
        ],
      ),
    );
  }
}

class _JournalHistoryRow extends StatelessWidget {
  const _JournalHistoryRow({
    required this.date,
    required this.className,
    required this.subject,
    required this.material,
    required this.attendance,
    required this.status,
    this.isHeader = false,
  });

  factory _JournalHistoryRow.fromItem(Map<String, dynamic> item) {
    final present = item['present_students'];
    final total = item['class_total_students'];
    final status = item['status'] as String? ?? 'hadir';
    return _JournalHistoryRow(
      date: '${item['date_label'] ?? '-'}\n${item['time'] ?? '-'}',
      className: item['class_name'] as String? ?? '-',
      subject: item['subject'] as String? ?? '-',
      material: item['materi'] as String? ?? '-',
      attendance: status == 'terlewat'
          ? 'Belum'
          : present == null
              ? '-'
              : total == null
                  ? '$present'
                  : '$present/$total',
      status: status,
    );
  }

  final String date;
  final String className;
  final String subject;
  final String material;
  final String attendance;
  final String status;
  final bool isHeader;

  @override
  Widget build(BuildContext context) {
    final isMissed = status == 'terlewat';
    final isIzin = status == 'izin';
    final rowColor = isHeader
        ? _journalPrimaryDark
        : isMissed
            ? _journalDanger
            : isIzin
                ? _journalWarning
                : _journalText;
    final style = TextStyle(
      color: rowColor,
      fontSize: isHeader ? 8.5 : 9.5,
      fontWeight: isHeader ? FontWeight.w800 : FontWeight.w700,
      height: 1.28,
    );
    return Row(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        SizedBox(width: 66, child: Text(date, style: style, maxLines: 2, overflow: TextOverflow.ellipsis)),
        const SizedBox(width: 5),
        SizedBox(width: 34, child: Text(className, style: style, maxLines: 2, overflow: TextOverflow.ellipsis)),
        const SizedBox(width: 5),
        Expanded(
          flex: 2,
          child: Text(subject, style: style, maxLines: 2, overflow: TextOverflow.ellipsis),
        ),
        const SizedBox(width: 5),
        Expanded(
          flex: 2,
          child: Text(material, style: style, maxLines: 2, overflow: TextOverflow.ellipsis),
        ),
        const SizedBox(width: 5),
        SizedBox(
          width: 40,
          child: Text(
            attendance,
            textAlign: TextAlign.right,
            style: style,
            maxLines: 2,
            overflow: TextOverflow.ellipsis,
          ),
        ),
      ],
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
      padding: const EdgeInsets.symmetric(horizontal: 9, vertical: 6),
      decoration: BoxDecoration(
        color: color.withValues(alpha: 0.1),
        borderRadius: BorderRadius.circular(999),
        border: Border.all(color: _journalBorder),
      ),
      child: Text(
        label,
        style: TextStyle(
          color: color,
          fontSize: 10.5,
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
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: color.withValues(alpha: 0.1),
        borderRadius: BorderRadius.circular(18),
        border: Border.all(color: _journalBorder),
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
                  const SizedBox(height: 3),
                  Text(
                    note!,
                    style: TextStyle(
                      color: color,
                      fontSize: 11.5,
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
      padding: const EdgeInsets.symmetric(horizontal: 9, vertical: 6),
      decoration: BoxDecoration(
        color: color.withValues(alpha: 0.12),
        borderRadius: BorderRadius.circular(999),
      ),
      child: Text(
        label,
        style: TextStyle(
          color: color,
          fontSize: 10.5,
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
            fontSize: 10.5,
            fontWeight: FontWeight.w800,
            letterSpacing: 0.6,
          ),
        ),
        const SizedBox(height: 3),
        Text(
          title,
          style: const TextStyle(
            color: _journalText,
            fontSize: 16,
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
          const SizedBox(height: 10),
          OutlinedButton(
            onPressed: onRetry,
            style: OutlinedButton.styleFrom(
              foregroundColor: _journalPrimaryDark,
              side: const BorderSide(color: _journalBorder),
            ),
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
