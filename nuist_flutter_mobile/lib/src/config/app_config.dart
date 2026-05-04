class AppConfig {
  static const _defaultApiBaseUrl = 'https://nuist.id/api';
  static const _defaultFallbackApiBaseUrls = <String>[
    'https://www.nuist.id/api',
  ];
  static const _configuredApiBaseUrl = String.fromEnvironment('API_BASE_URL');

  static String get apiBaseUrl {
    return apiBaseUrls.first;
  }

  static List<String> get apiBaseUrls {
    final configured = _normalize(_configuredApiBaseUrl);
    if (configured != null) {
      return [configured];
    }

    return [
      _defaultApiBaseUrl,
      ..._defaultFallbackApiBaseUrls,
    ].map(_normalize).whereType<String>().toSet().toList();
  }

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
