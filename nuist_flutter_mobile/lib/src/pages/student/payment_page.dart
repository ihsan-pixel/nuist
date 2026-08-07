import 'package:flutter/material.dart';
import 'package:flutter/services.dart';

import '../../services/student_mobile_repository.dart';
import '../../theme/app_theme.dart';
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
            normalizeStudentText(
              result['_message'],
              fallback: 'Virtual Account berhasil dibuat.',
            ),
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
      return StudentErrorView(
        message: _errorMessage!,
        onRetry: _load,
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
        padding: const EdgeInsets.fromLTRB(16, 16, 16, 128),
        children: [
          StudentPageBanner(
            title: 'Pembayaran',
            subtitle:
                'Kelola tagihan aktif dan Virtual Account dengan layout yang lebih efisien.',
            icon: Icons.account_balance_wallet_rounded,
            badges: [
              StudentBannerBadge(
                label: '${summary['pending_payments'] ?? 0} pembayaran pending',
                icon: Icons.schedule_rounded,
              ),
              StudentBannerBadge(
                label: formatStudentCurrency(summary['outstanding']),
                icon: Icons.payments_rounded,
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
                  label: 'Sisa tagihan',
                  value: formatStudentCurrency(summary['outstanding']),
                  icon: Icons.account_balance_wallet_rounded,
                  tone: const Color(0xFF2563EB),
                ),
                StudentMetricCard(
                  label: 'Pending',
                  value: '${summary['pending_payments'] ?? 0}',
                  icon: Icons.schedule_rounded,
                  tone: const Color(0xFFD97706),
                ),
                StudentMetricCard(
                  label: 'Terbayar',
                  value: formatStudentCurrency(summary['total_paid']),
                  icon: Icons.task_alt_rounded,
                  tone: const Color(0xFF0B8F6E),
                ),
              ],
            ),
          ),
          const SizedBox(height: 16),
          AppSectionCard(
            padding: const EdgeInsets.all(14),
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
                  const StudentTicketEmptyState(
                    title: 'Tidak ada tagihan aktif',
                    message:
                        'Semua tagihan sudah lunas atau belum ada tagihan aktif.',
                    icon: Icons.task_alt_rounded,
                    accentColor: Color(0xFF0B8F6E),
                    footerLabel: 'TIDAK ADA TIKET TAGIHAN',
                  )
                else ...[
                  StudentInfoRow(
                    label: 'Nomor tagihan',
                    value: normalizeStudentText(activeBill['nomor_tagihan']),
                    icon: Icons.confirmation_number_rounded,
                  ),
                  StudentInfoRow(
                    label: 'Periode',
                    value: normalizeStudentText(activeBill['periode']),
                    icon: Icons.date_range_rounded,
                  ),
                  StudentInfoRow(
                    label: 'Sisa pembayaran',
                    value:
                        formatStudentCurrency(activeBill['outstanding_amount']),
                    icon: Icons.payments_rounded,
                  ),
                  const SizedBox(height: 10),
                  Align(
                    alignment: Alignment.centerLeft,
                    child: FilledButton.icon(
                      onPressed: (!bniVaEnabled ||
                              activeBill['can_generate_va'] != true ||
                              _isGeneratingVa)
                          ? null
                          : () => _generateVirtualAccount(
                              (activeBill['id'] as num).toInt()),
                      icon: _isGeneratingVa
                          ? const SizedBox(
                              width: 16,
                              height: 16,
                              child: CircularProgressIndicator(
                                strokeWidth: 2,
                                color: Colors.white,
                              ),
                            )
                          : const Icon(Icons.account_balance_wallet_rounded,
                              size: 18),
                      label: Text(
                        bniVaEnabled
                            ? 'Buat / pakai Virtual Account'
                            : 'BNI VA belum aktif',
                      ),
                    ),
                  ),
                ],
              ],
            ),
          ),
          const SizedBox(height: 16),
          AppSectionCard(
            padding: const EdgeInsets.all(14),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                StudentSectionHeading(
                  title: 'Virtual Account aktif',
                  subtitle: activePayment == null
                      ? 'Nomor VA akan muncul setelah dibuat dari tagihan aktif.'
                      : 'Gunakan nomor berikut untuk membayar lewat kanal BNI.',
                  trailing: TextButton(
                    onPressed: widget.onOpenHistoryTab,
                    child: const Text('Riwayat'),
                  ),
                ),
                const SizedBox(height: 12),
                if (activePayment == null)
                  const StudentTicketEmptyState(
                    title: 'Belum ada VA aktif',
                    message:
                        'Buat Virtual Account dari tagihan aktif untuk melanjutkan pembayaran.',
                    icon: Icons.account_balance_wallet_outlined,
                    accentColor: Color(0xFF2563EB),
                    footerLabel: 'MENUNGGU VIRTUAL ACCOUNT',
                  )
                else ...[
                  Container(
                    width: double.infinity,
                    padding: const EdgeInsets.all(16),
                    decoration: BoxDecoration(
                      gradient: const LinearGradient(
                        colors: [Color(0xFF0F172A), Color(0xFF1E293B)],
                        begin: Alignment.topLeft,
                        end: Alignment.bottomRight,
                      ),
                      borderRadius: BorderRadius.circular(20),
                    ),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          'Nomor Virtual Account',
                          style: TextStyle(
                            color: Colors.white.withOpacity(0.78),
                            fontSize: 11,
                            fontWeight: FontWeight.w600,
                          ),
                        ),
                        const SizedBox(height: 8),
                        Text(
                          normalizeStudentText(activePayment['va_number']),
                          style: const TextStyle(
                            color: Colors.white,
                            fontSize: 20,
                            fontWeight: FontWeight.w800,
                            letterSpacing: 0.8,
                          ),
                        ),
                        const SizedBox(height: 6),
                        Text(
                          'Berlaku sampai ${formatStudentDateTime(activePayment['va_expired_at'] as String?)}',
                          style: TextStyle(
                            color: Colors.white.withOpacity(0.78),
                            fontSize: 11,
                          ),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: 10),
                  Row(
                    children: [
                      Expanded(
                        child: OutlinedButton.icon(
                          onPressed: () => _copyVaNumber(
                            normalizeStudentText(activePayment['va_number']),
                          ),
                          icon: const Icon(Icons.copy_rounded, size: 18),
                          label: const Text('Salin nomor VA'),
                        ),
                      ),
                      const SizedBox(width: 10),
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
                  const SizedBox(height: 12),
                  const Text(
                    'Langkah pembayaran',
                    style: TextStyle(
                      fontSize: 13,
                      fontWeight: FontWeight.w800,
                      color: AppColors.textMain,
                    ),
                  ),
                  const SizedBox(height: 8),
                  const _StepText(
                    number: '1',
                    text: 'Salin nomor Virtual Account.',
                  ),
                  const _StepText(
                    number: '2',
                    text: 'Bayar lewat ATM BNI, mobile banking, atau teller.',
                  ),
                  const _StepText(
                    number: '3',
                    text:
                        'Status akan berubah terverifikasi setelah pembayaran masuk.',
                  ),
                ],
              ],
            ),
          ),
          if (recentPayments.isNotEmpty) ...[
            const SizedBox(height: 16),
            AppSectionCard(
              padding: const EdgeInsets.all(14),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const StudentSectionHeading(
                    title: 'Transaksi terbaru',
                    subtitle: 'Riwayat singkat pembayaran terakhir.',
                  ),
                  const SizedBox(height: 12),
                  ...recentPayments.map(
                    (item) => Padding(
                      padding: const EdgeInsets.only(bottom: 10),
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

    return StudentTicketCard(
      accentColor: color,
      padding: const EdgeInsets.fromLTRB(12, 12, 12, 10),
      footer: StudentTicketAmount(
        label: 'Nominal bayar',
        value: formatStudentCurrency(item['nominal_bayar']),
        color: color,
      ),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  normalizeStudentText(bill['nomor_tagihan']),
                  style: const TextStyle(
                    fontSize: 12.5,
                    fontWeight: FontWeight.w800,
                    color: AppColors.textMain,
                  ),
                ),
                const SizedBox(height: 3),
                Text(
                  formatStudentDate(item['tanggal_bayar'] as String?),
                  style: const TextStyle(
                    fontSize: 10.5,
                    color: AppColors.textMuted,
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(width: 8),
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
      padding: const EdgeInsets.only(bottom: 8),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            width: 22,
            height: 22,
            decoration: const BoxDecoration(
              color: AppColors.accentSoft,
              shape: BoxShape.circle,
            ),
            alignment: Alignment.center,
            child: Text(
              number,
              style: const TextStyle(
                fontSize: 11,
                fontWeight: FontWeight.w800,
                color: AppColors.accentDeep,
              ),
            ),
          ),
          const SizedBox(width: 8),
          Expanded(
            child: Text(
              text,
              style: const TextStyle(
                fontSize: 11.5,
                color: AppColors.textBody,
                height: 1.35,
              ),
            ),
          ),
        ],
      ),
    );
  }
}
