import 'dart:async';

import 'package:flutter/material.dart';
import 'package:flutter/services.dart';

import '../../services/teacher_mobile_repository.dart';
import '../../widgets/app/app_empty_state.dart';
import '../../widgets/app/app_section_card.dart';
import '../../widgets/app/teacher_page_header.dart';
import 'profile_change_password_page.dart';
import 'profile_settings_page.dart';

class _ProfilePalette {
  static const primary = Color(0xFF00745A);
  static const primaryDark = Color(0xFF00553F);
  static const textPrimary = Color(0xFF172A24);
  static const textSecondary = Color(0xFF64746E);
  static const border = Color(0xFFDCE7E3);
  static const softGreen = Color(0xFFE5F5F0);
  static const danger = Color(0xFFEF4444);

  const _ProfilePalette._();
}

class TeacherProfilePage extends StatefulWidget {
  const TeacherProfilePage({
    super.key,
    required this.repository,
    required this.isActive,
    required this.onOpenIzin,
    required this.onOpenManageIzin,
    required this.onBackToHome,
  });

  final TeacherMobileRepository repository;
  final bool isActive;
  final Future<void> Function() onOpenIzin;
  final Future<void> Function() onOpenManageIzin;
  final VoidCallback onBackToHome;

  @override
  State<TeacherProfilePage> createState() => _TeacherProfilePageState();
}

class _TeacherProfilePageState extends State<TeacherProfilePage>
    with WidgetsBindingObserver {
  late Future<Map<String, dynamic>> _future;

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addObserver(this);
    _future = widget.repository.getProfile();
  }

  @override
  void didUpdateWidget(covariant TeacherProfilePage oldWidget) {
    super.didUpdateWidget(oldWidget);
    if (!oldWidget.isActive && widget.isActive) {
      unawaited(_refresh());
    }
  }

  @override
  void didChangeAppLifecycleState(AppLifecycleState state) {
    if (state == AppLifecycleState.resumed && widget.isActive) {
      unawaited(_refresh());
    }
  }

  @override
  void dispose() {
    WidgetsBinding.instance.removeObserver(this);
    super.dispose();
  }

  Future<void> _refresh() async {
    final future = widget.repository.getProfile();
    setState(() {
      _future = future;
    });
    await future;
  }

  Future<void> _openSettings(Map<String, dynamic> data) async {
    await Navigator.of(context).push(
      MaterialPageRoute(
        builder: (_) => TeacherProfileSettingsPage(
          repository: widget.repository,
          initialData: data,
          onOpenChangePassword: () {
            return Navigator.of(context).push(
              MaterialPageRoute(
                builder: (_) => TeacherProfileChangePasswordPage(
                  repository: widget.repository,
                ),
              ),
            );
          },
        ),
      ),
    );

    if (!mounted) {
      return;
    }

    await _refresh();
  }

  @override
  Widget build(BuildContext context) {
    return AnnotatedRegion<SystemUiOverlayStyle>(
      value: const SystemUiOverlayStyle(
        statusBarColor: Colors.transparent,
        statusBarIconBrightness: Brightness.dark,
        statusBarBrightness: Brightness.light,
      ),
      child: SafeArea(
        bottom: false,
        child: FutureBuilder<Map<String, dynamic>>(
          future: _future,
          builder: (context, snapshot) {
            final bottomNavInset = MediaQuery.paddingOf(context).bottom + 128;

            return Column(
              children: [
                TeacherOverlayPageHeader(
                  title: 'Profil',
                  onBack: widget.onBackToHome,
                  trailing: IconButton(
                      onPressed: snapshot.hasData
                          ? () => _openSettings(
                                snapshot.data ?? const <String, dynamic>{},
                              )
                          : null,
                      icon: Icon(
                        Icons.settings_outlined,
                        color: snapshot.hasData
                            ? Colors.white
                            : Colors.white.withValues(alpha: 0.45),
                        size: 21,
                      ),
                    ),
                  ),
                Expanded(
                  child: RefreshIndicator(
                    onRefresh: _refresh,
                    child: ListView(
                      physics: const AlwaysScrollableScrollPhysics(),
                      padding: EdgeInsets.only(bottom: bottomNavInset),
                      children: [
                  Transform.translate(
                    offset: const Offset(0, -8),
                    child: Container(
                      width: double.infinity,
                      padding: const EdgeInsets.fromLTRB(14, 22, 14, 16),
                      decoration: const BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.only(
                          topLeft: Radius.circular(18),
                          topRight: Radius.circular(18),
                        ),
                      ),
                      child: snapshot.connectionState == ConnectionState.waiting
                          ? const _ProfileLoading()
                          : snapshot.hasError
                              ? _ProfileError(
                                  message: snapshot.error.toString(),
                                  onRetry: _refresh,
                                )
                              : _ProfileContent(
                                  data: snapshot.data ??
                                      const <String, dynamic>{},
                                  onOpenSettings: () => _openSettings(
                                    snapshot.data ??
                                        const <String, dynamic>{},
                                  ),
                                ),
                    ),
                  ),
                      ],
                    ),
                  ),
                ),
              ],
            );
          },
        ),
      ),
    );
  }
}

