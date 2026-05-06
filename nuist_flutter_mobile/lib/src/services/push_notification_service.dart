import 'dart:async';
import 'dart:io';

import 'package:firebase_core/firebase_core.dart';
import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:flutter/foundation.dart';
import 'package:flutter_local_notifications/flutter_local_notifications.dart';

import '../../firebase_options.dart';
import 'auth_repository.dart';
import 'token_storage.dart';

@pragma('vm:entry-point')
Future<void> firebaseMessagingBackgroundHandler(RemoteMessage message) async {
  try {
    await Firebase.initializeApp(
      options: DefaultFirebaseOptions.currentPlatform,
    );
  } catch (_) {
    // Abaikan jika Firebase belum terkonfigurasi pada device ini.
  }
}

class PushNotificationService {
  PushNotificationService({
    required AuthRepository authRepository,
    required TokenStorage tokenStorage,
  })  : _authRepository = authRepository,
        _tokenStorage = tokenStorage;

  static const _channelId = 'nuist_general';
  static const _channelName = 'NUIST General';
  static const _channelDescription = 'Notifikasi presensi, izin, dan jurnal.';

  final AuthRepository _authRepository;
  final TokenStorage _tokenStorage;
  final FlutterLocalNotificationsPlugin _localNotifications =
      FlutterLocalNotificationsPlugin();

  bool _initialized = false;
  bool _firebaseReady = false;

  Future<void> initialize({
    required Future<bool> Function() canRegisterToken,
  }) async {
    if (_initialized) {
      return;
    }
    _initialized = true;

    try {
      await Firebase.initializeApp(
        options: DefaultFirebaseOptions.currentPlatform,
      );
      _firebaseReady = true;
    } catch (error) {
      debugPrint('Push notification disabled: Firebase init failed: $error');
      return;
    }

    FirebaseMessaging.onBackgroundMessage(firebaseMessagingBackgroundHandler);

    const androidSettings =
        AndroidInitializationSettings('@mipmap/ic_launcher');
    const iosSettings = DarwinInitializationSettings();
    await _localNotifications.initialize(
      const InitializationSettings(
        android: androidSettings,
        iOS: iosSettings,
      ),
    );

    final androidPlugin = _localNotifications
        .resolvePlatformSpecificImplementation<
            AndroidFlutterLocalNotificationsPlugin>();
    await androidPlugin?.createNotificationChannel(
      const AndroidNotificationChannel(
        _channelId,
        _channelName,
        description: _channelDescription,
        importance: Importance.high,
      ),
    );

    final messaging = FirebaseMessaging.instance;
    await messaging.setAutoInitEnabled(true);
    await messaging.requestPermission(
      alert: true,
      announcement: false,
      badge: true,
      carPlay: false,
      criticalAlert: false,
      provisional: false,
      sound: true,
    );

    final token = await _resolveCurrentToken();
    if (token != null && token.isNotEmpty) {
      await _tokenStorage.writePushToken(token);
      if (await canRegisterToken()) {
        await _registerTokenWithServer(token);
      }
    }

    FirebaseMessaging.instance.onTokenRefresh.listen((token) async {
      if (token.isEmpty) {
        return;
      }

      await _tokenStorage.writePushToken(token);
      if (await canRegisterToken()) {
        await _registerTokenWithServer(token);
      }
    });

    FirebaseMessaging.onMessage.listen((message) async {
      await _showForegroundNotification(message);
    });
  }

  Future<void> syncTokenIfNeeded() async {
    if (!_firebaseReady) {
      return;
    }

    var token = await _tokenStorage.readPushToken();
    if (token == null || token.isEmpty) {
      token = await _resolveCurrentToken();
      if (token != null && token.isNotEmpty) {
        await _tokenStorage.writePushToken(token);
      }
    }

    if (token == null || token.isEmpty) {
      debugPrint('Push token sync skipped: Firebase token still unavailable.');
      return;
    }

    await _registerTokenWithServer(token);
  }

  Future<String?> _resolveCurrentToken({
    int attempts = 4,
    Duration delay = const Duration(seconds: 2),
  }) async {
    for (var index = 0; index < attempts; index++) {
      try {
        final token = await FirebaseMessaging.instance.getToken();
        if (token != null && token.isNotEmpty) {
          debugPrint('Firebase push token resolved.');
          return token;
        }
      } catch (error) {
        debugPrint('Failed to resolve Firebase push token: $error');
      }

      if (index < attempts - 1) {
        await Future<void>.delayed(delay);
      }
    }

    return null;
  }

  Future<void> _registerTokenWithServer(String token) async {
    try {
      await _authRepository.registerPushToken(
        token: token,
        platform: _platformLabel,
        deviceName: _deviceName,
      );
    } catch (error) {
      debugPrint('Push token registration skipped: $error');
    }
  }

  Future<void> _showForegroundNotification(RemoteMessage message) async {
    final notification = message.notification;
    if (notification == null) {
      return;
    }

    await _localNotifications.show(
      message.hashCode,
      notification.title ?? 'NUIST',
      notification.body ?? '',
      const NotificationDetails(
        android: AndroidNotificationDetails(
          _channelId,
          _channelName,
          channelDescription: _channelDescription,
          importance: Importance.high,
          priority: Priority.high,
          icon: '@mipmap/ic_launcher',
        ),
        iOS: DarwinNotificationDetails(),
      ),
    );
  }

  String get _platformLabel {
    if (kIsWeb) {
      return 'web';
    }

    if (Platform.isAndroid) {
      return 'android';
    }

    if (Platform.isIOS) {
      return 'ios';
    }

    if (Platform.isMacOS) {
      return 'macos';
    }

    return 'unknown';
  }

  String get _deviceName {
    if (kIsWeb) {
      return 'Flutter Web';
    }

    if (Platform.isAndroid) {
      return 'Android Flutter';
    }

    if (Platform.isIOS) {
      return 'iOS Flutter';
    }

    if (Platform.isMacOS) {
      return 'macOS Flutter';
    }

    return 'Flutter Device';
  }
}
