import 'package:shared_preferences/shared_preferences.dart';

class TokenStorage {
  static const _tokenKey = 'nuist_auth_token';
  static const _rememberLoginKey = 'nuist_remember_login';
  static const _savedEmailKey = 'nuist_saved_email';
  static const _savedPasswordKey = 'nuist_saved_password';
  static const _pushTokenKey = 'nuist_push_token';

  Future<void> writeToken(String token) async {
    final preferences = await SharedPreferences.getInstance();
    await preferences.setString(_tokenKey, token);
  }

  Future<String?> readToken() async {
    final preferences = await SharedPreferences.getInstance();
    return preferences.getString(_tokenKey);
  }

  Future<void> deleteToken() async {
    final preferences = await SharedPreferences.getInstance();
    await preferences.remove(_tokenKey);
  }

  Future<void> saveRememberedLogin({
    required String email,
    required String password,
    required bool remember,
  }) async {
    final preferences = await SharedPreferences.getInstance();
    await preferences.setBool(_rememberLoginKey, remember);

    if (!remember) {
      await preferences.remove(_savedEmailKey);
      await preferences.remove(_savedPasswordKey);
      return;
    }

    await preferences.setString(_savedEmailKey, email);
    await preferences.setString(_savedPasswordKey, password);
  }

  Future<Map<String, dynamic>> readRememberedLogin() async {
    final preferences = await SharedPreferences.getInstance();
    return {
      'remember': preferences.getBool(_rememberLoginKey) ?? false,
      'email': preferences.getString(_savedEmailKey) ?? '',
      'password': preferences.getString(_savedPasswordKey) ?? '',
    };
  }

  Future<void> clearRememberedLogin() async {
    final preferences = await SharedPreferences.getInstance();
    await preferences.remove(_rememberLoginKey);
    await preferences.remove(_savedEmailKey);
    await preferences.remove(_savedPasswordKey);
  }

  Future<void> writePushToken(String token) async {
    final preferences = await SharedPreferences.getInstance();
    await preferences.setString(_pushTokenKey, token);
  }

  Future<String?> readPushToken() async {
    final preferences = await SharedPreferences.getInstance();
    return preferences.getString(_pushTokenKey);
  }

  Future<void> deletePushToken() async {
    final preferences = await SharedPreferences.getInstance();
    await preferences.remove(_pushTokenKey);
  }
}
