import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';

import '../../core/widgets/async_value_section.dart';
import 'izin_provider.dart';

class IzinTab extends ConsumerWidget {
  const IzinTab({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final asyncIzinList = ref.watch(izinListProvider);

    return AsyncValueSection(
      value: asyncIzinList,
      onRetry: () => ref.invalidate(izinListProvider),
      emptyTitle: 'Belum ada izin',
      emptySubtitle: 'Riwayat izin akan muncul di sini.',
      isEmpty: (items) => items.isEmpty,
      data: (items) {
        return ListView(
          padding: const EdgeInsets.all(16),
          children: <Widget>[
            for (final item in items) ...<Widget>[
              Card(
                child: InkWell(
                  borderRadius: BorderRadius.circular(20),
                  onTap: () => context.go('/izin/${item.id}'),
                  child: Padding(
                    padding: const EdgeInsets.all(18),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: <Widget>[
                        Text(
                          item.title,
                          style: Theme.of(context).textTheme.titleMedium?.copyWith(
                                fontWeight: FontWeight.w700,
                              ),
                        ),
                        const SizedBox(height: 6),
                        Text(item.type),
                        const SizedBox(height: 14),
                        Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: <Widget>[
                            Text(item.submittedAt ?? '-'),
                            Container(
                              padding: const EdgeInsets.symmetric(
                                horizontal: 12,
                                vertical: 6,
                              ),
                              decoration: BoxDecoration(
                                color: const Color(0xFFE6F3EA),
                                borderRadius: BorderRadius.circular(999),
                              ),
                              child: Text(item.status),
                            ),
                          ],
                        ),
                      ],
                    ),
                  ),
                ),
              ),
              const SizedBox(height: 12),
            ],
          ],
        );
      },
    );
  }
}
