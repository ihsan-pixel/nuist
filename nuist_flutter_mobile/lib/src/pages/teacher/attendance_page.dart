import 'package:flutter/material.dart';

import '../../services/teacher_mobile_repository.dart';
import '../../widgets/app/app_empty_state.dart';
import '../../widgets/app/app_section_card.dart';

class TeacherAttendancePage extends StatefulWidget {
  const TeacherAttendancePage({
    super.key,
    required this.repository,
  });

  final TeacherMobileRepository repository;

  @override
  State<TeacherAttendancePage> createState() => _TeacherAttendancePageState();
}

class _TeacherAttendancePageState extends State<TeacherAttendancePage> {
  late Future<Map<String, dynamic>> _future;

  @override
  void initState() {
    super.initState();
    _future = widget.repository.getAttendance();
  }

  Future<void> _refresh() async {
    final future = widget.repository.getAttendance();
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
                _AttendanceContent(
                  data: snapshot.data ?? const <String, dynamic>{},
                ),
            ],
          ),
        );
      },
    );
  }
}

class _AttendanceContent extends StatelessWidget {
  const _AttendanceContent({
    required this.data,
  });

  final Map<String, dynamic> data;

  @override
  Widget build(BuildContext context) {
    final today = Map<String, dynamic>.from(
      (data['today_attendance'] as Map?) ?? const <String, dynamic>{},
    );
    final recent = ((data['recent'] as List?) ?? const [])
        .whereType<Map>()
        .map((item) => Map<String, dynamic>.from(item))
        .toList();

    return Column(
      children: [
        AppSectionCard(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                data['today_label'] as String? ?? '-',
                style: const TextStyle(
                  fontSize: 12,
                  color: Color(0xFF8BA3A1),
                ),
              ),
              const SizedBox(height: 10),
              Text(
                today['status_label'] as String? ?? 'Belum presensi',
                style: const TextStyle(
                  fontSize: 22,
                  fontWeight: FontWeight.w800,
                  color: Color(0xFF1F4F4C),
                ),
              ),
              const SizedBox(height: 12),
              _AttendanceInfoRow(
                label: 'Masuk',
                value: today['check_in'] as String? ?? '-',
              ),
              _AttendanceInfoRow(
                label: 'Keluar',
                value: today['check_out'] as String? ?? '-',
              ),
              _AttendanceInfoRow(
                label: 'Lokasi',
                value: today['location'] as String? ?? '-',
              ),
              const SizedBox(height: 12),
              Container(
                padding: const EdgeInsets.all(12),
                decoration: BoxDecoration(
                  color: const Color(0xFFEAF7F6),
                  borderRadius: BorderRadius.circular(16),
                ),
                child: const Text(
                  'Halaman ini sudah menampilkan ringkasan presensi mobile. Flow selfie, lokasi, dan submit presensi penuh akan mengikuti implementasi mobile existing.',
                  style: TextStyle(
                    color: Color(0xFF0D5C63),
                    fontSize: 12,
                    fontWeight: FontWeight.w600,
                  ),
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
                'Riwayat Presensi',
                style: TextStyle(
                  fontSize: 16,
                  fontWeight: FontWeight.w800,
                  color: Color(0xFF1F4F4C),
                ),
              ),
              const SizedBox(height: 12),
              if (recent.isEmpty)
                const AppEmptyState(
                  title: 'Belum ada riwayat presensi',
                  message: 'Riwayat presensi akan tampil di sini.',
                  icon: Icons.history_toggle_off_rounded,
                )
              else
                ...recent.map(
                  (item) => ListTile(
                    contentPadding: EdgeInsets.zero,
                    title: Text(item['date_label'] as String? ?? '-'),
                    subtitle: Text(
                      '${item['check_in'] ?? '-'} / ${item['check_out'] ?? '-'}'
                      '${(item['school_name'] as String?) != null ? ' • ${item['school_name']}' : ''}',
                    ),
                    trailing: _StatusPill(
                      label: (item['status'] as String?) ?? '-',
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

class _AttendanceInfoRow extends StatelessWidget {
  const _AttendanceInfoRow({
    required this.label,
    required this.value,
  });

  final String label;
  final String value;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 8),
      child: Row(
        children: [
          SizedBox(
            width: 64,
            child: Text(
              label,
              style: const TextStyle(
                fontSize: 13,
                color: Color(0xFF6D7F7D),
              ),
            ),
          ),
          Expanded(
            child: Text(
              value,
              style: const TextStyle(
                fontSize: 13,
                fontWeight: FontWeight.w700,
                color: Color(0xFF1F4F4C),
              ),
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
  });

  final String label;

  @override
  Widget build(BuildContext context) {
    final normalized = label.toLowerCase();
    final color = normalized == 'hadir'
        ? const Color(0xFF2E8B57)
        : normalized == 'izin'
            ? const Color(0xFFF4A12A)
            : normalized == 'alpha'
                ? const Color(0xFFB42318)
                : const Color(0xFF6D7F7D);

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
          fontWeight: FontWeight.w800,
          fontSize: 12,
        ),
      ),
    );
  }
}
