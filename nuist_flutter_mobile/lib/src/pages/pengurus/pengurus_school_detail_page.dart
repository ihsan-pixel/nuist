part of 'pengurus_shell_page.dart';

class _SchoolDetailPage extends StatefulWidget {
  const _SchoolDetailPage({
    required this.repository,
    required this.schoolId,
  });

  final PengurusMobileRepository repository;
  final int schoolId;

  @override
  State<_SchoolDetailPage> createState() => _SchoolDetailPageState();
}

class _SchoolDetailPageState extends State<_SchoolDetailPage> {
  late Future<Map<String, dynamic>> _future;
  int _tabIndex = 0;

  @override
  void initState() {
    super.initState();
    _future = widget.repository.school(widget.schoolId);
  }

  Future<void> _refresh() async {
    setState(() {
      _future = widget.repository.school(widget.schoolId);
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF7F9FC),
      body: SafeArea(
        child: FutureBuilder<Map<String, dynamic>>(
          future: _future,
          builder: (context, snapshot) {
            if (snapshot.connectionState == ConnectionState.waiting) {
              return const Center(child: CircularProgressIndicator());
            }

            if (snapshot.hasError || !snapshot.hasData) {
              return _LoadError(
                onRetry: _refresh,
                title: 'Detail sekolah belum dapat dimuat',
                message:
                    'Data sekolah belum tersedia. Periksa koneksi lalu coba lagi.',
              );
            }

            final data = snapshot.data!;
            final school = Map<String, dynamic>.from(data['school'] as Map? ?? {});
            final headmaster = data['headmaster'] is Map
                ? Map<String, dynamic>.from(data['headmaster'] as Map)
                : null;
            final teachers = _updateItems(data['teachers']);
            final students = _updateItems(data['students']);
            final name = school['name']?.toString() ?? 'Sekolah';
            final scod = school['scod']?.toString() ?? '-';
            final accreditation = school['akreditasi']?.toString() ?? '-';
            final logoUrl = _schoolLogoUrl(school);
            final status = school['status']?.toString().trim().isNotEmpty == true
                ? school['status'].toString()
                : 'Aktif';

            return RefreshIndicator(
              onRefresh: _refresh,
              child: ListView(
                padding: EdgeInsets.zero,
                physics: const AlwaysScrollableScrollPhysics(),
                children: [
                  SizedBox(
                    height: 115,
                    child: Stack(
                      clipBehavior: Clip.none,
                      children: [
                        Positioned.fill(
                          child: CustomPaint(painter: _PremiumHeaderBackground()),
                        ),
                        Padding(
                          padding: EdgeInsets.only(
                            top: MediaQuery.paddingOf(context).top + 8,
                            left: 14,
                            right: 14,
                          ),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Row(
                                children: [
                                  IconButton(
                                    onPressed: () => Navigator.of(context).pop(),
                                    padding: const EdgeInsets.all(8),
                                    constraints: const BoxConstraints(
                                      minWidth: 42,
                                      minHeight: 42,
                                    ),
                                    splashRadius: 22,
                                    icon: const Icon(
                                      Icons.arrow_back_rounded,
                                      color: Colors.white,
                                      size: 25,
                                    ),
                                  ),
                                  const SizedBox(width: 5),
                                  const Expanded(
                                    child: Text(
                                      'Detail Sekolah',
                                      style: TextStyle(
                                        color: Colors.white,
                                        fontSize: 20,
                                        fontWeight: FontWeight.w700,
                                        letterSpacing: -0.35,
                                        height: 1.1,
                                      ),
                                    ),
                                  ),
                                ],
                              ),
                              Transform.translate(
                                offset: const Offset(0, -4),
                                child: const Padding(
                                  padding: EdgeInsets.only(left: 56),
                                  child: Text(
                                    'Informasi lengkap sekolah',
                                    style: TextStyle(
                                      color: Color(0xA8FFFFFF),
                                      fontSize: 11,
                                      fontWeight: FontWeight.w400,
                                      height: 1.1,
                                      letterSpacing: 0.05,
                                    ),
                                  ),
                                ),
                              ),
                            ],
                          ),
                        ),
                      ],
                    ),
                  ),
                  Transform.translate(
                    offset: const Offset(0, -28),
                    child: Container(
                      width: double.infinity,
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: const BorderRadius.vertical(
                          top: Radius.circular(34),
                        ),
                        boxShadow: [
                          BoxShadow(
                            color: Colors.black.withValues(alpha: 0.12),
                            blurRadius: 28,
                            offset: const Offset(0, -3),
                          ),
                        ],
                      ),
                      child: Padding(
                        padding: const EdgeInsets.fromLTRB(14, 10, 14, 18),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            _SchoolDetailHero(
                              name: name,
                              scod: scod,
                              accreditation: accreditation,
                              logoUrl: logoUrl,
                            ),
                            const SizedBox(height: 12),
                            _SchoolTabs(
                              index: _tabIndex,
                              onChanged: (index) =>
                                  setState(() => _tabIndex = index),
                            ),
                            const SizedBox(height: 12),
                            _InfoCard(
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                const Text(
                                  'Informasi Sekolah',
                                  style: TextStyle(
                                    color: Color(0xFF111827),
                                    fontSize: 18,
                                    fontWeight: FontWeight.w800,
                                  ),
                                ),
                                const SizedBox(height: 14),
                                _InfoRow(icon: Icons.school_outlined, label: 'Nama Sekolah', value: name),
                                _InfoRow(icon: Icons.qr_code_rounded, label: 'SCOD', value: scod),
                                _InfoRow(
                                  icon: Icons.location_city_outlined,
                                  label: 'Kabupaten',
                                  value: school['kabupaten']?.toString() ?? '-',
                                ),
                                _InfoRow(
                                  icon: Icons.apartment_outlined,
                                  label: 'Kecamatan',
                                  value: school['kecamatan']?.toString() ?? '-',
                                ),
                                _InfoRow(
                                  icon: Icons.location_on_outlined,
                                  label: 'Alamat',
                                  value: school['alamat']?.toString() ?? 'Alamat belum tersedia',
                                  multiline: true,
                                ),
                                _InfoRow(
                                  icon: Icons.verified_outlined,
                                  label: 'Status',
                                  value: status,
                                  valueChip: true,
                                ),
                                _InfoRow(
                                  icon: Icons.verified_rounded,
                                  label: 'Akreditasi',
                                  value: accreditation,
                                ),
                                const SizedBox(height: 12),
                                const Text(
                                  'Deskripsi',
                                  style: TextStyle(
                                    color: Color(0xFF111827),
                                    fontSize: 18,
                                    fontWeight: FontWeight.w800,
                                  ),
                                ),
                                const SizedBox(height: 8),
                                const Text(
                                  'Sekolah ini merupakan sekolah menengah kejuruan yang berkomitmen mencetak lulusan berkualitas dan berakhlak mulia.',
                                  style: TextStyle(
                                    color: Color(0xFF4B5563),
                                    fontSize: 14,
                                    height: 1.45,
                                  ),
                                ),
                                const SizedBox(height: 18),
                                Row(
                                  children: [
                                    Expanded(
                                      child: OutlinedButton(
                                        onPressed: null,
                                        style: OutlinedButton.styleFrom(
                                          minimumSize: const Size.fromHeight(52),
                                          side: const BorderSide(
                                            color: Color(0xFF7EC4B4),
                                            width: 1.5,
                                          ),
                                          shape: RoundedRectangleBorder(
                                            borderRadius: BorderRadius.circular(14),
                                          ),
                                        ),
                                        child: const Text(
                                          'Hubungi',
                                          style: TextStyle(
                                            color: Color(0xFF0E8F6E),
                                            fontSize: 15,
                                            fontWeight: FontWeight.w700,
                                          ),
                                        ),
                                      ),
                                    ),
                                    const SizedBox(width: 14),
                                    Expanded(
                                      flex: 2,
                                      child: FilledButton(
                                        onPressed: null,
                                        style: FilledButton.styleFrom(
                                          backgroundColor: const Color(0xFF0E8F6E),
                                          minimumSize: const Size.fromHeight(52),
                                          shape: RoundedRectangleBorder(
                                            borderRadius: BorderRadius.circular(14),
                                          ),
                                        ),
                                        child: const Text(
                                          'Lihat Lokasi',
                                          style: TextStyle(
                                            fontSize: 15,
                                            fontWeight: FontWeight.w700,
                                          ),
                                        ),
                                      ),
                                    ),
                                  ],
                                ),
                                if (headmaster != null ||
                                    teachers.isNotEmpty ||
                                    students.isNotEmpty) ...[
                                  const SizedBox(height: 22),
                                  const _SchoolDetailSectionHeading(
                                    eyebrow: 'RINGKASAN',
                                    title: 'Data Sekolah',
                                  ),
                                  const SizedBox(height: 9),
                                  _SchoolSummaryStats(
                                    headmasterName:
                                        headmaster?['name']?.toString(),
                                    teacherCount: teachers.length,
                                    studentCount: students.length,
                                  ),
                                ],
                                ],
                              ),
                            ),
                          ],
                        ),
                      ),
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

class _DetailEmpty extends StatelessWidget {
  const _DetailEmpty({required this.label});
  final String label;
  @override
  Widget build(BuildContext context) {
    return AppSectionCard(
      padding: const EdgeInsets.all(14),
      child: Text(
        label,
        style: const TextStyle(
          color: Color(0xFF64748B),
          fontSize: 12,
        ),
      ),
    );
  }
}

class _SchoolTabs extends StatelessWidget {
  const _SchoolTabs({required this.index, required this.onChanged});
  final int index;
  final ValueChanged<int> onChanged;
  @override
  Widget build(BuildContext context) {
    const labels = ['Informasi', 'Pengurus', 'Kontak', 'Statistik'];
    return Row(
      children: List.generate(labels.length, (i) {
        final selected = i == index;
        return Expanded(
          child: GestureDetector(
            onTap: () => onChanged(i),
            child: Column(
              children: [
                Padding(
                  padding: const EdgeInsets.symmetric(vertical: 12),
                  child: Text(
                    labels[i],
                    textAlign: TextAlign.center,
                    style: TextStyle(
                      color: selected ? const Color(0xFF0E8F6E) : const Color(0xFF8B95A7),
                      fontSize: 15,
                      fontWeight: FontWeight.w700,
                    ),
                  ),
                ),
                Container(
                  height: 3,
                  decoration: BoxDecoration(
                    color: selected ? const Color(0xFF0E8F6E) : Colors.transparent,
                    borderRadius: BorderRadius.circular(999),
                  ),
                ),
              ],
            ),
          ),
        );
      }),
    );
  }
}

class _InfoCard extends StatelessWidget {
  const _InfoCard({required this.child});
  final Widget child;
  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(18),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(24),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(alpha: 0.05),
            blurRadius: 18,
            offset: const Offset(0, 8),
          ),
        ],
      ),
      child: child,
    );
  }
}

