import 'dart:async';

import 'package:flutter/material.dart';

import '../../services/teacher_mobile_repository.dart';
import '../../widgets/app/app_empty_state.dart';
import '../../widgets/app/app_section_card.dart';

class TeacherDashboardPage extends StatefulWidget {
  const TeacherDashboardPage({
    super.key,
    required this.repository,
    required this.isActive,
    required this.onOpenIzin,
    required this.onOpenManageIzin,
    required this.onOpenReports,
    required this.onOpenStaffAttendance,
    required this.onSelectTab,
    required this.onOpenProfile,
    required this.onOpenSettings,
    required this.onOpenNotifications,
    required this.onLogout,
  });

  final TeacherMobileRepository repository;
  final bool isActive;
  final Future<void> Function() onOpenIzin;
  final Future<void> Function() onOpenManageIzin;
  final Future<void> Function() onOpenReports;
  final Future<void> Function() onOpenStaffAttendance;
  final ValueChanged<int> onSelectTab;
  final VoidCallback onOpenProfile;
  final VoidCallback onOpenSettings;
  final VoidCallback onOpenNotifications;
  final Future<void> Function() onLogout;

  @override
  State<TeacherDashboardPage> createState() => _TeacherDashboardPageState();
}

class _TeacherDashboardPageState extends State<TeacherDashboardPage>
    with WidgetsBindingObserver {
  late Future<Map<String, dynamic>> _future;

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addObserver(this);
    _future = widget.repository.getDashboard();
  }

  @override
  void didUpdateWidget(covariant TeacherDashboardPage oldWidget) {
    super.didUpdateWidget(oldWidget);
    if (!oldWidget.isActive && widget.isActive) {
      unawaited(_refresh());
    }
  }

  @override
  void didChangeAppLifecycleState(AppLifecycleState state) {
    if (state == AppLifecycleState.resumed && widget.isActive) {
      unawaited(_refresh());
    }
  }

  @override
  void dispose() {
    WidgetsBinding.instance.removeObserver(this);
    super.dispose();
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
                  onOpenManageIzin: widget.onOpenManageIzin,
                  onOpenReports: widget.onOpenReports,
                  onOpenStaffAttendance: widget.onOpenStaffAttendance,
                  onSelectTab: widget.onSelectTab,
                  onOpenProfile: widget.onOpenProfile,
                  onOpenSettings: widget.onOpenSettings,
                  onOpenNotifications: widget.onOpenNotifications,
                  onLogout: widget.onLogout,
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
    required this.onOpenManageIzin,
    required this.onOpenReports,
    required this.onOpenStaffAttendance,
    required this.onSelectTab,
    required this.onOpenProfile,
    required this.onOpenSettings,
    required this.onOpenNotifications,
    required this.onLogout,
  });

  final Map<String, dynamic> data;
  final Future<void> Function() onOpenIzin;
  final Future<void> Function() onOpenManageIzin;
  final Future<void> Function() onOpenReports;
  final Future<void> Function() onOpenStaffAttendance;
  final ValueChanged<int> onSelectTab;
  final VoidCallback onOpenProfile;
  final VoidCallback onOpenSettings;
  final VoidCallback onOpenNotifications;
  final Future<void> Function() onLogout;

  @override
  Widget build(BuildContext context) {
    final summary = Map<String, dynamic>.from(
      (data['summary'] as Map?) ?? const <String, dynamic>{},
    );
    final permissions = Map<String, dynamic>.from(
      (data['permissions'] as Map?) ?? const <String, dynamic>{},
    );
    final monthlyStats = Map<String, dynamic>.from(
      (data['monthly_stats'] as Map?) ?? const <String, dynamic>{},
    );
    final greeting = (data['greeting'] as String?)?.trim().isNotEmpty == true
        ? data['greeting'] as String
        : 'Selamat datang';
    final userName = (data['user_name'] as String?)?.trim().isNotEmpty == true
        ? data['user_name'] as String
        : 'Pengguna';
    final performance = Map<String, dynamic>.from(
      (data['performance'] as Map?) ?? const <String, dynamic>{},
    );
    final steps = ((performance['steps'] as List?) ?? const [])
        .whereType<Map>()
        .map((item) => Map<String, dynamic>.from(item))
        .toList();
    final currentMonthLabel =
        (data['current_month_label'] as String?) ?? 'Bulan Ini';
    final attendanceBasisLabel =
        (summary['attendance_basis_label'] as String?)?.trim().isNotEmpty ==
                true
            ? summary['attendance_basis_label'] as String
            : null;
    final calendarLeadingEmptyDays =
        (data['attendance_calendar_leading_empty_days'] as num?)?.toInt() ?? 0;
    final attendanceCalendar =
        ((data['attendance_calendar'] as List?) ?? const [])
            .whereType<Map>()
            .map((item) => Map<String, dynamic>.from(item))
            .toList();
    final holidayNotes = ((data['holiday_notes'] as List?) ?? const [])
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
        _DashboardHeader(
          greeting: greeting,
          userName: userName,
          onOpenNotifications: onOpenNotifications,
          onOpenProfile: onOpenProfile,
          onOpenSettings: onOpenSettings,
          onLogout: onLogout,
        ),
        const SizedBox(height: 18),
        _PerformanceCard(
          level: (performance['level'] as String?) ?? 'Belum Ada Progress',
          percent: (performance['percent'] as num?)?.toInt() ?? 0,
        ),
        const SizedBox(height: 20),
        _SectionHeading(
          eyebrow: 'Aktivitas Presensii',
          title: currentMonthLabel,
        ),
        const SizedBox(height: 12),
        GridView.count(
          physics: const NeverScrollableScrollPhysics(),
          crossAxisCount: 4,
          shrinkWrap: true,
          childAspectRatio: 0.78,
          crossAxisSpacing: 8,
          mainAxisSpacing: 8,
          children: [
            _MonthlyStatTile(
              label: 'Kehadiran',
              value: '${summary['attendance_percent'] ?? 0}%',
              gradient: const [
                Color(0xFF0D8E89),
                Color(0xFF005E5A),
              ],
              icon: Icons.trending_up_rounded,
            ),
            _MonthlyStatTile(
              label: 'Presensi',
              value: '${monthlyStats['present_count'] ?? 0}',
              gradient: const [
                Color(0xFF1F9D73),
                Color(0xFF17634B),
              ],
              icon: Icons.check_circle_rounded,
            ),
            _MonthlyStatTile(
              label: 'Izin',
              value: '${monthlyStats['izin_count'] ?? 0}',
              gradient: const [
                Color(0xFFF4B860),
                Color(0xFFE28B2D),
              ],
              icon: Icons.schedule_rounded,
            ),
            _MonthlyStatTile(
              label: 'Alpha',
              value: '${monthlyStats['alpha_count'] ?? 0}',
              gradient: const [
                Color(0xFFEE6B5F),
                Color(0xFFB83A36),
              ],
              icon: Icons.cancel_rounded,
            ),
          ],
        ),
        if (attendanceBasisLabel != null) ...[
          const SizedBox(height: 10),
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 2),
            child: Text(
              attendanceBasisLabel,
              style: const TextStyle(
                fontSize: 11,
                fontWeight: FontWeight.w600,
                color: Color(0xFF6D7F7D),
                height: 1.35,
              ),
            ),
          ),
        ],
        const SizedBox(height: 18),
        const _SectionHeading(
          eyebrow: 'Layanan',
          title: 'Akses Cepat',
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
                label: 'Pengaturan',
                colors: const [Color(0xFF7BC7B2), Color(0xFF2C8B76)],
                icon: Icons.settings_rounded,
                onTap: onOpenSettings,
              ),
              _ServiceShortcutTile(
                label: 'Laporan',
                colors: const [Color(0xFFFFC95C), Color(0xFFF0A019)],
                icon: Icons.summarize_rounded,
                onTap: () {
                  onOpenReports();
                },
              ),
              if (permissions['can_manage_izin'] == true)
                _ServiceShortcutTile(
                  label: 'Kelola Izin',
                  colors: const [Color(0xFFFFA94D), Color(0xFFD97706)],
                  icon: Icons.approval_rounded,
                  badgeText: '${summary['pending_approval_izin_count'] ?? 0}',
                  onTap: () {
                    onOpenManageIzin();
                  },
                ),
              _ServiceShortcutTile(
                label: permissions['can_manage_izin'] == true
                    ? 'Data Presensi'
                    : 'Jadwal Hari Ini',
                colors: const [Color(0xFF57C1E8), Color(0xFF2D7DA8)],
                icon: permissions['can_manage_izin'] == true
                    ? Icons.groups_rounded
                    : Icons.today_rounded,
                badgeText: permissions['can_manage_izin'] == true
                    ? '${summary['pending_approval_izin_count'] ?? 0}'
                    : '${summary['teaching_today_count'] ?? 0}',
                onTap: () => permissions['can_manage_izin'] == true
                    ? onOpenStaffAttendance()
                    : onSelectTab(1),
              ),
            ],
          ),
        ),
        const SizedBox(height: 18),
        const _SectionHeading(
          eyebrow: 'Jadwal Hari Ini',
          title: 'Agenda Mengajar',
        ),
        const SizedBox(height: 12),
        AppSectionCard(
          padding: const EdgeInsets.fromLTRB(0, 16, 0, 16),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
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
        const SizedBox(height: 18),
        _SectionHeading(
          eyebrow: 'Kalender Presensi',
          title: 'Kalender $currentMonthLabel',
        ),
        const SizedBox(height: 12),
        _AttendanceCalendarCard(
          monthLabel: currentMonthLabel,
          leadingEmptyDays: calendarLeadingEmptyDays,
          items: attendanceCalendar,
          holidayNotes: holidayNotes,
        ),
        const SizedBox(height: 18),
        const _SectionHeading(
          eyebrow: 'Presensi Hari Ini',
          title: 'Status Kehadiran',
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
          child: Theme(
            data: Theme.of(context).copyWith(dividerColor: Colors.transparent),
            child: ExpansionTile(
              tilePadding: EdgeInsets.zero,
              childrenPadding: EdgeInsets.zero,
              title: const _SectionTitle('Detail Aktivitas Hari Ini'),
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

class _DashboardHeader extends StatelessWidget {
  const _DashboardHeader({
    required this.greeting,
    required this.userName,
    required this.onOpenNotifications,
    required this.onOpenProfile,
    required this.onOpenSettings,
    required this.onLogout,
  });

  final String greeting;
  final String userName;
  final VoidCallback onOpenNotifications;
  final VoidCallback onOpenProfile;
  final VoidCallback onOpenSettings;
  final Future<void> Function() onLogout;

  @override
  Widget build(BuildContext context) {
    return Row(
      children: [
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                greeting,
                style: const TextStyle(
                  color: Color(0xFF6D7F7D),
                  fontSize: 13,
                  fontWeight: FontWeight.w600,
                ),
              ),
              const SizedBox(height: 4),
              Text(
                userName,
                maxLines: 1,
                overflow: TextOverflow.ellipsis,
                style: const TextStyle(
                  color: Color(0xFF1F4F4C),
                  fontSize: 24,
                  fontWeight: FontWeight.w800,
                  height: 1.05,
                ),
              ),
            ],
          ),
        ),
        const SizedBox(width: 12),
        _HeaderActionButton(
          icon: Icons.notifications_none_rounded,
          onTap: onOpenNotifications,
        ),
        const SizedBox(width: 10),
        _HeaderMoreMenu(
          onOpenProfile: onOpenProfile,
          onOpenSettings: onOpenSettings,
          onLogout: onLogout,
        ),
      ],
    );
  }
}

