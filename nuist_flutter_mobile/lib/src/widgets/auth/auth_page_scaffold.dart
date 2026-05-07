import 'package:flutter/material.dart';

const _authAppVersionLabel = 'Nuist Mobile v1.0.0+1';

class AuthPageScaffold extends StatelessWidget {
  const AuthPageScaffold({
    super.key,
    required this.title,
    required this.subtitle,
    required this.children,
    this.footer,
    this.backgroundAsset,
  });

  final String title;
  final String subtitle;
  final List<Widget> children;
  final Widget? footer;
  final String? backgroundAsset;

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final screenHeight = MediaQuery.sizeOf(context).height;
    final safePadding = MediaQuery.paddingOf(context);

    return Scaffold(
      body: DecoratedBox(
        decoration: BoxDecoration(
          color: Colors.white,
          image: backgroundAsset == null
              ? null
              : DecorationImage(
                  image: AssetImage(backgroundAsset!),
                  fit: BoxFit.cover,
                  alignment: Alignment.center,
                ),
        ),
        child: SafeArea(
          child: SingleChildScrollView(
            child: Center(
              child: ConstrainedBox(
                constraints: BoxConstraints(
                  maxWidth: 460,
                  minHeight:
                      screenHeight - safePadding.top - safePadding.bottom,
                ),
                child: Container(
                  color: Colors.transparent,
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.stretch,
                    children: [
                      const Stack(
                        clipBehavior: Clip.none,
                        alignment: Alignment.topCenter,
                        children: [
                          _MobileAuthHeader(),
                          Positioned(
                            top: 16,
                            child: _MobileBrandPill(),
                          ),
                        ],
                      ),
                      Padding(
                        padding: const EdgeInsets.fromLTRB(20, 18, 20, 28),
                        child: Column(
                          children: [
                            Text(
                              title,
                              textAlign: TextAlign.center,
                              style: theme.textTheme.headlineLarge,
                            ),
                            const SizedBox(height: 10),
                            Text(
                              subtitle,
                              textAlign: TextAlign.center,
                              style: theme.textTheme.titleMedium,
                            ),
                            const SizedBox(height: 16),
                            ...children,
                            if (footer != null) ...[
                              const SizedBox(height: 16),
                              footer!,
                            ],
                            const SizedBox(height: 18),
                            const Text(
                              _authAppVersionLabel,
                              textAlign: TextAlign.center,
                              style: TextStyle(
                                fontSize: 11,
                                fontWeight: FontWeight.w600,
                                color: Color(0xFFA07B57),
                              ),
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            ),
          ),
        ),
      ),
    );
  }
}

class _MobileAuthHeader extends StatelessWidget {
  const _MobileAuthHeader();

  @override
  Widget build(BuildContext context) {
    return SizedBox(
      height: 132,
      child: Stack(
        children: [
          Container(
            decoration: const BoxDecoration(
              color: Colors.white,
            ),
          ),
          Positioned(
            left: -32,
            right: -32,
            bottom: 22,
            child: Container(
              height: 78,
              decoration: BoxDecoration(
                color: Colors.white.withOpacity(0.45),
                borderRadius: BorderRadius.circular(160),
              ),
            ),
          ),
          Positioned(
            left: -32,
            right: -32,
            bottom: -18,
            child: Container(
              height: 92,
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(160),
              ),
            ),
          ),
        ],
      ),
    );
  }
}

class _MobileBrandPill extends StatelessWidget {
  const _MobileBrandPill();

  @override
  Widget build(BuildContext context) {
    return Container(
      width: 72,
      height: 72,
      padding: const EdgeInsets.all(8),
      decoration: BoxDecoration(
        color: Colors.white.withOpacity(0.92),
        borderRadius: BorderRadius.circular(18),
        boxShadow: const [
          BoxShadow(
            color: Color(0x33C86A12),
            blurRadius: 24,
            offset: Offset(0, 10),
          ),
        ],
      ),
      child: ClipRRect(
        borderRadius: BorderRadius.circular(14),
        child: Image.asset(
          'assets/images/nuist_logo.png',
          fit: BoxFit.cover,
        ),
      ),
    );
  }
}
