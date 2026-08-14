import 'package:flutter/material.dart';

import '../../controllers/session_controller.dart';
import '../../services/student_mobile_repository.dart';
import '../../widgets/app/teacher_bottom_nav.dart';
import 'dashboard_page.dart';
import 'payment_history_page.dart';
import 'payment_page.dart';
import 'profile_page.dart';
import 'tagihan_page.dart';

class StudentShellPage extends StatefulWidget {
  const StudentShellPage({
    super.key,
    required this.controller,
    required this.repository,
  });

  final SessionController controller;
  final StudentMobileRepository repository;

  @override
  State<StudentShellPage> createState() => _StudentShellPageState();
}

class _StudentShellPageState extends State<StudentShellPage> {
  int _currentIndex = 0;
  int _dataRevision = 0;

  static const List<TeacherBottomNavItem> _navItems = [
    TeacherBottomNavItem(
      label: 'Home',
      icon: Icons.home_rounded,
      isCenter: false,
    ),
    TeacherBottomNavItem(
      label: 'Tagihan',
      icon: Icons.receipt_long_rounded,
      isCenter: false,
    ),
    TeacherBottomNavItem(
      label: 'Bayar',
      icon: Icons.account_balance_wallet_rounded,
      isCenter: true,
    ),
    TeacherBottomNavItem(
      label: 'Riwayat',
      icon: Icons.history_rounded,
      isCenter: false,
    ),
    TeacherBottomNavItem(
      label: 'Profil',
      icon: Icons.person_rounded,
      isCenter: false,
    ),
  ];

  void _selectTab(int index) {
    if (!mounted) {
      return;
    }
    setState(() {
      _currentIndex = index;
    });
  }

  void _handleDataChanged() {
    if (!mounted) {
      return;
    }
    setState(() {
      _dataRevision++;
    });
  }

  Future<void> _logout() async {
    await widget.controller.logout();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      extendBody: true,
      backgroundColor: Colors.white,
      body: SafeArea(
        bottom: false,
        child: IndexedStack(
          index: _currentIndex,
          children: [
            StudentDashboardPage(
              repository: widget.repository,
              dataRevision: _dataRevision,
              onSelectTab: _selectTab,
              onLogout: _logout,
            ),
            StudentBillsPage(
              repository: widget.repository,
              dataRevision: _dataRevision,
              onBackToHome: () => _selectTab(0),
              onOpenPaymentTab: () => _selectTab(2),
            ),
            StudentPaymentPage(
              repository: widget.repository,
              dataRevision: _dataRevision,
              onDataChanged: _handleDataChanged,
              onBackToHome: () => _selectTab(0),
              onOpenHistoryTab: () => _selectTab(3),
            ),
            StudentPaymentHistoryPage(
              repository: widget.repository,
              dataRevision: _dataRevision,
              onBackToHome: () => _selectTab(0),
            ),
            StudentProfilePage(
              repository: widget.repository,
              dataRevision: _dataRevision,
              onBackToHome: () => _selectTab(0),
              onLogout: _logout,
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
