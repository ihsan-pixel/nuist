import 'package:flutter/material.dart';

class AppColors {
  static const authBgStart = Color(0xFFF2F8F4);
  static const authBgEnd = Color(0xFFE1EEE7);
  static const accentMain = Color(0xFF1F6B52);
  static const accentSoft = Color(0xFF4D8D74);
  static const accentDeep = Color(0xFF174C3D);
  static const textMain = Colors.black;
  static const textBody = Colors.black;
  static const textMuted = Colors.black;
  static const inputBorder = Color(0xFFC8DDD3);
  static const inputFill = Color(0xFFFCFEFC);
  static const fieldError = Color(0xFFC44F4F);

  const AppColors._();
}

class AppTheme {
  const AppTheme._();

  static ThemeData build() {
    return ThemeData(
      useMaterial3: true,
      scaffoldBackgroundColor: AppColors.authBgStart,
      colorScheme: ColorScheme.fromSeed(
        seedColor: AppColors.accentMain,
        brightness: Brightness.light,
      ),
      textTheme: const TextTheme(
        headlineLarge: TextStyle(
          fontSize: 32,
          fontWeight: FontWeight.w700,
          letterSpacing: -0.9,
          color: Colors.black,
        ),
        titleMedium: TextStyle(
          fontSize: 15,
          fontWeight: FontWeight.w500,
          color: AppColors.textMuted,
        ),
        bodyMedium: TextStyle(
          fontSize: 15,
          color: AppColors.textBody,
        ),
      ),
      inputDecorationTheme: InputDecorationTheme(
        filled: true,
        fillColor: AppColors.inputFill,
        contentPadding: const EdgeInsets.symmetric(
          horizontal: 14,
          vertical: 12,
        ),
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(14),
          borderSide: const BorderSide(color: AppColors.inputBorder),
        ),
        enabledBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(14),
          borderSide: const BorderSide(color: AppColors.inputBorder),
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(14),
          borderSide: const BorderSide(
            color: AppColors.accentMain,
            width: 1.2,
          ),
        ),
        errorBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(14),
          borderSide: const BorderSide(color: AppColors.fieldError),
        ),
        focusedErrorBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(14),
          borderSide: const BorderSide(
            color: AppColors.fieldError,
            width: 1.2,
          ),
        ),
        hintStyle: const TextStyle(
          color: Colors.black,
          fontSize: 14,
        ),
      ),
      snackBarTheme: const SnackBarThemeData(
        behavior: SnackBarBehavior.floating,
      ),
    );
  }
}
