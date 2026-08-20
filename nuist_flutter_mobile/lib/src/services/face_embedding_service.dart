import 'dart:convert';
import 'dart:io';

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

  Future<List<double>?> extractEmbedding(File imageFile) async {
    if (!await imageFile.exists()) {
      return null;
    }

    final frame = 'data:image/jpeg;base64,${base64Encode(await imageFile.readAsBytes())}';

    try {
      final response = await _client.post<Map<String, dynamic>>(
        '/api/v1/enroll',
        data: {
          'teacher_id': 0,
          'teacher_name': 'flutter-face-client',
          'frames': [frame],
          'device_info': 'flutter_face_embedding_client',
        },
      );

      final body = response.data;
      final embedding = body == null ? null : body['face_embedding'];
      if (embedding is List) {
        final values = embedding
            .whereType<num>()
            .map((value) => value.toDouble())
            .toList(growable: false);
        if (values.length == 128) {
          return values;
        }
      }
    } catch (_) {
      return null;
    }

    return null;
  }
}
