import 'package:flutter/material.dart';

import '../../services/student_mobile_repository.dart';
import '../../theme/app_theme.dart';
import '../../widgets/app/app_section_card.dart';
import 'student_ui.dart';
import 'package:flutter/widget_previews.dart';

class StudentDashboardPage extends StatefulWidget {
  const StudentDashboardPage({
    super.key,
    required this.repository,
    required this.dataRevision,
    required this.onSelectTab,
    required this.onLogout,
  });

  final StudentMobileRepository repository;
  final int dataRevision;
  final ValueChanged<int> onSelectTab;
  final Future<void> Function() onLogout;

  @override
  State<StudentDashboardPage> createState() => _StudentDashboardPageState();
}

class _StudentDashboardPageState extends State<StudentDashboardPage> {
  final ScrollController _scrollController = ScrollController();

  bool _isLoading = true;
  bool _hasScrolled = false;
  String? _errorMessage;
  Map<String, dynamic> _data = const <String, dynamic>{};

  @override
  void initState() {
    super.initState();
    _scrollController.addListener(_handleScrollChanged);
    _load();
  }

  @override
  void dispose() {
    _scrollController
      ..removeListener(_handleScrollChanged)
      ..dispose();
    super.dispose();
  }

  @override
  void didUpdateWidget(covariant StudentDashboardPage oldWidget) {
    super.didUpdateWidget(oldWidget);
    if (oldWidget.dataRevision != widget.dataRevision) {
      _load();
    }
  }

  void _handleScrollChanged() {
    final hasScrolled =
        _scrollController.hasClients && _scrollController.offset > 28;
    if (hasScrolled == _hasScrolled || !mounted) {
      return;
    }

    setState(() {
      _hasScrolled = hasScrolled;
    });
  }

  Future<void> _load() async {
    if (!mounted) {
      return;
    }

    setState(() {
      _isLoading = true;
      _errorMessage = null;
    });

    try {
      final result = await widget.repository.getDashboard();
      if (!mounted) {
        return;
      }
      setState(() {
        _data = result;
      });
    } catch (error) {
      if (!mounted) {
        return;
      }
      setState(() {
        _errorMessage = error.toString();
      });
    } finally {
      if (mounted) {
        setState(() {
          _isLoading = false;
        });
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    if (_isLoading) {
      return const Center(child: CircularProgressIndicator());
    }

    if (_errorMessage != null) {
      return StudentErrorView(
        message: _errorMessage!,
        onRetry: _load,
      );
    }

    final student = Map<String, dynamic>.from(
      (_data['student'] as Map?) ?? const <String, dynamic>{},
    );
    final school = Map<String, dynamic>.from(
      (_data['school'] as Map?) ?? const <String, dynamic>{},
    );
    final summary = Map<String, dynamic>.from(
      (_data['summary'] as Map?) ?? const <String, dynamic>{},
    );
    final activeBill = (_data['active_bill'] as Map?) == null
        ? null
        : Map<String, dynamic>.from(_data['active_bill'] as Map);
    final reminder = (_data['upcoming_reminder'] as Map?) == null
        ? null
        : Map<String, dynamic>.from(_data['upcoming_reminder'] as Map);
    final recentPayments = ((_data['recent_payments'] as List?) ?? const [])
        .whereType<Map>()
        .map((item) => Map<String, dynamic>.from(item))
        .toList();

    final studentName = normalizeStudentText(student['name']);
    final schoolName = normalizeStudentText(school['name']);

    return RefreshIndicator(
      onRefresh: _load,
      child: Stack(
        children: [
          ListView(
            controller: _scrollController,
            physics: const AlwaysScrollableScrollPhysics(),
            padding: const EdgeInsets.only(bottom: 152),
            children: [
              const _StudentDashboardTopBackdrop(),
              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 16),
                child: Transform.translate(
                  offset: const Offset(0, -194),
                  child: _StudentDashboardContent(
                    student: student,
                    studentName: studentName,
                    schoolName: schoolName,
                    summary: summary,
                    activeBill: activeBill,
                    reminder: reminder,
                    recentPayments: recentPayments,
                    onSelectTab: widget.onSelectTab,
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
                          color: Color(0x14172A24),
                          blurRadius: 20,
                          offset: Offset(0, 8),
                        ),
                      ]
                    : const [],
              ),
              padding: const EdgeInsets.fromLTRB(16, 16, 16, 10),
              child: _StudentDashboardHeader(
                schoolName: schoolName,
                studentName: studentName,
                isScrolled: _hasScrolled,
                onOpenPayments: () => widget.onSelectTab(2),
                onOpenProfile: () => widget.onSelectTab(4),
                onLogout: widget.onLogout,
              ),
            ),
          ),
        ],
      ),
    );
  }
}

