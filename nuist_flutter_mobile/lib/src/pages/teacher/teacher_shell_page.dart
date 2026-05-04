import 'package:flutter/material.dart';

import '../../controllers/session_controller.dart';
import '../../services/teacher_mobile_repository.dart';
import '../../theme/app_theme.dart';
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
      icon: Icons.home_rounded,
      page: TeacherDashboardPage(
        repository: widget.repository,
        onOpenIzin: _openIzinPage,
      ),
    ),
    _ShellItem(
      title: 'Jadwal',
      icon: Icons.calendar_month_rounded,
      page: TeacherSchedulePage(repository: widget.repository),
    ),
    _ShellItem(
      title: 'Presensi',
      icon: Icons.fact_check_rounded,
      page: TeacherAttendancePage(repository: widget.repository),
    ),
    _ShellItem(
      title: 'Jurnal',
      icon: Icons.menu_book_rounded,
      page: TeacherTeachingJournalPage(repository: widget.repository),
    ),
    _ShellItem(
      title: 'Profile',
      icon: Icons.person_rounded,
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

  @override
  Widget build(BuildContext context) {
    final currentItem = _items[_currentIndex];

    return Scaffold(
      backgroundColor: const Color(0xFFF2F7F6),
      appBar: AppBar(
        title: Text(currentItem.title),
        backgroundColor: Colors.white,
        foregroundColor: AppColors.textMain,
        elevation: 0,
        scrolledUnderElevation: 0,
        actions: [
          PopupMenuButton<String>(
            onSelected: (value) {
              if (value == 'izin') {
                _openIzinPage();
                return;
              }
              if (value == 'logout') {
                _logout();
              }
            },
            itemBuilder: (context) => const [
              PopupMenuItem<String>(
                value: 'izin',
                child: Text('Daftar Izin'),
              ),
              PopupMenuItem<String>(
                value: 'logout',
                child: Text('Logout'),
              ),
            ],
          ),
        ],
      ),
      body: IndexedStack(
        index: _currentIndex,
        children: _items.map((item) => item.page).toList(),
      ),
      bottomNavigationBar: NavigationBar(
        selectedIndex: _currentIndex,
        onDestinationSelected: (value) {
          setState(() {
            _currentIndex = value;
          });
        },
        destinations: _items
            .map(
              (item) => NavigationDestination(
                icon: Icon(item.icon),
                label: item.title,
              ),
            )
            .toList(),
      ),
    );
  }
}

class _ShellItem {
  const _ShellItem({
    required this.title,
    required this.icon,
    required this.page,
  });

  final String title;
  final IconData icon;
  final Widget page;
}
