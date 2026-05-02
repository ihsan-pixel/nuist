import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';

import '../../features/auth/login_screen.dart';
import '../../features/home/home_screen.dart';
import '../../features/izin/izin_detail_screen.dart';

class AppNavigation {
  static void goLogin(BuildContext context) {
    final router = GoRouter.maybeOf(context);
    if (router != null) {
      context.go(LoginScreen.routePath);
      return;
    }

    Navigator.of(context).pushAndRemoveUntil(
      MaterialPageRoute<void>(
        builder: (_) => const LoginScreen(),
        settings: const RouteSettings(name: LoginScreen.routePath),
      ),
      (route) => false,
    );
  }

  static void goHome(BuildContext context) {
    final router = GoRouter.maybeOf(context);
    if (router != null) {
      context.go(HomeScreen.routePath);
      return;
    }

    Navigator.of(context).pushAndRemoveUntil(
      MaterialPageRoute<void>(
        builder: (_) => const HomeScreen(),
        settings: const RouteSettings(name: HomeScreen.routePath),
      ),
      (route) => false,
    );
  }

  static void goIzinDetail(BuildContext context, String izinId) {
    final router = GoRouter.maybeOf(context);
    if (router != null) {
      context.go('/izin/$izinId');
      return;
    }

    Navigator.of(context).push(
      MaterialPageRoute<void>(
        builder: (_) => IzinDetailScreen(izinId: izinId),
        settings: RouteSettings(name: '/izin/$izinId'),
      ),
    );
  }
}
