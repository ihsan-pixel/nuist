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
      return const Color(0xFF0B8F6E);
    case 'sebagian':
      return const Color(0xFFD97706);
    default:
      return const Color(0xFFB42318);
  }
}

Color paymentStatusColor(String? status) {
  switch (status) {
    case 'diverifikasi':
      return const Color(0xFF0B8F6E);
    case 'ditolak':
      return const Color(0xFFB42318);
    default:
      return const Color(0xFF2563EB);
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
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
      decoration: BoxDecoration(
        color: color.withOpacity(0.12),
        borderRadius: BorderRadius.circular(999),
      ),
      child: Text(
        label,
        style: TextStyle(
          color: color,
          fontWeight: FontWeight.w700,
          fontSize: 12,
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
                  fontSize: 18,
                  fontWeight: FontWeight.w800,
                  color: AppColors.textMain,
                ),
              ),
              if (subtitle != null && subtitle!.trim().isNotEmpty) ...[
                const SizedBox(height: 4),
                Text(
                  subtitle!,
                  style: const TextStyle(
                    fontSize: 13,
                    color: AppColors.textMuted,
                    height: 1.35,
                  ),
                ),
              ],
            ],
          ),
        ),
        if (trailing != null) ...[
          const SizedBox(width: 12),
          trailing!,
        ],
      ],
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
      padding: const EdgeInsets.symmetric(vertical: 8),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          if (icon != null) ...[
            Container(
              width: 34,
              height: 34,
              decoration: BoxDecoration(
                color: AppColors.accentSoft,
                borderRadius: BorderRadius.circular(12),
              ),
              child: Icon(
                icon,
                size: 18,
                color: AppColors.accentDeep,
              ),
            ),
            const SizedBox(width: 12),
          ],
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  label,
                  style: const TextStyle(
                    fontSize: 12,
                    fontWeight: FontWeight.w600,
                    color: AppColors.textMuted,
                  ),
                ),
                const SizedBox(height: 3),
                Text(
                  value,
                  style: const TextStyle(
                    fontSize: 14,
                    fontWeight: FontWeight.w700,
                    color: AppColors.textMain,
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
