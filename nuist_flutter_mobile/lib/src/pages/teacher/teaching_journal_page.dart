import 'package:flutter/material.dart';

import '../../services/teacher_mobile_repository.dart';
import '../../widgets/app/app_empty_state.dart';
import '../../widgets/app/app_section_card.dart';
import '../../widgets/app/app_stat_card.dart';

class TeacherTeachingJournalPage extends StatefulWidget {
  const TeacherTeachingJournalPage({
    super.key,
    required this.repository,
  });

  final TeacherMobileRepository repository;

  @override
  State<TeacherTeachingJournalPage> createState() =>
      _TeacherTeachingJournalPageState();
}

class _TeacherTeachingJournalPageState
    extends State<TeacherTeachingJournalPage> {
  late Future<Map<String, dynamic>> _future;

  @override
  void initState() {
    super.initState();
    _future = widget.repository.getTeachingJournal();
  }

  Future<void> _refresh() async {
    final future = widget.repository.getTeachingJournal();
    setState(() {
      _future = future;
    });
    await future;
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
                _JournalContent(
                    data: snapshot.data ?? const <String, dynamic>{}),
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
  });

  final Map<String, dynamic> data;

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
        Text(
          data['month_label'] as String? ?? '-',
          style: const TextStyle(
            fontSize: 14,
            color: Color(0xFF6D7F7D),
            fontWeight: FontWeight.w700,
          ),
        ),
        const SizedBox(height: 12),
        SingleChildScrollView(
          scrollDirection: Axis.horizontal,
          child: Row(
            children: [
              AppStatCard(
                label: 'Total Jurnal',
                value: '${summary['total_entries'] ?? 0}',
                color: const Color(0xFF0D8E89),
              ),
              const SizedBox(width: 12),
              AppStatCard(
                label: 'Siswa Hadir',
                value: '${summary['total_present_students'] ?? 0}',
                color: const Color(0xFF004B48),
              ),
              const SizedBox(width: 12),
              AppStatCard(
                label: 'Kelas Tercatat',
                value: '${summary['total_classes'] ?? 0}',
                color: const Color(0xFF6C8C3C),
              ),
            ],
          ),
        ),
        const SizedBox(height: 16),
        AppSectionCard(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const Text(
                'Riwayat Jurnal Mengajar',
                style: TextStyle(
                  fontSize: 16,
                  fontWeight: FontWeight.w800,
                  color: Color(0xFF1F4F4C),
                ),
              ),
              const SizedBox(height: 12),
              if (items.isEmpty)
                const AppEmptyState(
                  title: 'Belum ada jurnal mengajar',
                  message: 'Riwayat presensi mengajar akan tampil di sini.',
                  icon: Icons.menu_book_outlined,
                )
              else
                ...items.map(
                  (item) => Padding(
                    padding: const EdgeInsets.only(bottom: 12),
                    child: Container(
                      padding: const EdgeInsets.all(14),
                      decoration: BoxDecoration(
                        color: const Color(0xFFF7FBFB),
                        borderRadius: BorderRadius.circular(18),
                        border: Border.all(color: const Color(0xFFDDEBE9)),
                      ),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            item['subject'] as String? ?? '-',
                            style: const TextStyle(
                              fontWeight: FontWeight.w800,
                              color: Color(0xFF1F4F4C),
                            ),
                          ),
                          const SizedBox(height: 6),
                          Text(
                            '${item['class_name'] ?? '-'} • ${item['date_label'] ?? '-'}',
                            style: const TextStyle(
                              color: Color(0xFF6D7F7D),
                              fontWeight: FontWeight.w600,
                            ),
                          ),
                          const SizedBox(height: 8),
                          Text(
                            item['materi'] as String? ?? '-',
                            style: const TextStyle(
                              color: Color(0xFF1F4F4C),
                            ),
                          ),
                          const SizedBox(height: 8),
                          Text(
                            'Hadir ${item['present_students'] ?? '-'} dari ${item['class_total_students'] ?? '-'} siswa'
                            '${item['student_attendance_percentage'] != null ? ' • ${item['student_attendance_percentage']}%' : ''}',
                            style: const TextStyle(
                              color: Color(0xFF8BA3A1),
                              fontSize: 12,
                              fontWeight: FontWeight.w600,
                            ),
                          ),
                        ],
                      ),
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