class _ProfileContent extends StatelessWidget {
  const _ProfileContent({
    required this.data,
    required this.onOpenSettings,
  });

  final Map<String, dynamic> data;
  final VoidCallback onOpenSettings;

  @override
  Widget build(BuildContext context) {
    final user = Map<String, dynamic>.from(
      (data['user'] as Map?) ?? const <String, dynamic>{},
    );
    final details = ((data['details'] as List?) ?? const [])
        .whereType<Map>()
        .map((item) => Map<String, dynamic>.from(item))
        .where(
          (item) => ((item['value'] as String?)?.trim().isNotEmpty ?? false),
        )
        .toList();
    final memberships = ((data['mgmp_memberships'] as List?) ?? const [])
        .whereType<Map>()
        .map((item) => Map<String, dynamic>.from(item))
        .toList();
    final mgmpNames = memberships
        .map((item) => (item['group_name'] as String?)?.trim() ?? '')
        .where((name) => name.isNotEmpty)
        .toList();
    if (mgmpNames.isNotEmpty) {
      details.add(<String, dynamic>{
        'label': 'MGMP Diikuti',
        'value': mgmpNames.join(', '),
      });
    }

    final name = (user['name'] as String?)?.trim();
    final role = _roleLabel(user['role'] as String?);
    final schoolName = (user['school_name'] as String?)?.trim();
    final email = (user['email'] as String?)?.trim();

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        _ProfileHeroCard(
          name: name?.isNotEmpty == true ? name! : 'Nama User',
          role: role,
          schoolName: schoolName,
          email: email,
          avatarUrl:
              _cacheBustedAvatarUrl((user['avatar_url'] as String?)?.trim()),
        ),
        const SizedBox(height: 12),
        AppSectionCard(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const _SectionHeading(
                eyebrow: 'Data Utama',
                title: 'Informasi Profil',
              ),
              const SizedBox(height: 10),
              if (details.isEmpty)
                const AppEmptyState(
                  title: 'Belum ada informasi',
                  message: 'Data profil untuk akun ini belum tersedia.',
                  icon: Icons.person_outline_rounded,
                )
              else
                ...details.map(
                  (item) => _DetailRow(
                    label: _profileDetailLabel(item['label'] as String? ?? '-'),
                    value: item['value'] as String? ?? '-',
                  ),
                ),
            ],
          ),
        ),
      ],
    );
  }
}

String _profileDetailLabel(String label) {
  return label.trim().toLowerCase() == 'nip' ? "NIP Ma'arif" : label;
}

class _ProfileHeroCard extends StatelessWidget {
  const _ProfileHeroCard({
    required this.name,
    required this.role,
    required this.schoolName,
    required this.email,
    required this.avatarUrl,
  });

  final String name;
  final String role;
  final String? schoolName;
  final String? email;
  final String? avatarUrl;

  @override
  Widget build(BuildContext context) {
    final subtitleParts = [
      role,
      if (schoolName != null && schoolName!.isNotEmpty) schoolName!,
    ];

    return AppSectionCard(
      padding: const EdgeInsets.all(14),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
            Row(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Container(
                  padding: const EdgeInsets.all(3),
                  decoration: BoxDecoration(
                    color: _ProfilePalette.softGreen,
                    shape: BoxShape.circle,
                    border: Border.all(
                      color: _ProfilePalette.border,
                    ),
                  ),
                  child: _ProfileHeaderAvatar(avatarUrl: avatarUrl),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        name,
                        maxLines: 2,
                        overflow: TextOverflow.ellipsis,
                        style: const TextStyle(
                          color: _ProfilePalette.textPrimary,
                          fontSize: 16,
                          fontWeight: FontWeight.w800,
                          height: 1.15,
                        ),
                      ),
                      const SizedBox(height: 4),
                      Text(
                        subtitleParts.join(' • '),
                        maxLines: 2,
                        overflow: TextOverflow.ellipsis,
                        style: const TextStyle(
                          color: _ProfilePalette.textSecondary,
                          fontSize: 11.5,
                          fontWeight: FontWeight.w600,
                        ),
                      ),
                      if (email != null && email!.isNotEmpty) ...[
                        const SizedBox(height: 4),
                        Text(
                          email!,
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                          style: const TextStyle(
                            color: _ProfilePalette.textSecondary,
                            fontSize: 10.5,
                          ),
                        ),
                      ],
                    ],
                  ),
                ),
              ],
            ),
            const SizedBox(height: 8),
            const Wrap(
              spacing: 8,
              runSpacing: 8,
              children: [
                _HeroPill(
                  icon: Icons.verified_rounded,
                  label: 'Verified Account',
                  foreground: _ProfilePalette.primaryDark,
                  background: _ProfilePalette.softGreen,
                ),
              ],
            ),
        ],
      ),
    );
  }
}

