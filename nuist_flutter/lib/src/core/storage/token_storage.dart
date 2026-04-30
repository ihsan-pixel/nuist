import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';

final tokenStorageProvider = Provider<TokenStorage>((ref) {
  return const TokenStorage(
    FlutterSecureStorage(),
  );
});

class TokenStorage {
  const TokenStorage(this._storage);

  final FlutterSecureStorage _storage;

  static const _tokenKey = 'nuist_access_token';
  static const _baseUrlKey = 'nuist_api_base_url';
  static const _storageTimeout = Duration(seconds: 3);

  Future<String?> readToken() async {
    try {
      return await _storage.read(key: _tokenKey).timeout(_storageTimeout);
    } catch (_) {
      return null;
    }
  }

  Future<void> writeToken(String token) async {
    await _storage.write(key: _tokenKey, value: token).timeout(_storageTimeout);
  }

  Future<void> clear() async {
    try {
      await _storage.delete(key: _tokenKey).timeout(_storageTimeout);
    } catch (_) {}
  }

  Future<String?> readBaseUrl() async {
    try {
      return await _storage.read(key: _baseUrlKey).timeout(_storageTimeout);
    } catch (_) {
      return null;
    }
  }

  Future<void> writeBaseUrl(String baseUrl) async {
    await _storage.write(key: _baseUrlKey, value: baseUrl).timeout(_storageTimeout);
  }

  Future<void> clearBaseUrl() async {
    try {
      await _storage.delete(key: _baseUrlKey).timeout(_storageTimeout);
    } catch (_) {}
  }
}
