import 'package:flutter/material.dart';

import '../../controllers/session_controller.dart';
import '../../services/auth_repository.dart';
import '../../theme/app_theme.dart';
import '../../widgets/auth/auth_action_button.dart';
import '../../widgets/auth/status_banner.dart';
import 'forgot_password_page.dart';
import 'register_page.dart';

class LoginPage extends StatefulWidget {
  const LoginPage({
    super.key,
    required this.controller,
    required this.authRepository,
  });

  final SessionController controller;
  final AuthRepository authRepository;

  @override
  State<LoginPage> createState() => _LoginPageState();
}

class _LoginPageState extends State<LoginPage> {
  final _formKey = GlobalKey<FormState>();
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  bool _obscurePassword = true;
  bool _rememberMe = false;
  bool _loadingRemembered = true;
  String? _lastShownError;

  @override
  void initState() {
    super.initState();
    widget.controller.addListener(_handleControllerChanged);
    _restoreRememberedLogin();
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

  Future<void> _restoreRememberedLogin() async {
    try {
      final remembered = await widget.authRepository.readRememberedLogin();
      if (!mounted) {
        return;
      }

      setState(() {
        _rememberMe = remembered['remember'] == true;
        if (_rememberMe) {
          _emailController.text = (remembered['email'] as String?) ?? '';
          _passwordController.text = (remembered['password'] as String?) ?? '';
        }
        _loadingRemembered = false;
      });
    } catch (_) {
      if (!mounted) {
        return;
      }
      setState(() {
        _loadingRemembered = false;
      });
    }
  }

  void _handleControllerChanged() {
    final message = widget.controller.errorMessage;
    if (!mounted) {
      return;
    }

    if (message == null || message == _lastShownError) {
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

  Future<void> _setRememberMe(bool value) async {
    setState(() {
      _rememberMe = value;
    });

    if (!value) {
      await widget.authRepository.clearRememberedLogin();
    }
  }

  Future<void> _openRegisterPage() async {
    final message = await Navigator.of(context).push<String>(
      MaterialPageRoute(
        builder: (_) => RegisterPage(
          authRepository: widget.authRepository,
        ),
      ),
    );

    if (!mounted || message == null || message.isEmpty) {
      return;
    }

    ScaffoldMessenger.of(context)
      ..hideCurrentSnackBar()
      ..showSnackBar(SnackBar(content: Text(message)));
  }

  Future<void> _openForgotPasswordPage() async {
    final message = await Navigator.of(context).push<String>(
      MaterialPageRoute(
        builder: (_) => ForgotPasswordPage(
          authRepository: widget.authRepository,
        ),
      ),
    );

    if (!mounted || message == null || message.isEmpty) {
      return;
    }

    ScaffoldMessenger.of(context)
      ..hideCurrentSnackBar()
      ..showSnackBar(SnackBar(content: Text(message)));
  }

  @override
  Widget build(BuildContext context) {
    final controller = widget.controller;

    if (_loadingRemembered) {
      return const Scaffold(
        body: Center(child: CircularProgressIndicator()),
      );
    }

    return Scaffold(
      backgroundColor: const Color(0xFFF7F8F5),
      body: SafeArea(
        child: ListView(
          padding: const EdgeInsets.fromLTRB(18, 16, 18, 28),
          children: [
            const _LoginHero(),
            const SizedBox(height: 18),
            Container(
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(30),
                boxShadow: const [
                  BoxShadow(
                    color: Color(0x12003B39),
                    blurRadius: 24,
                    offset: Offset(0, 10),
                  ),
                ],
              ),
              padding: const EdgeInsets.fromLTRB(18, 18, 18, 20),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text(
                    'Masuk ke akun Anda',
                    style: TextStyle(
                      color: AppColors.textMain,
                      fontSize: 22,
                      fontWeight: FontWeight.w800,
                    ),
                  ),
                  const SizedBox(height: 6),
                  const Text(
                    'Lanjutkan presensi, jurnal mengajar, izin, dan laporan dalam satu aplikasi.',
                    style: TextStyle(
                      color: AppColors.textMuted,
                      fontSize: 13,
                      height: 1.45,
                    ),
                  ),
                  const SizedBox(height: 16),
                  if (controller.errorMessage != null) ...[
                    StatusBanner(
                      message: controller.errorMessage!,
                      type: StatusBannerType.error,
                    ),
                    const SizedBox(height: 16),
                  ],
                  Form(
                    key: _formKey,
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const _InlineFieldLabel('Email'),
                        const SizedBox(height: 8),
                        TextFormField(
                          controller: _emailController,
                          keyboardType: TextInputType.emailAddress,
                          decoration: const InputDecoration(
                            hintText: 'Masukkan email akun Anda',
                            prefixIcon: Icon(Icons.mail_outline_rounded),
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
                        const SizedBox(height: 14),
                        const _InlineFieldLabel('Password'),
                        const SizedBox(height: 8),
                        TextFormField(
                          controller: _passwordController,
                          obscureText: _obscurePassword,
                          decoration: InputDecoration(
                            hintText: 'Masukkan password',
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
                                color: AppColors.accentMain,
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
                            Expanded(
                              child: InkWell(
                                borderRadius: BorderRadius.circular(12),
                                onTap: () => _setRememberMe(!_rememberMe),
                                child: Row(
                                  children: [
                                    Checkbox(
                                      value: _rememberMe,
                                      onChanged: (value) =>
                                          _setRememberMe(value ?? false),
                                      activeColor: AppColors.accentMain,
                                      shape: RoundedRectangleBorder(
                                        borderRadius: BorderRadius.circular(6),
                                      ),
                                    ),
                                    const Expanded(
                                      child: Text(
                                        'Remember me',
                                        style: TextStyle(
                                          color: AppColors.textBody,
                                          fontWeight: FontWeight.w600,
                                        ),
                                      ),
                                    ),
                                  ],
                                ),
                              ),
                            ),
                            TextButton(
                              onPressed: controller.isLoggingIn
                                  ? null
                                  : _openForgotPasswordPage,
                              child: const Text(
                                'Lupa password?',
                                style: TextStyle(
                                  fontWeight: FontWeight.w700,
                                  color: AppColors.accentDeep,
                                ),
                              ),
                            ),
                          ],
                        ),
                        const SizedBox(height: 6),
                        AuthActionButton(
                          label: 'Masuk Sekarang',
                          filled: true,
                          onPressed: _submit,
                          isLoading: controller.isLoggingIn,
                        ),
                        const SizedBox(height: 12),
                        SizedBox(
                          width: double.infinity,
                          child: OutlinedButton(
                            onPressed: controller.isLoggingIn
                                ? null
                                : _openRegisterPage,
                            style: OutlinedButton.styleFrom(
                              minimumSize: const Size.fromHeight(52),
                              side: const BorderSide(
                                color: Color(0xFFE0E8E7),
                              ),
                              shape: RoundedRectangleBorder(
                                borderRadius: BorderRadius.circular(16),
                              ),
                            ),
                            child: const Text(
                              'Buat Akun Baru',
                              style: TextStyle(
                                color: AppColors.textMain,
                                fontWeight: FontWeight.w700,
                              ),
                            ),
                          ),
                        ),
                        const SizedBox(height: 14),
                        const Text(
                          'Jika memilih remember me, email dan password akan diisi otomatis saat membuka aplikasi kembali.',
                          style: TextStyle(
                            color: AppColors.textMuted,
                            fontSize: 11,
                            height: 1.45,
                          ),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}

class _LoginHero extends StatelessWidget {
  const _LoginHero();

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.fromLTRB(18, 18, 18, 20),
      decoration: BoxDecoration(
        gradient: const LinearGradient(
          colors: [
            Color(0xFF0D8E89),
            Color(0xFF06706B),
            Color(0xFFF4A12A),
          ],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
        borderRadius: BorderRadius.circular(34),
      ),
      child: Stack(
        children: [
          Positioned(
            right: -18,
            top: -14,
            child: Container(
              width: 126,
              height: 126,
              decoration: BoxDecoration(
                color: Colors.white.withOpacity(0.12),
                shape: BoxShape.circle,
              ),
            ),
          ),
          Positioned(
            left: 120,
            bottom: -26,
            child: Container(
              width: 148,
              height: 148,
              decoration: BoxDecoration(
                color: Colors.white.withOpacity(0.08),
                shape: BoxShape.circle,
              ),
            ),
          ),
          Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                children: [
                  Container(
                    width: 54,
                    height: 54,
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(18),
                    ),
                    child: const Icon(
                      Icons.shield_rounded,
                      color: AppColors.accentMain,
                      size: 30,
                    ),
                  ),
                  const Spacer(),
                  Container(
                    padding: const EdgeInsets.symmetric(
                      horizontal: 12,
                      vertical: 8,
                    ),
                    decoration: BoxDecoration(
                      color: Colors.white.withOpacity(0.16),
                      borderRadius: BorderRadius.circular(999),
                      border: Border.all(
                        color: Colors.white.withOpacity(0.18),
                      ),
                    ),
                    child: const Text(
                      'NUIST Mobile',
                      style: TextStyle(
                        color: Colors.white,
                        fontWeight: FontWeight.w700,
                      ),
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 22),
              const Text(
                'Akses kerja harian Anda dalam satu sentuhan.',
                style: TextStyle(
                  color: Colors.white,
                  fontSize: 28,
                  fontWeight: FontWeight.w800,
                  height: 1.15,
                ),
              ),
              const SizedBox(height: 10),
              Text(
                "Presensi, jurnal mengajar, izin, dan pemantauan guru dalam pengalaman mobile yang lebih ringkas dan cepat.",
                style: TextStyle(
                  color: Colors.white.withOpacity(0.88),
                  fontSize: 13,
                  height: 1.55,
                ),
              ),
              const SizedBox(height: 18),
              const Row(
                children: [
                  Expanded(
                    child: _HeroMiniCard(
                      icon: Icons.fact_check_rounded,
                      title: 'Presensi',
                      value: 'Realtime',
                    ),
                  ),
                  SizedBox(width: 10),
                  Expanded(
                    child: _HeroMiniCard(
                      icon: Icons.menu_book_rounded,
                      title: 'Jurnal',
                      value: 'Terstruktur',
                    ),
                  ),
                  SizedBox(width: 10),
                  Expanded(
                    child: _HeroMiniCard(
                      icon: Icons.summarize_rounded,
                      title: 'Laporan',
                      value: 'Siap PDF',
                    ),
                  ),
                ],
              ),
            ],
          ),
        ],
      ),
    );
  }
}

class _HeroMiniCard extends StatelessWidget {
  const _HeroMiniCard({
    required this.icon,
    required this.title,
    required this.value,
  });

  final IconData icon;
  final String title;
  final String value;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: Colors.white.withOpacity(0.14),
        borderRadius: BorderRadius.circular(18),
        border: Border.all(color: Colors.white.withOpacity(0.14)),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Icon(icon, color: Colors.white, size: 20),
          const SizedBox(height: 12),
          Text(
            title,
            style: TextStyle(
              color: Colors.white.withOpacity(0.82),
              fontSize: 11,
              fontWeight: FontWeight.w600,
            ),
          ),
          const SizedBox(height: 2),
          Text(
            value,
            style: const TextStyle(
              color: Colors.white,
              fontWeight: FontWeight.w800,
            ),
          ),
        ],
      ),
    );
  }
}

class _InlineFieldLabel extends StatelessWidget {
  const _InlineFieldLabel(this.label);

  final String label;

  @override
  Widget build(BuildContext context) {
    return Text(
      label,
      style: const TextStyle(
        color: AppColors.textMain,
        fontSize: 13,
        fontWeight: FontWeight.w700,
      ),
    );
  }
}
