import 'package:dio/dio.dart';
import 'package:flutter/foundation.dart';

import 'api_client.dart';

class TeacherMobileRepository {
  TeacherMobileRepository({
    required ApiClient apiClient,
  }) : _apiClient = apiClient;

  final ApiClient _apiClient;

  Future<Map<String, dynamic>> getDashboard() {
    return _get('/mobile/app/teacher/dashboard', actionLabel: 'dashboard');
  }

  Future<Map<String, dynamic>> getSchedule() {
    return _get('/mobile/app/teacher/schedule', actionLabel: 'jadwal');
  }

  Future<Map<String, dynamic>> getScheduleOptions() {
    return _get(
      '/mobile/app/teacher/schedule/options',
      actionLabel: 'opsi jadwal',
    );
  }

  Future<Map<String, dynamic>> createSchedule({
    required Map<String, dynamic> payload,
  }) {
    return _send(
      '/mobile/app/teacher/schedule',
      actionLabel: 'tambah jadwal',
      method: 'POST',
      data: payload,
    );
  }

  Future<Map<String, dynamic>> updateSchedule({
    required int scheduleId,
    required Map<String, dynamic> payload,
  }) {
    return _send(
      '/mobile/app/teacher/schedule/$scheduleId',
      actionLabel: 'ubah jadwal',
      method: 'PUT',
      data: payload,
    );
  }

  Future<void> deleteSchedule({
    required int scheduleId,
  }) async {
    try {
      await _apiClient.dio.delete<Map<String, dynamic>>(
        '/mobile/app/teacher/schedule/$scheduleId',
      );
    } on DioException catch (error) {
      debugPrint(
        'Teacher hapus jadwal request failed: '
        'status=${error.response?.statusCode} body=${error.response?.data}',
      );
      throw _mapDioError(error);
    }
  }

  Future<Map<String, dynamic>> getAttendance() {
    return _get('/mobile/app/teacher/attendance', actionLabel: 'presensi');
  }

  Future<Map<String, dynamic>> getTeachingJournal() {
    return _get(
      '/mobile/app/teacher/teaching-journal',
      actionLabel: 'jurnal mengajar',
    );
  }

  Future<Map<String, dynamic>> getProfile() {
    return _get('/mobile/app/teacher/profile', actionLabel: 'profil');
  }

  Future<Map<String, dynamic>> getIzin() {
    return _get('/mobile/app/teacher/izin', actionLabel: 'izin');
  }

  Future<Map<String, dynamic>> _get(
    String path, {
    required String actionLabel,
  }) async {
    try {
      final response = await _apiClient.dio.get<Map<String, dynamic>>(path);
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
        'Teacher $actionLabel request failed: '
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
          response = await _apiClient.dio.post<Map<String, dynamic>>(
            path,
            data: data,
          );
          break;
        case 'PUT':
          response = await _apiClient.dio.put<Map<String, dynamic>>(
            path,
            data: data,
          );
          break;
        default:
          throw UnsupportedError('Unsupported method: $method');
      }

      final body = response.data ?? const <String, dynamic>{};
      final responseData = body['data'];
      if (responseData is Map<String, dynamic>) {
        return responseData;
      }
      if (responseData is Map) {
        return Map<String, dynamic>.from(responseData);
      }
      return body;
    } on DioException catch (error) {
      debugPrint(
        'Teacher $actionLabel request failed: '
        'status=${error.response?.statusCode} body=${error.response?.data}',
      );
      throw _mapDioError(error);
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
        return 'Server terlalu lama merespons. Coba lagi beberapa saat.';
      case DioExceptionType.connectionError:
        return 'Tidak bisa terhubung ke server.';
      default:
        final message = error.message?.trim();
        if (message != null && message.isNotEmpty) {
          return 'Request gagal. Detail: $message';
        }
        return 'Request gagal dijalankan.';
    }
  }
}
