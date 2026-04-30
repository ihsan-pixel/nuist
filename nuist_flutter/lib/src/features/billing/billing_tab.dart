import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:intl/intl.dart';

import '../../core/widgets/async_value_section.dart';
import 'billing_provider.dart';

class BillingTab extends ConsumerWidget {
  const BillingTab({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final asyncBilling = ref.watch(billingProvider);
    final currencyFormat = NumberFormat.decimalPattern('id');

    return AsyncValueSection(
      value: asyncBilling,
      onRetry: () => ref.invalidate(billingProvider),
      emptyTitle: 'Belum ada tagihan',
      emptySubtitle: 'Daftar invoice siswa akan tampil di sini.',
      isEmpty: (data) => data.items.isEmpty,
      data: (billing) {
        return ListView(
          padding: const EdgeInsets.all(16),
          children: <Widget>[
            Card(
              color: const Color(0xFFF1F7F2),
              child: Padding(
                padding: const EdgeInsets.all(20),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: <Widget>[
                    Text(
                      'Total Belum Lunas',
                      style: Theme.of(context).textTheme.labelLarge,
                    ),
                    const SizedBox(height: 10),
                    Text(
                      'Rp ${currencyFormat.format(billing.totalUnpaid)}',
                      style: Theme.of(context).textTheme.headlineSmall?.copyWith(
                            fontWeight: FontWeight.w700,
                          ),
                    ),
                    const SizedBox(height: 8),
                    Text('${billing.items.length} invoice tercatat'),
                  ],
                ),
              ),
            ),
            const SizedBox(height: 16),
            for (final item in billing.items) ...<Widget>[
              Card(
                child: Padding(
                  padding: const EdgeInsets.all(18),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: <Widget>[
                      Text(
                        item.invoiceNumber,
                        style: Theme.of(context).textTheme.titleMedium?.copyWith(
                              fontWeight: FontWeight.w700,
                            ),
                      ),
                      const SizedBox(height: 6),
                      Text(item.billType ?? 'Tagihan'),
                      const SizedBox(height: 14),
                      _MetaRow(label: 'Periode', value: item.period),
                      _MetaRow(label: 'Jatuh Tempo', value: item.dueDate ?? '-'),
                      _MetaRow(label: 'Status', value: item.status),
                      _MetaRow(
                        label: 'Total',
                        value: 'Rp ${currencyFormat.format(item.totalAmount)}',
                      ),
                    ],
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

class _MetaRow extends StatelessWidget {
  const _MetaRow({
    required this.label,
    required this.value,
  });

  final String label;
  final String value;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.only(top: 8),
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
