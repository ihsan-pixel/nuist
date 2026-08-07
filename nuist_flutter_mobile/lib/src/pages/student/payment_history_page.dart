import 'package:flutter/material.dart';

import '../../theme/app_theme.dart';
import '../../services/student_mobile_repository.dart';
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
      return StudentErrorView(
        message: _errorMessage!,
        onRetry: _load,
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
        padding: const EdgeInsets.fromLTRB(16, 16, 16, 128),
        children: [
          StudentPageBanner(
            title: 'Riwayat pembayaran',
            subtitle:
                'Semua transaksi siswa dengan tata letak yang lebih padat dan mudah discan.',
            icon: Icons.history_rounded,
            badges: [
              StudentBannerBadge(
                label: '${summary['verified_count'] ?? 0} terverifikasi',
                icon: Icons.check_circle_rounded,
              ),
              StudentBannerBadge(
                label: '${summary['pending_count'] ?? 0} menunggu',
                icon: Icons.schedule_rounded,
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
                  label: 'Terverifikasi',
                  value: '${summary['verified_count'] ?? 0}',
                  icon: Icons.task_alt_rounded,
                  tone: const Color(0xFF0B8F6E),
                ),
                StudentMetricCard(
                  label: 'Menunggu',
                  value: '${summary['pending_count'] ?? 0}',
                  icon: Icons.schedule_rounded,
                  tone: const Color(0xFF2563EB),
                ),
                StudentMetricCard(
                  label: 'Total masuk',
                  value: formatStudentCurrency(summary['total_paid']),
                  icon: Icons.payments_rounded,
                  tone: const Color(0xFFD97706),
                ),
              ],
            ),
          ),
          const SizedBox(height: 16),
          if (items.isEmpty)
            const StudentTicketEmptyState(
              title: 'Belum ada riwayat',
              message:
                  'Riwayat pembayaran akan tampil setelah transaksi mulai dibuat.',
              icon: Icons.history_rounded,
              accentColor: Color(0xFF2563EB),
              footerLabel: 'BELUM ADA TIKET PEMBAYARAN',
            )
          else
            ...items.map(
              (item) => Padding(
                padding: const EdgeInsets.only(bottom: 10),
                child: _HistoryCard(item: item),
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
    final note = normalizeStudentText(item['keterangan'], fallback: '');

    return StudentTicketCard(
      accentColor: color,
      footer: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          StudentTicketAmount(
            label: 'Nominal bayar',
            value: formatStudentCurrency(item['nominal_bayar']),
            color: color,
          ),
          if (note.isNotEmpty) ...[
            const SizedBox(height: 10),
            Container(
              width: double.infinity,
              padding: const EdgeInsets.all(11),
              decoration: BoxDecoration(
                color: const Color(0xFFF8FAFC),
                borderRadius: BorderRadius.circular(14),
              ),
              child: Text(
                note,
                style: const TextStyle(
                  fontSize: 11,
                  color: AppColors.textBody,
                  height: 1.4,
                ),
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
                      normalizeStudentText(item['nomor_transaksi']),
                      style: const TextStyle(
                        fontSize: 13,
                        fontWeight: FontWeight.w800,
                        color: AppColors.textMain,
                      ),
                    ),
                    const SizedBox(height: 3),
                    Text(
                      normalizeStudentText(bill['nomor_tagihan']),
                      style: const TextStyle(
                        fontSize: 11,
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
          const SizedBox(height: 10),
          Row(
            children: [
              Expanded(
                child: StudentTicketMeta(
                  label: 'Jenis tagihan',
                  value: normalizeStudentText(bill['jenis_tagihan']),
                  icon: Icons.receipt_rounded,
                  color: color,
                ),
              ),
              const SizedBox(width: 10),
              Expanded(
                child: StudentTicketMeta(
                  label: 'Tanggal bayar',
                  value: formatStudentDate(item['tanggal_bayar'] as String?),
                  icon: Icons.event_rounded,
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
                  label: 'Metode',
                  value: normalizeStudentText(item['metode_pembayaran']),
                  icon: Icons.account_balance_rounded,
                  color: color,
                ),
              ),
              const SizedBox(width: 10),
              Expanded(
                child: StudentTicketMeta(
                  label: 'Status',
                  value: paymentStatusLabel(
                    item['status_verifikasi'] as String?,
                  ),
                  icon: Icons.verified_rounded,
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
