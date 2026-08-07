import 'package:flutter/material.dart';

import '../../services/auth_repository.dart';
import '../../widgets/app/app_section_card.dart';
import '../../widgets/app/teacher_page_header.dart';
import '../../widgets/auth/auth_action_button.dart';
import '../../widgets/auth/auth_field_label.dart';
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
  final _emailFocusNode = FocusNode();

  bool _isSubmitting = false;
  String? _errorMessage;
  String? _successMessage;

  @override
  void dispose() {
    _emailController.dispose();
    _emailFocusNode.dispose();
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
    final bottomInset = MediaQuery.paddingOf(context).bottom + 24;

    return Scaffold(
      backgroundColor: const Color(0xFFF7F8FC),
      body: SafeArea(
        bottom: false,
        child: ListView(
          padding: EdgeInsets.fromLTRB(14, 12, 14, bottomInset),
          children: [
            TeacherPageHeader(
              title: 'Lupa Password',
              onBack: _backToLogin,
            ),
            const SizedBox(height: 12),
            AppSectionCard(
              padding: const EdgeInsets.all(14),
              child: Form(
                key: _formKey,
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text(
                      'Reset Password',
                      style: TextStyle(
                        fontSize: 18,
                        fontWeight: FontWeight.w800,
                        color: Color(0xFF1E293B),
                      ),
                    ),
                    const SizedBox(height: 6),
                    const Text(
                      'Masukkan email akun untuk menerima tautan reset password. Tampilan halaman ini sudah disamakan dengan tema guru.',
                      style: TextStyle(
                        fontSize: 11.5,
                        height: 1.45,
                        color: Color(0xFF64748B),
                      ),
                    ),
                    const SizedBox(height: 12),
                    if (_successMessage != null) ...[
                      StatusBanner(
                        message: _successMessage!,
                        type: StatusBannerType.success,
                      ),
                      const SizedBox(height: 10),
                    ],
                    if (_errorMessage != null) ...[
                      StatusBanner(
                        message: _errorMessage!,
                        type: StatusBannerType.error,
                      ),
                      const SizedBox(height: 10),
                    ],
                    const AuthFieldLabel('Email'),
                    const SizedBox(height: 6),
                    TextFormField(
                      controller: _emailController,
                      focusNode: _emailFocusNode,
                      autofocus: true,
                      enabled: !_isSubmitting,
                      keyboardType: TextInputType.emailAddress,
                      textInputAction: TextInputAction.done,
                      decoration: const InputDecoration(
                        hintText: 'Masukkan email akun',
                        prefixIcon: Icon(Icons.mail_outline_rounded),
                        contentPadding: EdgeInsets.symmetric(
                          horizontal: 14,
                          vertical: 12,
                        ),
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
                      onFieldSubmitted: (_) => _submit(),
                    ),
                    const SizedBox(height: 14),
                    AuthActionButton(
                      label: 'Kirim Tautan Reset',
                      filled: true,
                      onPressed: _submit,
                      isLoading: _isSubmitting,
                    ),
                    const SizedBox(height: 8),
                    AuthActionButton(
                      label: 'Kembali ke Login',
                      filled: false,
                      onPressed: _isSubmitting ? null : _backToLogin,
                    ),
                  ],
                ),
              ),
            ),
            const SizedBox(height: 12),
            const Text(
              'Nuist Mobile v1.0.0+1',
              textAlign: TextAlign.center,
              style: TextStyle(
                fontSize: 11,
                fontWeight: FontWeight.w600,
                color: Color(0xFF64748B),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
