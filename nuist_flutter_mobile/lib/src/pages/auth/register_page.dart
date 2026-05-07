import 'package:flutter/material.dart';

import '../../models/madrasah_option.dart';
import '../../services/auth_repository.dart';
import '../../theme/app_theme.dart';
import '../../widgets/auth/auth_action_button.dart';
import '../../widgets/auth/auth_field_label.dart';
import '../../widgets/auth/auth_page_scaffold.dart';
import '../../widgets/auth/status_banner.dart';

class RegisterPage extends StatefulWidget {
  const RegisterPage({
    super.key,
    required this.authRepository,
  });

  final AuthRepository authRepository;

  @override
  State<RegisterPage> createState() => _RegisterPageState();
}

class _RegisterPageState extends State<RegisterPage> {
  final _formKey = GlobalKey<FormState>();
  final _nameController = TextEditingController();
  final _emailController = TextEditingController();
  final _jabatanController = TextEditingController();
  final _passwordController = TextEditingController();
  final _passwordConfirmationController = TextEditingController();

  List<MadrasahOption> _madrasahs = const [];
  String? _selectedRole;
  int? _selectedMadrasahId;
  bool _obscurePassword = true;
  bool _obscurePasswordConfirmation = true;
  bool _isLoadingOptions = true;
  bool _isSubmitting = false;
  String? _errorMessage;

  @override
  void initState() {
    super.initState();
    _loadMadrasahs();
  }

  @override
  void dispose() {
    _nameController.dispose();
    _emailController.dispose();
    _jabatanController.dispose();
    _passwordController.dispose();
    _passwordConfirmationController.dispose();
    super.dispose();
  }

