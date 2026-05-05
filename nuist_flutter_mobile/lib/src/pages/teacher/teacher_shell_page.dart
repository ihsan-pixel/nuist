import 'package:flutter/material.dart';

import '../../controllers/session_controller.dart';
import '../../services/teacher_mobile_repository.dart';
import '../../widgets/app/teacher_bottom_nav.dart';
import 'attendance_page.dart';
import 'dashboard_page.dart';
import 'izin_manage_page.dart';
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

  static const List<TeacherBottomNavItem> _navItems = [
    TeacherBottomNavItem(
      label: 'Home',
      icon: Icons.home_rounded,
      isCenter: false,
    ),
    TeacherBottomNavItem(
      label: 'Jadwal',
      icon: Icons.calendar_month_rounded,
      isCenter: false,
    ),
    TeacherBottomNavItem(
      label: 'Presensi',
      icon: Icons.verified_user_rounded,
      isCenter: true,
    ),
    TeacherBottomNavItem(
      label: 'Jurnal',
      icon: Icons.menu_book_rounded,
      isCenter: false,
    ),
    TeacherBottomNavItem(
      label: 'Profile',
      icon: Icons.person_rounded,
      isCenter: false,
    ),
  ];

  Future<void> _openIzinPage() async {
    await Navigator.of(context).push(
      MaterialPageRoute(
        builder: (_) => TeacherIzinPage(
          repository: widget.repository,
          onOpenManageIzin: _openManageIzinPage,
        ),
      ),
    );
  }

  Future<void> _openManageIzinPage() async {
    await Navigator.of(context).push(
      MaterialPageRoute(
        builder: (_) => TeacherIzinManagePage(repository: widget.repository),
      ),
    );
  }

  Future<void> _logout() async {
    await widget.controller.logout();
  }

  void _openProfile() {
    _selectTab(4);
  }

  void _openDashboard() {
    _selectTab(0);
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
    return Scaffold(
      backgroundColor: Colors.white,
      body: SafeArea(
        top: _currentIndex != 4,
        bottom: false,
        child: IndexedStack(
          index: _currentIndex,
          children: [
            TeacherDashboardPage(
              repository: widget.repository,
              onOpenIzin: _openIzinPage,
              onOpenManageIzin: _openManageIzinPage,
              onSelectTab: _selectTab,
              onOpenProfile: _openProfile,
              onOpenSettings: _openSettings,
              onOpenNotifications: _openNotifications,
              onLogout: _logout,
            ),
            TeacherSchedulePage(
              repository: widget.repository,
              onBackToHome: _openDashboard,
            ),
            TeacherAttendancePage(
              repository: widget.repository,
              onBackToHome: _openDashboard,
              isActive: _currentIndex == 2,
            ),
            TeacherTeachingJournalPage(
              repository: widget.repository,
              onBackToHome: _openDashboard,
              isActive: _currentIndex == 3,
            ),
            TeacherProfilePage(
              repository: widget.repository,
              onOpenIzin: _openIzinPage,
              onOpenManageIzin: _openManageIzinPage,
              onBackToHome: _openDashboard,
            ),
          ],
        ),
      ),
      bottomNavigationBar: TeacherBottomNav(
        items: _navItems,
        currentIndex: _currentIndex,
        onSelect: _selectTab,
      ),
    );
  }
}
