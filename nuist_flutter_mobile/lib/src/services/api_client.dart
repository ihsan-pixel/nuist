import 'package:dio/dio.dart';

class ApiClient {
  ApiClient({
    required List<String> baseUrls,
  })  : dio = Dio(
          BaseOptions(
            baseUrl: _normalizeBaseUrls(baseUrls).first,
            headers: const {
              'Accept': 'application/json',
              'Content-Type': 'application/json',
            },
            connectTimeout: const Duration(seconds: 12),
            receiveTimeout: const Duration(seconds: 45),
            sendTimeout: const Duration(seconds: 30),
          ),
        ),
        _baseUrls = _normalizeBaseUrls(baseUrls);

  final Dio dio;
  final List<String> _baseUrls;
  int _currentBaseUrlIndex = 0;

  String get baseUrl => _baseUrls[_currentBaseUrlIndex];

  bool switchToNextBaseUrl() {
    if (_currentBaseUrlIndex >= _baseUrls.length - 1) {
      return false;
    }

    _currentBaseUrlIndex += 1;
    dio.options.baseUrl = _baseUrls[_currentBaseUrlIndex];
    return true;
  }

  void setAuthToken(String? token) {
    if (token == null || token.isEmpty) {
      dio.options.headers.remove('Authorization');
      return;
    }

    dio.options.headers['Authorization'] = 'Bearer $token';
  }

  static List<String> _normalizeBaseUrls(List<String> baseUrls) {
    final normalized = baseUrls
        .map((item) => item.trim())
        .where((item) => item.isNotEmpty)
        .toSet()
        .toList();

    if (normalized.isEmpty) {
      throw ArgumentError('At least one API base URL is required.');
    }

    return normalized;
  }
}