class _HeaderActionButton extends StatelessWidget {
  const _HeaderActionButton({
    required this.icon,
    this.onTap,
  });

  final IconData icon;
  final VoidCallback? onTap;

  @override
  Widget build(BuildContext context) {
    final button = Ink(
      width: 48,
      height: 48,
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(18),
        border: Border.all(color: const Color(0xFFD8E6E4)),
        boxShadow: const [
          BoxShadow(
            color: Color(0x10003B39),
            blurRadius: 18,
            offset: Offset(0, 8),
          ),
        ],
      ),
      child: Icon(
        icon,
        color: const Color(0xFF214845),
        size: 22,
      ),
    );

    if (onTap == null) {
      return button;
    }

    return Material(
      color: Colors.white,
      borderRadius: BorderRadius.circular(18),
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(18),
        child: button,
      ),
    );
  }
}

class _HeaderMoreMenu extends StatelessWidget {
  const _HeaderMoreMenu({
    required this.onOpenProfile,
    required this.onOpenSettings,
    required this.onLogout,
  });

  final VoidCallback onOpenProfile;
  final VoidCallback onOpenSettings;
  final Future<void> Function() onLogout;

  @override
  Widget build(BuildContext context) {
    return PopupMenuButton<String>(
      tooltip: 'Menu',
      elevation: 12,
      color: Colors.white,
      surfaceTintColor: Colors.white,
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(18),
      ),
      onSelected: (value) async {
        switch (value) {
          case 'profile':
            onOpenProfile();
            break;
          case 'settings':
            onOpenSettings();
            break;
          case 'logout':
            await onLogout();
            break;
        }
      },
      itemBuilder: (context) => const [
        PopupMenuItem<String>(
          value: 'profile',
          child: _HeaderMenuItem(
            icon: Icons.person_outline_rounded,
            label: 'Profile',
          ),
        ),
        PopupMenuItem<String>(
          value: 'settings',
          child: _HeaderMenuItem(
            icon: Icons.settings_outlined,
            label: 'Pengaturan',
          ),
        ),
        PopupMenuItem<String>(
          value: 'logout',
          child: _HeaderMenuItem(
            icon: Icons.logout_rounded,
            label: 'Logout',
            isDestructive: true,
          ),
        ),
      ],
      child: const _HeaderActionButton(icon: Icons.more_horiz_rounded),
    );
  }
}

