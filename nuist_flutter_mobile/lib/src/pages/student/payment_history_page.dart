import 'package:flutter/material.dart';

import '../../theme/app_theme.dart';
import '../../services/student_mobile_repository.dart';
import '../../widgets/app/teacher_page_header.dart';
import 'student_ui.dart';

class StudentPaymentHistoryPage extends StatefulWidget {
  const StudentPaymentHistoryPage({
    super.key,
    required this.repository,
    required this.dataRevision,
    required this.onBackToHome,
  });

  final StudentMobileRepository repository;
  final int dataRevision;
  final VoidCallback onBackToHome;

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
    return Column(
      children: [
        TeacherOverlayPageHeader(
          title: 'Riwayat Pembayaran',
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
    final items = ((_data['items'] as List?) ?? const [])
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
                const Text('Riwayat pembayaran', style: TextStyle(fontSize: 20, fontWeight: FontWeight.w800, color: Color(0xFF172A24))),
                const SizedBox(height: 4),
                const Text('Transaksi yang sudah dibuat dari akun Anda.', style: TextStyle(fontSize: 12, color: Color(0xFF64746E))),
                const SizedBox(height: 16),
                _HistorySummary(
                  total: formatStudentCurrency(summary['total_paid']),
                  verified: '${summary['verified_count'] ?? 0}',
                  pending: '${summary['pending_count'] ?? 0}',
                ),
                const SizedBox(height: 20),
                StudentSectionHeading(title: 'Transaksi', subtitle: '${items.length} transaksi tercatat'),
                const SizedBox(height: 10),
          if (items.isEmpty)
            const StudentTicketEmptyState(
              title: 'Belum ada riwayat',
              message:
                  'Riwayat pembayaran akan tampil setelah transaksi mulai dibuat.',
              icon: Icons.history_rounded,
              accentColor: Color(0xFF00745A),
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
          ),
        ],
      ),
    );
  }
}

class _HistorySummary extends StatelessWidget {
  const _HistorySummary({required this.total, required this.verified, required this.pending});
  final String total;
  final String verified;
  final String pending;

  @override
  Widget build(BuildContext context) => Container(
    width: double.infinity,
    padding: const EdgeInsets.all(16),
    decoration: BoxDecoration(color: const Color(0xFFE5F5F0), borderRadius: BorderRadius.circular(20)),
    child: Row(children: [
      const Icon(Icons.payments_rounded, color: Color(0xFF00745A)),
      const SizedBox(width: 12),
      Expanded(child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
        const Text('Total pembayaran', style: TextStyle(fontSize: 12, color: Color(0xFF64746E))),
        const SizedBox(height: 3),
        Text(total, style: const TextStyle(fontSize: 20, fontWeight: FontWeight.w800, color: Color(0xFF00553F))),
      ])),
      Text('$verified selesai\n$pending menunggu', textAlign: TextAlign.right, style: const TextStyle(fontSize: 11, fontWeight: FontWeight.w700, color: Color(0xFF00745A), height: 1.5)),
    ]),
  );
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
                color: const Color(0xFFF7F9FC),
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