class _InfoRow extends StatelessWidget {
  const _InfoRow({
    required this.icon,
    required this.label,
    required this.value,
    this.multiline = false,
    this.valueChip = false,
  });
  final IconData icon;
  final String label;
  final String value;
  final bool multiline;
  final bool valueChip;
  @override
  Widget build(BuildContext context) {
    final valueWidget = valueChip
        ? _Pill(text: value, background: const Color(0xFFE4F7ED), foreground: const Color(0xFF0E8F6E))
        : Text(
            value,
            textAlign: TextAlign.right,
            maxLines: multiline ? 2 : 1,
            overflow: TextOverflow.ellipsis,
            style: const TextStyle(
              color: Color(0xFF111827),
              fontSize: 14,
              fontWeight: FontWeight.w700,
            ),
          );
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 11),
      child: Row(
        crossAxisAlignment: multiline ? CrossAxisAlignment.start : CrossAxisAlignment.center,
        children: [
          Icon(icon, size: 20, color: const Color(0xFF94A3B8)),
          const SizedBox(width: 12),
          Expanded(
            child: Text(
              label,
              style: const TextStyle(
                color: Color(0xFF8B95A7),
                fontSize: 14,
                fontWeight: FontWeight.w600,
              ),
            ),
          ),
          const SizedBox(width: 12),
          Flexible(child: valueWidget),
        ],
      ),
    );
  }
}

