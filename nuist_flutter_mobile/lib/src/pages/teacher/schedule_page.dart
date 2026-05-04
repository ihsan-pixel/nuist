import 'package:flutter/material.dart';

import '../../services/teacher_mobile_repository.dart';
import '../../widgets/app/app_empty_state.dart';
import '../../widgets/app/app_section_card.dart';

class TeacherSchedulePage extends StatefulWidget {
  const TeacherSchedulePage({
    super.key,
    required this.repository,
  });

  final TeacherMobileRepository repository;

  @override
  State<TeacherSchedulePage> createState() => _TeacherSchedulePageState();
}

class _TeacherSchedulePageState extends State<TeacherSchedulePage> {
  late Future<Map<String, dynamic>> _future;

  @override
  void initState() {
    super.initState();
    _future = widget.repository.getSchedule();
  }

  Future<void> _refresh() async {
    final future = widget.repository.getSchedule();
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
                _ScheduleContent(
                    data: snapshot.data ?? const <String, dynamic>{}),
            ],
          ),
        );
      },
    );
  }
}

class _ScheduleContent extends StatelessWidget {
  const _ScheduleContent({
    required this.data,
  });

  final Map<String, dynamic> data;

  @override
  Widget build(BuildContext context) {
    final items = ((data['items'] as List?) ?? const [])
        .whereType<Map>()
        .map((item) => Map<String, dynamic>.from(item))
        .toList();

    if (items.isEmpty) {
      return const AppSectionCard(
        child: AppEmptyState(
          title: 'Jadwal belum tersedia',
          message: 'Belum ada jadwal mengajar untuk akun ini.',
          icon: Icons.calendar_month_outlined,
        ),
      );
    }

    final grouped = <String, List<Map<String, dynamic>>>{};
    for (final item in items) {
      final day = item['day'] as String? ?? 'Lainnya';
      grouped.putIfAbsent(day, () => <Map<String, dynamic>>[]).add(item);
    }

    return Column(
      children: grouped.entries.map((entry) {
        return Padding(
          padding: const EdgeInsets.only(bottom: 16),
          child: AppSectionCard(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  entry.key,
                  style: const TextStyle(
                    fontSize: 17,
                    fontWeight: FontWeight.w800,
                    color: Color(0xFF1F4F4C),
                  ),
                ),
                const SizedBox(height: 14),
                ...entry.value.map(
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
                            '${item['class_name'] ?? '-'} • ${item['start_time'] ?? '-'} - ${item['end_time'] ?? '-'}',
                            style: const TextStyle(
                              color: Color(0xFF6D7F7D),
                              fontWeight: FontWeight.w600,
                            ),
                          ),
                          if ((item['school_name'] as String?) != null) ...[
                            const SizedBox(height: 4),
                            Text(
                              item['school_name'] as String,
                              style: const TextStyle(
                                color: Color(0xFF8BA3A1),
                                fontSize: 12,
                              ),
                            ),
                          ],
                        ],
                      ),
                    ),
                  ),
                ),
              ],
            ),
          ),
        );
      }).toList(),
    );
  }
}
