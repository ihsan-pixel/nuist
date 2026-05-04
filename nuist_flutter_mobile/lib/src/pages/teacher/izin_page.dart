import 'package:flutter/material.dart';

import '../../services/teacher_mobile_repository.dart';
import '../../widgets/app/app_empty_state.dart';
import '../../widgets/app/app_section_card.dart';
import '../../widgets/app/app_stat_card.dart';

class TeacherIzinPage extends StatefulWidget {
  const TeacherIzinPage({
    super.key,
    required this.repository,
  });

  final TeacherMobileRepository repository;

  @override
  State<TeacherIzinPage> createState() => _TeacherIzinPageState();
}

class _TeacherIzinPageState extends State<TeacherIzinPage> {
  late Future<Map<String, dynamic>> _future;

  @override
  void initState() {
    super.initState();
    _future = widget.repository.getIzin();
  }

  Future<void> _refresh() async {
    final future = widget.repository.getIzin();
    setState(() {
      _future = future;
    });
    await future;
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF2F7F6),
      appBar: AppBar(
        title: const Text('Daftar Izin'),
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
                  _IzinContent(
                      data: snapshot.data ?? const <String, dynamic>{}),
              ],
            ),
          );
        },
      ),
    );
  }
}

class _IzinContent extends StatelessWidget {
  const _IzinContent({
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
        SingleChildScrollView(
          scrollDirection: Axis.horizontal,
          child: Row(
            children: [
              AppStatCard(
                label: 'Pending',
                value: '${summary['pending'] ?? 0}',
                color: const Color(0xFFF4A12A),
              ),
              const SizedBox(width: 12),
              AppStatCard(
                label: 'Disetujui',
                value: '${summary['approved'] ?? 0}',
                color: const Color(0xFF2E8B57),
              ),
              const SizedBox(width: 12),
              AppStatCard(
                label: 'Ditolak',
                value: '${summary['rejected'] ?? 0}',
                color: const Color(0xFFB42318),
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
                'Riwayat Izin',
                style: TextStyle(
                  fontSize: 16,
                  fontWeight: FontWeight.w800,
                  color: Color(0xFF1F4F4C),
                ),
              ),
              const SizedBox(height: 12),
              if (items.isEmpty)
                const AppEmptyState(
                  title: 'Belum ada pengajuan izin',
                  message: 'Riwayat izin Anda akan tampil di sini.',
                  icon: Icons.assignment_outlined,
                )
              else
                ...items.map(
                  (item) => Container(
                    margin: const EdgeInsets.only(bottom: 12),
                    padding: const EdgeInsets.all(14),
                    decoration: BoxDecoration(
                      color: const Color(0xFFF7FBFB),
                      borderRadius: BorderRadius.circular(18),
                      border: Border.all(color: const Color(0xFFDDEBE9)),
                    ),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Row(
                          children: [
                            Expanded(
                              child: Text(
                                item['title'] as String? ?? '-',
                                style: const TextStyle(
                                  fontWeight: FontWeight.w800,
                                  color: Color(0xFF1F4F4C),
                                ),
                              ),
                            ),
                            _IzinStatusPill(
                              status: item['status'] as String? ?? '-',
                            ),
                          ],
                        ),
                        const SizedBox(height: 6),
                        Text(
                          item['submitted_at_label'] as String? ?? '-',
                          style: const TextStyle(
                            color: Color(0xFF6D7F7D),
                            fontWeight: FontWeight.w600,
                          ),
                        ),
                        if ((item['end_date_label'] as String?) != null) ...[
                          const SizedBox(height: 4),
                          Text(
                            'Sampai ${item['end_date_label']}',
                            style: const TextStyle(
                              color: Color(0xFF8BA3A1),
                              fontSize: 12,
                            ),
                          ),
                        ],
                        if ((item['reason'] as String?) != null &&
                            (item['reason'] as String).isNotEmpty) ...[
                          const SizedBox(height: 8),
                          Text(
                            item['reason'] as String,
                            style: const TextStyle(
                              color: Color(0xFF1F4F4C),
                            ),
                          ),
                        ],
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

class _IzinStatusPill extends StatelessWidget {
  const _IzinStatusPill({
    required this.status,
  });

  final String status;

  @override
  Widget build(BuildContext context) {
    final color = status == 'approved'
        ? const Color(0xFF2E8B57)
        : status == 'rejected'
            ? const Color(0xFFB42318)
            : const Color(0xFFF4A12A);

    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
      decoration: BoxDecoration(
        color: color.withOpacity(0.12),
        borderRadius: BorderRadius.circular(999),
      ),
      child: Text(
        status,
        style: TextStyle(
          color: color,
          fontSize: 12,
          fontWeight: FontWeight.w800,
        ),
      ),
    );
  }
}
