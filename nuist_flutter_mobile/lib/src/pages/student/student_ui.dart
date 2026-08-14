import 'package:flutter/material.dart';

import '../../theme/app_theme.dart';

String formatStudentCurrency(dynamic value) {
  final int amount;
  if (value is num) {
    amount = value.round();
  } else if (value is String) {
    amount = int.tryParse(value) ?? 0;
  } else {
    amount = 0;
  }

  final digits = amount.toString();
  final buffer = StringBuffer();
  for (var index = 0; index < digits.length; index++) {
    final reverseIndex = digits.length - index;
    buffer.write(digits[index]);
    if (reverseIndex > 1 && reverseIndex % 3 == 1) {
      buffer.write('.');
    }
  }
  return 'Rp $buffer';
}

String formatStudentDate(String? isoDate) {
  if (isoDate == null || isoDate.trim().isEmpty) {
    return '-';
  }

  final date = DateTime.tryParse(isoDate);
  if (date == null) {
    return isoDate;
  }

  const months = <String>[
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
  return '${date.day} ${months[date.month - 1]} ${date.year}';
}

String formatStudentDateTime(String? isoDateTime) {
  if (isoDateTime == null || isoDateTime.trim().isEmpty) {
    return '-';
  }

  final date = DateTime.tryParse(isoDateTime);
  if (date == null) {
    return isoDateTime;
  }

  final local = date.toLocal();
  final hour = local.hour.toString().padLeft(2, '0');
  final minute = local.minute.toString().padLeft(2, '0');
  return '${formatStudentDate(local.toIso8601String())} • $hour:$minute';
}

String normalizeStudentText(dynamic value, {String fallback = '-'}) {
  if (value == null) {
    return fallback;
  }
  final text = value.toString().trim();
  return text.isEmpty ? fallback : text;
}

Color billStatusColor(String? status) {
  switch (status) {
    case 'lunas':
      return const Color(0xFF00745A);
    case 'sebagian':
      return const Color(0xFFD97706);
    default:
      return const Color(0xFFB42318);
  }
}

Color paymentStatusColor(String? status) {
  switch (status) {
    case 'diverifikasi':
      return const Color(0xFF00745A);
    case 'ditolak':
      return const Color(0xFFB42318);
    default:
      return const Color(0xFF00745A);
  }
}

String paymentStatusLabel(String? status) {
  switch (status) {
    case 'diverifikasi':
      return 'Terverifikasi';
    case 'ditolak':
      return 'Ditolak';
    default:
      return 'Menunggu';
  }
}

class StudentPageBanner extends StatelessWidget {
  const StudentPageBanner({
    super.key,
    required this.title,
    required this.subtitle,
    required this.icon,
    this.badges = const [],
  });

  final String title;
  final String subtitle;
  final IconData icon;
  final List<Widget> badges;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(18),
      decoration: BoxDecoration(
        gradient: const LinearGradient(
          colors: [Color(0xFF00745A), Color(0xFF00553F)],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
        borderRadius: BorderRadius.circular(26),
        boxShadow: const [
          BoxShadow(
            color: Color(0x2900745A),
            blurRadius: 24,
            offset: Offset(0, 10),
          ),
        ],
      ),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  title,
                  style: const TextStyle(
                    fontSize: 21,
                    fontWeight: FontWeight.w800,
                    color: Colors.white,
                  ),
                ),
                const SizedBox(height: 6),
                Text(
                  subtitle,
                  style: TextStyle(
                    fontSize: 12,
                    color: Colors.white.withValues(alpha: 0.88),
                    height: 1.4,
                  ),
                ),
                if (badges.isNotEmpty) ...[
                  const SizedBox(height: 12),
                  Wrap(
                    spacing: 8,
                    runSpacing: 8,
                    children: badges,
                  ),
                ],
              ],
            ),
          ),
          const SizedBox(width: 14),
          Container(
            width: 52,
            height: 52,
            decoration: BoxDecoration(
              color: Colors.white.withValues(alpha: 0.14),
              shape: BoxShape.circle,
              border: Border.all(
                color: Colors.white.withValues(alpha: 0.2),
              ),
            ),
            child: Icon(
              icon,
              color: Colors.white,
              size: 24,
            ),
          ),
        ],
      ),
    );
  }
}

