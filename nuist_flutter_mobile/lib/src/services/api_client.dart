import 'package:dio/dio.dart';

class ApiClient {
  ApiClient({
    required String baseUrl,
  }) : dio = Dio(
          BaseOptions(
            baseUrl: baseUrl,
            headers: const {
              'Accept': 'application/json',
              'Content-Type': 'application/json',
            },
            connectTimeout: const Duration(seconds: 30),
            receiveTimeout: const Duration(seconds: 45),
            sendTimeout: const Duration(seconds: 30),
          ),
        );

  final Dio dio;

  void setAuthToken(String? token) {
    if (token == null || token.isEmpty) {
      dio.options.headers.remove('Authorization');
      return;
    }

    dio.options.headers['Authorization'] = 'Bearer $token';
  }
}
