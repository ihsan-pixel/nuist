import 'package:dio/dio.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../core/network/api_exception.dart';
import '../core/network/dio_provider.dart';
import '../models/user_model.dart';

final authRepositoryProvider = Provider<AuthRepository>((ref) {
  return AuthRepository(ref.watch(dioProvider));
});

class LoginResult {
  LoginResult({
    required this.token,
    required this.user,
  });

  final String token;
  final UserModel user;
}

class AuthRepository {
  AuthRepository(this._dio);

  final Dio _dio;

  Future<LoginResult> login({
    required String email,
    required String password,
  }) async {
    try {
      final response = await _dio.post<Map<String, dynamic>>(
        'api/mobile/login',
        data: <String, dynamic>{
          'email': email,
          'password': password,
        },
      );

      final body = response.data ?? <String, dynamic>{};
      final token = body['token']?.toString();
      final userJson = body['user'] as Map<String, dynamic>?;

      if (token == null || userJson == null) {
        throw const ApiException('Response login tidak lengkap.');
      }

      return LoginResult(
        token: token,
        user: UserModel.fromJson(userJson),
      );
    } on DioException catch (error) {
      final apiException = error.error;
      if (apiException is ApiException) {
        throw apiException;
      }
      throw const ApiException('Gagal login ke server.');
    }
  }

  Future<UserModel> me() async {
    try {
      final response = await _dio.get<Map<String, dynamic>>('api/mobile/me');
      final body = response.data ?? <String, dynamic>{};
      final data = body['data'] as Map<String, dynamic>?;

      if (data == null) {
        throw const ApiException('Data profil tidak tersedia.');
      }

      return UserModel.fromJson(data);
    } on DioException catch (error) {
      final apiException = error.error;
      if (apiException is ApiException) {
        throw apiException;
      }
      throw const ApiException('Gagal memuat profil.');
    }
  }

  Future<void> logout() async {
    try {
      await _dio.post<Map<String, dynamic>>('api/mobile/logout');
    } on DioException catch (error) {
      final apiException = error.error;
      if (apiException is ApiException && apiException.statusCode == 401) {
        return;
      }
      if (apiException is ApiException) {
        throw apiException;
      }
      throw const ApiException('Gagal logout dari server.');
    }
  }
}
