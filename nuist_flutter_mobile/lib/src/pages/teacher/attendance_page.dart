import 'package:flutter/material.dart';

import '../../services/teacher_mobile_repository.dart';
import '../../widgets/app/app_empty_state.dart';
import '../../widgets/app/app_section_card.dart';
import '../../widgets/app/teacher_page_header.dart';

class TeacherAttendancePage extends StatefulWidget {
  const TeacherAttendancePage({
    super.key,
    required this.repository,
    required this.onBackToHome,
  });

  final TeacherMobileRepository repository;
  final VoidCallback onBackToHome;

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
                const _PageLoading()
              else if (snapshot.hasError)
                _PageError(
                  message: snapshot.error.toString(),
                  onRetry: _refresh,
                )
              else
                _AttendanceContent(
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

class _AttendanceContent extends StatelessWidget {
  const _AttendanceContent({
    required this.data,
    required this.onBackToHome,
  });

  final Map<String, dynamic> data;
  final VoidCallback onBackToHome;

  @override
  Widget build(BuildContext context) {
    final today = Map<String, dynamic>.from(
      (data['today_attendance'] as Map?) ?? const <String, dynamic>{},
    );
    final recent = ((data['recent'] as List?) ?? const [])
        .whereType<Map>()
        .map((item) => Map<String, dynamic>.from(item))
        .toList();
    final hadirCount = recent
        .where(
          (item) => (item['status'] as String?)?.toLowerCase() == 'hadir',
        )
        .length;
    final izinCount = recent
        .where(
          (item) => (item['status'] as String?)?.toLowerCase() == 'izin',
        )
        .length;

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        TeacherPageHeader(
          title: 'Presensi',
          onBack: onBackToHome,
        ),
        const SizedBox(height: 18),
        const _PageSectionHeading(
          eyebrow: 'Kehadiran',
          title: 'Presensi Hari Ini',
        ),
        const SizedBox(height: 12),
        _AttendanceHeroCard(
          dateLabel: data['today_label'] as String? ?? '-',
          statusLabel: today['status_label'] as String? ?? 'Belum presensi',
          status: today['status'] as String? ?? 'belum_presensi',
        ),
        const SizedBox(height: 18),
        Row(
          children: [
            Expanded(
              child: _MiniAttendanceCard(
                icon: Icons.login_rounded,
                label: 'Masuk',
                value: today['check_in'] as String? ?? '-',
                accent: const Color(0xFFF49637),
              ),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: _MiniAttendanceCard(
                icon: Icons.logout_rounded,
                label: 'Keluar',
                value: today['check_out'] as String? ?? '-',
                accent: const Color(0xFFF49637),
              ),
            ),
          ],
        ),
        const SizedBox(height: 12),
        AppSectionCard(
          child: Row(
            children: [
              Expanded(
                child: _CompactPresenceMetric(
                  label: 'Riwayat Hadir',
                  value: '$hadirCount',
                  color: const Color(0xFFF49637),
                ),
              ),
              Container(
                width: 1,
                height: 36,
                color: const Color(0xFFE2ECE5),
              ),
              Expanded(
                child: _CompactPresenceMetric(
                  label: 'Riwayat Izin',
                  value: '$izinCount',
                  color: const Color(0xFFF49637),
                ),
              ),
            ],
          ),
        ),
        const SizedBox(height: 12),
        AppSectionCard(
          child: Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Container(
                width: 36,
                height: 36,
                decoration: BoxDecoration(
                  color: const Color(0xFFFFF4E8),
                  borderRadius: BorderRadius.circular(14),
                ),
                child: const Icon(
                  Icons.location_on_outlined,
                  color: Color(0xFFF49637),
                  size: 20,
                ),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text(
                      'Lokasi Presensi Hari Ini',
                      style: TextStyle(
                        color: Color(0xFF7A4212),
                        fontWeight: FontWeight.w800,
                        fontSize: 14,
                      ),
                    ),
                    const SizedBox(height: 4),
                    Text(
                      today['location'] as String? ?? '-',
                      style: const TextStyle(
                        color: Color(0xFF6D7F7D),
                        fontSize: 12,
                        height: 1.35,
                      ),
                    ),
                  ],
                ),
              ),
            ],
          ),
        ),
        const SizedBox(height: 18),
        const _PageSectionHeading(
          eyebrow: 'Riwayat',
          title: 'Riwayat Presensi',
        ),
        const SizedBox(height: 12),
        if (recent.isEmpty)
          const AppSectionCard(
            child: AppEmptyState(
              title: 'Belum ada riwayat presensi',
              message: 'Riwayat presensi akan tampil di sini.',
              icon: Icons.history_toggle_off_rounded,
            ),
          )
        else
          ...recent.map(
            (item) => Padding(
              padding: const EdgeInsets.only(bottom: 12),
              child: _AttendanceHistoryTile(item: item),
            ),
          ),
      ],
    );
  }
}

class _AttendanceHeroCard extends StatelessWidget {
  const _AttendanceHeroCard({
    required this.dateLabel,
    required this.statusLabel,
    required this.status,
  });

  final String dateLabel;
  final String statusLabel;
  final String status;

