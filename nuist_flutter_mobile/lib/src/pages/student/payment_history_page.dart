import 'package:flutter/material.dart';

import '../../services/student_mobile_repository.dart';
import '../../theme/app_theme.dart';
import '../../widgets/app/app_empty_state.dart';
import '../../widgets/app/app_section_card.dart';
import 'student_ui.dart';

class StudentPaymentHistoryPage extends StatefulWidget {
  const StudentPaymentHistoryPage({
    super.key,
    required this.repository,
    required this.dataRevision,
  });

  final StudentMobileRepository repository;
  final int dataRevision;

  @override
  State<StudentPaymentHistoryPage> createState() =>
      _StudentPaymentHistoryPageState();
}

class _StudentPaymentHistoryPageState extends State<StudentPaymentHistoryPage> {
  bool _isLoading = true;
  String? _errorMessage;
  Map<String, dynamic> _data = const <String, dynamic>{};

  @override
  void initState() {
    super.initState();
    _load();
  }

  @override
  void didUpdateWidget(covariant StudentPaymentHistoryPage oldWidget) {
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
      final result = await widget.repository.getPaymentHistory();
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
              Text(_errorMessage!, textAlign: TextAlign.center),
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
    final items = ((_data['items'] as List?) ?? const [])
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
            title: 'Riwayat pembayaran',
            subtitle:
                'Semua transaksi pembayaran siswa, termasuk yang masih menunggu verifikasi.',
          ),
          const SizedBox(height: 18),
          AppSectionCard(
            child: Wrap(
              spacing: 12,
              runSpacing: 12,
              children: [
                _HistoryMetric(
                  label: 'Terverifikasi',
                  value: '${summary['verified_count'] ?? 0}',
                  color: const Color(0xFF0B8F6E),
                ),
                _HistoryMetric(
                  label: 'Menunggu',
                  value: '${summary['pending_count'] ?? 0}',
                  color: const Color(0xFF2563EB),
                ),
                _HistoryMetric(
                  label: 'Ditolak',
                  value: '${summary['rejected_count'] ?? 0}',
                  color: const Color(0xFFB42318),
                ),
                _HistoryMetric(
                  label: 'Total masuk',
                  value: formatStudentCurrency(summary['total_paid']),
                  color: const Color(0xFFFF8A1F),
                ),
              ],
            ),
          ),
          const SizedBox(height: 18),
          if (items.isEmpty)
            const AppSectionCard(
              child: AppEmptyState(
                title: 'Belum ada riwayat',
                message:
                    'Riwayat pembayaran akan tampil setelah transaksi mulai dibuat.',
                icon: Icons.history_rounded,
              ),
            )
          else
            ...items.map(
              (item) => Padding(
                padding: const EdgeInsets.only(bottom: 14),
                child: _HistoryCard(item: item),
              ),
            ),
        ],
      ),
    );
  }
}

class _HistoryMetric extends StatelessWidget {
  const _HistoryMetric({
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
      width: 150,
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

class _HistoryCard extends StatelessWidget {
  const _HistoryCard({
    required this.item,
  });

  final Map<String, dynamic> item;

  @override
  Widget build(BuildContext context) {
    final bill = Map<String, dynamic>.from(
      (item['bill'] as Map?) ?? const <String, dynamic>{},
    );
    final color = paymentStatusColor(item['status_verifikasi'] as String?);

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
                      normalizeStudentText(item['nomor_transaksi']),
                      style: const TextStyle(
                        fontSize: 15,
                        fontWeight: FontWeight.w800,
                        color: AppColors.textMain,
                      ),
                    ),
                    const SizedBox(height: 4),
                    Text(
                      normalizeStudentText(bill['nomor_tagihan']),
                      style: const TextStyle(
                        fontSize: 13,
                        color: AppColors.textMuted,
                      ),
                    ),
                  ],
                ),
              ),
              StudentStatusBadge(
                label: paymentStatusLabel(item['status_verifikasi'] as String?),
                color: color,
              ),
            ],
          ),
          const SizedBox(height: 14),
          Row(
            children: [
              Expanded(
                child: StudentInfoRow(
                  label: 'Jenis tagihan',
                  value: normalizeStudentText(bill['jenis_tagihan']),
                  icon: Icons.receipt_rounded,
                ),
              ),
              Expanded(
                child: StudentInfoRow(
                  label: 'Tanggal bayar',
                  value: formatStudentDate(item['tanggal_bayar'] as String?),
                  icon: Icons.event_rounded,
                ),
              ),
            ],
          ),
          Row(
            children: [
              Expanded(
                child: StudentInfoRow(
                  label: 'Nominal',
                  value: formatStudentCurrency(item['nominal_bayar']),
                  icon: Icons.payments_rounded,
                ),
              ),
              Expanded(
                child: StudentInfoRow(
                  label: 'Metode',
                  value: normalizeStudentText(item['metode_pembayaran']),
                  icon: Icons.account_balance_rounded,
                ),
              ),
            ],
          ),
          if (normalizeStudentText(item['keterangan'], fallback: '').isNotEmpty)
            Container(
              width: double.infinity,
              margin: const EdgeInsets.only(top: 6),
              padding: const EdgeInsets.all(14),
              decoration: BoxDecoration(
                color: const Color(0xFFF8FAFC),
                borderRadius: BorderRadius.circular(16),
              ),
              child: Text(
                normalizeStudentText(item['keterangan']),
                style: const TextStyle(
                  fontSize: 13,
                  color: AppColors.textBody,
                  height: 1.4,
                ),
              ),
            ),
        ],
      ),
    );
  }
}
