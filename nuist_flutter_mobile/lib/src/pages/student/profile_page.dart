import 'package:flutter/material.dart';

import '../../services/student_mobile_repository.dart';
import '../../theme/app_theme.dart';
import '../../widgets/app/app_section_card.dart';
import 'student_ui.dart';

class StudentProfilePage extends StatefulWidget {
  const StudentProfilePage({
    super.key,
    required this.repository,
    required this.dataRevision,
    required this.onLogout,
  });

  final StudentMobileRepository repository;
  final int dataRevision;
  final Future<void> Function() onLogout;

  @override
  State<StudentProfilePage> createState() => _StudentProfilePageState();
}

class _StudentProfilePageState extends State<StudentProfilePage> {
  bool _isLoading = true;
  bool _isLoggingOut = false;
  String? _errorMessage;
  Map<String, dynamic> _data = const <String, dynamic>{};

  @override
  void initState() {
    super.initState();
    _load();
  }

  @override
  void didUpdateWidget(covariant StudentProfilePage oldWidget) {
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
      final result = await widget.repository.getProfile();
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

  Future<void> _logout() async {
    if (_isLoggingOut) {
      return;
    }
    setState(() {
      _isLoggingOut = true;
    });

    try {
      await widget.onLogout();
    } finally {
      if (mounted) {
        setState(() {
          _isLoggingOut = false;
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
                child: const Text('Coba lagi'),
              ),
            ],
          ),
        ),
      );
    }

    final user = Map<String, dynamic>.from(
      (_data['user'] as Map?) ?? const <String, dynamic>{},
    );
    final student = Map<String, dynamic>.from(
      (_data['student'] as Map?) ?? const <String, dynamic>{},
    );
    final school = Map<String, dynamic>.from(
      (_data['school'] as Map?) ?? const <String, dynamic>{},
    );
    final summary = Map<String, dynamic>.from(
      (_data['summary'] as Map?) ?? const <String, dynamic>{},
    );

