import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import 'package:shared_preferences/shared_preferences.dart';

class TokenStorage {
  static const _tokenKey = 'nuist_auth_token';
  static const _rememberLoginKey = 'nuist_remember_login';
  static const _savedEmailKey = 'nuist_saved_email';
  static const _savedLoginRoleKey = 'nuist_saved_login_role';
  static const _pushTokenKey = 'nuist_push_token';

  static const _secureStorage = FlutterSecureStorage();

  Future<void> writeToken(String token) async {
    await _secureStorage.write(key: _tokenKey, value: token);
    await _removeLegacyToken();
  }

  Future<String?> readToken() async {
    final secureToken = await _secureStorage.read(key: _tokenKey);
    if (secureToken != null && secureToken.isNotEmpty) {
      await _removeLegacyToken();
      return secureToken;
    }

    final preferences = await SharedPreferences.getInstance();
    final legacyToken = preferences.getString(_tokenKey);
    if (legacyToken != null && legacyToken.isNotEmpty) {
      await _secureStorage.write(key: _tokenKey, value: legacyToken);
      await _removeLegacyToken();
    }
    return legacyToken;
  }

  Future<void> deleteToken() async {
    await _secureStorage.delete(key: _tokenKey);
    await _removeLegacyToken();
  }

  Future<void> _removeLegacyToken() async {
    final preferences = await SharedPreferences.getInstance();
    await preferences.remove(_tokenKey);
  }

  Future<void> saveRememberedLogin({
    required String email,
    required String loginAs,
    required bool remember,
  }) async {
    final preferences = await SharedPreferences.getInstance();
    await preferences.setBool(_rememberLoginKey, remember);

    if (!remember) {
      await preferences.remove(_savedEmailKey);
      await preferences.remove(_savedLoginRoleKey);
      return;
    }

    await preferences.setString(_savedEmailKey, email);
    await preferences.setString(_savedLoginRoleKey, loginAs);
    // Remove passwords written by older app versions.
    await preferences.remove('nuist_saved_password');
  }

  Future<Map<String, dynamic>> readRememberedLogin() async {
    final preferences = await SharedPreferences.getInstance();
    await preferences.remove('nuist_saved_password');
    return {
      'remember': preferences.getBool(_rememberLoginKey) ?? false,
      'email': preferences.getString(_savedEmailKey) ?? '',
      'password': '',
      'loginAs': preferences.getString(_savedLoginRoleKey) ?? 'tenaga_pendidik',
    };
  }

  Future<void> clearRememberedLogin() async {
    final preferences = await SharedPreferences.getInstance();
    await preferences.remove(_rememberLoginKey);
    await preferences.remove(_savedEmailKey);
    await preferences.remove('nuist_saved_password');
    await preferences.remove(_savedLoginRoleKey);
  }

  Future<void> writePushToken(String token) async {
    await _secureStorage.write(key: _pushTokenKey, value: token);
  }

  Future<String?> readPushToken() async {
    final secureToken = await _secureStorage.read(key: _pushTokenKey);
    if (secureToken != null && secureToken.isNotEmpty) {
      await _removeLegacyPushToken();
      return secureToken;
    }

    final preferences = await SharedPreferences.getInstance();
    final legacyToken = preferences.getString(_pushTokenKey);
    if (legacyToken != null && legacyToken.isNotEmpty) {
      await _secureStorage.write(key: _pushTokenKey, value: legacyToken);
      await _removeLegacyPushToken();
    }
    return legacyToken;
  }

  Future<void> deletePushToken() async {
    await _secureStorage.delete(key: _pushTokenKey);
    await _removeLegacyPushToken();
  }

  Future<void> _removeLegacyPushToken() async {
    final preferences = await SharedPreferences.getInstance();
    await preferences.remove(_pushTokenKey);
  }
}
