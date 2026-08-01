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
  late final ScrollController _scrollController;
  DateTime? _selectedCalendarMonth;
  Map<String, dynamic> _profileData = const <String, dynamic>{};
  bool _hasScrolled = false;

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addObserver(this);
    _scrollController = ScrollController()..addListener(_handleScroll);
    _selectedCalendarMonth = _currentMonthAnchor();
    _future = _requestDashboardForMonth(_selectedCalendarMonth!);
    unawaited(_loadProfileData());
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
    _scrollController
      ..removeListener(_handleScroll)
      ..dispose();
    WidgetsBinding.instance.removeObserver(this);
    super.dispose();
  }

  void _handleScroll() {
    final next = _scrollController.hasClients && _scrollController.offset > 6;
    if (next == _hasScrolled || !mounted) {
      return;
    }
    setState(() {
      _hasScrolled = next;
    });
  }

  Future<Map<String, dynamic>> _requestDashboardForMonth(DateTime month) async {
    final normalized = DateTime(month.year, month.month);
    return widget.repository.getDashboard(
      month: _monthKeyFromDate(normalized),
    );
  }

  Future<void> _refresh() async {
    final future = _requestDashboardForMonth(_effectiveSelectedCalendarMonth);
    setState(() {
      _future = future;
    });
    await Future.wait<void>([
      future.then((_) {}),
      _loadProfileData(),
    ]);
  }

  DateTime _currentMonthAnchor() {
    final now = DateTime.now();
    return DateTime(now.year, now.month);
  }

  DateTime get _effectiveSelectedCalendarMonth =>
      _selectedCalendarMonth ?? _currentMonthAnchor();

  Future<void> _openCalendarMonthPicker() async {
    final currentSelectedMonth = _effectiveSelectedCalendarMonth;
    final selected = await showModalBottomSheet<DateTime>(
      context: context,
      showDragHandle: true,
      backgroundColor: Colors.white,
      builder: (context) {
        final now = DateTime.now();
        final yearOptions = List<int>.generate(
          (now.year + 2) - 2020 + 1,
          (index) => 2020 + index,
        ).reversed.toList(growable: false);

        var pickerYear = currentSelectedMonth.year;

        return StatefulBuilder(
          builder: (context, setModalState) {
            return SafeArea(
              top: false,
              child: Padding(
                padding: const EdgeInsets.fromLTRB(16, 8, 16, 24),
                child: Column(
                  mainAxisSize: MainAxisSize.min,
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text(
                      'Pilih Bulan',
                      style: TextStyle(
                        color: Colors.black,
                        fontSize: 18,
                        fontWeight: FontWeight.w800,
                      ),
                    ),
                    const SizedBox(height: 14),
                    Container(
                      width: double.infinity,
                      padding: const EdgeInsets.symmetric(horizontal: 14),
                      decoration: BoxDecoration(
                        color: const Color(0xFFF7FAF8),
                        borderRadius: BorderRadius.circular(16),
                        border: Border.all(color: const Color(0xFFE2ECE9)),
                      ),
                      child: DropdownButtonHideUnderline(
                        child: DropdownButton<int>(
                          value: pickerYear,
                          isExpanded: true,
                          icon: const Icon(
                            Icons.keyboard_arrow_down_rounded,
                            color: Color(0xFF1F6B52),
                          ),
                          items: yearOptions
                              .map(
                                (year) => DropdownMenuItem<int>(
                                  value: year,
                                  child: Text(
                                    year.toString(),
                                    style: const TextStyle(
                                      color: Colors.black,
                                      fontWeight: FontWeight.w700,
                                    ),
                                  ),
                                ),
                              )
                              .toList(),
                          onChanged: (value) {
                            if (value == null) {
                              return;
                            }
                            setModalState(() {
                              pickerYear = value;
                            });
                          },
                        ),
                      ),
                    ),
                    const SizedBox(height: 14),
                    Wrap(
                      spacing: 8,
                      runSpacing: 8,
                      children: List<Widget>.generate(12, (index) {
                        final month = index + 1;
                        final candidate = DateTime(pickerYear, month);
                        final isSelected =
                            candidate.year == currentSelectedMonth.year &&
                                candidate.month == currentSelectedMonth.month;

                        return GestureDetector(
                          onTap: () => Navigator.of(context).pop(candidate),
                          child: Container(
                            width:
                                (MediaQuery.of(context).size.width - 56) / 3,
                            padding: const EdgeInsets.symmetric(
                              horizontal: 12,
                              vertical: 12,
                            ),
                            decoration: BoxDecoration(
                              color: isSelected
                                  ? const Color(0xFFF1F7F4)
                                  : const Color(0xFFF9FCFA),
                              borderRadius: BorderRadius.circular(16),
                              border: Border.all(
                                color: isSelected
                                    ? const Color(0xFF1F6B52)
                                    : const Color(0xFFE3ECE8),
                              ),
                            ),
                            child: Center(
                              child: Text(
                                _monthLabelShortId(month),
                                style: TextStyle(
                                  color: isSelected
                                      ? const Color(0xFF1F6B52)
                                      : Colors.black,
                                  fontSize: 13,
                                  fontWeight: isSelected
                                      ? FontWeight.w800
                                      : FontWeight.w700,
                                ),
                              ),
                            ),
                          ),
                        );
                      }),
                    ),
                  ],
                ),
              ),
            );
          },
        );
      },
    );

    if (selected == null || !mounted) {
      return;
    }

    final normalized = DateTime(selected.year, selected.month);
    if (normalized.year == currentSelectedMonth.year &&
        normalized.month == currentSelectedMonth.month) {
      return;
    }

    final future = _requestDashboardForMonth(normalized);
    setState(() {
      _selectedCalendarMonth = normalized;
      _future = future;
    });
    await future;
  }

  Future<void> _loadProfileData() async {
    try {
      final data = await widget.repository.getProfile();
      if (!mounted) {
        return;
      }

      final profileUser = Map<String, dynamic>.from(
        (data['user'] as Map?) ?? const <String, dynamic>{},
      );
      final avatarUrl = _normalizedAvatarUrl(
        (profileUser['avatar_url'] as String?)?.trim(),
      );

      setState(() {
        _profileData = data;
      });

      if (avatarUrl != null) {
        unawaited(
          precacheImage(
            NetworkImage(avatarUrl),
            context,
          ).catchError((_) {}),
        );
      }
    } catch (_) {
      // Keep dashboard usable even when profile summary fails to load.
    }
  }

  @override
  Widget build(BuildContext context) {
    return FutureBuilder<Map<String, dynamic>>(
      future: _future,
      builder: (context, snapshot) {
        final data = snapshot.data ?? const <String, dynamic>{};
        final selectedMonthLabel =
            _monthLabelFullId(_effectiveSelectedCalendarMonth);

        if (snapshot.connectionState == ConnectionState.waiting) {
          return RefreshIndicator(
            onRefresh: _refresh,
            child: ListView(
              physics: const AlwaysScrollableScrollPhysics(),
              padding: const EdgeInsets.fromLTRB(16, 16, 16, 28),
              children: const [
                _LoadingBlock(),
              ],
            ),
          );
        }

        if (snapshot.hasError) {
          return RefreshIndicator(
            onRefresh: _refresh,
            child: ListView(
              physics: const AlwaysScrollableScrollPhysics(),
              padding: const EdgeInsets.fromLTRB(16, 16, 16, 28),
              children: [
                _ErrorBlock(
                  message: snapshot.error.toString(),
                  onRetry: _refresh,
                ),
              ],
            ),
          );
        }

        final headerData = _resolveDashboardHeaderData(
          data: data,
          profileData: _profileData,
        );

        return RefreshIndicator(
          onRefresh: _refresh,
          child: Stack(
            children: [
              ListView(
                controller: _scrollController,
                physics: const AlwaysScrollableScrollPhysics(),
                padding: const EdgeInsets.only(bottom: 28),
                children: [
                  const _DashboardTopBackdrop(),
                  Padding(
                    padding: const EdgeInsets.symmetric(horizontal: 16),
                    child: Transform.translate(
                      offset: const Offset(0, -196),
                      child: _DashboardContent(
                        data: data,
                        selectedMonthLabel: selectedMonthLabel,
                        onOpenIzin: widget.onOpenIzin,
                        onOpenManageIzin: widget.onOpenManageIzin,
                        onOpenReports: widget.onOpenReports,
                        onOpenStaffAttendance: widget.onOpenStaffAttendance,
                        onSelectTab: widget.onSelectTab,
                        onOpenProfile: widget.onOpenProfile,
                        onOpenSettings: widget.onOpenSettings,
                        onOpenNotifications: widget.onOpenNotifications,
                        onLogout: widget.onLogout,
                        onOpenCalendarMonthPicker: _openCalendarMonthPicker,
                      ),
                    ),
                  ),
                ],
              ),
              Positioned(
                top: 0,
                left: 0,
                right: 0,
                child: AnimatedContainer(
                  duration: const Duration(milliseconds: 180),
                  curve: Curves.easeOut,
                  decoration: BoxDecoration(
                    color: _hasScrolled
                        ? Colors.white
                        : const Color(0xFF174C3D),
                    boxShadow: _hasScrolled
                        ? const [
                            BoxShadow(
                              color: Color(0x12003B39),
                              blurRadius: 16,
                              offset: Offset(0, 6),
                            ),
                          ]
                        : const [],
                  ),
                  padding: const EdgeInsets.fromLTRB(16, 16, 16, 10),
                  child: _DashboardHeader(
                    schoolName: headerData.schoolName,
                    userName: headerData.userName,
                    avatarUrl: headerData.avatarUrl,
                    isScrolled: _hasScrolled,
                    onOpenNotifications: widget.onOpenNotifications,
                    onOpenProfile: widget.onOpenProfile,
                    onOpenSettings: widget.onOpenSettings,
                    onLogout: widget.onLogout,
                  ),
                ),
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
    required this.selectedMonthLabel,
    required this.onOpenIzin,
    required this.onOpenManageIzin,
    required this.onOpenReports,
    required this.onOpenStaffAttendance,
    required this.onSelectTab,
    required this.onOpenProfile,
    required this.onOpenSettings,
    required this.onOpenNotifications,
    required this.onLogout,
    required this.onOpenCalendarMonthPicker,
  });

  final Map<String, dynamic> data;
  final String selectedMonthLabel;
  final Future<void> Function() onOpenIzin;
  final Future<void> Function() onOpenManageIzin;
  final Future<void> Function() onOpenReports;
  final Future<void> Function() onOpenStaffAttendance;
  final ValueChanged<int> onSelectTab;
  final VoidCallback onOpenProfile;
  final VoidCallback onOpenSettings;
  final VoidCallback onOpenNotifications;
  final Future<void> Function() onLogout;
  final VoidCallback onOpenCalendarMonthPicker;

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
    final performance = Map<String, dynamic>.from(
      (data['performance'] as Map?) ?? const <String, dynamic>{},
    );
    final currentMonthLabel = selectedMonthLabel;
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
    final schedules = ((data['today_schedules'] as List?) ?? const [])
        .whereType<Map>()
        .map((item) => Map<String, dynamic>.from(item))
        .toList();
    final serviceSections = _buildServiceSections(
      permissions: permissions,
      summary: summary,
      onSelectTab: onSelectTab,
      onOpenIzin: onOpenIzin,
      onOpenManageIzin: onOpenManageIzin,
      onOpenReports: onOpenReports,
      onOpenStaffAttendance: onOpenStaffAttendance,
      onOpenSettings: onOpenSettings,
    );
    final serviceItems = serviceSections
        .expand((section) => section.items)
        .toList(growable: false);

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        AppSectionCard(
          padding: const EdgeInsets.fromLTRB(8, 8, 8, 10),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              _PerformanceCard(
                level: (performance['level'] as String?) ?? 'Belum Ada Progress',
                percent: (performance['percent'] as num?)?.toInt() ?? 0,
              ),
              const SizedBox(height: 14),
              Row(
                children: [
                  const Expanded(
                    child: Text(
                      'Aktivitas Presensi',
                      style: TextStyle(
                        color: Colors.black,
                        fontSize: 15,
                        fontWeight: FontWeight.w800,
                      ),
                    ),
                  ),
                  Container(
                    padding: const EdgeInsets.symmetric(
                      horizontal: 10,
                      vertical: 6,
                    ),
                    decoration: BoxDecoration(
                      color: const Color(0xFFF3F7F5),
                      borderRadius: BorderRadius.circular(999),
                    ),
                    child: Text(
                      currentMonthLabel,
                      style: const TextStyle(
                        color: Colors.black,
                        fontSize: 10,
                        fontWeight: FontWeight.w700,
                      ),
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 10),
              IntrinsicHeight(
                child: Row(
                  children: [
                    Expanded(
                      child: _MonthlyStatTile(
                        label: 'Kehadiran',
                        value: '${summary['attendance_percent'] ?? 0}%',
                        gradient: const [
                          Color(0xFF0D8E89),
                          Color(0xFF005E5A),
                        ],
                        icon: Icons.trending_up_rounded,
                      ),
                    ),
                    const SizedBox(width: 8),
                    Expanded(
                      child: _MonthlyStatTile(
                        label: 'Presensi',
                        value: '${monthlyStats['present_count'] ?? 0}',
                        gradient: const [
                          Color(0xFF1F9D73),
                          Color(0xFF17634B),
                        ],
                        icon: Icons.check_circle_rounded,
                      ),
                    ),
                    const SizedBox(width: 8),
                    Expanded(
                      child: _MonthlyStatTile(
                        label: 'Izin',
                        value: '${monthlyStats['izin_count'] ?? 0}',
                        gradient: const [
                          Color(0xFF4D8D74),
                          Color(0xFF215344),
                        ],
                        icon: Icons.schedule_rounded,
                      ),
                    ),
                    const SizedBox(width: 8),
                    Expanded(
                      child: _MonthlyStatTile(
                        label: 'Alpha',
                        value: '${monthlyStats['alpha_count'] ?? 0}',
                        gradient: const [
                          Color(0xFFEE6B5F),
                          Color(0xFFB83A36),
                        ],
                        icon: Icons.cancel_rounded,
                      ),
                    ),
                  ],
                ),
              ),
            ],
          ),
        ),
        const SizedBox(height: 18),
        Row(
          crossAxisAlignment: CrossAxisAlignment.end,
          children: [
            const Expanded(
              child: Text(
                'Layanan',
                style: TextStyle(
                  color: Colors.black,
                  fontSize: 11,
                  fontWeight: FontWeight.w800,
                  letterSpacing: 0.4,
                ),
              ),
            ),
            GestureDetector(
              onTap: () {
                Navigator.of(context).push(
                  MaterialPageRoute<void>(
                    builder: (_) => _AllServicesPage(
                      sections: serviceSections,
                    ),
                  ),
                );
              },
              child: const Text(
                'See All',
                style: TextStyle(
                  color: Color(0xFF1F6B52),
                  fontSize: 12,
                  fontWeight: FontWeight.w700,
                ),
              ),
            ),
          ],
        ),
        const SizedBox(height: 10),
        SizedBox(
          height: 98,
          child: ListView.separated(
            scrollDirection: Axis.horizontal,
            physics: const BouncingScrollPhysics(),
            itemCount: serviceItems.length,
            separatorBuilder: (_, __) => const SizedBox(width: 18),
            itemBuilder: (context, index) {
              final item = serviceItems[index];

              return SizedBox(
                width: 72,
                child: _ServiceShortcutTile(
                  label: item.label,
                  colors: item.colors,
                  icon: item.icon,
                  badgeText: item.badgeText,
                  onTap: item.onTap,
                ),
              );
            },
          ),
        ),
        const SizedBox(height: 18),
        Row(
          crossAxisAlignment: CrossAxisAlignment.end,
          children: [
            const Expanded(
              child: Text(
                'Jadwal',
                style: TextStyle(
                  color: Colors.black,
                  fontSize: 11,
                  fontWeight: FontWeight.w800,
                  letterSpacing: 0.4,
                ),
              ),
            ),
            GestureDetector(
              onTap: () => onSelectTab(1),
              child: const Text(
                'See All',
                style: TextStyle(
                  color: Color(0xFF1F6B52),
                  fontSize: 12,
                  fontWeight: FontWeight.w700,
                ),
              ),
            ),
          ],
        ),
        const SizedBox(height: 10),
        if (schedules.isEmpty)
          const Padding(
            padding: EdgeInsets.symmetric(horizontal: 4),
            child: AppEmptyState(
              title: 'Tidak ada jadwal hari ini',
              message: 'Belum ada jadwal mengajar untuk hari ini.',
              icon: Icons.event_busy_outlined,
            ),
          )
        else
          SizedBox(
            height: 170,
            child: ListView.separated(
              padding: EdgeInsets.zero,
              scrollDirection: Axis.horizontal,
              itemBuilder: (context, index) {
                return _ScheduleShowcaseCard(item: schedules[index]);
              },
              separatorBuilder: (_, __) => const SizedBox(width: 12),
              itemCount: schedules.length,
            ),
          ),
        const SizedBox(height: 18),
        Row(
          crossAxisAlignment: CrossAxisAlignment.end,
          children: [
            const Expanded(
              child: Text(
                'Kalender',
                style: TextStyle(
                  color: Colors.black,
                  fontSize: 11,
                  fontWeight: FontWeight.w800,
                  letterSpacing: 0.4,
                ),
              ),
            ),
            Text(
              currentMonthLabel,
              style: const TextStyle(
                color: Color(0xFF627370),
                fontSize: 11,
                fontWeight: FontWeight.w700,
              ),
            ),
          ],
        ),
        const SizedBox(height: 12),
        _AttendanceCalendarCard(
          monthLabel: currentMonthLabel,
          leadingEmptyDays: calendarLeadingEmptyDays,
          items: attendanceCalendar,
          holidayNotes: holidayNotes,
          onOpenMonthPicker: onOpenCalendarMonthPicker,
        ),
      ],
    );
  }
}

class _DashboardTopBackdrop extends StatelessWidget {
  const _DashboardTopBackdrop();

  @override
  Widget build(BuildContext context) {
    return Container(
      height: 288,
      decoration: const BoxDecoration(
        gradient: LinearGradient(
          colors: [
            Color(0xFF11372D),
            Color(0xFF154336),
            Color(0xFF174C3D),
            Color(0xFF20614D),
            Color(0xFF2E7D61),
            Color(0xFF58A383),
            Color(0xFF8BC8AE),
          ],
          stops: [0, 0.14, 0.32, 0.52, 0.72, 0.88, 1],
          begin: Alignment.topCenter,
          end: Alignment.bottomCenter,
        ),
        borderRadius: BorderRadius.only(
          bottomLeft: Radius.elliptical(360, 132),
          bottomRight: Radius.elliptical(360, 132),
        ),
      ),
    );
  }
}

class _DashboardHeaderData {
  const _DashboardHeaderData({
    required this.schoolName,
    required this.userName,
    required this.avatarUrl,
  });

  final String schoolName;
  final String userName;
  final String? avatarUrl;
}

_DashboardHeaderData _resolveDashboardHeaderData({
  required Map<String, dynamic> data,
  required Map<String, dynamic> profileData,
}) {
  final profileUser = Map<String, dynamic>.from(
    (profileData['user'] as Map?) ?? const <String, dynamic>{},
  );
  final user = Map<String, dynamic>.from(
    (data['user'] as Map?) ?? const <String, dynamic>{},
  );

  final userName = (data['user_name'] as String?)?.trim().isNotEmpty == true
      ? data['user_name'] as String
      : ((profileUser['name'] as String?)?.trim().isNotEmpty == true
          ? (profileUser['name'] as String).trim()
          : ((user['name'] as String?)?.trim().isNotEmpty == true
              ? (user['name'] as String).trim()
              : 'Pengguna'));

  final schoolName =
      (profileUser['school_name'] as String?)?.trim().isNotEmpty == true
          ? (profileUser['school_name'] as String).trim()
          : (user['school_name'] as String?)?.trim().isNotEmpty == true
              ? (user['school_name'] as String).trim()
              : ((data['school_name'] as String?)?.trim().isNotEmpty == true
                  ? (data['school_name'] as String).trim()
                  : '-');

  final avatarUrl = _normalizedAvatarUrl(
    (profileUser['avatar_url'] as String?)?.trim().isNotEmpty == true
        ? (profileUser['avatar_url'] as String).trim()
        : (user['avatar_url'] as String?)?.trim().isNotEmpty == true
            ? (user['avatar_url'] as String).trim()
            : ((data['avatar_url'] as String?)?.trim().isNotEmpty == true
                ? (data['avatar_url'] as String).trim()
                : null),
  );

  return _DashboardHeaderData(
    schoolName: schoolName,
    userName: userName,
    avatarUrl: avatarUrl,
  );
}

class _DashboardHeader extends StatelessWidget {
  const _DashboardHeader({
    required this.schoolName,
    required this.userName,
    required this.avatarUrl,
    required this.isScrolled,
    required this.onOpenNotifications,
    required this.onOpenProfile,
    required this.onOpenSettings,
    required this.onLogout,
  });

  final String schoolName;
  final String userName;
  final String? avatarUrl;
  final bool isScrolled;
  final VoidCallback onOpenNotifications;
  final VoidCallback onOpenProfile;
  final VoidCallback onOpenSettings;
  final Future<void> Function() onLogout;

  @override
  Widget build(BuildContext context) {
    final foregroundColor =
        isScrolled ? Colors.black : const Color(0xFFF6FBF8);
    final iconColor =
        isScrolled ? const Color(0xFF214845) : const Color(0xFFF6FBF8);

    return Row(
      children: [
        Expanded(
          child: Row(
            children: [
              _DashboardHeaderAvatar(
                avatarUrl: avatarUrl,
                userName: userName,
              ),
              const SizedBox(width: 12),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      schoolName,
                      maxLines: 1,
                      overflow: TextOverflow.ellipsis,
                      style: TextStyle(
                        color: foregroundColor.withOpacity(isScrolled ? 1 : 0.9),
                        fontSize: 12,
                        fontWeight: FontWeight.w600,
                      ),
                    ),
                    const SizedBox(height: 4),
                    Text(
                      userName,
                      maxLines: 1,
                      overflow: TextOverflow.ellipsis,
                      style: TextStyle(
                        color: foregroundColor,
                        fontSize: 16,
                        fontWeight: FontWeight.w800,
                        height: 1.05,
                      ),
                    ),
                  ],
                ),
              ),
            ],
          ),
        ),
        const SizedBox(width: 12),
        _HeaderActionButton(
          icon: Icons.notifications_none_rounded,
          color: iconColor,
          onTap: onOpenNotifications,
        ),
        const SizedBox(width: 0),
        _HeaderMoreMenu(
          color: iconColor,
          onOpenProfile: onOpenProfile,
          onOpenSettings: onOpenSettings,
          onLogout: onLogout,
        ),
      ],
    );
  }
}

