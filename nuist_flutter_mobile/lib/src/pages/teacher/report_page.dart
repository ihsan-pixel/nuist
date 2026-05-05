import 'dart:io';

import 'package:flutter/material.dart';
import 'package:open_filex/open_filex.dart';
import 'package:path_provider/path_provider.dart';

import '../../services/teacher_mobile_repository.dart';
import '../../widgets/app/app_empty_state.dart';
import '../../widgets/app/app_section_card.dart';
import '../../widgets/app/app_stat_card.dart';

const _reportPrimary = Color(0xFFF49637);
const _reportText = Color(0xFF1F4F4C);
const _reportMuted = Color(0xFF6D7F7D);

class TeacherReportPage extends StatefulWidget {
  const TeacherReportPage({
    super.key,
    required this.repository,
    this.initialTeacherId,
    this.pageTitle = 'Laporan Presensi',
  });

  final TeacherMobileRepository repository;
  final int? initialTeacherId;
  final String pageTitle;

  @override
  State<TeacherReportPage> createState() => _TeacherReportPageState();
}

class _TeacherReportPageState extends State<TeacherReportPage> {
  late Future<Map<String, dynamic>> _future;
  String _scope = 'monthly';
  late String _month;
  int? _teacherId;
  bool _exportingAttendance = false;
  bool _exportingTeaching = false;

  @override
  void initState() {
    super.initState();
    final now = DateTime.now();
    _month =
        '${now.year.toString().padLeft(4, '0')}-${now.month.toString().padLeft(2, '0')}';
    _teacherId = widget.initialTeacherId;
    _future = _load();
  }

  Future<Map<String, dynamic>> _load() {
    return widget.repository.getAttendanceReports(
      scope: _scope,
      month: _month,
      teacherId: _teacherId,
    );
  }

  Future<void> _refresh() async {
    final future = _load();
    setState(() {
      _future = future;
    });
    await future;
  }

  Future<void> _pickMonth() async {
    final initialParts = _month.split('-');
    final initialDate = DateTime(
      int.parse(initialParts[0]),
      int.parse(initialParts[1]),
      1,
    );
    final picked = await showDatePicker(
      context: context,
      initialDate: initialDate,
      firstDate: DateTime(2020),
      lastDate: DateTime.now().add(const Duration(days: 365)),
    );
    if (picked == null) {
      return;
    }

    final month =
        '${picked.year.toString().padLeft(4, '0')}-${picked.month.toString().padLeft(2, '0')}';
    if (month == _month) {
      return;
    }

    final future = widget.repository.getAttendanceReports(
      scope: _scope,
      month: month,
      teacherId: _teacherId,
    );
    setState(() {
      _month = month;
      _future = future;
    });
    await future;
  }

  Future<void> _changeScope(String scope) async {
    if (_scope == scope) {
      return;
    }

    final future = widget.repository.getAttendanceReports(
      scope: scope,
      month: _month,
      teacherId: _teacherId,
    );
    setState(() {
      _scope = scope;
      _future = future;
    });
    await future;
  }

  Future<void> _changeTeacher(int? teacherId) async {
    final future = widget.repository.getAttendanceReports(
      scope: _scope,
      month: _month,
      teacherId: teacherId,
    );
    setState(() {
      _teacherId = teacherId;
      _future = future;
    });
    await future;
  }