  @override
  Widget build(BuildContext context) {
    final color = _statusColor(status);

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
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            dateLabel,
            style: TextStyle(
              color: Colors.white.withOpacity(0.78),
              fontSize: 12,
              fontWeight: FontWeight.w700,
            ),
          ),
          const SizedBox(height: 10),
          Row(
            children: [
              Expanded(
                child: Text(
                  statusLabel,
                  style: const TextStyle(
                    color: Colors.white,
                    fontSize: 24,
                    fontWeight: FontWeight.w800,
                    height: 1.05,
                  ),
                ),
              ),
              const SizedBox(width: 12),
              Container(
                padding:
                    const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
                decoration: BoxDecoration(
                  color: Colors.white.withOpacity(0.16),
                  borderRadius: BorderRadius.circular(999),
                ),
                child: Text(
                  _statusText(status),
                  style: TextStyle(
                    color: color == const Color(0xFFB42318)
                        ? Colors.white
                        : color,
                    fontSize: 12,
                    fontWeight: FontWeight.w800,
                  ),
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }
}

class _MiniAttendanceCard extends StatelessWidget {
  const _MiniAttendanceCard({
    required this.icon,
    required this.label,
    required this.value,
    required this.accent,
  });

  final IconData icon;
  final String label;
  final String value;
  final Color accent;

  @override
  Widget build(BuildContext context) {
    return AppSectionCard(
      padding: const EdgeInsets.all(14),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            width: 36,
            height: 36,
            decoration: BoxDecoration(
              color: const Color(0xFFFFF4E8),
              borderRadius: BorderRadius.circular(14),
            ),
            child: Icon(
              icon,
              color: accent,
              size: 18,
            ),
          ),
          const SizedBox(height: 14),
          Text(
            value,
            style: const TextStyle(
              color: Color(0xFF7A4212),
              fontSize: 20,
              fontWeight: FontWeight.w800,
            ),
          ),
          const SizedBox(height: 4),
          Text(
            label,
            style: const TextStyle(
              color: Color(0xFF7A8F8C),
              fontWeight: FontWeight.w700,
              fontSize: 12,
            ),
          ),
        ],
      ),
    );
  }
}

class _CompactPresenceMetric extends StatelessWidget {
  const _CompactPresenceMetric({
    required this.label,
    required this.value,
    required this.color,
  });

  final String label;
  final String value;
  final Color color;

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Text(
          value,
          style: TextStyle(
            color: color,
            fontSize: 22,
            fontWeight: FontWeight.w800,
          ),
        ),
        const SizedBox(height: 4),
        Text(
          label,
          textAlign: TextAlign.center,
          style: const TextStyle(
            color: Color(0xFF7A8F8C),
            fontSize: 12,
            fontWeight: FontWeight.w700,
          ),
        ),
      ],
    );
  }
}

class _AttendanceHistoryTile extends StatelessWidget {
  const _AttendanceHistoryTile({
    required this.item,
  });

  final Map<String, dynamic> item;

  @override
  Widget build(BuildContext context) {
    final status = (item['status'] as String? ?? '-').toLowerCase();
    final statusColor = _statusColor(status);

    return AppSectionCard(
      padding: const EdgeInsets.all(14),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            width: 42,
            height: 42,
            decoration: BoxDecoration(
              color: statusColor.withOpacity(0.1),
              borderRadius: BorderRadius.circular(16),
            ),
            child: Icon(
              status == 'hadir'
                  ? Icons.verified_rounded
                  : status == 'izin'
                      ? Icons.schedule_rounded
                      : Icons.cancel_rounded,
              color: statusColor,
              size: 20,
            ),
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  children: [
                    Expanded(
                      child: Text(
                        item['date_label'] as String? ?? '-',
                        style: const TextStyle(
                          color: Color(0xFF7A4212),
                          fontWeight: FontWeight.w800,
                          fontSize: 14,
                        ),
                      ),
                    ),
                    _StatusPill(label: item['status'] as String? ?? '-'),
                  ],
                ),
                const SizedBox(height: 6),
                Text(
                  '${item['check_in'] ?? '-'} • ${item['check_out'] ?? '-'}',
                  style: const TextStyle(
                    color: Color(0xFF4D6663),
                    fontWeight: FontWeight.w700,
                    fontSize: 12,
                  ),
                ),
                if ((item['location'] as String?)?.trim().isNotEmpty ==
                    true) ...[
                  const SizedBox(height: 5),
                  Text(
                    item['location'] as String,
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                    style: const TextStyle(
                      color: Color(0xFF8A9B99),
                      fontSize: 12,
                    ),
                  ),
                ],
              ],
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
    final color = _statusColor(label.toLowerCase());

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
          fontSize: 11,
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

Color _statusColor(String status) {
  switch (status) {
    case 'hadir':
      return const Color(0xFFF49637);
    case 'izin':
      return const Color(0xFFF49637);
    case 'alpha':
      return const Color(0xFFB42318);
    default:
      return const Color(0xFF7A8F8C);
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

String _statusText(String status) {
  switch (status) {
    case 'hadir':
      return 'Hadir';
    case 'izin':
      return 'Izin';
    case 'alpha':
      return 'Alpha';
    default:
      return 'Pending';
  }
}
