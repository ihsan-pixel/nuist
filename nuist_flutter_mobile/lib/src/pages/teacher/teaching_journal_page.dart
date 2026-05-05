import 'package:flutter/material.dart';

import '../../services/teacher_mobile_repository.dart';
import '../../widgets/app/app_empty_state.dart';
import '../../widgets/app/app_section_card.dart';
import '../../widgets/app/teacher_page_header.dart';

class TeacherTeachingJournalPage extends StatefulWidget {
  const TeacherTeachingJournalPage({
    super.key,
    required this.repository,
    required this.onBackToHome,
  });

  final TeacherMobileRepository repository;
  final VoidCallback onBackToHome;

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
                const _PageLoading()
              else if (snapshot.hasError)
                _PageError(
                  message: snapshot.error.toString(),
                  onRetry: _refresh,
                )
              else
                _JournalContent(
                  data: snapshot.data ?? const <String, dynamic>{},
                  onBackToHome: widget.onBackToHome,
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
    required this.onBackToHome,
  });

  final Map<String, dynamic> data;
  final VoidCallback onBackToHome;

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
        TeacherPageHeader(
          title: 'Jurnal',
          onBack: onBackToHome,
        ),
        const SizedBox(height: 18),
        const _PageSectionHeading(
          eyebrow: 'Mengajar',
          title: 'Ringkasan Jurnal',
        ),
        const SizedBox(height: 12),
        _JournalHeroCard(
          monthLabel: data['month_label'] as String? ?? '-',
          totalEntries: '${summary['total_entries'] ?? 0}',
          totalClasses: '${summary['total_classes'] ?? 0}',
        ),
        const SizedBox(height: 18),
        Row(
          children: [
            Expanded(
              child: _JournalSummaryTile(
                label: 'Total Jurnal',
                value: '${summary['total_entries'] ?? 0}',
                accent: const Color(0xFFF49637),
              ),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: _JournalSummaryTile(
                label: 'Siswa Hadir',
                value: '${summary['total_present_students'] ?? 0}',
                accent: const Color(0xFFF49637),
              ),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: _JournalSummaryTile(
                label: 'Kelas',
                value: '${summary['total_classes'] ?? 0}',
                accent: const Color(0xFFF49637),
              ),
            ),
          ],
        ),
        const SizedBox(height: 18),
        const _PageSectionHeading(
          eyebrow: 'Aktivitas',
          title: 'Riwayat Jurnal',
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
    required this.monthLabel,
    required this.totalEntries,
    required this.totalClasses,
  });

  final String monthLabel;
  final String totalEntries;
  final String totalClasses;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(18),
      decoration: BoxDecoration(
        gradient: const LinearGradient(
          colors: [
            Color(0xFFF49637),
            Color(0xFFC96A19),
          ],
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
      child: Row(
        children: [
          Container(
            width: 54,
            height: 54,
            decoration: BoxDecoration(
              gradient: const LinearGradient(
                colors: [
                  Color(0xFFF49637),
                  Color(0xFFC96A19),
                ],
                begin: Alignment.topLeft,
                end: Alignment.bottomRight,
              ),
              borderRadius: BorderRadius.circular(18),
            ),
            child: const Icon(
              Icons.menu_book_rounded,
              color: Colors.white,
              size: 26,
            ),
          ),
          const SizedBox(width: 14),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  monthLabel,
                  style: TextStyle(
                    color: Colors.white.withOpacity(0.78),
                    fontWeight: FontWeight.w700,
                    fontSize: 12,
                  ),
                ),
                const SizedBox(height: 4),
                const Text(
                  'Jurnal Mengajar',
                  style: TextStyle(
                    color: Colors.white,
                    fontWeight: FontWeight.w800,
                    fontSize: 22,
                  ),
                ),
              ],
            ),
          ),
          Column(
            crossAxisAlignment: CrossAxisAlignment.end,
            children: [
              Text(
                totalEntries,
                style: const TextStyle(
                  color: Colors.white,
                  fontSize: 22,
                  fontWeight: FontWeight.w800,
                ),
              ),
              Text(
                '$totalClasses kelas',
                style: TextStyle(
                  color: Colors.white.withOpacity(0.78),
                  fontSize: 11,
                  fontWeight: FontWeight.w700,
                ),
              ),
            ],
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
  });

  final String label;
  final String value;
  final Color accent;

  @override
  Widget build(BuildContext context) {
    return AppSectionCard(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 14),
      child: Column(
        children: [
          Container(
            width: 32,
            height: 32,
            decoration: BoxDecoration(
              color: accent.withOpacity(0.12),
              borderRadius: BorderRadius.circular(12),
            ),
            child: Icon(
              Icons.insert_chart_outlined_rounded,
              color: accent,
              size: 18,
            ),
          ),
          const SizedBox(height: 10),
          Text(
            value,
            style: const TextStyle(
              color: Color(0xFF7A4212),
              fontSize: 18,
              fontWeight: FontWeight.w800,
            ),
          ),
          const SizedBox(height: 4),
          Text(
            label,
            textAlign: TextAlign.center,
            style: const TextStyle(
              color: Color(0xFF7A8F8C),
              fontSize: 11,
              fontWeight: FontWeight.w700,
            ),
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
                  color: const Color(0xFFFFF4E8),
                  borderRadius: BorderRadius.circular(16),
                ),
                child: const Icon(
                  Icons.cast_for_education_rounded,
                  color: Color(0xFFF49637),
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
                        color: Color(0xFF7A4212),
                        fontWeight: FontWeight.w800,
                        fontSize: 15,
                      ),
                    ),
                    const SizedBox(height: 4),
                    Text(
                      '${item['class_name'] ?? '-'} • ${item['date_label'] ?? '-'}',
                      style: const TextStyle(
                        color: Color(0xFF6D7F7D),
                        fontWeight: FontWeight.w700,
                        fontSize: 12,
                      ),
                    ),
                  ],
                ),
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
              _JournalMetaChip(
                label:
                    'Hadir ${item['present_students'] ?? '-'} / ${item['class_total_students'] ?? '-'}',
                color: const Color(0xFFF49637),
              ),
              if (percentage != null)
                _JournalMetaChip(
                  label: '$percentage%',
                  color: const Color(0xFFF49637),
                ),
              if ((item['time'] as String?)?.trim().isNotEmpty == true)
                _JournalMetaChip(
                  label: item['time'] as String,
                  color: const Color(0xFFF49637),
                ),
            ],
          ),
        ],
      ),
    );
  }
}

class _JournalMetaChip extends StatelessWidget {
  const _JournalMetaChip({
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

class _PageLoading extends StatelessWidget {
  const _PageLoading();

  @override
  Widget build(BuildContext context) {
    return const Center(
      child: Padding(
        padding: EdgeInsets.only(top: 120),
        child: CircularProgressIndicator(
          color: Color(0xFFF49637),
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
            color: Color(0xFFF49637),
            fontSize: 11,
            fontWeight: FontWeight.w800,
            letterSpacing: 0.6,
          ),
        ),
        const SizedBox(height: 4),
        Text(
          title,
          style: const TextStyle(
            color: Color(0xFF7A4212),
            fontSize: 17,
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
