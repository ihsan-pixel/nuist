# nuist_flutter_mobile

Flutter client for the Laravel mobile API protected by Sanctum.

## Run

Release builds default to the production API:

```text
https://nuist.id/api
```

Run the debug app against the local Laravel API (Android physical device):

```bash
/Users/lpmnudiymacpro/Documents/flutter/bin/flutter run \
  --dart-define=APP_ENV=local \
  --dart-define=API_BASE_URL=http://<MAC_LAN_IP>:8000/api
```

For an Android emulator, use `http://10.0.2.2:8000/api`.

Run directly against production:

```bash
/Users/lpmnudiymacpro/Documents/flutter/bin/flutter run
```

Override when needed for staging or local API:

```bash
/Users/lpmnudiymacpro/Documents/flutter/bin/flutter run \
  --dart-define=API_BASE_URL=https://nuist.id/api
```

## Play Store release

Create an upload keystore and add the following to `android/key.properties`
(this file is ignored by git):

```properties
storePassword=YOUR_STORE_PASSWORD
keyPassword=YOUR_KEY_PASSWORD
keyAlias=upload
storeFile=/absolute/path/to/upload-keystore.jks
```

Then build the signed Android App Bundle:

```bash
/Users/lpmnudiymacpro/Documents/flutter/bin/flutter build appbundle --release \
  --dart-define=APP_ENV=production \
  --dart-define=API_BASE_URL=https://nuist.id/api
```

## Integrated endpoints

- `POST /api/mobile/login`
- `GET /api/mobile/me`
- `GET /api/mobile/dashboard`
- `POST /api/mobile/logout`

## Notes

- Token Sanctum is stored with `flutter_secure_storage`.
- Android internet permission is enabled.
- If your production URL is HTTPS, no extra iOS transport exception is needed.