class _Pill extends StatelessWidget {
  const _Pill({
    required this.text,
    required this.background,
    required this.foreground,
  });
  final String text;
  final Color background;
  final Color foreground;
  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
      decoration: BoxDecoration(
        color: background,
        borderRadius: BorderRadius.circular(999),
      ),
      child: Text(
        text,
        style: TextStyle(color: foreground, fontSize: 13, fontWeight: FontWeight.w700),
      ),
    );
  }
}

class _SchoolDetailHero extends StatelessWidget {
  const _SchoolDetailHero({
    required this.name,
    required this.scod,
    required this.accreditation,
    required this.logoUrl,
  });
  final String name;
  final String scod;
  final String accreditation;
  final String? logoUrl;
  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.fromLTRB(14, 14, 14, 16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(28),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(alpha: 0.08),
            blurRadius: 24,
            offset: const Offset(0, 10),
          ),
        ],
      ),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            width: 80,
            height: 80,
            padding: const EdgeInsets.all(5),
            decoration: BoxDecoration(
              color: const Color(0xFFF4F7F6),
              borderRadius: BorderRadius.circular(18),
            ),
            child: ClipRRect(
              borderRadius: BorderRadius.circular(14),
              child: logoUrl != null && logoUrl!.isNotEmpty
                  ? Image.network(
                      logoUrl!,
                      fit: BoxFit.contain,
                      errorBuilder: (_, __, ___) => const _SchoolLogoFallback(),
                    )
                  : const _SchoolLogoFallback(),
            ),
          ),
          const SizedBox(width: 14),
          Expanded(
            child: Padding(
              padding: const EdgeInsets.only(top: 2),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    name,
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                    style: const TextStyle(
                      color: Color(0xFF111827),
                      fontSize: 20,
                      height: 1.12,
                      fontWeight: FontWeight.w800,
                    ),
                  ),
                  const SizedBox(height: 9),
                  _Pill(
                    text: 'Terakreditasi $accreditation',
                    background: const Color(0xFFE4F7ED),
                    foreground: const Color(0xFF0E8F6E),
                  ),
                  const SizedBox(height: 9),
                  Text(
                    'SCOD$scod',
                    style: const TextStyle(
                      color: Color(0xFF8B95A7),
                      fontSize: 12.5,
                      fontWeight: FontWeight.w600,
                    ),
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }
}