class _DashboardHeaderAvatar extends StatelessWidget {
  const _DashboardHeaderAvatar({
    required this.avatarUrl,
    required this.userName,
  });

  final String? avatarUrl;
  final String userName;

  @override
  Widget build(BuildContext context) {
    final initials = _initialsFromName(userName);

    return CircleAvatar(
      radius: 26,
      backgroundColor: Colors.white,
      child: ClipOval(
        child: SizedBox(
          width: 52,
          height: 52,
          child: avatarUrl != null && avatarUrl!.trim().isNotEmpty
              ? Image.network(
                  avatarUrl!.trim(),
                  fit: BoxFit.cover,
                  errorBuilder: (_, __, ___) => _DashboardAvatarFallback(
                    initials: initials,
                  ),
                )
              : _DashboardAvatarFallback(
                  initials: initials,
                ),
        ),
      ),
    );
  }
}

class _DashboardAvatarFallback extends StatelessWidget {
  const _DashboardAvatarFallback({
    required this.initials,
  });

  final String initials;

  @override
  Widget build(BuildContext context) {
    return DecoratedBox(
      decoration: const BoxDecoration(
        gradient: LinearGradient(
          colors: [Color(0xFF1F6B52), Color(0xFF174C3D)],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
      ),
      child: Center(
        child: Text(
          initials,
          style: const TextStyle(
            color: Colors.white,
            fontSize: 16,
            fontWeight: FontWeight.w800,
          ),
        ),
      ),
    );
  }
}

class _HeaderActionButton extends StatelessWidget {
  const _HeaderActionButton({
    required this.icon,
    required this.color,
    this.onTap,
  });

