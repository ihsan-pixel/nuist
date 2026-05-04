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
  bool _isLoginPanelOpen = false;
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
    if (!mounted) {
      return;
    }

    if (message != null && !_isLoginPanelOpen) {
      setState(() {
        _isLoginPanelOpen = true;
      });
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
      rememberSession: true,
    );
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

  void _toggleLoginPanel() {
    setState(() {
      _isLoginPanelOpen = !_isLoginPanelOpen;
    });
  }

  @override
  Widget build(BuildContext context) {
    final controller = widget.controller;

    return AuthPageScaffold(
      title: 'Welcome!',
      subtitle: "Nuist Mobile LP. Ma'arif NU PWNU DIY",
      children: [
        if (controller.errorMessage != null) ...[
          StatusBanner(
            message: controller.errorMessage!,
            type: StatusBannerType.error,
          ),
          const SizedBox(height: 16),
        ],
        Form(
          key: _formKey,
          child: AnimatedSize(
            duration: const Duration(milliseconds: 250),
            curve: Curves.easeOut,
            child: _isLoginPanelOpen
                ? Container(
                    padding: const EdgeInsets.only(top: 16),
                    decoration: const BoxDecoration(
                      border: Border(
                        top: BorderSide(
                          color: Color(0xFFD7E8E7),
                        ),
                      ),
                    ),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const Center(
                          child: Text(
                            'Masuk ke akun Anda',
                            style: TextStyle(
                              fontSize: 14,
                              fontWeight: FontWeight.w600,
                              color: AppColors.textMain,
                            ),
                          ),
                        ),
                        const SizedBox(height: 12),
                        const AuthFieldLabel('Email'),
                        const SizedBox(height: 6),
                        TextFormField(
                          controller: _emailController,
                          keyboardType: TextInputType.emailAddress,
                          decoration: const InputDecoration(
                            hintText: 'Masukkan email',
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
                          onFieldSubmitted: (_) => _submit(),
                        ),
                        const SizedBox(height: 12),
                        const Text(
                          'Gunakan email akun web yang terdaftar di Laravel.',
                          style: TextStyle(
                            fontSize: 12,
                            color: AppColors.textMuted,
                          ),
                        ),
                        const SizedBox(height: 16),
                        AuthActionButton(
                          label: 'Login Sekarang',
                          filled: true,
                          onPressed: _submit,
                          isLoading: controller.isLoggingIn,
                        ),
                        const SizedBox(height: 12),
                        Center(
                          child: TextButton(
                            onPressed: controller.isLoggingIn
                                ? null
                                : _openForgotPasswordPage,
                            child: const Text(
                              'Forgot password?',
                              style: TextStyle(
                                fontSize: 12,
                                fontWeight: FontWeight.w600,
                                color: AppColors.accentDeep,
                              ),
                            ),
                          ),
                        ),
                      ],
                    ),
                  )
                : const SizedBox.shrink(),
          ),
        ),
        const SizedBox(height: 12),
        AuthActionButton(
          label: 'Sign Up',
          filled: true,
          onPressed: controller.isLoggingIn ? null : _openRegisterPage,
        ),
        const SizedBox(height: 12),
        AuthActionButton(
          label: 'Login',
          filled: false,
          onPressed: controller.isLoggingIn ? null : _toggleLoginPanel,
        ),
      ],
    );
  }
}
