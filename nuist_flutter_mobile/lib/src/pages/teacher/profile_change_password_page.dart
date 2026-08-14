import 'package:flutter/material.dart';

import '../../services/teacher_mobile_repository.dart';
import '../../widgets/app/teacher_page_header.dart';

class _PasswordPagePalette {
  static const background = Color(0xFFFFFFFF);
  static const surface = Color(0xFFFFFFFF);
  static const primary = Color(0xFF00745A);
  static const textPrimary = Color(0xFF172A24);
  static const textSecondary = Color(0xFF64746E);
  static const border = Color(0xFFDCE7E3);
  static const danger = Color(0xFFEF4444);
  static const warning = Color(0xFFF59E0B);
  static const info = Color(0xFF00745A);
  static const success = Color(0xFF00745A);

  const _PasswordPagePalette._();
}

class TeacherProfileChangePasswordPage extends StatefulWidget {
  const TeacherProfileChangePasswordPage({
    super.key,
    required this.repository,
  });

  final TeacherMobileRepository repository;

  @override
  State<TeacherProfileChangePasswordPage> createState() =>
      _TeacherProfileChangePasswordPageState();
}

class _TeacherProfileChangePasswordPageState
    extends State<TeacherProfileChangePasswordPage> {
  final TextEditingController _currentPasswordController =
      TextEditingController();
  final TextEditingController _newPasswordController = TextEditingController();
  final TextEditingController _confirmPasswordController =
      TextEditingController();

  bool _submitting = false;
  bool _obscureCurrent = true;
  bool _obscureNew = true;
  bool _obscureConfirm = true;

  @override
  void dispose() {
    _currentPasswordController.dispose();
    _newPasswordController.dispose();
    _confirmPasswordController.dispose();
    super.dispose();
  }

  Future<void> _submit() async {
    final currentPassword = _currentPasswordController.text;
    final newPassword = _newPasswordController.text;
    final confirmPassword = _confirmPasswordController.text;
    final strength = _PasswordStrength.evaluate(newPassword);

    if (!strength.meetsMinimumRequirement) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(
            strength.message.isNotEmpty
                ? strength.message
                : 'Password baru belum memenuhi kriteria keamanan.',
          ),
        ),
      );
      return;
    }

    if (newPassword != confirmPassword) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Konfirmasi password baru tidak sesuai.'),
        ),
      );
      return;
    }

    setState(() {
      _submitting = true;
    });

    try {
      final result = await widget.repository.updateProfilePassword(
        currentPassword: currentPassword,
        password: newPassword,
        passwordConfirmation: confirmPassword,
      );

      if (!mounted) {
        return;
      }

      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(
            (result['_message'] as String?) ?? 'Password berhasil diperbarui.',
          ),
        ),
      );
      Navigator.of(context).pop();
    } catch (error) {
      if (!mounted) {
        return;
      }

      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(error.toString().replaceFirst('Exception: ', '')),
        ),
      );
    } finally {
      if (mounted) {
        setState(() {
          _submitting = false;
        });
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    final passwordStrength = _PasswordStrength.evaluate(
      _newPasswordController.text,
    );
    final bottomInset = MediaQuery.paddingOf(context).bottom + 24;

    return Scaffold(
      backgroundColor: _PasswordPagePalette.background,
      body: SafeArea(
        bottom: false,
        child: Column(
          children: [
            TeacherOverlayPageHeader(
              title: 'Ubah Password',
              onBack: () => Navigator.of(context).pop(),
            ),
            Expanded(
              child: ListView(
                padding: EdgeInsets.zero,
                children: [
                  Transform.translate(
                    offset: const Offset(0, -8),
                    child: Container(
                padding: EdgeInsets.fromLTRB(14, 22, 14, bottomInset),
                decoration: const BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.only(
                    topLeft: Radius.circular(18),
                    topRight: Radius.circular(18),
                  ),
                ),
                child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const _PasswordSectionHeading(
                    title: 'Keamanan Akun',
                  ),
                  const SizedBox(height: 6),
                  const Text(
                    'Gunakan password baru yang kuat untuk menjaga keamanan akun.',
                    style: TextStyle(
                      color: _PasswordPagePalette.textSecondary,
                      fontSize: 11.5,
                      height: 1.45,
                    ),
                  ),
                  const SizedBox(height: 12),
                  TextField(
                    controller: _currentPasswordController,
                    obscureText: _obscureCurrent,
                    decoration:
                        _passwordInputDecoration('Password Lama').copyWith(
                      suffixIcon: IconButton(
                        onPressed: () {
                          setState(() {
                            _obscureCurrent = !_obscureCurrent;
                          });
                        },
                        icon: Icon(
                          _obscureCurrent
                              ? Icons.visibility_off_rounded
                              : Icons.visibility_rounded,
                          color: _PasswordPagePalette.textSecondary,
                        ),
                      ),
                    ),
                  ),
                  const SizedBox(height: 10),
                  TextField(
                    controller: _newPasswordController,
                    obscureText: _obscureNew,
                    onChanged: (_) {
                      setState(() {});
                    },
                    decoration:
                        _passwordInputDecoration('Password Baru').copyWith(
                      suffixIcon: IconButton(
                        onPressed: () {
                          setState(() {
                            _obscureNew = !_obscureNew;
                          });
                        },
                        icon: Icon(
                          _obscureNew
                              ? Icons.visibility_off_rounded
                              : Icons.visibility_rounded,
                          color: _PasswordPagePalette.textSecondary,
                        ),
                      ),
                    ),
                  ),
                  const SizedBox(height: 8),
                  _PasswordStrengthCard(strength: passwordStrength),
                  const SizedBox(height: 10),
                  TextField(
                    controller: _confirmPasswordController,
                    obscureText: _obscureConfirm,
                    decoration: _passwordInputDecoration(
                      'Konfirmasi Password Baru',
                    ).copyWith(
                      suffixIcon: IconButton(
                        onPressed: () {
                          setState(() {
                            _obscureConfirm = !_obscureConfirm;
                          });
                        },
                        icon: Icon(
                          _obscureConfirm
                              ? Icons.visibility_off_rounded
                              : Icons.visibility_rounded,
                          color: _PasswordPagePalette.textSecondary,
                        ),
                      ),
                    ),
                  ),
                  const SizedBox(height: 14),
                  SizedBox(
                    width: double.infinity,
                    child: ElevatedButton(
                      onPressed: _submitting ? null : _submit,
                      style: ElevatedButton.styleFrom(
                        backgroundColor: _PasswordPagePalette.primary,
                        foregroundColor: Colors.white,
                        disabledBackgroundColor: _PasswordPagePalette.border,
                        disabledForegroundColor:
                            _PasswordPagePalette.textSecondary,
                        padding: const EdgeInsets.symmetric(vertical: 14),
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(16),
                        ),
                        elevation: 0,
                      ),
                      child: _submitting
                          ? const SizedBox(
                              width: 18,
                              height: 18,
                              child: CircularProgressIndicator(
                                strokeWidth: 2,
                                color: Colors.white,
                              ),
                            )
                          : const Text(
                              'Simpan Password',
                              style: TextStyle(fontWeight: FontWeight.w800),
                            ),
                    ),
                  ),
                ],
              ),
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

class _PasswordStrengthCard extends StatelessWidget {
  const _PasswordStrengthCard({
    required this.strength,
  });

  final _PasswordStrength strength;

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: _PasswordPagePalette.surface,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: _PasswordPagePalette.border),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            strength.message,
            style: TextStyle(
              color: strength.color,
              fontSize: 12,
              fontWeight: FontWeight.w700,
              height: 1.4,
            ),
          ),
          const SizedBox(height: 8),
          ClipRRect(
            borderRadius: BorderRadius.circular(999),
            child: LinearProgressIndicator(
              minHeight: 7,
              value: strength.value / 100,
              backgroundColor: _PasswordPagePalette.border,
              valueColor: AlwaysStoppedAnimation<Color>(strength.color),
            ),
          ),
        ],
      ),
    );
  }
}

InputDecoration _passwordInputDecoration(String label) {
  return InputDecoration(
    labelText: label,
    filled: true,
    fillColor: _PasswordPagePalette.surface,
    isDense: true,
    contentPadding: const EdgeInsets.symmetric(horizontal: 14, vertical: 14),
    labelStyle: const TextStyle(
      color: _PasswordPagePalette.textSecondary,
      fontSize: 13,
    ),
    border: OutlineInputBorder(
      borderRadius: BorderRadius.circular(16),
      borderSide: const BorderSide(color: _PasswordPagePalette.border),
    ),
    enabledBorder: OutlineInputBorder(
      borderRadius: BorderRadius.circular(16),
      borderSide: const BorderSide(color: _PasswordPagePalette.border),
    ),
    focusedBorder: OutlineInputBorder(
      borderRadius: BorderRadius.circular(16),
      borderSide: const BorderSide(
        color: _PasswordPagePalette.primary,
        width: 1.4,
      ),
    ),
  );
}

class _PasswordStrength {
  const _PasswordStrength({
    required this.value,
    required this.label,
    required this.message,
    required this.color,
    required this.meetsMinimumRequirement,
  });

  final double value;
  final String label;
  final String message;
  final Color color;
  final bool meetsMinimumRequirement;

  static _PasswordStrength evaluate(String password) {
    if (password.isEmpty) {
      return const _PasswordStrength(
        value: 0,
        label: 'Belum Diisi',
        message:
            'Password harus mengandung huruf besar, huruf kecil, angka, dan simbol (@\$!%*?&).',
        color: _PasswordPagePalette.textSecondary,
        meetsMinimumRequirement: false,
      );
    }

    var strength = 0.0;
    final feedback = <String>[];

    if (password.length >= 8) {
      strength += 25;
    } else {
      feedback.add('minimal 8 karakter');
    }

    if (RegExp(r'[a-z]').hasMatch(password)) {
      strength += 25;
    } else {
      feedback.add('huruf kecil');
    }

    if (RegExp(r'[A-Z]').hasMatch(password)) {
      strength += 25;
    } else {
      feedback.add('huruf besar');
    }

    if (RegExp(r'\d').hasMatch(password)) {
      strength += 12.5;
    } else {
      feedback.add('angka');
    }

    if (RegExp(r'[@$!%*?&]').hasMatch(password)) {
      strength += 12.5;
    } else {
      feedback.add('simbol (@\$!%*?&)');
    }

    if (strength < 50) {
      return _PasswordStrength(
        value: strength,
        label: 'Lemah',
        message: 'Lemah: ${feedback.join(', ')}',
        color: _PasswordPagePalette.danger,
        meetsMinimumRequirement: false,
      );
    }

    if (strength < 75) {
      return _PasswordStrength(
        value: strength,
        label: 'Sedang',
        message: 'Sedang: Perlu ${feedback.join(', ')}',
        color: _PasswordPagePalette.warning,
        meetsMinimumRequirement: false,
      );
    }

    if (strength < 100) {
      return _PasswordStrength(
        value: strength,
        label: 'Kuat',
        message: 'Kuat: Perlu ${feedback.join(', ')}',
        color: _PasswordPagePalette.info,
        meetsMinimumRequirement: false,
      );
    }

    return const _PasswordStrength(
      value: 100,
      label: 'Sangat Kuat',
      message: 'Sangat Kuat: Password memenuhi semua kriteria!',
      color: _PasswordPagePalette.success,
      meetsMinimumRequirement: true,
    );
  }
}

class _PasswordSectionHeading extends StatelessWidget {
  const _PasswordSectionHeading({
    required this.title,
  });

  final String title;

  @override
  Widget build(BuildContext context) {
    return Text(
      title,
      style: const TextStyle(
        color: _PasswordPagePalette.textPrimary,
        fontSize: 16,
        fontWeight: FontWeight.w800,
      ),
    );
  }
}
