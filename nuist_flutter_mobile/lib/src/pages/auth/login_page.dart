import 'dart:math' as math;

import 'package:flutter/material.dart';

import '../../controllers/session_controller.dart';
import '../../services/auth_repository.dart';
import '../../widgets/auth/status_banner.dart';
import 'forgot_password_page.dart';
import 'student_password_reset_page.dart';

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

class _LoginPageState extends State<LoginPage> with TickerProviderStateMixin {
  static const _pagePadding =
      EdgeInsets.symmetric(horizontal: 18, vertical: 14);

  final _formKey = GlobalKey<FormState>();
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  final _emailFocusNode = FocusNode();
  final _passwordFocusNode = FocusNode();

  bool _obscurePassword = true;
  bool _rememberMe = false;
  bool _loadingRemembered = true;
  bool _hasSubmitted = false;
  String _loginAs = 'tenaga_pendidik';
  String? _lastErrorMessage;

  late final AnimationController _entryController;
  late final AnimationController _errorShakeController;

  @override
  void initState() {
    super.initState();
    _entryController = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 1050),
    );
    _errorShakeController = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 460),
    );
    _emailController.addListener(_handleFieldChanged);
    _passwordController.addListener(_handleFieldChanged);
    widget.controller.addListener(_handleControllerChanged);
    _entryController.forward();
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
    _entryController.dispose();
    _errorShakeController.dispose();
    _emailController.dispose();
    _passwordController.dispose();
    _emailFocusNode.dispose();
    _passwordFocusNode.dispose();
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
        final rememberedRole = remembered['loginAs'] as String?;
        _loginAs = const ['siswa', 'tenaga_pendidik', 'pengurus']
                .contains(rememberedRole)
            ? rememberedRole!
            : 'tenaga_pendidik';
        if (_rememberMe) {
          _emailController.text = (remembered['email'] as String?) ?? '';
          _passwordController.text = (remembered['password'] as String?) ?? '';
        }
        _loadingRemembered = false;
      });
      _requestEmailFocus();
    } catch (_) {
      if (!mounted) {
        return;
      }

      setState(() {
        _loadingRemembered = false;
      });
      _requestEmailFocus();
    }
  }

  void _requestEmailFocus() {
    WidgetsBinding.instance.addPostFrameCallback((_) {
      if (!mounted) {
        return;
      }
      _emailFocusNode.requestFocus();
    });
  }

  void _handleFieldChanged() {
    if (!mounted) {
      return;
    }
    setState(() {});
  }

  void _handleControllerChanged() {
    final message = widget.controller.errorMessage;
    if (message != null && message.isNotEmpty && message != _lastErrorMessage) {
      _lastErrorMessage = message;
      _errorShakeController.forward(from: 0);
    }
  }

  bool get _canSubmit {
    if (_loadingRemembered) {
      return false;
    }

    return _emailController.text.trim().isNotEmpty &&
        _passwordController.text.isNotEmpty &&
        !widget.controller.isLoggingIn &&
        !widget.controller.isPostLoginLoading;
  }

  String? _validateEmail(String? value) {
    final text = value?.trim() ?? '';
    if (text.isEmpty) {
      return _hasSubmitted ? 'Email wajib diisi.' : null;
    }
    if (!text.contains('@') || text.startsWith('@') || text.endsWith('@')) {
      return 'Format email tidak valid.';
    }
    return null;
  }

  String? _validateIdentifier(String? value) {
    if (_loginAs == 'siswa') {
      final nisn = value?.trim() ?? '';
      if (nisn.isEmpty) {
        return _hasSubmitted ? 'NISN wajib diisi.' : null;
      }
      if (!RegExp(r'^\d{6,50}$').hasMatch(nisn)) {
        return 'NISN harus berupa angka.';
      }
      return null;
    }

    return _validateEmail(value);
  }

  void _setLoginAs(String value) {
    if (_loginAs == value) return;
    setState(() {
      _loginAs = value;
      _emailController.clear();
      _hasSubmitted = false;
    });
    _requestEmailFocus();
  }

  String? _validatePassword(String? value) {
    if ((value ?? '').isEmpty) {
      return _hasSubmitted ? 'Password wajib diisi.' : null;
    }
    return null;
  }

  Future<void> _submit() async {
    final form = _formKey.currentState;
    setState(() {
      _hasSubmitted = true;
    });
    if (form == null || !form.validate()) {
      _errorShakeController.forward(from: 0);
      return;
    }

    FocusScope.of(context).unfocus();
    await widget.controller.login(
      identifier: _emailController.text.trim(),
      loginAs: _loginAs,
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

  Future<void> _openForgotPasswordPage() async {
    final message = await Navigator.of(context).push<String>(
      MaterialPageRoute(
        builder: (_) => _loginAs == 'siswa'
            ? StudentPasswordResetPage(authRepository: widget.authRepository)
            : ForgotPasswordPage(authRepository: widget.authRepository),
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
    final textTheme = Theme.of(context).textTheme;

    return Scaffold(
      backgroundColor: _LoginPalette.background,
      body: Stack(
        fit: StackFit.expand,
        children: [
          const _LoginBackdrop(),
          SafeArea(
            child: SingleChildScrollView(
              padding: _pagePadding,
              child: Center(
                child: ConstrainedBox(
                  constraints: const BoxConstraints(maxWidth: 372),
                  child: Column(
                    children: [
                      const SizedBox(height: 2),
                      _buildAnimatedHeader(),
                      const SizedBox(height: 10),
                      ConstrainedBox(
                        constraints: const BoxConstraints(maxWidth: 270),
                        child: _buildAnimatedTitle(
                          child: Text(
                            'Selamat Datang Kembali',
                            textAlign: TextAlign.center,
                            style: textTheme.headlineLarge?.copyWith(
                              fontSize: 23,
                              fontWeight: FontWeight.w800,
                              letterSpacing: -0.35,
                              color: _LoginPalette.textPrimary,
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
                      ConstrainedBox(
                        constraints: const BoxConstraints(maxWidth: 262),
                        child: _buildAnimatedTitle(
                          interval:
                              const Interval(0.18, 0.48, curve: Curves.easeOut),
                          beginY: 0.12,
                          child: Text(
                            'Masuk untuk mengakses seluruh layanan NUIST Mobile.',
                            textAlign: TextAlign.center,
                            style: textTheme.titleMedium?.copyWith(
                              fontSize: 13.5,
                              height: 1.38,
                              fontWeight: FontWeight.w500,
                              color: _LoginPalette.textSecondary,
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
                      _buildAnimatedFormCard(controller),
                      const SizedBox(height: 12),
                      _buildAnimatedVersion(),
                    ],
                  ),
                ),
              ),
            ),
          ),
          if (controller.isPostLoginLoading) const _PostLoginLoadingOverlay(),
        ],
      ),
    );
  }

  Widget _buildAnimatedHeader() {
    final animation = CurvedAnimation(
      parent: _entryController,
      curve: const Interval(0.0, 0.36, curve: Curves.easeOutCubic),
    );
    final slide = Tween<Offset>(
      begin: const Offset(0, -0.08),
      end: Offset.zero,
    ).animate(animation);

    return FadeTransition(
      opacity: animation,
      child: SlideTransition(
        position: slide,
        child: const _LoginBrandCard(),
      ),
    );
  }

  Widget _buildAnimatedTitle({
    required Widget child,
    Interval interval = const Interval(0.08, 0.42, curve: Curves.easeOutCubic),
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

  Widget _buildAnimatedFormCard(SessionController controller) {
    final animation = CurvedAnimation(
      parent: _entryController,
      curve: const Interval(0.22, 0.72, curve: Curves.easeOutCubic),
    );
    final slide = Tween<Offset>(
      begin: const Offset(0, 0.1),
      end: Offset.zero,
    ).animate(animation);

    return AnimatedBuilder(
      animation: _errorShakeController,
      builder: (context, child) {
        final shake = math.sin(_errorShakeController.value * math.pi * 6) *
            (1 - _errorShakeController.value) *
            14;
        return Transform.translate(
          offset: Offset(shake, 0),
          child: child,
        );
      },
      child: FadeTransition(
        opacity: animation,
        child: SlideTransition(
          position: slide,
          child: _LoginFormCard(
            formKey: _formKey,
            emailController: _emailController,
            loginAs: _loginAs,
            passwordController: _passwordController,
            emailFocusNode: _emailFocusNode,
            passwordFocusNode: _passwordFocusNode,
            obscurePassword: _obscurePassword,
            rememberMe: _rememberMe,
            canSubmit: _canSubmit,
            isSubmitting: controller.isLoggingIn,
            errorMessage: controller.errorMessage,
            onTogglePasswordVisibility: () {
              setState(() {
                _obscurePassword = !_obscurePassword;
              });
            },
            onRememberMeChanged: controller.isLoggingIn
                ? null
                : (value) => _setRememberMe(value),
            onForgotPassword:
                controller.isLoggingIn ? null : _openForgotPasswordPage,
            onSubmit: _submit,
            emailValidator: _validateIdentifier,
            onLoginAsChanged: controller.isLoggingIn ? null : _setLoginAs,
            passwordValidator: _validatePassword,
            shouldAutovalidate:
                _hasSubmitted || _emailController.text.isNotEmpty,
          ),
        ),
      ),
    );
  }

  Widget _buildAnimatedVersion() {
    final animation = CurvedAnimation(
      parent: _entryController,
      curve: const Interval(0.58, 0.92, curve: Curves.easeOut),
    );

    return FadeTransition(
      opacity: animation,
      child: const Text(
        'NUIST Mobile v1.0.0',
        textAlign: TextAlign.center,
        style: TextStyle(
          fontSize: 11,
          fontWeight: FontWeight.w500,
          color: _LoginPalette.textSecondary,
        ),
      ),
    );
  }
}

class _LoginFormCard extends StatelessWidget {
  const _LoginFormCard({
    required this.formKey,
    required this.emailController,
    required this.loginAs,
    required this.passwordController,
    required this.emailFocusNode,
    required this.passwordFocusNode,
    required this.obscurePassword,
    required this.rememberMe,
    required this.canSubmit,
    required this.isSubmitting,
    required this.errorMessage,
    required this.onTogglePasswordVisibility,
    required this.onRememberMeChanged,
    required this.onForgotPassword,
    required this.onSubmit,
    required this.emailValidator,
    required this.passwordValidator,
    required this.shouldAutovalidate,
    required this.onLoginAsChanged,
  });

  final GlobalKey<FormState> formKey;
  final TextEditingController emailController;
  final String loginAs;
  final TextEditingController passwordController;
  final FocusNode emailFocusNode;
  final FocusNode passwordFocusNode;
  final bool obscurePassword;
  final bool rememberMe;
  final bool canSubmit;
  final bool isSubmitting;
  final String? errorMessage;
  final VoidCallback onTogglePasswordVisibility;
  final ValueChanged<bool>? onRememberMeChanged;
  final VoidCallback? onForgotPassword;
  final VoidCallback onSubmit;
  final FormFieldValidator<String> emailValidator;
  final FormFieldValidator<String> passwordValidator;
  final bool shouldAutovalidate;
  final ValueChanged<String>? onLoginAsChanged;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.fromLTRB(14, 14, 14, 14),
      decoration: BoxDecoration(
        color: _LoginPalette.card,
        borderRadius: BorderRadius.circular(20),
        border: Border.all(
          color: _LoginPalette.border.withValues(alpha: 0.9),
        ),
        boxShadow: [
          BoxShadow(
            color: _LoginPalette.primaryDark.withValues(alpha: 0.06),
            blurRadius: 22,
            offset: const Offset(0, 12),
          ),
        ],
      ),
      child: Form(
        key: formKey,
        autovalidateMode: shouldAutovalidate
            ? AutovalidateMode.onUserInteraction
            : AutovalidateMode.disabled,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            if (errorMessage != null) ...[
              StatusBanner(
                message: errorMessage!,
                type: StatusBannerType.error,
              ),
              const SizedBox(height: 10),
            ],
            _LoginRoleToggle(
              selectedRole: loginAs,
              enabled: onLoginAsChanged != null,
              onChanged: onLoginAsChanged,
            ),
            const SizedBox(height: 12),
            _InputLabel(loginAs == 'siswa' ? 'NISN' : 'Email'),
            const SizedBox(height: 5),
            TextFormField(
              controller: emailController,
              focusNode: emailFocusNode,
              autofocus: true,
              keyboardType: loginAs == 'siswa'
                  ? TextInputType.number
                  : TextInputType.emailAddress,
              textInputAction: TextInputAction.next,
              enabled: !isSubmitting,
              style: const TextStyle(
                fontSize: 16,
                color: _LoginPalette.textPrimary,
                fontWeight: FontWeight.w500,
              ),
              decoration: _buildInputDecoration(
                hintText: loginAs == 'siswa' ? 'Masukkan NISN Anda' : 'Masukkan email Anda',
                icon: loginAs == 'siswa' ? Icons.numbers_rounded : Icons.mail_outline_rounded,
              ),
              validator: emailValidator,
              onFieldSubmitted: (_) => passwordFocusNode.requestFocus(),
            ),
            const SizedBox(height: 10),
            const _InputLabel('Password'),
            const SizedBox(height: 5),
            TextFormField(
              controller: passwordController,
              focusNode: passwordFocusNode,
              obscureText: obscurePassword,
              enabled: !isSubmitting,
              textInputAction: TextInputAction.done,
              style: const TextStyle(
                fontSize: 16,
                color: _LoginPalette.textPrimary,
                fontWeight: FontWeight.w500,
              ),
              decoration: _buildInputDecoration(
                hintText: 'Masukkan password',
                icon: Icons.lock_outline_rounded,
                suffix: IconButton(
                  onPressed: onTogglePasswordVisibility,
                  icon: Icon(
                    obscurePassword
                        ? Icons.visibility_outlined
                        : Icons.visibility_off_outlined,
                    color: _LoginPalette.textSecondary,
                  ),
                ),
              ),
              validator: passwordValidator,
              onFieldSubmitted: (_) => onSubmit(),
            ),
            const SizedBox(height: 8),
            Row(
              children: [
                InkWell(
                  borderRadius: BorderRadius.circular(10),
                  onTap: onRememberMeChanged == null
                      ? null
                      : () => onRememberMeChanged!(!rememberMe),
                  child: Row(
                    children: [
                      Checkbox(
                        value: rememberMe,
                        onChanged: onRememberMeChanged == null
                            ? null
                            : (value) => onRememberMeChanged!(value ?? false),
                        activeColor: _LoginPalette.primary,
                        side: const BorderSide(color: _LoginPalette.border),
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(6),
                        ),
                      ),
                      const Text(
                        'Ingat Saya',
                        style: TextStyle(
                          color: _LoginPalette.textPrimary,
                          fontSize: 12,
                          fontWeight: FontWeight.w600,
                        ),
                      ),
                    ],
                  ),
                ),
                const Spacer(),
                TextButton(
                  onPressed: onForgotPassword,
                  style: TextButton.styleFrom(
                    foregroundColor: _LoginPalette.primary,
                    padding: const EdgeInsets.symmetric(horizontal: 6),
                  ),
                  child: Text(
                    'Lupa Password?',
                    style: TextStyle(
                      fontSize: 12,
                      fontWeight: FontWeight.w700,
                    ),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 12),
            SizedBox(
              width: double.infinity,
              child: FilledButton(
                onPressed: canSubmit ? onSubmit : null,
                style: ButtonStyle(
                  minimumSize: WidgetStateProperty.all(
                    const Size.fromHeight(48),
                  ),
                  backgroundColor: WidgetStateProperty.resolveWith((states) {
                    if (states.contains(WidgetState.disabled)) {
                      return const Color(0xFFDCE7E3);
                    }
                    if (states.contains(WidgetState.pressed)) {
                      return _LoginPalette.primaryDark;
                    }
                    if (states.contains(WidgetState.hovered)) {
                      return const Color(0xFF00745A);
                    }
                    return _LoginPalette.primary;
                  }),
                  foregroundColor: WidgetStateProperty.all(Colors.white),
                  overlayColor: WidgetStateProperty.all(
                    Colors.white.withValues(alpha: 0.08),
                  ),
                  elevation: WidgetStateProperty.resolveWith((states) {
                    if (states.contains(WidgetState.disabled)) {
                      return 0.0;
                    }
                    if (states.contains(WidgetState.pressed)) {
                      return 1.0;
                    }
                    return 8.0;
                  }),
                  shadowColor: WidgetStateProperty.all(
                    _LoginPalette.primaryDark.withValues(alpha: 0.24),
                  ),
                  shape: WidgetStateProperty.all(
                    RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(18),
                    ),
                  ),
                ),
                child: isSubmitting
                    ? const SizedBox(
                        width: 22,
                        height: 22,
                        child: CircularProgressIndicator(
                          strokeWidth: 2.4,
                          color: Colors.white,
                        ),
                      )
                    : const Text(
                        'Masuk',
                        style: TextStyle(
                          fontSize: 15,
                          fontWeight: FontWeight.w800,
                        ),
                      ),
              ),
            ),
          ],
        ),
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
        color: _LoginPalette.textSecondary,
      ),
      filled: true,
      fillColor: Colors.white,
      prefixIcon: Icon(
        icon,
        color: _LoginPalette.textSecondary,
        size: 20,
      ),
      suffixIcon: suffix,
      contentPadding: const EdgeInsets.symmetric(
        horizontal: 14,
        vertical: 12,
      ),
      enabledBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(13),
        borderSide: const BorderSide(color: _LoginPalette.border),
      ),
      focusedBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(13),
        borderSide: const BorderSide(
          color: _LoginPalette.primary,
          width: 1.3,
        ),
      ),
      errorBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(13),
        borderSide: const BorderSide(
          color: _LoginPalette.error,
        ),
      ),
      focusedErrorBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(13),
        borderSide: const BorderSide(
          color: _LoginPalette.error,
          width: 1.3,
        ),
      ),
    );
  }
}

class _LoginRoleToggle extends StatelessWidget {
  const _LoginRoleToggle({
    required this.selectedRole,
    required this.enabled,
    required this.onChanged,
  });

  final String selectedRole;
  final bool enabled;
  final ValueChanged<String>? onChanged;

  @override
  Widget build(BuildContext context) {
    return Container(
      height: 58,
      padding: const EdgeInsets.all(5),
      decoration: BoxDecoration(
        color: const Color(0xFFF4F4F6),
        borderRadius: BorderRadius.circular(30),
      ),
      child: Stack(
        fit: StackFit.expand,
        children: [
          AnimatedAlign(
            duration: const Duration(milliseconds: 320),
            curve: Curves.easeInOutCubicEmphasized,
            alignment: selectedRole == 'siswa'
                ? Alignment.centerLeft
                : selectedRole == 'pengurus'
                    ? Alignment.center
                    : Alignment.centerRight,
            child: FractionallySizedBox(
              widthFactor: 1 / 3,
              child: Container(
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(25),
                  boxShadow: [
                    BoxShadow(
                      color: Colors.black.withValues(alpha: 0.10),
                      blurRadius: 10,
                      offset: const Offset(0, 3),
                    ),
                  ],
                ),
              ),
            ),
          ),
          Row(
            children: [
              Expanded(
                child: _LoginRoleToggleOption(
                  value: 'siswa',
                  label: 'Siswa',
                  selected: selectedRole == 'siswa',
                  enabled: enabled,
                  onChanged: onChanged,
                ),
              ),
              Expanded(
                child: _LoginRoleToggleOption(
                  value: 'pengurus',
                  label: 'Pendidik',
                  selected: selectedRole == 'tenaga_pendidik',
                  enabled: enabled,
                  onChanged: onChanged,
                ),
              ),
              Expanded(
                child: _LoginRoleToggleOption(
                  value: 'pengurus',
                  label: 'Pengurus',
                  selected: selectedRole == 'pengurus',
                  enabled: enabled,
                  onChanged: onChanged,
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }
}

class _LoginRoleToggleOption extends StatelessWidget {
  const _LoginRoleToggleOption({
    required this.value,
    required this.label,
    required this.selected,
    required this.enabled,
    required this.onChanged,
  });

  final String value;
  final String label;
  final bool selected;
  final bool enabled;
  final ValueChanged<String>? onChanged;

  @override
  Widget build(BuildContext context) {
    return Material(
      color: Colors.transparent,
      child: InkWell(
        onTap: enabled ? () => onChanged?.call(value) : null,
        borderRadius: BorderRadius.circular(25),
        child: AnimatedDefaultTextStyle(
          duration: const Duration(milliseconds: 180),
          curve: Curves.easeOutCubic,
          style: TextStyle(
            color: selected
                ? _LoginPalette.textPrimary
                : _LoginPalette.textSecondary.withValues(alpha: 0.48),
            fontSize: 12.5,
            fontWeight: selected ? FontWeight.w700 : FontWeight.w600,
          ),
          child: Center(
            child: Text(
              label,
              textAlign: TextAlign.center,
              maxLines: 1,
              overflow: TextOverflow.ellipsis,
            ),
          ),
        ),
      ),
    );
  }
}

class _LoginBrandCard extends StatefulWidget {
  const _LoginBrandCard();

  @override
  State<_LoginBrandCard> createState() => _LoginBrandCardState();
}

class _LoginBrandCardState extends State<_LoginBrandCard>
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
                      _LoginPalette.primary.withValues(
                        alpha: 0.14 + (pulse * 0.05),
                      ),
                      _LoginPalette.primary.withValues(alpha: 0.04),
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
                  color: _LoginPalette.primaryDark.withValues(alpha: 0.08),
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

class _LoginBackdrop extends StatelessWidget {
  const _LoginBackdrop();

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
              painter: _OrganicLinePainter(
                color: _LoginPalette.accent.withValues(alpha: 0.08),
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
                painter: _OrganicLinePainter(
                  color: _LoginPalette.accent.withValues(alpha: 0.08),
                ),
              ),
            ),
          ),
        ),
      ],
    );
  }
}

class _OrganicLinePainter extends CustomPainter {
  const _OrganicLinePainter({required this.color});

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
  bool shouldRepaint(covariant _OrganicLinePainter oldDelegate) {
    return oldDelegate.color != color;
  }
}

class _InputLabel extends StatelessWidget {
  const _InputLabel(this.label);

  final String label;

  @override
  Widget build(BuildContext context) {
    return Text(
      label,
      style: const TextStyle(
        fontSize: 12.5,
        fontWeight: FontWeight.w700,
        color: _LoginPalette.textPrimary,
      ),
    );
  }
}

class _PostLoginLoadingOverlay extends StatelessWidget {
  const _PostLoginLoadingOverlay();

  @override
  Widget build(BuildContext context) {
    return ColoredBox(
      color: Colors.white.withValues(alpha: 0.72),
      child: Center(
        child: Container(
          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(16),
            boxShadow: [
              BoxShadow(
                color: _LoginPalette.primaryDark.withValues(alpha: 0.08),
                blurRadius: 16,
                offset: const Offset(0, 10),
              ),
            ],
          ),
          child: const Row(
            mainAxisSize: MainAxisSize.min,
            children: [
              SizedBox(
                width: 20,
                height: 20,
                child: CircularProgressIndicator(
                  strokeWidth: 2.4,
                  color: _LoginPalette.primary,
                ),
              ),
              SizedBox(width: 14),
              Text(
                'Sedang memuat data...',
                style: TextStyle(
                  fontSize: 12.5,
                  fontWeight: FontWeight.w600,
                  color: _LoginPalette.textPrimary,
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

class _LoginPalette {
  static const primary = Color(0xFF00745A);
  static const primaryDark = Color(0xFF00553F);
  static const accent = Color(0xFFF59E0B);
  static const background = Color(0xFFF7F9FC);
  static const card = Color(0xFFFFFFFF);
  static const textPrimary = Color(0xFF172A24);
  static const textSecondary = Color(0xFF172A24);
  static const border = Color(0xFFDCE7E3);
  static const error = Color(0xFFEF4444);

  const _LoginPalette._();
}
