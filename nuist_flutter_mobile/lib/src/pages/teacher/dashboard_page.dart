import 'dart:async';

import 'package:flutter/material.dart';

import '../../services/teacher_mobile_repository.dart';
import '../../widgets/app/app_empty_state.dart';
import '../../widgets/app/app_section_card.dart';

class _DashboardPalette {
  static const background = Color(0xFFFFFFFF);
  static const surface = Color(0xFFFFFFFF);
  static const primary = Color(0xFF00745A);
  static const primaryDark = Color(0xFF00553F);
  static const secondaryGreen = Color(0xFF00745A);
  static const accent = Color(0xFFF59E0B);
  static const textPrimary = Color(0xFF172A24);
  static const textSecondary = Color(0xFF172A24);
  static const border = Color(0xFFDCE7E3);
  static const success = Color(0xFF00745A);
  static const warning = Color(0xFFF59E0B);
  static const danger = Color(0xFFEF4444);
  static const iconSurface = Color(0xFFE5F5F0);
  static const softGreen = Color(0xFFE5F5F0);
  static const softGreenAlt = Color(0xFFE5F5F0);
  static const softYellow = Color(0xFFFEF3C7);
  static const softRed = Color(0xFFFEE2E2);
  static const cardShadow = Color(0x14172A24);

