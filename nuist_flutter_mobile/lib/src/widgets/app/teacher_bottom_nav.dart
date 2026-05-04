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
                      color: Color(0x16003B39),
                      blurRadius: 28,
                      offset: Offset(0, 10),
                    ),
                  ],
                  border: Border.all(
                    color: const Color(0xFFE2EFED),
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
    return Material(
      color: Colors.transparent,
      child: InkWell(
        borderRadius: BorderRadius.circular(22),
        onTap: onTap,
        child: Center(
          child: AnimatedContainer(
            duration: const Duration(milliseconds: 220),
            curve: Curves.easeOut,
            padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 8),
            decoration: BoxDecoration(
              color: selected ? const Color(0xFFEAF7F6) : Colors.transparent,
              borderRadius: BorderRadius.circular(22),
            ),
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                Icon(
                  icon,
                  size: 22,
                  color: selected
                      ? const Color(0xFF004B48)
                      : const Color(0xFF8BA3A1),
                ),
                const SizedBox(height: 4),
                Text(
                  label,
                  style: TextStyle(
                    fontSize: 11,
                    fontWeight: selected ? FontWeight.w800 : FontWeight.w600,
                    color: selected
                        ? const Color(0xFF004B48)
                        : const Color(0xFF8BA3A1),
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

class _CenterNavItem extends StatelessWidget {
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
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          AnimatedContainer(
            duration: const Duration(milliseconds: 220),
            curve: Curves.easeOut,
            padding: const EdgeInsets.all(5),
            decoration: BoxDecoration(
              shape: BoxShape.circle,
              color:
                  selected ? const Color(0xFFF4C272) : const Color(0xFFEAF7F6),
              boxShadow: const [
                BoxShadow(
                  color: Color(0x22004B48),
                  blurRadius: 24,
                  offset: Offset(0, 10),
                ),
              ],
            ),
            child: Container(
              width: 62,
              height: 62,
              decoration: const BoxDecoration(
                shape: BoxShape.circle,
                gradient: LinearGradient(
                  colors: [
                    Color(0xFF0D8E89),
                    Color(0xFF004B48),
                  ],
                  begin: Alignment.topCenter,
                  end: Alignment.bottomCenter,
                ),
              ),
              child: Icon(
                icon,
                size: 30,
                color: Colors.white,
              ),
            ),
          ),
          const SizedBox(height: 8),
          Text(
            label,
            style: TextStyle(
              fontSize: 11,
              fontWeight: FontWeight.w800,
              color:
                  selected ? const Color(0xFF004B48) : const Color(0xFF5B7673),
            ),
          ),
        ],
      ),
    );
  }
}
