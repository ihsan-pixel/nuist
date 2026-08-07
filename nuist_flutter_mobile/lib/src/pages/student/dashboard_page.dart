import 'package:flutter/material.dart';

import '../../services/student_mobile_repository.dart';
import '../../theme/app_theme.dart';
import '../../widgets/app/app_empty_state.dart';
import '../../widgets/app/app_section_card.dart';
import '../../widgets/app/app_stat_card.dart';
import 'student_ui.dart';

class StudentDashboardPage extends StatefulWidget {
  const StudentDashboardPage({
    super.key,
    required this.repository,
    required this.dataRevision,
    required this.onSelectTab,
  });

  final StudentMobileRepository repository;
  final int dataRevision;
  final ValueChanged<int> onSelectTab;

  @override
  State<StudentDashboardPage> createState() => _StudentDashboardPageState();
}

class _StudentDashboardPageState extends State<StudentDashboardPage> {
  bool _isLoading = true;
  String? _errorMessage;
  Map<String, dynamic> _data = const <String, dynamic>{};

  @override
  void initState() {
    super.initState();
    _load();
  }

  @override
  void didUpdateWidget(covariant StudentDashboardPage oldWidget) {
    super.didUpdateWidget(oldWidget);
    if (oldWidget.dataRevision != widget.dataRevision) {
      _load(isRefresh: true);
    }
  }

