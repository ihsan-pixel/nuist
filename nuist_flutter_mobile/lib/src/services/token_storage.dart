class TokenStorage {
  static String? _token;

  Future<void> writeToken(String token) {
    _token = token;
    return Future.value();
  }

  Future<String?> readToken() {
    return Future.value(_token);
  }

  Future<void> deleteToken() {
    _token = null;
    return Future.value();
  }
}
