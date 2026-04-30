import 'package:flutter/foundation.dart';

class AppConfig {
  static const String _definedBaseUrl = String.fromEnvironment(
    'BASE_URL',
    defaultValue: '',
  );

  static String get baseUrl {
    final configuredBaseUrl = _normalizeBaseUrl(_definedBaseUrl);
    if (configuredBaseUrl != null) {
      return configuredBaseUrl;
    }

    if (kIsWeb) {
      return 'http://localhost:8000/';
    }

    switch (defaultTargetPlatform) {
      case TargetPlatform.android:
        return 'http://10.0.2.2:8000/';
      case TargetPlatform.iOS:
      case TargetPlatform.macOS:
      case TargetPlatform.linux:
      case TargetPlatform.windows:
        return 'http://localhost:8000/';
      case TargetPlatform.fuchsia:
        return 'http://localhost:8000/';
    }
  }

  static String? _normalizeBaseUrl(String value) {
    final trimmedValue = value.trim();
    if (trimmedValue.isEmpty) {
      return null;
    }

    return trimmedValue.endsWith('/') ? trimmedValue : '$trimmedValue/';
  }
}
