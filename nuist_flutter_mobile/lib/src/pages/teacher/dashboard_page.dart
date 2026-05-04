import 'package:flutter/material.dart';

import '../../services/teacher_mobile_repository.dart';
import '../../widgets/app/app_empty_state.dart';
import '../../widgets/app/app_section_card.dart';

class TeacherDashboardPage extends StatefulWidget {
  const TeacherDashboardPage({
    super.key,
    required this.repository,
    required this.onOpenIzin,
    required this.onSelectTab,
  });

  final TeacherMobileRepository repository;
  final Future<void> Function() onOpenIzin;
  final ValueChanged<int> onSelectTab;

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
            padding: const EdgeInsets.fromLTRB(16, 16, 16, 28),
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
                  onSelectTab: widget.onSelectTab,
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
    required this.onSelectTab,
  });

  final Map<String, dynamic> data;
  final Future<void> Function() onOpenIzin;
  final ValueChanged<int> onSelectTab;

  @override
  Widget build(BuildContext context) {
    final userCard = Map<String, dynamic>.from(
      (data['user_card'] as Map?) ?? const <String, dynamic>{},
    );
    final summary = Map<String, dynamic>.from(
      (data['summary'] as Map?) ?? const <String, dynamic>{},
    );
    final monthlyStats = Map<String, dynamic>.from(
      (data['monthly_stats'] as Map?) ?? const <String, dynamic>{},
    );
    final performance = Map<String, dynamic>.from(
      (data['performance'] as Map?) ?? const <String, dynamic>{},
    );
    final steps = ((performance['steps'] as List?) ?? const [])
        .whereType<Map>()
        .map((item) => Map<String, dynamic>.from(item))
        .toList();
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
        _DashboardHeroCard(
          greeting: (data['greeting'] as String?) ?? 'Selamat datang',
          todayLabel: (data['today_label'] as String?) ?? '-',
          userCard: userCard,
        ),
        const SizedBox(height: 18),
        _PerformanceCard(
          level: (performance['level'] as String?) ?? 'Belum Ada Progress',
          percent: (performance['percent'] as num?)?.toInt() ?? 0,
          steps: steps,
        ),
        const SizedBox(height: 18),
        const _SectionHeading(
          eyebrow: 'Aktivitas Presensi',
          title: 'Ringkasan Bulan Ini',
          subtitle: 'Pantau kehadiran, izin, dan ritme mengajar Anda.',
        ),
        const SizedBox(height: 12),
        GridView.count(
          physics: const NeverScrollableScrollPhysics(),
          crossAxisCount: 2,
          shrinkWrap: true,
          childAspectRatio: 1.38,
          crossAxisSpacing: 12,
          mainAxisSpacing: 12,
          children: [
            _MonthlyStatTile(
              label: 'Kehadiran',
              value: '${summary['attendance_percent'] ?? 0}%',
              caption: 'Persentase bulan ini',
              gradient: const [
                Color(0xFF0D8E89),
                Color(0xFF005E5A),
              ],
              icon: Icons.trending_up_rounded,
            ),
            _MonthlyStatTile(
              label: 'Presensi',
              value: '${monthlyStats['present_count'] ?? 0}',
              caption: 'Hadir tercatat',
              gradient: const [
                Color(0xFF1F9D73),
                Color(0xFF17634B),
              ],
              icon: Icons.check_circle_rounded,
            ),
            _MonthlyStatTile(
              label: 'Izin',
              value: '${monthlyStats['izin_count'] ?? 0}',
              caption: 'Termasuk sakit/cuti',
              gradient: const [
                Color(0xFFF4B860),
                Color(0xFFE28B2D),
              ],
              icon: Icons.schedule_rounded,
            ),
            _MonthlyStatTile(
              label: 'Alpha',
              value: '${monthlyStats['alpha_count'] ?? 0}',
              caption: 'Tidak hadir',
              gradient: const [
                Color(0xFFEE6B5F),
                Color(0xFFB83A36),
              ],
              icon: Icons.cancel_rounded,
            ),
          ],
        ),
        const SizedBox(height: 18),
        const _SectionHeading(
          eyebrow: 'Layanan',
          title: 'Akses Cepat',
          subtitle: 'Pintasan utama untuk alur kerja harian tenaga pendidik.',
        ),
        const SizedBox(height: 12),
        AppSectionCard(
          padding: const EdgeInsets.all(14),
          child: GridView.count(
            physics: const NeverScrollableScrollPhysics(),
            crossAxisCount: 4,
            shrinkWrap: true,
            childAspectRatio: 0.82,
            crossAxisSpacing: 12,
            mainAxisSpacing: 14,
            children: [
              _ServiceShortcutTile(
                label: 'Presensi',
                colors: const [Color(0xFF0D8E89), Color(0xFF004B48)],
                icon: Icons.fact_check_rounded,
                onTap: () => onSelectTab(2),
              ),
              _ServiceShortcutTile(
                label: 'Mengajar',
                colors: const [Color(0xFFF4B860), Color(0xFFE5952D)],
                icon: Icons.cast_for_education_rounded,
                onTap: () => onSelectTab(3),
              ),
              _ServiceShortcutTile(
                label: 'Jadwal',
                colors: const [Color(0xFF74B3FF), Color(0xFF3C6FD1)],
                icon: Icons.calendar_month_rounded,
                onTap: () => onSelectTab(1),
              ),
              _ServiceShortcutTile(
                label: 'Profil',
                colors: const [Color(0xFF8E7DF2), Color(0xFF5B49B6)],
                icon: Icons.person_rounded,
                onTap: () => onSelectTab(4),
              ),
              _ServiceShortcutTile(
                label: 'Daftar Izin',
                colors: const [Color(0xFFFF8D6C), Color(0xFFD95C3D)],
                icon: Icons.assignment_turned_in_rounded,
                onTap: () {
                  onOpenIzin();
                },
              ),
              _ServiceShortcutTile(
                label: 'Presensi Hari Ini',
                colors: const [Color(0xFF7BC7B2), Color(0xFF2C8B76)],
                icon: _attendanceStatusIcon(
                  todayAttendance['status'] as String? ?? 'belum_presensi',
                ),
                onTap: () => onSelectTab(2),
              ),
              _ServiceShortcutTile(
                label: 'Izin Pending',
                colors: const [Color(0xFFFFC95C), Color(0xFFF0A019)],
                icon: Icons.pending_actions_rounded,
                badgeText: '${summary['pending_izin_count'] ?? 0}',
                onTap: () {
                  onOpenIzin();
                },
              ),
              _ServiceShortcutTile(
                label: 'Jadwal Hari Ini',
                colors: const [Color(0xFF57C1E8), Color(0xFF2D7DA8)],
                icon: Icons.today_rounded,
                badgeText: '${summary['teaching_today_count'] ?? 0}',
                onTap: () => onSelectTab(1),
              ),
            ],
          ),
        ),
        const SizedBox(height: 18),
        const _SectionHeading(
          eyebrow: 'Hari Ini',
          title: 'Presensi dan Jadwal',
          subtitle: 'Lihat posisi aktivitas harian Anda secara cepat.',
        ),
        const SizedBox(height: 12),
        AppSectionCard(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                children: [
                  Expanded(
                    child: Text(
                      todayAttendance['status_label'] as String? ??
                          'Belum presensi',
                      style: const TextStyle(
                        fontSize: 20,
                        fontWeight: FontWeight.w800,
                        color: Color(0xFF1F4F4C),
                      ),
                    ),
                  ),
                  _StatusChip(
                    label: (todayAttendance['status'] as String? ?? 'pending')
                        .replaceAll('_', ' '),
                    color: _statusColor(
                      (todayAttendance['status'] as String?) ??
                          'belum_presensi',
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 14),
              Row(
                children: [
                  Expanded(
                    child: _MiniInfoCard(
                      label: 'Masuk',
                      value: (todayAttendance['check_in'] as String?) ?? '-',
                      icon: Icons.login_rounded,
                    ),
                  ),
                  const SizedBox(width: 10),
                  Expanded(
                    child: _MiniInfoCard(
                      label: 'Keluar',
                      value: (todayAttendance['check_out'] as String?) ?? '-',
                      icon: Icons.logout_rounded,
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 10),
              Container(
                width: double.infinity,
                padding: const EdgeInsets.all(14),
                decoration: BoxDecoration(
                  color: const Color(0xFFF5FAF9),
                  borderRadius: BorderRadius.circular(18),
                  border: Border.all(color: const Color(0xFFDDEBE9)),
                ),
                child: Row(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Icon(
                      Icons.location_on_rounded,
                      color: Color(0xFF0D8E89),
                    ),
                    const SizedBox(width: 8),
                    Expanded(
                      child: Text(
                        (todayAttendance['location'] as String?) ?? '-',
                        style: const TextStyle(
                          color: Color(0xFF1F4F4C),
                          fontWeight: FontWeight.w600,
                        ),
                      ),
                    ),
                  ],
                ),
              ),
            ],
          ),
        ),
        const SizedBox(height: 16),
        AppSectionCard(
          padding: const EdgeInsets.fromLTRB(0, 16, 0, 16),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const Padding(
                padding: EdgeInsets.symmetric(horizontal: 18),
                child: _SectionTitle('Jadwal Mengajar Hari Ini'),
              ),
              const SizedBox(height: 12),
              if (schedules.isEmpty)
                const Padding(
                  padding: EdgeInsets.symmetric(horizontal: 18),
                  child: AppEmptyState(
                    title: 'Tidak ada jadwal hari ini',
                    message: 'Belum ada jadwal mengajar untuk hari ini.',
                    icon: Icons.event_busy_outlined,
                  ),
                )
              else
                SizedBox(
                  height: 172,
                  child: ListView.separated(
                    padding: const EdgeInsets.symmetric(horizontal: 18),
                    scrollDirection: Axis.horizontal,
                    itemBuilder: (context, index) {
                      return _ScheduleShowcaseCard(item: schedules[index]);
                    },
                    separatorBuilder: (_, __) => const SizedBox(width: 12),
                    itemCount: schedules.length,
                  ),
                ),
            ],
          ),
        ),
        const SizedBox(height: 16),
        AppSectionCard(
          child: Theme(
            data: Theme.of(context).copyWith(dividerColor: Colors.transparent),
            child: ExpansionTile(
              tilePadding: EdgeInsets.zero,
              childrenPadding: EdgeInsets.zero,
              title: const _SectionTitle('Detail Aktivitas Hari Ini'),
              subtitle: const Text(
                'Presensi masuk, mengajar, dan presensi keluar.',
                style: TextStyle(
                  color: Color(0xFF6D7F7D),
                  fontSize: 12,
                ),
              ),
              children: [
                const SizedBox(height: 4),
                ...steps.map(
                  (step) => Padding(
                    padding: const EdgeInsets.only(bottom: 10),
                    child: _ActivityStepTile(step: step),
                  ),
                ),
              ],
            ),
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
                  (item) => Container(
                    margin: const EdgeInsets.only(bottom: 10),
                    padding: const EdgeInsets.all(14),
                    decoration: BoxDecoration(
                      color: const Color(0xFFF7FBFB),
                      borderRadius: BorderRadius.circular(18),
                      border: Border.all(color: const Color(0xFFDDEBE9)),
                    ),
                    child: Row(
                      children: [
                        Container(
                          width: 42,
                          height: 42,
                          decoration: BoxDecoration(
                            color: _izinStatusColor(
                              item['status'] as String?,
                            ).withOpacity(0.14),
                            borderRadius: BorderRadius.circular(14),
                          ),
                          child: Icon(
                            Icons.assignment_turned_in_rounded,
                            color: _izinStatusColor(item['status'] as String?),
                          ),
                        ),
                        const SizedBox(width: 12),
                        Expanded(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(
                                item['title'] as String? ?? '-',
                                style: const TextStyle(
                                  fontWeight: FontWeight.w800,
                                  color: Color(0xFF1F4F4C),
                                ),
                              ),
                              const SizedBox(height: 4),
                              Text(
                                item['submitted_at_label'] as String? ?? '-',
                                style: const TextStyle(
                                  color: Color(0xFF6D7F7D),
                                  fontSize: 12,
                                ),
                              ),
                            ],
                          ),
                        ),
                        const SizedBox(width: 8),
                        _StatusChip(
                          label: (item['status'] as String?) ?? '-',
                          color: _izinStatusColor(item['status'] as String?),
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

class _DashboardHeroCard extends StatelessWidget {
  const _DashboardHeroCard({
    required this.greeting,
    required this.todayLabel,
    required this.userCard,
  });

  final String greeting;
  final String todayLabel;
  final Map<String, dynamic> userCard;

  @override
  Widget build(BuildContext context) {
    final avatarUrl = userCard['avatar_url'] as String?;

    return Container(
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(30),
        gradient: const LinearGradient(
          colors: [
            Color(0xFFF6C36F),
            Color(0xFFECA13A),
            Color(0xFF0D8E89),
            Color(0xFF004B48),
          ],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
          stops: [0, 0.24, 0.72, 1],
        ),
        boxShadow: const [
          BoxShadow(
            color: Color(0x1C003B39),
            blurRadius: 28,
            offset: Offset(0, 14),
          ),
        ],
      ),
      child: Stack(
        children: [
          Positioned(
            top: -18,
            right: -8,
            child: Container(
              width: 110,
              height: 110,
              decoration: BoxDecoration(
                color: Colors.white.withOpacity(0.12),
                shape: BoxShape.circle,
              ),
            ),
          ),
          Positioned(
            bottom: -36,
            left: -24,
            child: Container(
              width: 150,
              height: 150,
              decoration: BoxDecoration(
                color: Colors.black.withOpacity(0.08),
                shape: BoxShape.circle,
              ),
            ),
          ),
          Padding(
            padding: const EdgeInsets.all(18),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    CircleAvatar(
                      radius: 28,
                      backgroundColor: Colors.white.withOpacity(0.24),
                      backgroundImage:
                          avatarUrl != null ? NetworkImage(avatarUrl) : null,
                      child: avatarUrl == null
                          ? const Icon(
                              Icons.person_rounded,
                              size: 28,
                              color: Colors.white,
                            )
                          : null,
                    ),
                    const SizedBox(width: 14),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            greeting,
                            style: const TextStyle(
                              color: Colors.white,
                              fontSize: 13,
                              fontWeight: FontWeight.w600,
                            ),
                          ),
                          const SizedBox(height: 4),
                          Text(
                            userCard['name'] as String? ?? '-',
                            style: const TextStyle(
                              color: Colors.white,
                              fontSize: 24,
                              fontWeight: FontWeight.w800,
                              height: 1.1,
                            ),
                          ),
                          const SizedBox(height: 6),
                          Text(
                            userCard['school_name'] as String? ?? '-',
                            style: TextStyle(
                              color: Colors.white.withOpacity(0.88),
                              fontSize: 13,
                              fontWeight: FontWeight.w600,
                            ),
                          ),
                        ],
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 18),
                Wrap(
                  spacing: 8,
                  runSpacing: 8,
                  children: [
                    _HeroChip(
                      icon: Icons.badge_rounded,
                      label: 'NUIST ID ${userCard['nuist_id'] ?? '-'}',
                    ),
                    _HeroChip(
                      icon: Icons.verified_user_rounded,
                      label: userCard['status_kepegawaian'] as String? ?? '-',
                    ),
                    _HeroChip(
                      icon: Icons.school_rounded,
                      label: userCard['ketugasan'] as String? ?? '-',
                    ),
                  ],
                ),
                const SizedBox(height: 16),
                Text(
                  todayLabel,
                  style: TextStyle(
                    color: Colors.white.withOpacity(0.86),
                    fontSize: 12,
                    fontWeight: FontWeight.w600,
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}

class _HeroChip extends StatelessWidget {
  const _HeroChip({
    required this.icon,
    required this.label,
  });

  final IconData icon;
  final String label;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 8),
      decoration: BoxDecoration(
        color: Colors.white.withOpacity(0.16),
        borderRadius: BorderRadius.circular(999),
        border: Border.all(color: Colors.white.withOpacity(0.12)),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(icon, size: 15, color: Colors.white),
          const SizedBox(width: 6),
          Text(
            label,
            style: const TextStyle(
              color: Colors.white,
              fontSize: 11,
              fontWeight: FontWeight.w700,
            ),
          ),
        ],
      ),
    );
  }
}

class _PerformanceCard extends StatelessWidget {
  const _PerformanceCard({
    required this.level,
    required this.percent,
    required this.steps,
  });

  final String level;
  final int percent;
  final List<Map<String, dynamic>> steps;

  @override
  Widget build(BuildContext context) {
    final progress = (percent.clamp(0, 100)) / 100;

    return Container(
      padding: const EdgeInsets.all(18),
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(26),
        gradient: const LinearGradient(
          colors: [
            Color(0xFF004B48),
            Color(0xFF0D8E89),
          ],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
        boxShadow: const [
          BoxShadow(
            color: Color(0x18004B48),
            blurRadius: 24,
            offset: Offset(0, 12),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Container(
                padding:
                    const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
                decoration: BoxDecoration(
                  color: Colors.white.withOpacity(0.14),
                  borderRadius: BorderRadius.circular(999),
                ),
                child: const Text(
                  'LEVEL HARI INI',
                  style: TextStyle(
                    color: Colors.white,
                    fontSize: 10,
                    fontWeight: FontWeight.w800,
                    letterSpacing: 0.4,
                  ),
                ),
              ),
              const Spacer(),
              Text(
                '$percent%',
                style: const TextStyle(
                  color: Colors.white,
                  fontSize: 26,
                  fontWeight: FontWeight.w800,
                ),
              ),
            ],
          ),
          const SizedBox(height: 12),
          Text(
            level,
            style: const TextStyle(
              color: Colors.white,
              fontSize: 20,
              fontWeight: FontWeight.w800,
            ),
          ),
          const SizedBox(height: 6),
          Text(
            'Ringkasan progress aktivitas presensi dan mengajar hari ini.',
            style: TextStyle(
              color: Colors.white.withOpacity(0.84),
              fontSize: 12,
              fontWeight: FontWeight.w500,
            ),
          ),
          const SizedBox(height: 16),
          ClipRRect(
            borderRadius: BorderRadius.circular(999),
            child: LinearProgressIndicator(
              minHeight: 8,
              value: progress.toDouble(),
              backgroundColor: Colors.white.withOpacity(0.16),
              valueColor: const AlwaysStoppedAnimation<Color>(
                Color(0xFFF4C36F),
              ),
            ),
          ),
          const SizedBox(height: 16),
          Wrap(
            spacing: 10,
            runSpacing: 10,
            children:
                steps.map((step) => _PerformanceStepChip(step: step)).toList(),
          ),
        ],
      ),
    );
  }
}

class _PerformanceStepChip extends StatelessWidget {
  const _PerformanceStepChip({
    required this.step,
  });

  final Map<String, dynamic> step;

  @override
  Widget build(BuildContext context) {
    final isDone = (step['status'] as String?) == 'completed';

    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
      decoration: BoxDecoration(
        color:
            isDone ? const Color(0x26FFFFFF) : Colors.white.withOpacity(0.12),
        borderRadius: BorderRadius.circular(18),
        border: Border.all(
          color: Colors.white.withOpacity(isDone ? 0.22 : 0.12),
        ),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(
            _stepIcon(step['icon'] as String?),
            size: 16,
            color: isDone ? const Color(0xFFF4C36F) : Colors.white,
          ),
          const SizedBox(width: 8),
          Text(
            step['label'] as String? ?? '-',
            style: const TextStyle(
              color: Colors.white,
              fontSize: 12,
              fontWeight: FontWeight.w700,
            ),
          ),
        ],
      ),
    );
  }
}

class _SectionHeading extends StatelessWidget {
  const _SectionHeading({
    required this.eyebrow,
    required this.title,
    required this.subtitle,
  });

  final String eyebrow;
  final String title;
  final String subtitle;

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          eyebrow,
          style: const TextStyle(
            fontSize: 11,
            fontWeight: FontWeight.w800,
            color: Color(0xFF0D8E89),
            letterSpacing: 0.4,
          ),
        ),
        const SizedBox(height: 4),
        Text(
          title,
          style: const TextStyle(
            fontSize: 22,
            fontWeight: FontWeight.w800,
            color: Color(0xFF1F4F4C),
            height: 1.1,
          ),
        ),
        const SizedBox(height: 6),
        Text(
          subtitle,
          style: const TextStyle(
            fontSize: 13,
            color: Color(0xFF6D7F7D),
            height: 1.35,
          ),
        ),
      ],
    );
  }
}