  final IconData icon;
  final Color color;
  final VoidCallback? onTap;

  @override
  Widget build(BuildContext context) {
    if (onTap == null) {
      return const SizedBox(
        width: 24,
        height: 24,
      );
    }

    return IconButton(
      onPressed: onTap,
      padding: EdgeInsets.zero,
      constraints: const BoxConstraints.tightFor(width: 24, height: 24),
      splashRadius: 16,
      icon: Icon(
        icon,
        color: color,
        size: 22,
      ),
    );
  }
}

class _HeaderMoreMenu extends StatelessWidget {
  const _HeaderMoreMenu({
    required this.color,
    required this.onOpenProfile,
    required this.onOpenSettings,
    required this.onLogout,
  });

  final Color color;
  final VoidCallback onOpenProfile;
  final VoidCallback onOpenSettings;
  final Future<void> Function() onLogout;

  @override
  Widget build(BuildContext context) {
    return PopupMenuButton<String>(
      tooltip: 'Menu',
      padding: EdgeInsets.zero,
      elevation: 12,
      color: Colors.white,
      surfaceTintColor: Colors.white,
      offset: const Offset(0, 34),
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
      child: SizedBox(
        width: 22,
        height: 22,
        child: Center(
          child: Icon(
            Icons.more_vert_rounded,
            color: color,
            size: 22,
          ),
        ),
      ),
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
      padding: const EdgeInsets.fromLTRB(16, 16, 16, 16),
      decoration: BoxDecoration(
        color: const Color(0xFF174C3D),
        borderRadius: BorderRadius.circular(20),
      ),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                'Level Progres',
                style: TextStyle(
                  color: Colors.white.withOpacity(0.9),
                  fontSize: 11,
                  fontWeight: FontWeight.w700,
                ),
              ),
              const Spacer(),
              Text(
                '$percent%',
                style: const TextStyle(
                  color: Colors.white,
                  fontSize: 22,
                  fontWeight: FontWeight.w800,
                  height: 1,
                ),
              ),
            ],
          ),
          const SizedBox(height: 12),
          Text(
            level,
            style: const TextStyle(
              color: Colors.white,
              fontSize: 18,
              fontWeight: FontWeight.w800,
              height: 1.15,
            ),
          ),
          const SizedBox(height: 14),
          ClipRRect(
            borderRadius: BorderRadius.circular(999),
            child: LinearProgressIndicator(
              minHeight: 8,
              value: progress.toDouble(),
              backgroundColor: const Color(0xFF3B6F5F),
              valueColor: const AlwaysStoppedAnimation<Color>(
                Color(0xFFC7E5D8),
              ),
            ),
          ),
        ],
      ),
    );
  }
}

