import 'package:flutter/material.dart';

class AppColors {
  static const authBgStart = Color(0xFFFFF5EA);
  static const authBgEnd = Color(0xFFFFECD6);
  static const accentMain = Color(0xFFF28C28);
  static const accentSoft = Color(0xFFF7B56B);
  static const accentDeep = Color(0xFFC86A12);
  static const textMain = Color(0xFF8B4F18);
  static const textBody = Color(0xFF7A5B3F);
  static const textMuted = Color(0xFFA07B57);
  static const inputBorder = Color(0xFFF1D4B3);
  static const inputFill = Color(0xFFFFFCF8);
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
          color: AppColors.accentMain,
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
          color: Color(0xFFC8A27E),
          fontSize: 14,
        ),
      ),
      snackBarTheme: const SnackBarThemeData(
        behavior: SnackBarBehavior.floating,
      ),
    );
  }
}