  Future<void> _load({bool isRefresh = false}) async {
    if (!mounted) {
      return;
    }
    setState(() {
      _isLoading = true;
      _errorMessage = null;
    });

    try {
      final result = await widget.repository.getDashboard();
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
      return _StudentErrorView(
        message: _errorMessage!,
        onRetry: _load,
      );
    }

    final student = Map<String, dynamic>.from(
      (_data['student'] as Map?) ?? const <String, dynamic>{},
    );
    final school = Map<String, dynamic>.from(
      (_data['school'] as Map?) ?? const <String, dynamic>{},
    );
    final summary = Map<String, dynamic>.from(
      (_data['summary'] as Map?) ?? const <String, dynamic>{},
    );
    final activeBill = (_data['active_bill'] as Map?) == null
        ? null
        : Map<String, dynamic>.from(_data['active_bill'] as Map);
    final reminder = (_data['upcoming_reminder'] as Map?) == null
        ? null
        : Map<String, dynamic>.from(_data['upcoming_reminder'] as Map);
    final lastPayment = (_data['last_payment'] as Map?) == null
        ? null
        : Map<String, dynamic>.from(_data['last_payment'] as Map);
    final recentPayments = ((_data['recent_payments'] as List?) ?? const [])
        .whereType<Map>()
        .map((item) => Map<String, dynamic>.from(item))
        .toList();

    return RefreshIndicator(
      onRefresh: _load,
      child: ListView(
        physics: const AlwaysScrollableScrollPhysics(),
        padding: const EdgeInsets.fromLTRB(18, 18, 18, 132),
        children: [
          _StudentDashboardHero(
            greeting: normalizeStudentText(_data['greeting']),
            studentName: normalizeStudentText(student['name']),
            schoolName: normalizeStudentText(school['name']),
            classLabel: [
              normalizeStudentText(student['kelas'], fallback: ''),
              normalizeStudentText(student['jurusan'], fallback: ''),
            ].where((item) => item.isNotEmpty).join(' • '),
          ),
          const SizedBox(height: 18),
          SingleChildScrollView(
            scrollDirection: Axis.horizontal,
            child: Row(
              children: [
                AppStatCard(
                  label: 'Tagihan aktif',
                  value: '${summary['unpaid_bills'] ?? 0}',
                  color: const Color(0xFF0B8F6E),
                  trailing: const Icon(
                    Icons.receipt_long_rounded,
                    color: Colors.white,
                    size: 24,
                  ),
                ),
                const SizedBox(width: 14),
                AppStatCard(
                  label: 'Tingkat lunas',
                  value: '${summary['payment_completion_rate'] ?? 0}%',
                  color: const Color(0xFFFF8A1F),
                  trailing: const Icon(
                    Icons.pie_chart_rounded,
                    color: Colors.white,
                    size: 24,
                  ),
                ),
                const SizedBox(width: 14),
                AppStatCard(
                  label: 'Terbayar',
                  value: formatStudentCurrency(summary['total_paid']),
                  color: const Color(0xFF2563EB),
                  trailing: const Icon(
                    Icons.payments_rounded,
                    color: Colors.white,
                    size: 24,
                  ),
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
                  title: 'Ringkasan pembayaran',
                  subtitle:
                      'Pantau posisi tagihan dan progres pelunasan saat ini.',
                  trailing: TextButton(
                    onPressed: () => widget.onSelectTab(1),
                    child: const Text('Lihat semua'),
                  ),
                ),
                const SizedBox(height: 14),
                Wrap(
                  spacing: 12,
                  runSpacing: 12,
                  children: [
                    _SummaryMiniCard(
                      label: 'Total tagihan',
                      value: '${summary['total_bills'] ?? 0}',
                      color: const Color(0xFFE8F7EE),
                    ),
                    _SummaryMiniCard(
                      label: 'Sudah lunas',
                      value: '${summary['paid_bills'] ?? 0}',
                      color: const Color(0xFFFFF1E4),
                    ),
                    _SummaryMiniCard(
                      label: 'Sisa tagihan',
                      value: formatStudentCurrency(summary['outstanding']),
                      color: const Color(0xFFEAF2FF),
                    ),
                  ],
                ),
              ],
            ),
          ),
          if (reminder != null) ...[
            const SizedBox(height: 18),
            AppSectionCard(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const StudentSectionHeading(
                    title: 'Pengingat terdekat',
                    subtitle:
                        'Prioritaskan tagihan yang jatuh temponya paling dekat.',
                  ),
                  const SizedBox(height: 12),
                  Container(
                    width: double.infinity,
                    padding: const EdgeInsets.all(16),
                    decoration: BoxDecoration(
                      color: const Color(0xFFFFF4E8),
                      borderRadius: BorderRadius.circular(18),
                      border: Border.all(color: const Color(0xFFFCCEA5)),
                    ),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          normalizeStudentText(reminder['title']),
                          style: const TextStyle(
                            fontSize: 15,
                            fontWeight: FontWeight.w800,
                            color: Color(0xFF9A3412),
                          ),
                        ),
                        const SizedBox(height: 6),
                        Text(
                          normalizeStudentText(reminder['message']),
                          style: const TextStyle(
                            fontSize: 13,
                            color: Color(0xFF9A3412),
                            height: 1.4,
                          ),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),
          ],
          const SizedBox(height: 18),
          AppSectionCard(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                StudentSectionHeading(
                  title: 'Tagihan utama',
                  subtitle: activeBill == null
                      ? 'Belum ada tagihan yang perlu ditindaklanjuti.'
                      : 'Tagihan yang sebaiknya kamu cek atau bayar lebih dulu.',
                  trailing: activeBill == null
                      ? null
                      : StudentStatusBadge(
                          label:
                              normalizeStudentText(activeBill['status_label']),
                          color:
                              billStatusColor(activeBill['status'] as String?),
                        ),
                ),
                const SizedBox(height: 14),
                if (activeBill == null)
                  const AppEmptyState(
                    title: 'Belum ada tagihan',
                    message:
                        'Saat admin sekolah membuat tagihan, datanya akan tampil di sini.',
                    icon: Icons.receipt_long_rounded,
                  )
                else
                  Column(
                    children: [
                      StudentInfoRow(
                        label: 'Nomor tagihan',
                        value:
                            normalizeStudentText(activeBill['nomor_tagihan']),
                        icon: Icons.confirmation_number_rounded,
                      ),
                      StudentInfoRow(
                        label: 'Jenis tagihan',
                        value:
                            normalizeStudentText(activeBill['jenis_tagihan']),
                        icon: Icons.category_rounded,
                      ),
                      StudentInfoRow(
                        label: 'Jatuh tempo',
                        value: formatStudentDate(
                            activeBill['jatuh_tempo'] as String?),
                        icon: Icons.event_available_rounded,
                      ),
                      StudentInfoRow(
                        label: 'Sisa pembayaran',
                        value: formatStudentCurrency(
                            activeBill['outstanding_amount']),
                        icon: Icons.account_balance_wallet_rounded,
                      ),
                      const SizedBox(height: 12),
                      SizedBox(
                        width: double.infinity,
                        child: FilledButton.icon(
                          onPressed: () => widget.onSelectTab(2),
                          icon: const Icon(Icons.payments_rounded),
                          label: const Text('Buka halaman pembayaran'),
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
                  title: 'Pembayaran terbaru',
                  subtitle: 'Riwayat singkat transaksi siswa.',
                  trailing: TextButton(
                    onPressed: () => widget.onSelectTab(3),
                    child: const Text('Riwayat'),
                  ),
                ),
                const SizedBox(height: 12),
                if (recentPayments.isEmpty)
                  const AppEmptyState(
                    title: 'Belum ada pembayaran',
                    message:
                        'Pembayaran yang telah dibuat akan tampil di sini.',
                    icon: Icons.history_rounded,
                  )
                else
                  ...recentPayments.map(
                    (item) => Padding(
                      padding: const EdgeInsets.only(bottom: 12),
                      child: _PaymentPreviewTile(item: item),
                    ),
                  ),
                if (lastPayment != null && recentPayments.isEmpty)
                  _PaymentPreviewTile(item: lastPayment),
              ],
            ),
          ),
        ],
      ),
    );
  }
}

class _StudentDashboardHero extends StatelessWidget {
  const _StudentDashboardHero({
    required this.greeting,
    required this.studentName,
    required this.schoolName,
    required this.classLabel,
  });

  final String greeting;
  final String studentName;
  final String schoolName;
  final String classLabel;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(22),
      decoration: BoxDecoration(
        gradient: const LinearGradient(
          colors: [Color(0xFF0B8F6E), Color(0xFF066C56)],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
        borderRadius: BorderRadius.circular(28),
        boxShadow: const [
          BoxShadow(
            color: Color(0x330B8F6E),
            blurRadius: 24,
            offset: Offset(0, 12),
          ),
        ],
      ),
      child: Row(
        children: [
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  greeting,
                  style: const TextStyle(
                    fontSize: 14,
                    fontWeight: FontWeight.w600,
                    color: Color(0xFFD9FDEB),
                  ),
                ),
                const SizedBox(height: 10),
                Text(
                  studentName,
                  style: const TextStyle(
                    fontSize: 24,
                    fontWeight: FontWeight.w800,
                    color: Colors.white,
                  ),
                ),
                const SizedBox(height: 6),
                Text(
                  schoolName,
                  style: const TextStyle(
                    fontSize: 14,
                    color: Colors.white,
                    height: 1.35,
                  ),
                ),
                if (classLabel.trim().isNotEmpty) ...[
                  const SizedBox(height: 12),
                  Container(
                    padding: const EdgeInsets.symmetric(
                      horizontal: 12,
                      vertical: 8,
                    ),
                    decoration: BoxDecoration(
                      color: Colors.white.withOpacity(0.16),
                      borderRadius: BorderRadius.circular(999),
                    ),
                    child: Text(
                      classLabel,
                      style: const TextStyle(
                        color: Colors.white,
                        fontSize: 12,
                        fontWeight: FontWeight.w700,
                      ),
                    ),
                  ),
                ],
              ],
            ),
          ),
          const SizedBox(width: 16),
          Container(
            width: 72,
            height: 72,
            decoration: BoxDecoration(
              color: Colors.white.withOpacity(0.14),
              shape: BoxShape.circle,
              border: Border.all(
                color: Colors.white.withOpacity(0.2),
              ),
            ),
            child: const Icon(
              Icons.school_rounded,
              color: Colors.white,
              size: 34,
            ),
          ),
        ],
      ),
    );
  }
}

