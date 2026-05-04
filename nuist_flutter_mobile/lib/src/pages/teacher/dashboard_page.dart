import 'package:flutter/material.dart';

import '../../services/teacher_mobile_repository.dart';
import '../../widgets/app/app_empty_state.dart';
import '../../widgets/app/app_section_card.dart';
import '../../widgets/app/app_stat_card.dart';

class TeacherDashboardPage extends StatefulWidget {
  const TeacherDashboardPage({
    super.key,
    required this.repository,
    required this.onOpenIzin,
  });

  final TeacherMobileRepository repository;
  final Future<void> Function() onOpenIzin;

  @override
  State<TeacherDashboardPage> createState() => _TeacherDashboardPageState();
}

class _TeacherDashboardPageState extends State<TeacherDashboardPage> {
  late Future<Map<String, dynamic>> _future;

  @override
  void initState() {
    super.initState();
    _future = widget.repository.getDashboard();
  }

  Future<void> _refresh() async {
    final future = widget.repository.getDashboard();
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
                const _LoadingBlock()
              else if (snapshot.hasError)
                _ErrorBlock(
                  message: snapshot.error.toString(),
                  onRetry: _refresh,
                )
              else
                _DashboardContent(
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

class _DashboardContent extends StatelessWidget {
  const _DashboardContent({
    required this.data,
    required this.onOpenIzin,
  });

  final Map<String, dynamic> data;
  final Future<void> Function() onOpenIzin;

  @override
  Widget build(BuildContext context) {
    final summary = Map<String, dynamic>.from(
      (data['summary'] as Map?) ?? const <String, dynamic>{},
    );
    final todayAttendance = Map<String, dynamic>.from(
      (data['today_attendance'] as Map?) ?? const <String, dynamic>{},
    );
    final schedules = ((data['today_schedules'] as List?) ?? const [])
        .whereType<Map>()
        .map((item) => Map<String, dynamic>.from(item))
        .toList();
    final recentIzin = ((data['recent_izin'] as List?) ?? const [])
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
              Text(
                (data['greeting'] as String?) ?? 'Selamat datang',
                style: const TextStyle(
                  fontSize: 24,
                  fontWeight: FontWeight.w800,
                  color: Color(0xFF1F4F4C),
                ),
              ),
              const SizedBox(height: 6),
              Text(
                (data['school_name'] as String?) ?? '-',
                style: const TextStyle(
                  fontSize: 14,
                  color: Color(0xFF6D7F7D),
                  fontWeight: FontWeight.w600,
                ),
              ),
              const SizedBox(height: 14),
              Text(
                (data['today_label'] as String?) ?? '-',
                style: const TextStyle(
                  fontSize: 12,
                  color: Color(0xFF8BA3A1),
                ),
              ),
            ],
          ),
        ),
        const SizedBox(height: 16),
        SingleChildScrollView(
          scrollDirection: Axis.horizontal,
          child: Row(
            children: [
              AppStatCard(
                label: 'Kehadiran Bulan Ini',
                value: '${summary['attendance_percent'] ?? 0}%',
                color: const Color(0xFF0D8E89),
                trailing: const Icon(
                  Icons.trending_up_rounded,
                  color: Colors.white,
                ),
              ),
              const SizedBox(width: 12),
              AppStatCard(
                label: 'Izin Pending',
                value: '${summary['pending_izin_count'] ?? 0}',
                color: const Color(0xFFF4A12A),
                trailing: const Icon(
                  Icons.receipt_long_rounded,
                  color: Colors.white,
                ),
              ),
              const SizedBox(width: 12),
              AppStatCard(
                label: 'Jadwal Hari Ini',
                value: '${summary['teaching_today_count'] ?? 0}',
                color: const Color(0xFF004B48),
                trailing: const Icon(
                  Icons.calendar_today_rounded,
                  color: Colors.white,
                ),
              ),
              const SizedBox(width: 12),
              AppStatCard(
                label: 'Jurnal Selesai',
                value: '${summary['completed_teaching_today_count'] ?? 0}',
                color: const Color(0xFF6C8C3C),
                trailing: const Icon(
                  Icons.task_alt_rounded,
                  color: Colors.white,
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
              const _SectionTitle('Presensi Hari Ini'),
              const SizedBox(height: 12),
              _BadgeLabel(
                label: (todayAttendance['status_label'] as String?) ??
                    'Belum presensi',
                color: _statusColor(
                  (todayAttendance['status'] as String?) ?? 'belum_presensi',
                ),
              ),
              const SizedBox(height: 12),
              _InfoRow(
                label: 'Masuk',
                value: (todayAttendance['check_in'] as String?) ?? '-',
              ),
              _InfoRow(
                label: 'Keluar',
                value: (todayAttendance['check_out'] as String?) ?? '-',
              ),
              _InfoRow(
                label: 'Lokasi',
                value: (todayAttendance['location'] as String?) ?? '-',
              ),
              const SizedBox(height: 12),
              FilledButton.icon(
                onPressed: () {
                  onOpenIzin();
                },
                icon: const Icon(Icons.receipt_long_rounded),
                label: const Text('Lihat Daftar Izin'),
              ),
            ],
          ),
        ),
        const SizedBox(height: 16),
        AppSectionCard(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const _SectionTitle('Jadwal Mengajar Hari Ini'),
              const SizedBox(height: 12),
              if (schedules.isEmpty)
                const AppEmptyState(
                  title: 'Tidak ada jadwal hari ini',
                  message: 'Belum ada jadwal mengajar untuk hari ini.',
                  icon: Icons.event_busy_outlined,
                )
              else
                ...schedules.map(
                  (item) => Padding(
                    padding: const EdgeInsets.only(bottom: 12),
                    child: _ScheduleTile(item: item),
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
              const _SectionTitle('Izin Terbaru'),
              const SizedBox(height: 12),
              if (recentIzin.isEmpty)
                const AppEmptyState(
                  title: 'Belum ada pengajuan izin',
                  message: 'Riwayat izin Anda akan tampil di sini.',
                  icon: Icons.assignment_outlined,
                )
              else
                ...recentIzin.map(
                  (item) => ListTile(
                    contentPadding: EdgeInsets.zero,
                    title: Text(item['title'] as String? ?? '-'),
                    subtitle:
                        Text(item['submitted_at_label'] as String? ?? '-'),
                    trailing: _BadgeLabel(
                      label: (item['status'] as String?) ?? '-',
                      color: _izinStatusColor(item['status'] as String?),
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

class _ScheduleTile extends StatelessWidget {
  const _ScheduleTile({
    required this.item,
  });

  final Map<String, dynamic> item;

  @override
  Widget build(BuildContext context) {
    final status = item['attendance_status'] as String? ?? 'pending';

    return Container(
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
                  item['subject'] as String? ?? '-',
                  style: const TextStyle(
                    fontWeight: FontWeight.w800,
                    color: Color(0xFF1F4F4C),
                  ),
                ),
              ),
              _BadgeLabel(
                label: status == 'completed' ? 'Selesai' : 'Belum',
                color: status == 'completed'
                    ? const Color(0xFF2E8B57)
                    : const Color(0xFFF4A12A),
              ),
            ],
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
    );
  }
}

class _LoadingBlock extends StatelessWidget {
  const _LoadingBlock();

  @override
  Widget build(BuildContext context) {
    return const Padding(
      padding: EdgeInsets.only(top: 120),
      child: Center(child: CircularProgressIndicator()),
    );
  }
}

class _ErrorBlock extends StatelessWidget {
  const _ErrorBlock({
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

class _SectionTitle extends StatelessWidget {
  const _SectionTitle(this.title);

  final String title;

  @override
  Widget build(BuildContext context) {
    return Text(
      title,
      style: const TextStyle(
        fontSize: 16,
        fontWeight: FontWeight.w800,
        color: Color(0xFF1F4F4C),
      ),
    );
  }
}

class _InfoRow extends StatelessWidget {
  const _InfoRow({
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
                color: Color(0xFF6D7F7D),
                fontSize: 13,
              ),
            ),
          ),
          Expanded(
            child: Text(
              value,
              style: const TextStyle(
                color: Color(0xFF1F4F4C),
                fontWeight: FontWeight.w700,
                fontSize: 13,
              ),
            ),
          ),
        ],
      ),
    );
  }
}

class _BadgeLabel extends StatelessWidget {
  const _BadgeLabel({
    required this.label,
    required this.color,
  });

  final String label;
  final Color color;

  @override
  Widget build(BuildContext context) {
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

Color _statusColor(String value) {
  switch (value) {
    case 'hadir':
      return const Color(0xFF2E8B57);
    case 'izin':
      return const Color(0xFFF4A12A);
    case 'alpha':
      return const Color(0xFFB42318);
    default:
      return const Color(0xFF6D7F7D);
  }
}

Color _izinStatusColor(String? value) {
  switch (value) {
    case 'approved':
      return const Color(0xFF2E8B57);
    case 'rejected':
      return const Color(0xFFB42318);
    default:
      return const Color(0xFFF4A12A);
  }
}
