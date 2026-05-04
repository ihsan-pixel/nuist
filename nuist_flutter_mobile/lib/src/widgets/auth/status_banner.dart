import 'package:flutter/material.dart';

enum StatusBannerType {
  error,
  success,
  info,
}

class StatusBanner extends StatelessWidget {
  const StatusBanner({
    super.key,
    required this.message,
    required this.type,
  });

  final String message;
  final StatusBannerType type;

  @override
  Widget build(BuildContext context) {
    final style = _resolveStyle(type);

    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: style.background,
        borderRadius: BorderRadius.circular(18),
        border: Border.all(color: style.border),
      ),
      child: Text(
        message,
        style: TextStyle(
          color: style.foreground,
          fontWeight: FontWeight.w600,
        ),
      ),
    );
  }

  _StatusBannerStyle _resolveStyle(StatusBannerType type) {
    switch (type) {
      case StatusBannerType.success:
        return const _StatusBannerStyle(
          background: Color(0xFFE8F8EE),
          border: Color(0xFFBFE8CB),
          foreground: Color(0xFF1D6B40),
        );
      case StatusBannerType.info:
        return const _StatusBannerStyle(
          background: Color(0xFFEAF7F6),
          border: Color(0xFFB7E1DD),
          foreground: Color(0xFF0D5C63),
        );
      case StatusBannerType.error:
        return const _StatusBannerStyle(
          background: Color(0xFFFFF1F2),
          border: Color(0xFFFDA4AF),
          foreground: Color(0xFF9F1239),
        );
    }
  }
}

class _StatusBannerStyle {
  const _StatusBannerStyle({
    required this.background,
    required this.border,
    required this.foreground,
  });

  final Color background;
  final Color border;
  final Color foreground;
}