class _MonthlyStatTile extends StatelessWidget {
  const _MonthlyStatTile({
    required this.label,
    required this.value,
    required this.caption,
    required this.gradient,
    required this.icon,
  });

  final String label;
  final String value;
  final String caption;
  final List<Color> gradient;
  final IconData icon;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(24),
        gradient: LinearGradient(
          colors: gradient,
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
        boxShadow: const [
          BoxShadow(
            color: Color(0x15003B39),
            blurRadius: 20,
            offset: Offset(0, 8),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Icon(icon, color: Colors.white, size: 24),
          const Spacer(),
          Text(
            value,
            style: const TextStyle(
              color: Colors.white,
              fontSize: 24,
              fontWeight: FontWeight.w800,
            ),
          ),
          const SizedBox(height: 4),
          Text(
            label,
            style: const TextStyle(
              color: Colors.white,
              fontSize: 13,
              fontWeight: FontWeight.w700,
            ),
          ),
          const SizedBox(height: 2),
          Text(
            caption,
            style: TextStyle(
              color: Colors.white.withOpacity(0.86),
              fontSize: 11,
            ),
          ),
        ],
      ),
    );
  }
}

class _ServiceShortcutTile extends StatelessWidget {
  const _ServiceShortcutTile({
    required this.label,
    required this.colors,
    required this.icon,
    required this.onTap,
    this.badgeText,
  });