class _HeaderMenuItem extends StatelessWidget {
  const _HeaderMenuItem({
    required this.icon,
    required this.label,
    this.isDestructive = false,
  });

  final IconData icon;
  final String label;
  final bool isDestructive;

  @override
  Widget build(BuildContext context) {
    final color =
        isDestructive ? const Color(0xFFB42318) : const Color(0xFF214845);

    return Row(
      children: [
        Icon(icon, size: 20, color: color),
        const SizedBox(width: 10),
        Text(
          label,
          style: TextStyle(
            color: color,
            fontWeight: FontWeight.w700,
          ),
        ),
      ],
    );
  }
}

class _PerformanceCard extends StatelessWidget {
  const _PerformanceCard({
    required this.level,
    required this.percent,
  });

  final String level;
  final int percent;

  @override
  Widget build(BuildContext context) {
    final progress = (percent.clamp(0, 100)) / 100;

    return Container(
      padding: const EdgeInsets.all(18),
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(26),
        gradient: const LinearGradient(
          colors: [
            Color(0xFFC96A19),
            Color(0xFFF49637),
          ],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
        boxShadow: const [
          BoxShadow(
            color: Color(0x22C96A19),
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
          const SizedBox(height: 16),
          ClipRRect(
            borderRadius: BorderRadius.circular(999),
            child: LinearProgressIndicator(
              minHeight: 8,
              value: progress.toDouble(),
              backgroundColor: Colors.white.withOpacity(0.16),
              valueColor: const AlwaysStoppedAnimation<Color>(
                Color(0xFFFFE0BE),
              ),
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
  });

  final String eyebrow;
  final String title;

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
            color: Color(0xFFF49637),
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
      ],
    );
  }
}

class _AttendanceCalendarCard extends StatelessWidget {
  const _AttendanceCalendarCard({
    required this.monthLabel,
    required this.leadingEmptyDays,
    required this.items,
    required this.holidayNotes,
  });

  final String monthLabel;
  final int leadingEmptyDays;
  final List<Map<String, dynamic>> items;
  final List<Map<String, dynamic>> holidayNotes;

  @override
  Widget build(BuildContext context) {
    final totalCells = leadingEmptyDays + items.length;

    return AppSectionCard(
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              const Expanded(
                child: _SectionTitle('Kalender Presensi Bulan Ini'),
              ),
              Container(
                padding:
                    const EdgeInsets.symmetric(horizontal: 10, vertical: 7),
                decoration: BoxDecoration(
                  color: const Color(0xFFF2FBFA),
                  borderRadius: BorderRadius.circular(999),
                ),
                child: Text(
                  monthLabel,
                  style: const TextStyle(
                    color: Color(0xFF0D8E89),
                    fontSize: 11,
                    fontWeight: FontWeight.w800,
                  ),
                ),
              ),
            ],
          ),
          const SizedBox(height: 14),
          const Wrap(
            spacing: 8,
            runSpacing: 8,
            children: [
              _CalendarLegendChip(
                label: 'Hadir',
                color: Color(0xFF2E8B57),
              ),
              _CalendarLegendChip(
                label: 'Izin',
                color: Color(0xFFF4A12A),
              ),
              _CalendarLegendChip(
                label: 'Alpha',
                color: Color(0xFFB42318),
              ),
              _CalendarLegendChip(
                label: 'Tanggal Merah',
                color: Color(0xFFD92D20),
              ),
              _CalendarLegendChip(
                label: 'Libur',
                color: Color(0xFF6B7A99),
              ),
            ],
          ),
          const SizedBox(height: 16),
          const Row(
            children: [
              _CalendarWeekday('Sen'),
              _CalendarWeekday('Sel'),
              _CalendarWeekday('Rab'),
              _CalendarWeekday('Kam'),
              _CalendarWeekday('Jum'),
              _CalendarWeekday('Sab'),
              _CalendarWeekday('Min'),
            ],
          ),
          const SizedBox(height: 10),
          if (items.isEmpty)
            const AppEmptyState(
              title: 'Kalender belum tersedia',
              message: 'Data kalender presensi bulan ini belum dapat dimuat.',
              icon: Icons.calendar_today_outlined,
            )
          else
            GridView.builder(
              physics: const NeverScrollableScrollPhysics(),
              shrinkWrap: true,
              itemCount: totalCells,
              gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                crossAxisCount: 7,
                mainAxisSpacing: 8,
                crossAxisSpacing: 8,
                childAspectRatio: 0.66,
              ),
              itemBuilder: (context, index) {
                if (index < leadingEmptyDays) {
                  return const SizedBox.shrink();
                }

                return _CalendarDayTile(
                  item: items[index - leadingEmptyDays],
                );
              },
            ),
          const SizedBox(height: 16),
          const _SectionTitle('Keterangan Tanggal Merah'),
          const SizedBox(height: 10),
          if (holidayNotes.isEmpty)
            const AppEmptyState(
              title: 'Tidak ada tanggal merah bulan ini',
              message:
                  'Daftar hari libur nasional untuk bulan ini belum tersedia.',
              icon: Icons.event_available_rounded,
            )
          else
            ...holidayNotes.map(
              (item) => Container(
                margin: const EdgeInsets.only(bottom: 8),
                padding: const EdgeInsets.fromLTRB(0, 0, 0, 8),
                decoration: const BoxDecoration(
                  border: Border(
                    bottom: BorderSide(
                      color: Color(0xFFF1DEDB),
                    ),
                  ),
                ),
                child: Row(
                  crossAxisAlignment: CrossAxisAlignment.center,
                  children: [
                    Container(
                      padding: const EdgeInsets.symmetric(
                        horizontal: 10,
                        vertical: 6,
                      ),
                      decoration: BoxDecoration(
                        color: const Color(0xFFD92D20).withOpacity(0.1),
                        borderRadius: BorderRadius.circular(999),
                      ),
                      child: Text(
                        item['date_label'] as String? ?? '-',
                        style: const TextStyle(
                          color: Color(0xFFD92D20),
                          fontSize: 10,
                          fontWeight: FontWeight.w800,
                        ),
                      ),
                    ),
                    const SizedBox(width: 10),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          Text(
                            item['name'] as String? ?? 'Tanggal merah',
                            maxLines: 1,
                            overflow: TextOverflow.ellipsis,
                            style: const TextStyle(
                              color: Color(0xFF385452),
                              fontSize: 12,
                              fontWeight: FontWeight.w800,
                              height: 1.1,
                            ),
                          ),
                          if ((item['description'] as String?)
                                  ?.trim()
                                  .isNotEmpty ==
                              true) ...[
                            const SizedBox(height: 2),
                            Text(
                              item['description'] as String,
                              maxLines: 1,
                              overflow: TextOverflow.ellipsis,
                              style: const TextStyle(
                                color: Color(0xFF8A9B99),
                                fontSize: 10,
                                height: 1.2,
                              ),
                            ),
                          ],
                        ],
                      ),
                    ),
                  ],
                ),
              ),
            ),
        ],
      ),
    );
  }
}