class _StudentDashboardContent extends StatelessWidget {
  const _StudentDashboardContent({
    required this.student,
    required this.studentName,
    required this.schoolName,
    required this.summary,
    required this.activeBill,
    required this.reminder,
    required this.recentPayments,
    required this.onSelectTab,
  });

  final Map<String, dynamic> student;
  final String studentName;
  final String schoolName;
  final Map<String, dynamic> summary;
  final Map<String, dynamic>? activeBill;
  final Map<String, dynamic>? reminder;
  final List<Map<String, dynamic>> recentPayments;
  final ValueChanged<int> onSelectTab;

  @override
  Widget build(BuildContext context) {
    final monthLabel = _studentDashboardMonthLabel(DateTime.now());
    final services = <_StudentServiceItem>[
      _StudentServiceItem(
        label: 'Tagihan',
        icon: Icons.receipt_long_rounded,
        onTap: () => onSelectTab(1),
      ),
      _StudentServiceItem(
        label: 'Bayar',
        icon: Icons.account_balance_wallet_rounded,
        onTap: () => onSelectTab(2),
      ),
      _StudentServiceItem(
        label: 'Riwayat',
        icon: Icons.history_rounded,
        onTap: () => onSelectTab(3),
      ),
      _StudentServiceItem(
        label: 'Profil',
        icon: Icons.person_rounded,
        onTap: () => onSelectTab(4),
      ),
    ];

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        AppSectionCard(
          padding: const EdgeInsets.fromLTRB(8, 8, 8, 8),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              _StudentPerformanceCard(
                percent:
                    (summary['payment_completion_rate'] as num?)?.toInt() ?? 0,
              ),
              const SizedBox(height: 12),
              Row(
                children: [
                  const Expanded(
                    child: Text(
                      'Aktivitas Pembayaran',
                      style: TextStyle(
                        color: Color(0xFF172A24),
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
                      color: const Color(0xFFE5F5F0),
                      borderRadius: BorderRadius.circular(999),
                      border: Border.all(color: const Color(0xFFDCE7E3)),
                    ),
                    child: Text(
                      monthLabel,
                      style: const TextStyle(
                        color: Color(0xFF172A24),
                        fontSize: 10,
                        fontWeight: FontWeight.w700,
                      ),
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 8),
              IntrinsicHeight(
                child: Row(
                  children: [
                    Expanded(
                      child: _StudentStatTile(
                        label: 'Lunas',
                        value: '${summary['paid_bills'] ?? 0}',
                        iconSurface: const Color(0xFFE5F5F0),
                        iconColor: const Color(0xFF00745A),
                        icon: Icons.trending_up_rounded,
                      ),
                    ),
                    const SizedBox(width: 8),
                    Expanded(
                      child: _StudentStatTile(
                        label: 'Tagihan',
                        value: '${summary['total_bills'] ?? 0}',
                        iconSurface: const Color(0xFFE5F5F0),
                        iconColor: const Color(0xFF00745A),
                        icon: Icons.check_circle_rounded,
                      ),
                    ),
                    const SizedBox(width: 8),
                    Expanded(
                      child: _StudentStatTile(
                        label: 'Pending',
                        value: '${summary['pending_payments'] ?? 0}',
                        iconSurface: const Color(0xFFFFF4D6),
                        iconColor: const Color(0xFFF59E0B),
                        icon: Icons.schedule_rounded,
                      ),
                    ),
                    const SizedBox(width: 8),
                    Expanded(
                      child: _StudentStatTile(
                        label: 'Sisa',
                        value: '${summary['unpaid_bills'] ?? 0}',
                        iconSurface: const Color(0xFFFFDDE0),
                        iconColor: const Color(0xFFEF4444),
                        icon: Icons.cancel_rounded,
                      ),
                    ),
                  ],
                ),
              ),
            ],
          ),
        ),
        const SizedBox(height: 16),
        _StudentSectionHeader(
          title: 'Layanan',
          onTap: () => onSelectTab(4),
        ),
        const SizedBox(height: 8),
        SizedBox(
          height: 94,
          child: ListView.separated(
            scrollDirection: Axis.horizontal,
            physics: const BouncingScrollPhysics(),
            itemCount: services.length,
            separatorBuilder: (_, __) => const SizedBox(width: 16),
            itemBuilder: (context, index) {
              final item = services[index];
              return SizedBox(
                width: 72,
                child: _StudentServiceShortcutTile(item: item),
              );
            },
          ),
        ),
        const SizedBox(height: 16),
        _StudentSectionHeader(
          title: 'Tagihan',
          onTap: activeBill == null ? null : () => onSelectTab(1),
        ),
        const SizedBox(height: 8),
        if (activeBill == null)
          Padding(
            padding: EdgeInsets.symmetric(horizontal: 4),
            child: _StudentCompactEmptyState(
              title: 'Tidak ada tagihan aktif',
              message: 'Semua tagihan sudah lunas atau belum ada tagihan.',
              icon: Icons.receipt_long_rounded,
            ),
          )
        else
          SizedBox(
            height: 164,
            child: ListView(
              scrollDirection: Axis.horizontal,
              children: [
                _StudentBillShowcaseCard(
                  item: activeBill!,
                  schoolName: schoolName,
                ),
                if (reminder != null) ...[
                  const SizedBox(width: 12),
                  _StudentReminderCard(item: reminder!),
                ],
              ],
            ),
          ),
        const SizedBox(height: 16),
        _StudentSectionHeader(
          title: 'Riwayat',
          onTap: recentPayments.isEmpty ? null : () => onSelectTab(3),
        ),
        const SizedBox(height: 8),
        if (recentPayments.isEmpty)
          Padding(
            padding: EdgeInsets.symmetric(horizontal: 4),
            child: _StudentCompactEmptyState(
              title: 'Belum ada riwayat pembayaran',
              message: 'Transaksi yang tercatat akan tampil di sini.',
              icon: Icons.history_rounded,
              accentColor: const Color(0xFFF59E0B),
            ),
          )
        else
          Column(
            children: recentPayments
                .take(3)
                .map(
                  (item) => Padding(
                    padding: const EdgeInsets.only(bottom: 10),
                    child: _PaymentPreviewTile(item: item),
                  ),
                )
                .toList(),
          ),
        const SizedBox(height: 24),
      ],
    );
  }
}

