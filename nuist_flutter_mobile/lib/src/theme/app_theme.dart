import 'package:flutter/material.dart';

class AppColors {
  static const authBgStart = Color(0xFFF6FBF4);
  static const authBgEnd = Color(0xFFEAF6EA);
  static const accentMain = Color(0xFF04A512);
  static const accentSoft = Color(0xFFE1F6E4);
  static const accentDeep = Color(0xFF037A0D);
  static const accentWarm = Color(0xFFFF8A1F);
  static const accentWarmSoft = Color(0xFFFFE7CF);
  static const textMain = Color(0xFF132A16);
  static const textBody = Color(0xFF223526);
  static const textMuted = Color(0xFF617565);
  static const inputBorder = Color(0xFFD3E6D4);
  static const inputFill = Color(0xFFFCFEFC);
  static const fieldError = Color(0xFFC44F4F);
  static const surfaceLine = Color(0xFFE6EFE4);
  static const shadowSoft = Color(0x14043A0B);

  const AppColors._();
}

class AppTheme {
  const AppTheme._();

  static ThemeData build() {
    final colorScheme = ColorScheme.fromSeed(
      seedColor: AppColors.accentMain,
      brightness: Brightness.light,
    ).copyWith(
      primary: AppColors.accentMain,
      onPrimary: Colors.white,
      secondary: AppColors.accentWarm,
      onSecondary: Colors.white,
      tertiary: AppColors.accentDeep,
      onTertiary: Colors.white,
      surface: Colors.white,
      onSurface: AppColors.textMain,
      outline: AppColors.inputBorder,
    );

    return ThemeData(
      useMaterial3: true,
      scaffoldBackgroundColor: AppColors.authBgStart,
      colorScheme: colorScheme,
      textTheme: const TextTheme(
        headlineLarge: TextStyle(
          fontSize: 32,
          fontWeight: FontWeight.w700,
          letterSpacing: -0.9,
          color: AppColors.textMain,
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
      appBarTheme: const AppBarTheme(
        backgroundColor: Colors.white,
        foregroundColor: AppColors.textMain,
        elevation: 0,
        centerTitle: true,
      ),
      filledButtonTheme: FilledButtonThemeData(
        style: FilledButton.styleFrom(
          backgroundColor: AppColors.accentMain,
          foregroundColor: Colors.white,
          disabledBackgroundColor: const Color(0xFFCBE8CE),
          disabledForegroundColor: const Color(0xFF7A9580),
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(14),
          ),
        ),
      ),
      outlinedButtonTheme: OutlinedButtonThemeData(
        style: OutlinedButton.styleFrom(
          foregroundColor: AppColors.accentDeep,
          side: const BorderSide(color: AppColors.accentWarm, width: 1.2),
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(14),
          ),
        ),
      ),
      cardTheme: CardThemeData(
        color: Colors.white,
        elevation: 0,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(24),
          side: const BorderSide(color: AppColors.surfaceLine),
        ),
      ),
      snackBarTheme: const SnackBarThemeData(
        behavior: SnackBarBehavior.floating,
        backgroundColor: AppColors.textMain,
        contentTextStyle: TextStyle(
          color: Colors.white,
          fontWeight: FontWeight.w600,
        ),
      ),
      progressIndicatorTheme: const ProgressIndicatorThemeData(
        color: AppColors.accentMain,
      ),
      floatingActionButtonTheme: const FloatingActionButtonThemeData(
        backgroundColor: AppColors.accentWarm,
        foregroundColor: Colors.white,
      ),
      chipTheme: ChipThemeData(
        backgroundColor: AppColors.accentSoft,
        selectedColor: AppColors.accentWarmSoft,
        secondarySelectedColor: AppColors.accentWarmSoft,
        labelStyle: const TextStyle(
          color: AppColors.textMain,
          fontWeight: FontWeight.w700,
        ),
        side: const BorderSide(color: AppColors.surfaceLine),
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(14),
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
          color: AppColors.textMuted,
          fontSize: 14,
        ),
      ),
    );
  }
}
