import 'package:flutter/material.dart';

import '../../controllers/session_controller.dart';
import '../../services/auth_repository.dart';
import '../../theme/app_theme.dart';
import '../../widgets/auth/auth_action_button.dart';
import '../../widgets/auth/auth_field_label.dart';
import '../../widgets/auth/auth_page_scaffold.dart';
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

  @override
  void initState() {
    super.initState();
    _restoreRememberedLogin();
  }

  @override
  void dispose() {
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

    return AuthPageScaffold(
      backgroundAsset: 'assets/images/login_bg.png',
      title: 'Welcome Back!',
      subtitle: 'Login to Nuist LP. Maarif NU PWNU DIY',
      footer: Row(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          const Text(
            'Belum punya akun? ',
            style: TextStyle(
              color: AppColors.textMuted,
              fontSize: 12,
            ),
          ),
          GestureDetector(
            onTap: controller.isLoggingIn ? null : _openRegisterPage,
            child: Text(
              'Daftar di sini',
              style: TextStyle(
                color: controller.isLoggingIn
                    ? AppColors.textMuted
                    : AppColors.accentDeep,
                fontWeight: FontWeight.w700,
                fontSize: 12,
              ),
            ),
          ),
        ],
      ),
      children: [
        if (controller.errorMessage != null) ...[
          StatusBanner(
            message: controller.errorMessage!,
            type: StatusBannerType.error,
          ),
          const SizedBox(height: 12),
        ],
        Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const AuthFieldLabel('Email'),
              const SizedBox(height: 6),
              TextFormField(
                controller: _emailController,
                keyboardType: TextInputType.emailAddress,
                textInputAction: TextInputAction.next,
                decoration: const InputDecoration(
                  hintText: 'Masukkan email akun',
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
              const SizedBox(height: 12),
              const AuthFieldLabel('Password'),
              const SizedBox(height: 6),
              TextFormField(
                controller: _passwordController,
                obscureText: _obscurePassword,
                onFieldSubmitted: (_) => _submit(),
                decoration: InputDecoration(
                  hintText: 'Masukkan password',
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
              ),
              const SizedBox(height: 10),
              Row(
                children: [
                  Checkbox(
                    value: _rememberMe,
                    onChanged: controller.isLoggingIn
                        ? null
                        : (value) => _setRememberMe(value ?? false),
                    activeColor: AppColors.accentMain,
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(6),
                    ),
                  ),
                  Expanded(
                    child: GestureDetector(
                      onTap: controller.isLoggingIn
                          ? null
                          : () => _setRememberMe(!_rememberMe),
                      child: const Text(
                        'Remember me',
                        style: TextStyle(
                          color: AppColors.textBody,
                          fontSize: 12,
                          fontWeight: FontWeight.w600,
                        ),
                      ),
                    ),
                  ),
                  GestureDetector(
                    onTap:
                        controller.isLoggingIn ? null : _openForgotPasswordPage,
                    child: Text(
                      'Lupa password?',
                      style: TextStyle(
                        color: controller.isLoggingIn
                            ? AppColors.textMuted
                            : AppColors.accentDeep,
                        fontWeight: FontWeight.w700,
                        fontSize: 12,
                      ),
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 4),
              const Text(
                'Jika dipilih, email dan password akan tersimpan untuk login berikutnya.',
                style: TextStyle(
                  color: AppColors.textMuted,
                  fontSize: 11,
                ),
              ),
              const SizedBox(height: 16),
              AuthActionButton(
                label: 'Masuk',
                filled: true,
                onPressed: _submit,
                isLoading: controller.isLoggingIn,
              ),
            ],
          ),
        ),
      ],
    );
  }
}