    return RefreshIndicator(
      onRefresh: _load,
      child: ListView(
        physics: const AlwaysScrollableScrollPhysics(),
        padding: const EdgeInsets.fromLTRB(18, 18, 18, 132),
        children: [
          Container(
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
                Container(
                  width: 68,
                  height: 68,
                  decoration: BoxDecoration(
                    color: Colors.white.withOpacity(0.14),
                    shape: BoxShape.circle,
                  ),
                  child: const Icon(
                    Icons.person_rounded,
                    color: Colors.white,
                    size: 34,
                  ),
                ),
                const SizedBox(width: 16),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        normalizeStudentText(student['name']),
                        style: const TextStyle(
                          fontSize: 22,
                          fontWeight: FontWeight.w800,
                          color: Colors.white,
                        ),
                      ),
                      const SizedBox(height: 6),
                      Text(
                        normalizeStudentText(user['email']),
                        style: const TextStyle(
                          fontSize: 13,
                          color: Colors.white,
                        ),
                      ),
                      const SizedBox(height: 8),
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
                          [
                            normalizeStudentText(student['kelas'],
                                fallback: ''),
                            normalizeStudentText(student['jurusan'],
                                fallback: ''),
                          ].where((item) => item.isNotEmpty).join(' • '),
                          style: const TextStyle(
                            color: Colors.white,
                            fontSize: 12,
                            fontWeight: FontWeight.w700,
                          ),
                        ),
                      ),
                    ],
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
                const StudentSectionHeading(
                  title: 'Ringkasan akun',
                  subtitle: 'Gambaran cepat status finansial siswa.',
                ),
                const SizedBox(height: 14),
                Row(
                  children: [
                    Expanded(
                      child: _ProfileMetric(
                        label: 'Total tagihan',
                        value: '${summary['total_bills'] ?? 0}',
                      ),
                    ),
                    const SizedBox(width: 12),
                    Expanded(
                      child: _ProfileMetric(
                        label: 'Sudah lunas',
                        value: '${summary['paid_bills'] ?? 0}',
                      ),
                    ),
                    const SizedBox(width: 12),
                    Expanded(
                      child: _ProfileMetric(
                        label: 'Terbayar',
                        value: formatStudentCurrency(summary['total_paid']),
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
                const StudentSectionHeading(
                  title: 'Data siswa',
                  subtitle: 'Informasi utama yang terhubung dengan akun login.',
                ),
                const SizedBox(height: 10),
                StudentInfoRow(
                  label: 'Nama lengkap',
                  value: normalizeStudentText(student['name']),
                  icon: Icons.badge_rounded,
                ),
                StudentInfoRow(
                  label: 'NIS / NISN',
                  value:
                      '${normalizeStudentText(student['nis'])} / ${normalizeStudentText(student['nisn'])}',
                  icon: Icons.credit_card_rounded,
                ),
                StudentInfoRow(
                  label: 'Kelas',
                  value: normalizeStudentText(student['kelas']),
                  icon: Icons.class_rounded,
                ),
                StudentInfoRow(
                  label: 'Jurusan',
                  value: normalizeStudentText(student['jurusan']),
                  icon: Icons.menu_book_rounded,
                ),
                StudentInfoRow(
                  label: 'Email',
                  value: normalizeStudentText(student['email']),
                  icon: Icons.email_rounded,
                ),
                StudentInfoRow(
                  label: 'Telepon',
                  value: normalizeStudentText(student['phone']),
                  icon: Icons.phone_rounded,
                ),
                StudentInfoRow(
                  label: 'Tahun masuk',
                  value: normalizeStudentText(student['entry_year']),
                  icon: Icons.calendar_today_rounded,
                ),
                StudentInfoRow(
                  label: 'Wali / Orang tua',
                  value: normalizeStudentText(student['parent_name']),
                  icon: Icons.family_restroom_rounded,
                ),
                StudentInfoRow(
                  label: 'Alamat',
                  value: normalizeStudentText(student['address']),
                  icon: Icons.location_on_rounded,
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
                  title: 'Informasi sekolah',
                  subtitle: 'Asal sekolah yang terhubung dengan akun siswa.',
                ),
                const SizedBox(height: 10),
                StudentInfoRow(
                  label: 'Nama sekolah',
                  value: normalizeStudentText(school['name']),
                  icon: Icons.school_rounded,
                ),
                StudentInfoRow(
                  label: 'SCOD',
                  value: normalizeStudentText(school['scod']),
                  icon: Icons.qr_code_rounded,
                ),
                StudentInfoRow(
                  label: 'Telepon',
                  value: normalizeStudentText(school['phone']),
                  icon: Icons.call_rounded,
                ),
                StudentInfoRow(
                  label: 'Email',
                  value: normalizeStudentText(school['email']),
                  icon: Icons.mail_outline_rounded,
                ),
                StudentInfoRow(
                  label: 'Alamat',
                  value: normalizeStudentText(school['address']),
                  icon: Icons.place_rounded,
                ),
              ],
            ),
          ),
          const SizedBox(height: 18),
          SizedBox(
            width: double.infinity,
            child: OutlinedButton.icon(
              onPressed: _isLoggingOut ? null : _logout,
              icon: _isLoggingOut
                  ? const SizedBox(
                      width: 16,
                      height: 16,
                      child: CircularProgressIndicator(strokeWidth: 2),
                    )
                  : const Icon(Icons.logout_rounded),
              label: const Text('Logout'),
            ),
          ),
        ],
      ),
    );
  }
}

class _ProfileMetric extends StatelessWidget {
  const _ProfileMetric({
    required this.label,
    required this.value,
  });

  final String label;
  final String value;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: const Color(0xFFF8FAFC),
        borderRadius: BorderRadius.circular(18),
        border: Border.all(color: const Color(0xFFE2E8F0)),
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
              fontSize: 15,
              fontWeight: FontWeight.w800,
              color: AppColors.textMain,
            ),
          ),
        ],
      ),
    );
  }
}
