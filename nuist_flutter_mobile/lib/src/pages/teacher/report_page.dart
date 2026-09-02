import 'dart:io';

import 'package:flutter/material.dart';
import 'package:open_filex/open_filex.dart';
import 'package:path_provider/path_provider.dart';

import '../../services/teacher_mobile_repository.dart';
import '../../widgets/app/app_empty_state.dart';
import '../../widgets/app/app_section_card.dart';
import '../../widgets/app/teacher_page_header.dart';

class _ReportPalette {
  static const surface = Color(0xFFFFFFFF);
  static const primary = Color(0xFF00745A);
  static const primaryDark = Color(0xFF00553F);
  static const textPrimary = Color(0xFF172A24);
  static const textSecondary = Color(0xFF172A24);
  static const border = Color(0xFFDCE7E3);
  static const success = Color(0xFF00745A);
  static const warning = Color(0xFFF59E0B);
  static const danger = Color(0xFFEF4444);
  static const info = Color(0xFF00745A);
  static const iconSurface = Color(0xFFE5F5F0);
  static const softGreen = Color(0xFFE5F5F0);
  static const softYellow = Color(0xFFFEF3C7);

  const _ReportPalette._();
}

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
    final filename = (file['filename'] as String?)?.trim().isNotEmpty == true
        ? file['filename'] as String
        : 'report.pdf';
    final directory = await getTemporaryDirectory();
    final path =
        '${directory.path}/${DateTime.now().millisecondsSinceEpoch}_$filename';
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
    final bottomInset = MediaQuery.paddingOf(context).bottom + 24;

    return Scaffold(
      backgroundColor: Colors.white,
      body: SafeArea(
        bottom: false,
        child: FutureBuilder<Map<String, dynamic>>(
          future: _future,
          builder: (context, snapshot) {
            return Column(
              children: [
                TeacherOverlayPageHeader(
                  title: widget.pageTitle,
                  onBack: () => Navigator.of(context).pop(),
                ),
                Expanded(
                  child: RefreshIndicator(
                    onRefresh: _refresh,
                    child: ListView(
                      physics: const AlwaysScrollableScrollPhysics(),
                      padding: EdgeInsets.fromLTRB(14, 16, 14, bottomInset),
                      children: [
                        if (snapshot.connectionState == ConnectionState.waiting)
                          const _ReportLoading()
                        else if (snapshot.hasError)
                          _ReportError(
                            message: snapshot.error.toString(),
                            onRetry: _refresh,
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
                  ),
                ),
              ],
            );
          },
        ),
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
          padding: const EdgeInsets.all(14),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const _ReportSectionHeading(
                eyebrow: 'Filter',
                title: 'Filter Laporan',
              ),
              const SizedBox(height: 10),
              if (permissions['can_select_teacher'] == true) ...[
                DropdownButtonFormField<int>(
                  initialValue:
                      (filters['selected_teacher_id'] as num?)?.toInt(),
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
                const SizedBox(height: 10),
              ],
              Row(
                children: [
                  Expanded(
                    child: ChoiceChip(
                      label: const Text('Bulanan'),
                      selected: scope == 'monthly',
                      onSelected: (_) => onScopeChange('monthly'),
                      selectedColor: _ReportPalette.iconSurface,
                      backgroundColor: _ReportPalette.surface,
                      side: const BorderSide(color: _ReportPalette.border),
                      labelStyle: TextStyle(
                        color: scope == 'monthly'
                            ? _ReportPalette.primaryDark
                            : _ReportPalette.textSecondary,
                        fontWeight: FontWeight.w700,
                        fontSize: 12,
                      ),
                    ),
                  ),
                  const SizedBox(width: 10),
                  Expanded(
                    child: ChoiceChip(
                      label: const Text('Keseluruhan'),
                      selected: scope == 'all',
                      onSelected: (_) => onScopeChange('all'),
                      selectedColor: _ReportPalette.iconSurface,
                      backgroundColor: _ReportPalette.surface,
                      side: const BorderSide(color: _ReportPalette.border),
                      labelStyle: TextStyle(
                        color: scope == 'all'
                            ? _ReportPalette.primaryDark
                            : _ReportPalette.textSecondary,
                        fontWeight: FontWeight.w700,
                        fontSize: 12,
                      ),
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 10),
              InkWell(
                borderRadius: BorderRadius.circular(16),
                onTap: scope == 'monthly' ? onPickMonth : null,
                child: Ink(
                  padding: const EdgeInsets.symmetric(
                    horizontal: 14,
                    vertical: 14,
                  ),
                  decoration: BoxDecoration(
                    color: scope == 'monthly'
                        ? _ReportPalette.surface
                        : const Color(0xFFF7F9FC),
                    borderRadius: BorderRadius.circular(16),
                    border: Border.all(color: _ReportPalette.border),
                  ),
                  child: Row(
                    children: [
                      const Icon(
                        Icons.calendar_month_rounded,
                        color: _ReportPalette.primary,
                      ),
                      const SizedBox(width: 10),
                      Expanded(
                        child: Text(
                          scope == 'monthly'
                              ? _formatMonthLabel(month)
                              : 'Periode keseluruhan',
                          style: const TextStyle(
                            color: _ReportPalette.textPrimary,
                            fontWeight: FontWeight.w700,
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
              ),
              const SizedBox(height: 10),
              Text(
                '${selectedTeacher['name'] ?? '-'} • ${selectedTeacher['school_name'] ?? '-'}',
                style: const TextStyle(
                  color: _ReportPalette.textSecondary,
                  fontSize: 11.5,
                  fontWeight: FontWeight.w600,
                ),
              ),
            ],
          ),
        ),
        const SizedBox(height: 12),
        Row(
          children: [
            Expanded(
              child: _ReportMetricTile(
                label: 'Hadir',
                value: '${attendanceSummary['total_hadir'] ?? 0}',
                color: _ReportPalette.success,
                surface: _ReportPalette.softGreen,
                icon: Icons.check_circle_rounded,
              ),
            ),
            const SizedBox(width: 10),
            Expanded(
              child: _ReportMetricTile(
                label: 'Izin',
                value: '${attendanceSummary['total_izin'] ?? 0}',
                color: _ReportPalette.warning,
                surface: _ReportPalette.softYellow,
                icon: Icons.event_available_rounded,
              ),
            ),
            const SizedBox(width: 10),
            Expanded(
              child: _ReportMetricTile(
                label: 'Mengajar',
                value: '${teachingSummary['total_entries'] ?? 0}',
                color: _ReportPalette.info,
                surface: const Color(0xFFE5F5F0),
                icon: Icons.menu_book_rounded,
              ),
            ),
          ],
        ),
        const SizedBox(height: 12),
        AppSectionCard(
          padding: const EdgeInsets.all(14),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                children: [
                  const Expanded(
                    child: _ReportSectionHeading(
                      eyebrow: 'Kehadiran',
                      title: 'Riwayat Presensi Kehadiran',
                    ),
                  ),
                  OutlinedButton.icon(
                    onPressed: exportingAttendance
                        ? null
                        : () async {
                            await onDownloadAttendance();
                          },
                    style: OutlinedButton.styleFrom(
                      foregroundColor: _ReportPalette.primaryDark,
                      side: const BorderSide(color: _ReportPalette.border),
                      padding: const EdgeInsets.symmetric(
                        horizontal: 12,
                        vertical: 10,
                      ),
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(14),
                      ),
                    ),
                    icon: exportingAttendance
                        ? const SizedBox(
                            width: 14,
                            height: 14,
                            child: CircularProgressIndicator(strokeWidth: 2),
                          )
                        : const Icon(Icons.picture_as_pdf_rounded, size: 16),
                    label: const Text(
                      'PDF',
                      style: TextStyle(fontWeight: FontWeight.w700),
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 4),
              Text(
                attendance['period_label'] as String? ?? '-',
                style: const TextStyle(
                  color: _ReportPalette.textSecondary,
                  fontSize: 11.5,
                ),
              ),
              const SizedBox(height: 10),
              if (attendanceRecords.isEmpty)
                const AppEmptyState(
                  title: 'Belum ada riwayat kehadiran',
                  message: 'Data presensi kehadiran akan tampil di sini.',
                  icon: Icons.fact_check_outlined,
                )
              else
                ...attendanceRecords.map(
                  (item) => _SimpleHistoryCard(
                    title: item['date'] as String? ?? '-',
                    badgeLabel: item['status_label'] as String? ?? '-',
                    badgeColor: _reportStatusColor(item['status'] as String?),
                    subtitle:
                        'Masuk ${item['check_in'] ?? '-'} • Keluar ${item['check_out'] ?? '-'}',
                    detail: item['note'] as String? ?? '-',
                  ),
                ),
            ],
          ),
        ),
        const SizedBox(height: 12),
        AppSectionCard(
          padding: const EdgeInsets.all(14),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                children: [
                  const Expanded(
                    child: _ReportSectionHeading(
                      eyebrow: 'Mengajar',
                      title: 'Riwayat Presensi Jurnal Mengajar',
                    ),
                  ),
                  OutlinedButton.icon(
                    onPressed: exportingTeaching
                        ? null
                        : () async {
                            await onDownloadTeaching();
                          },
                    style: OutlinedButton.styleFrom(
                      foregroundColor: _ReportPalette.primaryDark,
                      side: const BorderSide(color: _ReportPalette.border),
                      padding: const EdgeInsets.symmetric(
                        horizontal: 12,
                        vertical: 10,
                      ),
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(14),
                      ),
                    ),
                    icon: exportingTeaching
                        ? const SizedBox(
                            width: 14,
                            height: 14,
                            child: CircularProgressIndicator(strokeWidth: 2),
                          )
                        : const Icon(Icons.picture_as_pdf_rounded, size: 16),
                    label: const Text(
                      'PDF',
                      style: TextStyle(fontWeight: FontWeight.w700),
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 4),
              Text(
                teaching['period_label'] as String? ?? '-',
                style: const TextStyle(
                  color: _ReportPalette.textSecondary,
                  fontSize: 11.5,
                ),
              ),
              const SizedBox(height: 10),
              if (teachingRecords.isEmpty)
                const AppEmptyState(
                  title: 'Belum ada riwayat mengajar',
                  message: 'Data presensi jurnal mengajar akan tampil di sini.',
                  icon: Icons.menu_book_outlined,
                )
              else
                ...teachingRecords.map(
                  (item) => _SimpleHistoryCard(
                    title: item['subject'] as String? ?? '-',
                    badgeLabel: item['date_label'] as String? ?? '-',
                    badgeColor: _ReportPalette.info,
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
    required this.badgeLabel,
    required this.badgeColor,
    required this.subtitle,
    required this.detail,
  });

  final String title;
  final String badgeLabel;
  final Color badgeColor;
  final String subtitle;
  final String detail;

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: const EdgeInsets.only(bottom: 10),
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: _ReportPalette.surface,
        borderRadius: BorderRadius.circular(18),
        border: Border.all(color: _ReportPalette.border),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Expanded(
                child: Text(
                  title,
                  style: const TextStyle(
                    color: _ReportPalette.textPrimary,
                    fontWeight: FontWeight.w800,
                    fontSize: 13,
                  ),
                ),
              ),
              const SizedBox(width: 8),
              _HistoryBadge(
                label: badgeLabel,
                color: badgeColor,
              ),
            ],
          ),
          const SizedBox(height: 4),
          Text(
            subtitle,
            style: const TextStyle(
              color: _ReportPalette.textSecondary,
              fontSize: 11.5,
              fontWeight: FontWeight.w600,
            ),
          ),
          const SizedBox(height: 8),
          Text(
            detail,
            style: const TextStyle(
              color: _ReportPalette.textPrimary,
              fontSize: 12,
              height: 1.45,
            ),
          ),
        ],
      ),
    );
  }
}

class _ReportMetricTile extends StatelessWidget {
  const _ReportMetricTile({
    required this.label,
    required this.value,
    required this.color,
    required this.surface,
    required this.icon,
  });

  final String label;
  final String value;
  final Color color;
  final Color surface;
  final IconData icon;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 12),
      decoration: BoxDecoration(
        color: _ReportPalette.surface,
        borderRadius: BorderRadius.circular(18),
        border: Border.all(color: _ReportPalette.border),
      ),
      child: Column(
        children: [
          Container(
            width: 32,
            height: 32,
            decoration: BoxDecoration(
              color: surface,
              borderRadius: BorderRadius.circular(10),
            ),
            child: Icon(icon, size: 18, color: color),
          ),
          const SizedBox(height: 8),
          Text(
            value,
            style: const TextStyle(
              color: _ReportPalette.textPrimary,
              fontSize: 17,
              fontWeight: FontWeight.w800,
            ),
          ),
          const SizedBox(height: 2),
          Text(
            label,
            textAlign: TextAlign.center,
            style: const TextStyle(
              color: _ReportPalette.textSecondary,
              fontSize: 10.5,
              fontWeight: FontWeight.w700,
            ),
          ),
        ],
      ),
    );
  }
}

class _ReportSectionHeading extends StatelessWidget {
  const _ReportSectionHeading({
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
            color: _ReportPalette.textSecondary,
            fontSize: 10.5,
            fontWeight: FontWeight.w800,
            letterSpacing: 0.4,
          ),
        ),
        const SizedBox(height: 3),
        Text(
          title,
          style: const TextStyle(
            color: _ReportPalette.textPrimary,
            fontSize: 15,
            fontWeight: FontWeight.w800,
          ),
        ),
      ],
    );
  }
}

