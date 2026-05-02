import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../core/config/api_base_url_controller.dart';
import '../../core/navigation/app_navigation.dart';
import 'auth_controller.dart';

class SplashScreen extends ConsumerStatefulWidget {
  const SplashScreen({super.key});

  static const String routePath = '/';

  @override
  ConsumerState<SplashScreen> createState() => _SplashScreenState();
}

class _SplashScreenState extends ConsumerState<SplashScreen> {
  bool _hasNavigated = false;

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) async {
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
        AppNavigation.goLogin(context);
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    final authState = ref.watch(authControllerProvider);

    if (!_hasNavigated && authState.status != SessionStatus.checking) {
      _hasNavigated = true;
      WidgetsBinding.instance.addPostFrameCallback((_) {
        if (!mounted) {
          return;
        }
        if (authState.status == SessionStatus.authenticated) {
          AppNavigation.goHome(context);
        } else {
          AppNavigation.goLogin(context);
        }
      });
    }

    return const Scaffold(
      body: Center(
        child: CircularProgressIndicator(),
      ),
    );
  }
}
