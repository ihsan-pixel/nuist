import 'package:flutter/material.dart';
import 'package:flutter/services.dart';

import '../../services/student_mobile_repository.dart';
import '../../theme/app_theme.dart';
import '../../widgets/app/app_empty_state.dart';
import '../../widgets/app/app_section_card.dart';
import 'student_ui.dart';

class StudentPaymentPage extends StatefulWidget {
  const StudentPaymentPage({
    super.key,
    required this.repository,
    required this.dataRevision,
    required this.onDataChanged,
    required this.onOpenHistoryTab,
  });

  final StudentMobileRepository repository;
  final int dataRevision;
  final VoidCallback onDataChanged;
  final VoidCallback onOpenHistoryTab;

  @override
  State<StudentPaymentPage> createState() => _StudentPaymentPageState();
}

class _StudentPaymentPageState extends State<StudentPaymentPage> {
  bool _isLoading = true;
  bool _isGeneratingVa = false;
  String? _errorMessage;
  Map<String, dynamic> _data = const <String, dynamic>{};

  @override
  void initState() {
    super.initState();
    _load();
  }

  @override
  void didUpdateWidget(covariant StudentPaymentPage oldWidget) {
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
      final result = await widget.repository.getPayments();
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

  Future<void> _generateVirtualAccount(int billId) async {
    if (_isGeneratingVa) {
      return;
    }

    setState(() {
      _isGeneratingVa = true;
    });

    try {
      final result =
          await widget.repository.createVirtualAccount(billId: billId);
      if (!mounted) {
        return;
      }
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(
            normalizeStudentText(result['_message'],
                fallback: 'Virtual Account berhasil dibuat.'),
          ),
        ),
      );
      await _load();
      widget.onDataChanged();
    } catch (error) {
      if (!mounted) {
        return;
      }
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(error.toString())),
      );
    } finally {
      if (mounted) {
        setState(() {
          _isGeneratingVa = false;
        });
      }
    }
  }

  Future<void> _copyVaNumber(String value) async {
    await Clipboard.setData(ClipboardData(text: value));
    if (!mounted) {
      return;
    }
    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(content: Text('Nomor VA disalin.')),
    );
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
                child: const Text('Coba lagi'),
              ),
            ],
          ),
        ),
      );
    }

    final summary = Map<String, dynamic>.from(
      (_data['summary'] as Map?) ?? const <String, dynamic>{},
    );
    final activeBill = (_data['active_bill'] as Map?) == null
        ? null
        : Map<String, dynamic>.from(_data['active_bill'] as Map);
    final activePayment = (_data['active_payment'] as Map?) == null
        ? null
        : Map<String, dynamic>.from(_data['active_payment'] as Map);
    final recentPayments = ((_data['recent_payments'] as List?) ?? const [])
        .whereType<Map>()
        .map((item) => Map<String, dynamic>.from(item))
        .toList();
    final bniVaEnabled = _data['bni_va_enabled'] == true;

    return RefreshIndicator(
      onRefresh: _load,
      child: ListView(
        physics: const AlwaysScrollableScrollPhysics(),
        padding: const EdgeInsets.fromLTRB(18, 18, 18, 132),
        children: [
          const StudentSectionHeading(
            title: 'Pembayaran',
            subtitle:
                'Kelola Virtual Account dan lihat instruksi pembayaran tagihan aktif.',
          ),
          const SizedBox(height: 18),
          AppSectionCard(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const StudentSectionHeading(
                  title: 'Status pembayaran',
                  subtitle:
                      'Fokus pada tagihan aktif dan transaksi yang masih menunggu.',
                ),
                const SizedBox(height: 14),
                Wrap(
                  spacing: 12,
                  runSpacing: 12,
                  children: [
                    _PaymentSummaryCard(
                      label: 'Sisa tagihan',
                      value: formatStudentCurrency(summary['outstanding']),
                      color: const Color(0xFFEAF2FF),
                    ),
                    _PaymentSummaryCard(
                      label: 'Pembayaran pending',
                      value: '${summary['pending_payments'] ?? 0}',
                      color: const Color(0xFFFFF1E4),
                    ),
                    _PaymentSummaryCard(
                      label: 'Sudah terbayar',
                      value: formatStudentCurrency(summary['total_paid']),
                      color: const Color(0xFFE8F7EE),
                    ),
                  ],
                ),
              ],
            ),
          ),
          const SizedBox(height: 18),
          AppSectionCard(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const StudentSectionHeading(
                  title: 'Tagihan aktif',
                  subtitle:
                      'Buat atau gunakan nomor VA untuk menyelesaikan pembayaran.',
                ),
                const SizedBox(height: 12),
                if (activeBill == null)
                  const AppEmptyState(
                    title: 'Tidak ada tagihan aktif',
                    message:
                        'Semua tagihan sudah lunas atau belum ada data tagihan aktif.',
                    icon: Icons.task_alt_rounded,
                  )
                else
                  Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Container(
                        width: double.infinity,
                        padding: const EdgeInsets.all(16),
                        decoration: BoxDecoration(
                          color: const Color(0xFFF8FAFC),
                          borderRadius: BorderRadius.circular(20),
                          border: Border.all(color: const Color(0xFFE2E8F0)),
                        ),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Row(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Expanded(
                                  child: Column(
                                    crossAxisAlignment:
                                        CrossAxisAlignment.start,
                                    children: [
                                      Text(
                                        normalizeStudentText(
                                            activeBill['nomor_tagihan']),
                                        style: const TextStyle(
                                          fontSize: 16,
                                          fontWeight: FontWeight.w800,
                                          color: AppColors.textMain,
                                        ),
                                      ),
                                      const SizedBox(height: 4),
                                      Text(
                                        normalizeStudentText(
                                            activeBill['jenis_tagihan']),
                                        style: const TextStyle(
                                          fontSize: 13,
                                          color: AppColors.textMuted,
                                        ),
                                      ),
                                    ],
                                  ),
                                ),
                                StudentStatusBadge(
                                  label: normalizeStudentText(
                                      activeBill['status_label']),
                                  color: billStatusColor(
                                      activeBill['status'] as String?),
                                ),
                              ],
                            ),
                            const SizedBox(height: 14),
                            StudentInfoRow(
                              label: 'Periode',
                              value:
                                  normalizeStudentText(activeBill['periode']),
                              icon: Icons.date_range_rounded,
                            ),
                            StudentInfoRow(
                              label: 'Jatuh tempo',
                              value: formatStudentDate(
                                  activeBill['jatuh_tempo'] as String?),
                              icon: Icons.event_note_rounded,
                            ),
                            StudentInfoRow(
                              label: 'Sisa pembayaran',
                              value: formatStudentCurrency(
                                  activeBill['outstanding_amount']),
                              icon: Icons.payments_rounded,
                            ),
                            const SizedBox(height: 12),
                            SizedBox(
                              width: double.infinity,
                              child: FilledButton.icon(
                                onPressed: (!bniVaEnabled ||
                                        activeBill['can_generate_va'] != true ||
                                        _isGeneratingVa)
                                    ? null
                                    : () => _generateVirtualAccount(
                                          (activeBill['id'] as num).toInt(),
                                        ),
                                icon: _isGeneratingVa
                                    ? const SizedBox(
                                        width: 18,
                                        height: 18,
                                        child: CircularProgressIndicator(
                                          strokeWidth: 2,
                                          color: Colors.white,
                                        ),
                                      )
                                    : const Icon(
                                        Icons.account_balance_wallet_rounded),
                                label: Text(
                                  bniVaEnabled
                                      ? 'Buat / pakai Virtual Account'
                                      : 'BNI VA belum aktif',
                                ),
                              ),
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
              ],
            ),
          ),
          const SizedBox(height: 18),
          AppSectionCard(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                StudentSectionHeading(
                  title: 'Virtual Account aktif',
                  subtitle: activePayment == null
                      ? 'Setelah VA dibuat, nomor dan instruksi pembayarannya akan tampil di sini.'
                      : 'Gunakan nomor VA berikut untuk membayar lewat kanal BNI.',
                  trailing: TextButton(
                    onPressed: widget.onOpenHistoryTab,
                    child: const Text('Riwayat'),
                  ),
                ),
                const SizedBox(height: 12),
                if (activePayment == null)
                  const AppEmptyState(
                    title: 'Belum ada VA aktif',
                    message:
                        'Buat Virtual Account dari tagihan aktif untuk melanjutkan pembayaran.',
                    icon: Icons.account_balance_wallet_outlined,
                  )
                else
                  Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Container(
                        width: double.infinity,
                        padding: const EdgeInsets.all(18),
                        decoration: BoxDecoration(
                          gradient: const LinearGradient(
                            colors: [Color(0xFF0F172A), Color(0xFF1E293B)],
                            begin: Alignment.topLeft,
                            end: Alignment.bottomRight,
                          ),
                          borderRadius: BorderRadius.circular(22),
                        ),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              'Nomor Virtual Account',
                              style: TextStyle(
                                color: Colors.white.withOpacity(0.8),
                                fontSize: 12,
                                fontWeight: FontWeight.w600,
                              ),
                            ),
                            const SizedBox(height: 10),
                            Text(
                              normalizeStudentText(activePayment['va_number']),
                              style: const TextStyle(
                                color: Colors.white,
                                fontSize: 24,
                                fontWeight: FontWeight.w800,
                                letterSpacing: 1.2,
                              ),
                            ),
                            const SizedBox(height: 8),
                            Text(
                              'Berlaku sampai ${formatStudentDateTime(activePayment['va_expired_at'] as String?)}',
                              style: TextStyle(
                                color: Colors.white.withOpacity(0.8),
                                fontSize: 12,
                              ),
                            ),
                          ],
                        ),
                      ),
                      const SizedBox(height: 12),
                      Row(
                        children: [
                          Expanded(
                            child: OutlinedButton.icon(
                              onPressed: () => _copyVaNumber(
                                normalizeStudentText(
                                    activePayment['va_number']),
                              ),
                              icon: const Icon(Icons.copy_rounded),
                              label: const Text('Salin nomor VA'),
                            ),
                          ),
                          const SizedBox(width: 12),
                          StudentStatusBadge(
                            label: paymentStatusLabel(
                              activePayment['status_verifikasi'] as String?,
                            ),
                            color: paymentStatusColor(
                              activePayment['status_verifikasi'] as String?,
                            ),
                          ),
                        ],
                      ),
                      const SizedBox(height: 16),
                      const Text(
                        'Langkah pembayaran',
                        style: TextStyle(
                          fontSize: 15,
                          fontWeight: FontWeight.w800,
                          color: AppColors.textMain,
                        ),
                      ),
                      const SizedBox(height: 8),
                      const _StepText(
                          number: '1',
                          text: 'Salin nomor Virtual Account di atas.'),
                      const _StepText(
                          number: '2',
                          text:
                              'Bayar melalui ATM BNI, mobile banking, atau teller sesuai kebijakan sekolah.'),
                      const _StepText(
                          number: '3',
                          text:
                              'Setelah pembayaran masuk, status akan berubah menjadi terverifikasi.'),
                    ],
                  ),
              ],
            ),
          ),
          if (recentPayments.isNotEmpty) ...[
            const SizedBox(height: 18),
            AppSectionCard(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const StudentSectionHeading(
                    title: 'Transaksi terbaru',
                    subtitle:
                        'Lima transaksi terakhir yang tercatat pada akun siswa.',
                  ),
                  const SizedBox(height: 12),
                  ...recentPayments.map(
                    (item) => Padding(
                      padding: const EdgeInsets.only(bottom: 12),
                      child: _RecentPaymentRow(item: item),
                    ),
                  ),
                ],
              ),
            ),
          ],
        ],
      ),
    );
  }
}