  Future<void> _downloadAttendancePdf() async {
    if (_exportingAttendance) {
      return;
    }
    setState(() {
      _exportingAttendance = true;
    });
    try {
      final file = await widget.repository.downloadAttendanceReportPdf(
        scope: _scope,
        month: _month,
        teacherId: _teacherId,
      );
      await _saveAndOpenPdf(file);
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
          _exportingAttendance = false;
        });
      }
    }
  }

  Future<void> _downloadTeachingPdf() async {
    if (_exportingTeaching) {
      return;
    }
    setState(() {
      _exportingTeaching = true;
    });
    try {
      final file = await widget.repository.downloadTeachingReportPdf(
        scope: _scope,
        month: _month,
        teacherId: _teacherId,
      );
      await _saveAndOpenPdf(file);
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
          _exportingTeaching = false;
        });
      }
    }
  }

  Future<void> _saveAndOpenPdf(Map<String, dynamic> file) async {
    final bytes = (file['bytes'] as List?)?.cast<int>() ?? const <int>[];
    final filename =
        (file['filename'] as String?)?.trim().isNotEmpty == true
            ? file['filename'] as String
            : 'report.pdf';
    final directory = await getTemporaryDirectory();
    final path = '${directory.path}/${DateTime.now().millisecondsSinceEpoch}_$filename';
    final output = File(path);
    await output.writeAsBytes(bytes, flush: true);
    final result = await OpenFilex.open(output.path);

    if (!mounted) {
      return;
    }

    if (result.type != ResultType.done) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(
            result.message.isNotEmpty
                ? result.message
                : 'PDF berhasil dibuat di $path',
          ),
        ),
      );
      return;
    }

    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(content: Text('PDF berhasil dibuat: $filename')),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFFFFAF5),
      appBar: AppBar(
        title: Text(widget.pageTitle),
        backgroundColor: Colors.white,
        elevation: 0,
        scrolledUnderElevation: 0,
      ),
      body: FutureBuilder<Map<String, dynamic>>(
        future: _future,
        builder: (context, snapshot) {
          return RefreshIndicator(
            onRefresh: _refresh,
            child: ListView(
              padding: const EdgeInsets.all(16),
              children: [
                if (snapshot.connectionState == ConnectionState.waiting)
                  const Center(
                    child: Padding(
                      padding: EdgeInsets.only(top: 120),
                      child: CircularProgressIndicator(),
                    ),
                  )
                else if (snapshot.hasError)
                  AppSectionCard(
                    child: Text(
                      snapshot.error.toString(),
                      style: const TextStyle(
                        color: Color(0xFF9F1239),
                        fontWeight: FontWeight.w700,
                      ),
                    ),
                  )
                else
                  _ReportContent(
                    data: snapshot.data ?? const <String, dynamic>{},
                    scope: _scope,
                    month: _month,
                    exportingAttendance: _exportingAttendance,
                    exportingTeaching: _exportingTeaching,
                    onScopeChange: _changeScope,
                    onPickMonth: _pickMonth,
                    onTeacherChange: _changeTeacher,
                    onDownloadAttendance: _downloadAttendancePdf,
                    onDownloadTeaching: _downloadTeachingPdf,
                  ),
              ],
            ),
          );
        },
      ),
    );
  }
}

class _ReportContent extends StatelessWidget {
  const _ReportContent({
    required this.data,
    required this.scope,
    required this.month,
    required this.exportingAttendance,
    required this.exportingTeaching,
    required this.onScopeChange,
    required this.onPickMonth,
    required this.onTeacherChange,
    required this.onDownloadAttendance,
    required this.onDownloadTeaching,
  });

  final Map<String, dynamic> data;
  final String scope;
  final String month;
  final bool exportingAttendance;
  final bool exportingTeaching;
  final ValueChanged<String> onScopeChange;
  final Future<void> Function() onPickMonth;
  final Future<void> Function(int? teacherId) onTeacherChange;
  final Future<void> Function() onDownloadAttendance;
  final Future<void> Function() onDownloadTeaching;

