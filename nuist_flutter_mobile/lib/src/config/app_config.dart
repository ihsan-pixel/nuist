class AppConfig {
  static const _defaultApiBaseUrl = 'https://nuist.id/api';
  static const _configuredApiBaseUrl = String.fromEnvironment('API_BASE_URL');

  static String get apiBaseUrl {
    final configured = _configuredApiBaseUrl.trim();
    final resolved = configured.isEmpty ? _defaultApiBaseUrl : configured;

    return resolved.endsWith('/')
        ? resolved.substring(0, resolved.length - 1)
        : resolved;
  }

  static bool get isPlaceholder => false;
}
