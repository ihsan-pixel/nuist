import 'dart:convert';

import 'package:flutter/material.dart';

import 'config/app_config.dart';
import 'controllers/session_controller.dart';
import 'models/session.dart';
import 'services/api_client.dart';
import 'services/auth_repository.dart';
import 'services/token_storage.dart';

class NuistMobileApp extends StatefulWidget {
  const NuistMobileApp({super.key});

  @override
  State<NuistMobileApp> createState() => _NuistMobileAppState();
}

class _NuistMobileAppState extends State<NuistMobileApp> {
  late final SessionController _sessionController;

  @override
  void initState() {
    super.initState();
    final tokenStorage = TokenStorage();
    final apiClient = ApiClient(baseUrl: AppConfig.apiBaseUrl);
    final authRepository = AuthRepository(
      apiClient: apiClient,
      tokenStorage: tokenStorage,
    );

    _sessionController = SessionController(authRepository)..bootstrap();
  }

  @override
  void dispose() {
    _sessionController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return AnimatedBuilder(
      animation: _sessionController,
      builder: (context, _) {
        return MaterialApp(
          debugShowCheckedModeBanner: false,
          title: 'Nuist Login',
          theme: ThemeData(
            useMaterial3: true,
            scaffoldBackgroundColor: const Color(0xFFF4EFE7),
            colorScheme: ColorScheme.fromSeed(
              seedColor: const Color(0xFF0D5C63),
              brightness: Brightness.light,
            ),
            textTheme: const TextTheme(
              headlineLarge: TextStyle(
                fontSize: 36,
                fontWeight: FontWeight.w800,
                letterSpacing: -1.2,
                color: Color(0xFF102A43),
              ),
              titleMedium: TextStyle(
                fontSize: 16,
                fontWeight: FontWeight.w600,
                color: Color(0xFF486581),
              ),
              bodyMedium: TextStyle(
                fontSize: 15,
                color: Color(0xFF334E68),
              ),
            ),
            inputDecorationTheme: InputDecorationTheme(
              filled: true,
              fillColor: Colors.white,
              contentPadding: const EdgeInsets.symmetric(
                horizontal: 18,
                vertical: 18,
              ),
              border: OutlineInputBorder(
                borderRadius: BorderRadius.circular(20),
                borderSide: BorderSide.none,
              ),
              enabledBorder: OutlineInputBorder(
                borderRadius: BorderRadius.circular(20),
                borderSide: BorderSide.none,
              ),
              focusedBorder: OutlineInputBorder(
                borderRadius: BorderRadius.circular(20),
                borderSide: const BorderSide(
                  color: Color(0xFF0D5C63),
                  width: 1.4,
                ),
              ),
            ),
          ),
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
      return LoginPage(controller: _sessionController);
    }

    return HomePage(controller: _sessionController, session: session);
  }
}

class LoginPage extends StatefulWidget {
  const LoginPage({
    super.key,
    required this.controller,
  });

  final SessionController controller;

  @override
  State<LoginPage> createState() => _LoginPageState();
}

class _LoginPageState extends State<LoginPage> {
  final _formKey = GlobalKey<FormState>();
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  bool _obscurePassword = true;
  bool _rememberMe = true;
  String? _lastShownError;

  @override
  void initState() {
    super.initState();
    widget.controller.addListener(_handleControllerChanged);
  }

  @override
  void didUpdateWidget(covariant LoginPage oldWidget) {
    super.didUpdateWidget(oldWidget);
    if (oldWidget.controller != widget.controller) {
      oldWidget.controller.removeListener(_handleControllerChanged);
      widget.controller.addListener(_handleControllerChanged);
    }
  }

  @override
  void dispose() {
    widget.controller.removeListener(_handleControllerChanged);
    _emailController.dispose();
    _passwordController.dispose();
    super.dispose();
  }

  void _handleControllerChanged() {
    final message = widget.controller.errorMessage;
    if (!mounted || message == null || message == _lastShownError) {
      return;
    }

    _lastShownError = message;
    ScaffoldMessenger.of(context)
      ..hideCurrentSnackBar()
      ..showSnackBar(
        SnackBar(
          content: Text(message),
          backgroundColor: const Color(0xFF9F1239),
        ),
      );
  }

  Future<void> _submit() async {
    final form = _formKey.currentState;
    if (form == null || !form.validate()) {
      return;
    }

    FocusScope.of(context).unfocus();
    await widget.controller.login(
      email: _emailController.text.trim(),
      password: _passwordController.text,
      rememberSession: _rememberMe,
    );
  }

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final controller = widget.controller;

    return Scaffold(
      body: SafeArea(
        child: Center(
          child: SingleChildScrollView(
            padding: const EdgeInsets.all(24),
            child: ConstrainedBox(
              constraints: const BoxConstraints(maxWidth: 420),
              child: Form(
                key: _formKey,
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Container(
                      height: 180,
                      decoration: BoxDecoration(
                        borderRadius: BorderRadius.circular(32),
                        gradient: const LinearGradient(
                          colors: [
                            Color(0xFF0D5C63),
                            Color(0xFF44A1A0),
                            Color(0xFFF4B860),
                          ],
                          begin: Alignment.topLeft,
                          end: Alignment.bottomRight,
                        ),
                      ),
                      child: Stack(
                        children: [
                          Positioned(
                            top: -10,
                            right: -6,
                            child: Container(
                              width: 92,
                              height: 92,
                              decoration: BoxDecoration(
                                color: Colors.white.withOpacity(0.14),
                                shape: BoxShape.circle,
                              ),
                            ),
                          ),
                          Positioned(
                            bottom: -18,
                            left: -8,
                            child: Container(
                              width: 120,
                              height: 120,
                              decoration: BoxDecoration(
                                color: Colors.black.withOpacity(0.08),
                                shape: BoxShape.circle,
                              ),
                            ),
                          ),
                          const Padding(
                            padding: EdgeInsets.all(24),
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              mainAxisAlignment: MainAxisAlignment.spaceBetween,
                              children: [
                                _HeroBadge(),
                                Text(
                                  'Secure sign in for\nmobile access',
                                  style: TextStyle(
                                    color: Colors.white,
                                    fontSize: 28,
                                    fontWeight: FontWeight.w800,
                                    height: 1.1,
                                    letterSpacing: -0.8,
                                  ),
                                ),
                              ],
                            ),
                          ),
                        ],
                      ),
                    ),
                    const SizedBox(height: 28),
                    Text('Welcome back', style: theme.textTheme.headlineLarge),
                    const SizedBox(height: 8),
                    Text(
                      'Sign in using the same Laravel account used by your active users.',
                      style: theme.textTheme.titleMedium,
                    ),
                    const SizedBox(height: 18),
                    const _LoginHint(),
                    const SizedBox(height: 18),
                    _BaseUrlNotice(baseUrl: AppConfig.apiBaseUrl),
                    const SizedBox(height: 24),
                    const _FieldLabel('Work Email'),
                    const SizedBox(height: 10),
                    TextFormField(
                      controller: _emailController,
                      keyboardType: TextInputType.emailAddress,
                      decoration: const InputDecoration(
                        hintText: 'you@company.com',
                        prefixIcon: Icon(Icons.alternate_email_rounded),
                      ),
                      validator: (value) {
                        final text = value?.trim() ?? '';
                        if (text.isEmpty) {
                          return 'Email wajib diisi.';
                        }
                        if (!text.contains('@')) {
                          return 'Format email tidak valid.';
                        }
                        return null;
                      },
                    ),
                    const SizedBox(height: 18),
                    const _FieldLabel('Password'),
                    const SizedBox(height: 10),
                    TextFormField(
                      controller: _passwordController,
                      obscureText: _obscurePassword,
                      decoration: InputDecoration(
                        hintText: 'Enter your password',
                        prefixIcon: const Icon(Icons.lock_outline_rounded),
                        suffixIcon: IconButton(
                          onPressed: () {
                            setState(() {
                              _obscurePassword = !_obscurePassword;
                            });
                          },
                          icon: Icon(
                            _obscurePassword
                                ? Icons.visibility_outlined
                                : Icons.visibility_off_outlined,
                          ),
                        ),
                      ),
                      validator: (value) {
                        if ((value ?? '').isEmpty) {
                          return 'Password wajib diisi.';
                        }
                        return null;
                      },
                      onFieldSubmitted: (_) => _submit(),
                    ),
                    const SizedBox(height: 14),
                    Row(
                      children: [
                        Switch(
                          value: _rememberMe,
                          onChanged: controller.isLoggingIn
                              ? null
                              : (value) {
                                  setState(() {
                                    _rememberMe = value;
                                  });
                                },
                        ),
                        const Text(
                          'Remember me',
                          style: TextStyle(
                            fontSize: 14,
                            fontWeight: FontWeight.w600,
                            color: Color(0xFF334E68),
                          ),
                        ),
                        const Spacer(),
                        TextButton(
                          onPressed: controller.isLoggingIn ? null : () {},
                          child: const Text('Forgot password?'),
                        ),
                      ],
                    ),
                    if (controller.errorMessage != null) ...[
                      const SizedBox(height: 8),
                      _ErrorBanner(message: controller.errorMessage!),
                    ],
                    const SizedBox(height: 14),
                    SizedBox(
                      width: double.infinity,
                      child: FilledButton(
                        onPressed: controller.isLoggingIn ? null : _submit,
                        style: FilledButton.styleFrom(
                          backgroundColor: const Color(0xFF102A43),
                          foregroundColor: Colors.white,
                          padding: const EdgeInsets.symmetric(vertical: 18),
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(22),
                          ),
                        ),
                        child: controller.isLoggingIn
                            ? const SizedBox(
                                width: 22,
                                height: 22,
                                child: CircularProgressIndicator(
                                  strokeWidth: 2.2,
                                  color: Colors.white,
                                ),
                              )
                            : const Text(
                                'Sign In',
                                style: TextStyle(
                                  fontSize: 16,
                                  fontWeight: FontWeight.w700,
                                ),
                              ),
                      ),
                    ),
                    const SizedBox(height: 18),
                    Row(
                      children: [
                        Expanded(
                          child: Divider(
                            color: const Color(0xFF9FB3C8).withOpacity(0.5),
                          ),
                        ),
                        const Padding(
                          padding: EdgeInsets.symmetric(horizontal: 12),
                          child: Text(
                            'API mobile status',
                            style: TextStyle(
                              color: Color(0xFF829AB1),
                              fontWeight: FontWeight.w600,
                            ),
                          ),
                        ),
                        Expanded(
                          child: Divider(
                            color: const Color(0xFF9FB3C8).withOpacity(0.5),
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 18),
                    const _EndpointSummary(),
                  ],
                ),
              ),
            ),
          ),
        ),
      ),
    );
  }
}

