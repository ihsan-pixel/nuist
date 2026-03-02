// Helper utilities to request permissions and use Capacitor plugins
// Bahasa: fungsi-fungsi ini memberi contoh bagaimana meminta izin dan
// mengakses lokasi, kamera, dan notifikasi (push & local).

import { Camera, CameraResultType, CameraSource } from '@capacitor/camera';
import { Geolocation } from '@capacitor/geolocation';
import { PushNotifications } from '@capacitor/push-notifications';
import { LocalNotifications } from '@capacitor/local-notifications';
import { PermissionsAndroid, Platform } from '@capacitor/core';

// NOTE: Capacitor core doesn't export PermissionsAndroid; Android runtime
// permission checks are shown here using plugin-specific APIs and examples.

// Request location permission (iOS and Android)
export async function requestLocationPermission() {
  try {
    // Capacitor Geolocation has a requestPermissions helper
    const permission = await Geolocation.requestPermissions();
    return permission; // { location: 'granted' } or platform-specific shape
  } catch (e) {
    console.error('requestLocationPermission error', e);
    throw e;
  }
}

export async function getCurrentPosition(options = {}) {
  try {
    // Ensure permission first
    await requestLocationPermission();
    const pos = await Geolocation.getCurrentPosition(options);
    return pos; // { coords: { latitude, longitude, ... }, timestamp }
  } catch (e) {
    console.error('getCurrentPosition error', e);
    throw e;
  }
}

// Camera: request and take a photo
export async function takePhoto(opts = { quality: 90, allowEditing: false }) {
  try {
    const result = await Camera.getPhoto({
      resultType: CameraResultType.Uri,
      source: CameraSource.Prompt,
      quality: opts.quality ?? 90,
      allowEditing: opts.allowEditing ?? false,
    });

    // result.webPath or result.path (native) can be used to display the image
    return result;
  } catch (e) {
    console.error('takePhoto error', e);
    throw e;
  }
}

// Push notifications: register and listen
export async function registerForPushNotifications() {
  try {
    // Request permission and register
    await PushNotifications.requestPermissions();

    // Only register if granted
    const perm = await PushNotifications.checkPermissions();
    if (perm.receive === 'granted' || perm.display === 'granted' || (perm.value && perm.value === 'granted')) {
      await PushNotifications.register();
    } else {
      console.warn('Push permission not granted:', perm);
      return { registered: false, perm };
    }

    // Handlers
    PushNotifications.addListener('registration', (token) => {
      console.log('Push registration success, token:', token);
    });

    PushNotifications.addListener('registrationError', (err) => {
      console.error('Push registration error:', err);
    });

    PushNotifications.addListener('pushNotificationReceived', (notification) => {
      console.log('Push received:', notification);
    });

    PushNotifications.addListener('pushNotificationActionPerformed', (action) => {
      console.log('Push action performed:', action);
    });

    return { registered: true };
  } catch (e) {
    console.error('registerForPushNotifications error', e);
    throw e;
  }
}

// Local notification example (schedule one)
export async function scheduleLocalNotification({ title = 'Test', body = 'Hello', id = 1, scheduleInSeconds = 5 } = {}) {
  try {
    // Request permission
    await LocalNotifications.requestPermissions();

    await LocalNotifications.schedule({
      notifications: [
        {
          id,
          title,
          body,
          schedule: { at: new Date(new Date().getTime() + scheduleInSeconds * 1000) },
          smallIcon: 'ic_stat_icon',
        },
      ],
    });

    return { scheduled: true };
  } catch (e) {
    console.error('scheduleLocalNotification error', e);
    throw e;
  }
}

// Convenience function to request all necessary permissions in sequence
export async function requestAllPermissions() {
  const results = {};
  try {
    results.location = await requestLocationPermission();
  } catch (e) {
    results.location = { error: e };
  }

  try {
    // Camera permission is implicitly requested when using Camera.getPhoto; some platforms
    // may require adding a Info.plist / AndroidManifest permission entry (see README-plugins.md)
    // We do a test call to prompt permission with source: Camera
    // But to avoid launching UI, the app can call Camera.requestPermissions() if available.
    if (Camera && Camera.requestPermissions) {
      results.camera = await Camera.requestPermissions();
    } else {
      results.camera = { info: 'Use Camera.getPhoto to trigger permission prompt' };
    }
  } catch (e) {
    results.camera = { error: e };
  }

  try {
    results.push = await PushNotifications.requestPermissions();
  } catch (e) {
    results.push = { error: e };
  }

  try {
    results.localNotifications = await LocalNotifications.requestPermissions();
  } catch (e) {
    results.localNotifications = { error: e };
  }

  return results;
}

// Small default export for convenience
export default {
  requestLocationPermission,
  getCurrentPosition,
  takePhoto,
  registerForPushNotifications,
  scheduleLocalNotification,
  requestAllPermissions,
};
