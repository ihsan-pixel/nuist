import 'package:flutter/material.dart';

import '../../services/student_mobile_repository.dart';
import '../../widgets/app/app_section_card.dart';
import '../../widgets/app/teacher_page_header.dart';
import 'student_ui.dart';

class StudentProfilePage extends StatefulWidget {
  const StudentProfilePage({
    super.key,
    required this.repository,
    required this.dataRevision,
    required this.onBackToHome,
    required this.onLogout,
  });

  final StudentMobileRepository repository;
  final int dataRevision;
  final VoidCallback onBackToHome;
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
    return Column(
      children: [
        TeacherOverlayPageHeader(
          title: 'Profil',
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

    final user = Map<String, dynamic>.from(
      (_data['user'] as Map?) ?? const <String, dynamic>{},
    );
    final student = Map<String, dynamic>.from(
      (_data['student'] as Map?) ?? const <String, dynamic>{},
    );
    final school = Map<String, dynamic>.from(
      (_data['school'] as Map?) ?? const <String, dynamic>{},
    );
    final classBadge = [
      normalizeStudentText(student['kelas'], fallback: ''),
      normalizeStudentText(student['jurusan'], fallback: ''),
    ].where((item) => item.isNotEmpty).join(' • ');

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
                const Text('Profil siswa', style: TextStyle(fontSize: 20, fontWeight: FontWeight.w800, color: Color(0xFF172A24))),
                const SizedBox(height: 16),
                _ProfileIdentityCard(
                  name: normalizeStudentText(student['name']),
                  email: normalizeStudentText(user['email']),
                  classBadge: classBadge,
                  schoolName: normalizeStudentText(school['name']),
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
          ),
        ],
      ),
    );
  }
}

class _ProfileIdentityCard extends StatelessWidget {
  const _ProfileIdentityCard({
    required this.name,
    required this.email,
    required this.classBadge,
    required this.schoolName,
  });

  final String name;
  final String email;
  final String classBadge;
  final String schoolName;

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
          Container(
            width: 46,
            height: 46,
            decoration: const BoxDecoration(color: Color(0xFF00745A), shape: BoxShape.circle),
            child: const Icon(Icons.person_rounded, color: Colors.white),
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(name, style: const TextStyle(fontSize: 16, fontWeight: FontWeight.w800, color: Color(0xFF172A24))),
                const SizedBox(height: 2),
                Text(email, maxLines: 1, overflow: TextOverflow.ellipsis, style: const TextStyle(fontSize: 11, color: Color(0xFF64746E))),
                if (classBadge.isNotEmpty || schoolName != '-') ...[
                  const SizedBox(height: 5),
                  Text(
                    [classBadge, schoolName].where((item) => item.isNotEmpty && item != '-').join(' • '),
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                    style: const TextStyle(fontSize: 11, fontWeight: FontWeight.w700, color: Color(0xFF00745A)),
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