/// Surface utama untuk halaman tab siswa. Bentuknya mengikuti panel konten
/// pada halaman Jadwal dan Profil guru yang berada tepat di bawah header.
class StudentPageContentSurface extends StatelessWidget {
  const StudentPageContentSurface({
    super.key,
    required this.child,
  });

  final Widget child;

  @override
  Widget build(BuildContext context) {
    return Transform.translate(
      offset: const Offset(0, -8),
      child: Container(
        width: double.infinity,
        padding: const EdgeInsets.fromLTRB(16, 22, 16, 16),
        decoration: const BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.only(
            topLeft: Radius.circular(18),
            topRight: Radius.circular(18),
          ),
        ),
        child: child,
      ),
    );
  }
}

class StudentBannerBadge extends StatelessWidget {
  const StudentBannerBadge({
    super.key,
    required this.label,
    this.icon,
  });

  final String label;
  final IconData? icon;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 7),
      decoration: BoxDecoration(
        color: Colors.white.withValues(alpha: 0.16),
        borderRadius: BorderRadius.circular(999),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          if (icon != null) ...[
            Icon(icon, size: 13, color: Colors.white),
            const SizedBox(width: 6),
          ],
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

class StudentStatusBadge extends StatelessWidget {
  const StudentStatusBadge({
    super.key,
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
        color: color.withValues(alpha: 0.12),
        borderRadius: BorderRadius.circular(999),
      ),
      child: Text(
        label,
        style: TextStyle(
          color: color,
          fontWeight: FontWeight.w700,
          fontSize: 11,
        ),
      ),
    );
  }
}

class StudentSectionHeading extends StatelessWidget {
  const StudentSectionHeading({
    super.key,
    required this.title,
    this.subtitle,
    this.trailing,
  });

  final String title;
  final String? subtitle;
  final Widget? trailing;

  @override
  Widget build(BuildContext context) {
    return Row(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                title,
                style: const TextStyle(
                  fontSize: 15,
                  fontWeight: FontWeight.w800,
                  color: AppColors.textMain,
                ),
              ),
              if (subtitle != null && subtitle!.trim().isNotEmpty) ...[
                const SizedBox(height: 3),
                Text(
                  subtitle!,
                  style: const TextStyle(
                    fontSize: 11.5,
                    color: AppColors.textMuted,
                    height: 1.35,
                  ),
                ),
              ],
            ],
          ),
        ),
        if (trailing != null) ...[
          const SizedBox(width: 10),
          trailing!,
        ],
      ],
    );
  }
}

class StudentMetricCard extends StatelessWidget {
  const StudentMetricCard({
    super.key,
    required this.label,
    required this.value,
    required this.icon,
    required this.tone,
  });

  final String label;
  final String value;
  final IconData icon;
  final Color tone;

  @override
  Widget build(BuildContext context) {
    return Container(
      constraints: const BoxConstraints(minWidth: 104),
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: tone.withValues(alpha: 0.09),
        borderRadius: BorderRadius.circular(18),
        border: Border.all(color: tone.withValues(alpha: 0.12)),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            width: 28,
            height: 28,
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(10),
            ),
            child: Icon(icon, size: 15, color: tone),
          ),
          const SizedBox(height: 10),
          Text(
            value,
            maxLines: 1,
            overflow: TextOverflow.ellipsis,
            style: const TextStyle(
              fontSize: 15,
              fontWeight: FontWeight.w800,
              color: AppColors.textMain,
            ),
          ),
          const SizedBox(height: 3),
          Text(
            label,
            style: const TextStyle(
              fontSize: 11,
              fontWeight: FontWeight.w600,
              color: AppColors.textMuted,
            ),
          ),
        ],
      ),
    );
  }
}