class _HistoryBadge extends StatelessWidget {
  const _HistoryBadge({
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
        border: Border.all(color: _ReportPalette.border),
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

class _ReportLoading extends StatelessWidget {
  const _ReportLoading();

  @override
  Widget build(BuildContext context) {
    return const AppSectionCard(
      child: SizedBox(
        height: 220,
        child: Center(
          child: CircularProgressIndicator(
            color: _ReportPalette.primary,
          ),
        ),
      ),
    );
  }
}

class _ReportError extends StatelessWidget {
  const _ReportError({
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
          const AppEmptyState(
            title: 'Laporan belum bisa dimuat',
            message: 'Tarik ke bawah atau coba lagi beberapa saat lagi.',
            icon: Icons.error_outline_rounded,
          ),
          Text(
            message,
            textAlign: TextAlign.center,
            style: const TextStyle(
              color: _ReportPalette.danger,
              fontWeight: FontWeight.w700,
              fontSize: 12,
            ),
          ),
          const SizedBox(height: 10),
          OutlinedButton(
            onPressed: onRetry,
            style: OutlinedButton.styleFrom(
              foregroundColor: _ReportPalette.primaryDark,
              side: const BorderSide(color: _ReportPalette.border),
            ),
            child: const Text('Coba Lagi'),
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
    fillColor: _ReportPalette.surface,
    isDense: true,
    contentPadding: const EdgeInsets.symmetric(horizontal: 14, vertical: 14),
    labelStyle: const TextStyle(
      color: _ReportPalette.textSecondary,
      fontSize: 13,
    ),
    border: OutlineInputBorder(
      borderRadius: BorderRadius.circular(16),
      borderSide: const BorderSide(color: _ReportPalette.border),
    ),
    enabledBorder: OutlineInputBorder(
      borderRadius: BorderRadius.circular(16),
      borderSide: const BorderSide(color: _ReportPalette.border),
    ),
    focusedBorder: OutlineInputBorder(
      borderRadius: BorderRadius.circular(16),
      borderSide: const BorderSide(color: _ReportPalette.primary, width: 1.4),
    ),
  );
}

Color _reportStatusColor(String? status) {
  switch (status) {
    case 'hadir':
      return _ReportPalette.success;
    case 'izin':
      return _ReportPalette.warning;
    case 'alpha':
      return _ReportPalette.danger;
    default:
      return _ReportPalette.info;
  }
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