class _AttendanceCalendarCard extends StatelessWidget {
  const _AttendanceCalendarCard({
    required this.monthLabel,
    required this.leadingEmptyDays,
    required this.items,
    required this.holidayNotes,
    required this.onOpenMonthPicker,
  });

  final String monthLabel;
  final int leadingEmptyDays;
  final List<Map<String, dynamic>> items;
  final List<Map<String, dynamic>> holidayNotes;
  final VoidCallback onOpenMonthPicker;

  @override
  Widget build(BuildContext context) {
    final totalCells = leadingEmptyDays + items.length;
    final trailingEmptyDays = (7 - (totalCells % 7)) % 7;
    final calendarCells = <Map<String, dynamic>?>[
      ...List<Map<String, dynamic>?>.filled(leadingEmptyDays, null),
      ...items,
      ...List<Map<String, dynamic>?>.filled(trailingEmptyDays, null),
    ];
    final weekRows = <List<Map<String, dynamic>?>>[];
    for (var index = 0; index < calendarCells.length; index += 7) {
      weekRows.add(calendarCells.sublist(index, index + 7));
    }

    return AppSectionCard(
      padding: const EdgeInsets.fromLTRB(14, 14, 14, 14),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              const Expanded(
                child: Text(
                  'Presensi Bulan Ini',
                  style: TextStyle(
                    color: Colors.black,
                    fontSize: 16,
                    fontWeight: FontWeight.w800,
                  ),
                ),
              ),
              GestureDetector(
                onTap: onOpenMonthPicker,
                child: Container(
                  padding:
                      const EdgeInsets.symmetric(horizontal: 10, vertical: 7),
                  decoration: BoxDecoration(
                    color: const Color(0xFFF3F7F5),
                    borderRadius: BorderRadius.circular(999),
                  ),
                  child: Row(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      Text(
                        monthLabel,
                        style: const TextStyle(
                          color: Colors.black,
                          fontSize: 10,
                          fontWeight: FontWeight.w700,
                        ),
                      ),
                      const SizedBox(width: 4),
                      const Icon(
                        Icons.keyboard_arrow_down_rounded,
                        size: 14,
                        color: Color(0xFF1F6B52),
                      ),
                    ],
                  ),
                ),
              ),
            ],
          ),
          const SizedBox(height: 12),
          const Wrap(
            spacing: 6,
            runSpacing: 6,
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
          const SizedBox(height: 14),
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 10),
            decoration: BoxDecoration(
              color: const Color(0xFFF7FAF8),
              borderRadius: BorderRadius.circular(16),
              border: Border.all(color: const Color(0xFFE4EEEA)),
            ),
            child: const Row(
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
          ),
          const SizedBox(height: 10),
          if (items.isEmpty)
            const AppEmptyState(
              title: 'Kalender belum tersedia',
              message: 'Data kalender presensi bulan ini belum dapat dimuat.',
              icon: Icons.calendar_today_outlined,
            )
          else
            Column(
              children: [
                for (var rowIndex = 0; rowIndex < weekRows.length; rowIndex++) ...[
                  Row(
                    children: [
                      for (var cellIndex = 0;
                          cellIndex < weekRows[rowIndex].length;
                          cellIndex++) ...[
                        Expanded(
                          child: AspectRatio(
                            aspectRatio: 0.82,
                            child: weekRows[rowIndex][cellIndex] == null
                                ? const SizedBox.shrink()
                                : _CalendarDayTile(
                                    item: weekRows[rowIndex][cellIndex]!,
                                  ),
                          ),
                        ),
                        if (cellIndex < weekRows[rowIndex].length - 1)
                          const SizedBox(width: 6),
                      ],
                    ],
                  ),
                  if (rowIndex < weekRows.length - 1) const SizedBox(height: 6),
                ],
              ],
            ),
          const SizedBox(height: 14),
          const Text(
            'Tanggal Merah',
            style: TextStyle(
              color: Colors.black,
              fontSize: 14,
              fontWeight: FontWeight.w800,
            ),
          ),
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
                padding: const EdgeInsets.all(12),
                decoration: BoxDecoration(
                  color: const Color(0xFFFFF7F6),
                  borderRadius: BorderRadius.circular(16),
                  border: Border.all(
                    color: const Color(0xFFF3D4CF),
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
                          fontWeight: FontWeight.w700,
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
                              color: Colors.black,
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
      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 6),
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
              fontSize: 10,
              fontWeight: FontWeight.w700,
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
          color: Color(0xFF667774),
          fontSize: 10,
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
    final backgroundColor = isHoliday
        ? const Color(0xFFFFF5F4)
        : isToday
            ? const Color(0xFFF1F8F5)
            : const Color(0xFFF8FBF9);
    final borderColor = isHoliday
        ? const Color(0xFFF1C9C2)
        : isToday
            ? const Color(0xFF1F6B52)
            : const Color(0xFFE1EBE7);

    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 4, vertical: 7),
      decoration: BoxDecoration(
        color: backgroundColor,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(
          color: borderColor,
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
                        ? const Color(0xFF1F6B52)
                        : const Color(0xFF25403B),
                fontSize: 13,
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
      padding: const EdgeInsets.fromLTRB(8, 10, 8, 10),
      decoration: BoxDecoration(
        color: const Color(0xFFF9FCFA),
        borderRadius: BorderRadius.circular(16),
        border: Border.all(
          color: accent.withOpacity(0.18),
        ),
      ),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        mainAxisSize: MainAxisSize.min,
        crossAxisAlignment: CrossAxisAlignment.center,
        children: [
          Container(
            width: 24,
            height: 24,
            decoration: BoxDecoration(
              color: accent.withOpacity(0.12),
              shape: BoxShape.circle,
            ),
            child: Icon(icon, color: accent, size: 13),
          ),
          const SizedBox(height: 4),
          FittedBox(
            fit: BoxFit.scaleDown,
            child: Text(
              value,
              textAlign: TextAlign.center,
              style: const TextStyle(
                color: Colors.black,
                fontSize: 12,
                fontWeight: FontWeight.w800,
                height: 1,
              ),
            ),
          ),
          const SizedBox(height: 2),
          Text(
            label == 'Kehadiran' ? 'Hadir' : label,
            maxLines: 1,
            overflow: TextOverflow.ellipsis,
            textAlign: TextAlign.center,
            style: const TextStyle(
              color: Color(0xFF5F706B),
              fontSize: 8.5,
              fontWeight: FontWeight.w700,
              height: 1.1,
            ),
          ),
        ],
      ),
    );
  }
}

