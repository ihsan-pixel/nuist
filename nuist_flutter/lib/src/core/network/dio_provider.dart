import 'package:dio/dio.dart';
import 'package:flutter/foundation.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../config/api_base_url_controller.dart';
import '../storage/token_storage.dart';
import 'api_exception.dart';

final dioProvider = Provider<Dio>((ref) {
  final storage = ref.watch(tokenStorageProvider);
  final baseUrl = ref.watch(apiBaseUrlProvider);
  final dio = Dio(
    BaseOptions(
      baseUrl: baseUrl,
      connectTimeout: const Duration(seconds: 20),
      receiveTimeout: const Duration(seconds: 20),
      headers: <String, String>{
        'Accept': 'application/json',
      },
    ),
  );

  dio.interceptors.add(
    QueuedInterceptorsWrapper(
      onRequest: (options, handler) async {
        final token = await storage.readToken();
        if (token != null && token.isNotEmpty) {
          options.headers['Authorization'] = 'Bearer $token';
        }
        handler.next(options);
      },
      onError: (error, handler) {
        final statusCode = error.response?.statusCode;
        final responseData = error.response?.data;
        final message = responseData is Map<String, dynamic>
            ? responseData['message']?.toString()
            : null;
        final fallbackMessage = _buildFallbackMessage(error, baseUrl);

        handler.reject(
          DioException(
            requestOptions: error.requestOptions,
            response: error.response,
            type: error.type,
            error: ApiException(
              message ?? fallbackMessage,
              statusCode: statusCode,
            ),
          ),
        );
      },
    ),
  );

  return dio;
});

String _buildFallbackMessage(DioException error, String baseUrl) {
  if (error.response == null) {
    if (kIsWeb) {
      return 'Tidak bisa terhubung ke API $baseUrl. Pastikan Laravel aktif, URL API benar, dan endpoint ini bisa diakses dari browser.';
    }

    return 'Tidak bisa terhubung ke API $baseUrl. Pastikan Laravel aktif dan BASE_URL sudah benar.';
  }

  return 'Terjadi kesalahan jaringan.';
}
