import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';

import 'features/auth/login_screen.dart';
import 'features/auth/splash_screen.dart';
import 'features/home/home_screen.dart';
import 'features/izin/izin_detail_screen.dart';

final GoRouter _router = GoRouter(
  initialLocation: SplashScreen.routePath,
  routes: <RouteBase>[
    GoRoute(
      path: SplashScreen.routePath,
      builder: (context, state) => const SplashScreen(),
    ),
    GoRoute(
      path: LoginScreen.routePath,
      builder: (context, state) => const LoginScreen(),
    ),
    GoRoute(
      path: HomeScreen.routePath,
      builder: (context, state) => const HomeScreen(),
    ),
    GoRoute(
      path: IzinDetailScreen.routePath,
      builder: (context, state) {
        final izinId = state.pathParameters['id'] ?? '';
        return IzinDetailScreen(izinId: izinId);
      },
    ),
  ],
);

class NuistApp extends StatelessWidget {
  const NuistApp({super.key});

  @override
  Widget build(BuildContext context) {
    const seedColor = Color(0xFF135D66);

    return MaterialApp.router(
      title: 'Nuist Flutter',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        colorScheme: ColorScheme.fromSeed(
          seedColor: seedColor,
          brightness: Brightness.light,
        ),
        useMaterial3: true,
        scaffoldBackgroundColor: const Color(0xFFF7F8F4),
        appBarTheme: const AppBarTheme(
          backgroundColor: Colors.transparent,
          foregroundColor: Color(0xFF0F172A),
          elevation: 0,
          centerTitle: false,
        ),
        cardTheme: CardTheme(
          color: Colors.white,
          elevation: 0,
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(20),
          ),
        ),
      ),
      routerConfig: _router,
      builder: (context, child) => child ?? const SizedBox.shrink(),
    );
  }
}
