import 'package:flutter/material.dart';
import 'package:flutter/services.dart';

import '../../services/teacher_mobile_repository.dart';
import '../../widgets/app/app_empty_state.dart';
import '../../widgets/app/app_section_card.dart';

class TeacherProfilePage extends StatefulWidget {
  const TeacherProfilePage({
    super.key,
    required this.repository,
    required this.onOpenIzin,
    required this.onBackToHome,
  });

  final TeacherMobileRepository repository;
  final Future<void> Function() onOpenIzin;
  final VoidCallback onBackToHome;

  @override
  State<TeacherProfilePage> createState() => _TeacherProfilePageState();
}

class _TeacherProfilePageState extends State<TeacherProfilePage> {
  late Future<Map<String, dynamic>> _future;

  @override
  void initState() {
    super.initState();
    _future = widget.repository.getProfile();
  }

  Future<void> _refresh() async {
    final future = widget.repository.getProfile();
    setState(() {
      _future = future;
    });
    await future;
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
            return RefreshIndicator(
              onRefresh: _refresh,
              child: ListView(
                physics: const AlwaysScrollableScrollPhysics(),
                padding: const EdgeInsets.fromLTRB(16, 14, 16, 28),
                children: [
                  _ProfileTopHeader(
                    onBack: widget.onBackToHome,
                  ),
                  const SizedBox(height: 16),
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
                      onOpenIzin: widget.onOpenIzin,
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
  });

  final VoidCallback onBack;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.fromLTRB(14, 14, 14, 14),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(28),
        boxShadow: const [
          BoxShadow(
            color: Color(0x0F003B39),
            blurRadius: 18,
            offset: Offset(0, 8),
          ),
        ],
        border: const Border.fromBorderSide(
          BorderSide(
            color: Color(0xFFE6EEEE),
          ),
        ),
      ),
      child: Row(
        children: [
          Material(
            color: const Color(0xFFFFF4E8),
            borderRadius: BorderRadius.circular(16),
            child: InkWell(
              onTap: onBack,
              borderRadius: BorderRadius.circular(16),
              child: const SizedBox(
                width: 42,
                height: 42,
                child: Icon(
                  Icons.arrow_back_ios_new_rounded,
                  color: Color(0xFFF49637),
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
                color: Color(0xFF7A4212),
                fontSize: 19,
                fontWeight: FontWeight.w800,
              ),
            ),
          ),
          Container(
            width: 42,
            height: 42,
            alignment: Alignment.center,
            child: Container(
              width: 8,
              height: 8,
              decoration: const BoxDecoration(
                color: Color(0xFFF49637),
                shape: BoxShape.circle,
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
    required this.onOpenIzin,
  });

  final Map<String, dynamic> data;
  final Future<void> Function() onOpenIzin;

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
          avatarUrl: (user['avatar_url'] as String?)?.trim(),
          detailCount: details.length,
          activityCount: activities.length,
          membershipCount: memberships.length,
        ),
        const SizedBox(height: 16),
        AppSectionCard(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const _SectionHeading(
                eyebrow: 'Layanan',
                title: 'Akses Cepat',
              ),
              const SizedBox(height: 14),
              Row(
                children: [
                  Expanded(
                    child: _ActionShortcut(
                      icon: Icons.receipt_long_rounded,
                      label: 'Izin',
                      caption: 'Buka',
                      accent: const Color(0xFFF49637),
                      background: const Color(0xFFFFF4E8),
                      onTap: () {
                        onOpenIzin();
                      },
                    ),
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    child: _ActionShortcut(
                      icon: Icons.badge_rounded,
                      label: 'Profil',
                      caption: '${details.length} data',
                      accent: const Color(0xFFF49637),
                      background: const Color(0xFFFFF4E8),
                    ),
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    child: _ActionShortcut(
                      icon: Icons.event_note_rounded,
                      label: 'Agenda',
                      caption: '${activities.length} item',
                      accent: const Color(0xFFF49637),
                      background: const Color(0xFFFFF6EC),
                    ),
                  ),
                ],
              ),
            ],
          ),
        ),
        const SizedBox(height: 16),
        AppSectionCard(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const _SectionHeading(
                eyebrow: 'Data Utama',
                title: 'Informasi Profil',
              ),
              const SizedBox(height: 14),
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
        const SizedBox(height: 16),
        if (memberships.isNotEmpty) ...[
          AppSectionCard(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const _SectionHeading(
                  eyebrow: 'Komunitas',
                  title: 'Keanggotaan MGMP',
                ),
                const SizedBox(height: 14),
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
          const SizedBox(height: 16),
        ],
        AppSectionCard(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const _SectionHeading(
                eyebrow: 'Aktivitas',
                title: 'Agenda Terdekat',
              ),
              const SizedBox(height: 14),
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
    required this.detailCount,
    required this.activityCount,
    required this.membershipCount,
  });

  final String name;
  final String role;
  final String? schoolName;
  final String? email;
  final String? avatarUrl;
  final int detailCount;
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
            Color(0xFFF49637),
            Color(0xFFC96A19),
          ],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
        borderRadius: BorderRadius.circular(28),
        boxShadow: const [
          BoxShadow(
            color: Color(0x16003B39),
            blurRadius: 24,
            offset: Offset(0, 10),
          ),
        ],
      ),
      child: Padding(
        padding: const EdgeInsets.all(18),
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
                  ),
                  child: CircleAvatar(
                    radius: 31,
                    backgroundColor: Colors.white,
                    backgroundImage:
                        avatarUrl != null && avatarUrl!.isNotEmpty
                            ? NetworkImage(avatarUrl!)
                            : null,
                    child: avatarUrl == null || avatarUrl!.isEmpty
                        ? const Icon(
                            Icons.person_rounded,
                            size: 34,
                            color: Color(0xFF90A4A3),
                          )
                        : null,
                  ),
                ),
                const SizedBox(width: 14),
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
                          fontSize: 22,
                          fontWeight: FontWeight.w800,
                          height: 1.15,
                        ),
                      ),
                      const SizedBox(height: 6),
                      Text(
                        subtitleParts.join(' • '),
                        maxLines: 2,
                        overflow: TextOverflow.ellipsis,
                        style: TextStyle(
                          color: Colors.white.withOpacity(0.82),
                          fontSize: 13,
                          fontWeight: FontWeight.w600,
                        ),
                      ),
                      if (email != null && email!.isNotEmpty) ...[
                        const SizedBox(height: 6),
                        Text(
                          email!,
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                          style: TextStyle(
                            color: Colors.white.withOpacity(0.72),
                            fontSize: 12,
                          ),
                        ),
                      ],
                    ],
                  ),
                ),
              ],
            ),
            const SizedBox(height: 16),
            const Wrap(
              spacing: 8,
              runSpacing: 8,
              children: [
                _HeroPill(
                  icon: Icons.verified_rounded,
                  label: 'Verified Account',
                  foreground: Color(0xFFF49637),
                  background: Color(0xFFFFF4E8),
                ),
                _HeroPill(
                  icon: Icons.star_rounded,
                  label: '4.8 Rating',
                  foreground: Color(0xFFA65612),
                  background: Color(0xFFFFF4E8),
                ),
              ],
            ),
            const SizedBox(height: 18),
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 14),
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
                      label: 'Profil',
                      value: '$detailCount',
                    ),
                  ),
                  Container(
                    width: 1,
                    height: 38,
                    color: Colors.white.withOpacity(0.18),
                  ),
                  Expanded(
                    child: _HeroStat(
                      label: 'Agenda',
                      value: '$activityCount',
                    ),
                  ),
                  Container(
                    width: 1,
                    height: 38,
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
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 7),
      decoration: BoxDecoration(
        color: background,
        borderRadius: BorderRadius.circular(999),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(icon, color: foreground, size: 15),
          const SizedBox(width: 5),
          Text(
            label,
            style: TextStyle(
              color: foreground,
              fontSize: 12,
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
            fontSize: 19,
            fontWeight: FontWeight.w800,
          ),
        ),
        const SizedBox(height: 4),
        Text(
          label,
          style: TextStyle(
            color: Colors.white.withOpacity(0.78),
            fontSize: 12,
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
            color: Color(0xFFF49637),
            fontSize: 11,
            fontWeight: FontWeight.w800,
            letterSpacing: 0.6,
          ),
        ),
        const SizedBox(height: 4),
        Text(
          title,
          style: const TextStyle(
            color: Color(0xFF7A4212),
            fontSize: 17,
            fontWeight: FontWeight.w800,
          ),
        ),
      ],
    );
  }
}

