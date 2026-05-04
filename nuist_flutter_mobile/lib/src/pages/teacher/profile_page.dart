import 'package:flutter/material.dart';

import '../../services/teacher_mobile_repository.dart';
import '../../widgets/app/app_detail_row.dart';
import '../../widgets/app/app_empty_state.dart';
import '../../widgets/app/app_section_card.dart';

class TeacherProfilePage extends StatefulWidget {
  const TeacherProfilePage({
    super.key,
    required this.repository,
    required this.onOpenIzin,
  });

  final TeacherMobileRepository repository;
  final Future<void> Function() onOpenIzin;

  @override
  State<TeacherProfilePage> createState() => _TeacherProfilePageState();
}

class _TeacherProfilePageState extends State<TeacherProfilePage> {
  late Future<Map<String, dynamic>> _future;

  @override
  void initState() {
    super.initState();
    _future = widget.repository.getProfile();
  }

  Future<void> _refresh() async {
    final future = widget.repository.getProfile();
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
                _ProfileContent(
                  data: snapshot.data ?? const <String, dynamic>{},
                  onOpenIzin: widget.onOpenIzin,
                ),
            ],
          ),
        );
      },
    );
  }
}

class _ProfileContent extends StatelessWidget {
  const _ProfileContent({
    required this.data,
    required this.onOpenIzin,
  });

  final Map<String, dynamic> data;
  final Future<void> Function() onOpenIzin;

  @override
  Widget build(BuildContext context) {
    final user = Map<String, dynamic>.from(
      (data['user'] as Map?) ?? const <String, dynamic>{},
    );
    final details = ((data['details'] as List?) ?? const [])
        .whereType<Map>()
        .map((item) => Map<String, dynamic>.from(item))
        .toList();
    final memberships = ((data['mgmp_memberships'] as List?) ?? const [])
        .whereType<Map>()
        .map((item) => Map<String, dynamic>.from(item))
        .toList();
    final activities = ((data['upcoming_activities'] as List?) ?? const [])
        .whereType<Map>()
        .map((item) => Map<String, dynamic>.from(item))
        .toList();

    return Column(
      children: [
        AppSectionCard(
          child: Column(
            children: [
              CircleAvatar(
                radius: 34,
                backgroundColor: const Color(0xFFEAF7F6),
                backgroundImage: (user['avatar_url'] as String?) != null
                    ? NetworkImage(user['avatar_url'] as String)
                    : null,
                child: (user['avatar_url'] as String?) == null
                    ? const Icon(
                        Icons.person_rounded,
                        size: 34,
                        color: Color(0xFF0D8E89),
                      )
                    : null,
              ),
              const SizedBox(height: 12),
              Text(
                user['name'] as String? ?? '-',
                textAlign: TextAlign.center,
                style: const TextStyle(
                  fontSize: 22,
                  fontWeight: FontWeight.w800,
                  color: Color(0xFF1F4F4C),
                ),
              ),
              const SizedBox(height: 6),
              Text(
                user['email'] as String? ?? '-',
                textAlign: TextAlign.center,
                style: const TextStyle(
                  color: Color(0xFF6D7F7D),
                  fontWeight: FontWeight.w600,
                ),
              ),
              const SizedBox(height: 4),
              Text(
                user['school_name'] as String? ?? '-',
                textAlign: TextAlign.center,
                style: const TextStyle(
                  color: Color(0xFF8BA3A1),
                  fontSize: 12,
                ),
              ),
              const SizedBox(height: 14),
              FilledButton.icon(
                onPressed: () {
                  onOpenIzin();
                },
                icon: const Icon(Icons.receipt_long_rounded),
                label: const Text('Buka Daftar Izin'),
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
                'Informasi Profil',
                style: TextStyle(
                  fontSize: 16,
                  fontWeight: FontWeight.w800,
                  color: Color(0xFF1F4F4C),
                ),
              ),
              const SizedBox(height: 10),
              ...details.map(
                (item) => AppDetailRow(
                  label: item['label'] as String? ?? '-',
                  value: item['value'] as String? ?? '-',
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
              const Text(
                'Keanggotaan MGMP',
                style: TextStyle(
                  fontSize: 16,
                  fontWeight: FontWeight.w800,
                  color: Color(0xFF1F4F4C),
                ),
              ),
              const SizedBox(height: 12),
              if (memberships.isEmpty)
                const AppEmptyState(
                  title: 'Belum ada keanggotaan MGMP',
                  message: 'Keanggotaan MGMP akan tampil di sini.',
                  icon: Icons.groups_outlined,
                )
              else
                ...memberships.map(
                  (item) => ListTile(
                    contentPadding: EdgeInsets.zero,
                    leading: const Icon(Icons.groups_2_rounded),
                    title: Text(item['group_name'] as String? ?? '-'),
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
              const Text(
                'Agenda MGMP Mendatang',
                style: TextStyle(
                  fontSize: 16,
                  fontWeight: FontWeight.w800,
                  color: Color(0xFF1F4F4C),
                ),
              ),
              const SizedBox(height: 12),
              if (activities.isEmpty)
                const AppEmptyState(
                  title: 'Belum ada agenda mendatang',
                  message: 'Agenda MGMP terdekat akan tampil di sini.',
                  icon: Icons.event_available_outlined,
                )
              else
                ...activities.map(
                  (item) => ListTile(
                    contentPadding: EdgeInsets.zero,
                    title: Text(item['title'] as String? ?? '-'),
                    subtitle: Text(
                      '${item['date_label'] ?? '-'}'
                      '${(item['time_label'] as String?) != null ? ' • ${item['time_label']}' : ''}',
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
