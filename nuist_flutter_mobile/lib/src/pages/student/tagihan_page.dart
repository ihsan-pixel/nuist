import 'package:flutter/material.dart';

import '../../services/student_mobile_repository.dart';
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
      return StudentErrorView(
        message: _errorMessage!,
        onRetry: _load,
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
        padding: const EdgeInsets.fromLTRB(16, 16, 16, 128),
        children: [
          StudentPageBanner(
            title: 'Tagihan siswa',
            subtitle:
                'Daftar tagihan aktif dan lunas dengan komposisi yang lebih ringkas.',
            icon: Icons.receipt_long_rounded,
            badges: [
              StudentBannerBadge(
                label: '${summary['unpaid_bills'] ?? 0} belum lunas',
                icon: Icons.warning_amber_rounded,
              ),
              StudentBannerBadge(
                label: '${summary['paid_bills'] ?? 0} sudah lunas',
                icon: Icons.task_alt_rounded,
              ),
            ],
          ),
          const SizedBox(height: 16),
          AppSectionCard(
            padding: const EdgeInsets.all(14),
            child: Wrap(
              spacing: 10,
              runSpacing: 10,
              children: [
                StudentMetricCard(
                  label: 'Belum lunas',
                  value: '${summary['unpaid_bills'] ?? 0}',
                  icon: Icons.error_outline_rounded,
                  tone: const Color(0xFFB42318),
                ),
                StudentMetricCard(
                  label: 'Sudah lunas',
                  value: '${summary['paid_bills'] ?? 0}',
                  icon: Icons.check_circle_rounded,
                  tone: const Color(0xFF0B8F6E),
                ),
                StudentMetricCard(
                  label: 'Sisa nominal',
                  value: formatStudentCurrency(summary['outstanding']),
                  icon: Icons.account_balance_wallet_rounded,
                  tone: const Color(0xFF2563EB),
                ),
              ],
            ),
          ),
          const SizedBox(height: 16),
          if (bills.isEmpty)
            const StudentTicketEmptyState(
              title: 'Belum ada tagihan',
              message:
                  'Data tagihan akan muncul setelah dibuat oleh admin sekolah.',
              icon: Icons.receipt_long_rounded,
              accentColor: Color(0xFF0B8F6E),
              footerLabel: 'BELUM ADA TIKET TAGIHAN',
            )
          else
            ...bills.map(
              (item) => Padding(
                padding: const EdgeInsets.only(bottom: 10),
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
    final canPay = status != 'lunas';

    return StudentTicketCard(
      accentColor: color,
      footer: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          StudentTicketAmount(
            label: 'Sisa pembayaran',
            value: formatStudentCurrency(item['outstanding_amount']),
            color: color,
            icon: Icons.payments_rounded,
          ),
          if (canPay) ...[
            const SizedBox(height: 10),
            SizedBox(
              width: double.infinity,
              child: OutlinedButton.icon(
                onPressed: onOpenPayment,
                icon: const Icon(Icons.arrow_forward_rounded, size: 17),
                label: const Text('Lanjut ke pembayaran'),
              ),
            ),
          ],
        ],
      ),
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
                        fontSize: 13,
                        fontWeight: FontWeight.w800,
                      ),
                    ),
                    const SizedBox(height: 3),
                    Text(
                      normalizeStudentText(item['jenis_tagihan']),
                      style: const TextStyle(
                        fontSize: 11,
                        color: Color(0xFF617565),
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
          const SizedBox(height: 10),
          Row(
            children: [
              Expanded(
                child: StudentTicketMeta(
                  label: 'Periode',
                  value: normalizeStudentText(item['periode']),
                  icon: Icons.date_range_rounded,
                  color: color,
                ),
              ),
              const SizedBox(width: 10),
              Expanded(
                child: StudentTicketMeta(
                  label: 'Jatuh tempo',
                  value: formatStudentDate(item['jatuh_tempo'] as String?),
                  icon: Icons.event_note_rounded,
                  color: color,
                ),
              ),
            ],
          ),
          const SizedBox(height: 10),
          Row(
            children: [
              Expanded(
                child: StudentTicketMeta(
                  label: 'Total tagihan',
                  value: formatStudentCurrency(item['total_tagihan']),
                  icon: Icons.payments_outlined,
                  color: color,
                ),
              ),
              const SizedBox(width: 10),
              Expanded(
                child: StudentTicketMeta(
                  label: 'Sisa',
                  value: formatStudentCurrency(item['outstanding_amount']),
                  icon: Icons.account_balance_wallet_outlined,
                  color: color,
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }
}