class _ActionShortcut extends StatelessWidget {
  const _ActionShortcut({
    required this.icon,
    required this.label,
    required this.caption,
    required this.accent,
    required this.background,
    this.onTap,
  });

  final IconData icon;
  final String label;
  final String caption;
  final Color accent;
  final Color background;
  final VoidCallback? onTap;

  @override
  Widget build(BuildContext context) {
    final content = Container(
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 14),
      decoration: BoxDecoration(
        color: background,
        borderRadius: BorderRadius.circular(20),
      ),
      child: Column(
        children: [
          Container(
            width: 42,
            height: 42,
            decoration: BoxDecoration(
              color: Colors.white.withOpacity(0.88),
              borderRadius: BorderRadius.circular(14),
            ),
            child: Icon(icon, color: accent, size: 21),
          ),
          const SizedBox(height: 10),
          Text(
            label,
            style: const TextStyle(
              color: Color(0xFF7A4212),
              fontSize: 13,
              fontWeight: FontWeight.w800,
            ),
          ),
          const SizedBox(height: 3),
          Text(
            caption,
            maxLines: 1,
            overflow: TextOverflow.ellipsis,
            style: const TextStyle(
              color: Color(0xFF7C8F8D),
              fontSize: 11,
              fontWeight: FontWeight.w600,
            ),
          ),
        ],
      ),
    );

