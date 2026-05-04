import 'package:dio/dio.dart';
import 'package:flutter/foundation.dart';

import '../models/app_user.dart';
import '../models/madrasah_option.dart';
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

  Future<List<MadrasahOption>> getRegisterOptions() async {
    try {
      final response = await _withRetry<Map<String, dynamic>>(
        request: () => _apiClient.dio.get<Map<String, dynamic>>(
          '/mobile/register/options',
        ),
        actionLabel: 'opsi registrasi',
      );

      final items = response.data?['madrasahs'];
      if (items is! List) {
        return const [];
      }

      return items
          .whereType<Map>()
          .map(
            (item) => MadrasahOption.fromJson(
              Map<String, dynamic>.from(item),
            ),
          )
          .toList();
    } on DioException catch (error) {
      debugPrint(
        'Register options request failed: '
        'status=${error.response?.statusCode} body=${error.response?.data}',
      );
      throw _mapDioError(error);
    }
  }

  Future<String> register({
    required String name,
    required String email,
    required String password,
    required String passwordConfirmation,
    required String role,
    String? jabatan,
    int? asalSekolahId,
  }) async {
    try {
      final response = await _withRetry<Map<String, dynamic>>(
        request: () => _apiClient.dio.post<Map<String, dynamic>>(
          '/mobile/register',
          data: {
            'name': name,
            'email': email,
            'password': password,
            'password_confirmation': passwordConfirmation,
            'role': role,
            'jabatan': jabatan,
            'asal_sekolah': asalSekolahId,
          },
        ),
        actionLabel: 'registrasi',
      );

      return _extractMessage(
        response.data,
        fallback:
            'Pendaftaran berhasil dikirim. Silakan tunggu persetujuan admin.',
      );
    } on DioException catch (error) {
      debugPrint(
        'Register request failed: '
        'status=${error.response?.statusCode} body=${error.response?.data}',
      );
      throw _mapDioError(error);
    }
  }

  Future<String> sendPasswordResetLink({
    required String email,
  }) async {
    try {
      final response = await _withRetry<Map<String, dynamic>>(
        request: () => _apiClient.dio.post<Map<String, dynamic>>(
          '/mobile/forgot-password',
          data: {
            'email': email,
          },
        ),
        actionLabel: 'forgot password',
      );

      return _extractMessage(
        response.data,
        fallback: 'Tautan reset password berhasil dikirim ke email Anda.',
      );
    } on DioException catch (error) {
      debugPrint(
        'Forgot password request failed: '
        'status=${error.response?.statusCode} body=${error.response?.data}',
      );
      throw _mapDioError(error);
    }
  }

  Future<Session> login({
    required String email,
    required String password,
    required bool rememberSession,
  }) async {
    try {
      final response = await _withRetry<Map<String, dynamic>>(
        request: () => _apiClient.dio.post<Map<String, dynamic>>(
          '/mobile/login',
          data: {
            'email': email,
            'password': password,
          },
        ),
        actionLabel: 'login',
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
      final response = await _withRetry<Map<String, dynamic>>(
        request: () => _apiClient.dio.get<Map<String, dynamic>>(
          '/mobile/dashboard',
        ),
        actionLabel: 'dashboard',
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
    final response = await _withRetry<Map<String, dynamic>>(
      request: () => _apiClient.dio.get<Map<String, dynamic>>('/mobile/me'),
      actionLabel: 'profil pengguna',
    );
    final data = response.data ?? <String, dynamic>{};

    if (data['user'] is Map<String, dynamic>) {
      return AppUser.fromJson(data['user'] as Map<String, dynamic>);
    }

    return AppUser.fromJson(data);
  }

  String _extractMessage(
    Map<String, dynamic>? data, {
    required String fallback,
  }) {
    final message = data?['message'];
    if (message is String && message.trim().isNotEmpty) {
      return message;
    }

    return fallback;
  }

  Future<Response<T>> _withRetry<T>({
    required Future<Response<T>> Function() request,
    required String actionLabel,
  }) async {
    try {
      return await request();
    } on DioException catch (error) {
      if (!_isTransientDioError(error)) {
        rethrow;
      }

      debugPrint(
        'Retrying $actionLabel after transient network error: '
        'type=${error.type} message=${error.message}',
      );

      await Future<void>.delayed(const Duration(milliseconds: 700));
      return request();
    }
  }

  bool _isTransientDioError(DioException error) {
    switch (error.type) {
      case DioExceptionType.connectionTimeout:
      case DioExceptionType.receiveTimeout:
      case DioExceptionType.sendTimeout:
      case DioExceptionType.connectionError:
        return true;
      default:
        return false;
    }
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
        return 'Server terlalu lama merespons. Periksa koneksi internet Anda lalu coba lagi.';
      case DioExceptionType.connectionError:
        final message = error.message?.trim();
        if (message != null && message.isNotEmpty) {
          return 'Tidak bisa terhubung ke server. Detail: $message';
        }
        return 'Tidak bisa terhubung ke server Laravel.';
      default:
        final message = error.message?.trim();
        if (message != null && message.isNotEmpty) {
          return 'Request gagal dijalankan. Detail: $message';
        }
        return 'Request gagal dijalankan.';
    }
  }
}