class HomePage extends StatelessWidget {
  const HomePage({
    super.key,
    required this.controller,
    required this.session,
  });

  final SessionController controller;
  final Session session;

  @override
  Widget build(BuildContext context) {
    final user = session.user;
    final dashboardJson = controller.dashboardData == null
        ? null
        : const JsonEncoder.withIndent('  ').convert(controller.dashboardData);

    return Scaffold(
      appBar: AppBar(
        title: const Text('Mobile Session'),
        actions: [
          TextButton(
            onPressed: controller.isBusy ? null : controller.logout,
            child: const Text('Logout'),
          ),
        ],
      ),
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(24),
          child: Center(
            child: ConstrainedBox(
              constraints: const BoxConstraints(maxWidth: 720),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Wrap(
                    spacing: 16,
                    runSpacing: 16,
                    children: [
                      _InfoCard(
                        title: 'User',
                        lines: [
                          user.name,
                          user.email,
                          'Role: ${user.role ?? '-'}',
                        ],
                      ),
                      _InfoCard(
                        title: 'Route',
                        lines: [
                          session.mobileRoute ?? 'Tidak ada mobile_route',
                          'Token tersimpan aman di device',
                        ],
                      ),
                    ],
                  ),
                  const SizedBox(height: 24),
                  Row(
                    children: [
                      FilledButton.icon(
                        onPressed:
                            controller.isBusy ? null : controller.loadDashboard,
                        icon: controller.isDashboardLoading
                            ? const SizedBox(
                                width: 16,
                                height: 16,
                                child: CircularProgressIndicator(
                                  strokeWidth: 2,
                                  color: Colors.white,
                                ),
                              )
                            : const Icon(Icons.space_dashboard_outlined),
                        label: const Text('Load Dashboard'),
                      ),
                      const SizedBox(width: 12),
                      OutlinedButton.icon(
                        onPressed:
                            controller.isBusy ? null : controller.refreshMe,
                        icon: const Icon(Icons.refresh),
                        label: const Text('Refresh Profile'),
                      ),
                    ],
                  ),
                  if (controller.errorMessage != null) ...[
                    const SizedBox(height: 16),
                    _ErrorBanner(message: controller.errorMessage!),
                  ],
                  const SizedBox(height: 24),
                  Container(
                    width: double.infinity,
                    padding: const EdgeInsets.all(20),
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(24),
                    ),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const Text(
                          'Dashboard Response',
                          style: TextStyle(
                            fontSize: 18,
                            fontWeight: FontWeight.w700,
                            color: Color(0xFF102A43),
                          ),
                        ),
                        const SizedBox(height: 12),
                        SelectableText(
                          dashboardJson ??
                              'Tekan "Load Dashboard" untuk mengetes endpoint GET /api/mobile/dashboard.',
                          style: const TextStyle(
                            height: 1.4,
                            color: Color(0xFF334E68),
                            fontFamily: 'monospace',
                          ),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),
          ),
        ),
      ),
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

class _HeroBadge extends StatelessWidget {
  const _HeroBadge();

  @override
  Widget build(BuildContext context) {
    return Container(
      width: 54,
      height: 54,
      decoration: BoxDecoration(
        color: Colors.white.withOpacity(0.16),
        borderRadius: BorderRadius.circular(18),
      ),
      child: const Icon(
        Icons.shield_outlined,
        color: Colors.white,
        size: 28,
      ),
    );
  }
}

class _BaseUrlNotice extends StatelessWidget {
  const _BaseUrlNotice({
    required this.baseUrl,
  });

  final String baseUrl;

  @override
  Widget build(BuildContext context) {
    final isPlaceholder = AppConfig.isPlaceholder;

    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: isPlaceholder ? const Color(0xFFFEF6E7) : Colors.white,
        borderRadius: BorderRadius.circular(20),
        border: Border.all(
          color:
              isPlaceholder ? const Color(0xFFF4B860) : const Color(0xFFD9E2EC),
        ),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            isPlaceholder ? 'Base URL belum diatur' : 'Base URL aktif',
            style: const TextStyle(
              fontWeight: FontWeight.w700,
              color: Color(0xFF102A43),
            ),
          ),
          const SizedBox(height: 8),
          SelectableText(
            baseUrl,
            style: const TextStyle(
              color: Color(0xFF334E68),
              fontFamily: 'monospace',
            ),
          ),
          if (isPlaceholder) ...[
            const SizedBox(height: 8),
            const Text(
              'Jalankan app dengan --dart-define=API_BASE_URL=https://domain-anda.com/api',
              style: TextStyle(
                color: Color(0xFF8D5500),
                fontWeight: FontWeight.w600,
              ),
            ),
          ],
        ],
      ),
    );
  }
}