class _StudentSectionHeader extends StatelessWidget {
  const _StudentSectionHeader({
    required this.title,
    this.onTap,
  });

  final String title;
  final VoidCallback? onTap;

  @override
  Widget build(BuildContext context) {
    return Row(
      crossAxisAlignment: CrossAxisAlignment.end,
      children: [
        Expanded(
          child: Text(
            title,
            style: const TextStyle(
              color: Color(0xFF172A24),
              fontSize: 11,
              fontWeight: FontWeight.w800,
              letterSpacing: 0.4,
            ),
          ),
        ),
        if (onTap != null)
          GestureDetector(
            onTap: onTap,
            child: const Text(
              'See All',
              style: TextStyle(
                color: Color(0xFF00745A),
                fontSize: 12,
                fontWeight: FontWeight.w700,
              ),
            ),
          ),
      ],
    );
  }
}

class _StudentCompactEmptyState extends StatelessWidget {
  const _StudentCompactEmptyState({
    required this.title,
    required this.message,
    required this.icon,
    this.accentColor = const Color(0xFF00745A),
  });

  final String title;
  final String message;
  final IconData icon;
  final Color accentColor;

  @override
  Widget build(BuildContext context) {
    return StudentTicketEmptyState(
      title: title,
      message: message,
      icon: icon,
      accentColor: accentColor,
      footerLabel: 'MENUNGGU DATA',
    );
  }
}

