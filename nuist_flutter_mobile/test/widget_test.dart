import 'package:flutter_test/flutter_test.dart';

import 'package:nuist_flutter_mobile/src/config/app_config.dart';

void main() {
  test('uses nuist production API base URL by default', () {
    expect(AppConfig.apiBaseUrl, 'https://nuist.id/api');
    expect(AppConfig.isPlaceholder, isFalse);
  });
}
