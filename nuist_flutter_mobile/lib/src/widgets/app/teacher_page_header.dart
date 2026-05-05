import 'package:flutter/material.dart';

class TeacherPageHeader extends StatelessWidget {
  const TeacherPageHeader({
    super.key,
    required this.title,
    required this.onBack,
  });

  final String title;
  final VoidCallback onBack;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.fromLTRB(14, 14, 14, 14),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(28),
        boxShadow: const [
          BoxShadow(
            color: Color(0x0F003B39),
            blurRadius: 18,
            offset: Offset(0, 8),
          ),
        ],
        border: const Border.fromBorderSide(
          BorderSide(
            color: Color(0xFFE6EEEE),
          ),
        ),
      ),
      child: Row(
        children: [
          Material(
            color: const Color(0xFFFFF4E8),
            borderRadius: BorderRadius.circular(16),
            child: InkWell(
              onTap: onBack,
              borderRadius: BorderRadius.circular(16),
              child: const SizedBox(
                width: 40,
                height: 40,
                child: Icon(
                  Icons.arrow_back_ios_new_rounded,
                  color: Color(0xFFF49637),
                  size: 18,
                ),
              ),
            ),
          ),
          Expanded(
            child: Text(
              title,
              textAlign: TextAlign.center,
              style: const TextStyle(
                color: Color(0xFF7A4212),
                fontSize: 19,
                fontWeight: FontWeight.w800,
              ),
            ),
          ),
          SizedBox(
            width: 40,
            child: Center(
              child: Container(
                width: 8,
                height: 8,
                decoration: const BoxDecoration(
                  color: Color(0xFFF49637),
                  shape: BoxShape.circle,
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }
}