class _CalendarLegendChip extends StatelessWidget {
  const _CalendarLegendChip({
    required this.label,
    required this.color,
  });

  final String label;
  final Color color;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 8),
      decoration: BoxDecoration(
        color: color.withOpacity(0.1),
        borderRadius: BorderRadius.circular(999),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Container(
            width: 8,
            height: 8,
            decoration: BoxDecoration(
              color: color,
              shape: BoxShape.circle,
            ),
          ),
          const SizedBox(width: 6),
          Text(
            label,
            style: TextStyle(
              color: color,
              fontSize: 11,
              fontWeight: FontWeight.w800,
            ),
          ),
        ],
      ),
    );
  }
}

class _CalendarWeekday extends StatelessWidget {
  const _CalendarWeekday(this.label);

  final String label;

  @override
  Widget build(BuildContext context) {
    return Expanded(
      child: Text(
        label,
        textAlign: TextAlign.center,
        style: const TextStyle(
          color: Color(0xFF7A8F8C),
          fontSize: 11,
          fontWeight: FontWeight.w700,
        ),
      ),
    );
  }
}

class _CalendarDayTile extends StatelessWidget {
  const _CalendarDayTile({
    required this.item,
  });

  final Map<String, dynamic> item;

  @override
  Widget build(BuildContext context) {
    final status = item['status'] as String? ?? 'belum_tercatat';
    final isToday = item['is_today'] == true;
    final color = _calendarStatusColor(status);
    final isHoliday = status == 'tanggal_merah';

    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 4, vertical: 6),
      decoration: BoxDecoration(
        color: isHoliday
            ? const Color(0xFFFFF3F2)
            : isToday
                ? const Color(0xFFF2FBFA)
                : const Color(0xFFF9FCFC),
        borderRadius: BorderRadius.circular(18),
        border: Border.all(
          color: isHoliday
              ? const Color(0xFFF1B5AE)
              : isToday
                  ? const Color(0xFF0D8E89)
                  : const Color(0xFFDDEBE9),
          width: isToday ? 1.5 : 1,
        ),
      ),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.spaceEvenly,
        children: [
          FittedBox(
            fit: BoxFit.scaleDown,
            child: Text(
              '${item['day_number'] ?? '-'}',
              style: TextStyle(
                color: isHoliday
                    ? const Color(0xFFD92D20)
                    : isToday
                        ? const Color(0xFF0D8E89)
                        : const Color(0xFF1F4F4C),
                fontSize: 14,
                fontWeight: FontWeight.w800,
              ),
            ),
          ),
          Container(
            width: 8,
            height: 8,
            decoration: BoxDecoration(
              color: color,
              shape: BoxShape.circle,
            ),
          ),
        ],
      ),
    );
  }
}

