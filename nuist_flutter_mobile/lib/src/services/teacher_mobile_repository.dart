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

  String _mapDioError(DioException error) {
    final responseData = error.response?.data;
    if (responseData is Map<String, dynamic>) {
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