class StudentInfoRow extends StatelessWidget {
  const StudentInfoRow({
    super.key,
    required this.label,
    required this.value,
    this.icon,
  });

  final String label;
  final String value;
  final IconData? icon;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 5),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          if (icon != null) ...[
            Container(
              width: 28,
              height: 28,
              decoration: BoxDecoration(
                color: AppColors.accentSoft,
                borderRadius: BorderRadius.circular(10),
              ),
              child: Icon(
                icon,
                size: 15,
                color: AppColors.accentDeep,
              ),
            ),
            const SizedBox(width: 10),
          ],
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  label,
                  style: const TextStyle(
                    fontSize: 11,
                    fontWeight: FontWeight.w600,
                    color: AppColors.textMuted,
                  ),
                ),
                const SizedBox(height: 2),
                Text(
                  value,
                  style: const TextStyle(
                    fontSize: 13,
                    fontWeight: FontWeight.w700,
                    color: AppColors.textMain,
                    height: 1.35,
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

class StudentFactTile extends StatelessWidget {
  const StudentFactTile({
    super.key,
    required this.label,
    required this.value,
    this.icon,
  });

  final String label;
  final String value;
  final IconData? icon;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: const Color(0xFFF7F9FC),
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: const Color(0xFFDCE7E3)),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          if (icon != null) ...[
            Container(
              width: 26,
              height: 26,
              decoration: BoxDecoration(
                color: AppColors.accentSoft,
                borderRadius: BorderRadius.circular(8),
              ),
              child: Icon(
                icon,
                size: 14,
                color: AppColors.accentDeep,
              ),
            ),
            const SizedBox(height: 8),
          ],
          Text(
            label,
            style: const TextStyle(
              fontSize: 11,
              fontWeight: FontWeight.w600,
              color: AppColors.textMuted,
            ),
          ),
          const SizedBox(height: 4),
          Text(
            value,
            style: const TextStyle(
              fontSize: 13,
              fontWeight: FontWeight.w700,
              color: AppColors.textMain,
              height: 1.35,
            ),
          ),
        ],
      ),
    );
  }
}

class StudentTicketCard extends StatefulWidget {
  const StudentTicketCard({
    super.key,
    required this.child,
    this.footer,
    this.accentColor = AppColors.accentMain,
    this.padding = const EdgeInsets.all(14),
    this.showTicketDivider = false,
  });

  final Widget child;
  final Widget? footer;
  final Color accentColor;
  final EdgeInsetsGeometry padding;
  final bool showTicketDivider;

  @override
  State<StudentTicketCard> createState() => _StudentTicketCardState();
}

class _StudentTicketCardState extends State<StudentTicketCard> {
  final _cardKey = GlobalKey();
  final _dividerKey = GlobalKey();
  double? _notchCenterY;

  void _measureNotch() {
    final cardBox = _cardKey.currentContext?.findRenderObject() as RenderBox?;
    final dividerBox =
        _dividerKey.currentContext?.findRenderObject() as RenderBox?;
    if (cardBox == null || dividerBox == null) return;

    final offset = dividerBox.localToGlobal(Offset.zero, ancestor: cardBox);
    final centerY = offset.dy + (dividerBox.size.height / 2);
    if (_notchCenterY == centerY) return;
    setState(() => _notchCenterY = centerY);
  }