class _MonthlyStatTile extends StatelessWidget {
  const _MonthlyStatTile({
    required this.label,
    required this.value,
    required this.gradient,
    required this.icon,
  });

  final String label;
  final String value;
  final List<Color> gradient;
  final IconData icon;

  @override
  Widget build(BuildContext context) {
    final accent = gradient.first;

    return Container(
      padding: const EdgeInsets.fromLTRB(8, 12, 8, 10),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        border: Border.all(
          color: accent.withOpacity(0.5),
          width: 1.2,
        ),
        boxShadow: const [
          BoxShadow(
            color: Color(0x0B003B39),
            blurRadius: 12,
            offset: Offset(0, 4),
          ),
        ],
      ),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        crossAxisAlignment: CrossAxisAlignment.center,
        children: [
          Container(
            width: 30,
            height: 30,
            decoration: BoxDecoration(
              color: accent.withOpacity(0.12),
              shape: BoxShape.circle,
            ),
            child: Icon(icon, color: accent, size: 16),
          ),
          const SizedBox(height: 6),
          FittedBox(
            fit: BoxFit.scaleDown,
            child: Text(
              value,
              textAlign: TextAlign.center,
              style: TextStyle(
                color: accent,
                fontSize: 14,
                fontWeight: FontWeight.w800,
                height: 1,
              ),
            ),
          ),
          const SizedBox(height: 4),
          Flexible(
            child: Center(
              child: Text(
                label,
                maxLines: 2,
                overflow: TextOverflow.ellipsis,
                textAlign: TextAlign.center,
                style: const TextStyle(
                  color: Color(0xFF6D7F7D),
                  fontSize: 10,
                  fontWeight: FontWeight.w700,
                  height: 1.1,
                ),
              ),
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
    final isExcused = status == 'izin';
    final gradientColors = isCompleted
        ? const [Color(0xFF1F9D73), Color(0xFF17634B)]
        : (isExcused
            ? const [Color(0xFF34B3E4), Color(0xFF1D6FA5)]
            : const [Color(0xFFF4B860), Color(0xFFE5952D)]);
    final dotColor = isCompleted
        ? const Color(0xFFB7FFD0)
        : (isExcused ? const Color(0xFFD9F3FF) : const Color(0xFFFFF1BE));

    return Container(
      width: 238,
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(24),
        gradient: LinearGradient(
          colors: gradientColors,
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
                  color: dotColor,
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
    final status = step['status'] as String? ?? 'pending';
    final isDone = status == 'completed';
    final isExcused = status == 'izin' || status == 'excused';
    final tileColor = isDone
        ? const Color(0xFFE8F8EE)
        : (isExcused ? const Color(0xFFE9F7FE) : const Color(0xFFF7FBFB));
    final borderColor = isDone
        ? const Color(0xFFBFE8CB)
        : (isExcused ? const Color(0xFFB7E3F8) : const Color(0xFFDDEBE9));
    final iconColor = isDone
        ? const Color(0xFF2E8B57)
        : (isExcused ? const Color(0xFF0E7490) : const Color(0xFFCFDEDC));
    final iconForeground = isDone || isExcused
        ? Colors.white
        : const Color(0xFF52726E);
    final textColor = isDone
        ? const Color(0xFF1D6B40)
        : (isExcused ? const Color(0xFF155E75) : const Color(0xFF1F4F4C));
    final subtitle = isDone
        ? 'Sudah dilakukan'
        : (isExcused ? 'Izin disetujui' : 'Belum dilakukan');

    return Container(
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: tileColor,
        borderRadius: BorderRadius.circular(18),
        border: Border.all(
          color: borderColor,
        ),
      ),
      child: Row(
        children: [
          Container(
            width: 40,
            height: 40,
            decoration: BoxDecoration(
              color: iconColor,
              borderRadius: BorderRadius.circular(14),
            ),
            child: Icon(
              _stepIcon(step['icon'] as String?),
              color: iconForeground,
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
                    color: textColor,
                  ),
                ),
                const SizedBox(height: 4),
                Text(
                  subtitle,
                  style: TextStyle(
                    fontSize: 12,
                    color: isDone || isExcused
                        ? textColor
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

Color _calendarStatusColor(String value) {
  switch (value) {
    case 'tanggal_merah':
      return const Color(0xFFD92D20);
    case 'hadir':
      return const Color(0xFF2E8B57);
    case 'izin':
      return const Color(0xFFF4A12A);
    case 'alpha':
      return const Color(0xFFB42318);
    case 'libur':
      return const Color(0xFF6B7A99);
    case 'akan_datang':
      return const Color(0xFFC8D3D1);
    default:
      return const Color(0xFF90A4A1);
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
