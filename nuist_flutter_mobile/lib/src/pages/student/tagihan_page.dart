import 'package:flutter/material.dart';

import '../../services/student_mobile_repository.dart';
import '../../theme/app_theme.dart';
import '../../widgets/app/app_empty_state.dart';
import '../../widgets/app/app_section_card.dart';
import 'student_ui.dart';

class StudentBillsPage extends StatefulWidget {
  const StudentBillsPage({
    super.key,
    required this.repository,
    required this.dataRevision,
    required this.onOpenPaymentTab,
  });

  final StudentMobileRepository repository;
  final int dataRevision;
  final VoidCallback onOpenPaymentTab;

  @override
  State<StudentBillsPage> createState() => _StudentBillsPageState();
}

class _StudentBillsPageState extends State<StudentBillsPage> {
  bool _isLoading = true;
  String? _errorMessage;
  Map<String, dynamic> _data = const <String, dynamic>{};

  @override
  void initState() {
    super.initState();
    _load();
  }

  @override
  void didUpdateWidget(covariant StudentBillsPage oldWidget) {
    super.didUpdateWidget(oldWidget);
    if (oldWidget.dataRevision != widget.dataRevision) {
      _load();
    }
  }

  Future<void> _load() async {
    if (!mounted) {
      return;
    }
    setState(() {
      _isLoading = true;
      _errorMessage = null;
    });

    try {
      final result = await widget.repository.getBills();
      if (!mounted) {
        return;
      }
      setState(() {
        _data = result;
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
          _isLoading = false;
        });
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    if (_isLoading) {
      return const Center(child: CircularProgressIndicator());
    }

    if (_errorMessage != null) {
      return Center(
        child: Padding(
          padding: const EdgeInsets.all(24),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              Text(
                _errorMessage!,
                textAlign: TextAlign.center,
              ),
              const SizedBox(height: 12),
              FilledButton(
                onPressed: _load,
                child: const Text('Muat ulang'),
              ),
            ],
          ),
        ),
      );
    }

    final summary = Map<String, dynamic>.from(
      (_data['summary'] as Map?) ?? const <String, dynamic>{},
    );
    final bills = ((_data['items'] as List?) ?? const [])
        .whereType<Map>()
        .map((item) => Map<String, dynamic>.from(item))
        .toList();

    return RefreshIndicator(
      onRefresh: _load,
      child: ListView(
        physics: const AlwaysScrollableScrollPhysics(),
        padding: const EdgeInsets.fromLTRB(18, 18, 18, 132),
        children: [
          const StudentSectionHeading(
            title: 'Tagihan siswa',
            subtitle:
                'Daftar tagihan aktif dan yang sudah lunas untuk akun siswa.',
          ),
          const SizedBox(height: 18),
          AppSectionCard(
            child: Row(
              children: [
                Expanded(
                  child: _TopSummaryItem(
                    label: 'Belum lunas',
                    value: '${summary['unpaid_bills'] ?? 0}',
                    color: const Color(0xFFB42318),
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: _TopSummaryItem(
                    label: 'Sudah lunas',
                    value: '${summary['paid_bills'] ?? 0}',
                    color: const Color(0xFF0B8F6E),
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: _TopSummaryItem(
                    label: 'Sisa nominal',
                    value: formatStudentCurrency(summary['outstanding']),
                    color: const Color(0xFF2563EB),
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(height: 18),
          if (bills.isEmpty)
            const AppSectionCard(
              child: AppEmptyState(
                title: 'Belum ada tagihan',
                message:
                    'Data tagihan akan muncul setelah dibuat oleh admin sekolah.',
                icon: Icons.receipt_long_rounded,
              ),
            )
          else
            ...bills.map(
              (item) => Padding(
                padding: const EdgeInsets.only(bottom: 14),
                child: _BillCard(
                  item: item,
                  onOpenPayment: widget.onOpenPaymentTab,
                ),
              ),
            ),
        ],
      ),
    );
  }
}

class _TopSummaryItem extends StatelessWidget {
  const _TopSummaryItem({
    required this.label,
    required this.value,
    required this.color,
  });

  final String label;
  final String value;
  final Color color;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: color.withOpacity(0.08),
        borderRadius: BorderRadius.circular(18),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            label,
            style: const TextStyle(
              fontSize: 12,
              fontWeight: FontWeight.w600,
              color: AppColors.textMuted,
            ),
          ),
          const SizedBox(height: 8),
          Text(
            value,
            style: TextStyle(
              fontSize: 16,
              fontWeight: FontWeight.w800,
              color: color,
            ),
          ),
        ],
      ),
    );
  }
}

class _BillCard extends StatelessWidget {
  const _BillCard({
    required this.item,
    required this.onOpenPayment,
  });

  final Map<String, dynamic> item;
  final VoidCallback onOpenPayment;

  @override
  Widget build(BuildContext context) {
    final status = item['status'] as String?;
    final color = billStatusColor(status);
    final outstandingAmount = item['outstanding_amount'];
    final canPay = status != 'lunas';

    return AppSectionCard(
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      normalizeStudentText(item['nomor_tagihan']),
                      style: const TextStyle(
                        fontSize: 16,
                        fontWeight: FontWeight.w800,
                        color: AppColors.textMain,
                      ),
                    ),
                    const SizedBox(height: 4),
                    Text(
                      normalizeStudentText(item['jenis_tagihan']),
                      style: const TextStyle(
                        fontSize: 13,
                        color: AppColors.textMuted,
                      ),
                    ),
                  ],
                ),
              ),
              StudentStatusBadge(
                label: normalizeStudentText(item['status_label']),
                color: color,
              ),
            ],
          ),
          const SizedBox(height: 14),
          Row(
            children: [
              Expanded(
                child: StudentInfoRow(
                  label: 'Periode',
                  value: normalizeStudentText(item['periode']),
                  icon: Icons.date_range_rounded,
                ),
              ),
              Expanded(
                child: StudentInfoRow(
                  label: 'Jatuh tempo',
                  value: formatStudentDate(item['jatuh_tempo'] as String?),
                  icon: Icons.event_note_rounded,
                ),
              ),
            ],
          ),
          Row(
            children: [
              Expanded(
                child: StudentInfoRow(
                  label: 'Total tagihan',
                  value: formatStudentCurrency(item['total_tagihan']),
                  icon: Icons.payments_outlined,
                ),
              ),
              Expanded(
                child: StudentInfoRow(
                  label: 'Sisa bayar',
                  value: formatStudentCurrency(outstandingAmount),
                  icon: Icons.account_balance_wallet_outlined,
                ),
              ),
            ],
          ),
          if (canPay) ...[
            const SizedBox(height: 10),
            SizedBox(
              width: double.infinity,
              child: OutlinedButton.icon(
                onPressed: onOpenPayment,
                icon: const Icon(Icons.arrow_forward_rounded),
                label: const Text('Lanjut ke pembayaran'),
              ),
            ),
          ],
        ],
      ),
    );
  }
}