class _SchoolSummaryStats extends StatelessWidget {
  const _SchoolSummaryStats({
    required this.headmasterName,
    required this.teacherCount,
    required this.studentCount,
  });
  final String? headmasterName;
  final int teacherCount;
  final int studentCount;
  @override
  Widget build(BuildContext context) {
    return AppSectionCard(
      padding: const EdgeInsets.all(14),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          _InfoRow(
            icon: Icons.person_rounded,
            label: 'Kepala Sekolah',
            value: headmasterName?.trim().isNotEmpty == true ? headmasterName! : 'Belum ditetapkan',
          ),
          _InfoRow(
            icon: Icons.groups_rounded,
            label: 'Jumlah Guru',
            value: '$teacherCount',
          ),
          _InfoRow(
            icon: Icons.groups_2_rounded,
            label: 'Jumlah Siswa',
            value: '$studentCount',
          ),
        ],
      ),
    );
  }
}

class _SchoolDetailSectionHeading extends StatelessWidget {
  const _SchoolDetailSectionHeading({
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
          eyebrow,
          style: const TextStyle(
            color: _pengurusPrimary,
            fontSize: 9,
            fontWeight: FontWeight.w800,
            letterSpacing: 0.8,
          ),
        ),
        const SizedBox(height: 2),
        Text(
          title,
          style: const TextStyle(
            color: _pengurusText,
            fontSize: 16,
            fontWeight: FontWeight.w800,
          ),
        ),
      ],
    );
  }
}

class _SchoolDataTable extends StatelessWidget {
  const _SchoolDataTable({
    required this.headers,
    required this.rows,
  });
  final List<String> headers;
  final List<List<String>> rows;
  @override
  Widget build(BuildContext context) {
    return AppSectionCard(
      padding: EdgeInsets.zero,
      child: Column(
        children: [
          Container(
            width: double.infinity,
            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
            decoration: const BoxDecoration(
              color: Color(0xFFE5F5F0),
              borderRadius: BorderRadius.vertical(top: Radius.circular(23)),
            ),
            child: _SchoolDataRow(values: headers, isHeader: true),
          ),
          ...rows.asMap().entries.map(
                (entry) => Container(
                  padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
                  decoration: BoxDecoration(
                    border: entry.key == rows.length - 1
                        ? null
                        : const Border(
                            bottom: BorderSide(color: Color(0xFFDCE7E3)),
                          ),
                  ),
                  child: _SchoolDataRow(values: entry.value),
                ),
              ),
        ],
      ),
    );
  }
}

class _SchoolDataRow extends StatelessWidget {
  const _SchoolDataRow({
    required this.values,
    this.isHeader = false,
  });
  final List<String> values;
  final bool isHeader;
  @override
  Widget build(BuildContext context) {
    final style = TextStyle(
      color: isHeader ? const Color(0xFF00553F) : _pengurusText,
      fontSize: isHeader ? 9 : 11,
      fontWeight: isHeader ? FontWeight.w800 : FontWeight.w700,
      height: 1.25,
    );
    return Row(
      children: [
        Expanded(
          flex: 3,
          child: Text(
            values.isNotEmpty ? values.first : '-',
            maxLines: 2,
            overflow: TextOverflow.ellipsis,
            style: style,
          ),
        ),
        const SizedBox(width: 10),
        Expanded(
          flex: 2,
          child: Text(
            values.length > 1 ? values[1] : '-',
            textAlign: TextAlign.right,
            maxLines: 2,
            overflow: TextOverflow.ellipsis,
            style: style,
          ),
        ),
      ],
    );
  }
}
