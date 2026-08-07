import 'dart:math' as math;

import 'package:flutter/material.dart';

class SplashPage extends StatefulWidget {
  const SplashPage({super.key});

  @override
  State<SplashPage> createState() => _SplashPageState();
}

class _SplashPageState extends State<SplashPage> with TickerProviderStateMixin {
  late final AnimationController _introController;
  late final AnimationController _pulseController;
  late final Animation<double> _logoOpacity;
  late final Animation<double> _logoScale;

  @override
  void initState() {
    super.initState();
    _introController = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 900),
    )..forward();
    _pulseController = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 2600),
    )..repeat();
    _logoOpacity = CurvedAnimation(
      parent: _introController,
      curve: const Interval(0.0, 0.72, curve: Curves.easeOut),
    );
    _logoScale = Tween<double>(
      begin: 0.9,
      end: 1,
    ).animate(
      CurvedAnimation(
        parent: _introController,
        curve: Curves.easeOutBack,
      ),
    );
  }

  @override
  void dispose() {
    _introController.dispose();
    _pulseController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final size = MediaQuery.sizeOf(context);
    final titleStyle = Theme.of(context).textTheme.headlineLarge?.copyWith(
      fontSize: size.width < 380 ? 30 : 34,
      fontWeight: FontWeight.w800,
      letterSpacing: -1,
      color: _SplashPalette.textMain,
      fontFamilyFallback: const ['SF Pro Display', 'Inter', 'Poppins'],
    );
    final subtitleStyle = Theme.of(context).textTheme.titleMedium?.copyWith(
      fontSize: size.width < 380 ? 13 : 14,
      height: 1.55,
      fontWeight: FontWeight.w500,
      color: _SplashPalette.textMuted,
      fontFamilyFallback: const ['SF Pro Display', 'Inter', 'Poppins'],
    );
    final loadingStyle = Theme.of(context).textTheme.bodyMedium?.copyWith(
      fontSize: 14,
      fontWeight: FontWeight.w400,
      color: _SplashPalette.textMuted,
      fontFamilyFallback: const ['SF Pro Display', 'Inter', 'Poppins'],
    );

    return Scaffold(
      backgroundColor: _SplashPalette.background,
      body: Stack(
        fit: StackFit.expand,
        children: [
          const _SplashBackdrop(),
          SafeArea(
            child: Padding(
              padding: const EdgeInsets.symmetric(horizontal: 28, vertical: 32),
              child: Column(
                children: [
                  const Spacer(flex: 3),
                  FadeTransition(
                    opacity: _logoOpacity,
                    child: ScaleTransition(
                      scale: _logoScale,
                      child: _SplashHero(
                        pulseController: _pulseController,
                      ),
                    ),
                  ),
                  const SizedBox(height: 28),
                  ConstrainedBox(
                    constraints: const BoxConstraints(maxWidth: 320),
                    child: Column(
                      children: [
                        Text(
                          'NUIST Mobile',
                          textAlign: TextAlign.center,
                          style: titleStyle,
                        ),
                        const SizedBox(height: 10),
                        Text(
                          'Sistem Informasi Terintegrasi untuk Sekolah dan Madrasah',
                          textAlign: TextAlign.center,
                          style: subtitleStyle,
                        ),
                      ],
                    ),
                  ),
                  const Spacer(flex: 2),
                  FadeTransition(
                    opacity: _logoOpacity,
                    child: Column(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        _AnimatedDotsIndicator(controller: _pulseController),
                        const SizedBox(height: 12),
                        Text(
                          'Mempersiapkan aplikasi...',
                          style: loadingStyle,
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }
}

class SplashGate extends StatefulWidget {
  const SplashGate({
    super.key,
    required this.isReady,
    required this.child,
  });

  final bool isReady;
  final Widget child;

  @override
  State<SplashGate> createState() => _SplashGateState();
}

class _SplashGateState extends State<SplashGate> {
  static const _minimumSplashDuration = Duration(milliseconds: 2400);

  bool _minimumElapsed = false;

  @override
  void initState() {
    super.initState();
    Future<void>.delayed(_minimumSplashDuration, () {
      if (!mounted) {
        return;
      }
      setState(() {
        _minimumElapsed = true;
      });
    });
  }

  @override
  Widget build(BuildContext context) {
    final shouldShowContent = _minimumElapsed && widget.isReady;
    final content = shouldShowContent
        ? KeyedSubtree(
            key: ValueKey<String>(widget.child.runtimeType.toString()),
            child: widget.child,
          )
        : const KeyedSubtree(
            key: ValueKey<String>('nuist-splash'),
            child: SplashPage(),
          );

    return AnimatedSwitcher(
      duration: const Duration(milliseconds: 550),
      switchInCurve: Curves.easeOut,
      switchOutCurve: Curves.easeIn,
      transitionBuilder: (child, animation) {
        return FadeTransition(
          opacity: animation,
          child: child,
        );
      },
      child: content,
    );
  }
}

class _SplashHero extends StatelessWidget {
  const _SplashHero({required this.pulseController});

  final AnimationController pulseController;

  @override
  Widget build(BuildContext context) {
    final size = MediaQuery.sizeOf(context);
    final logoSize = size.width < 380 ? 94.0 : 106.0;

    return SizedBox(
      width: 220,
      height: 220,
      child: Stack(
        alignment: Alignment.center,
        children: [
          AnimatedBuilder(
            animation: pulseController,
            builder: (context, child) {
              final t = pulseController.value;
              final pulse = 0.5 + (math.sin(t * 2 * math.pi) + 1) * 0.5;
              final glowSize = 146 + (pulse * 22);
              return Container(
                width: glowSize,
                height: glowSize,
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  gradient: RadialGradient(
                    colors: [
                      _SplashPalette.primary.withOpacity(0.18 + (pulse * 0.06)),
                      _SplashPalette.primary.withOpacity(0.06),
                      Colors.transparent,
                    ],
                    stops: const [0.0, 0.55, 1.0],
                  ),
                ),
              );
            },
          ),
          Container(
            width: 160,
            height: 160,
            padding: const EdgeInsets.all(26),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(28),
              boxShadow: [
                BoxShadow(
                  color: _SplashPalette.primaryDark.withOpacity(0.08),
                  blurRadius: 34,
                  offset: const Offset(0, 18),
                ),
                BoxShadow(
                  color: Colors.white.withOpacity(0.7),
                  blurRadius: 18,
                  offset: const Offset(0, -4),
                ),
              ],
            ),
            child: Image.asset(
              'assets/images/nuist_logo.png',
              width: logoSize,
              height: logoSize,
              fit: BoxFit.contain,
            ),
          ),
        ],
      ),
    );
  }
}

class _AnimatedDotsIndicator extends StatelessWidget {
  const _AnimatedDotsIndicator({required this.controller});

  final AnimationController controller;

  @override
  Widget build(BuildContext context) {
    return AnimatedBuilder(
      animation: controller,
      builder: (context, _) {
        return Row(
          mainAxisAlignment: MainAxisAlignment.center,
          children: List.generate(3, (index) {
            final phase = (controller.value + (index * 0.18)) % 1;
            final wave = math.sin(phase * 2 * math.pi);
            final active = (wave + 1) / 2;
            return Container(
              width: 10 + (active * 2),
              height: 10 + (active * 2),
              margin: const EdgeInsets.symmetric(horizontal: 5),
              decoration: BoxDecoration(
                color: Color.lerp(
                  _SplashPalette.primary.withOpacity(0.36),
                  _SplashPalette.primary,
                  active,
                ),
                shape: BoxShape.circle,
              ),
            );
          }),
        );
      },
    );
  }
}

class _SplashBackdrop extends StatelessWidget {
  const _SplashBackdrop();

  @override
  Widget build(BuildContext context) {
    return Stack(
      fit: StackFit.expand,
      children: [
        Positioned(
          top: -36,
          left: -44,
          child: SizedBox(
            width: 190,
            height: 190,
            child: CustomPaint(
              painter: _OrganicLinePainter(
                color: _SplashPalette.accent.withOpacity(0.08),
              ),
            ),
          ),
        ),
        Positioned(
          right: -46,
          bottom: -52,
          child: Transform.rotate(
            angle: math.pi,
            child: SizedBox(
              width: 210,
              height: 210,
              child: CustomPaint(
                painter: _OrganicLinePainter(
                  color: _SplashPalette.accent.withOpacity(0.08),
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
      ..moveTo(size.width * 0.05, size.height * 0.5)
      ..quadraticBezierTo(
        size.width * 0.24,
        size.height * 0.18,
        size.width * 0.52,
        size.height * 0.34,
      )
      ..quadraticBezierTo(
        size.width * 0.8,
        size.height * 0.5,
        size.width * 0.94,
        size.height * 0.16,
      );

    final secondPath = Path()
      ..moveTo(size.width * 0.1, size.height * 0.78)
      ..quadraticBezierTo(
        size.width * 0.36,
        size.height * 0.54,
        size.width * 0.56,
        size.height * 0.74,
      )
      ..quadraticBezierTo(
        size.width * 0.82,
        size.height * 0.98,
        size.width * 0.98,
        size.height * 0.68,
      );

    canvas.drawPath(firstPath, paint);
    canvas.drawPath(secondPath, paint..strokeWidth = 5);
  }

  @override
  bool shouldRepaint(covariant _OrganicLinePainter oldDelegate) {
    return oldDelegate.color != color;
  }
}

class _SplashPalette {
  static const primary = Color(0xFF0B8F6E);
  static const primaryDark = Color(0xFF066C56);
  static const accent = Color(0xFFF5B301);
  static const background = Color(0xFFF7F8FC);
  static const textMain = Color(0xFF132B24);
  static const textMuted = Color(0xFF6E7A86);

  const _SplashPalette._();
}