class _SummaryMiniCard extends StatelessWidget {
  const _SummaryMiniCard({
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
      width: 148,
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
              fontSize: 18,
              fontWeight: FontWeight.w800,
              color: AppColors.textMain,
            ),
          ),
        ],
      ),
    );
  }
}

class _PaymentPreviewTile extends StatelessWidget {
  const _PaymentPreviewTile({
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
        color: const Color(0xFFF8FAFC),
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: const Color(0xFFE2E8F0)),
      ),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            width: 44,
            height: 44,
            decoration: BoxDecoration(
              color: color.withOpacity(0.12),
              borderRadius: BorderRadius.circular(14),
            ),
            child: Icon(
              Icons.payments_rounded,
              color: color,
            ),
          ),
          const SizedBox(width: 14),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  normalizeStudentText(bill['nomor_tagihan']),
                  style: const TextStyle(
                    fontSize: 14,
                    fontWeight: FontWeight.w800,
                    color: AppColors.textMain,
                  ),
                ),
                const SizedBox(height: 4),
                Text(
                  '${normalizeStudentText(bill['jenis_tagihan'])} • ${formatStudentCurrency(item['nominal_bayar'])}',
                  style: const TextStyle(
                    fontSize: 13,
                    color: AppColors.textMuted,
                  ),
                ),
                const SizedBox(height: 4),
                Text(
                  formatStudentDate(item['tanggal_bayar'] as String?),
                  style: const TextStyle(
                    fontSize: 12,
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

class _StudentErrorView extends StatelessWidget {
  const _StudentErrorView({
    required this.message,
    required this.onRetry,
  });

  final String message;
  final Future<void> Function() onRetry;

  @override
  Widget build(BuildContext context) {
    return Center(
      child: Padding(
        padding: const EdgeInsets.all(24),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            const Icon(
              Icons.cloud_off_rounded,
              size: 42,
              color: AppColors.accentWarm,
            ),
            const SizedBox(height: 12),
            Text(
              message,
              textAlign: TextAlign.center,
              style: const TextStyle(
                fontSize: 14,
                color: AppColors.textMain,
              ),
            ),
            const SizedBox(height: 16),
            FilledButton(
              onPressed: onRetry,
              child: const Text('Coba lagi'),
            ),
          ],
        ),
      ),
    );
  }
}
