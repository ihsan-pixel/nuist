import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../core/widgets/async_value_section.dart';
import 'izin_provider.dart';

class IzinDetailScreen extends ConsumerWidget {
  const IzinDetailScreen({
    required this.izinId,
    super.key,
  });

  static const String routePath = '/izin/:id';

  final String izinId;

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final asyncDetail = ref.watch(izinDetailProvider(izinId));

    return Scaffold(
      appBar: AppBar(
        title: const Text('Detail Izin'),
      ),
      body: AsyncValueSection(
        value: asyncDetail,
        onRetry: () => ref.invalidate(izinDetailProvider(izinId)),
        data: (item) {
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
                        item.title,
                        style: Theme.of(context).textTheme.headlineSmall?.copyWith(
                              fontWeight: FontWeight.w700,
                            ),
                      ),
                      const SizedBox(height: 8),
                      Text(item.type),
                      const SizedBox(height: 16),
                      _DetailRow(label: 'Status', value: item.status),
                      _DetailRow(label: 'Tanggal', value: item.submittedAt ?? '-'),
                      _DetailRow(label: 'Alasan', value: item.reason ?? '-'),
                    ],
                  ),
                ),
              ),
            ],
          );
        },
      ),
    );
  }
}

class _DetailRow extends StatelessWidget {
  const _DetailRow({
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
        crossAxisAlignment: CrossAxisAlignment.start,
        children: <Widget>[
          SizedBox(
            width: 90,
            child: Text(
              label,
              style: const TextStyle(fontWeight: FontWeight.w600),
            ),
          ),
          Expanded(child: Text(value)),
        ],
      ),
    );
  }
}
