import 'package:flutter/material.dart';

import '../../services/teacher_mobile_repository.dart';
import '../../widgets/app/app_empty_state.dart';
import '../../widgets/app/app_section_card.dart';
import '../../widgets/app/app_stat_card.dart';
import 'report_page.dart';

const _staffPrimary = Color(0xFFF49637);
const _staffText = Color(0xFF1F4F4C);
const _staffMuted = Color(0xFF6D7F7D);

class TeacherStaffAttendancePage extends StatefulWidget {
  const TeacherStaffAttendancePage({
    super.key,
    required this.repository,
  });

  final TeacherMobileRepository repository;

  @override
  State<TeacherStaffAttendancePage> createState() =>
      _TeacherStaffAttendancePageState();
}

class _TeacherStaffAttendancePageState extends State<TeacherStaffAttendancePage> {
  late Future<Map<String, dynamic>> _future;
  late String _date;

  @override
  void initState() {
    super.initState();
    final now = DateTime.now();
    _date =
        '${now.year.toString().padLeft(4, '0')}-${now.month.toString().padLeft(2, '0')}-${now.day.toString().padLeft(2, '0')}';
    _future = widget.repository.getStaffAttendance(date: _date);
  }

  Future<void> _refresh() async {
    final future = widget.repository.getStaffAttendance(date: _date);
    setState(() {
      _future = future;
    });
    await future;
  }

  Future<void> _pickDate() async {
    final parts = _date.split('-');
    final initialDate = DateTime(
      int.parse(parts[0]),
      int.parse(parts[1]),
      int.parse(parts[2]),
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
    final nextDate =
        '${picked.year.toString().padLeft(4, '0')}-${picked.month.toString().padLeft(2, '0')}-${picked.day.toString().padLeft(2, '0')}';
    final future = widget.repository.getStaffAttendance(date: nextDate);
    setState(() {
      _date = nextDate;
      _future = future;
    });
    await future;
  }

  Future<void> _openTeacherReport(int teacherId, String teacherName) async {
    await Navigator.of(context).push(
      MaterialPageRoute(
        builder: (_) => TeacherReportPage(
          repository: widget.repository,
          initialTeacherId: teacherId,
          pageTitle: 'Laporan $teacherName',
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFFFFAF5),
      appBar: AppBar(
        title: const Text('Data Presensi'),
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
                  _StaffAttendanceContent(
                    data: snapshot.data ?? const <String, dynamic>{},
                    selectedDate: _date,
                    onPickDate: _pickDate,
                    onOpenTeacherReport: _openTeacherReport,
                  ),
              ],
            ),
          );
        },
      ),
    );
  }
}

class _StaffAttendanceContent extends StatelessWidget {
  const _StaffAttendanceContent({
    required this.data,
    required this.selectedDate,
    required this.onPickDate,
    required this.onOpenTeacherReport,
  });

  final Map<String, dynamic> data;
  final String selectedDate;
  final Future<void> Function() onPickDate;
  final Future<void> Function(int teacherId, String teacherName)
      onOpenTeacherReport;