  @override
  Widget build(BuildContext context) {
    final shouldShowDivider =
        widget.showTicketDivider || widget.footer != null;

    if (shouldShowDivider) {
      WidgetsBinding.instance.addPostFrameCallback((_) {
        if (mounted) _measureNotch();
      });
    }

    return CustomPaint(
      foregroundPainter: _StudentTicketBorderPainter(
        notchCenterY: _notchCenterY,
      ),
      child: PhysicalShape(
        key: _cardKey,
        clipper: _StudentTicketClipper(notchCenterY: _notchCenterY),
        color: Colors.white,
        shadowColor: const Color(0x14172A24),
        elevation: 5,
        child: SizedBox(
          width: double.infinity,
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              Padding(
            padding: const EdgeInsets.fromLTRB(16, 10, 16, 0),
            child: Container(
              height: 5,
              decoration: BoxDecoration(
                gradient: LinearGradient(
                  colors: [
                    widget.accentColor.withValues(alpha: .85),
                    widget.accentColor,
                  ],
                ),
                borderRadius: BorderRadius.circular(99),
              ),
            ),
          ),
          Padding(
            padding: widget.padding,
            child: widget.child,
          ),
          if (shouldShowDivider)
            KeyedSubtree(
              key: _dividerKey,
              child: _StudentTicketDivider(
                accentColor: widget.accentColor,
              ),
            ),
          if (widget.footer != null)
            Padding(
              padding: const EdgeInsets.fromLTRB(14, 0, 14, 14),
              child: widget.footer,
            ),
            ],
          ),
        ),
      ),
    );
  }
}

class _StudentTicketDivider extends StatelessWidget {
  const _StudentTicketDivider({required this.accentColor});

  final Color accentColor;

  @override
  Widget build(BuildContext context) {
    return SizedBox(
      height: 24,
      width: double.infinity,
      child: CustomPaint(
        painter: _StudentTicketDashedLinePainter(
          color: accentColor.withValues(alpha: 0.25),
          sideInset: 22,
        ),
      ),
    );
  }
}

class _StudentTicketClipper extends CustomClipper<Path> {
  const _StudentTicketClipper({required this.notchCenterY});

  final double? notchCenterY;

  @override
  Path getClip(Size size) => _studentTicketPath(size, notchCenterY);

  @override
  bool shouldReclip(covariant _StudentTicketClipper oldClipper) =>
      oldClipper.notchCenterY != notchCenterY;
}

class _StudentTicketBorderPainter extends CustomPainter {
  const _StudentTicketBorderPainter({required this.notchCenterY});

  final double? notchCenterY;

  @override
  void paint(Canvas canvas, Size size) {
    final paint = Paint()
      ..color = const Color(0xFFDCE7E3)
      ..style = PaintingStyle.stroke
      ..strokeWidth = 1;
    canvas.drawPath(_studentTicketPath(size, notchCenterY), paint);
  }

  @override
  bool shouldRepaint(covariant _StudentTicketBorderPainter oldDelegate) =>
      oldDelegate.notchCenterY != notchCenterY;
}

Path _studentTicketPath(Size size, double? notchCenterY) {
  const corner = 22.0;
  const notchRadius = 12.0;
  final width = size.width;
  final height = size.height;

  final centerY = notchCenterY;
  if (centerY == null ||
      centerY <= corner + notchRadius ||
      centerY >= height - corner - notchRadius) {
    return Path()
      ..addRRect(RRect.fromRectAndRadius(
        Offset.zero & size,
        const Radius.circular(corner),
      ));
  }

  final top = centerY - notchRadius;
  final bottom = centerY + notchRadius;
  // 0.55228475 × radius: the standard Bézier constant for a quarter circle.
  const arcControl = 6.63;

  return Path()
    ..moveTo(corner, 0)
    ..lineTo(width - corner, 0)
    ..quadraticBezierTo(width, 0, width, corner)
    ..lineTo(width, top)
    ..cubicTo(
      width - arcControl,
      top,
      width - notchRadius,
      centerY - arcControl,
      width - notchRadius,
      centerY,
    )
    ..cubicTo(
      width - notchRadius,
      centerY + arcControl,
      width - arcControl,
      bottom,
      width,
      bottom,
    )
    ..lineTo(width, height - corner)
    ..quadraticBezierTo(width, height, width - corner, height)
    ..lineTo(corner, height)
    ..quadraticBezierTo(0, height, 0, height - corner)
    ..lineTo(0, bottom)
    ..cubicTo(
      arcControl,
      bottom,
      notchRadius,
      centerY + arcControl,
      notchRadius,
      centerY,
    )
    ..cubicTo(
      notchRadius,
      centerY - arcControl,
      arcControl,
      top,
      0,
      top,
    )
    ..lineTo(0, corner)
    ..quadraticBezierTo(0, 0, corner, 0)
    ..close();
}