  @override
  Widget build(BuildContext context) {
    final permissions = Map<String, dynamic>.from(
      (data['permissions'] as Map?) ?? const <String, dynamic>{},
    );
    final filters = Map<String, dynamic>.from(
      (data['filters'] as Map?) ?? const <String, dynamic>{},
    );
    final selectedTeacher = Map<String, dynamic>.from(
      (data['selected_teacher'] as Map?) ?? const <String, dynamic>{},
    );
    final teacherOptions = ((data['teacher_options'] as List?) ?? const [])
        .whereType<Map>()
        .map((item) => Map<String, dynamic>.from(item))
        .toList();
    final attendance = Map<String, dynamic>.from(
      (data['attendance'] as Map?) ?? const <String, dynamic>{},
    );
    final attendanceSummary = Map<String, dynamic>.from(
      (attendance['summary'] as Map?) ?? const <String, dynamic>{},
    );
    final attendanceRecords = ((attendance['records'] as List?) ?? const [])
        .whereType<Map>()
        .map((item) => Map<String, dynamic>.from(item))
        .toList();
    final teaching = Map<String, dynamic>.from(
      (data['teaching'] as Map?) ?? const <String, dynamic>{},
    );
    final teachingSummary = Map<String, dynamic>.from(
      (teaching['summary'] as Map?) ?? const <String, dynamic>{},
    );
    final teachingRecords = ((teaching['records'] as List?) ?? const [])
        .whereType<Map>()
        .map((item) => Map<String, dynamic>.from(item))
        .toList();

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        AppSectionCard(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const Text(
                'Filter Laporan',
                style: TextStyle(
                  color: _reportText,
                  fontSize: 16,
                  fontWeight: FontWeight.w800,
                ),
              ),
              const SizedBox(height: 14),
              if (permissions['can_select_teacher'] == true) ...[
                DropdownButtonFormField<int>(
                  value: (filters['selected_teacher_id'] as num?)?.toInt(),
                  items: teacherOptions
                      .map(
                        (item) => DropdownMenuItem<int>(
                          value: (item['id'] as num?)?.toInt(),
                          child: Text(item['name'] as String? ?? '-'),
                        ),
                      )
                      .toList(),
                  onChanged: (value) async {
                    await onTeacherChange(value);
                  },
                  decoration: _inputDecoration('Pilih Guru'),
                ),
                const SizedBox(height: 12),
              ],
              Row(
                children: [
                  Expanded(
                    child: ChoiceChip(
                      label: const Text('Bulanan'),
                      selected: scope == 'monthly',
                      onSelected: (_) => onScopeChange('monthly'),
                    ),
                  ),
                  const SizedBox(width: 10),
                  Expanded(
                    child: ChoiceChip(
                      label: const Text('Keseluruhan'),
                      selected: scope == 'all',
                      onSelected: (_) => onScopeChange('all'),
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 12),
              InkWell(
                borderRadius: BorderRadius.circular(18),
                onTap: scope == 'monthly' ? onPickMonth : null,
                child: Ink(
                  padding: const EdgeInsets.symmetric(
                    horizontal: 14,
                    vertical: 14,
                  ),
                  decoration: BoxDecoration(
                    color: scope == 'monthly'
                        ? Colors.white
                        : const Color(0xFFF5F1EC),
                    borderRadius: BorderRadius.circular(18),
                    border: Border.all(color: const Color(0xFFE8D6C6)),
                  ),
                  child: Row(
                    children: [
                      const Icon(
                        Icons.calendar_month_rounded,
                        color: _reportPrimary,
                      ),
                      const SizedBox(width: 10),
                      Expanded(
                        child: Text(
                          scope == 'monthly'
                              ? _formatMonthLabel(month)
                              : 'Periode keseluruhan',
                          style: const TextStyle(
                            color: _reportText,
                            fontWeight: FontWeight.w700,
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
              ),
              const SizedBox(height: 12),
              Text(
                '${selectedTeacher['name'] ?? '-'} • ${selectedTeacher['school_name'] ?? '-'}',
                style: const TextStyle(
                  color: _reportMuted,
                  fontWeight: FontWeight.w600,
                ),
              ),
            ],
          ),
        ),
        const SizedBox(height: 16),
        Row(
          children: [
            Expanded(
              child: AppStatCard(
                label: 'Hadir',
                value: '${attendanceSummary['total_hadir'] ?? 0}',
                color: const Color(0xFF2E8B57),
              ),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: AppStatCard(
                label: 'Izin',
                value: '${attendanceSummary['total_izin'] ?? 0}',
                color: const Color(0xFFF4A12A),
              ),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: AppStatCard(
                label: 'Mengajar',
                value: '${teachingSummary['total_entries'] ?? 0}',
                color: const Color(0xFF3C6FD1),
              ),
            ),
          ],
        ),
        const SizedBox(height: 16),
        AppSectionCard(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                children: [
                  const Expanded(
                    child: Text(
                      'Riwayat Presensi Kehadiran',
                      style: TextStyle(
                        color: _reportText,
                        fontSize: 16,
                        fontWeight: FontWeight.w800,
                      ),
                    ),
                  ),
                  FilledButton.tonalIcon(
                    onPressed: exportingAttendance
                        ? null
                        : () async {
                            await onDownloadAttendance();
                          },
                    icon: exportingAttendance
                        ? const SizedBox(
                            width: 14,
                            height: 14,
                            child: CircularProgressIndicator(strokeWidth: 2),
                          )
                        : const Icon(Icons.picture_as_pdf_rounded),
                    label: const Text('Export PDF'),
                  ),
                ],
              ),
              const SizedBox(height: 6),
              Text(
                attendance['period_label'] as String? ?? '-',
                style: const TextStyle(
                  color: _reportMuted,
                  fontSize: 12,
                ),
              ),
              const SizedBox(height: 12),
              if (attendanceRecords.isEmpty)
                const AppEmptyState(
                  title: 'Belum ada riwayat kehadiran',
                  message: 'Data presensi kehadiran akan tampil di sini.',
                  icon: Icons.fact_check_outlined,
                )
              else
                ...attendanceRecords.map(
                  (item) => _SimpleHistoryCard(
                    title:
                        '${item['date'] ?? '-'} • ${item['status_label'] ?? '-'}',
                    subtitle:
                        'Masuk ${item['check_in'] ?? '-'} • Keluar ${item['check_out'] ?? '-'}',
                    detail: item['note'] as String? ?? '-',
                  ),
                ),
            ],
          ),
        ),
        const SizedBox(height: 16),
        AppSectionCard(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                children: [
                  const Expanded(
                    child: Text(
                      'Riwayat Presensi Jurnal Mengajar',
                      style: TextStyle(
                        color: _reportText,
                        fontSize: 16,
                        fontWeight: FontWeight.w800,
                      ),
                    ),
                  ),
                  FilledButton.tonalIcon(
                    onPressed: exportingTeaching
                        ? null
                        : () async {
                            await onDownloadTeaching();
                          },
                    icon: exportingTeaching
                        ? const SizedBox(
                            width: 14,
                            height: 14,
                            child: CircularProgressIndicator(strokeWidth: 2),
                          )
                        : const Icon(Icons.picture_as_pdf_rounded),
                    label: const Text('Export PDF'),
                  ),
                ],
              ),
              const SizedBox(height: 6),
              Text(
                teaching['period_label'] as String? ?? '-',
                style: const TextStyle(
                  color: _reportMuted,
                  fontSize: 12,
                ),
              ),
              const SizedBox(height: 12),
              if (teachingRecords.isEmpty)
                const AppEmptyState(
                  title: 'Belum ada riwayat mengajar',
                  message: 'Data presensi jurnal mengajar akan tampil di sini.',
                  icon: Icons.menu_book_outlined,
                )
              else
                ...teachingRecords.map(
                  (item) => _SimpleHistoryCard(
                    title:
                        '${item['date_label'] ?? '-'} • ${item['subject'] ?? '-'}',
                    subtitle:
                        '${item['class_name'] ?? '-'} • ${item['time'] ?? '-'} • ${(item['student_attendance_percentage'] ?? 0)}%',
                    detail: item['materi'] as String? ?? '-',
                  ),
                ),
            ],
          ),
        ),
      ],
    );
  }
}

