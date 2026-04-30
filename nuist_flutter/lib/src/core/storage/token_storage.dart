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

  Future<String?> readToken() {
    return _storage.read(key: _tokenKey);
  }

  Future<void> writeToken(String token) {
    return _storage.write(key: _tokenKey, value: token);
  }

  Future<void> clear() {
    return _storage.delete(key: _tokenKey);
  }
}
