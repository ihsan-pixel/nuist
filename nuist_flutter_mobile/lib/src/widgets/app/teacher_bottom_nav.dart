import 'package:flutter/material.dart';

class TeacherBottomNavItem {
  const TeacherBottomNavItem({
    required this.label,
    required this.icon,
    required this.isCenter,
  });

  final String label;
  final IconData icon;
  final bool isCenter;
}

class TeacherBottomNav extends StatelessWidget {
  const TeacherBottomNav({
    super.key,
    required this.items,
    required this.currentIndex,
    required this.onSelect,
  });

  final List<TeacherBottomNavItem> items;
  final int currentIndex;
  final ValueChanged<int> onSelect;

  @override
  Widget build(BuildContext context) {
    final centerIndex = items.indexWhere((item) => item.isCenter);
    final leftItems = items.take(centerIndex).toList();
    final rightItems = items.skip(centerIndex + 1).toList();

    return SafeArea(
      top: false,
      child: SizedBox(
        height: 112,
        child: Stack(
          clipBehavior: Clip.none,
          alignment: Alignment.topCenter,
          children: [
            Positioned(
              left: 14,
              right: 14,
              bottom: 8,
              child: Container(
                height: 78,
                padding: const EdgeInsets.symmetric(horizontal: 12),
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(28),
                  boxShadow: const [
                    BoxShadow(
                      color: Color(0x14172A24),
                      blurRadius: 20,
                      offset: Offset(0, 8),
                    ),
                  ],
                  border: Border.all(
                    color: const Color(0xFFDCE7E3),
                  ),
                ),
                child: Row(
                  children: [
                    ...leftItems.map((item) {
                      final index = items.indexOf(item);
                      return Expanded(
                        child: _NavSideItem(
                          label: item.label,
                          icon: item.icon,
                          selected: currentIndex == index,
                          onTap: () => onSelect(index),
                        ),
                      );
                    }),
                    const SizedBox(width: 84),
                    ...rightItems.map((item) {
                      final index = items.indexOf(item);
                      return Expanded(
                        child: _NavSideItem(
                          label: item.label,
                          icon: item.icon,
                          selected: currentIndex == index,
                          onTap: () => onSelect(index),
                        ),
                      );
                    }),
                  ],
                ),
              ),
            ),
            if (centerIndex >= 0)
              Positioned(
                top: 0,
                child: _CenterNavItem(
                  label: items[centerIndex].label,
                  icon: items[centerIndex].icon,
                  selected: currentIndex == centerIndex,
                  onTap: () => onSelect(centerIndex),
                ),
              ),
          ],
        ),
      ),
    );
  }
}

class _NavSideItem extends StatelessWidget {
  const _NavSideItem({
    required this.label,
    required this.icon,
    required this.selected,
    required this.onTap,
  });

  final String label;
  final IconData icon;
  final bool selected;
  final VoidCallback onTap;

  @override
  Widget build(BuildContext context) {
    final iconColor =
        selected ? const Color(0xFF00745A) : const Color(0xFF94A3B8);

    return Material(
      color: Colors.transparent,
      child: InkWell(
        borderRadius: BorderRadius.circular(22),
        onTap: onTap,
        child: Center(
          child: Padding(
            padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 8),
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                Icon(
                  icon,
                  size: 22,
                  color: iconColor,
                ),
                const SizedBox(height: 4),
                Text(
                  label,
                  style: TextStyle(
                    fontSize: 11,
                    fontWeight: selected ? FontWeight.w800 : FontWeight.w600,
                    color: iconColor,
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}

class _CenterNavItem extends StatefulWidget {
  const _CenterNavItem({
    required this.label,
    required this.icon,
    required this.selected,
    required this.onTap,
  });

  final String label;
  final IconData icon;
  final bool selected;
  final VoidCallback onTap;

  @override
  State<_CenterNavItem> createState() => _CenterNavItemState();
}

class _CenterNavItemState extends State<_CenterNavItem> {
  bool _pressed = false;

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: widget.onTap,
      onTapDown: (_) => setState(() => _pressed = true),
      onTapUp: (_) => setState(() => _pressed = false),
      onTapCancel: () => setState(() => _pressed = false),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          AnimatedScale(
            duration: const Duration(milliseconds: 220),
            curve: Curves.easeOut,
            scale: _pressed ? 1.06 : 1,
            child: AnimatedContainer(
              duration: const Duration(milliseconds: 220),
              curve: Curves.easeOut,
              padding: const EdgeInsets.all(5),
              decoration: BoxDecoration(
                shape: BoxShape.circle,
                color: const Color(0xFFE5F5F0),
                boxShadow: const [
                  BoxShadow(
                    color: Color(0x14172A24),
                    blurRadius: 20,
                    offset: Offset(0, 8),
                  ),
                ],
                border: Border.all(
                  color: const Color(0xFFDCE7E3),
                ),
              ),
              child: Container(
                width: 62,
                height: 62,
                decoration: const BoxDecoration(
                  shape: BoxShape.circle,
                  gradient: LinearGradient(
                    colors: [
                      Color(0xFF00745A),
                      Color(0xFF00553F),
                    ],
                    begin: Alignment.topCenter,
                    end: Alignment.bottomCenter,
                  ),
                  boxShadow: [
                    BoxShadow(
                      color: Color(0x3300745A),
                      blurRadius: 20,
                      offset: Offset(0, 8),
                    ),
                  ],
                ),
                child: Icon(
                  widget.icon,
                  size: 30,
                  color: Colors.white,
                ),
              ),
            ),
          ),
          const SizedBox(height: 8),
          Text(
            widget.label,
            style: TextStyle(
              fontSize: 11,
              fontWeight: FontWeight.w800,
              color: widget.selected
                  ? const Color(0xFF00745A)
                  : const Color(0xFF94A3B8),
            ),
          ),
        ],
      ),
    );
  }
}