class _SimpleHistoryCard extends StatelessWidget {
  const _SimpleHistoryCard({
    required this.title,
    required this.subtitle,
    required this.detail,
  });

  final String title;
  final String subtitle;
  final String detail;

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: const EdgeInsets.only(bottom: 12),
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: const Color(0xFFFFFCF8),
        borderRadius: BorderRadius.circular(18),
        border: Border.all(color: const Color(0xFFEEDFD0)),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            title,
            style: const TextStyle(
              color: _reportText,
              fontWeight: FontWeight.w800,
            ),
          ),
          const SizedBox(height: 4),
          Text(
            subtitle,
            style: const TextStyle(
              color: _reportMuted,
              fontSize: 12,
              fontWeight: FontWeight.w600,
            ),
          ),
          const SizedBox(height: 8),
          Text(
            detail,
            style: const TextStyle(
              color: _reportText,
              height: 1.45,
            ),
          ),
        ],
      ),
    );
  }
}

InputDecoration _inputDecoration(String label) {
  return InputDecoration(
    labelText: label,
    filled: true,
    fillColor: Colors.white,
    border: OutlineInputBorder(
      borderRadius: BorderRadius.circular(18),
      borderSide: const BorderSide(color: Color(0xFFE8D6C6)),
    ),
    enabledBorder: OutlineInputBorder(
      borderRadius: BorderRadius.circular(18),
      borderSide: const BorderSide(color: Color(0xFFE8D6C6)),
    ),
    focusedBorder: OutlineInputBorder(
      borderRadius: BorderRadius.circular(18),
      borderSide: const BorderSide(color: _reportPrimary, width: 1.4),
    ),
  );
}

String _formatMonthLabel(String value) {
  final parts = value.split('-');
  if (parts.length != 2) {
    return value;
  }
  final year = int.tryParse(parts[0]);
  final month = int.tryParse(parts[1]);
  if (year == null || month == null) {
    return value;
  }
  const monthNames = [
    '',
    'Januari',
    'Februari',
    'Maret',
    'April',
    'Mei',
    'Juni',
    'Juli',
    'Agustus',
    'September',
    'Oktober',
    'November',
    'Desember',
  ];
  return '${monthNames[month]} $year';
}
