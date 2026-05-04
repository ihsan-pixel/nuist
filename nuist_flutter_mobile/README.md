# nuist_flutter_mobile

Flutter client for the Laravel mobile API protected by Sanctum.

## Run

Default production API:

```text
https://nuist.id/api
```

Run directly against production:

```bash
/Users/lpmnudiymacpro/Documents/flutter/bin/flutter run
```

Override when needed for staging or local API:

```bash
/Users/lpmnudiymacpro/Documents/flutter/bin/flutter run \
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
