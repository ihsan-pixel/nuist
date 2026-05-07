import 'package:flutter/material.dart';

import '../../services/auth_repository.dart';
import '../../theme/app_theme.dart';
import '../../widgets/auth/auth_action_button.dart';
import '../../widgets/auth/auth_field_label.dart';
import '../../widgets/auth/auth_page_scaffold.dart';
import '../../widgets/auth/status_banner.dart';

class ForgotPasswordPage extends StatefulWidget {
  const ForgotPasswordPage({
    super.key,
    required this.authRepository,
  });

  final AuthRepository authRepository;

  @override
  State<ForgotPasswordPage> createState() => _ForgotPasswordPageState();
}

class _ForgotPasswordPageState extends State<ForgotPasswordPage> {
  final _formKey = GlobalKey<FormState>();
  final _emailController = TextEditingController();

  bool _isSubmitting = false;
  String? _errorMessage;
  String? _successMessage;

  @override
  void dispose() {
    _emailController.dispose();
    super.dispose();
  }

  Future<void> _submit() async {
    final form = _formKey.currentState;
    if (form == null || !form.validate()) {
      return;
    }

    setState(() {
      _isSubmitting = true;
      _errorMessage = null;
      _successMessage = null;
    });

    try {
      final message = await widget.authRepository.sendPasswordResetLink(
        email: _emailController.text.trim(),
      );

      if (!mounted) {
        return;
      }

      setState(() {
        _successMessage = message;
      });
    } catch (error) {
      if (!mounted) {
        return;
      }

      setState(() {
        _errorMessage = error.toString();
      });
    } finally {
      if (mounted) {
        setState(() {
          _isSubmitting = false;
        });
      }
    }
  }

  void _backToLogin() {
    Navigator.of(context).pop(_successMessage);
  }

  @override
  Widget build(BuildContext context) {
    return AuthPageScaffold(
      backgroundAsset: 'assets/images/login_bg.png',
      title: 'Welcome!',
      subtitle: 'Reset your password through your email',
      footer: Row(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          const Text(
            'Ingat password Anda? ',
            style: TextStyle(
              color: AppColors.textMuted,
              fontSize: 12,
            ),
          ),
          GestureDetector(
            onTap: _backToLogin,
            child: const Text(
              'Masuk di sini',
              style: TextStyle(
                color: AppColors.accentDeep,
                fontWeight: FontWeight.w700,
                fontSize: 12,
              ),
            ),
          ),
        ],
      ),
      children: [
        if (_successMessage != null) ...[
          StatusBanner(
            message: _successMessage!,
            type: StatusBannerType.success,
          ),
          const SizedBox(height: 12),
        ],
        if (_errorMessage != null) ...[
          StatusBanner(
            message: _errorMessage!,
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
              const SizedBox(height: 16),
              AuthActionButton(
                label: 'Kirim Tautan Reset',
                filled: true,
                onPressed: _submit,
                isLoading: _isSubmitting,
              ),
              const SizedBox(height: 10),
              AuthActionButton(
                label: 'Kembali ke Login',
                filled: false,
                onPressed: _isSubmitting ? null : _backToLogin,
              ),
            ],
          ),
        ),
      ],
    );
  }
}
