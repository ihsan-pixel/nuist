import 'package:dio/dio.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../core/network/api_exception.dart';
import '../core/network/dio_provider.dart';
import '../models/billing_summary.dart';
import '../models/dashboard_data.dart';
import '../models/izin_item.dart';
import '../models/user_model.dart';

final mobileRepositoryProvider = Provider<MobileRepository>((ref) {
  return MobileRepository(ref.watch(dioProvider));
});

class MobileRepository {
  MobileRepository(this._dio);

  final Dio _dio;

  Future<DashboardData> fetchDashboard() async {
    return _unwrapObject(
      path: 'api/mobile/dashboard',
      parser: DashboardData.fromJson,
    );
  }

  Future<BillingSummary> fetchBills() async {
    return _unwrapObject(
      path: 'api/mobile/tagihan',
      parser: BillingSummary.fromJson,
    );
  }

  Future<List<IzinItem>> fetchIzinList() async {
    try {
      final response = await _dio.get<Map<String, dynamic>>('api/mobile/izin');
      final body = response.data ?? <String, dynamic>{};
      final data = body['data'] as Map<String, dynamic>?;
      final rawItems =
          (data?['items'] as List<dynamic>? ?? <dynamic>[])
              .cast<Map<String, dynamic>>();
      return rawItems.map(IzinItem.fromJson).toList();
    } on DioException catch (error) {
      final apiException = error.error;
      if (apiException is ApiException) {
        throw apiException;
      }
      throw const ApiException('Gagal memuat daftar izin.');
    }
  }

  Future<IzinItem> fetchIzinDetail(String izinId) async {
    try {
      final response = await _dio.get<Map<String, dynamic>>('api/mobile/izin/$izinId');
      final body = response.data ?? <String, dynamic>{};
      final data = body['data'] as Map<String, dynamic>?;
      final item = data?['item'] as Map<String, dynamic>?;

      if (item == null) {
        throw const ApiException('Detail izin tidak tersedia.');
      }

      return IzinItem.fromJson(item);
    } on DioException catch (error) {
      final apiException = error.error;
      if (apiException is ApiException) {
        throw apiException;
      }
      throw const ApiException('Gagal memuat detail izin.');
    }
  }

  Future<UserModel> fetchProfile() async {
    return _unwrapObject(
      path: 'api/mobile/me',
      parser: UserModel.fromJson,
    );
  }

  Future<T> _unwrapObject<T>({
    required String path,
    required T Function(Map<String, dynamic> json) parser,
  }) async {
    try {
      final response = await _dio.get<Map<String, dynamic>>(path);
      final body = response.data ?? <String, dynamic>{};
      final data = body['data'] as Map<String, dynamic>?;

      if (data == null) {
        throw const ApiException('Response API tidak memiliki data.');
      }

      return parser(data);
    } on DioException catch (error) {
      final apiException = error.error;
      if (apiException is ApiException) {
        throw apiException;
      }
      throw const ApiException('Gagal memuat data dari server.');
    }
  }
}