class _StudentDashboardTopBackdrop extends StatelessWidget {
  const _StudentDashboardTopBackdrop();

  @override
  Widget build(BuildContext context) {
    return SizedBox(
      // Keep the content anchor unchanged; only the painted green panel is
      // shorter than the reserved header space.
      height: 288,
      child: Stack(
      children: [
        Container(
          height: 250,
          decoration: const BoxDecoration(
            gradient: LinearGradient(
              colors: [
                Color(0xFF00553F),
                Color(0xFF00745A),
              ],
              begin: Alignment.topCenter,
              end: Alignment.bottomCenter,
            ),
            boxShadow: [
              BoxShadow(
                color: Color(0x14172A24),
                blurRadius: 20,
                offset: Offset(0, 10),
              ),
            ],
          ),
        ),
      ],
      ),
    );
  }
}


class _StudentDashboardHeader extends StatelessWidget {
  const _StudentDashboardHeader({
    required this.schoolName,
    required this.studentName,
    required this.isScrolled,
    required this.onOpenPayments,
    required this.onOpenProfile,
    required this.onLogout,
  });

  final String schoolName;
  final String studentName;
  final bool isScrolled;
  final VoidCallback onOpenPayments;
  final VoidCallback onOpenProfile;
  final Future<void> Function() onLogout;

  @override
  Widget build(BuildContext context) {
    final foregroundColor = isScrolled ? const Color(0xFF172A24) : Colors.white;
    final secondaryColor =
        isScrolled ? const Color(0xFFDCE7E3) : Colors.white70;
    final iconColor = isScrolled ? const Color(0xFF00553F) : Colors.white;

    return Row(
      children: [
        Expanded(
          child: Row(
            children: [
              _StudentHeaderAvatar(userName: studentName),
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
                        color: isScrolled
                            ? const Color(0xFF172A24)
                            : secondaryColor,
                        fontSize: 12,
                        fontWeight: FontWeight.w600,
                      ),
                    ),
                    const SizedBox(height: 4),
                    Text(
                      studentName,
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
        IconButton(
          onPressed: onOpenPayments,
          padding: EdgeInsets.zero,
          constraints: const BoxConstraints.tightFor(width: 24, height: 24),
          splashRadius: 16,
          icon: Icon(
            Icons.account_balance_wallet_outlined,
            color: iconColor,
            size: 22,
          ),
        ),
        PopupMenuButton<String>(
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
              case 'payments':
                onOpenPayments();
                break;
              case 'logout':
                await onLogout();
                break;
            }
          },
          itemBuilder: (context) => const [
            PopupMenuItem<String>(
              value: 'profile',
              child: _StudentHeaderMenuItem(
                icon: Icons.person_outline_rounded,
                label: 'Profil',
              ),
            ),
            PopupMenuItem<String>(
              value: 'payments',
              child: _StudentHeaderMenuItem(
                icon: Icons.account_balance_wallet_outlined,
                label: 'Pembayaran',
              ),
            ),
            PopupMenuItem<String>(
              value: 'logout',
              child: _StudentHeaderMenuItem(
                icon: Icons.logout_rounded,
                label: 'Keluar',
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
                color: iconColor,
                size: 22,
              ),
            ),
          ),
        ),
      ],
    );
  }
}

class _StudentHeaderAvatar extends StatelessWidget {
  const _StudentHeaderAvatar({
    required this.userName,
  });

  final String userName;

  @override
  Widget build(BuildContext context) {
    final words = userName.trim().split(RegExp(r'\s+'));
    final initials = words.take(2).map((item) => item[0]).join().toUpperCase();

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
            color: Color(0x14172A24),
            blurRadius: 14,
            offset: Offset(0, 6),
          ),
        ],
      ),
      child: ClipOval(
        child: DecoratedBox(
          decoration: const BoxDecoration(
            gradient: LinearGradient(
              colors: [
                Color(0xFF00745A),
                Color(0xFF00553F),
              ],
              begin: Alignment.topLeft,
              end: Alignment.bottomRight,
            ),
          ),
          child: Center(
            child: Text(
              initials.isEmpty ? 'S' : initials,
              style: const TextStyle(
                color: Colors.white,
                fontSize: 16,
                fontWeight: FontWeight.w800,
              ),
            ),
          ),
        ),
      ),
    );
  }
}

