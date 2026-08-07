import 'dart:async';

import 'package:flutter/material.dart';
import 'package:flutter/services.dart';

import '../../services/teacher_mobile_repository.dart';
import '../../widgets/app/app_empty_state.dart';
import '../../widgets/app/app_section_card.dart';
import 'profile_change_password_page.dart';
import 'profile_settings_page.dart';

class _ProfilePalette {
  static const surface = Color(0xFFFFFFFF);
  static const primary = Color(0xFF0B8F6E);
  static const primaryDark = Color(0xFF066C56);
  static const accent = Color(0xFFF5B301);
  static const textPrimary = Color(0xFF1E293B);
  static const textSecondary = Color(0xFF64748B);
  static const border = Color(0xFFE2E8F0);
  static const iconSurface = Color(0xFFECFDF5);
  static const softGreen = Color(0xFFDCFCE7);
  static const softYellow = Color(0xFFFEF3C7);
  static const danger = Color(0xFFEF4444);
  static const shadow = Color(0x141E293B);

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

            return RefreshIndicator(
              onRefresh: _refresh,
              child: ListView(
                physics: const AlwaysScrollableScrollPhysics(),
                padding: EdgeInsets.fromLTRB(14, 12, 14, bottomNavInset),
                children: [
                  _ProfileTopHeader(
                    onBack: widget.onBackToHome,
                    onOpenSettings: snapshot.hasData
                        ? () => _openSettings(
                              snapshot.data ?? const <String, dynamic>{},
                            )
                        : null,
                  ),
                  const SizedBox(height: 12),
                  if (snapshot.connectionState == ConnectionState.waiting)
                    const _ProfileLoading()
                  else if (snapshot.hasError)
                    _ProfileError(
                      message: snapshot.error.toString(),
                      onRetry: _refresh,
                    )
                  else
                    _ProfileContent(
                      data: snapshot.data ?? const <String, dynamic>{},
                      onOpenSettings: () => _openSettings(
                        snapshot.data ?? const <String, dynamic>{},
                      ),
                    ),
                ],
              ),
            );
          },
        ),
      ),
    );
  }
}

class _ProfileTopHeader extends StatelessWidget {
  const _ProfileTopHeader({
    required this.onBack,
    this.onOpenSettings,
  });

  final VoidCallback onBack;
  final VoidCallback? onOpenSettings;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.fromLTRB(12, 12, 12, 12),
      decoration: BoxDecoration(
        color: _ProfilePalette.surface,
        borderRadius: BorderRadius.circular(28),
        boxShadow: const [
          BoxShadow(
            color: _ProfilePalette.shadow,
            blurRadius: 18,
            offset: Offset(0, 8),
          ),
        ],
        border: const Border.fromBorderSide(
          BorderSide(
            color: _ProfilePalette.border,
          ),
        ),
      ),
      child: Row(
        children: [
          Material(
            color: _ProfilePalette.softYellow,
            borderRadius: BorderRadius.circular(14),
            child: InkWell(
              onTap: onBack,
              borderRadius: BorderRadius.circular(14),
              child: const SizedBox(
                width: 38,
                height: 38,
                child: Icon(
                  Icons.arrow_back_ios_new_rounded,
                  color: _ProfilePalette.accent,
                  size: 18,
                ),
              ),
            ),
          ),
          const Expanded(
            child: Text(
              'Profile',
              textAlign: TextAlign.center,
              style: TextStyle(
                color: _ProfilePalette.textPrimary,
                fontSize: 18,
                fontWeight: FontWeight.w800,
              ),
            ),
          ),
          Material(
            color: _ProfilePalette.iconSurface,
            borderRadius: BorderRadius.circular(14),
            child: InkWell(
              onTap: onOpenSettings,
              borderRadius: BorderRadius.circular(14),
              child: SizedBox(
                width: 38,
                height: 38,
                child: Icon(
                  Icons.settings_outlined,
                  color: onOpenSettings == null
                      ? _ProfilePalette.textSecondary
                      : _ProfilePalette.primary,
                  size: 20,
                ),
              ),
            ),
          ),
        ],
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
    final activities = ((data['upcoming_activities'] as List?) ?? const [])
        .whereType<Map>()
        .map((item) => Map<String, dynamic>.from(item))
        .toList();
    final memberships = ((data['mgmp_memberships'] as List?) ?? const [])
        .whereType<Map>()
        .map((item) => Map<String, dynamic>.from(item))
        .toList();

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
          activityCount: activities.length,
          membershipCount: memberships.length,
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
                    label: item['label'] as String? ?? '-',
                    value: item['value'] as String? ?? '-',
                  ),
                ),
            ],
          ),
        ),
        const SizedBox(height: 12),
        if (memberships.isNotEmpty) ...[
          AppSectionCard(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const _SectionHeading(
                  eyebrow: 'Komunitas',
                  title: 'Keanggotaan MGMP',
                ),
                const SizedBox(height: 10),
                Wrap(
                  spacing: 10,
                  runSpacing: 10,
                  children: memberships
                      .take(4)
                      .map(
                        (item) => _MembershipChip(
                          label: item['group_name'] as String? ?? 'MGMP',
                        ),
                      )
                      .toList(),
                ),
              ],
            ),
          ),
          const SizedBox(height: 12),
        ],
        AppSectionCard(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const _SectionHeading(
                eyebrow: 'Aktivitas',
                title: 'Agenda Terdekat',
              ),
              const SizedBox(height: 10),
              if (activities.isEmpty)
                const AppEmptyState(
                  title: 'Belum ada agenda',
                  message: 'Tidak ada agenda terdekat untuk saat ini.',
                  icon: Icons.event_note_rounded,
                )
              else
                ...activities.take(3).map(
                      (item) => _AgendaRow(
                        title: item['title'] as String? ?? 'Agenda',
                        subtitle: _activitySubtitle(item),
                      ),
                    ),
            ],
          ),
        ),
      ],
    );
  }

  static String _activitySubtitle(Map<String, dynamic> item) {
    final date = (item['date_label'] as String?)?.trim() ?? '';
    final time = (item['time_label'] as String?)?.trim() ?? '';
    if (date.isEmpty && time.isEmpty) {
      return 'Jadwal belum tersedia';
    }
    if (date.isNotEmpty && time.isNotEmpty) {
      return '$date • $time';
    }
    return date.isNotEmpty ? date : time;
  }
}