class _LoginHint extends StatelessWidget {
  const _LoginHint();

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: const Color(0xFFD9E2EC)),
      ),
      child: const Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            'Catatan login',
            style: TextStyle(
              fontWeight: FontWeight.w700,
              color: Color(0xFF102A43),
            ),
          ),
          SizedBox(height: 8),
          Text(
            'API mobile saat ini mengirim field email dan password. Jika akun web Anda biasa login dengan username atau nomor lain, gunakan alamat email yang terdaftar di Laravel.',
            style: TextStyle(
              color: Color(0xFF334E68),
              fontWeight: FontWeight.w600,
            ),
          ),
        ],
      ),
    );
  }
}

class _EndpointSummary extends StatelessWidget {
  const _EndpointSummary();

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(18),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(24),
        border: Border.all(color: const Color(0xFFD9E2EC)),
      ),
      child: const Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          _ApiLine('POST', '/mobile/login'),
          _ApiLine('GET', '/mobile/me'),
          _ApiLine('GET', '/mobile/dashboard'),
          _ApiLine('POST', '/mobile/logout'),
        ],
      ),
    );
  }
}

class _ApiLine extends StatelessWidget {
  const _ApiLine(this.method, this.path);

  final String method;
  final String path;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4),
      child: Row(
        children: [
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
            decoration: BoxDecoration(
              color: const Color(0xFFE0FBFC),
              borderRadius: BorderRadius.circular(999),
            ),
            child: Text(
              method,
              style: const TextStyle(
                fontWeight: FontWeight.w800,
                color: Color(0xFF0D5C63),
              ),
            ),
          ),
          const SizedBox(width: 12),
          Text(
            path,
            style: const TextStyle(
              fontWeight: FontWeight.w700,
              color: Color(0xFF334E68),
            ),
          ),
        ],
      ),
    );
  }
}

