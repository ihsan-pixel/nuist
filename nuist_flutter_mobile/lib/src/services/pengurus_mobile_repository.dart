import 'package:dio/dio.dart';

import 'api_client.dart';

class PengurusMobileRepository {
  PengurusMobileRepository({required ApiClient apiClient}) : _apiClient = apiClient;
  final ApiClient _apiClient;

  Future<Map<String, dynamic>> dashboard() => _get('/mobile/app/pengurus/dashboard');
  Future<Map<String, dynamic>> schools() => _get('/mobile/app/pengurus/schools');

  Future<Map<String, dynamic>> _get(String path) async {
    final response = await _apiClient.dio.get<Map<String, dynamic>>(
      path,
      options: Options(
        connectTimeout: const Duration(seconds: 10),
        receiveTimeout: const Duration(seconds: 12),
      ),
    );
    final data = response.data?['data'];
    return data is Map ? Map<String, dynamic>.from(data) : <String, dynamic>{};
  }
}
