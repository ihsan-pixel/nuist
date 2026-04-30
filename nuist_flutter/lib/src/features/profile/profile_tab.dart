import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../core/widgets/async_value_section.dart';
import '../auth/auth_controller.dart';
import 'profile_provider.dart';

class ProfileTab extends ConsumerWidget {
  const ProfileTab({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final asyncProfile = ref.watch(profileProvider);
    final authState = ref.watch(authControllerProvider);

    return AsyncValueSection(
      value: asyncProfile,
      onRetry: () => ref.invalidate(profileProvider),
      data: (user) {
        return ListView(
          padding: const EdgeInsets.all(16),
          children: <Widget>[
            Card(
              child: Padding(
                padding: const EdgeInsets.all(20),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: <Widget>[
                    Text(
                      user.name,
                      style: Theme.of(context).textTheme.headlineSmall?.copyWith(
                            fontWeight: FontWeight.w700,
                          ),
                    ),
                    const SizedBox(height: 6),
                    Text(user.email),
                    const SizedBox(height: 16),
                    _ProfileRow(label: 'Role', value: user.role),
                    _ProfileRow(label: 'Nuist ID', value: user.nuistId ?? '-'),
                  ],
                ),
              ),
            ),
            const SizedBox(height: 16),
            FilledButton.tonal(
              onPressed: authState.isSubmitting
                  ? null
                  : () => ref.read(authControllerProvider.notifier).logout(),
              child: Text(authState.isSubmitting ? 'Keluar...' : 'Logout'),
            ),
          ],
        );
      },
    );
  }
}

class _ProfileRow extends StatelessWidget {
  const _ProfileRow({
    required this.label,
    required this.value,
  });

  final String label;
  final String value;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.only(top: 10),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: <Widget>[
          Text(label),
          Text(
            value,
            style: const TextStyle(fontWeight: FontWeight.w600),
          ),
        ],
      ),
    );
  }
}