class _DashboardServiceSection {
  const _DashboardServiceSection({
    required this.title,
    required this.items,
  });

  final String title;
  final List<_DashboardServiceItem> items;
}

class _DashboardServiceItem {
  const _DashboardServiceItem({
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
}

List<_DashboardServiceSection> _buildServiceSections({
  required Map<String, dynamic> permissions,
  required Map<String, dynamic> summary,
  required ValueChanged<int> onSelectTab,
  required Future<void> Function() onOpenIzin,
  required Future<void> Function() onOpenManageIzin,
  required Future<void> Function() onOpenReports,
  required Future<void> Function() onOpenStaffAttendance,
  required VoidCallback onOpenSettings,
}) {
  final primaryItems = <_DashboardServiceItem>[
    _DashboardServiceItem(
      label: 'Presensi',
      colors: const [Color(0xFF0D8E89), Color(0xFF004B48)],
      icon: Icons.fact_check_rounded,
      onTap: () => onSelectTab(2),
    ),
    _DashboardServiceItem(
      label: 'Mengajar',
      colors: const [Color(0xFF4D8D74), Color(0xFF215344)],
      icon: Icons.cast_for_education_rounded,
      onTap: () => onSelectTab(3),
    ),
    _DashboardServiceItem(
      label: 'Jadwal',
      colors: const [Color(0xFF74B3FF), Color(0xFF3C6FD1)],
      icon: Icons.calendar_month_rounded,
      onTap: () => onSelectTab(1),
    ),
  ];
  final administrationItems = <_DashboardServiceItem>[
    _DashboardServiceItem(
      label: 'Daftar Izin',
      colors: const [Color(0xFF3A9B78), Color(0xFF1F6B52)],
      icon: Icons.assignment_turned_in_rounded,
      onTap: () {
        onOpenIzin();
      },
    ),
    _DashboardServiceItem(
      label: 'Laporan',
      colors: const [Color(0xFF68A98A), Color(0xFF2C6C56)],
      icon: Icons.summarize_rounded,
      onTap: () {
        onOpenReports();
      },
    ),
    _DashboardServiceItem(
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
  ];
  if (permissions['can_manage_izin'] == true) {
    administrationItems.insert(
      1,
      _DashboardServiceItem(
        label: 'Kelola Izin',
        colors: const [Color(0xFF2E7D61), Color(0xFF174C3D)],
        icon: Icons.approval_rounded,
        badgeText: '${summary['pending_approval_izin_count'] ?? 0}',
        onTap: () {
          onOpenManageIzin();
        },
      ),
    );
  }

  final accountItems = <_DashboardServiceItem>[
    _DashboardServiceItem(
      label: 'Profil',
      colors: const [Color(0xFF8E7DF2), Color(0xFF5B49B6)],
      icon: Icons.person_rounded,
      onTap: () => onSelectTab(4),
    ),
    _DashboardServiceItem(
      label: 'Pengaturan',
      colors: const [Color(0xFF7BC7B2), Color(0xFF2C8B76)],
      icon: Icons.settings_rounded,
      onTap: onOpenSettings,
    ),
  ];

  return [
    _DashboardServiceSection(
      title: 'Menu Utama',
      items: primaryItems,
    ),
    _DashboardServiceSection(
      title: 'Administrasi',
      items: administrationItems,
    ),
    _DashboardServiceSection(
      title: 'Akun',
      items: accountItems,
    ),
  ];
}

class _AllServicesPage extends StatelessWidget {
  const _AllServicesPage({
    required this.sections,
  });

  final List<_DashboardServiceSection> sections;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF7FAF8),
      appBar: AppBar(
        elevation: 0,
        backgroundColor: Colors.white,
        foregroundColor: Colors.black,
        title: const Text(
          'Semua Layanan',
          style: TextStyle(
            fontSize: 18,
            fontWeight: FontWeight.w800,
            color: Colors.black,
          ),
        ),
      ),
      body: ListView.separated(
        padding: const EdgeInsets.fromLTRB(16, 16, 16, 24),
        itemCount: sections.length,
        separatorBuilder: (_, __) => const SizedBox(height: 16),
        itemBuilder: (context, index) {
          final section = sections[index];

          return AppSectionCard(
            padding: const EdgeInsets.fromLTRB(14, 14, 14, 16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  section.title,
                  style: const TextStyle(
                    color: Colors.black,
                    fontSize: 15,
                    fontWeight: FontWeight.w800,
                  ),
                ),
                const SizedBox(height: 14),
                GridView.builder(
                  shrinkWrap: true,
                  physics: const NeverScrollableScrollPhysics(),
                  itemCount: section.items.length,
                  gridDelegate:
                      const SliverGridDelegateWithFixedCrossAxisCount(
                    crossAxisCount: 4,
                    crossAxisSpacing: 10,
                    mainAxisSpacing: 16,
                    childAspectRatio: 0.85,
                  ),
                  itemBuilder: (context, itemIndex) {
                    final item = section.items[itemIndex];

                    return _ServiceShortcutTile(
                      label: item.label,
                      colors: item.colors,
                      icon: item.icon,
                      badgeText: item.badgeText,
                      onTap: () {
                        Navigator.of(context).pop();
                        Future<void>.microtask(item.onTap);
                      },
                    );
                  },
                ),
              ],
            ),
          );
        },
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
    final accent = colors.first;

    return GestureDetector(
      onTap: onTap,
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Stack(
            clipBehavior: Clip.none,
            children: [
              Container(
                width: 58,
                height: 58,
                decoration: BoxDecoration(
                  color: accent.withOpacity(0.12),
                  shape: BoxShape.circle,
                ),
                child: Icon(
                  icon,
                  size: 24,
                  color: accent,
                ),
              ),
              if (badgeText != null)
                Positioned(
                  top: -2,
                  right: -2,
                  child: Container(
                    constraints: const BoxConstraints(minWidth: 18),
                    padding: const EdgeInsets.symmetric(
                      horizontal: 5,
                      vertical: 2,
                    ),
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(999),
                      border: Border.all(color: const Color(0xFFE2ECE9)),
                    ),
                    child: Text(
                      badgeText!,
                      textAlign: TextAlign.center,
                      style: TextStyle(
                        color: accent,
                        fontSize: 9,
                        fontWeight: FontWeight.w800,
                      ),
                    ),
                  ),
                ),
            ],
          ),
          const SizedBox(height: 10),
          Text(
            label,
            textAlign: TextAlign.center,
            style: const TextStyle(
              fontSize: 11,
              fontWeight: FontWeight.w700,
              color: Color(0xFF233432),
            ),
            maxLines: 2,
            overflow: TextOverflow.ellipsis,
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
    final accentColor = isCompleted
        ? const Color(0xFF1F9D73)
        : (isExcused ? const Color(0xFF2D7DA8) : const Color(0xFF1F6B52));
    final softColor = isCompleted
        ? const Color(0xFFEAF8EF)
        : (isExcused ? const Color(0xFFEAF5FB) : const Color(0xFFF1F7F4));
    final statusLabel = isCompleted
        ? 'Selesai'
        : (isExcused ? 'Izin' : 'Belum Presensi');

    return Container(
      width: 224,
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(22),
        border: Border.all(
          color: accentColor.withOpacity(0.18),
        ),
        boxShadow: const [
          BoxShadow(
            color: Color(0x0F003B39),
            blurRadius: 18,
            offset: Offset(0, 8),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Container(
                padding: const EdgeInsets.symmetric(
                  horizontal: 10,
                  vertical: 6,
                ),
                decoration: BoxDecoration(
                  color: softColor,
                  borderRadius: BorderRadius.circular(999),
                ),
                child: Text(
                  '${item['start_time'] ?? '-'} - ${item['end_time'] ?? '-'}',
                  style: TextStyle(
                    color: accentColor,
                    fontSize: 10,
                    fontWeight: FontWeight.w800,
                  ),
                ),
              ),
              const Spacer(),
              Container(
                width: 10,
                height: 10,
                decoration: BoxDecoration(
                  color: accentColor,
                  shape: BoxShape.circle,
                ),
              ),
            ],
          ),
          const SizedBox(height: 14),
          Text(
            item['subject'] as String? ?? '-',
            maxLines: 2,
            overflow: TextOverflow.ellipsis,
            style: const TextStyle(
              color: Colors.black,
              fontSize: 16,
              fontWeight: FontWeight.w800,
              height: 1.15,
            ),
          ),
          const SizedBox(height: 8),
          Text(
            item['class_name'] as String? ?? '-',
            maxLines: 1,
            overflow: TextOverflow.ellipsis,
            style: const TextStyle(
              color: Color(0xFF516360),
              fontSize: 12,
              fontWeight: FontWeight.w700,
            ),
          ),
          if ((item['school_name'] as String?)?.trim().isNotEmpty == true) ...[
            const SizedBox(height: 4),
            Text(
              item['school_name'] as String,
              maxLines: 1,
              overflow: TextOverflow.ellipsis,
              style: const TextStyle(
                color: Color(0xFF82918E),
                fontSize: 11,
                fontWeight: FontWeight.w600,
              ),
            ),
          ],
          const Spacer(),
          Container(
            width: double.infinity,
            padding: const EdgeInsets.symmetric(
              horizontal: 10,
              vertical: 9,
            ),
            decoration: BoxDecoration(
              color: softColor,
              borderRadius: BorderRadius.circular(16),
            ),
            child: Row(
              children: [
                Icon(
                  Icons.school_rounded,
                  size: 16,
                  color: accentColor,
                ),
                const SizedBox(width: 8),
                Expanded(
                  child: Text(
                    statusLabel,
                    style: TextStyle(
                      color: accentColor,
                      fontSize: 11,
                      fontWeight: FontWeight.w800,
                    ),
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

String _initialsFromName(String value) {
  final parts = value
      .trim()
      .split(RegExp(r'\s+'))
      .where((part) => part.isNotEmpty)
      .toList();

  if (parts.isEmpty) {
    return 'U';
  }

  final buffer = StringBuffer(parts.first[0].toUpperCase());
  if (parts.length > 1) {
    buffer.write(parts.last[0].toUpperCase());
  }
  return buffer.toString();
}

String? _normalizedAvatarUrl(String? url) {
  final value = url?.trim() ?? '';
  if (value.isEmpty) {
    return null;
  }
  return value;
}

String _monthKeyFromDate(DateTime value) {
  return '${value.year.toString().padLeft(4, '0')}-${value.month.toString().padLeft(2, '0')}';
}

String _monthLabelFullId(DateTime value) {
  const monthNames = <String>[
    'Januari',
    'Februari',
    'Maret',
    'April',
    'Mei',
    'Juni',
    'Juli',
    'Agustus',
    'September',
    'Oktober',
    'November',
    'Desember',
  ];

  return '${monthNames[value.month - 1]} ${value.year}';
}

String _monthLabelShortId(int month) {
  const monthNames = <String>[
    'Jan',
    'Feb',
    'Mar',
    'Apr',
    'Mei',
    'Jun',
    'Jul',
    'Agu',
    'Sep',
    'Okt',
    'Nov',
    'Des',
  ];

  return monthNames[month - 1];
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
