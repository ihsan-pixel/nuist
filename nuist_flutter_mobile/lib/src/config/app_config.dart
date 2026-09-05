import 'package:flutter/foundation.dart';

class AppConfig {
  static const _defaultApiBaseUrl = 'https://nuist.id/api';
  static const _defaultFallbackApiBaseUrls = <String>[
    'https://www.nuist.id/api',
  ];
  static const _configuredApiBaseUrl = String.fromEnvironment('API_BASE_URL');
  static const _configuredEnvironment = String.fromEnvironment('APP_ENV');
  // Current MacBook LAN address for a physical Android device. Override this
  // with API_BASE_URL when the network address changes.
  static const _defaultLocalApiBaseUrl = 'http://10.219.186.244:8000/api';

  static String get apiBaseUrl {
    return apiBaseUrls.first;
  }

  static List<String> get apiBaseUrls {
    final configured = _normalize(_configuredApiBaseUrl);
    if (configured != null) {
      return [configured];
    }

    // Debug runs target the local Laravel host by default. Production builds
    // remain unchanged unless APP_ENV=local is explicitly supplied.
    if (isLocal) {
      return [_defaultLocalApiBaseUrl];
    }

    return [
      _defaultApiBaseUrl,
      ..._defaultFallbackApiBaseUrls,
    ].map(_normalize).whereType<String>().toSet().toList();
  }

  static String get webBaseUrl {
    return webBaseUrls.first;
  }

  static List<String> get webBaseUrls {
    return apiBaseUrls
        .map((url) => url.replaceFirst(RegExp(r'/api/?$'), ''))
        .map(_normalize)
        .whereType<String>()
        .toSet()
        .toList();
  }

  static String get attendanceFaceScanBridgeUrl {
    return '$webBaseUrl/mobile-face-scan-bridge.html';
  }

  static bool get isLocal =>
      _configuredEnvironment.trim().toLowerCase() == 'local' ||
      (kDebugMode && _configuredEnvironment.trim().isEmpty);

  static bool get isPlaceholder => false;

  static String? _normalize(String value) {
    final trimmed = value.trim();
    if (trimmed.isEmpty) {
      return null;
    }

    return trimmed.endsWith('/')
        ? trimmed.substring(0, trimmed.length - 1)
        : trimmed;
  }
}