class _ProfileHeaderAvatar extends StatelessWidget {
  const _ProfileHeaderAvatar({
    required this.avatarUrl,
  });

  final String? avatarUrl;

  @override
  Widget build(BuildContext context) {
    return CircleAvatar(
      radius: 27,
      backgroundColor: Colors.white,
      child: ClipOval(
        child: SizedBox(
          width: 54,
          height: 54,
          child: avatarUrl != null && avatarUrl!.trim().isNotEmpty
              ? Image.network(
                  avatarUrl!.trim(),
                  fit: BoxFit.cover,
                  errorBuilder: (_, __, ___) => const Icon(
                    Icons.person_rounded,
                    size: 30,
                    color: _ProfilePalette.textSecondary,
                  ),
                )
              : const Icon(
                  Icons.person_rounded,
                  size: 30,
                  color: _ProfilePalette.textSecondary,
                ),
        ),
      ),
    );
  }
}

class _HeroPill extends StatelessWidget {
  const _HeroPill({
    required this.icon,
    required this.label,
    required this.foreground,
    required this.background,
  });

  final IconData icon;
  final String label;
  final Color foreground;
  final Color background;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 9, vertical: 6),
      decoration: BoxDecoration(
        color: background,
        borderRadius: BorderRadius.circular(999),
        border: Border.all(color: Colors.white.withValues(alpha: 0.2)),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(icon, color: foreground, size: 14),
          const SizedBox(width: 4),
          Text(
            label,
            style: TextStyle(
              color: foreground,
              fontSize: 11,
              fontWeight: FontWeight.w800,
            ),
          ),
        ],
      ),
    );
  }
}

class _SectionHeading extends StatelessWidget {
  const _SectionHeading({
    required this.eyebrow,
    required this.title,
  });

  final String eyebrow;
  final String title;

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          eyebrow.toUpperCase(),
          style: const TextStyle(
            color: _ProfilePalette.textSecondary,
            fontSize: 10.5,
            fontWeight: FontWeight.w800,
            letterSpacing: 0.6,
          ),
        ),
        const SizedBox(height: 3),
        Text(
          title,
          style: const TextStyle(
            color: _ProfilePalette.textPrimary,
            fontSize: 15,
            fontWeight: FontWeight.w800,
          ),
        ),
      ],
    );
  }
}

class _DetailRow extends StatelessWidget {
  const _DetailRow({
    required this.label,
    required this.value,
  });

  final String label;
  final String value;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(vertical: 9),
      decoration: const BoxDecoration(
        border: Border(
          bottom: BorderSide(
            color: _ProfilePalette.border,
          ),
        ),
      ),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SizedBox(
            width: 98,
            child: Text(
              label,
              style: const TextStyle(
                fontSize: 11.5,
                fontWeight: FontWeight.w600,
                color: _ProfilePalette.textSecondary,
              ),
            ),
          ),
          const SizedBox(width: 10),
          Expanded(
            child: Text(
              value,
              style: const TextStyle(
                fontSize: 12.5,
                fontWeight: FontWeight.w700,
                color: _ProfilePalette.textPrimary,
              ),
            ),
          ),
        ],
      ),
    );
  }
}

class _ProfileLoading extends StatelessWidget {
  const _ProfileLoading();

  @override
  Widget build(BuildContext context) {
    return const AppSectionCard(
      child: SizedBox(
        height: 220,
        child: Center(
          child: CircularProgressIndicator(
            color: _ProfilePalette.primary,
          ),
        ),
      ),
    );
  }
}

class _ProfileError extends StatelessWidget {
  const _ProfileError({
    required this.message,
    required this.onRetry,
  });

  final String message;
  final VoidCallback onRetry;

  @override
  Widget build(BuildContext context) {
    return AppSectionCard(
      child: Column(
        children: [
          const AppEmptyState(
            title: 'Profile belum bisa dimuat',
            message: 'Coba lagi beberapa saat lagi.',
            icon: Icons.error_outline_rounded,
          ),
          Text(
            message,
            textAlign: TextAlign.center,
            style: const TextStyle(
              fontSize: 12,
              color: _ProfilePalette.danger,
              fontWeight: FontWeight.w600,
            ),
          ),
          const SizedBox(height: 10),
          OutlinedButton(
            onPressed: onRetry,
            style: OutlinedButton.styleFrom(
              foregroundColor: _ProfilePalette.primaryDark,
              side: const BorderSide(color: _ProfilePalette.border),
            ),
            child: const Text('Coba Lagi'),
          ),
        ],
      ),
    );
  }
}

String _roleLabel(String? role) {
  switch (role) {
    case 'tenaga_pendidik':
      return 'Tenaga Pendidik';
    case 'pengurus':
      return 'Pengurus';
    default:
      return 'Pengguna';
  }
}

String? _cacheBustedAvatarUrl(String? url) {
  final value = url?.trim() ?? '';
  if (value.isEmpty) {
    return null;
  }

  final separator = value.contains('?') ? '&' : '?';
  return '$value${separator}t=${DateTime.now().millisecondsSinceEpoch}';
}