class _StudentTicketDashedLinePainter extends CustomPainter {
  const _StudentTicketDashedLinePainter({
    required this.color,
    required this.sideInset,
  });

  final Color color;
  final double sideInset;

  @override
  void paint(Canvas canvas, Size size) {
    final paint = Paint()
      ..color = color
      ..style = PaintingStyle.stroke
      ..strokeCap = StrokeCap.round
      ..strokeWidth = 2;
    const dashWidth = 8.0;
    const dashGap = 7.0;
    final end = size.width - sideInset;

    for (var start = sideInset; start < end; start += dashWidth + dashGap) {
      canvas.drawLine(
        Offset(start, size.height / 2),
        Offset((start + dashWidth).clamp(sideInset, end), size.height / 2),
        paint,
      );
    }
  }

  @override
  bool shouldRepaint(covariant _StudentTicketDashedLinePainter oldDelegate) {
    return oldDelegate.color != color || oldDelegate.sideInset != sideInset;
  }
}

class StudentTicketEmptyState extends StatelessWidget {
  const StudentTicketEmptyState({
    super.key,
    required this.title,
    required this.message,
    required this.icon,
    this.accentColor = AppColors.accentMain,
    this.footerLabel = 'Belum tersedia',
  });

  final String title;
  final String message;
  final IconData icon;
  final Color accentColor;
  final String footerLabel;

  @override
  Widget build(BuildContext context) {
    return StudentTicketCard(
      accentColor: accentColor,
      showTicketDivider: true,
      padding: const EdgeInsets.fromLTRB(16, 16, 16, 14),
      footer: Center(
        child: Text(
          footerLabel,
          style: TextStyle(
            fontSize: 10.5,
            fontWeight: FontWeight.w700,
            color: accentColor,
            letterSpacing: 0.2,
          ),
        ),
      ),
      child: Column(
        children: [
          Container(
            width: 44,
            height: 44,
            decoration: BoxDecoration(
              color: accentColor.withValues(alpha: 0.12),
              shape: BoxShape.circle,
            ),
            child: Icon(
              icon,
              size: 22,
              color: accentColor,
            ),
          ),
          const SizedBox(height: 10),
          Text(
            title,
            textAlign: TextAlign.center,
            style: const TextStyle(
              fontSize: 15,
              fontWeight: FontWeight.w800,
              color: AppColors.textMain,
              height: 1.2,
            ),
          ),
          const SizedBox(height: 4),
          Text(
            message,
            textAlign: TextAlign.center,
            style: const TextStyle(
              fontSize: 12,
              color: AppColors.textMuted,
              height: 1.45,
            ),
          ),
        ],
      ),
    );
  }
}

class StudentTicketMeta extends StatelessWidget {
  const StudentTicketMeta({
    super.key,
    required this.label,
    required this.value,
    this.icon,
    this.color = AppColors.accentDeep,
  });

