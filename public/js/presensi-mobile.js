const Capacitor = window.Capacitor || { isNativePlatform: () => false };
const Plugins = (window.Capacitor && window.Capacitor.Plugins) || {};
const Geolocation = Plugins.Geolocation;
const PushNotifications = Plugins.PushNotifications;

const DEFAULT_ENDPOINT = '/mobile/presensi';
const PRESENSI_ENDPOINT = document.querySelector('meta[name="presensi-endpoint"]')?.content || DEFAULT_ENDPOINT;
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';

function randomNonce(length = 24) {
  const chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
  let out = '';
  for (let i = 0; i < length; i += 1) {
    out += chars[Math.floor(Math.random() * chars.length)];
  }
  return out;
}

async function sleep(ms) {
  return new Promise((res) => setTimeout(res, ms));
}

async function collectLocationReadings(count = 4, intervalMs = 700, timeout = 10000) {
  const readings = [];
  for (let i = 0; i < count; i += 1) {
    const pos = await Geolocation.getCurrentPosition({ enableHighAccuracy: true, timeout });
    readings.push({
      latitude: pos.coords.latitude,
      longitude: pos.coords.longitude,
      accuracy: pos.coords.accuracy,
      altitude: pos.coords.altitude,
      speed: pos.coords.speed,
      timestamp: pos.timestamp,
    });
    if (i < count - 1) {
      await sleep(intervalMs);
    }
  }
  return readings;
}

async function getLocation() {
  await Geolocation.requestPermissions();
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

async function registerPushIfNeeded() {
  try {
    const perm = await PushNotifications.requestPermissions();
    if (perm?.receive === 'granted' || perm?.value === 'granted' || perm?.display === 'granted') {
      await PushNotifications.register();
    }
  } catch (e) {
    console.warn('Push registration failed', e);
  }
}

function createHiddenVideo() {
  const video = document.createElement('video');
  video.autoplay = true;
  video.muted = true;
  video.playsInline = true;
  video.setAttribute('autoplay', 'autoplay');
  video.setAttribute('muted', 'muted');
  video.setAttribute('playsinline', 'playsinline');
  video.style.position = 'fixed';
  video.style.left = '-9999px';
  video.style.top = '0';
  document.body.appendChild(video);
  return video;
}

async function runFaceScan() {
  const FaceRecognitionClass = window.FaceRecognition;
  if (typeof FaceRecognitionClass !== 'function') {
    throw new Error('Komponen scan wajah belum tersedia di halaman ini.');
  }

  const faceRecognition = new FaceRecognitionClass();
  const video = createHiddenVideo();

  try {
    await faceRecognition.loadModels();
    await faceRecognition.initializeCamera(video);
    const scanResult = await faceRecognition.performAttendanceScan(video, {});
    return scanResult;
  } finally {
    try {
      faceRecognition.stopCamera(video);
    } catch (_) {
      // noop
    }

    if (video?.parentNode) {
      video.parentNode.removeChild(video);
    }
  }
}

async function initPermissions() {
  if (!(window.Capacitor && Capacitor.isNativePlatform && Capacitor.isNativePlatform())) {
    return;
  }

  try {
    const locPerm = await Geolocation.checkPermissions();
    if (locPerm.location !== 'granted') {
      await Geolocation.requestPermissions();
    }

    const pushPerm = await PushNotifications.checkPermissions();
    if (pushPerm.receive !== 'granted') {
      await PushNotifications.requestPermissions();
    }
  } catch (err) {
    console.warn('Permission init error', err);
  }
}

window.absenMobile = async function absenMobile(options = {}) {
  if (!(window.Capacitor && Capacitor.isNativePlatform && Capacitor.isNativePlatform())) {
    alert('Fitur hanya tersedia di aplikasi mobile');
    return false;
  }

  const endpoint = options.endpoint || PRESENSI_ENDPOINT;
  const statusMessage = options.onStatus;

  const emitStatus = (message) => {
    if (typeof statusMessage === 'function') {
      statusMessage(message);
    }
  };

  try {
    emitStatus('Memulai pemindaian wajah...');
    const lokasi = await getLocation();

    emitStatus('Membuka kamera biometrik...');
    const scanResult = await runFaceScan();

    if (!scanResult?.face_descriptor || !Array.isArray(scanResult.face_descriptor) || scanResult.face_descriptor.length < 32) {
      throw new Error('Data biometrik wajah tidak valid.');
    }

    emitStatus('Memverifikasi wajah...');
    const payload = {
      latitude: lokasi.latitude,
      longitude: lokasi.longitude,
      lokasi: options.lokasi || null,
      accuracy: lokasi.accuracy || null,
      altitude: lokasi.altitude || null,
      speed: lokasi.speed || null,
      device_info: navigator.userAgent || null,
      location_readings: JSON.stringify(lokasi.readings || []),
      face_descriptor: scanResult.face_descriptor,
      liveness_score: scanResult.liveness_score,
      liveness_challenges: scanResult.liveness_challenges,
      verification_nonce: randomNonce(),
      verification_timestamp: Date.now(),
    };

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
      try {
        data = await res.json();
      } catch (_) {
        // noop
      }
      throw new Error(data?.message || 'Presensi gagal (server error)');
    }

    emitStatus('Presensi berhasil.');
    alert('Presensi berhasil ✅');
    return true;
  } catch (err) {
    console.error('absenMobile error', err);
    alert('Presensi gagal ❌\n' + (err.message || err));
    return false;
  }
};

window.registerPushIfNeeded = registerPushIfNeeded;

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
