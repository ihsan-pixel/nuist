# Plugins: lokasi, kamera, dan notifikasi (Capacitor)

Panduan singkat untuk menambahkan akses lokasi, kamera, dan notifikasi pada aplikasi mobile-capacitor.

1) Sudah diinstall
- @capacitor/geolocation (v5.x)
- @capacitor/camera (v5.x)
- @capacitor/push-notifications (v5.x)
- @capacitor/local-notifications (v5.x)

2) Sinkronisasi native
- Jalankan di folder project `mobile-capacitor`:

  npx cap sync

  Jika Anda ingin hanya Android / iOS:

  npx cap sync android
  npx cap sync ios

3) Android (catatan penting)
- Plugin sudah menambahkan banyak permission otomatis, namun periksa `android/app/src/main/AndroidManifest.xml` untuk memastikan ada:

  <uses-permission android:name="android.permission.CAMERA" />
  <uses-permission android:name="android.permission.ACCESS_FINE_LOCATION" />
  <uses-permission android:name="android.permission.ACCESS_COARSE_LOCATION" />

  Untuk Android 13+ (API 33) Anda perlu juga permission runtime untuk notifikasi:

  <uses-permission android:name="android.permission.POST_NOTIFICATIONS" />

  Jika aplikasi target SDK >= 33: pastikan menangani request runtime untuk POST_NOTIFICATIONS
  (plugin PushNotifications menyediakan requestPermissions()).

4) iOS (catatan penting)
- Buka `ios` project di Xcode (`npx cap open ios`), lalu tambahkan keterangan permission di `Info.plist` jika belum ada:

  - NSCameraUsageDescription (alasan akses kamera)
  - NSPhotoLibraryAddUsageDescription (jika menyimpan foto)
  - NSLocationWhenInUseUsageDescription (alasan lokasi saat aplikasi digunakan)
  - NSLocationAlwaysAndWhenInUseUsageDescription (jika perlu lokasi di background)

  Notifikasi: plugin akan meminta permission via API, namun Anda harus mengaktifkan capabilities yang diperlukan
  (Background Modes -> Remote notifications) jika menggunakan push.

5) CocoaPods & Xcode
- Pada macOS pastikan `cocoapods` & Xcode tersedia. Jika `npx cap sync ios` gagal karena CocoaPods, jalankan di folder `ios/App`:

  pod install

  Kemudian buka workspace di Xcode dan build/run.

6) Cara menggunakan helper JS
- Saya menambahkan file helper di `src/capacitor-helpers.js` yang mengekspor fungsi:
  - requestLocationPermission()
  - getCurrentPosition()
  - takePhoto()
  - registerForPushNotifications()
  - scheduleLocalNotification()
  - requestAllPermissions()

  Contoh penggunaan (di project web Anda):

  import helpers from './src/capacitor-helpers';

  // Minta semua permission saat app start
  await helpers.requestAllPermissions();

  // Ambil lokasi
  const pos = await helpers.getCurrentPosition();

  // Ambil foto
  const photo = await helpers.takePhoto();

  // Register push
  await helpers.registerForPushNotifications();

  // Schedule local notif
  await helpers.scheduleLocalNotification({ title: 'Halo', body: 'Coba notifikasi lokal', scheduleInSeconds: 10 });

7) Pengujian
- Android: gunakan device nyata untuk notifikasi push (emulator seringkali terbatas). Untuk build debug:

  npx cap open android
  # lalu build dari Android Studio atau via terminal
  ./gradlew assembleDebug

- iOS: buka Xcode workspace, pilih device/simulator, jalankan. Untuk push di iOS gunakan device nyata.

8) Catatan keamanan
- Pastikan menjelaskan alasan penggunaan permission di App Store / Play Store.

Jika Anda mau, saya bisa:
- Menambahkan contoh integrasi ke dalam halaman web aplikasi Anda (komponen UI untuk meminta izin dan tombol tes),
- Membantu menambahkan entri manifest/Info.plist secara otomatis (butuh akses file yang tepat), atau
- Menambahkan test unit kecil untuk helper functions.