class _ProfileHeroCard extends StatelessWidget {
  const _ProfileHeroCard({
    required this.name,
    required this.role,
    required this.schoolName,
    required this.email,
    required this.avatarUrl,
    required this.activityCount,
    required this.membershipCount,
  });

  final String name;
  final String role;
  final String? schoolName;
  final String? email;
  final String? avatarUrl;
  final int activityCount;
  final int membershipCount;

  @override
  Widget build(BuildContext context) {
    final subtitleParts = [
      role,
      if (schoolName != null && schoolName!.isNotEmpty) schoolName!,
    ];

    return Container(
      decoration: BoxDecoration(
        gradient: const LinearGradient(
          colors: [
            _ProfilePalette.primaryDark,
            _ProfilePalette.primary,
          ],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
        borderRadius: BorderRadius.circular(28),
        boxShadow: const [
          BoxShadow(
            color: _ProfilePalette.shadow,
            blurRadius: 24,
            offset: Offset(0, 10),
          ),
        ],
      ),
      child: Padding(
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
                    color: Colors.white.withOpacity(0.2),
                    shape: BoxShape.circle,
                    border: Border.all(
                      color: Colors.white.withOpacity(0.14),
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
                          color: Colors.white,
                          fontSize: 19,
                          fontWeight: FontWeight.w800,
                          height: 1.15,
                        ),
                      ),
                      const SizedBox(height: 4),
                      Text(
                        subtitleParts.join(' • '),
                        maxLines: 2,
                        overflow: TextOverflow.ellipsis,
                        style: TextStyle(
                          color: Colors.white.withOpacity(0.82),
                          fontSize: 12,
                          fontWeight: FontWeight.w600,
                        ),
                      ),
                      if (email != null && email!.isNotEmpty) ...[
                        const SizedBox(height: 4),
                        Text(
                          email!,
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                          style: TextStyle(
                            color: Colors.white.withOpacity(0.72),
                            fontSize: 11,
                          ),
                        ),
                      ],
                    ],
                  ),
                ),
              ],
            ),
            const SizedBox(height: 10),
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
            const SizedBox(height: 12),
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
              decoration: BoxDecoration(
                color: Colors.white.withOpacity(0.14),
                borderRadius: BorderRadius.circular(20),
                border: Border.all(
                  color: Colors.white.withOpacity(0.14),
                ),
              ),
              child: Row(
                children: [
                  Expanded(
                    child: _HeroStat(
                      label: 'Agenda',
                      value: '$activityCount',
                    ),
                  ),
                  Container(
                    width: 1,
                    height: 32,
                    color: Colors.white.withOpacity(0.18),
                  ),
                  Expanded(
                    child: _HeroStat(
                      label: 'MGMP',
                      value: '$membershipCount',
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
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
        border: Border.all(color: Colors.white.withOpacity(0.2)),
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

class _HeroStat extends StatelessWidget {
  const _HeroStat({
    required this.label,
    required this.value,
  });

  final String label;
  final String value;

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Text(
          value,
          style: const TextStyle(
            color: Colors.white,
            fontSize: 17,
            fontWeight: FontWeight.w800,
          ),
        ),
        const SizedBox(height: 2),
        Text(
          label,
          style: TextStyle(
            color: Colors.white.withOpacity(0.78),
            fontSize: 11,
            fontWeight: FontWeight.w600,
          ),
        ),
      ],
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

class _MembershipChip extends StatelessWidget {
  const _MembershipChip({
    required this.label,
  });

  final String label;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 7),
      decoration: BoxDecoration(
        color: _ProfilePalette.iconSurface,
        borderRadius: BorderRadius.circular(999),
        border: Border.all(color: _ProfilePalette.border),
      ),
      child: Text(
        label,
        style: const TextStyle(
          color: _ProfilePalette.primaryDark,
          fontSize: 11,
          fontWeight: FontWeight.w700,
        ),
      ),
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

class _AgendaRow extends StatelessWidget {
  const _AgendaRow({
    required this.title,
    required this.subtitle,
  });

  final String title;
  final String subtitle;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(vertical: 8),
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
          Container(
            width: 38,
            height: 38,
            decoration: BoxDecoration(
              color: _ProfilePalette.iconSurface,
              borderRadius: BorderRadius.circular(12),
              border: Border.all(color: _ProfilePalette.border),
            ),
            child: const Icon(
              Icons.event_available_rounded,
              color: _ProfilePalette.primary,
              size: 18,
            ),
          ),
          const SizedBox(width: 10),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  title,
                  style: const TextStyle(
                    fontSize: 12.5,
                    fontWeight: FontWeight.w700,
                    color: _ProfilePalette.textPrimary,
                  ),
                ),
                const SizedBox(height: 3),
                Text(
                  subtitle,
                  style: const TextStyle(
                    fontSize: 11.5,
                    color: _ProfilePalette.textSecondary,
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
