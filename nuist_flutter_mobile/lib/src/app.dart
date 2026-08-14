import 'dart:async';

import 'package:flutter/material.dart';

import 'config/app_config.dart';
import 'controllers/session_controller.dart';
import 'pages/auth/login_page.dart';
import 'pages/home/home_page.dart';
import 'pages/splash/splash_page.dart';
import 'pages/student/student_shell_page.dart';
import 'pages/pengurus/pengurus_shell_page.dart';
import 'pages/teacher/teacher_shell_page.dart';
import 'services/api_client.dart';
import 'services/auth_repository.dart';
import 'services/push_notification_service.dart';
import 'services/student_mobile_repository.dart';
import 'services/pengurus_mobile_repository.dart';
import 'services/teacher_mobile_repository.dart';
import 'services/token_storage.dart';
import 'theme/app_theme.dart';

class NuistMobileApp extends StatefulWidget {
  const NuistMobileApp({super.key});

  @override
  State<NuistMobileApp> createState() => _NuistMobileAppState();
}

class _NuistMobileAppState extends State<NuistMobileApp>
    with WidgetsBindingObserver {
  late final AuthRepository _authRepository;
  late final StudentMobileRepository _studentMobileRepository;
  late final TeacherMobileRepository _teacherMobileRepository;
  late final PengurusMobileRepository _pengurusMobileRepository;
  late final SessionController _sessionController;
  late final PushNotificationService _pushNotificationService;
  String? _lastSyncedPushUserKey;

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addObserver(this);
    final tokenStorage = TokenStorage();
    final apiClient = ApiClient(baseUrls: AppConfig.apiBaseUrls);
    _authRepository = AuthRepository(
      apiClient: apiClient,
      tokenStorage: tokenStorage,
    );
    _studentMobileRepository = StudentMobileRepository(apiClient: apiClient);
    _teacherMobileRepository = TeacherMobileRepository(apiClient: apiClient);
    _pengurusMobileRepository = PengurusMobileRepository(apiClient: apiClient);
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
    WidgetsBinding.instance.removeObserver(this);
    _sessionController.removeListener(_handleSessionChanged);
    _sessionController.dispose();
    super.dispose();
  }

  @override
  void didChangeAppLifecycleState(AppLifecycleState state) {
    if (state == AppLifecycleState.resumed &&
        _sessionController.session != null) {
      unawaited(_pushNotificationService.syncTokenIfNeeded());
    }
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
          title: 'Nuist Mobile',
          theme: AppTheme.build(),
          home: SplashGate(
            isReady: !_sessionController.isBootstrapping,
            child: _buildHome(),
          ),
        );
      },
    );
  }

  Widget _buildHome() {
    final session = _sessionController.session;
    if (session == null || _sessionController.isPostLoginLoading) {
      return LoginPage(
        controller: _sessionController,
        authRepository: _authRepository,
      );
    }

    final normalizedRole = (session.user.role ?? '').trim().toLowerCase();
    final mobileRoute = (session.mobileRoute ?? '').trim().toLowerCase();
    final isTeacherRoute = normalizedRole == 'tenaga_pendidik' ||
        mobileRoute.startsWith('/mobile/guru/') ||
        mobileRoute.startsWith('/mobile/teacher/');
    final isStudentRoute = normalizedRole == 'siswa' ||
        mobileRoute.startsWith('/mobile/siswa/') ||
        mobileRoute.startsWith('/mobile/student/');
    final isPengurusRoute = normalizedRole == 'pengurus' || mobileRoute.startsWith('/mobile/pengurus/');

    if (isTeacherRoute) {
      return TeacherShellPage(
        controller: _sessionController,
        repository: _teacherMobileRepository,
      );
    }

    if (isStudentRoute) {
      return StudentShellPage(
        controller: _sessionController,
        repository: _studentMobileRepository,
      );
    }
    if (isPengurusRoute) return PengurusShellPage(controller: _sessionController, repository: _pengurusMobileRepository);

    return HomePage(
      controller: _sessionController,
      session: session,
    );
  }
}
