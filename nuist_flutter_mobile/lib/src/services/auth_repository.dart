import 'package:dio/dio.dart';
import 'package:flutter/foundation.dart';

import '../models/app_user.dart';
import '../models/session.dart';
import 'api_client.dart';
import 'token_storage.dart';

class AuthRepository {
  AuthRepository({
    required ApiClient apiClient,
    required TokenStorage tokenStorage,
  })  : _apiClient = apiClient,
        _tokenStorage = tokenStorage;

  final ApiClient _apiClient;
  final TokenStorage _tokenStorage;

  Session? _session;

  Future<Session> login({
    required String email,
    required String password,
    required bool rememberSession,
  }) async {
    try {
      final response = await _apiClient.dio.post<Map<String, dynamic>>(
        '/mobile/login',
        data: {
          'email': email,
          'password': password,
        },
      );

      final session =
          Session.fromLoginJson(response.data ?? <String, dynamic>{});
      _session = session;
      _apiClient.setAuthToken(session.token);

      if (rememberSession) {
        await _tokenStorage.writeToken(session.token);
      } else {
        await _tokenStorage.deleteToken();
      }

      return session;
    } on DioException catch (error) {
      debugPrint(
        'Login request failed: status=${error.response?.statusCode} body=${error.response?.data}',
      );
      throw _mapDioError(error);
    }
  }

  Future<Session?> restoreSession() async {
    final token = await _tokenStorage.readToken();
    if (token == null || token.isEmpty) {
      return null;
    }

    try {
      _apiClient.setAuthToken(token);
      final user = await _fetchCurrentUser();
      _session = Session(token: token, user: user);
      return _session;
    } on DioException {
      await _tokenStorage.deleteToken();
      _apiClient.setAuthToken(null);
      return null;
    }
  }

  Future<Session> refreshSession() async {
    final currentSession = _session;
    if (currentSession == null) {
      throw 'Belum ada sesi aktif.';
    }

    try {
      final user = await _fetchCurrentUser();
      _session = currentSession.copyWith(user: user);
      return _session!;
    } on DioException catch (error) {
      debugPrint(
        'Refresh session failed: status=${error.response?.statusCode} body=${error.response?.data}',
      );
      throw _mapDioError(error);
    }
  }

  Future<Map<String, dynamic>> getDashboard() async {
    try {
      final response = await _apiClient.dio.get<Map<String, dynamic>>(
        '/mobile/dashboard',
      );
      return response.data ?? <String, dynamic>{};
    } on DioException catch (error) {
      debugPrint(
        'Dashboard request failed: status=${error.response?.statusCode} body=${error.response?.data}',
      );
      throw _mapDioError(error);
    }
  }

  Future<void> logout() async {
    try {
      await _apiClient.dio.post<void>('/mobile/logout');
    } on DioException {
      // Token lokal tetap harus dibersihkan walau revoke gagal.
    } finally {
      _session = null;
      _apiClient.setAuthToken(null);
      await _tokenStorage.deleteToken();
    }
  }

  Future<AppUser> _fetchCurrentUser() async {
    final response =
        await _apiClient.dio.get<Map<String, dynamic>>('/mobile/me');
    final data = response.data ?? <String, dynamic>{};

    if (data['user'] is Map<String, dynamic>) {
      return AppUser.fromJson(data['user'] as Map<String, dynamic>);
    }

    return AppUser.fromJson(data);
  }

  String _mapDioError(DioException error) {
    final responseData = error.response?.data;
    if (responseData is Map<String, dynamic>) {
      final message = responseData['message'];
      if (message is String && message.trim().isNotEmpty) {
        return message;
      }

      final errors = responseData['errors'];
      if (errors is Map<String, dynamic>) {
        for (final value in errors.values) {
          if (value is List && value.isNotEmpty) {
            final first = value.first;
            if (first is String && first.trim().isNotEmpty) {
              return first;
            }
          }
        }
      }
    }

    if (responseData is String && responseData.trim().isNotEmpty) {
      return responseData;
    }

    final statusCode = error.response?.statusCode;
    if (statusCode != null) {
      return 'Request gagal dengan status HTTP $statusCode.';
    }

    switch (error.type) {
      case DioExceptionType.connectionTimeout:
      case DioExceptionType.receiveTimeout:
      case DioExceptionType.sendTimeout:
        return 'Koneksi ke server timeout.';
      case DioExceptionType.connectionError:
        return 'Tidak bisa terhubung ke server Laravel.';
      default:
        return 'Request gagal dijalankan.';
    }
  }
}
