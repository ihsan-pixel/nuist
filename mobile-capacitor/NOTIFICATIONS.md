Capacitor local notifications setup (Nuist Mobile)

What I changed
- Added a notification permission request and local notification dispatch in `resources/views/mobile/presensi.blade.php`.
  - The code prefers Capacitor LocalNotifications plugin on native platforms and falls back to the browser Notification API when running in a normal browser.

Native app setup (Android / iOS)
1. Ensure the Capacitor local-notifications plugin is installed in `mobile-capacitor/package.json` (this repo already lists `@capacitor/local-notifications` and `@capacitor/push-notifications`).

2. From the `mobile-capacitor` folder, run:

```bash
# install JS deps (if not already)
npm install

# sync Capacitor plugins and native projects
npx cap sync
```

3. For Android: open the project and rebuild

```bash
npx cap open android
# then build/run from Android Studio (Gradle sync and Run on device)
```

4. For iOS: open the project and rebuild

```bash
npx cap open ios
# then build/run from Xcode on a real device (simulator push notifications limited)
```

Testing
- Open the Nuist app on a real device (Android/iOS). On first visit to the mobile presensi page the app will attempt to request notification permission.
- After a successful presensi (take selfie + send), a local notification should appear.
- If using the web preview (browser), you'll see the standard browser notification permission prompt and a regular web notification when presensi succeeds.

Notes & troubleshooting
- On Android, make sure the app's notification channel settings allow showing notifications.
- On iOS, local notifications require asking permission; the plugin will prompt when `requestPermissions()` is called.
- If you don't see notifications on native, run `npx cap sync` then rebuild the native project and deploy to device.
- Service workers / background notifications are out of scope here; this implementation sends an immediate local notification on successful presensi only.

If you want, I can also add a small server-side notification record (database) so admins or users can see a history of notifications in-app; tell me and I'll implement it.