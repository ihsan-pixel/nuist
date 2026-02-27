# Nuist Mobile (Capacitor) — Prototype

Purpose: provide a minimal Capacitor wrapper that points to your existing Laravel PWA as a remote URL (fast prototype). This project intentionally keeps native assets separate from the main Laravel repo.

Quick start (prototype using remote URL):

1. Install dependencies

```bash
cd mobile-capacitor
npm install
```

2. Set your Laravel URL in `capacitor.config.json` `server.url` (must be https). For local dev you can use an https tunnel (ngrok or localtunnel).

3. Add platforms and open native IDE:

```bash
npx cap add android
npx cap add ios
npx cap sync
npx cap open android
npx cap open ios
```

Notes:
- This setup uses remote URL by default. For a production-ready mobile app, consider building frontend assets (static) and copying to `www/` then removing `server.url` so the app uses bundled assets and can work offline.
- Use token-based auth for mobile (see `app/Http/Controllers/Api/AuthController.php`).