  final String label;
  final List<Color> colors;
  final IconData icon;
  final VoidCallback onTap;
  final String? badgeText;

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Expanded(
            child: Container(
              decoration: BoxDecoration(
                borderRadius: BorderRadius.circular(22),
                gradient: LinearGradient(
                  colors: colors,
                  begin: Alignment.topLeft,
                  end: Alignment.bottomRight,
                ),
                boxShadow: const [
                  BoxShadow(
                    color: Color(0x14003B39),
                    blurRadius: 16,
                    offset: Offset(0, 8),
                  ),
                ],
              ),
              child: Stack(
                children: [
                  Positioned(
                    top: -10,
                    right: -8,
                    child: Container(
                      width: 44,
                      height: 44,
                      decoration: BoxDecoration(
                        color: Colors.white.withOpacity(0.14),
                        shape: BoxShape.circle,
                      ),
                    ),
                  ),
                  Center(
                    child: Icon(
                      icon,
                      size: 28,
                      color: Colors.white,
                    ),
                  ),
                  if (badgeText != null)
                    Positioned(
                      top: 8,
                      right: 8,
                      child: Container(
                        padding: const EdgeInsets.symmetric(
                          horizontal: 6,
                          vertical: 3,
                        ),
                        decoration: BoxDecoration(
                          color: Colors.white,
                          borderRadius: BorderRadius.circular(999),
                        ),
                        child: Text(
                          badgeText!,
                          style: const TextStyle(
                            color: Color(0xFF1F4F4C),
                            fontSize: 10,
                            fontWeight: FontWeight.w800,
                          ),
                        ),
                      ),
                    ),
                ],
              ),
            ),
          ),
          const SizedBox(height: 8),
          Text(
            label,
            textAlign: TextAlign.center,
            style: const TextStyle(
              fontSize: 11,
              fontWeight: FontWeight.w700,
              color: Color(0xFF214845),
            ),
          ),
        ],
      ),
    );
  }
}