class _StudentHeaderMenuItem extends StatelessWidget {
  const _StudentHeaderMenuItem({
    required this.icon,
    required this.label,
    this.isDestructive = false,
  });

  final IconData icon;
  final String label;
  final bool isDestructive;

  @override
  Widget build(BuildContext context) {
    return Row(
      children: [
        Icon(
          icon,
          size: 20,
          color: isDestructive
              ? const Color(0xFFDC2626)
              : const Color(0xFF00553F),
        ),
        const SizedBox(width: 10),
        Text(
          label,
          style: TextStyle(
            color: isDestructive
                ? const Color(0xFFDC2626)
                : const Color(0xFF00553F),
            fontWeight: FontWeight.w700,
          ),
        ),
      ],
    );
  }
}

class _StudentPerformanceCard extends StatelessWidget {
  const _StudentPerformanceCard({
    required this.percent,
  });

  final int percent;

  @override
  Widget build(BuildContext context) {
    final progress = (percent.clamp(0, 100)) / 100;
    return Container(
      padding: const EdgeInsets.fromLTRB(15, 15, 15, 14),
      decoration: BoxDecoration(
        gradient: const LinearGradient(
          colors: [
            Color(0xFF00745A),
            Color(0xFF00553F),
          ],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
        borderRadius: BorderRadius.circular(24),
        boxShadow: const [
          BoxShadow(
            color: Color(0x14172A24),
            blurRadius: 20,
            offset: Offset(0, 10),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                'Level Progres',
                style: TextStyle(
                  color: Colors.white.withValues(alpha: 0.9),
                  fontSize: 10.5,
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
                      fontSize: 20,
                      fontWeight: FontWeight.w800,
                      height: 1,
                    ),
                  );
                },
              ),
            ],
          ),
          const SizedBox(height: 12),
          ClipRRect(
            borderRadius: BorderRadius.circular(999),
            child: TweenAnimationBuilder<double>(
              tween: Tween<double>(begin: 0, end: progress.toDouble()),
              duration: const Duration(milliseconds: 1000),
              curve: Curves.easeOutCubic,
              builder: (context, animatedValue, _) {
                return LinearProgressIndicator(
                  minHeight: 7,
                  value: animatedValue,
                  backgroundColor: Colors.white24,
                  valueColor: const AlwaysStoppedAnimation<Color>(
                    Color(0xFF00745A),
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

class _StudentStatTile extends StatelessWidget {
  const _StudentStatTile({
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
    final numericValue = int.tryParse(value.replaceAll(RegExp(r'\D'), '')) ?? 0;

    return Container(
      padding: const EdgeInsets.fromLTRB(8, 10, 8, 10),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: const Color(0xFFDCE7E3)),
        boxShadow: const [
          BoxShadow(
            color: Color(0x14172A24),
            blurRadius: 16,
            offset: Offset(0, 6),
          ),
        ],
      ),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
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
              return Text(
                '$animatedValue',
                textAlign: TextAlign.center,
                style: const TextStyle(
                  color: Color(0xFF172A24),
                  fontSize: 12,
                  fontWeight: FontWeight.w800,
                  height: 1,
                ),
              );
            },
          ),
          const SizedBox(height: 2),
          Text(
            label,
            maxLines: 1,
            overflow: TextOverflow.ellipsis,
            textAlign: TextAlign.center,
            style: const TextStyle(
              color: Color(0xFF172A24),
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

class _StudentServiceItem {
  const _StudentServiceItem({
    required this.label,
    required this.icon,
    required this.onTap,
  });

  final String label;
  final IconData icon;
  final VoidCallback onTap;
}

class _StudentServiceShortcutTile extends StatelessWidget {
  const _StudentServiceShortcutTile({
    required this.item,
  });

  final _StudentServiceItem item;

  @override
  Widget build(BuildContext context) {
    return _AnimatedPressable(
      onTap: item.onTap,
      builder: (context, isPressed) {
        final accent =
            isPressed ? const Color(0xFF00553F) : const Color(0xFF00745A);

        return Column(
          mainAxisSize: MainAxisSize.min,
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
                    color: Color(0x14172A24),
                    blurRadius: 12,
                    offset: Offset(0, 4),
                  ),
                ],
              ),
              child: Icon(
                item.icon,
                size: 24,
                color: accent,
              ),
            ),
            const SizedBox(height: 10),
            Text(
              item.label,
              textAlign: TextAlign.center,
              style: const TextStyle(
                fontSize: 11,
                fontWeight: FontWeight.w700,
                color: Color(0xFF172A24),
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

class _StudentBillShowcaseCard extends StatelessWidget {
  const _StudentBillShowcaseCard({
    required this.item,
    required this.schoolName,
  });

  final Map<String, dynamic> item;
  final String schoolName;

  @override
  Widget build(BuildContext context) {
    final status = item['status'] as String? ?? 'belum_lunas';
    final accentColor = billStatusColor(status);

    return SizedBox(
      width: 224,
      child: StudentTicketCard(
        accentColor: accentColor,
        padding: const EdgeInsets.fromLTRB(12, 12, 12, 10),
        footer: StudentTicketAmount(
          label: 'Sisa bayar',
          value: formatStudentCurrency(item['outstanding_amount']),
          color: accentColor,
          icon: Icons.payments_rounded,
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                Container(
                  padding: const EdgeInsets.symmetric(
                    horizontal: 9,
                    vertical: 5,
                  ),
                  decoration: BoxDecoration(
                    color: const Color(0xFFE5F5F0),
                    borderRadius: BorderRadius.circular(999),
                  ),
                  child: Text(
                    formatStudentDate(item['jatuh_tempo'] as String?),
                    style: const TextStyle(
                      color: Color(0xFF00553F),
                      fontSize: 9.5,
                      fontWeight: FontWeight.w800,
                    ),
                  ),
                ),
                const Spacer(),
                Container(
                  width: 9,
                  height: 9,
                  decoration: BoxDecoration(
                    color: accentColor,
                    shape: BoxShape.circle,
                  ),
                ),
              ],
            ),
            const SizedBox(height: 12),
            Text(
              normalizeStudentText(item['jenis_tagihan']),
              maxLines: 2,
              overflow: TextOverflow.ellipsis,
              style: const TextStyle(
                color: Color(0xFF172A24),
                fontSize: 14,
                fontWeight: FontWeight.w800,
                height: 1.15,
              ),
            ),
            const SizedBox(height: 7),
            Text(
              normalizeStudentText(item['nomor_tagihan']),
              maxLines: 1,
              overflow: TextOverflow.ellipsis,
              style: const TextStyle(
                color: Color(0xFF172A24),
                fontSize: 11,
                fontWeight: FontWeight.w700,
              ),
            ),
            const SizedBox(height: 3),
            Text(
              schoolName,
              maxLines: 1,
              overflow: TextOverflow.ellipsis,
              style: const TextStyle(
                color: Color(0xFF172A24),
                fontSize: 10.5,
                fontWeight: FontWeight.w600,
              ),
            ),
          ],
        ),
      ),
    );
  }
}

class _StudentReminderCard extends StatelessWidget {
  const _StudentReminderCard({
    required this.item,
  });

  final Map<String, dynamic> item;

  @override
  Widget build(BuildContext context) {
    return Container(
      width: 224,
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: const Color(0xFFDCE7E3)),
        boxShadow: const [
          BoxShadow(
            color: Color(0x14172A24),
            blurRadius: 20,
            offset: Offset(0, 8),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            width: 36,
            height: 36,
            decoration: BoxDecoration(
              color: const Color(0xFFFFF4D6),
              borderRadius: BorderRadius.circular(12),
            ),
            child: const Icon(
              Icons.alarm_rounded,
              size: 18,
              color: Color(0xFFD97706),
            ),
          ),
          const SizedBox(height: 12),
          Text(
            normalizeStudentText(item['title']),
            maxLines: 2,
            overflow: TextOverflow.ellipsis,
            style: const TextStyle(
              color: Color(0xFF172A24),
              fontSize: 15,
              fontWeight: FontWeight.w800,
              height: 1.15,
            ),
          ),
          const SizedBox(height: 8),
          Text(
            normalizeStudentText(item['message']),
            maxLines: 4,
            overflow: TextOverflow.ellipsis,
            style: const TextStyle(
              color: Color(0xFF172A24),
              fontSize: 11.5,
              height: 1.35,
            ),
          ),
        ],
      ),
    );
  }
}

class _AnimatedPressable extends StatefulWidget {
  const _AnimatedPressable({
    required this.builder,
    this.onTap,
  });

  final Widget Function(BuildContext context, bool isPressed) builder;
  final VoidCallback? onTap;

  @override
  State<_AnimatedPressable> createState() => _AnimatedPressableState();
}

class _AnimatedPressableState extends State<_AnimatedPressable> {
  bool _pressed = false;

  void _setPressed(bool value) {
    if (_pressed == value || !mounted) {
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
      child: AnimatedScale(
        scale: _pressed ? 0.96 : 1,
        duration: const Duration(milliseconds: 150),
        curve: Curves.easeOut,
        child: widget.builder(context, _pressed),
      ),
    );
  }
}

class _PaymentPreviewTile extends StatelessWidget {
  const _PaymentPreviewTile({
    required this.item,
  });

  final Map<String, dynamic> item;

  @override
  Widget build(BuildContext context) {
    final bill = Map<String, dynamic>.from(
      (item['bill'] as Map?) ?? const <String, dynamic>{},
    );
    final color = paymentStatusColor(item['status_verifikasi'] as String?);

    return StudentTicketCard(
      // Riwayat uses the NUIST gold accent for the ticket perforation/bar;
      // the payment status itself keeps its semantic color below.
      accentColor: const Color(0xFFF59E0B),
      padding: const EdgeInsets.fromLTRB(12, 12, 12, 10),
      footer: StudentTicketAmount(
        label: 'Nominal bayar',
        value: formatStudentCurrency(item['nominal_bayar']),
        color: color,
      ),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            width: 32,
            height: 32,
            decoration: BoxDecoration(
              color: color.withValues(alpha: 0.12),
              borderRadius: BorderRadius.circular(11),
            ),
            child: Icon(
              Icons.payments_rounded,
              color: color,
              size: 16,
            ),
          ),
          const SizedBox(width: 10),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  normalizeStudentText(bill['nomor_tagihan']),
                  style: const TextStyle(
                    fontSize: 12.5,
                    fontWeight: FontWeight.w800,
                    color: AppColors.textMain,
                  ),
                ),
                const SizedBox(height: 3),
                Text(
                  formatStudentDate(item['tanggal_bayar'] as String?),
                  style: const TextStyle(
                    fontSize: 10.5,
                    color: AppColors.textMuted,
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(width: 8),
          StudentStatusBadge(
            label: paymentStatusLabel(item['status_verifikasi'] as String?),
            color: color,
          ),
        ],
      ),
    );
  }
}

String _studentDashboardMonthLabel(DateTime date) {
  const months = <String>[
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
  return '${months[date.month - 1]} ${date.year}';
}

@Preview(
  name: 'Student Dashboard UI',
  size: Size(390, 844),
)
Widget studentDashboardPreview() {
  return MaterialApp(
    debugShowCheckedModeBanner: false,
    home: Scaffold(
      backgroundColor: const Color(0xFFF7F9FC),
      body: SafeArea(
        child: _StudentDashboardContent(
          student: const {
            'name': 'Ahmad Maulana',
            'kelas': 'XII',
            'jurusan': 'RPL',
          },
          studentName: 'Ahmad Maulana',
          schoolName: 'SMK Ma\'arif NU',
          summary: const {
            'payment_completion_rate': 75,
            'paid_bills': 6,
            'total_bills': 8,
            'pending_payments': 1,
            'unpaid_bills': 2,
          },
          activeBill: const {
            'status': 'belum_lunas',
            'jenis_tagihan': 'SPP Agustus',
            'nomor_tagihan': 'INV-2026-008',
            'jatuh_tempo': '2026-08-15',
            'outstanding_amount': 150000,
          },
          reminder: null,
          recentPayments: const [],
          onSelectTab: (_) {},
        ),
      ),
    ),
  );
}
