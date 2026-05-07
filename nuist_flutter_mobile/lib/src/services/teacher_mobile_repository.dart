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
      await _withRetry<Map<String, dynamic>>(
        request: () => _apiClient.dio.delete<Map<String, dynamic>>(
          '/mobile/app/teacher/schedule/$scheduleId',
        ),
        actionLabel: 'hapus jadwal',
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

  Future<Map<String, dynamic>> submitAttendance({
    required Map<String, dynamic> payload,
  }) async {
    try {
      final response = await _withRetry<Map<String, dynamic>>(
        request: () => _apiClient.dio.post<Map<String, dynamic>>(
          '/mobile/app/teacher/attendance',
          data: payload,
        ),
        actionLabel: 'submit presensi',
      );
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
        'Teacher submit presensi request failed: '
        'status=${error.response?.statusCode} body=${error.response?.data}',
      );
      throw _mapDioError(error);
    }
  }

  Future<Map<String, dynamic>> getTeachingJournal() {
    return _get(
      '/mobile/app/teacher/teaching-journal',
      actionLabel: 'jurnal mengajar',
    );
  }

  Future<Map<String, dynamic>> checkTeachingJournalLocation({
    required int scheduleId,
    required double latitude,
    required double longitude,
  }) async {
    try {
      final response = await _withRetry<Map<String, dynamic>>(
        request: () => _apiClient.dio.post<Map<String, dynamic>>(
          '/mobile/app/teacher/teaching-journal/check-location',
          data: {
            'teaching_schedule_id': scheduleId,
            'latitude': latitude,
            'longitude': longitude,
          },
        ),
        actionLabel: 'cek lokasi presensi mengajar',
      );
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
        'Teacher cek lokasi presensi mengajar request failed: '
        'status=${error.response?.statusCode} body=${error.response?.data}',
      );
      throw _mapDioError(error);
    }
  }

  Future<Map<String, dynamic>> submitTeachingJournalAttendance({
    required Map<String, dynamic> payload,
  }) async {
    try {
      final response = await _withRetry<Map<String, dynamic>>(
        request: () => _apiClient.dio.post<Map<String, dynamic>>(
          '/mobile/app/teacher/teaching-journal/attendance',
          data: payload,
        ),
        actionLabel: 'presensi mengajar',
      );
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
        'Teacher presensi mengajar request failed: '
        'status=${error.response?.statusCode} body=${error.response?.data}',
      );
      throw _mapDioError(error);
    }
  }

  Future<Map<String, dynamic>> getProfile() {
    return _get('/mobile/app/teacher/profile', actionLabel: 'profil');
  }

  Future<Map<String, dynamic>> updateProfile({
    required Map<String, dynamic> payload,
  }) async {
    try {
      final response = await _withRetry<Map<String, dynamic>>(
        request: () => _apiClient.dio.post<Map<String, dynamic>>(
          '/mobile/app/teacher/profile/update',
          data: payload,
        ),
        actionLabel: 'ubah profil',
      );
      return _responseDataWithMessage(response.data);
    } on DioException catch (error) {
      debugPrint(
        'Teacher ubah profil request failed: '
        'status=${error.response?.statusCode} body=${error.response?.data}',
      );
      throw _mapDioError(error);
    }
  }

  Future<Map<String, dynamic>> updateProfileAvatar({
    required String filePath,
  }) async {
    try {
      final formData = FormData.fromMap({
        'avatar': await MultipartFile.fromFile(filePath),
      });
      final response = await _withRetry<Map<String, dynamic>>(
        request: () => _apiClient.dio.post<Map<String, dynamic>>(
          '/mobile/app/teacher/profile/avatar',
          data: formData,
          options: Options(
            headers: const {
              'Accept': 'application/json',
            },
            contentType: 'multipart/form-data',
          ),
        ),
        actionLabel: 'ubah foto profil',
      );
      return _responseDataWithMessage(response.data);
    } on DioException catch (error) {
      debugPrint(
        'Teacher ubah foto profil request failed: '
        'status=${error.response?.statusCode} body=${error.response?.data}',
      );
      throw _mapDioError(error);
    }
  }

  Future<Map<String, dynamic>> updateProfilePassword({
    required String currentPassword,
    required String password,
    required String passwordConfirmation,
  }) async {
    try {
      final response = await _withRetry<Map<String, dynamic>>(
        request: () => _apiClient.dio.post<Map<String, dynamic>>(
          '/mobile/app/teacher/profile/password',
          data: {
            'current_password': currentPassword,
            'password': password,
            'password_confirmation': passwordConfirmation,
          },
        ),
        actionLabel: 'ubah password',
      );
      return _responseDataWithMessage(response.data);
    } on DioException catch (error) {
      debugPrint(
        'Teacher ubah password request failed: '
        'status=${error.response?.statusCode} body=${error.response?.data}',
      );
      throw _mapDioError(error);
    }
  }

  Future<Map<String, dynamic>> getIzin() {
    return _get('/mobile/app/teacher/izin', actionLabel: 'izin');
  }

  Future<Map<String, dynamic>> submitIzin({
    required String type,
    required Map<String, dynamic> payload,
    String? attachmentField,
    String? attachmentPath,
  }) async {
    try {
      final data = Map<String, dynamic>.from(payload)
        ..['type'] = type;

      final formMap = <String, dynamic>{};
      data.forEach((key, value) {
        if (value == null) {
          return;
        }
        formMap[key] = value;
      });

      if (attachmentField != null &&
          attachmentPath != null &&
          attachmentPath.trim().isNotEmpty) {
        formMap[attachmentField] = await MultipartFile.fromFile(attachmentPath);
      }

      final response = await _withRetry<Map<String, dynamic>>(
        request: () => _apiClient.dio.post<Map<String, dynamic>>(
          '/mobile/app/teacher/izin',
          data: FormData.fromMap(formMap),
          options: Options(
            headers: const {
              'Accept': 'application/json',
            },
            contentType: 'multipart/form-data',
          ),
        ),
        actionLabel: 'ajukan izin',
      );
      return _responseDataWithMessage(response.data);
    } on DioException catch (error) {
      debugPrint(
        'Teacher ajukan izin request failed: '
        'status=${error.response?.statusCode} body=${error.response?.data}',
      );
      throw _mapDioError(error);
    }
  }

  Future<Map<String, dynamic>> updateIzin({
    required int izinId,
    required String type,
    required Map<String, dynamic> payload,
    String? attachmentField,
    String? attachmentPath,
  }) async {
    try {
      final data = Map<String, dynamic>.from(payload)
        ..['type'] = type;

      final formMap = <String, dynamic>{};
      data.forEach((key, value) {
        if (value == null) {
          return;
        }
        formMap[key] = value;
      });

      if (attachmentField != null &&
          attachmentPath != null &&
          attachmentPath.trim().isNotEmpty) {
        formMap[attachmentField] = await MultipartFile.fromFile(attachmentPath);
      }

      final response = await _withRetry<Map<String, dynamic>>(
        request: () => _apiClient.dio.post<Map<String, dynamic>>(
          '/mobile/app/teacher/izin/$izinId/update',
          data: FormData.fromMap(formMap),
          options: Options(
            headers: const {
              'Accept': 'application/json',
            },
            contentType: 'multipart/form-data',
          ),
        ),
        actionLabel: 'ubah izin',
      );
      return _responseDataWithMessage(response.data);
    } on DioException catch (error) {
      debugPrint(
        'Teacher ubah izin request failed: '
        'status=${error.response?.statusCode} body=${error.response?.data}',
      );
      throw _mapDioError(error);
    }
  }

  Future<Map<String, dynamic>> getManagedIzin({
    String status = 'pending',
  }) {
    return _get(
      '/mobile/app/teacher/izin/manage?status=$status',
      actionLabel: 'kelola izin',
    );
  }

  Future<Map<String, dynamic>> approveManagedIzin({
    required int izinId,
    String? approvalNotes,
  }) {
    return _send(
      '/mobile/app/teacher/izin/$izinId/approve',
      actionLabel: 'setujui izin',
      method: 'POST',
      data: {
        'approval_notes': approvalNotes,
      },
    );
  }

  Future<Map<String, dynamic>> rejectManagedIzin({
    required int izinId,
    String? approvalNotes,
  }) {
    return _send(
      '/mobile/app/teacher/izin/$izinId/reject',
      actionLabel: 'tolak izin',
      method: 'POST',
      data: {
        'approval_notes': approvalNotes,
      },
    );
  }

  Future<Map<String, dynamic>> downloadAttachment({
    required String url,
  }) async {
    try {
      final response = await _withRetry<List<int>>(
        request: () => _apiClient.dio.get<List<int>>(
          url,
          options: Options(responseType: ResponseType.bytes),
        ),
        actionLabel: 'unduh lampiran izin',
      );

      final bytes = response.data ?? const <int>[];
      final disposition = response.headers.value('content-disposition') ?? '';
      final filenameMatch = RegExp(
        r'filename="?([^";]+)"?',
        caseSensitive: false,
      ).firstMatch(disposition);
      final uri = Uri.tryParse(url);
      final fallbackName = uri != null && uri.pathSegments.isNotEmpty
          ? uri.pathSegments.last
          : 'lampiran';

      return {
        'bytes': bytes,
        'filename': filenameMatch?.group(1) ?? fallbackName,
        'content_type': response.headers.value('content-type') ?? '',
      };
    } on DioException catch (error) {
      debugPrint(
        'Teacher unduh lampiran izin request failed: '
        'status=${error.response?.statusCode} body=${error.response?.data}',
      );
      throw _mapDioError(error);
    }
  }

  Future<Map<String, dynamic>> getAttendanceReports({
    String scope = 'monthly',
    String? month,
    int? teacherId,
  }) {
    final params = <String>[
      'scope=$scope',
      if (month != null && month.trim().isNotEmpty) 'month=${month.trim()}',
      if (teacherId != null) 'teacher_id=$teacherId',
    ];
    return _get(
      '/mobile/app/teacher/reports?${params.join('&')}',
      actionLabel: 'laporan presensi',
    );
  }

  Future<Map<String, dynamic>> getStaffAttendance({
    String? date,
  }) {
    final query = (date != null && date.trim().isNotEmpty)
        ? '?date=${date.trim()}'
        : '';
    return _get(
      '/mobile/app/teacher/staff-attendance$query',
      actionLabel: 'data presensi guru',
    );
  }

  Future<Map<String, dynamic>> downloadAttendanceReportPdf({
    String scope = 'monthly',
    String? month,
    int? teacherId,
  }) {
    final params = <String>[
      'scope=$scope',
      if (month != null && month.trim().isNotEmpty) 'month=${month.trim()}',
      if (teacherId != null) 'teacher_id=$teacherId',
    ];
    return _downloadPdf(
      '/mobile/app/teacher/reports/export/attendance?${params.join('&')}',
      actionLabel: 'export pdf presensi kehadiran',
    );
  }

  Future<Map<String, dynamic>> downloadTeachingReportPdf({
    String scope = 'monthly',
    String? month,
    int? teacherId,
  }) {
    final params = <String>[
      'scope=$scope',
      if (month != null && month.trim().isNotEmpty) 'month=${month.trim()}',
      if (teacherId != null) 'teacher_id=$teacherId',
    ];
    return _downloadPdf(
      '/mobile/app/teacher/reports/export/teaching?${params.join('&')}',
      actionLabel: 'export pdf presensi mengajar',
    );
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
        'Teacher $actionLabel request failed: '
        'status=${error.response?.statusCode} body=${error.response?.data}',
      );
      throw _mapDioError(error);
    }
  }

  Future<Map<String, dynamic>> _downloadPdf(
    String path, {
    required String actionLabel,
  }) async {
    try {
      final response = await _withRetry<List<int>>(
        request: () => _apiClient.dio.get<List<int>>(
          path,
          options: Options(responseType: ResponseType.bytes),
        ),
        actionLabel: actionLabel,
      );

      final bytes = response.data ?? const <int>[];
      final disposition = response.headers.value('content-disposition') ?? '';
      final filenameMatch = RegExp(
        r'filename="?([^";]+)"?',
        caseSensitive: false,
      ).firstMatch(disposition);

      return {
        'bytes': bytes,
        'filename': filenameMatch?.group(1) ?? 'report.pdf',
      };
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
          response = await _withRetry<Map<String, dynamic>>(
            request: () => _apiClient.dio.post<Map<String, dynamic>>(
              path,
              data: data,
            ),
            actionLabel: actionLabel,
          );
          break;
        case 'PUT':
          response = await _withRetry<Map<String, dynamic>>(
            request: () => _apiClient.dio.put<Map<String, dynamic>>(
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
            'Retrying teacher $actionLabel using fallback API host ${_apiClient.baseUrl} '
            'after transient network error: type=${error.type} message=${error.message}',
          );
          continue;
        }

        if (retriedOnSameHost) {
          rethrow;
        }

        retriedOnSameHost = true;
        debugPrint(
          'Retrying teacher $actionLabel on API host ${_apiClient.baseUrl} '
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

  Map<String, dynamic> _responseDataWithMessage(Map<String, dynamic>? body) {
    final responseBody = body ?? const <String, dynamic>{};
    final responseData = responseBody['data'];
    final result = responseData is Map<String, dynamic>
        ? Map<String, dynamic>.from(responseData)
        : responseData is Map
            ? Map<String, dynamic>.from(responseData)
            : <String, dynamic>{};

    if (responseBody['message'] is String) {
      result['_message'] = responseBody['message'];
    }

    return result;
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
