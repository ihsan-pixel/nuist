import 'package:dio/dio.dart';
import 'package:flutter/foundation.dart';

class FaceEmbeddingService {
  FaceEmbeddingService({
    Dio? client,
    String? baseUrl,
  })  : _client = client ??
            Dio(
              BaseOptions(
                baseUrl: baseUrl ?? _defaultBaseUrl(),
                connectTimeout: const Duration(seconds: 5),
                receiveTimeout: const Duration(seconds: 20),
                sendTimeout: const Duration(seconds: 20),
                contentType: Headers.jsonContentType,
                responseType: ResponseType.json,
              ),
            );

  final Dio _client;

  static String _defaultBaseUrl() {
    const fromDefine = String.fromEnvironment('FACE_ENGINE_BASE_URL');
    if (fromDefine.isNotEmpty) {
      return fromDefine.replaceAll(RegExp(r'/$'), '');
    }

    if (kIsWeb) {
      return 'http://127.0.0.1:8800';
    }

    switch (defaultTargetPlatform) {
      case TargetPlatform.android:
        return 'http://10.0.2.2:8800';
      case TargetPlatform.iOS:
        return 'http://127.0.0.1:8800';
      case TargetPlatform.macOS:
      case TargetPlatform.windows:
      case TargetPlatform.linux:
      case TargetPlatform.fuchsia:
        return 'http://127.0.0.1:8800';
    }
  }

  Future<bool> isEngineReady() async {
    try {
      final response = await _client.get<Map<String, dynamic>>('/health');
      return response.data?['success'] == true && response.data?['model_ready'] == true;
    } catch (_) {
      return false;
    }
  }
}
