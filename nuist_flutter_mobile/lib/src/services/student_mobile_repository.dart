import 'package:dio/dio.dart';
import 'package:flutter/foundation.dart';

import 'api_client.dart';

class StudentMobileRepository {
  StudentMobileRepository({
    required ApiClient apiClient,
  }) : _apiClient = apiClient;

  final ApiClient _apiClient;

  Future<Map<String, dynamic>> getDashboard() {
    return _get('/mobile/app/student/dashboard',
        actionLabel: 'dashboard siswa');
  }

  Future<Map<String, dynamic>> getBills() {
    return _get('/mobile/app/student/bills', actionLabel: 'tagihan siswa');
  }

  Future<Map<String, dynamic>> getPayments() {
    return _get('/mobile/app/student/payments',
        actionLabel: 'pembayaran siswa');
  }

  Future<Map<String, dynamic>> createVirtualAccount({
    required int billId,
  }) {
    return _send(
      '/mobile/app/student/payments/$billId/virtual-account',
      actionLabel: 'buat virtual account siswa',
      method: 'POST',
    );
  }

  Future<Map<String, dynamic>> getPaymentHistory() {
    return _get(
      '/mobile/app/student/payment-history',
      actionLabel: 'riwayat pembayaran siswa',
    );
  }

  Future<Map<String, dynamic>> getProfile() {
    return _get('/mobile/app/student/profile', actionLabel: 'profil siswa');
  }

  Future<Map<String, dynamic>> _get(
    String path, {
    required String actionLabel,
  }) async {
    try {
      final response = await _withRetry<Map<String, dynamic>>(
        request: () => _apiClient.dio.get<Map<String, dynamic>>(path),
        actionLabel: actionLabel,
      );
      final data = response.data?['data'];
      if (data is Map<String, dynamic>) {
        return data;
      }
      if (data is Map) {
        return Map<String, dynamic>.from(data);
      }
      return <String, dynamic>{};
    } on DioException catch (error) {
      debugPrint(
        'Student $actionLabel request failed: '
        'status=${error.response?.statusCode} body=${error.response?.data}',
      );
      throw _mapDioError(error);
    }
  }

  Future<Map<String, dynamic>> _send(
    String path, {
    required String actionLabel,
    required String method,
    Map<String, dynamic>? data,
  }) async {
    try {
      late final Response<Map<String, dynamic>> response;
      switch (method) {
        case 'POST':
          response = await _withRetry<Map<String, dynamic>>(
            request: () => _apiClient.dio.post<Map<String, dynamic>>(
              path,
              data: data,
            ),
            actionLabel: actionLabel,
          );
          break;
        default:
          throw UnsupportedError('Unsupported method: $method');
      }

      final body = response.data ?? const <String, dynamic>{};
      final responseData = body['data'];
      final result = responseData is Map<String, dynamic>
          ? Map<String, dynamic>.from(responseData)
          : responseData is Map
              ? Map<String, dynamic>.from(responseData)
              : <String, dynamic>{};

      if (body['message'] is String) {
        result['_message'] = body['message'];
      }

      return result;
    } on DioException catch (error) {
      debugPrint(
        'Student $actionLabel request failed: '
        'status=${error.response?.statusCode} body=${error.response?.data}',
      );
      throw _mapDioError(error);
    }
  }

  Future<Response<T>> _withRetry<T>({
    required Future<Response<T>> Function() request,
    required String actionLabel,
  }) async {
    var retriedOnSameHost = false;

    while (true) {
      try {
        return await request();
      } on DioException catch (error) {
        if (!_isTransientDioError(error)) {
          rethrow;
        }

        if (_shouldFailoverBaseUrl(error) && _apiClient.switchToNextBaseUrl()) {
          debugPrint(
            'Retrying student $actionLabel using fallback API host ${_apiClient.baseUrl} '
            'after transient network error: type=${error.type} message=${error.message}',
          );
          continue;
        }

        if (retriedOnSameHost) {
          rethrow;
        }

        retriedOnSameHost = true;
        debugPrint(
          'Retrying student $actionLabel on API host ${_apiClient.baseUrl} '
          'after transient network error: type=${error.type} message=${error.message}',
        );

        await Future<void>.delayed(const Duration(milliseconds: 700));
      }
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

  bool _shouldFailoverBaseUrl(DioException error) {
    switch (error.type) {
      case DioExceptionType.connectionTimeout:
      case DioExceptionType.connectionError:
        return true;
      default:
        return false;
    }
  }

  String _mapDioError(DioException error) {
    final responseData = error.response?.data;
    if (responseData is Map<String, dynamic>) {
      final errors = responseData['errors'];
      if (errors is Map) {
        for (final value in errors.values) {
          if (value is List && value.isNotEmpty) {
            final first = value.first;
            if (first is String && first.trim().isNotEmpty) {
              return first;
            }
          }
          if (value is String && value.trim().isNotEmpty) {
            return value;
          }
        }
      }

      final message = responseData['message'];
      if (message is String && message.trim().isNotEmpty) {
        return message;
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
        return 'Server terlalu lama merespons dari ${_apiClient.baseUrl}. Periksa koneksi internet Anda lalu coba lagi.';
      case DioExceptionType.connectionError:
        final message = error.message?.trim();
        if (message != null && message.isNotEmpty) {
          return 'Tidak bisa terhubung ke server ${_apiClient.baseUrl}. Detail: $message';
        }
        return 'Tidak bisa terhubung ke server ${_apiClient.baseUrl}.';
      default:
        final message = error.message?.trim();
        if (message != null && message.isNotEmpty) {
          return 'Request gagal. Detail: $message';
        }
        return 'Request gagal dijalankan.';
    }
  }
}
