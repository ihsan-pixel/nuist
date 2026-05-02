import 'package:flutter/material.dart';

import 'features/auth/login_screen.dart';
import 'features/home/home_screen.dart';
import 'features/izin/izin_detail_screen.dart';

class NuistApp extends StatelessWidget {
  const NuistApp({super.key});

  @override
  Widget build(BuildContext context) {
    const seedColor = Color(0xFF135D66);

    return MaterialApp(
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
      home: const LoginScreen(),
      onGenerateRoute: (settings) {
        if (settings.name == HomeScreen.routePath) {
          return MaterialPageRoute<void>(
            builder: (_) => const HomeScreen(),
            settings: settings,
          );
        }

        if (settings.name != null && settings.name!.startsWith('/izin/')) {
          final izinId = settings.name!.replaceFirst('/izin/', '');
          return MaterialPageRoute<void>(
            builder: (_) => IzinDetailScreen(izinId: izinId),
            settings: settings,
          );
        }

        return MaterialPageRoute<void>(
          builder: (_) => const LoginScreen(),
          settings: settings,
        );
      },
    );
  }
}
