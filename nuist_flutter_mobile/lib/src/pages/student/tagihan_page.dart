import 'package:flutter/material.dart';

import '../../services/student_mobile_repository.dart';
import '../../widgets/app/app_section_card.dart';
import '../../widgets/app/teacher_page_header.dart';
import 'student_ui.dart';

class StudentBillsPage extends StatefulWidget {
  const StudentBillsPage({
    super.key,
    required this.repository,
    required this.dataRevision,
    required this.onBackToHome,
    required this.onOpenPaymentTab,
  });

  final StudentMobileRepository repository;
  final int dataRevision;
  final VoidCallback onBackToHome;
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
    return Column(
      children: [
        TeacherOverlayPageHeader(
          title: 'Tagihan',
          onBack: widget.onBackToHome,
        ),
        Expanded(child: _buildContent(context)),
      ],
    );
  }

  Widget _buildContent(BuildContext context) {
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
        padding: const EdgeInsets.only(bottom: 128),
        children: [
          StudentPageContentSurface(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Text(
                  'Tagihan Anda',
                  style: TextStyle(
                    fontSize: 20,
                    fontWeight: FontWeight.w800,
                    color: Color(0xFF172A24),
                  ),
                ),
                const SizedBox(height: 4),
                const Text(
                  'Periksa dan selesaikan tagihan yang masih aktif.',
                  style: TextStyle(
                    fontSize: 12,
                    color: Color(0xFF64746E),
                  ),
                ),
                const SizedBox(height: 16),
                _BillsSummaryCard(summary: summary),
                const SizedBox(height: 20),
                StudentSectionHeading(
                  title: 'Daftar tagihan',
                  subtitle: '${bills.length} tagihan tercatat',
                ),
                const SizedBox(height: 10),
                if (bills.isEmpty)
                  const StudentTicketEmptyState(
                    title: 'Belum ada tagihan',
                    message:
                        'Data tagihan akan muncul setelah dibuat oleh admin sekolah.',
                    icon: Icons.receipt_long_rounded,
                    accentColor: Color(0xFF00745A),
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

    return AppSectionCard(
      padding: const EdgeInsets.all(14),
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
                      normalizeStudentText(item['jenis_tagihan']),
                      style: const TextStyle(
                        fontSize: 15,
                        fontWeight: FontWeight.w800,
                        color: Color(0xFF172A24),
                      ),
                    ),
                    const SizedBox(height: 3),
                    Text(
                      normalizeStudentText(item['nomor_tagihan']),
                      style: const TextStyle(
                        fontSize: 11,
                        color: Color(0xFF64746E),
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
                child: _BillDetail(
                  label: 'Periode',
                  value: normalizeStudentText(item['periode']),
                ),
              ),
              const SizedBox(width: 10),
              Expanded(
                child: _BillDetail(
                  label: 'Jatuh tempo',
                  value: formatStudentDate(item['jatuh_tempo'] as String?),
                ),
              ),
            ],
          ),
          const SizedBox(height: 14),
          Container(
            width: double.infinity,
            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
            decoration: BoxDecoration(
              color: color.withValues(alpha: 0.08),
              borderRadius: BorderRadius.circular(14),
            ),
            child: Row(
              children: [
                const Text(
                  'Sisa pembayaran',
                  style: TextStyle(
                    fontSize: 12,
                    color: Color(0xFF64746E),
                  ),
                ),
                const Spacer(),
                Text(
                  formatStudentCurrency(item['outstanding_amount']),
                  style: TextStyle(
                    fontSize: 14,
                    fontWeight: FontWeight.w800,
                    color: color,
                  ),
                ),
              ],
            ),
          ),
          if (canPay) ...[
            const SizedBox(height: 12),
            SizedBox(
              width: double.infinity,
              child: FilledButton(
                onPressed: onOpenPayment,
                child: const Text('Bayar tagihan'),
              ),
            ),
          ],
        ],
      ),
    );
  }
}

class _BillsSummaryCard extends StatelessWidget {
  const _BillsSummaryCard({required this.summary});

  final Map<String, dynamic> summary;

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: const Color(0xFFE5F5F0),
        borderRadius: BorderRadius.circular(20),
      ),
      child: Row(
        children: [
          const Icon(
            Icons.account_balance_wallet_rounded,
            color: Color(0xFF00745A),
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Text(
                  'Total yang perlu dibayar',
                  style: TextStyle(fontSize: 12, color: Color(0xFF64746E)),
                ),
                const SizedBox(height: 3),
                Text(
                  formatStudentCurrency(summary['outstanding']),
                  style: const TextStyle(
                    fontSize: 20,
                    fontWeight: FontWeight.w800,
                    color: Color(0xFF00553F),
                  ),
                ),
              ],
            ),
          ),
          Text(
            '${summary['unpaid_bills'] ?? 0} aktif',
            style: const TextStyle(
              fontSize: 12,
              fontWeight: FontWeight.w700,
              color: Color(0xFF00745A),
            ),
          ),
        ],
      ),
    );
  }
}

class _BillDetail extends StatelessWidget {
  const _BillDetail({required this.label, required this.value});

  final String label;
  final String value;

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          label,
          style: const TextStyle(fontSize: 11, color: Color(0xFF64746E)),
        ),
        const SizedBox(height: 3),
        Text(
          value,
          maxLines: 1,
          overflow: TextOverflow.ellipsis,
          style: const TextStyle(
            fontSize: 12,
            fontWeight: FontWeight.w700,
            color: Color(0xFF172A24),
          ),
        ),
      ],
    );
  }
}