  @override
  Widget build(BuildContext context) {
    final summary = Map<String, dynamic>.from(
      (data['summary'] as Map?) ?? const <String, dynamic>{},
    );
    final items = ((data['items'] as List?) ?? const [])
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
                'Monitoring Hari Ini',
                style: TextStyle(
                  color: _staffText,
                  fontSize: 16,
                  fontWeight: FontWeight.w800,
                ),
              ),
              const SizedBox(height: 6),
              Text(
                data['today_label'] as String? ?? selectedDate,
                style: const TextStyle(
                  color: _staffMuted,
                  fontWeight: FontWeight.w600,
                ),
              ),
              const SizedBox(height: 12),
              InkWell(
                borderRadius: BorderRadius.circular(18),
                onTap: onPickDate,
                child: Ink(
                  padding: const EdgeInsets.symmetric(
                    horizontal: 14,
                    vertical: 14,
                  ),
                  decoration: BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(18),
                    border: Border.all(color: const Color(0xFFE8D6C6)),
                  ),
                  child: Row(
                    children: [
                      const Icon(
                        Icons.calendar_today_rounded,
                        color: _staffPrimary,
                      ),
                      const SizedBox(width: 10),
                      Expanded(
                        child: Text(
                          selectedDate,
                          style: const TextStyle(
                            color: _staffText,
                            fontWeight: FontWeight.w700,
                          ),
                        ),
                      ),
                    ],
                  ),
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
                label: 'Total Guru',
                value: '${summary['total_teacher'] ?? 0}',
                color: const Color(0xFF3C6FD1),
              ),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: AppStatCard(
                label: 'Hadir',
                value: '${summary['hadir'] ?? 0}',
                color: const Color(0xFF2E8B57),
              ),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: AppStatCard(
                label: 'Izin/Belum',
                value:
                    '${(summary['izin'] ?? 0) + (summary['belum_presensi'] ?? 0)}',
                color: const Color(0xFFF4A12A),
              ),
            ),
          ],
        ),
        const SizedBox(height: 16),
        AppSectionCard(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const Text(
                'Daftar Tenaga Pendidik',
                style: TextStyle(
                  color: _staffText,
                  fontSize: 16,
                  fontWeight: FontWeight.w800,
                ),
              ),
              const SizedBox(height: 12),
              if (items.isEmpty)
                const AppEmptyState(
                  title: 'Belum ada data',
                  message: 'Data presensi guru akan tampil di sini.',
                  icon: Icons.groups_outlined,
                )
              else
                ...items.map(
                  (item) => Container(
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
                        Row(
                          children: [
                            Expanded(
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Text(
                                    item['teacher_name'] as String? ?? '-',
                                    style: const TextStyle(
                                      color: _staffText,
                                      fontWeight: FontWeight.w800,
                                    ),
                                  ),
                                  const SizedBox(height: 2),
                                  Text(
                                    item['ketugasan'] as String? ?? '-',
                                    style: const TextStyle(
                                      color: _staffMuted,
                                      fontSize: 12,
                                    ),
                                  ),
                                ],
                              ),
                            ),
                            _StaffStatusPill(
                              status: item['status'] as String? ?? '-',
                              label: item['status_label'] as String? ?? '-',
                            ),
                          ],
                        ),
                        const SizedBox(height: 8),
                        Text(
                          'Masuk ${item['check_in'] ?? '-'} • Keluar ${item['check_out'] ?? '-'}',
                          style: const TextStyle(
                            color: _staffMuted,
                            fontSize: 12,
                            fontWeight: FontWeight.w600,
                          ),
                        ),
                        const SizedBox(height: 4),
                        Text(
                          'Mengajar ${item['teaching_completed'] ?? 0} / ${item['teaching_total'] ?? 0} jadwal',
                          style: const TextStyle(
                            color: _staffMuted,
                            fontSize: 12,
                            fontWeight: FontWeight.w600,
                          ),
                        ),
                        if ((item['note'] as String?)?.isNotEmpty == true) ...[
                          const SizedBox(height: 8),
                          Text(
                            item['note'] as String,
                            style: const TextStyle(
                              color: _staffText,
                              height: 1.45,
                            ),
                          ),
                        ],
                        const SizedBox(height: 12),
                        SizedBox(
                          width: double.infinity,
                          child: OutlinedButton.icon(
                            onPressed: () async {
                              await onOpenTeacherReport(
                                (item['teacher_id'] as num).toInt(),
                                item['teacher_name'] as String? ?? 'Guru',
                              );
                            },
                            style: OutlinedButton.styleFrom(
                              foregroundColor: _staffPrimary,
                              side: const BorderSide(color: Color(0xFFF0C28B)),
                              shape: RoundedRectangleBorder(
                                borderRadius: BorderRadius.circular(16),
                              ),
                              padding: const EdgeInsets.symmetric(vertical: 13),
                            ),
                            icon: const Icon(Icons.history_rounded),
                            label: const Text(
                              'Lihat Riwayat dan Export PDF',
                              style: TextStyle(fontWeight: FontWeight.w800),
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
            ],
          ),
        ),
      ],
    );
  }
}

class _StaffStatusPill extends StatelessWidget {
  const _StaffStatusPill({
    required this.status,
    required this.label,
  });

  final String status;
  final String label;

  @override
  Widget build(BuildContext context) {
    final color = switch (status) {
      'hadir' => const Color(0xFF2E8B57),
      'izin' => const Color(0xFFF4A12A),
      'alpha' => const Color(0xFFB42318),
      _ => const Color(0xFF7C8F8D),
    };

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
          fontSize: 12,
          fontWeight: FontWeight.w800,
        ),
      ),
    );
  }
}
