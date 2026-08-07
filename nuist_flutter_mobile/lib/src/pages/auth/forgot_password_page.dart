import 'dart:math' as math;

import 'package:flutter/material.dart';

import '../../services/auth_repository.dart';
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

class _ForgotPasswordPageState extends State<ForgotPasswordPage>
    with TickerProviderStateMixin {
  static const _pagePadding =
      EdgeInsets.symmetric(horizontal: 18, vertical: 14);

  final _formKey = GlobalKey<FormState>();
  final _emailController = TextEditingController();
  final _emailFocusNode = FocusNode();

  bool _isSubmitting = false;
  String? _errorMessage;
  String? _successMessage;

  late final AnimationController _entryController;

  @override
  void initState() {
    super.initState();
    _entryController = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 920),
    )..forward();
  }

  @override
  void dispose() {
    _entryController.dispose();
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
    final textTheme = Theme.of(context).textTheme;

    return Scaffold(
      backgroundColor: _ForgotPalette.background,
      body: Stack(
        fit: StackFit.expand,
        children: [
          const _ForgotBackdrop(),
          SafeArea(
            child: SingleChildScrollView(
              padding: _pagePadding,
              child: Center(
                child: ConstrainedBox(
                  constraints: const BoxConstraints(maxWidth: 372),
                  child: Column(
                    children: [
                      const SizedBox(height: 2),
                      _buildAnimatedSection(
                        interval: const Interval(
                          0.0,
                          0.34,
                          curve: Curves.easeOutCubic,
                        ),
                        beginY: -0.08,
                        child: const _ForgotBrandCard(),
                      ),
                      const SizedBox(height: 10),
                      _buildAnimatedSection(
                        interval: const Interval(
                          0.08,
                          0.42,
                          curve: Curves.easeOutCubic,
                        ),
                        child: ConstrainedBox(
                          constraints: const BoxConstraints(maxWidth: 270),
                          child: Text(
                            'Lupa Password',
                            textAlign: TextAlign.center,
                            style: textTheme.headlineLarge?.copyWith(
                              fontSize: 23,
                              fontWeight: FontWeight.w800,
                              letterSpacing: -0.35,
                              color: _ForgotPalette.textPrimary,
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
                          0.16,
                          0.48,
                          curve: Curves.easeOut,
                        ),
                        beginY: 0.12,
                        child: ConstrainedBox(
                          constraints: const BoxConstraints(maxWidth: 270),
                          child: Text(
                            'Masukkan email akun untuk menerima tautan reset password.',
                            textAlign: TextAlign.center,
                            style: textTheme.titleMedium?.copyWith(
                              fontSize: 13.5,
                              height: 1.38,
                              fontWeight: FontWeight.w500,
                              color: _ForgotPalette.textSecondary,
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
                          0.76,
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
                              'Ingat password Anda? ',
                              style: TextStyle(
                                color: _ForgotPalette.textSecondary,
                                fontSize: 12,
                                fontWeight: FontWeight.w500,
                              ),
                            ),
                            GestureDetector(
                              onTap: _backToLogin,
                              child: const Text(
                                'Masuk di sini',
                                style: TextStyle(
                                  color: _ForgotPalette.primary,
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
                          0.92,
                          curve: Curves.easeOut,
                        ),
                        child: const Text(
                          'NUIST Mobile v1.0.0',
                          textAlign: TextAlign.center,
                          style: TextStyle(
                            fontSize: 11,
                            fontWeight: FontWeight.w500,
                            color: _ForgotPalette.textSecondary,
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
        color: _ForgotPalette.card,
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: _ForgotPalette.border.withOpacity(0.9)),
        boxShadow: [
          BoxShadow(
            color: _ForgotPalette.primaryDark.withOpacity(0.06),
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
            const _ForgotInputLabel('Email'),
            const SizedBox(height: 5),
            TextFormField(
              controller: _emailController,
              focusNode: _emailFocusNode,
              autofocus: true,
              enabled: !_isSubmitting,
              keyboardType: TextInputType.emailAddress,
              textInputAction: TextInputAction.done,
              decoration: _buildInputDecoration(
                hintText: 'Masukkan email akun',
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
                        'Kirim Tautan Reset',
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
                onPressed: _isSubmitting ? null : _backToLogin,
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
  }) {
    return InputDecoration(
      hintText: hintText,
      hintStyle: const TextStyle(
        fontSize: 13.5,
        color: _ForgotPalette.textSecondary,
      ),
      filled: true,
      fillColor: Colors.white,
      prefixIcon: Icon(
        icon,
        color: _ForgotPalette.textSecondary,
        size: 20,
      ),
      contentPadding: const EdgeInsets.symmetric(
        horizontal: 14,
        vertical: 12,
      ),
      enabledBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(13),
        borderSide: const BorderSide(color: _ForgotPalette.border),
      ),
      focusedBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(13),
        borderSide: const BorderSide(
          color: _ForgotPalette.primary,
          width: 1.3,
        ),
      ),
      errorBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(13),
        borderSide: const BorderSide(color: _ForgotPalette.error),
      ),
      focusedErrorBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(13),
        borderSide: const BorderSide(
          color: _ForgotPalette.error,
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
          return _ForgotPalette.primaryDark;
        }
        if (states.contains(MaterialState.hovered)) {
          return const Color(0xFF0A8466);
        }
        return _ForgotPalette.primary;
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
        _ForgotPalette.primaryDark.withOpacity(0.24),
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
      foregroundColor: _ForgotPalette.primary,
      side: const BorderSide(color: _ForgotPalette.primary, width: 1.2),
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

class _ForgotBrandCard extends StatefulWidget {
  const _ForgotBrandCard();

  @override
  State<_ForgotBrandCard> createState() => _ForgotBrandCardState();
}

class _ForgotBrandCardState extends State<_ForgotBrandCard>
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
                      _ForgotPalette.primary.withOpacity(0.14 + (pulse * 0.05)),
                      _ForgotPalette.primary.withOpacity(0.04),
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
                  color: _ForgotPalette.primaryDark.withOpacity(0.08),
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

class _ForgotBackdrop extends StatelessWidget {
  const _ForgotBackdrop();

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
              painter: _ForgotOrganicLinePainter(
                color: _ForgotPalette.accent.withOpacity(0.08),
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
                painter: _ForgotOrganicLinePainter(
                  color: _ForgotPalette.accent.withOpacity(0.08),
                ),
              ),
            ),
          ),
        ),
      ],
    );
  }
}

class _ForgotOrganicLinePainter extends CustomPainter {
  const _ForgotOrganicLinePainter({required this.color});

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
  bool shouldRepaint(covariant _ForgotOrganicLinePainter oldDelegate) {
    return oldDelegate.color != color;
  }
}

class _ForgotInputLabel extends StatelessWidget {
  const _ForgotInputLabel(this.label);

  final String label;

  @override
  Widget build(BuildContext context) {
    return Text(
      label,
      style: const TextStyle(
        fontSize: 12.5,
        fontWeight: FontWeight.w700,
        color: _ForgotPalette.textPrimary,
      ),
    );
  }
}

class _ForgotPalette {
  static const primary = Color(0xFF0B8F6E);
  static const primaryDark = Color(0xFF066C56);
  static const accent = Color(0xFFF5B301);
  static const background = Color(0xFFF7F8FC);
  static const card = Color(0xFFFFFFFF);
  static const textPrimary = Color(0xFF1E293B);
  static const textSecondary = Color(0xFF64748B);
  static const border = Color(0xFFE2E8F0);
  static const error = Color(0xFFEF4444);

  const _ForgotPalette._();
}
