import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../storage/token_storage.dart';
import 'app_config.dart';

final apiBaseUrlProvider =
    StateNotifierProvider<ApiBaseUrlController, String>((ref) {
  final controller = ApiBaseUrlController(
    tokenStorage: ref.watch(tokenStorageProvider),
  );
  controller.hydrate();
  return controller;
});

class ApiBaseUrlController extends StateNotifier<String> {
  ApiBaseUrlController({
    required TokenStorage tokenStorage,
  })  : _tokenStorage = tokenStorage,
        super(AppConfig.baseUrl);

  final TokenStorage _tokenStorage;

  Future<void> hydrate() async {
    try {
      final savedBaseUrl = await _tokenStorage.readBaseUrl();
      final normalizedBaseUrl = AppConfig.normalizeBaseUrl(savedBaseUrl ?? '');
      if (normalizedBaseUrl != null && normalizedBaseUrl != state) {
        state = normalizedBaseUrl;
      }
    } catch (_) {
      state = AppConfig.baseUrl;
    }
  }

  Future<void> setBaseUrl(String value) async {
    final normalizedBaseUrl = AppConfig.normalizeBaseUrl(value);
    if (normalizedBaseUrl == null) {
      await _tokenStorage.clearBaseUrl();
      state = AppConfig.baseUrl;
      return;
    }

    await _tokenStorage.writeBaseUrl(normalizedBaseUrl);
    state = normalizedBaseUrl;
  }
}