    if (onTap == null) {
      return content;
    }

    return Material(
      color: Colors.transparent,
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(20),
        child: content,
      ),
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
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 9),
      decoration: BoxDecoration(
        color: const Color(0xFFFFF4E8),
        borderRadius: BorderRadius.circular(999),
      ),
      child: Text(
        label,
        style: const TextStyle(
          color: Color(0xFFF49637),
          fontSize: 12,
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
      padding: const EdgeInsets.symmetric(vertical: 12),
      decoration: const BoxDecoration(
        border: Border(
          bottom: BorderSide(
            color: Color(0xFFF0F2F2),
          ),
        ),
      ),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SizedBox(
            width: 112,
            child: Text(
              label,
              style: const TextStyle(
                fontSize: 12,
                fontWeight: FontWeight.w600,
                color: Color(0xFF7B8E8D),
              ),
            ),
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Text(
              value,
              style: const TextStyle(
                fontSize: 13,
                fontWeight: FontWeight.w700,
                color: Color(0xFF1F1F1F),
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
      padding: const EdgeInsets.symmetric(vertical: 10),
      decoration: const BoxDecoration(
        border: Border(
          bottom: BorderSide(
            color: Color(0xFFF0F2F2),
          ),
        ),
      ),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            width: 42,
            height: 42,
            decoration: BoxDecoration(
              color: const Color(0xFFFFF4E8),
              borderRadius: BorderRadius.circular(14),
            ),
            child: const Icon(
              Icons.event_available_rounded,
              color: Color(0xFFF49637),
              size: 20,
            ),
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  title,
                  style: const TextStyle(
                    fontSize: 13,
                    fontWeight: FontWeight.w700,
                    color: Color(0xFF1F1F1F),
                  ),
                ),
                const SizedBox(height: 4),
                Text(
                  subtitle,
                  style: const TextStyle(
                    fontSize: 12,
                    color: Color(0xFF6D7F7D),
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
        height: 260,
        child: Center(
          child: CircularProgressIndicator(
            color: Color(0xFFF49637),
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
              color: Color(0xFFB42318),
              fontWeight: FontWeight.w600,
            ),
          ),
          const SizedBox(height: 14),
          OutlinedButton(
            onPressed: onRetry,
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
