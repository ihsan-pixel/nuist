import 'package:flutter/material.dart';

import '../../controllers/session_controller.dart';
import '../../services/teacher_mobile_repository.dart';
import '../../theme/app_theme.dart';
import '../../widgets/app/teacher_bottom_nav.dart';
import 'attendance_page.dart';
import 'dashboard_page.dart';
import 'izin_page.dart';
import 'profile_page.dart';
import 'schedule_page.dart';
import 'teaching_journal_page.dart';

class TeacherShellPage extends StatefulWidget {
  const TeacherShellPage({
    super.key,
    required this.controller,
    required this.repository,
  });

  final SessionController controller;
  final TeacherMobileRepository repository;

  @override
  State<TeacherShellPage> createState() => _TeacherShellPageState();
}

class _TeacherShellPageState extends State<TeacherShellPage> {
  int _currentIndex = 0;

  late final List<_ShellItem> _items = [
    _ShellItem(
      title: 'Dashboard',
      navItem: const TeacherBottomNavItem(
        label: 'Home',
        icon: Icons.home_rounded,
        isCenter: false,
      ),
      page: TeacherDashboardPage(
        repository: widget.repository,
        onOpenIzin: _openIzinPage,
        onSelectTab: _selectTab,
        onOpenProfile: _openProfile,
        onOpenSettings: _openSettings,
        onOpenNotifications: _openNotifications,
        onLogout: _logout,
      ),
    ),
    _ShellItem(
      title: 'Jadwal',
      navItem: const TeacherBottomNavItem(
        label: 'Jadwal',
        icon: Icons.calendar_month_rounded,
        isCenter: false,
      ),
      page: TeacherSchedulePage(repository: widget.repository),
    ),
    _ShellItem(
      title: 'Presensi',
      navItem: const TeacherBottomNavItem(
        label: 'Presensi',
        icon: Icons.verified_user_rounded,
        isCenter: true,
      ),
      page: TeacherAttendancePage(repository: widget.repository),
    ),
    _ShellItem(
      title: 'Jurnal',
      navItem: const TeacherBottomNavItem(
        label: 'Jurnal',
        icon: Icons.menu_book_rounded,
        isCenter: false,
      ),
      page: TeacherTeachingJournalPage(repository: widget.repository),
    ),
    _ShellItem(
      title: 'Profile',
      navItem: const TeacherBottomNavItem(
        label: 'Profile',
        icon: Icons.person_rounded,
        isCenter: false,
      ),
      page: TeacherProfilePage(
        repository: widget.repository,
        onOpenIzin: _openIzinPage,
      ),
    ),
  ];

  Future<void> _openIzinPage() async {
    await Navigator.of(context).push(
      MaterialPageRoute(
        builder: (_) => TeacherIzinPage(repository: widget.repository),
      ),
    );
  }

  Future<void> _logout() async {
    await widget.controller.logout();
  }

  void _openProfile() {
    _selectTab(4);
  }

  void _openSettings() {
    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(
        content: Text('Halaman pengaturan akan segera tersedia.'),
      ),
    );
  }

  void _openNotifications() {
    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(
        content: Text('Belum ada notifikasi baru.'),
      ),
    );
  }

  void _selectTab(int index) {
    if (!mounted) {
      return;
    }

    setState(() {
      _currentIndex = index;
    });
  }

  @override
  Widget build(BuildContext context) {
    final currentItem = _items[_currentIndex];
    final showDashboardHeader = _currentIndex == 0;

    return Scaffold(
      backgroundColor: const Color(0xFFF2F7F6),
      appBar: showDashboardHeader
          ? null
          : AppBar(
              title: Text(currentItem.title),
              backgroundColor: Colors.white,
              foregroundColor: AppColors.textMain,
              elevation: 0,
              scrolledUnderElevation: 0,
            ),
      body: SafeArea(
        top: showDashboardHeader,
        bottom: false,
        child: IndexedStack(
          index: _currentIndex,
          children: _items.map((item) => item.page).toList(),
        ),
      ),
      bottomNavigationBar: TeacherBottomNav(
        items: _items.map((item) => item.navItem).toList(),
        currentIndex: _currentIndex,
        onSelect: _selectTab,
      ),
    );
  }
}

class _ShellItem {
  const _ShellItem({
    required this.title,
    required this.navItem,
    required this.page,
  });

  final String title;
  final TeacherBottomNavItem navItem;
  final Widget page;
}
