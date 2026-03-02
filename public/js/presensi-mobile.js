import { Geolocation } from '@capacitor/geolocation';
import { Camera, CameraResultType, CameraSource } from '@capacitor/camera';
import { PushNotifications } from '@capacitor/push-notifications';
import { Capacitor } from '@capacitor/core';

// Production-ready presensi mobile helper
// Usage: include this script in your Blade (type=module)
// <meta name="csrf-token" content="{{ csrf_token() }}">
// <script type="module" src="/js/presensi-mobile.js"></script>

const DEFAULT_ENDPOINT = '/mobile/presensi';
const PRESENSI_ENDPOINT = document.querySelector('meta[name="presensi-endpoint"]')?.content || DEFAULT_ENDPOINT;
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';

async function sleep(ms) {
  return new Promise((res) => setTimeout(res, ms));
}

async function collectLocationReadings(count = 4, intervalMs = 700, timeout = 10000) {
  const readings = [];
  for (let i = 0; i < count; i++) {
    const pos = await Geolocation.getCurrentPosition({ enableHighAccuracy: true, timeout });
    readings.push({
      latitude: pos.coords.latitude,
      longitude: pos.coords.longitude,
      accuracy: pos.coords.accuracy,
      altitude: pos.coords.altitude,
      speed: pos.coords.speed,
      timestamp: pos.timestamp,
    });

    // small delay before next reading
    if (i < count - 1) await sleep(intervalMs);
  }
  return readings;
}

async function getLocation() {
  // Request permission on demand
  await Geolocation.requestPermissions();

  // Collect multiple readings for fake-gps detection
  const readings = await collectLocationReadings(4, 600);
  const latest = readings[readings.length - 1];

  return {
    latitude: latest.latitude,
    longitude: latest.longitude,
    accuracy: latest.accuracy,
    altitude: latest.altitude,
    speed: latest.speed,
    readings,
  };
}

async function takePhoto() {
  // Request camera permission on demand
  if (Camera.requestPermissions) {
    await Camera.requestPermissions();
  }

  const photo = await Camera.getPhoto({
    quality: 80,
    allowEditing: false,
    resultType: CameraResultType.Base64,
    source: CameraSource.Camera,
  });

  // photo.base64String is the raw base64 (no mime prefix)
  // For easier backend handling, we send raw base64 string
  return photo.base64String;
}

async function registerPushIfNeeded() {
  try {
    const perm = await PushNotifications.requestPermissions();
    // Only register when granted
    if (perm?.receive === 'granted' || perm?.value === 'granted' || perm?.display === 'granted') {
      await PushNotifications.register();
    }
  } catch (e) {
    // Non-fatal
    console.warn('Push registration failed', e);
  }
}

// Initialize/ensure common permissions when the app opens (production-safe)
export async function initPermissions() {
  if (!(window.Capacitor && Capacitor.isNativePlatform && Capacitor.isNativePlatform())) {
    return; // skip kalau bukan di app
  }

  try {
    // LOCATION
    const locPerm = await Geolocation.checkPermissions();
    if (locPerm.location !== 'granted') {
      await Geolocation.requestPermissions();
    }

    // CAMERA
    const camPerm = await Camera.checkPermissions();
    if (camPerm.camera !== 'granted') {
      await Camera.requestPermissions();
    }

    // NOTIFICATION
    const pushPerm = await PushNotifications.checkPermissions();
    if (pushPerm.receive !== 'granted') {
      await PushNotifications.requestPermissions();
    }

    console.log('Permissions initialized');
  } catch (err) {
    console.warn('Permission init error', err);
  }
}

// Main entry called by button
window.absenMobile = async function absenMobile(options = {}) {
  // Best-effort guard: only run on native platforms
  if (!(window.Capacitor && Capacitor.isNativePlatform && Capacitor.isNativePlatform())) {
    alert('Fitur hanya tersedia di aplikasi mobile');
    return;
  }

  const endpoint = options.endpoint || PRESENSI_ENDPOINT;

  try {
    // 1) Get reliable location (multiple readings)
    const lokasi = await getLocation();

    // Optionally confirm location with user UI here (omitted)

    // 2) Take photo (ask camera permission now)
    const fotoBase64 = await takePhoto();

    // 3) Prepare payload
    const payload = {
      latitude: lokasi.latitude,
      longitude: lokasi.longitude,
      lokasi: options.lokasi || null,
      accuracy: lokasi.accuracy || null,
      altitude: lokasi.altitude || null,
      speed: lokasi.speed || null,
      device_info: navigator.userAgent || null,
      location_readings: JSON.stringify(lokasi.readings || []),
      selfie_data: fotoBase64,
    };

    // 4) Send to server
    const res = await fetch(endpoint, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': CSRF,
      },
      body: JSON.stringify(payload),
    });

    if (!res.ok) {
      let data = null;
      try { data = await res.json(); } catch (e) { /* ignore */ }
      const msg = data?.message || 'Presensi gagal (server error)';
      throw new Error(msg);
    }

    // success
    alert('Presensi berhasil ✅');
    return true;
  } catch (err) {
    console.error('absenMobile error', err);
    alert('Presensi gagal ❌\n' + (err.message || err));
    return false;
  }
};

// Export helper for optional push registration (call on login success)
window.registerPushIfNeeded = registerPushIfNeeded;

// Init permissions when DOM is ready (best-effort)
document.addEventListener('DOMContentLoaded', () => {
  try {
    initPermissions();
  } catch (e) {
    console.warn('initPermissions call failed', e);
  }
});

export default {
  absenMobile,
  registerPushIfNeeded,
};
