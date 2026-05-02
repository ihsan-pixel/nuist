import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../core/config/api_base_url_controller.dart';
import '../../core/config/app_config.dart';
import '../../core/navigation/app_navigation.dart';
import 'auth_controller.dart';

class LoginScreen extends ConsumerStatefulWidget {
  const LoginScreen({super.key});

  static const String routePath = '/login';

  @override
  ConsumerState<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends ConsumerState<LoginScreen> {
  final _apiBaseUrlController = TextEditingController(
    text: AppConfig.baseUrl,
  );
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  final _apiBaseUrlFocusNode = FocusNode();
  final _formKey = GlobalKey<FormState>();

  @override
  void dispose() {
    _apiBaseUrlController.dispose();
    _apiBaseUrlFocusNode.dispose();
    _emailController.dispose();
    _passwordController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final apiBaseUrl = ref.watch(apiBaseUrlProvider);

    if (!_apiBaseUrlFocusNode.hasFocus && _apiBaseUrlController.text != apiBaseUrl) {
      _apiBaseUrlController.text = apiBaseUrl;
    }

    ref.listen<AuthState>(authControllerProvider, (previous, next) {
      if (next.status == SessionStatus.authenticated) {
        AppNavigation.goHome(context);
      }
    });

    final authState = ref.watch(authControllerProvider);

    return Scaffold(
      body: Container(
        decoration: const BoxDecoration(
          gradient: LinearGradient(
            colors: <Color>[
              Color(0xFFF6F8F2),
              Color(0xFFE4F0EB),
              Color(0xFFD8ECEE),
            ],
            begin: Alignment.topLeft,
            end: Alignment.bottomRight,
          ),
        ),
        child: SafeArea(
          child: Center(
            child: SingleChildScrollView(
              padding: const EdgeInsets.all(24),
              child: ConstrainedBox(
                constraints: const BoxConstraints(maxWidth: 480),
                child: Card(
                  child: Padding(
                    padding: const EdgeInsets.all(24),
                    child: Form(
                      key: _formKey,
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: <Widget>[
                          Text(
                            'Nuist Mobile',
                            style: Theme.of(context).textTheme.headlineMedium?.copyWith(
                                  fontWeight: FontWeight.w700,
                                ),
                          ),
                          const SizedBox(height: 8),
                          Text(
                            'Masuk dengan akun Laravel yang sudah aktif.',
                            style: Theme.of(context).textTheme.bodyMedium,
                          ),
                          const SizedBox(height: 24),
                          TextFormField(
                            controller: _emailController,
                            keyboardType: TextInputType.emailAddress,
                            decoration: const InputDecoration(
                              labelText: 'Email',
                              border: OutlineInputBorder(),
                            ),
                            validator: (value) {
                              if (value == null || value.trim().isEmpty) {
                                return 'Email wajib diisi.';
                              }
                              return null;
                            },
                          ),
                          const SizedBox(height: 16),
                          TextFormField(
                            controller: _passwordController,
                            obscureText: true,
                            decoration: const InputDecoration(
                              labelText: 'Password',
                              border: OutlineInputBorder(),
                            ),
                            validator: (value) {
                              if (value == null || value.isEmpty) {
                                return 'Password wajib diisi.';
                              }
                              return null;
                            },
                          ),
                          const SizedBox(height: 16),
                          TextFormField(
                            controller: _apiBaseUrlController,
                            focusNode: _apiBaseUrlFocusNode,
                            keyboardType: TextInputType.url,
                            decoration: const InputDecoration(
                              labelText: 'API Base URL',
                              hintText: 'https://domain-anda.com/',
                              border: OutlineInputBorder(),
                            ),
                            validator: (value) {
                              final normalizedBaseUrl = AppConfig.normalizeBaseUrl(value ?? '');
                              if (normalizedBaseUrl == null) {
                                return 'API Base URL wajib diisi.';
                              }

                              final uri = Uri.tryParse(normalizedBaseUrl);
                              if (uri == null || !uri.hasScheme || uri.host.isEmpty) {
                                return 'Format API Base URL tidak valid.';
                              }

                              return null;
                            },
                          ),
                          const SizedBox(height: 8),
                          Text(
                            'Untuk web localhost, isi dengan domain hosting Laravel Anda. Contoh: https://nuist.id/',
                            style: Theme.of(context).textTheme.bodySmall,
                          ),
                          if (authState.errorMessage != null) ...<Widget>[
                            const SizedBox(height: 12),
                            Text(
                              authState.errorMessage!,
                              style: TextStyle(
                                color: Theme.of(context).colorScheme.error,
                              ),
                            ),
                          ],
                          const SizedBox(height: 24),
                          SizedBox(
                            width: double.infinity,
                            child: FilledButton(
                              onPressed: authState.isSubmitting
                                  ? null
                                  : () async {
                                      if (_formKey.currentState?.validate() ?? false) {
                                        await ref
                                            .read(apiBaseUrlProvider.notifier)
                                            .setBaseUrl(_apiBaseUrlController.text);
                                        if (!mounted) {
                                          return;
                                        }
                                        await ref.read(authControllerProvider.notifier).login(
                                              email: _emailController.text.trim(),
                                              password: _passwordController.text,
                                            );
                                      }
                                    },
                              child: authState.isSubmitting
                                  ? const SizedBox(
                                      width: 18,
                                      height: 18,
                                      child: CircularProgressIndicator(strokeWidth: 2),
                                    )
                                  : const Text('Masuk'),
                            ),
                          ),
                          const SizedBox(height: 12),
                          Text(
                            'Aplikasi Flutter ini mengambil data lewat API Laravel, bukan koneksi database langsung.',
                            style: Theme.of(context).textTheme.bodySmall,
                          ),
                        ],
                      ),
                    ),
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