  final String label;
  final String value;
  final IconData? icon;
  final Color color;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.fromLTRB(10, 9, 10, 9),
      decoration: BoxDecoration(
        color: const Color(0xFFF7F9FC),
        borderRadius: BorderRadius.circular(14),
        border: Border.all(color: const Color(0xFFDCE7E3)),
      ),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          if (icon != null) ...[
            Container(
              width: 24,
              height: 24,
              decoration: BoxDecoration(
                color: color.withValues(alpha: 0.12),
                borderRadius: BorderRadius.circular(8),
              ),
              child: Icon(icon, size: 13, color: color),
            ),
            const SizedBox(width: 8),
          ],
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  label,
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                  style: const TextStyle(
                    fontSize: 10,
                    fontWeight: FontWeight.w600,
                    color: AppColors.textMuted,
                  ),
                ),
                const SizedBox(height: 2),
                Text(
                  value,
                  maxLines: 2,
                  overflow: TextOverflow.ellipsis,
                  style: const TextStyle(
                    fontSize: 12,
                    fontWeight: FontWeight.w700,
                    color: AppColors.textMain,
                    height: 1.3,
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

class StudentTicketAmount extends StatelessWidget {
  const StudentTicketAmount({
    super.key,
    required this.label,
    required this.value,
    required this.color,
    this.icon = Icons.payments_rounded,
  });

  final String label;
  final String value;
  final Color color;
  final IconData icon;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.fromLTRB(10, 10, 10, 10),
      decoration: BoxDecoration(
        color: color.withValues(alpha: 0.1),
        borderRadius: BorderRadius.circular(16),
      ),
      child: Row(
        children: [
          Container(
            width: 28,
            height: 28,
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(10),
            ),
            child: Icon(icon, size: 15, color: color),
          ),
          const SizedBox(width: 9),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  label,
                  style: TextStyle(
                    fontSize: 10,
                    fontWeight: FontWeight.w600,
                    color: color.withValues(alpha: 0.9),
                  ),
                ),
                const SizedBox(height: 2),
                Text(
                  value,
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                  style: TextStyle(
                    fontSize: 12,
                    fontWeight: FontWeight.w800,
                    color: color,
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

class StudentActionTile extends StatelessWidget {
  const StudentActionTile({
    super.key,
    required this.icon,
    required this.title,
    required this.subtitle,
    required this.onTap,
  });

  final IconData icon;
  final String title;
  final String subtitle;
  final VoidCallback onTap;

  @override
  Widget build(BuildContext context) {
    return Material(
      color: Colors.transparent,
      child: InkWell(
        borderRadius: BorderRadius.circular(18),
        onTap: onTap,
        child: Container(
          padding: const EdgeInsets.all(12),
          decoration: BoxDecoration(
            color: const Color(0xFFF7F9FC),
            borderRadius: BorderRadius.circular(18),
            border: Border.all(color: const Color(0xFFDCE7E3)),
          ),
          child: Row(
            children: [
              Container(
                width: 36,
                height: 36,
                decoration: BoxDecoration(
                  color: AppColors.accentSoft,
                  borderRadius: BorderRadius.circular(12),
                ),
                child: Icon(icon, color: AppColors.accentDeep, size: 18),
              ),
              const SizedBox(width: 10),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      title,
                      style: const TextStyle(
                        fontSize: 13,
                        fontWeight: FontWeight.w800,
                        color: AppColors.textMain,
                      ),
                    ),
                    const SizedBox(height: 2),
                    Text(
                      subtitle,
                      style: const TextStyle(
                        fontSize: 11,
                        color: AppColors.textMuted,
                      ),
                    ),
                  ],
                ),
              ),
              const Icon(
                Icons.chevron_right_rounded,
                color: AppColors.textMuted,
              ),
            ],
          ),
        ),
      ),
    );
  }
}

class StudentErrorView extends StatelessWidget {
  const StudentErrorView({
    super.key,
    required this.message,
    required this.onRetry,
  });

  final String message;
  final Future<void> Function() onRetry;

  @override
  Widget build(BuildContext context) {
    return Center(
      child: Padding(
        padding: const EdgeInsets.all(24),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            const Icon(
              Icons.cloud_off_rounded,
              size: 40,
              color: AppColors.accentWarm,
            ),
            const SizedBox(height: 12),
            Text(
              message,
              textAlign: TextAlign.center,
              style: const TextStyle(
                fontSize: 13,
                color: AppColors.textMain,
                height: 1.4,
              ),
            ),
            const SizedBox(height: 16),
            FilledButton(
              onPressed: onRetry,
              child: const Text('Coba lagi'),
            ),
          ],
        ),
      ),
    );
  }
}
