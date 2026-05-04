import 'dart:convert';

import 'package:flutter/material.dart';

import '../../controllers/session_controller.dart';
import '../../models/session.dart';

class HomePage extends StatelessWidget {
  const HomePage({
    super.key,
    required this.controller,
    required this.session,
  });

  final SessionController controller;
  final Session session;

  @override
  Widget build(BuildContext context) {
    final user = session.user;
    final dashboardJson = controller.dashboardData == null
        ? null
        : const JsonEncoder.withIndent('  ').convert(controller.dashboardData);

    return Scaffold(
      appBar: AppBar(
        title: const Text('Mobile Session'),
        actions: [
          TextButton(
            onPressed: controller.isBusy ? null : controller.logout,
            child: const Text('Logout'),
          ),
        ],
      ),
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(24),
          child: Center(
            child: ConstrainedBox(
              constraints: const BoxConstraints(maxWidth: 720),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Wrap(
                    spacing: 16,
                    runSpacing: 16,
                    children: [
                      _InfoCard(
                        title: 'User',
                        lines: [
                          user.name,
                          user.email,
                          'Role: ${user.role ?? '-'}',
                        ],
                      ),
                      _InfoCard(
                        title: 'Route',
                        lines: [
                          session.mobileRoute ?? 'Tidak ada mobile_route',
                          'Token tersimpan aman di device',
                        ],
                      ),
                    ],
                  ),
                  const SizedBox(height: 24),
                  Row(
                    children: [
                      FilledButton.icon(
                        onPressed:
                            controller.isBusy ? null : controller.loadDashboard,
                        icon: controller.isDashboardLoading
                            ? const SizedBox(
                                width: 16,
                                height: 16,
                                child: CircularProgressIndicator(
                                  strokeWidth: 2,
                                  color: Colors.white,
                                ),
                              )
                            : const Icon(Icons.space_dashboard_outlined),
                        label: const Text('Load Dashboard'),
                      ),
                      const SizedBox(width: 12),
                      OutlinedButton.icon(
                        onPressed:
                            controller.isBusy ? null : controller.refreshMe,
                        icon: const Icon(Icons.refresh),
                        label: const Text('Refresh Profile'),
                      ),
                    ],
                  ),
                  if (controller.errorMessage != null) ...[
                    const SizedBox(height: 16),
                    _HomeErrorBanner(message: controller.errorMessage!),
                  ],
                  const SizedBox(height: 24),
                  Container(
                    width: double.infinity,
                    padding: const EdgeInsets.all(20),
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(24),
                    ),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const Text(
                          'Dashboard Response',
                          style: TextStyle(
                            fontSize: 18,
                            fontWeight: FontWeight.w700,
                            color: Color(0xFF102A43),
                          ),
                        ),
                        const SizedBox(height: 12),
                        SelectableText(
                          dashboardJson ??
                              'Tekan "Load Dashboard" untuk mengetes endpoint GET /api/mobile/dashboard.',
                          style: const TextStyle(
                            height: 1.4,
                            color: Color(0xFF334E68),
                            fontFamily: 'monospace',
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
    );
  }
}

class _HomeErrorBanner extends StatelessWidget {
  const _HomeErrorBanner({
    required this.message,
  });

  final String message;

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: const Color(0xFFFFF1F2),
        borderRadius: BorderRadius.circular(18),
        border: Border.all(color: const Color(0xFFFDA4AF)),
      ),
      child: Text(
        message,
        style: const TextStyle(
          color: Color(0xFF9F1239),
          fontWeight: FontWeight.w600,
        ),
      ),
    );
  }
}

class _InfoCard extends StatelessWidget {
  const _InfoCard({
    required this.title,
    required this.lines,
  });

  final String title;
  final List<String> lines;

  @override
  Widget build(BuildContext context) {
    return Container(
      width: 320,
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(24),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            title,
            style: const TextStyle(
              fontSize: 18,
              fontWeight: FontWeight.w700,
              color: Color(0xFF102A43),
            ),
          ),
          const SizedBox(height: 12),
          for (final line in lines)
            Padding(
              padding: const EdgeInsets.only(bottom: 6),
              child: Text(
                line,
                style: const TextStyle(
                  color: Color(0xFF334E68),
                  fontWeight: FontWeight.w600,
                ),
              ),
            ),
        ],
      ),
    );
  }
}
