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

Android 13 (SDK 33+) runtime permission (POST_NOTIFICATIONS)
- If your app targets Android 13 or higher, you must request the runtime permission `android.permission.POST_NOTIFICATIONS` before posting notifications. Capacitor's LocalNotifications.requestPermissions() should request this, but if you find it's not being requested, ensure the permission is present in `android/app/src/main/AndroidManifest.xml`:

```xml
<uses-permission android:name="android.permission.POST_NOTIFICATIONS" />
```

Then call `LocalNotifications.requestPermissions()` (we already attempt this on page load). If still not prompted, rebuild the native app after `npx cap sync` and test on a real device.

Android notification channels
- The code now creates a channel `nuist-channel` with importance 5 when the plugin supports `createChannel`. If you need custom channel settings or multiple channels, update the initialization code in `resources/views/mobile/presensi.blade.php` to create channels on app startup.

If you want, I can also add a small server-side notification record (database) so admins or users can see a history of notifications in-app; tell me and I'll implement it.
