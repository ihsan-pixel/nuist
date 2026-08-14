import 'package:flutter/material.dart';

class AppColors {
  static const primary = Color(0xFF00745A);
  static const primaryDark = Color(0xFF00553F);
  static const orange = Color(0xFFF59E0B);
  static const orangeDark = Color(0xFFD97706);
  static const orangeLight = Color(0xFFFFF4D6);
  static const primaryLight = Color(0xFFE5F5F0);
  static const background = Color(0xFFFFFFFF);
  static const surface = Color(0xFFFFFFFF);
  static const textPrimary = Color(0xFF172A24);
  static const textSecondary = Color(0xFF64746E);
  static const border = Color(0xFFDCE7E3);

  static const authBgStart = background;
  static const authBgEnd = primaryLight;
  static const accentMain = primary;
  static const accentSoft = primaryLight;
  static const accentDeep = primaryDark;
  static const accentWarm = orange;
  static const accentWarmSoft = orangeLight;
  static const textMain = textPrimary;
  static const textBody = textPrimary;
  static const textMuted = textSecondary;
  static const inputBorder = border;
  static const inputFill = background;
  static const fieldError = Color(0xFFC44F4F);
  static const surfaceLine = border;
  static const shadowSoft = Color(0x14172A24);

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
      surface: AppColors.surface,
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
        backgroundColor: AppColors.surface,
        foregroundColor: AppColors.textMain,
        elevation: 0,
        centerTitle: true,
      ),
      filledButtonTheme: FilledButtonThemeData(
        style: FilledButton.styleFrom(
          backgroundColor: AppColors.accentMain,
          foregroundColor: Colors.white,
          disabledBackgroundColor: AppColors.primaryLight,
          disabledForegroundColor: AppColors.textSecondary,
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(14),
          ),
        ),
      ),
      outlinedButtonTheme: OutlinedButtonThemeData(
        style: OutlinedButton.styleFrom(
          foregroundColor: AppColors.accentDeep,
          side: const BorderSide(color: AppColors.primary, width: 1.2),
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(14),
          ),
        ),
      ),
      cardTheme: CardThemeData(
        color: AppColors.surface,
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