  Future<void> _loadMadrasahs() async {
    setState(() {
      _isLoadingOptions = true;
      _errorMessage = null;
    });

    try {
      final madrasahs = await widget.authRepository.getRegisterOptions();
      if (!mounted) {
        return;
      }

      setState(() {
        _madrasahs = madrasahs;
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
          _isLoadingOptions = false;
        });
      }
    }
  }

  Future<void> _submit() async {
    final form = _formKey.currentState;
    if (form == null || !form.validate()) {
      return;
    }

    if (_selectedRole == null) {
      setState(() {
        _errorMessage = 'Pilih peran akun terlebih dahulu.';
      });
      return;
    }

    setState(() {
      _isSubmitting = true;
      _errorMessage = null;
    });

    try {
      final message = await widget.authRepository.register(
        name: _nameController.text.trim(),
        email: _emailController.text.trim(),
        password: _passwordController.text,
        passwordConfirmation: _passwordConfirmationController.text,
        role: _selectedRole!,
        jabatan:
            _selectedRole == 'pengurus' ? _jabatanController.text.trim() : null,
        asalSekolahId:
            _selectedRole == 'tenaga_pendidik' ? _selectedMadrasahId : null,
      );

      if (!mounted) {
        return;
      }

      Navigator.of(context).pop(message);
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

  void _setRole(String value) {
    setState(() {
      _selectedRole = value;
      if (value == 'pengurus') {
        _selectedMadrasahId = null;
      } else {
        _jabatanController.clear();
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    return AuthPageScaffold(
      backgroundAsset: 'assets/images/login_bg.png',
      title: 'Welcome!',
      subtitle: 'Create your account to get started',
      footer: Row(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          const Text(
            'Sudah punya akun? ',
            style: TextStyle(
              color: AppColors.textMuted,
              fontSize: 12,
            ),
          ),
          GestureDetector(
            onTap: () => Navigator.of(context).maybePop(),
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
        if (_errorMessage != null) ...[
          StatusBanner(
            message: _errorMessage!,
            type: StatusBannerType.error,
          ),
          const SizedBox(height: 12),
        ],
        if (_isLoadingOptions) ...[
          const StatusBanner(
            message: 'Memuat daftar madrasah...',
            type: StatusBannerType.info,
          ),
          const SizedBox(height: 12),
        ],
        Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const AuthFieldLabel('Name'),
              const SizedBox(height: 6),
              TextFormField(
                controller: _nameController,
                textInputAction: TextInputAction.next,
                decoration: const InputDecoration(
                  hintText: 'Masukkan nama',
                ),
                validator: (value) {
                  if ((value ?? '').trim().isEmpty) {
                    return 'Nama wajib diisi.';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 12),
              const AuthFieldLabel('Email'),
              const SizedBox(height: 6),
              TextFormField(
                controller: _emailController,
                keyboardType: TextInputType.emailAddress,
                textInputAction: TextInputAction.next,
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
              const AuthFieldLabel('Daftar sebagai'),
              const SizedBox(height: 8),
              Row(
                children: [
                  Expanded(
                    child: _RoleOption(
                      label: 'Pengurus',
                      selected: _selectedRole == 'pengurus',
                      onTap: _isSubmitting ? null : () => _setRole('pengurus'),
                    ),
                  ),
                  const SizedBox(width: 8),
                  Expanded(
                    child: _RoleOption(
                      label: 'Tenaga Pendidik',
                      selected: _selectedRole == 'tenaga_pendidik',
                      onTap: _isSubmitting
                          ? null
                          : () => _setRole('tenaga_pendidik'),
                    ),
                  ),
                ],
              ),
              if (_selectedRole == null) ...[
                const SizedBox(height: 6),
                const Text(
                  'Pilih peran akun terlebih dahulu.',
                  style: TextStyle(
                    color: AppColors.fieldError,
                    fontSize: 12,
                  ),
                ),
              ],
              if (_selectedRole == 'pengurus') ...[
                const SizedBox(height: 12),
                const AuthFieldLabel('Jabatan'),
                const SizedBox(height: 6),
                TextFormField(
                  controller: _jabatanController,
                  textInputAction: TextInputAction.next,
                  decoration: const InputDecoration(
                    hintText: 'Masukkan jabatan',
                  ),
                  validator: (value) {
                    if (_selectedRole == 'pengurus' &&
                        (value ?? '').trim().isEmpty) {
                      return 'Jabatan wajib diisi untuk pengurus.';
                    }
                    return null;
                  },
                ),
              ],
              if (_selectedRole == 'tenaga_pendidik') ...[
                const SizedBox(height: 12),
                const AuthFieldLabel('Asal Sekolah'),
                const SizedBox(height: 6),
                DropdownButtonFormField<int>(
                  value: _selectedMadrasahId,
                  items: _madrasahs
                      .map(
                        (madrasah) => DropdownMenuItem<int>(
                          value: madrasah.id,
                          child: Text(madrasah.name),
                        ),
                      )
                      .toList(),
                  onChanged: _isSubmitting || _isLoadingOptions
                      ? null
                      : (value) {
                          setState(() {
                            _selectedMadrasahId = value;
                          });
                        },
                  decoration: const InputDecoration(
                    hintText: 'Pilih asal sekolah',
                  ),
                  validator: (value) {
                    if (_selectedRole == 'tenaga_pendidik' && value == null) {
                      return 'Asal sekolah wajib dipilih.';
                    }
                    return null;
                  },
                ),
              ],
              const SizedBox(height: 12),
              const AuthFieldLabel('Password'),
              const SizedBox(height: 6),
              TextFormField(
                controller: _passwordController,
                obscureText: _obscurePassword,
                textInputAction: TextInputAction.next,
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
                  if ((value ?? '').length < 8) {
                    return 'Password minimal 8 karakter.';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 12),
              const AuthFieldLabel('Konfirmasi Password'),
              const SizedBox(height: 6),
              TextFormField(
                controller: _passwordConfirmationController,
                obscureText: _obscurePasswordConfirmation,
                decoration: InputDecoration(
                  hintText: 'Ulangi password',
                  suffixIcon: IconButton(
                    onPressed: () {
                      setState(() {
                        _obscurePasswordConfirmation =
                            !_obscurePasswordConfirmation;
                      });
                    },
                    icon: Icon(
                      _obscurePasswordConfirmation
                          ? Icons.visibility_outlined
                          : Icons.visibility_off_outlined,
                      color: AppColors.accentMain,
                    ),
                  ),
                ),
                validator: (value) {
                  if ((value ?? '').isEmpty) {
                    return 'Konfirmasi password wajib diisi.';
                  }
                  if (value != _passwordController.text) {
                    return 'Konfirmasi password tidak sama.';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 16),
              AuthActionButton(
                label: 'Sign Up',
                filled: true,
                onPressed: _submit,
                isLoading: _isSubmitting,
              ),
              const SizedBox(height: 10),
              AuthActionButton(
                label: 'Login',
                filled: false,
                onPressed: _isSubmitting
                    ? null
                    : () => Navigator.of(context).maybePop(),
              ),
            ],
          ),
        ),
      ],
    );
  }
}

class _RoleOption extends StatelessWidget {
  const _RoleOption({
    required this.label,
    required this.selected,
    required this.onTap,
  });

  final String label;
  final bool selected;
  final VoidCallback? onTap;

  @override
  Widget build(BuildContext context) {
    return Material(
      color: selected ? AppColors.accentMain : AppColors.inputFill,
      borderRadius: BorderRadius.circular(12),
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(12),
        child: Container(
          constraints: const BoxConstraints(minHeight: 42),
          padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 8),
          decoration: BoxDecoration(
            borderRadius: BorderRadius.circular(12),
            border: Border.all(
              color: selected ? AppColors.accentMain : AppColors.inputBorder,
            ),
          ),
          child: Center(
            child: Text(
              label,
              textAlign: TextAlign.center,
              style: TextStyle(
                color: selected ? Colors.white : AppColors.textMain,
                fontSize: 12,
                fontWeight: FontWeight.w700,
              ),
            ),
          ),
        ),
      ),
    );
  }
}
