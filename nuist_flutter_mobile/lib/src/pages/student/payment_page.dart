import 'package:flutter/material.dart';
import 'package:flutter/services.dart';

import '../../services/student_mobile_repository.dart';
import '../../theme/app_theme.dart';
import '../../widgets/app/app_section_card.dart';
import '../../widgets/app/teacher_page_header.dart';
import 'student_ui.dart';

class StudentPaymentPage extends StatefulWidget {
  const StudentPaymentPage({
    super.key,
    required this.repository,
    required this.dataRevision,
    required this.onDataChanged,
    required this.onBackToHome,
    required this.onOpenHistoryTab,
  });

  final StudentMobileRepository repository;
  final int dataRevision;
  final VoidCallback onDataChanged;
  final VoidCallback onBackToHome;
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
    return Column(
      children: [
        TeacherOverlayPageHeader(
          title: 'Pembayaran',
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
        padding: const EdgeInsets.only(bottom: 128),
        children: [
          StudentPageContentSurface(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Text(
                  'Bayar tagihan',
                  style: TextStyle(
                    fontSize: 20,
                    fontWeight: FontWeight.w800,
                    color: Color(0xFF172A24),
                  ),
                ),
                const SizedBox(height: 4),
                const Text(
                  'Gunakan Virtual Account untuk menyelesaikan tagihan aktif.',
                  style: TextStyle(fontSize: 12, color: Color(0xFF64746E)),
                ),
                const SizedBox(height: 16),
                _PaymentSummary(
                  outstanding: formatStudentCurrency(summary['outstanding']),
                  pending: '${summary['pending_payments'] ?? 0}',
                ),
          const SizedBox(height: 16),
          AppSectionCard(
            padding: const EdgeInsets.all(14),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const StudentSectionHeading(title: 'Tagihan aktif'),
                const SizedBox(height: 12),
                if (activeBill == null)
                  const StudentTicketEmptyState(
                    title: 'Tidak ada tagihan aktif',
                    message:
                        'Semua tagihan sudah lunas atau belum ada tagihan aktif.',
                    icon: Icons.task_alt_rounded,
                    accentColor: Color(0xFF00745A),
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
                    accentColor: Color(0xFF00745A),
                    footerLabel: 'MENUNGGU VIRTUAL ACCOUNT',
                  )
                else ...[
                  Container(
                    width: double.infinity,
                    padding: const EdgeInsets.all(16),
                    decoration: BoxDecoration(
                      gradient: const LinearGradient(
                        colors: [Color(0xFF172A24), Color(0xFF172A24)],
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
                            color: Colors.white.withValues(alpha: 0.78),
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
                            color: Colors.white.withValues(alpha: 0.78),
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
                  const SizedBox(height: 2),
                  const Text(
                    'Bayar melalui ATM BNI, mobile banking, atau teller. Status akan diperbarui setelah pembayaran masuk.',
                    style: TextStyle(
                      fontSize: 11,
                      color: AppColors.textBody,
                      height: 1.45,
                    ),
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
          ),
        ],
      ),
    );
  }
}

class _PaymentSummary extends StatelessWidget {
  const _PaymentSummary({required this.outstanding, required this.pending});

  final String outstanding;
  final String pending;

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
          const Icon(Icons.payments_rounded, color: Color(0xFF00745A)),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Text('Sisa tagihan', style: TextStyle(fontSize: 12, color: Color(0xFF64746E))),
                const SizedBox(height: 3),
                Text(outstanding, style: const TextStyle(fontSize: 20, fontWeight: FontWeight.w800, color: Color(0xFF00553F))),
              ],
            ),
          ),
          Text('$pending pending', style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w700, color: Color(0xFF00745A))),
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