class _ErrorBanner extends StatelessWidget {
  const _ErrorBanner({
    required this.message,
  });

  final String message;

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: const Color(0xFFFFF1F2),
        borderRadius: BorderRadius.circular(18),
        border: Border.all(color: const Color(0xFFFDA4AF)),
      ),
      child: Text(
        message,
        style: const TextStyle(
          color: Color(0xFF9F1239),
          fontWeight: FontWeight.w600,
        ),
      ),
    );
  }
}

class _FieldLabel extends StatelessWidget {
  const _FieldLabel(this.label);

  final String label;

  @override
  Widget build(BuildContext context) {
    return Text(
      label,
      style: const TextStyle(
        fontSize: 14,
        fontWeight: FontWeight.w700,
        color: Color(0xFF243B53),
      ),
    );
  }
}

class _InfoCard extends StatelessWidget {
  const _InfoCard({
    required this.title,
    required this.lines,
  });

  final String title;
  final List<String> lines;

  @override
  Widget build(BuildContext context) {
    return Container(
      width: 320,
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(24),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            title,
            style: const TextStyle(
              fontSize: 18,
              fontWeight: FontWeight.w700,
              color: Color(0xFF102A43),
            ),
          ),
          const SizedBox(height: 12),
          for (final line in lines)
            Padding(
              padding: const EdgeInsets.only(bottom: 6),
              child: Text(
                line,
                style: const TextStyle(
                  color: Color(0xFF334E68),
                  fontWeight: FontWeight.w600,
                ),
              ),
            ),
        ],
      ),
    );
  }
}
