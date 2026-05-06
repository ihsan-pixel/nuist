import 'dart:async';

import 'package:flutter/material.dart';

import 'config/app_config.dart';
import 'controllers/session_controller.dart';
import 'pages/auth/login_page.dart';
import 'pages/home/home_page.dart';
import 'pages/teacher/teacher_shell_page.dart';
import 'services/api_client.dart';
import 'services/auth_repository.dart';
import 'services/push_notification_service.dart';
import 'services/teacher_mobile_repository.dart';
import 'services/token_storage.dart';
import 'theme/app_theme.dart';

class NuistMobileApp extends StatefulWidget {
  const NuistMobileApp({super.key});

  @override
  State<NuistMobileApp> createState() => _NuistMobileAppState();
}

class _NuistMobileAppState extends State<NuistMobileApp> {
  late final AuthRepository _authRepository;
  late final TeacherMobileRepository _teacherMobileRepository;
  late final SessionController _sessionController;
  late final PushNotificationService _pushNotificationService;
  String? _lastSyncedPushUserKey;

  @override
  void initState() {
    super.initState();
    final tokenStorage = TokenStorage();
    final apiClient = ApiClient(baseUrls: AppConfig.apiBaseUrls);
    _authRepository = AuthRepository(
      apiClient: apiClient,
      tokenStorage: tokenStorage,
    );
    _teacherMobileRepository = TeacherMobileRepository(apiClient: apiClient);
    _pushNotificationService = PushNotificationService(
      authRepository: _authRepository,
      tokenStorage: tokenStorage,
    );
    _sessionController = SessionController(_authRepository)..bootstrap();
    _sessionController.addListener(_handleSessionChanged);
    unawaited(
      _pushNotificationService.initialize(
        canRegisterToken: () async => _sessionController.session != null,
      ),
    );
  }

  @override
  void dispose() {
    _sessionController.removeListener(_handleSessionChanged);
    _sessionController.dispose();
    super.dispose();
  }

  void _handleSessionChanged() {
    final session = _sessionController.session;
    final sessionKey =
        session == null ? null : '${session.user.id}:${session.token}';

    if (sessionKey == null || sessionKey == _lastSyncedPushUserKey) {
      if (sessionKey == null) {
        _lastSyncedPushUserKey = null;
      }
      return;
    }

    _lastSyncedPushUserKey = sessionKey;
    unawaited(_pushNotificationService.syncTokenIfNeeded());
  }

  @override
  Widget build(BuildContext context) {
    return AnimatedBuilder(
      animation: _sessionController,
      builder: (context, _) {
        return MaterialApp(
          debugShowCheckedModeBanner: false,
          title: 'Nuist Login',
          theme: AppTheme.build(),
          home: _buildHome(),
        );
      },
    );
  }

  Widget _buildHome() {
    if (_sessionController.isBootstrapping) {
      return const _LoadingScreen();
    }

    final session = _sessionController.session;
    if (session == null) {
      return LoginPage(
        controller: _sessionController,
        authRepository: _authRepository,
      );
    }

    if (session.user.role == 'tenaga_pendidik') {
      return TeacherShellPage(
        controller: _sessionController,
        repository: _teacherMobileRepository,
      );
    }

    return HomePage(
      controller: _sessionController,
      session: session,
    );
  }
}

class _LoadingScreen extends StatelessWidget {
  const _LoadingScreen();

  @override
  Widget build(BuildContext context) {
    return const Scaffold(
      body: Center(
        child: CircularProgressIndicator(),
      ),
    );
  }
}
