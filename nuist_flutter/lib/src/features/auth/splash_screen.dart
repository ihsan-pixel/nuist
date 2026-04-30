import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';

import '../../core/config/api_base_url_controller.dart';
import '../home/home_screen.dart';
import 'auth_controller.dart';
import 'login_screen.dart';

class SplashScreen extends ConsumerStatefulWidget {
  const SplashScreen({super.key});

  static const String routePath = '/';

  @override
  ConsumerState<SplashScreen> createState() => _SplashScreenState();
}

class _SplashScreenState extends ConsumerState<SplashScreen> {
  @override
  void initState() {
    super.initState();
    Future<void>.microtask(() async {
      try {
        await ref.read(apiBaseUrlProvider.notifier).hydrate();
        if (!mounted) {
          return;
        }
        await ref.read(authControllerProvider.notifier).restoreSession();
      } catch (_) {
        if (!mounted) {
          return;
        }
        context.go(LoginScreen.routePath);
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    ref.listen<AuthState>(authControllerProvider, (previous, next) {
      if (next.status == SessionStatus.authenticated) {
        context.go(HomeScreen.routePath);
      } else if (next.status == SessionStatus.unauthenticated) {
        context.go(LoginScreen.routePath);
      }
    });

    return const Scaffold(
      body: Center(
        child: CircularProgressIndicator(),
      ),
    );
  }
}