class _MiniInfoCard extends StatelessWidget {
  const _MiniInfoCard({
    required this.label,
    required this.value,
    required this.icon,
  });

  final String label;
  final String value;
  final IconData icon;

  @override
  Widget build(BuildContext context) {
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
          Icon(icon, color: const Color(0xFF0D8E89), size: 20),
          const SizedBox(height: 10),
          Text(
            value,
            style: const TextStyle(
              fontSize: 18,
              fontWeight: FontWeight.w800,
              color: Color(0xFF1F4F4C),
            ),
          ),
          const SizedBox(height: 3),
          Text(
            label,
            style: const TextStyle(
              fontSize: 12,
              color: Color(0xFF6D7F7D),
              fontWeight: FontWeight.w600,
            ),
          ),
        ],
      ),
    );
  }
}

class _ScheduleShowcaseCard extends StatelessWidget {
  const _ScheduleShowcaseCard({
    required this.item,
  });

  final Map<String, dynamic> item;

  @override
  Widget build(BuildContext context) {
    final status = item['attendance_status'] as String? ?? 'pending';
    final isCompleted = status == 'completed';

    return Container(
      width: 238,
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(24),
        gradient: LinearGradient(
          colors: isCompleted
              ? const [Color(0xFF1F9D73), Color(0xFF17634B)]
              : const [Color(0xFFF4B860), Color(0xFFE5952D)],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Expanded(
                child: Text(
                  item['subject'] as String? ?? '-',
                  maxLines: 2,
                  overflow: TextOverflow.ellipsis,
                  style: const TextStyle(
                    color: Colors.white,
                    fontSize: 17,
                    fontWeight: FontWeight.w800,
                    height: 1.1,
                  ),
                ),
              ),
              const SizedBox(width: 8),
              Container(
                width: 12,
                height: 12,
                decoration: BoxDecoration(
                  color: isCompleted
                      ? const Color(0xFFB7FFD0)
                      : const Color(0xFFFFF1BE),
                  shape: BoxShape.circle,
                  border: Border.all(color: Colors.white, width: 2),
                ),
              ),
            ],
          ),
          const Spacer(),
          Text(
            item['class_name'] as String? ?? '-',
            style: TextStyle(
              color: Colors.white.withOpacity(0.92),
              fontSize: 13,
              fontWeight: FontWeight.w700,
            ),
          ),
          const SizedBox(height: 4),
          Text(
            '${item['start_time'] ?? '-'} - ${item['end_time'] ?? '-'}',
            style: TextStyle(
              color: Colors.white.withOpacity(0.86),
              fontSize: 12,
              fontWeight: FontWeight.w600,
            ),
          ),
          if ((item['school_name'] as String?) != null) ...[
            const SizedBox(height: 6),
            Text(
              item['school_name'] as String,
              maxLines: 1,
              overflow: TextOverflow.ellipsis,
              style: TextStyle(
                color: Colors.white.withOpacity(0.76),
                fontSize: 11,
              ),
            ),
          ],
        ],
      ),
    );
  }
}

