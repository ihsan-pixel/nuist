import 'package:flutter/material.dart';

import '../../services/student_mobile_repository.dart';
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
      return StudentErrorView(
        message: _errorMessage!,
        onRetry: _load,
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

    final classBadge = [
      normalizeStudentText(student['kelas'], fallback: ''),
      normalizeStudentText(student['jurusan'], fallback: ''),
    ].where((item) => item.isNotEmpty).join(' • ');

    return RefreshIndicator(
      onRefresh: _load,
      child: ListView(
        physics: const AlwaysScrollableScrollPhysics(),
        padding: const EdgeInsets.fromLTRB(16, 16, 16, 128),
        children: [
          StudentPageBanner(
            title: normalizeStudentText(student['name']),
            subtitle: normalizeStudentText(user['email']),
            icon: Icons.person_rounded,
            badges: [
              if (classBadge.isNotEmpty)
                StudentBannerBadge(
                  label: classBadge,
                  icon: Icons.school_rounded,
                ),
              StudentBannerBadge(
                label: normalizeStudentText(school['name']),
                icon: Icons.apartment_rounded,
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
                  label: 'Total tagihan',
                  value: '${summary['total_bills'] ?? 0}',
                  icon: Icons.receipt_long_rounded,
                  tone: const Color(0xFF2563EB),
                ),
                StudentMetricCard(
                  label: 'Sudah lunas',
                  value: '${summary['paid_bills'] ?? 0}',
                  icon: Icons.task_alt_rounded,
                  tone: const Color(0xFF0B8F6E),
                ),
                StudentMetricCard(
                  label: 'Terbayar',
                  value: formatStudentCurrency(summary['total_paid']),
                  icon: Icons.payments_rounded,
                  tone: const Color(0xFFD97706),
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
                  title: 'Data siswa',
                  subtitle: 'Informasi utama yang terhubung ke akun login.',
                ),
                const SizedBox(height: 12),
                LayoutBuilder(
                  builder: (context, constraints) {
                    final width = (constraints.maxWidth - 10) / 2;
                    return Wrap(
                      spacing: 10,
                      runSpacing: 10,
                      children: [
                        SizedBox(
                          width: width,
                          child: StudentFactTile(
                            label: 'NIS / NISN',
                            value:
                                '${normalizeStudentText(student['nis'])} / ${normalizeStudentText(student['nisn'])}',
                            icon: Icons.credit_card_rounded,
                          ),
                        ),
                        SizedBox(
                          width: width,
                          child: StudentFactTile(
                            label: 'Kelas',
                            value: normalizeStudentText(student['kelas']),
                            icon: Icons.class_rounded,
                          ),
                        ),
                        SizedBox(
                          width: width,
                          child: StudentFactTile(
                            label: 'Jurusan',
                            value: normalizeStudentText(student['jurusan']),
                            icon: Icons.menu_book_rounded,
                          ),
                        ),
                        SizedBox(
                          width: width,
                          child: StudentFactTile(
                            label: 'Telepon',
                            value: normalizeStudentText(student['phone']),
                            icon: Icons.phone_rounded,
                          ),
                        ),
                        SizedBox(
                          width: width,
                          child: StudentFactTile(
                            label: 'Tahun masuk',
                            value: normalizeStudentText(student['entry_year']),
                            icon: Icons.calendar_today_rounded,
                          ),
                        ),
                        SizedBox(
                          width: width,
                          child: StudentFactTile(
                            label: 'Wali / Orang tua',
                            value: normalizeStudentText(student['parent_name']),
                            icon: Icons.family_restroom_rounded,
                          ),
                        ),
                        SizedBox(
                          width: constraints.maxWidth,
                          child: StudentFactTile(
                            label: 'Alamat',
                            value: normalizeStudentText(student['address']),
                            icon: Icons.location_on_rounded,
                          ),
                        ),
                      ],
                    );
                  },
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
                  title: 'Informasi sekolah',
                  subtitle: 'Asal sekolah yang terhubung dengan akun siswa.',
                ),
                const SizedBox(height: 12),
                LayoutBuilder(
                  builder: (context, constraints) {
                    final width = (constraints.maxWidth - 10) / 2;
                    return Wrap(
                      spacing: 10,
                      runSpacing: 10,
                      children: [
                        SizedBox(
                          width: width,
                          child: StudentFactTile(
                            label: 'Nama sekolah',
                            value: normalizeStudentText(school['name']),
                            icon: Icons.school_rounded,
                          ),
                        ),
                        SizedBox(
                          width: width,
                          child: StudentFactTile(
                            label: 'SCOD',
                            value: normalizeStudentText(school['scod']),
                            icon: Icons.qr_code_rounded,
                          ),
                        ),
                        SizedBox(
                          width: width,
                          child: StudentFactTile(
                            label: 'Telepon',
                            value: normalizeStudentText(school['phone']),
                            icon: Icons.call_rounded,
                          ),
                        ),
                        SizedBox(
                          width: width,
                          child: StudentFactTile(
                            label: 'Email',
                            value: normalizeStudentText(school['email']),
                            icon: Icons.mail_outline_rounded,
                          ),
                        ),
                        SizedBox(
                          width: constraints.maxWidth,
                          child: StudentFactTile(
                            label: 'Alamat',
                            value: normalizeStudentText(school['address']),
                            icon: Icons.place_rounded,
                          ),
                        ),
                      ],
                    );
                  },
                ),
              ],
            ),
          ),
          const SizedBox(height: 16),
          Align(
            alignment: Alignment.centerLeft,
            child: OutlinedButton.icon(
              onPressed: _isLoggingOut ? null : _logout,
              icon: _isLoggingOut
                  ? const SizedBox(
                      width: 16,
                      height: 16,
                      child: CircularProgressIndicator(strokeWidth: 2),
                    )
                  : const Icon(Icons.logout_rounded, size: 18),
              label: const Text('Logout'),
            ),
          ),
        ],
      ),
    );
  }
}