class _PaymentSummaryCard extends StatelessWidget {
  const _PaymentSummaryCard({
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
        color: color,
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
            style: const TextStyle(
              fontSize: 17,
              fontWeight: FontWeight.w800,
              color: AppColors.textMain,
            ),
          ),
        ],
      ),
    );
  }
}

class _RecentPaymentRow extends StatelessWidget {
  const _RecentPaymentRow({
    required this.item,
  });

  final Map<String, dynamic> item;

  @override
  Widget build(BuildContext context) {
    final bill = Map<String, dynamic>.from(
      (item['bill'] as Map?) ?? const <String, dynamic>{},
    );
    final color = paymentStatusColor(item['status_verifikasi'] as String?);

    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(18),
        border: Border.all(color: const Color(0xFFE2E8F0)),
      ),
      child: Row(
        children: [
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  normalizeStudentText(bill['nomor_tagihan']),
                  style: const TextStyle(
                    fontWeight: FontWeight.w800,
                    color: AppColors.textMain,
                  ),
                ),
                const SizedBox(height: 4),
                Text(
                  '${formatStudentCurrency(item['nominal_bayar'])} • ${formatStudentDate(item['tanggal_bayar'] as String?)}',
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
    );
  }
}

class _StepText extends StatelessWidget {
  const _StepText({
    required this.number,
    required this.text,
  });

  final String number;
  final String text;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 10),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            width: 24,
            height: 24,
            decoration: const BoxDecoration(
              color: AppColors.accentSoft,
              shape: BoxShape.circle,
            ),
            alignment: Alignment.center,
            child: Text(
              number,
              style: const TextStyle(
                fontSize: 12,
                fontWeight: FontWeight.w800,
                color: AppColors.accentDeep,
              ),
            ),
          ),
          const SizedBox(width: 10),
          Expanded(
            child: Text(
              text,
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
