import 'package:flutter/material.dart';

import '../../controllers/session_controller.dart';
import '../../config/app_config.dart';
import '../../services/pengurus_mobile_repository.dart';
import '../../widgets/app/app_section_card.dart';
import '../../widgets/app/teacher_bottom_nav.dart';
import '../../widgets/app/teacher_page_header.dart';

const _pengurusPrimary = Color(0xFF00745A);
const _pengurusText = Color(0xFF172A24);

class PengurusShellPage extends StatefulWidget {
  const PengurusShellPage({super.key, required this.controller, required this.repository});
  final SessionController controller;
  final PengurusMobileRepository repository;

  @override
  State<PengurusShellPage> createState() => _PengurusShellPageState();
}

class _PengurusShellPageState extends State<PengurusShellPage> {
  int _tab = 0;

  static const _navItems = <TeacherBottomNavItem>[
    TeacherBottomNavItem(label: 'Beranda', icon: Icons.home_rounded, isCenter: false),
    TeacherBottomNavItem(label: 'Sekolah', icon: Icons.school_rounded, isCenter: false),
    TeacherBottomNavItem(label: 'Layanan', icon: Icons.grid_view_rounded, isCenter: true),
    TeacherBottomNavItem(label: 'Notifikasi', icon: Icons.notifications_none_rounded, isCenter: false),
    TeacherBottomNavItem(label: 'Profil', icon: Icons.person_rounded, isCenter: false),
  ];

  @override
  Widget build(BuildContext context) {
    final pages = [
      _PengurusDashboard(
        repository: widget.repository,
        userName: widget.controller.session?.user.name ?? 'Pengurus',
        onOpenSchools: () => setState(() => _tab = 1),
        onOpenProfile: () => setState(() => _tab = 4),
        onLogout: widget.controller.logout,
      ),
      _SchoolMonitor(repository: widget.repository, onBack: () => setState(() => _tab = 0)),
      _ServiceHubPage(onOpenSchools: () => setState(() => _tab = 1)),
      const _NotificationsPage(),
      _PengurusProfile(controller: widget.controller),
    ];

    return Scaffold(
      extendBody: true,
      backgroundColor: Colors.white,
      body: SafeArea(
        bottom: false,
        child: IndexedStack(index: _tab, children: pages),
      ),
      bottomNavigationBar: TeacherBottomNav(
        items: _navItems,
        currentIndex: _tab,
        onSelect: (value) => setState(() => _tab = value),
      ),
    );
  }
}

part 'pengurus_dashboard_page.dart';
part 'pengurus_school_page.dart';
part 'pengurus_profile_page.dart';
part 'pengurus_services_page.dart';
part 'pengurus_notifications_page.dart';
part 'pengurus_shared.dart';