  const _DashboardPalette._();
}

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
                        color: _DashboardPalette.textPrimary,
                        fontSize: 18,
                        fontWeight: FontWeight.w800,
                      ),
                    ),
                    const SizedBox(height: 14),
                    Container(
                      width: double.infinity,
                      padding: const EdgeInsets.symmetric(horizontal: 14),
                      decoration: BoxDecoration(
                        color: _DashboardPalette.background,
                        borderRadius: BorderRadius.circular(16),
                        border: Border.all(color: _DashboardPalette.border),
                      ),
                      child: DropdownButtonHideUnderline(
                        child: DropdownButton<int>(
                          value: pickerYear,
                          isExpanded: true,
                          icon: const Icon(
                            Icons.keyboard_arrow_down_rounded,
                            color: _DashboardPalette.primaryDark,
                          ),
                          items: yearOptions
                              .map(
                                (year) => DropdownMenuItem<int>(
                                  value: year,
                                  child: Text(
                                    year.toString(),
                                    style: const TextStyle(
                                      color: _DashboardPalette.textPrimary,
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
                            width: (MediaQuery.of(context).size.width - 56) / 3,
                            padding: const EdgeInsets.symmetric(
                              horizontal: 12,
                              vertical: 12,
                            ),
                            decoration: BoxDecoration(
                              color: isSelected
                                  ? _DashboardPalette.iconSurface
                                  : _DashboardPalette.surface,
                              borderRadius: BorderRadius.circular(16),
                              border: Border.all(
                                color: isSelected
                                    ? _DashboardPalette.primaryDark
                                    : _DashboardPalette.border,
                              ),
                            ),
                            child: Center(
                              child: Text(
                                _monthLabelShortId(month),
                                style: TextStyle(
                                  color: isSelected
                                      ? _DashboardPalette.primaryDark
                                      : _DashboardPalette.textPrimary,
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
                    color: _hasScrolled ? Colors.white : Colors.transparent,
                    boxShadow: _hasScrolled
                        ? const [
                            BoxShadow(
                              color: _DashboardPalette.cardShadow,
                              blurRadius: 20,
                              offset: Offset(0, 8),
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
    final hariKbm = (monthlyStats['hari_kbm'] as num?)?.toInt() ?? 6;
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
                level:
                    (performance['level'] as String?) ?? 'Belum Ada Progress',
                percent: (performance['percent'] as num?)?.toInt() ?? 0,
              ),
              const SizedBox(height: 14),
              Row(
                children: [
                  const Expanded(
                    child: Text(
                      'Aktivitas Presensi',
                      style: TextStyle(
                        color: _DashboardPalette.textPrimary,
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
                      color: _DashboardPalette.iconSurface,
                      borderRadius: BorderRadius.circular(999),
                      border: Border.all(color: _DashboardPalette.border),
                    ),
                    child: Text(
                      currentMonthLabel,
                      style: const TextStyle(
                        color: _DashboardPalette.textPrimary,
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
                        iconSurface: _DashboardPalette.softGreen,
                        iconColor: _DashboardPalette.success,
                        icon: Icons.trending_up_rounded,
                      ),
                    ),
                    const SizedBox(width: 8),
                    Expanded(
                      child: _MonthlyStatTile(
                        label: 'Presensi',
                        value: '${monthlyStats['present_count'] ?? 0}',
                        iconSurface: _DashboardPalette.softGreenAlt,
                        iconColor: _DashboardPalette.secondaryGreen,
                        icon: Icons.check_circle_rounded,
                      ),
                    ),
                    const SizedBox(width: 8),
                    Expanded(
                      child: _MonthlyStatTile(
                        label: 'Izin',
                        value: '${monthlyStats['izin_count'] ?? 0}',
                        iconSurface: _DashboardPalette.softYellow,
                        iconColor: _DashboardPalette.warning,
                        icon: Icons.schedule_rounded,
                      ),
                    ),
                    const SizedBox(width: 8),
                    Expanded(
                      child: _MonthlyStatTile(
                        label: 'Alpha',
                        value: '${monthlyStats['alpha_count'] ?? 0}',
                        iconSurface: _DashboardPalette.softRed,
                        iconColor: _DashboardPalette.danger,
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
                  color: _DashboardPalette.textPrimary,
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
                  color: _DashboardPalette.primary,
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
                  color: _DashboardPalette.textPrimary,
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
                  color: _DashboardPalette.primary,
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
                  color: _DashboardPalette.textPrimary,
                  fontSize: 11,
                  fontWeight: FontWeight.w800,
                  letterSpacing: 0.4,
                ),
              ),
            ),
            Text(
              currentMonthLabel,
              style: const TextStyle(
                color: _DashboardPalette.textSecondary,
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
          hariKbm: hariKbm,
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
    return SizedBox(
      height: 288,
      child: ClipRRect(
        child: CustomPaint(
          painter: _DashboardPremiumHeaderBackground(),
          child: Stack(
            clipBehavior: Clip.none,
            children: [
              Positioned(
                right: -70,
                top: -90,
                child: Container(
                  width: 240,
                  height: 240,
                  decoration: BoxDecoration(
                    shape: BoxShape.circle,
                    color: Colors.white.withValues(alpha: 0.035),
                  ),
                ),
              ),
              Positioned(
                right: 34,
                top: 92,
                child: Container(
                  width: 7,
                  height: 7,
                  decoration: BoxDecoration(
                    shape: BoxShape.circle,
                    color: Colors.white.withValues(alpha: 0.12),
                  ),
                ),
              ),
              Positioned(
                right: 50,
                top: 112,
                child: Container(
                  width: 4,
                  height: 4,
                  decoration: BoxDecoration(
                    shape: BoxShape.circle,
                    color: Colors.white.withValues(alpha: 0.08),
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

class _DashboardPremiumHeaderBackground extends CustomPainter {
  @override
  void paint(Canvas canvas, Size size) {
    final basePaint = Paint()..color = const Color(0xFF004838);
    canvas.drawRect(Offset.zero & size, basePaint);

    final darkTopPaint = Paint()
      ..color = const Color(0xFF002F25).withValues(alpha: 0.72);
    final darkTopPath = Path()
      ..moveTo(size.width * 0.28, 0)
      ..lineTo(size.width, 0)
      ..lineTo(size.width, size.height * 0.42)
      ..quadraticBezierTo(
        size.width * 0.80,
        size.height * 0.25,
        size.width * 0.55,
        size.height * 0.13,
      )
      ..quadraticBezierTo(
        size.width * 0.39,
        size.height * 0.05,
        size.width * 0.28,
        0,
      )
      ..close();
    canvas.drawPath(darkTopPath, darkTopPaint);

    final diagonalPaint = Paint()
      ..color = const Color(0xFF0B6650).withValues(alpha: 0.24);
    final diagonalPath = Path()
      ..moveTo(size.width * 0.16, 0)
      ..quadraticBezierTo(
        size.width * 0.47,
        size.height * 0.10,
        size.width,
        size.height * 0.40,
      )
      ..lineTo(size.width, size.height * 0.57)
      ..quadraticBezierTo(
        size.width * 0.58,
        size.height * 0.22,
        size.width * 0.16,
        size.height * 0.06,
      )
      ..close();
    canvas.drawPath(diagonalPath, diagonalPaint);

    final lightDiagonalPaint = Paint()
      ..color = Colors.white.withValues(alpha: 0.025);
    final lightDiagonalPath = Path()
      ..moveTo(0, size.height * 0.02)
      ..quadraticBezierTo(
        size.width * 0.40,
        size.height * 0.20,
        size.width,
        size.height * 0.06,
      )
      ..lineTo(size.width, size.height * 0.15)
      ..quadraticBezierTo(
        size.width * 0.48,
        size.height * 0.30,
        0,
        size.height * 0.10,
      )
      ..close();
    canvas.drawPath(lightDiagonalPath, lightDiagonalPaint);

    final bottomPaint = Paint()
      ..color = const Color(0xFF006E53).withValues(alpha: 0.22);
    final bottomPath = Path()
      ..moveTo(0, size.height * 0.70)
      ..quadraticBezierTo(
        size.width * 0.18,
        size.height * 0.54,
        size.width * 0.42,
        size.height * 0.69,
      )
      ..quadraticBezierTo(
        size.width * 0.72,
        size.height * 0.89,
        size.width,
        size.height * 0.62,
      )
      ..lineTo(size.width, size.height)
      ..lineTo(0, size.height)
      ..close();
    canvas.drawPath(bottomPath, bottomPaint);
  }

  @override
  bool shouldRepaint(covariant CustomPainter oldDelegate) => false;
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
        isScrolled ? _DashboardPalette.textPrimary : Colors.white;
    final iconColor = isScrolled
        ? _DashboardPalette.primaryDark
        : Colors.white.withValues(alpha: 0.9);

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
                        color: isScrolled ? foregroundColor : Colors.white70,
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

    return Container(
      width: 52,
      height: 52,
      padding: const EdgeInsets.all(2),
      decoration: BoxDecoration(
        shape: BoxShape.circle,
        color: Colors.white,
        border: Border.all(color: Colors.white, width: 1.5),
        boxShadow: const [
          BoxShadow(
            color: _DashboardPalette.cardShadow,
            blurRadius: 14,
            offset: Offset(0, 6),
          ),
        ],
      ),
      child: ClipOval(
        child: SizedBox(
          width: 48,
          height: 48,
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
          colors: [
            _DashboardPalette.primary,
            _DashboardPalette.primaryDark,
          ],
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
    final color = isDestructive
        ? _DashboardPalette.danger
        : _DashboardPalette.primaryDark;

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
        gradient: const LinearGradient(
          colors: [
            Color(0xFF004838),
            Color(0xFF002F25),
          ],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
        borderRadius: BorderRadius.circular(24),
        boxShadow: const [
          BoxShadow(
            color: _DashboardPalette.cardShadow,
            blurRadius: 20,
            offset: Offset(0, 10),
          ),
        ],
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
                  color: Colors.white.withValues(alpha: 0.9),
                  fontSize: 11,
                  fontWeight: FontWeight.w700,
                ),
              ),
              const Spacer(),
              TweenAnimationBuilder<int>(
                tween: IntTween(begin: 0, end: percent),
                duration: const Duration(milliseconds: 900),
                curve: Curves.easeOutCubic,
                builder: (context, value, _) {
                  return Text(
                    '$value%',
                    style: const TextStyle(
                      color: Colors.white,
                      fontSize: 22,
                      fontWeight: FontWeight.w800,
                      height: 1,
                    ),
                  );
                },
              ),
            ],
          ),
          const SizedBox(height: 14),
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
            child: TweenAnimationBuilder<double>(
              tween: Tween<double>(begin: 0, end: progress.toDouble()),
              duration: const Duration(milliseconds: 1000),
              curve: Curves.easeOutCubic,
              builder: (context, animatedValue, _) {
                return LinearProgressIndicator(
                  minHeight: 8,
                  value: animatedValue,
                  backgroundColor: const Color(0x1AFFFFFF),
                  valueColor: const AlwaysStoppedAnimation<Color>(
                    Color(0xFFF8D77A),
                  ),
                );
              },
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
    required this.hariKbm,
    required this.onOpenMonthPicker,
  });

  final String monthLabel;
  final int leadingEmptyDays;
  final List<Map<String, dynamic>> items;
  final List<Map<String, dynamic>> holidayNotes;
  final int hariKbm;
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
                    color: _DashboardPalette.textPrimary,
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
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(999),
                    border: Border.all(color: _DashboardPalette.border),
                  ),
                  child: Row(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      Text(
                        monthLabel,
                        style: const TextStyle(
                          color: _DashboardPalette.textPrimary,
                          fontSize: 10,
                          fontWeight: FontWeight.w700,
                        ),
                      ),
                      const SizedBox(width: 4),
                      const Icon(
                        Icons.keyboard_arrow_down_rounded,
                        size: 14,
                        color: _DashboardPalette.primary,
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
                color: _DashboardPalette.success,
              ),
              _CalendarLegendChip(
                label: 'Izin',
                color: _DashboardPalette.warning,
              ),
              _CalendarLegendChip(
                label: 'Alpha',
                color: _DashboardPalette.danger,
              ),
              _CalendarLegendChip(
                label: 'Tanggal Merah',
                color: _DashboardPalette.accent,
              ),
              _CalendarLegendChip(
                label: 'Libur',
                color: _DashboardPalette.textSecondary,
              ),
            ],
          ),
          const SizedBox(height: 14),
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 10),
            decoration: BoxDecoration(
              color: const Color(0xFFFFFFFF),
              borderRadius: BorderRadius.circular(16),
              border: Border.all(color: _DashboardPalette.border),
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
                for (var rowIndex = 0;
                    rowIndex < weekRows.length;
                    rowIndex++) ...[
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
                                    hariKbm: hariKbm,
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
              color: _DashboardPalette.textPrimary,
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
                  color: const Color(0xFFFFFBF1),
                  borderRadius: BorderRadius.circular(16),
                  border: Border.all(
                    color: _DashboardPalette.softYellow,
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
                        color: _DashboardPalette.softYellow,
                        borderRadius: BorderRadius.circular(999),
                      ),
                      child: Text(
                        item['date_label'] as String? ?? '-',
                        style: const TextStyle(
                          color: _DashboardPalette.warning,
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
                              color: _DashboardPalette.textPrimary,
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
                                color: _DashboardPalette.textSecondary,
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
        color: color.withValues(alpha: 0.1),
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
          color: _DashboardPalette.textSecondary,
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
    required this.hariKbm,
  });

  final Map<String, dynamic> item;
  final int hariKbm;

  @override
  Widget build(BuildContext context) {
    final status = item['status'] as String? ?? 'belum_tercatat';
    final isToday = item['is_today'] == true;
    final isHoliday = status == 'tanggal_merah';
    final weekdayShort = item['weekday_short'] as String? ?? '';
    final isSunday = weekdayShort == 'Min';
    final isSaturday = hariKbm == 5 && weekdayShort == 'Sab';
    final isBlackDay = isSunday || isSaturday;
    final isMissedAttendance = status == 'alpha' || status == 'belum_tercatat';
    final color = isBlackDay ? Colors.black87 : _calendarStatusColor(status);
    final backgroundColor = isHoliday
        ? const Color(0xFFFFFBF1)
        : (isMissedAttendance
            ? const Color(0xFFFFFFFF)
            : _DashboardPalette.surface);
    final borderColor = isToday
        ? _DashboardPalette.primary
        : (isHoliday
            ? _DashboardPalette.warning.withValues(alpha: 0.28)
            : (isBlackDay
                ? const Color(0xFFD1D5DB)
                : (isMissedAttendance
                    ? const Color(0xFFFECACA)
                    : _DashboardPalette.border)));

    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 4, vertical: 7),
      decoration: BoxDecoration(
        color: backgroundColor,
        borderRadius: BorderRadius.circular(16),
        boxShadow: const [
          BoxShadow(
            color: _DashboardPalette.cardShadow,
            blurRadius: 12,
            offset: Offset(0, 4),
          ),
        ],
        border: Border.all(
          color: isBlackDay ? const Color(0xFFD1D5DB) : borderColor,
          width: isToday ? 1.5 : 1,
        ),
      ),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.spaceEvenly,
        children: [
          FittedBox(
            fit: BoxFit.scaleDown,
            child: Container(
              padding: isToday
                  ? const EdgeInsets.symmetric(horizontal: 8, vertical: 4)
                  : EdgeInsets.zero,
              decoration: BoxDecoration(
                color: isToday ? _DashboardPalette.primary : Colors.transparent,
                shape: isToday ? BoxShape.circle : BoxShape.rectangle,
              ),
              child: Text(
                '${item['day_number'] ?? '-'}',
                style: TextStyle(
                  color: isToday
                      ? Colors.white
                      : (isHoliday
                          ? _DashboardPalette.warning
                          : (isBlackDay
                              ? Colors.black87
                              : (isMissedAttendance
                                  ? _DashboardPalette.danger
                                  : _DashboardPalette.textPrimary))),
                  fontSize: 13,
                  fontWeight: FontWeight.w800,
                ),
              ),
            ),
          ),
          Container(
            width: 8,
            height: 8,
            decoration: BoxDecoration(
              color: isBlackDay ? Colors.black87 : color,
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
    required this.iconSurface,
    required this.iconColor,
    required this.icon,
  });

  final String label;
  final String value;
  final Color iconSurface;
  final Color iconColor;
  final IconData icon;

  @override
  Widget build(BuildContext context) {
    final numericValue = _extractFirstInt(value);
    final suffix = value.replaceAll(RegExp(r'[\d\s]'), '');

    return Container(
      padding: const EdgeInsets.fromLTRB(8, 10, 8, 10),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: _DashboardPalette.border),
        boxShadow: const [
          BoxShadow(
            color: _DashboardPalette.cardShadow,
            blurRadius: 16,
            offset: Offset(0, 6),
          ),
        ],
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
              color: iconSurface,
              shape: BoxShape.circle,
            ),
            child: Icon(icon, color: iconColor, size: 13),
          ),
          const SizedBox(height: 4),
          TweenAnimationBuilder<int>(
            tween: IntTween(begin: 0, end: numericValue),
            duration: const Duration(milliseconds: 900),
            curve: Curves.easeOutCubic,
            builder: (context, animatedValue, _) {
              return FittedBox(
                fit: BoxFit.scaleDown,
                child: Text(
                  '$animatedValue$suffix',
                  textAlign: TextAlign.center,
                  style: const TextStyle(
                    color: _DashboardPalette.textPrimary,
                    fontSize: 12,
                    fontWeight: FontWeight.w800,
                    height: 1,
                  ),
                ),
              );
            },
          ),
          const SizedBox(height: 2),
          Text(
            label == 'Kehadiran' ? 'Hadir' : label,
            maxLines: 1,
            overflow: TextOverflow.ellipsis,
            textAlign: TextAlign.center,
            style: const TextStyle(
              color: _DashboardPalette.textSecondary,
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
      colors: const [Color(0xFF00745A), Color(0xFF172A24)],
      icon: Icons.fact_check_rounded,
      onTap: () => onSelectTab(2),
    ),
    _DashboardServiceItem(
      label: 'Mengajar',
      colors: const [Color(0xFF64746E), Color(0xFF172A24)],
      icon: Icons.cast_for_education_rounded,
      onTap: () => onSelectTab(3),
    ),
    _DashboardServiceItem(
      label: 'Jadwal',
      colors: const [Color(0xFF00553F), Color(0xFF00745A)],
      icon: Icons.calendar_month_rounded,
      onTap: () => onSelectTab(1),
    ),
  ];
  final administrationItems = <_DashboardServiceItem>[
    _DashboardServiceItem(
      label: 'Daftar Izin',
      colors: const [Color(0xFF00745A), Color(0xFF00553F)],
      icon: Icons.assignment_turned_in_rounded,
      onTap: () {
        onOpenIzin();
      },
    ),
    _DashboardServiceItem(
      label: 'Laporan',
      colors: const [Color(0xFF64746E), Color(0xFF172A24)],
      icon: Icons.summarize_rounded,
      onTap: () {
        onOpenReports();
      },
    ),
    _DashboardServiceItem(
      label: permissions['can_manage_izin'] == true
          ? 'Data Presensi'
          : 'Jadwal Hari Ini',
      colors: const [Color(0xFF00553F), Color(0xFF00745A)],
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
        colors: const [Color(0xFF00553F), Color(0xFF172A24)],
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
      colors: const [Color(0xFFF59E0B), Color(0xFFD97706)],
      icon: Icons.person_rounded,
      onTap: () => onSelectTab(4),
    ),
    _DashboardServiceItem(
      label: 'Pengaturan',
      colors: const [Color(0xFF64746E), Color(0xFF00745A)],
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
      backgroundColor: _DashboardPalette.background,
      appBar: AppBar(
        elevation: 0,
        backgroundColor: Colors.white,
        foregroundColor: _DashboardPalette.textPrimary,
        title: const Text(
          'Semua Layanan',
          style: TextStyle(
            fontSize: 18,
            fontWeight: FontWeight.w800,
            color: _DashboardPalette.textPrimary,
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
                    color: _DashboardPalette.textPrimary,
                    fontSize: 15,
                    fontWeight: FontWeight.w800,
                  ),
                ),
                const SizedBox(height: 14),
                GridView.builder(
                  shrinkWrap: true,
                  physics: const NeverScrollableScrollPhysics(),
                  itemCount: section.items.length,
                  gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
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
    return _AnimatedPressable(
      onTap: onTap,
      builder: (context, isPressed) {
        final accent = isPressed
            ? _DashboardPalette.primaryDark
            : _DashboardPalette.primary;

        return Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Stack(
              clipBehavior: Clip.none,
              children: [
                AnimatedContainer(
                  duration: const Duration(milliseconds: 160),
                  width: 58,
                  height: 58,
                  decoration: const BoxDecoration(
                    color: Colors.white,
                    shape: BoxShape.circle,
                    boxShadow: [
                      BoxShadow(
                        color: _DashboardPalette.cardShadow,
                        blurRadius: 12,
                        offset: Offset(0, 4),
                      ),
                    ],
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
                        border: Border.all(color: _DashboardPalette.border),
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
                color: _DashboardPalette.textPrimary,
              ),
              maxLines: 2,
              overflow: TextOverflow.ellipsis,
            ),
          ],
        );
      },
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
        ? _DashboardPalette.success
        : (isExcused ? _DashboardPalette.warning : _DashboardPalette.primary);
    final softColor = Colors.white.withValues(alpha: 0.14);
    final statusLabel =
        isCompleted ? 'Selesai' : (isExcused ? 'Izin' : 'Belum Presensi');

    return Container(
      width: 224,
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: const Color(0xFF004838),
        borderRadius: BorderRadius.circular(20),
        border: Border.all(
          color: const Color(0xFF0B6650).withValues(alpha: 0.5),
        ),
        boxShadow: const [
          BoxShadow(
            color: _DashboardPalette.cardShadow,
            blurRadius: 20,
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
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(999),
                ),
                child: Text(
                  '${item['start_time'] ?? '-'} - ${item['end_time'] ?? '-'}',
                  style: const TextStyle(
                    color: Color(0xFF004838),
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
              color: Colors.white,
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
            style: TextStyle(
              color: Colors.white.withValues(alpha: 0.88),
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
              style: TextStyle(
                color: Colors.white.withValues(alpha: 0.72),
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
                  Icons.task_alt_rounded,
                  size: 16,
                  color: Colors.white,
                ),
                const SizedBox(width: 8),
                Expanded(
                  child: Text(
                    statusLabel,
                    style: TextStyle(
                      color: Colors.white,
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

int _extractFirstInt(String value) {
  final match = RegExp(r'\d+').firstMatch(value);
  if (match == null) {
    return 0;
  }
  return int.tryParse(match.group(0)!) ?? 0;
}

class _AnimatedPressable extends StatefulWidget {
  const _AnimatedPressable({
    required this.builder,
    required this.onTap,
  });

  final Widget Function(BuildContext context, bool isPressed) builder;
  final VoidCallback onTap;

  @override
  State<_AnimatedPressable> createState() => _AnimatedPressableState();
}

class _AnimatedPressableState extends State<_AnimatedPressable> {
  bool _pressed = false;

  void _setPressed(bool value) {
    if (_pressed == value) {
      return;
    }
    setState(() {
      _pressed = value;
    });
  }

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: widget.onTap,
      onTapDown: (_) => _setPressed(true),
      onTapUp: (_) => _setPressed(false),
      onTapCancel: () => _setPressed(false),
      child: widget.builder(context, _pressed),
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
      return _DashboardPalette.accent;
    case 'hadir':
      return _DashboardPalette.success;
    case 'izin':
      return _DashboardPalette.warning;
    case 'alpha':
    case 'belum_tercatat':
      return _DashboardPalette.danger;
    case 'libur':
      return _DashboardPalette.textSecondary;
    case 'akan_datang':
      return _DashboardPalette.accent;
    default:
      return _DashboardPalette.accent;
  }
}
