import 'dart:math' as math;

import 'package:flutter/material.dart';

import '../../models/madrasah_option.dart';
import '../../services/auth_repository.dart';
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

class _RegisterPageState extends State<RegisterPage>
    with TickerProviderStateMixin {
  static const _pagePadding =
      EdgeInsets.symmetric(horizontal: 18, vertical: 14);

  final _formKey = GlobalKey<FormState>();
  final _nameController = TextEditingController();
  final _emailController = TextEditingController();
  final _jabatanController = TextEditingController();
  final _passwordController = TextEditingController();
  final _passwordConfirmationController = TextEditingController();
  final _nameFocusNode = FocusNode();
  final _emailFocusNode = FocusNode();
  final _jabatanFocusNode = FocusNode();
  final _passwordFocusNode = FocusNode();
  final _passwordConfirmationFocusNode = FocusNode();

  List<MadrasahOption> _madrasahs = const [];
  String? _selectedRole;
  int? _selectedMadrasahId;
  bool _obscurePassword = true;
  bool _obscurePasswordConfirmation = true;
  bool _isLoadingOptions = true;
  bool _isSubmitting = false;
  String? _errorMessage;

  late final AnimationController _entryController;

  @override
  void initState() {
    super.initState();
    _entryController = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 980),
    )..forward();
    _loadMadrasahs();
  }

  @override
  void dispose() {
    _entryController.dispose();
    _nameController.dispose();
    _emailController.dispose();
    _jabatanController.dispose();
    _passwordController.dispose();
    _passwordConfirmationController.dispose();
    _nameFocusNode.dispose();
    _emailFocusNode.dispose();
    _jabatanFocusNode.dispose();
    _passwordFocusNode.dispose();
    _passwordConfirmationFocusNode.dispose();
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
    final textTheme = Theme.of(context).textTheme;

    return Scaffold(
      backgroundColor: _RegisterPalette.background,
      body: Stack(
        fit: StackFit.expand,
        children: [
          const _RegisterBackdrop(),
          SafeArea(
            child: SingleChildScrollView(
              padding: _pagePadding,
              child: Center(
                child: ConstrainedBox(
                  constraints: const BoxConstraints(maxWidth: 376),
                  child: Column(
                    children: [
                      const SizedBox(height: 2),
                      _buildAnimatedHeader(),
                      const SizedBox(height: 10),
                      _buildAnimatedSection(
                        interval: const Interval(
                          0.08,
                          0.4,
                          curve: Curves.easeOutCubic,
                        ),
                        child: ConstrainedBox(
                          constraints: const BoxConstraints(maxWidth: 270),
                          child: Text(
                            'Buat Akun NUIST',
                            textAlign: TextAlign.center,
                            style: textTheme.headlineLarge?.copyWith(
                              fontSize: 23,
                              fontWeight: FontWeight.w800,
                              letterSpacing: -0.35,
                              color: _RegisterPalette.textPrimary,
                              fontFamilyFallback: const [
                                'SF Pro Display',
                                'Inter',
                                'Poppins',
                              ],
                            ),
                          ),
                        ),
                      ),
                      const SizedBox(height: 6),
                      _buildAnimatedSection(
                        interval: const Interval(
                          0.18,
                          0.46,
                          curve: Curves.easeOut,
                        ),
                        beginY: 0.12,
                        child: ConstrainedBox(
                          constraints: const BoxConstraints(maxWidth: 262),
                          child: Text(
                            'Lengkapi data untuk mulai menggunakan layanan NUIST Mobile.',
                            textAlign: TextAlign.center,
                            style: textTheme.titleMedium?.copyWith(
                              fontSize: 13.5,
                              height: 1.38,
                              fontWeight: FontWeight.w500,
                              color: _RegisterPalette.textSecondary,
                              fontFamilyFallback: const [
                                'SF Pro Display',
                                'Inter',
                                'Poppins',
                              ],
                            ),
                          ),
                        ),
                      ),
                      const SizedBox(height: 14),
                      _buildAnimatedSection(
                        interval: const Interval(
                          0.24,
                          0.78,
                          curve: Curves.easeOutCubic,
                        ),
                        beginY: 0.1,
                        child: _buildFormCard(),
                      ),
                      const SizedBox(height: 12),
                      _buildAnimatedSection(
                        interval: const Interval(
                          0.48,
                          0.86,
                          curve: Curves.easeOut,
                        ),
                        child: Row(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            const Text(
                              'Sudah punya akun? ',
                              style: TextStyle(
                                color: _RegisterPalette.textSecondary,
                                fontSize: 12,
                                fontWeight: FontWeight.w500,
                              ),
                            ),
                            GestureDetector(
                              onTap: _isSubmitting
                                  ? null
                                  : () => Navigator.of(context).maybePop(),
                              child: Text(
                                'Masuk di sini',
                                style: TextStyle(
                                  color: _isSubmitting
                                      ? _RegisterPalette.textSecondary
                                      : _RegisterPalette.primary,
                                  fontSize: 12,
                                  fontWeight: FontWeight.w700,
                                ),
                              ),
                            ),
                          ],
                        ),
                      ),
                      const SizedBox(height: 12),
                      _buildAnimatedSection(
                        interval: const Interval(
                          0.58,
                          0.94,
                          curve: Curves.easeOut,
                        ),
                        child: const Text(
                          'NUIST Mobile v1.0.0',
                          textAlign: TextAlign.center,
                          style: TextStyle(
                            fontSize: 11,
                            fontWeight: FontWeight.w500,
                            color: _RegisterPalette.textSecondary,
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildFormCard() {
    return Container(
      padding: const EdgeInsets.fromLTRB(14, 14, 14, 14),
      decoration: BoxDecoration(
        color: _RegisterPalette.card,
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: _RegisterPalette.border.withOpacity(0.9)),
        boxShadow: [
          BoxShadow(
            color: _RegisterPalette.primaryDark.withOpacity(0.06),
            blurRadius: 22,
            offset: const Offset(0, 12),
          ),
        ],
      ),
      child: Form(
        key: _formKey,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            if (_errorMessage != null) ...[
              StatusBanner(
                message: _errorMessage!,
                type: StatusBannerType.error,
              ),
              const SizedBox(height: 10),
            ],
            if (_isLoadingOptions) ...[
              const StatusBanner(
                message: 'Memuat daftar madrasah...',
                type: StatusBannerType.info,
              ),
              const SizedBox(height: 10),
            ],
            const _RegisterInputLabel('Nama Lengkap'),
            const SizedBox(height: 5),
            TextFormField(
              controller: _nameController,
              focusNode: _nameFocusNode,
              autofocus: true,
              enabled: !_isSubmitting,
              textInputAction: TextInputAction.next,
              decoration: _buildInputDecoration(
                hintText: 'Masukkan nama lengkap',
                icon: Icons.person_outline_rounded,
              ),
              validator: (value) {
                if ((value ?? '').trim().isEmpty) {
                  return 'Nama wajib diisi.';
                }
                return null;
              },
              onFieldSubmitted: (_) => _emailFocusNode.requestFocus(),
            ),
            const SizedBox(height: 10),
            const _RegisterInputLabel('Email'),
            const SizedBox(height: 5),
            TextFormField(
              controller: _emailController,
              focusNode: _emailFocusNode,
              enabled: !_isSubmitting,
              keyboardType: TextInputType.emailAddress,
              textInputAction: TextInputAction.next,
              decoration: _buildInputDecoration(
                hintText: 'Masukkan email',
                icon: Icons.mail_outline_rounded,
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
            const SizedBox(height: 10),
            const _RegisterInputLabel('Daftar sebagai'),
            const SizedBox(height: 6),
            Row(
              children: [
                Expanded(
                  child: _RoleOption(
                    label: 'Pengurus',
                    selected: _selectedRole == 'pengurus',
                    onTap: _isSubmitting ? null : () => _setRole('pengurus'),
                  ),
                ),
                const SizedBox(width: 6),
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
              const SizedBox(height: 5),
              const Text(
                'Pilih peran akun terlebih dahulu.',
                style: TextStyle(
                  color: _RegisterPalette.error,
                  fontSize: 11,
                  fontWeight: FontWeight.w500,
                ),
              ),
            ],
            if (_selectedRole == 'pengurus') ...[
              const SizedBox(height: 10),
              const _RegisterInputLabel('Jabatan'),
              const SizedBox(height: 5),
              TextFormField(
                controller: _jabatanController,
                focusNode: _jabatanFocusNode,
                enabled: !_isSubmitting,
                textInputAction: TextInputAction.next,
                decoration: _buildInputDecoration(
                  hintText: 'Masukkan jabatan',
                  icon: Icons.badge_outlined,
                ),
                validator: (value) {
                  if (_selectedRole == 'pengurus' &&
                      (value ?? '').trim().isEmpty) {
                    return 'Jabatan wajib diisi untuk pengurus.';
                  }
                  return null;
                },
                onFieldSubmitted: (_) => _passwordFocusNode.requestFocus(),
              ),
            ],
            if (_selectedRole == 'tenaga_pendidik') ...[
              const SizedBox(height: 10),
              const _RegisterInputLabel('Asal Sekolah'),
              const SizedBox(height: 5),
              DropdownButtonFormField<int>(
                value: _selectedMadrasahId,
                items: _madrasahs
                    .map(
                      (madrasah) => DropdownMenuItem<int>(
                        value: madrasah.id,
                        child: Text(
                          madrasah.name,
                          overflow: TextOverflow.ellipsis,
                        ),
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
                decoration: _buildInputDecoration(
                  hintText: 'Pilih asal sekolah',
                  icon: Icons.school_outlined,
                ),
                validator: (value) {
                  if (_selectedRole == 'tenaga_pendidik' && value == null) {
                    return 'Asal sekolah wajib dipilih.';
                  }
                  return null;
                },
                style: const TextStyle(
                  color: _RegisterPalette.textPrimary,
                  fontSize: 13.5,
                  fontWeight: FontWeight.w500,
                ),
                borderRadius: BorderRadius.circular(14),
              ),
            ],
            const SizedBox(height: 10),
            const _RegisterInputLabel('Password'),
            const SizedBox(height: 5),
            TextFormField(
              controller: _passwordController,
              focusNode: _passwordFocusNode,
              obscureText: _obscurePassword,
              enabled: !_isSubmitting,
              textInputAction: TextInputAction.next,
              decoration: _buildInputDecoration(
                hintText: 'Masukkan password',
                icon: Icons.lock_outline_rounded,
                suffix: IconButton(
                  onPressed: () {
                    setState(() {
                      _obscurePassword = !_obscurePassword;
                    });
                  },
                  icon: Icon(
                    _obscurePassword
                        ? Icons.visibility_outlined
                        : Icons.visibility_off_outlined,
                    color: _RegisterPalette.textSecondary,
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
              onFieldSubmitted: (_) =>
                  _passwordConfirmationFocusNode.requestFocus(),
            ),
            const SizedBox(height: 10),
            const _RegisterInputLabel('Konfirmasi Password'),
            const SizedBox(height: 5),
            TextFormField(
              controller: _passwordConfirmationController,
              focusNode: _passwordConfirmationFocusNode,
              obscureText: _obscurePasswordConfirmation,
              enabled: !_isSubmitting,
              textInputAction: TextInputAction.done,
              decoration: _buildInputDecoration(
                hintText: 'Ulangi password',
                icon: Icons.verified_user_outlined,
                suffix: IconButton(
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
                    color: _RegisterPalette.textSecondary,
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
              onFieldSubmitted: (_) => _submit(),
            ),
            const SizedBox(height: 12),
            SizedBox(
              width: double.infinity,
              child: FilledButton(
                onPressed: _isSubmitting ? null : _submit,
                style: _primaryButtonStyle(),
                child: _isSubmitting
                    ? const SizedBox(
                        width: 22,
                        height: 22,
                        child: CircularProgressIndicator(
                          strokeWidth: 2.4,
                          color: Colors.white,
                        ),
                      )
                    : const Text(
                        'Daftar',
                        style: TextStyle(
                          fontSize: 15,
                          fontWeight: FontWeight.w800,
                        ),
                      ),
              ),
            ),
            const SizedBox(height: 6),
            SizedBox(
              width: double.infinity,
              child: OutlinedButton(
                onPressed: _isSubmitting
                    ? null
                    : () => Navigator.of(context).maybePop(),
                style: _secondaryButtonStyle(),
                child: const Text(
                  'Kembali ke Login',
                  style: TextStyle(
                    fontSize: 14,
                    fontWeight: FontWeight.w700,
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildAnimatedHeader() {
    final animation = CurvedAnimation(
      parent: _entryController,
      curve: const Interval(0.0, 0.34, curve: Curves.easeOutCubic),
    );
    final slide = Tween<Offset>(
      begin: const Offset(0, -0.08),
      end: Offset.zero,
    ).animate(animation);

    return FadeTransition(
      opacity: animation,
      child: SlideTransition(
        position: slide,
        child: const _RegisterBrandCard(),
      ),
    );
  }

  Widget _buildAnimatedSection({
    required Widget child,
    required Interval interval,
    double beginY = 0.08,
  }) {
    final animation = CurvedAnimation(
      parent: _entryController,
      curve: interval,
    );
    final slide = Tween<Offset>(
      begin: Offset(0, beginY),
      end: Offset.zero,
    ).animate(animation);

    return FadeTransition(
      opacity: animation,
      child: SlideTransition(
        position: slide,
        child: child,
      ),
    );
  }

  InputDecoration _buildInputDecoration({
    required String hintText,
    required IconData icon,
    Widget? suffix,
  }) {
    return InputDecoration(
      hintText: hintText,
      hintStyle: const TextStyle(
        fontSize: 13.5,
        color: _RegisterPalette.textSecondary,
      ),
      filled: true,
      fillColor: Colors.white,
      prefixIcon: Icon(
        icon,
        color: _RegisterPalette.textSecondary,
        size: 20,
      ),
      suffixIcon: suffix,
      contentPadding: const EdgeInsets.symmetric(
        horizontal: 14,
        vertical: 12,
      ),
      enabledBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(13),
        borderSide: const BorderSide(color: _RegisterPalette.border),
      ),
      focusedBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(13),
        borderSide: const BorderSide(
          color: _RegisterPalette.primary,
          width: 1.3,
        ),
      ),
      errorBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(13),
        borderSide: const BorderSide(color: _RegisterPalette.error),
      ),
      focusedErrorBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(13),
        borderSide: const BorderSide(
          color: _RegisterPalette.error,
          width: 1.3,
        ),
      ),
    );
  }

  ButtonStyle _primaryButtonStyle() {
    return ButtonStyle(
      minimumSize: MaterialStateProperty.all(const Size.fromHeight(48)),
      backgroundColor: MaterialStateProperty.resolveWith((states) {
        if (states.contains(MaterialState.disabled)) {
          return const Color(0xFFBFD8D0);
        }
        if (states.contains(MaterialState.pressed)) {
          return _RegisterPalette.primaryDark;
        }
        if (states.contains(MaterialState.hovered)) {
          return const Color(0xFF0A8466);
        }
        return _RegisterPalette.primary;
      }),
      foregroundColor: MaterialStateProperty.all(Colors.white),
      overlayColor: MaterialStateProperty.all(Colors.white.withOpacity(0.08)),
      elevation: MaterialStateProperty.resolveWith((states) {
        if (states.contains(MaterialState.disabled)) {
          return 0.0;
        }
        if (states.contains(MaterialState.pressed)) {
          return 1.0;
        }
        return 8.0;
      }),
      shadowColor: MaterialStateProperty.all(
        _RegisterPalette.primaryDark.withOpacity(0.24),
      ),
      shape: MaterialStateProperty.all(
        RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(14),
        ),
      ),
    );
  }

  ButtonStyle _secondaryButtonStyle() {
    return OutlinedButton.styleFrom(
      minimumSize: const Size.fromHeight(46),
      foregroundColor: _RegisterPalette.primary,
      side: const BorderSide(color: _RegisterPalette.primary, width: 1.2),
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(14),
      ),
      textStyle: const TextStyle(
        fontSize: 14,
        fontWeight: FontWeight.w700,
      ),
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
      color: selected
          ? _RegisterPalette.primary
          : _RegisterPalette.card.withOpacity(0.96),
      borderRadius: BorderRadius.circular(13),
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(13),
        child: Container(
          constraints: const BoxConstraints(minHeight: 42),
          padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 7),
          decoration: BoxDecoration(
            borderRadius: BorderRadius.circular(13),
            border: Border.all(
              color:
                  selected ? _RegisterPalette.primary : _RegisterPalette.border,
            ),
          ),
          child: Center(
            child: Text(
              label,
              textAlign: TextAlign.center,
              style: TextStyle(
                color: selected ? Colors.white : _RegisterPalette.textPrimary,
                fontSize: 11.5,
                fontWeight: FontWeight.w700,
              ),
            ),
          ),
        ),
      ),
    );
  }
}

class _RegisterBrandCard extends StatefulWidget {
  const _RegisterBrandCard();

  @override
  State<_RegisterBrandCard> createState() => _RegisterBrandCardState();
}

class _RegisterBrandCardState extends State<_RegisterBrandCard>
    with SingleTickerProviderStateMixin {
  late final AnimationController _pulseController;

  @override
  void initState() {
    super.initState();
    _pulseController = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 2600),
    )..repeat();
  }

  @override
  void dispose() {
    _pulseController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return SizedBox(
      width: 132,
      height: 132,
      child: Stack(
        alignment: Alignment.center,
        children: [
          AnimatedBuilder(
            animation: _pulseController,
            builder: (context, child) {
              final pulse =
                  (math.sin(_pulseController.value * 2 * math.pi) + 1) / 2;
              return Container(
                width: 88 + (pulse * 12),
                height: 88 + (pulse * 12),
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  gradient: RadialGradient(
                    colors: [
                      _RegisterPalette.primary.withOpacity(
                        0.14 + (pulse * 0.05),
                      ),
                      _RegisterPalette.primary.withOpacity(0.04),
                      Colors.transparent,
                    ],
                    stops: const [0, 0.56, 1],
                  ),
                ),
              );
            },
          ),
          Container(
            width: 102,
            height: 102,
            padding: const EdgeInsets.all(15),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(20),
              boxShadow: [
                BoxShadow(
                  color: _RegisterPalette.primaryDark.withOpacity(0.08),
                  blurRadius: 18,
                  offset: const Offset(0, 10),
                ),
              ],
            ),
            child: Image.asset(
              'assets/images/nuist_logo.png',
              fit: BoxFit.contain,
            ),
          ),
        ],
      ),
    );
  }
}

class _RegisterBackdrop extends StatelessWidget {
  const _RegisterBackdrop();

  @override
  Widget build(BuildContext context) {
    return Stack(
      fit: StackFit.expand,
      children: [
        Positioned(
          top: -42,
          left: -48,
          child: SizedBox(
            width: 200,
            height: 200,
            child: CustomPaint(
              painter: _RegisterOrganicLinePainter(
                color: _RegisterPalette.accent.withOpacity(0.08),
              ),
            ),
          ),
        ),
        Positioned(
          right: -52,
          bottom: -56,
          child: Transform.rotate(
            angle: math.pi,
            child: SizedBox(
              width: 216,
              height: 216,
              child: CustomPaint(
                painter: _RegisterOrganicLinePainter(
                  color: _RegisterPalette.accent.withOpacity(0.08),
                ),
              ),
            ),
          ),
        ),
      ],
    );
  }
}

class _RegisterOrganicLinePainter extends CustomPainter {
  const _RegisterOrganicLinePainter({required this.color});

  final Color color;

  @override
  void paint(Canvas canvas, Size size) {
    final paint = Paint()
      ..color = color
      ..style = PaintingStyle.stroke
      ..strokeCap = StrokeCap.round
      ..strokeWidth = 7;

    final firstPath = Path()
      ..moveTo(size.width * 0.06, size.height * 0.5)
      ..quadraticBezierTo(
        size.width * 0.26,
        size.height * 0.18,
        size.width * 0.54,
        size.height * 0.34,
      )
      ..quadraticBezierTo(
        size.width * 0.82,
        size.height * 0.5,
        size.width * 0.95,
        size.height * 0.18,
      );

    final secondPath = Path()
      ..moveTo(size.width * 0.1, size.height * 0.79)
      ..quadraticBezierTo(
        size.width * 0.36,
        size.height * 0.56,
        size.width * 0.57,
        size.height * 0.76,
      )
      ..quadraticBezierTo(
        size.width * 0.84,
        size.height * 0.98,
        size.width * 0.98,
        size.height * 0.69,
      );

    canvas.drawPath(firstPath, paint);
    canvas.drawPath(secondPath, paint..strokeWidth = 5);
  }

  @override
  bool shouldRepaint(covariant _RegisterOrganicLinePainter oldDelegate) {
    return oldDelegate.color != color;
  }
}

class _RegisterInputLabel extends StatelessWidget {
  const _RegisterInputLabel(this.label);

  final String label;

  @override
  Widget build(BuildContext context) {
    return Text(
      label,
      style: const TextStyle(
        fontSize: 12.5,
        fontWeight: FontWeight.w700,
        color: _RegisterPalette.textPrimary,
      ),
    );
  }
}

class _RegisterPalette {
  static const primary = Color(0xFF0B8F6E);
  static const primaryDark = Color(0xFF066C56);
  static const accent = Color(0xFFF5B301);
  static const background = Color(0xFFF7F8FC);
  static const card = Color(0xFFFFFFFF);
  static const textPrimary = Color(0xFF1E293B);
  static const textSecondary = Color(0xFF64748B);
  static const border = Color(0xFFE2E8F0);
  static const error = Color(0xFFEF4444);

  const _RegisterPalette._();
}