class _ActivityStepTile extends StatelessWidget {
  const _ActivityStepTile({
    required this.step,
  });

  final Map<String, dynamic> step;

  @override
  Widget build(BuildContext context) {
    final isDone = (step['status'] as String?) == 'completed';

    return Container(
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: isDone ? const Color(0xFFE8F8EE) : const Color(0xFFF7FBFB),
        borderRadius: BorderRadius.circular(18),
        border: Border.all(
          color: isDone ? const Color(0xFFBFE8CB) : const Color(0xFFDDEBE9),
        ),
      ),
      child: Row(
        children: [
          Container(
            width: 40,
            height: 40,
            decoration: BoxDecoration(
              color: isDone ? const Color(0xFF2E8B57) : const Color(0xFFCFDEDC),
              borderRadius: BorderRadius.circular(14),
            ),
            child: Icon(
              _stepIcon(step['icon'] as String?),
              color: isDone ? Colors.white : const Color(0xFF52726E),
              size: 20,
            ),
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  step['label'] as String? ?? '-',
                  style: TextStyle(
                    fontWeight: FontWeight.w800,
                    color: isDone
                        ? const Color(0xFF1D6B40)
                        : const Color(0xFF1F4F4C),
                  ),
                ),
                const SizedBox(height: 4),
                Text(
                  isDone ? 'Sudah dilakukan' : 'Belum dilakukan',
                  style: TextStyle(
                    fontSize: 12,
                    color: isDone
                        ? const Color(0xFF1D6B40)
                        : const Color(0xFF6D7F7D),
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}

class _LoadingBlock extends StatelessWidget {
  const _LoadingBlock();

  @override
  Widget build(BuildContext context) {
    return const Column(
      children: [
        _SkeletonCard(height: 210),
        SizedBox(height: 16),
        _SkeletonCard(height: 180),
        SizedBox(height: 16),
        _SkeletonCard(height: 220),
      ],
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
          const Icon(
            Icons.error_outline_rounded,
            color: Color(0xFF9F1239),
            size: 34,
          ),
          const SizedBox(height: 10),
          Text(
            message,
            textAlign: TextAlign.center,
            style: const TextStyle(
              color: Color(0xFF9F1239),
              fontWeight: FontWeight.w700,
            ),
          ),
          const SizedBox(height: 14),
          OutlinedButton(
            onPressed: onRetry,
            child: const Text('Coba Lagi'),
          ),
        ],
      ),
    );
  }
}

class _SkeletonCard extends StatelessWidget {
  const _SkeletonCard({
    required this.height,
  });

  final double height;

  @override
  Widget build(BuildContext context) {
    return Container(
      height: height,
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(28),
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

class _StatusChip extends StatelessWidget {
  const _StatusChip({
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
        color: color.withOpacity(0.12),
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

IconData _stepIcon(String? icon) {
  switch (icon) {
    case 'login':
      return Icons.login_rounded;
    case 'logout':
      return Icons.logout_rounded;
    case 'school':
      return Icons.cast_for_education_rounded;
    default:
      return Icons.check_circle_outline_rounded;
  }
}

IconData _attendanceStatusIcon(String value) {
  switch (value) {
    case 'hadir':
      return Icons.verified_rounded;
    case 'izin':
      return Icons.schedule_rounded;
    case 'alpha':
      return Icons.cancel_rounded;
    default:
      return Icons.fact_check_rounded;
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
