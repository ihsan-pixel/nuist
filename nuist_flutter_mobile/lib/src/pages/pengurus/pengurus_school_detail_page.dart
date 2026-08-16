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
  int _reloadTick = 0;
  int _tabIndex = 0;

  Future<void> _refresh() async {
    setState(() {
      _reloadTick++;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF7F9FC),
      body: SafeArea(
        child: FutureBuilder<Map<String, dynamic>>(
          key: ValueKey(_reloadTick),
          future: widget.repository.school(widget.schoolId),
          builder: (context, snapshot) {
            if (snapshot.connectionState == ConnectionState.waiting) {
              return const Center(child: CircularProgressIndicator());
            }

            if (snapshot.hasError || !snapshot.hasData) {
              return _LoadError(
                onRetry: _refresh,
                title: 'Detail sekolah belum dapat dimuat',
                message: 'Data sekolah belum tersedia. Periksa koneksi lalu coba lagi.',
              );
            }

            final data = snapshot.data!;
            final school = Map<String, dynamic>.from(data['school'] as Map? ?? {});
            final headmaster = data['headmaster'] is Map
                ? Map<String, dynamic>.from(data['headmaster'] as Map)
                : null;
            final teachers = _updateItems(data['teachers']);
            final students = _updateItems(data['students']);
            final teacherCount = (data['teacher_count'] as num?)?.toInt() ?? teachers.length;
            final studentCount = (data['student_count'] as num?)?.toInt() ?? students.length;
            final classSummary = _studentClassSummary(students);
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
                    height: 118,
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
                                    constraints: const BoxConstraints(minWidth: 42, minHeight: 42),
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
                                      fontSize: 14,
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
                    offset: const Offset(0, -30),
                    child: Container(
                      width: double.infinity,
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: const BorderRadius.vertical(top: Radius.circular(34)),
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
                              onChanged: (index) => setState(() => _tabIndex = index),
                            ),
                            const SizedBox(height: 12),
                            _DetailTabContent(
                              tabIndex: _tabIndex,
                              name: name,
                              scod: scod,
                              school: school,
                              status: status,
                              accreditation: accreditation,
                              teacherCount: teacherCount,
                              studentCount: studentCount,
                              classSummary: classSummary,
                              headmaster: headmaster,
                              teachers: teachers,
                              students: students,
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

class _DetailTabContent extends StatelessWidget {
  const _DetailTabContent({
    required this.tabIndex,
    required this.name,
    required this.scod,
    required this.school,
    required this.status,
    required this.accreditation,
    required this.teacherCount,
    required this.studentCount,
    required this.classSummary,
    required this.headmaster,
    required this.teachers,
    required this.students,
  });

  final int tabIndex;
  final String name;
  final String scod;
  final Map<String, dynamic> school;
  final String status;
  final String accreditation;
  final int teacherCount;
  final int studentCount;
  final List<MapEntry<String, int>> classSummary;
  final Map<String, dynamic>? headmaster;
  final List<Map<String, dynamic>> teachers;
  final List<Map<String, dynamic>> students;

  @override
  Widget build(BuildContext context) {
    switch (tabIndex) {
      case 1:
        return _TeacherStaffTab(headmaster: headmaster, teachers: teachers);
      case 2:
        return _StudentTab(students: students, classSummary: classSummary, studentCount: studentCount);
      case 3:
        return _ContactTab(name: name, scod: scod, school: school);
      default:
        return _SchoolInfoTab(
          name: name,
          scod: scod,
          school: school,
          status: status,
          accreditation: accreditation,
          teacherCount: teacherCount,
          studentCount: studentCount,
          classSummary: classSummary,
        );
    }
  }
}

class _SchoolTabs extends StatelessWidget {
  const _SchoolTabs({required this.index, required this.onChanged});

  final int index;
  final ValueChanged<int> onChanged;

  @override
  Widget build(BuildContext context) {
    const labels = ['Informasi', 'Guru & Pegawai', 'Siswa', 'Kontak'];
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
                      fontSize: 12,
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
      padding: const EdgeInsets.all(16),
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
        ? _Pill(
            text: value,
            background: const Color(0xFFE4F7ED),
            foreground: const Color(0xFF0E8F6E),
          )
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
      padding: const EdgeInsets.symmetric(vertical: 10),
      child: Row(
        crossAxisAlignment: multiline ? CrossAxisAlignment.start : CrossAxisAlignment.center,
        children: [
          Icon(icon, size: 18, color: const Color(0xFF94A3B8)),
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
        style: TextStyle(
          color: foreground,
          fontSize: 14,
          fontWeight: FontWeight.w700,
        ),
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
      padding: const EdgeInsets.fromLTRB(14, 14, 14, 14),
      decoration: BoxDecoration(
        gradient: const LinearGradient(
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
          colors: [
            Color(0xFF053223),
            Color(0xFF0E8F6E),
          ],
        ),
        borderRadius: BorderRadius.circular(28),
        boxShadow: [
          BoxShadow(
            color: const Color(0xFF0A5E42).withValues(alpha: 0.22),
            blurRadius: 26,
            offset: const Offset(0, 12),
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
              color: Colors.white.withValues(alpha: 0.18),
              borderRadius: BorderRadius.circular(18),
              border: Border.all(color: Colors.white.withValues(alpha: 0.24), width: 1),
            ),
            child: ClipRRect(
              borderRadius: BorderRadius.circular(14),
              child: logoUrl != null && logoUrl!.isNotEmpty
                  ? Image.network(
                      logoUrl!,
                      fit: BoxFit.contain,
                      errorBuilder: (_, __, ___) => const Icon(Icons.school_rounded, color: Colors.white),
                    )
                  : const Icon(Icons.school_rounded, color: Colors.white),
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
                    fontSize: 14,
                    height: 1.12,
                    fontWeight: FontWeight.w800,
                  ),
                ),
                const SizedBox(height: 8),
                _Pill(
                  text: 'Terakreditasi $accreditation',
                  background: Colors.white.withValues(alpha: 0.18),
                  foreground: Colors.white,
                ),
                const SizedBox(height: 8),
                Text(
                  'SCOD$scod',
                  style: const TextStyle(
                    color: Color(0xE6FFFFFF),
                    fontSize: 14,
                    fontWeight: FontWeight.w600,
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

class _SchoolInfoTab extends StatelessWidget {
  const _SchoolInfoTab({
    required this.name,
    required this.scod,
    required this.school,
    required this.status,
    required this.accreditation,
    required this.teacherCount,
    required this.studentCount,
    required this.classSummary,
  });

  final String name;
  final String scod;
  final Map<String, dynamic> school;
  final String status;
  final String accreditation;
  final int teacherCount;
  final int studentCount;
  final List<MapEntry<String, int>> classSummary;

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          'Ringkasan',
          style: const TextStyle(
            color: Color(0xFF172A24),
            fontSize: 14,
            fontWeight: FontWeight.w800,
          ),
        ),
        const SizedBox(height: 12),
        Row(
          children: [
            Expanded(
              child: _SummaryCard(
                icon: Icons.badge_outlined,
                title: 'Guru & Pegawai',
                value: '$teacherCount',
                subtitle: 'Orang',
                accent: const Color(0xFF0E8F6E),
                tint: const Color(0xFFEAF7F1),
              ),
            ),
            const SizedBox(width: 10),
            Expanded(
              child: _SummaryCard(
                icon: Icons.school_outlined,
                title: 'Siswa',
                value: '$studentCount',
                subtitle: 'Siswa',
                accent: const Color(0xFF2F6FD6),
                tint: const Color(0xFFEAF1FF),
              ),
            ),
            const SizedBox(width: 10),
            Expanded(
              child: _SummaryCard(
                icon: Icons.apartment_outlined,
                title: 'Kelas',
                value: '${classSummary.length}',
                subtitle: 'Kelas',
                accent: const Color(0xFFC89200),
                tint: const Color(0xFFFFF4DD),
              ),
            ),
          ],
        ),
        const SizedBox(height: 14),
        _InfoCard(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const Text(
                'Informasi Sekolah',
                style: TextStyle(
                  fontSize: 14,
                  fontWeight: FontWeight.w800,
                  color: Color(0xFF172A24),
                ),
              ),
              const SizedBox(height: 12),
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
            ],
          ),
        ),
      ],
    );
  }
}

class _TeacherStaffTab extends StatelessWidget {
  const _TeacherStaffTab({
    required this.headmaster,
    required this.teachers,
  });

  final Map<String, dynamic>? headmaster;
  final List<Map<String, dynamic>> teachers;

  @override
  Widget build(BuildContext context) {
    return _TeacherDirectory(
      headmaster: headmaster,
      teachers: teachers,
    );
  }
}

class _StudentTab extends StatelessWidget {
  const _StudentTab({
    required this.students,
    required this.classSummary,
    required this.studentCount,
  });
  final List<Map<String, dynamic>> students;
  final List<MapEntry<String, int>> classSummary;
  final int studentCount;
  @override
  Widget build(BuildContext context) {
    if (students.isEmpty || classSummary.isEmpty) {
      return const _DetailEmpty(label: 'Data siswa belum tersedia.');
    }
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Row(
          children: [
            const Expanded(
              child: Text(
                'Siswa',
                style: TextStyle(
                  color: Color(0xFF172A24),
                  fontSize: 14,
                  fontWeight: FontWeight.w800,
                ),
              ),
            ),
            Text(
              'Lihat semua',
              style: const TextStyle(
                color: Color(0xFF0E8F6E),
                fontSize: 12,
                fontWeight: FontWeight.w700,
              ),
            ),
          ],
        ),
        const SizedBox(height: 12),
        Container(
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(22),
            boxShadow: [
              BoxShadow(
                color: Colors.black.withValues(alpha: 0.04),
                blurRadius: 16,
                offset: const Offset(0, 6),
              ),
            ],
          ),
          child: Column(
            children: classSummary.asMap().entries.map((entry) {
              final item = entry.value;
              final isLast = entry.key == classSummary.length - 1;
              return Container(
                padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
                decoration: BoxDecoration(
                  border: isLast ? null : const Border(bottom: BorderSide(color: Color(0xFFE7ECEA))),
                ),
                child: Row(
                  children: [
                    Container(
                      width: 42,
                      height: 42,
                      decoration: BoxDecoration(
                        color: const Color(0xFFE4F7ED),
                        borderRadius: BorderRadius.circular(14),
                      ),
                      child: const Icon(Icons.people_alt_outlined, color: Color(0xFF0E8F6E), size: 20),
                    ),
                    const SizedBox(width: 12),
                    Expanded(
                      child: Text(
                        item.key,
                        style: const TextStyle(
                          color: Color(0xFF64748B),
                          fontSize: 12,
                          fontWeight: FontWeight.w600,
                        ),
                      ),
                    ),
                    const SizedBox(width: 10),
                    Column(
                      crossAxisAlignment: CrossAxisAlignment.end,
                      children: [
                        Text(
                          '${item.value}',
                          style: const TextStyle(
                            color: Color(0xFF172A24),
                            fontSize: 14,
                            fontWeight: FontWeight.w800,
                          ),
                        ),
                        const SizedBox(height: 2),
                        const Text(
                          'Siswa',
                          style: TextStyle(
                            color: Color(0xFF64748B),
                            fontSize: 12,
                            fontWeight: FontWeight.w600,
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(width: 8),
                    const Icon(Icons.chevron_right_rounded, color: Color(0xFF94A3B8), size: 22),
                  ],
                ),
              );
            }).toList(),
          ),
        ),
      ],
    );
  }
}

class _ContactTab extends StatelessWidget {
  const _ContactTab({
    required this.name,
    required this.scod,
    required this.school,
  });

  final String name;
  final String scod;
  final Map<String, dynamic> school;

  @override
  Widget build(BuildContext context) {
    return _ContactCard(
      title: 'Kontak Operator',
      subtitle: 'Admin/Operator NUIST Sekolah',
      description: 'Hubungi admin/operator untuk bantuan teknis dan informasi terkait NUIST.',
      phone: school['phone']?.toString() ?? '-',
      email: school['email']?.toString() ?? '-',
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

class _TeacherDirectory extends StatefulWidget {
  const _TeacherDirectory({
    required this.headmaster,
    required this.teachers,
  });

  final Map<String, dynamic>? headmaster;
  final List<Map<String, dynamic>> teachers;

  @override
  State<_TeacherDirectory> createState() => _TeacherDirectoryState();
}

class _TeacherDirectoryState extends State<_TeacherDirectory> {
  final TextEditingController _searchController = TextEditingController();
  String _query = '';

  @override
  void dispose() {
    _searchController.dispose();
    super.dispose();
  }

  List<Map<String, dynamic>> get _filteredTeachers {
    final headmasterId = widget.headmaster?['id']?.toString();
    final q = _query.trim().toLowerCase();
    final baseItems = widget.teachers.where((teacher) {
      final teacherId = teacher['id']?.toString();
      return headmasterId == null || teacherId == null || teacherId != headmasterId;
    }).toList();

    if (q.isEmpty) return baseItems;
    return baseItems.where((teacher) {
      final name = _teacherName(teacher).toLowerCase();
      final status = _teacherStatus(teacher).toLowerCase();
      final position = _teacherPosition(teacher).toLowerCase();
      final title = _teacherTitle(teacher).toLowerCase();
      return name.contains(q) || status.contains(q) || position.contains(q) || title.contains(q);
    }).toList();
  }

  @override
  Widget build(BuildContext context) {
    final teachers = _filteredTeachers;
    final listHeight = (MediaQuery.sizeOf(context).height * 0.48).clamp(260.0, 520.0);
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        _InfoCard(
          child: InkWell(
            onTap: widget.headmaster == null ? null : () => _openTeacherSheet(context, widget.headmaster!),
            borderRadius: BorderRadius.circular(24),
            child: Row(
              crossAxisAlignment: CrossAxisAlignment.center,
              children: [
                _TeacherAvatar(teacher: widget.headmaster ?? const <String, dynamic>{}),
                const SizedBox(width: 12),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text(
                        'Kepala Sekolah',
                        style: TextStyle(fontSize: 12, fontWeight: FontWeight.w700),
                      ),
                      const SizedBox(height: 6),
                      Text(
                        widget.headmaster?['name']?.toString() ?? 'Belum ditetapkan',
                        maxLines: 2,
                        overflow: TextOverflow.ellipsis,
                        style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w600),
                      ),
                      const SizedBox(height: 6),
                      _Pill(
                        text: _teacherStatus(widget.headmaster ?? const <String, dynamic>{}),
                        background: const Color(0xFFE4F7ED),
                        foreground: const Color(0xFF0E8F6E),
                      ),
                    ],
                  ),
                ),
                const SizedBox(width: 8),
                const Icon(Icons.chevron_right_rounded, size: 22, color: Color(0xFF94A3B8)),
              ],
            ),
          ),
        ),
        const SizedBox(height: 12),
        TextField(
          controller: _searchController,
          onChanged: (value) => setState(() => _query = value),
          style: const TextStyle(fontSize: 12),
          decoration: InputDecoration(
            hintText: 'Cari guru atau pegawai',
            hintStyle: const TextStyle(fontSize: 14),
            prefixIcon: const Icon(Icons.search_rounded, size: 20),
            filled: true,
            fillColor: const Color(0xFFF3F7F5),
            contentPadding: const EdgeInsets.symmetric(horizontal: 14, vertical: 14),
            border: OutlineInputBorder(
              borderRadius: BorderRadius.circular(16),
              borderSide: BorderSide.none,
            ),
            enabledBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(16),
              borderSide: BorderSide.none,
            ),
            focusedBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(16),
              borderSide: const BorderSide(color: Color(0xFF0E8F6E), width: 1.2),
            ),
          ),
        ),
        const SizedBox(height: 14),
        if (teachers.isEmpty)
          const _DetailEmpty(label: 'Belum ada data guru atau pegawai')
        else
          SizedBox(
            height: listHeight,
            child: _PeopleDirectoryList(
              items: teachers,
              onTapItem: (teacher) => _openTeacherSheet(context, teacher),
            ),
          ),
      ],
    );
  }
}

class _PeopleDirectoryList extends StatelessWidget {
  const _PeopleDirectoryList({
    required this.items,
    required this.onTapItem,
  });

  final List<Map<String, dynamic>> items;
  final ValueChanged<Map<String, dynamic>> onTapItem;

  @override
  Widget build(BuildContext context) {
    return Container(
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(24),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(alpha: 0.04),
            blurRadius: 16,
            offset: const Offset(0, 6),
          ),
        ],
      ),
      child: ListView.separated(
        padding: EdgeInsets.zero,
        physics: const BouncingScrollPhysics(),
        itemCount: items.length,
        separatorBuilder: (_, __) => const Divider(
          height: 1,
          thickness: 1,
          color: Color(0xFFE7ECEA),
          indent: 82,
        ),
        itemBuilder: (context, index) {
          final teacher = items[index];
          final isFirst = index == 0;
          final isLast = index == items.length - 1;
          return InkWell(
            onTap: () => onTapItem(teacher),
            borderRadius: BorderRadius.vertical(
              top: isFirst ? const Radius.circular(24) : Radius.zero,
              bottom: isLast ? const Radius.circular(24) : Radius.zero,
            ),
            child: Container(
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
              child: Row(
                children: [
                  _TeacherAvatar(teacher: teacher),
                  const SizedBox(width: 14),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          _teacherName(teacher),
                          maxLines: 2,
                          overflow: TextOverflow.ellipsis,
                          style: const TextStyle(
                            color: Color(0xFF172A24),
                            fontSize: 11,
                            fontWeight: FontWeight.w600,
                            height: 1.25,
                          ),
                        ),
                        const SizedBox(height: 4),
                        Text(
                          _teacherStatus(teacher),
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                          style: const TextStyle(
                            color: Color(0xFF6B7280),
                            fontSize: 10,
                            fontWeight: FontWeight.w400,
                          ),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(width: 8),
                  const Icon(
                    Icons.chevron_right_rounded,
                    color: Color(0xFF94A3B8),
                    size: 24,
                  ),
                ],
              ),
            ),
          );
        },
      ),
    );
  }
}

class _TeacherAvatar extends StatelessWidget {
  const _TeacherAvatar({required this.teacher});
  final Map<String, dynamic> teacher;

  @override
  Widget build(BuildContext context) {
    final photoUrl = _teacherPhotoUrl(teacher);
    return Container(
      width: 52,
      height: 52,
      decoration: BoxDecoration(
        color: const Color(0xFFE4F7ED),
        shape: BoxShape.circle,
        border: Border.all(color: const Color(0xFFD5EDE3), width: 1),
      ),
      clipBehavior: Clip.antiAlias,
      child: photoUrl != null
          ? Image.network(
              photoUrl,
              fit: BoxFit.cover,
              errorBuilder: (_, __, ___) => _TeacherAvatarFallback(teacher: teacher),
            )
          : _TeacherAvatarFallback(teacher: teacher),
    );
  }
}

class _TeacherAvatarFallback extends StatelessWidget {
  const _TeacherAvatarFallback({required this.teacher});
  final Map<String, dynamic> teacher;

  @override
  Widget build(BuildContext context) {
    return Container(
      color: const Color(0xFFE4F7ED),
      alignment: Alignment.center,
      child: Text(
        _teacherInitials(teacher),
        style: const TextStyle(
          color: Color(0xFF0E8F6E),
          fontSize: 14,
          fontWeight: FontWeight.w700,
        ),
      ),
    );
  }
}

Future<void> _openTeacherSheet(
  BuildContext context,
  Map<String, dynamic> teacher,
) {
  return showModalBottomSheet<void>(
    context: context,
    isScrollControlled: true,
    backgroundColor: Colors.transparent,
    builder: (context) {
      return DraggableScrollableSheet(
        initialChildSize: 0.82,
        minChildSize: 0.65,
        maxChildSize: 0.92,
        expand: false,
        builder: (context, scrollController) {
          return Container(
            decoration: const BoxDecoration(
              gradient: LinearGradient(
                begin: Alignment.topCenter,
                end: Alignment.bottomCenter,
                colors: [
                  Color(0xFFFFFFFF),
                  Color(0xFFF5FAF7),
                ],
              ),
              borderRadius: BorderRadius.vertical(top: Radius.circular(28)),
            ),
            child: SingleChildScrollView(
              controller: scrollController,
              child: Padding(
                padding: const EdgeInsets.fromLTRB(16, 10, 16, 24),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Center(
                      child: Container(
                        width: 40,
                        height: 4,
                        margin: const EdgeInsets.only(bottom: 14),
                        decoration: BoxDecoration(
                          color: const Color(0xFFD8E2DD),
                          borderRadius: BorderRadius.circular(999),
                        ),
                      ),
                    ),
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        IconButton(
                          onPressed: () => Navigator.of(context).pop(),
                          icon: const Icon(
                            Icons.arrow_back_rounded,
                            color: Color(0xFF0E8F6E),
                          ),
                        ),
                        IconButton(
                          onPressed: () => Navigator.of(context).pop(),
                          icon: const Icon(
                            Icons.close_rounded,
                            color: Color(0xFF0E8F6E),
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 6),
                    Center(
                      child: Column(
                        children: [
                          Container(
                            padding: const EdgeInsets.all(5),
                            decoration: BoxDecoration(
                              color: Colors.white,
                              shape: BoxShape.circle,
                              boxShadow: [
                                BoxShadow(
                                  color: Colors.black.withValues(alpha: 0.08),
                                  blurRadius: 16,
                                  offset: const Offset(0, 6),
                                ),
                              ],
                            ),
                            child: _TeacherAvatar(teacher: teacher),
                          ),
                          const SizedBox(height: 14),
                          _Pill(
                            text: 'Guru / Pegawai',
                            background: const Color(0xFFDDF1E7),
                            foreground: const Color(0xFF0E6F57),
                          ),
                          const SizedBox(height: 14),
                          Text(
                            _teacherName(teacher),
                            textAlign: TextAlign.center,
                            style: const TextStyle(
                              fontSize: 18,
                              fontWeight: FontWeight.w800,
                              color: Color(0xFF0F3D32),
                              height: 1.15,
                            ),
                          ),
                          const SizedBox(height: 8),
                          Text(
                            _teacherValue(teacher, ['nuist_id', 'details.nuist_id']),
                            textAlign: TextAlign.center,
                            style: const TextStyle(
                              fontSize: 12,
                              color: Color(0xFF6B7280),
                              fontWeight: FontWeight.w500,
                            ),
                          ),
                        ],
                      ),
                    ),
                    const SizedBox(height: 20),
                    _TeacherProfileCard(
                      title: 'Informasi Data Guru/Pegawai',
                      rows: [
                        _TeacherProfileRow(
                          icon: Icons.badge_outlined,
                          label: 'NIPM',
                          value: _teacherValue(teacher, ['nip', 'nipm', 'nip_ma_arif', 'details.nip', 'details.nipm', 'details.nip_ma_arif']),
                        ),
                        _TeacherProfileRow(
                          icon: Icons.badge_rounded,
                          label: 'NUPTK',
                          value: _teacherValue(teacher, ['nuptk', 'details.nuptk']),
                        ),
                        _TeacherProfileRow(
                          icon: Icons.credit_card_outlined,
                          label: 'Kartanu',
                          value: _teacherValue(teacher, ['kartanu', 'nomor_kartanu', 'details.kartanu', 'details.nomor_kartanu']),
                        ),
                        _TeacherProfileRow(
                          icon: Icons.location_city_outlined,
                          label: 'Tempat / Tanggal lahir',
                          value: _combineTeacherValues(
                            teacher,
                            [
                              ['place_of_birth', 'tempat_lahir', 'details.place_of_birth', 'details.tempat_lahir'],
                              ['date_of_birth', 'tanggal_lahir', 'details.date_of_birth', 'details.tanggal_lahir'],
                            ],
                            separator: ', ',
                          ),
                        ),
                        _TeacherProfileRow(
                          icon: Icons.school_outlined,
                          label: 'Pendidikan terakhir',
                          value: _teacherValue(teacher, ['last_education', 'pendidikan_terakhir', 'details.last_education', 'details.pendidikan_terakhir']),
                        ),
                        _TeacherProfileRow(
                          icon: Icons.workspace_premium_outlined,
                          label: 'Tahun lulus',
                          value: _teacherValue(teacher, ['tahun_lulus', 'details.tahun_lulus']),
                        ),
                        _TeacherProfileRow(
                          icon: Icons.menu_book_outlined,
                          label: 'Program studi',
                          value: _teacherValue(teacher, ['study_program', 'program_studi', 'details.study_program', 'details.program_studi']),
                        ),
                        _TeacherProfileRow(
                          icon: Icons.person_outline,
                          label: 'Mengajar / Ketugasan',
                          value: _teacherValue(teacher, ['mengajar', 'mapel_tugas_yang_diampu', 'ketugasan', 'details.mengajar', 'details.mapel_tugas_yang_diampu', 'details.ketugasan']),
                        ),
                      ],
                    ),
                    const SizedBox(height: 16),
                    _TeacherFooterNote(
                      text: 'Data ini bersumber dari sistem kepegawaian. Jika ada kesalahan, hubungi administrator.',
                    ),
                  ],
                ),
              ),
            ),
          );
        },
      );
    },
  );
}

class _TeacherProfileCard extends StatelessWidget {
  const _TeacherProfileCard({
    required this.title,
    required this.rows,
  });

  final String title;
  final List<_TeacherProfileRow> rows;

  @override
  Widget build(BuildContext context) {
    final visibleRows = rows.where((row) => row.value != '-').toList();
    if (visibleRows.isEmpty) return const SizedBox.shrink();

    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(18),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(28),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(alpha: 0.05),
            blurRadius: 20,
            offset: const Offset(0, 8),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              const Icon(Icons.badge_outlined, color: Color(0xFF0E8F6E), size: 15),
              const SizedBox(width: 12),
              Expanded(
                child: Text(
                  title,
                  style: const TextStyle(
                    fontSize: 12,
                    fontWeight: FontWeight.w800,
                    color: Color(0xFF0F3D32),
                  ),
                ),
              ),
            ],
          ),
          const SizedBox(height: 14),
          ...List.generate(visibleRows.length, (index) {
            final row = visibleRows[index];
            return Column(
              children: [
                _TeacherProfileLine(row: row),
                if (index != visibleRows.length - 1)
                  const Padding(
                    padding: EdgeInsets.only(left: 48),
                    child: Divider(height: 1, thickness: 1, color: Color(0xFFE9EEEC)),
                  ),
              ],
            );
          }),
        ],
      ),
    );
  }
}

class _TeacherProfileRow {
  const _TeacherProfileRow({
    required this.icon,
    required this.label,
    required this.value,
  });

  final IconData icon;
  final String label;
  final String value;
}

class _TeacherProfileLine extends StatelessWidget {
  const _TeacherProfileLine({required this.row});

  final _TeacherProfileRow row;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 12),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.center,
        children: [
          Container(
            width: 38,
            height: 38,
            decoration: BoxDecoration(
              color: const Color(0xFFEAF6F1),
              borderRadius: BorderRadius.circular(12),
            ),
            child: Icon(row.icon, size: 15, color: const Color(0xFF0E8F6E)),
          ),
          const SizedBox(width: 14),
          Expanded(
            child: Align(
              alignment: Alignment.centerLeft,
              child: Text(
                row.label,
                style: const TextStyle(
                  fontSize: 12,
                  color: Color(0xFF6B7280),
                  height: 1.25,
                ),
              ),
            ),
          ),
          const SizedBox(width: 12),
          Flexible(
            flex: 2,
            child: Align(
              alignment: Alignment.centerRight,
              child: Text(
                row.value,
                textAlign: TextAlign.right,
                style: const TextStyle(
                  fontSize: 12,
                  color: Color(0xFF172A24),
                  fontWeight: FontWeight.w700,
                  height: 1.25,
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }
}

class _TeacherFooterNote extends StatelessWidget {
  const _TeacherFooterNote({required this.text});

  final String text;

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: const Color(0xFFF4F7FB),
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: const Color(0xFFE5EBF0)),
      ),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Icon(Icons.verified_outlined, color: Color(0xFF5FC35F), size: 24),
          const SizedBox(width: 12),
          Expanded(
            child: Text(
              text,
              style: const TextStyle(
                fontSize: 12,
                color: Color(0xFF51606B),
                height: 1.35,
              ),
            ),
          ),
        ],
      ),
    );
  }
}

class _SummaryCard extends StatelessWidget {
  const _SummaryCard({
    required this.icon,
    required this.title,
    required this.value,
    required this.subtitle,
    required this.accent,
    required this.tint,
  });

  final IconData icon;
  final String title;
  final String value;
  final String subtitle;
  final Color accent;
  final Color tint;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(22),
        border: Border.all(color: tint),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(alpha: 0.05),
            blurRadius: 16,
            offset: const Offset(0, 6),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            width: 40,
            height: 40,
            decoration: BoxDecoration(
              color: tint,
              shape: BoxShape.circle,
            ),
            child: Icon(icon, color: accent, size: 22),
          ),
          const SizedBox(height: 10),
          Text(
            title,
            maxLines: 2,
            overflow: TextOverflow.ellipsis,
            style: const TextStyle(
              fontSize: 12,
              color: Color(0xFF3F4A57),
              fontWeight: FontWeight.w600,
              height: 1.2,
            ),
          ),
          const SizedBox(height: 10),
          Text(
            value,
            style: TextStyle(
              fontSize: 16,
              color: accent,
              fontWeight: FontWeight.w800,
            ),
          ),
          Text(
            subtitle,
            style: const TextStyle(
              fontSize: 12,
              color: Color(0xFF6B7280),
              fontWeight: FontWeight.w500,
            ),
          ),
        ],
      ),
    );
  }
}

class _ContactCard extends StatelessWidget {
  const _ContactCard({
    required this.title,
    required this.subtitle,
    required this.description,
    required this.phone,
    required this.email,
  });

  final String title;
  final String subtitle;
  final String description;
  final String phone;
  final String email;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(22),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(alpha: 0.05),
            blurRadius: 16,
            offset: const Offset(0, 6),
          ),
        ],
      ),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            width: 52,
            height: 52,
            decoration: const BoxDecoration(
              color: Color(0xFFEAF6F1),
              shape: BoxShape.circle,
            ),
            child: const Icon(Icons.headset_mic_outlined, color: Color(0xFF0E8F6E), size: 28),
          ),
          const SizedBox(width: 14),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  title,
                  style: const TextStyle(
                    fontSize: 14,
                    fontWeight: FontWeight.w800,
                    color: Color(0xFF172A24),
                  ),
                ),
                const SizedBox(height: 4),
                Text(
                  subtitle,
                  style: const TextStyle(
                    fontSize: 12,
                    color: Color(0xFF0E8F6E),
                    fontWeight: FontWeight.w600,
                  ),
                ),
                const SizedBox(height: 10),
                Text(
                  description,
                  style: const TextStyle(
                    fontSize: 12,
                    color: Color(0xFF6B7280),
                    height: 1.35,
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(width: 12),
          Container(
            width: 150,
            padding: const EdgeInsets.all(12),
            decoration: BoxDecoration(
              color: const Color(0xFFEAF6F1),
              borderRadius: BorderRadius.circular(18),
            ),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  children: [
                    const Icon(Icons.phone_outlined, size: 18, color: Color(0xFF0E8F6E)),
                    const SizedBox(width: 8),
                    Expanded(
                      child: Text(
                        phone,
                        style: const TextStyle(
                          fontSize: 12,
                          fontWeight: FontWeight.w700,
                          color: Color(0xFF172A24),
                        ),
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 10),
                Row(
                  children: [
                    const Icon(Icons.mail_outline, size: 18, color: Color(0xFF0E8F6E)),
                    const SizedBox(width: 8),
                    Expanded(
                      child: Text(
                        email,
                        style: const TextStyle(
                          fontSize: 12,
                          fontWeight: FontWeight.w700,
                          color: Color(0xFF172A24),
                        ),
                      ),
                    ),
                  ],
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}

List<MapEntry<String, int>> _studentClassSummary(List<Map<String, dynamic>> students) {
  final counts = <String, int>{};
  for (final student in students) {
    final rawClass = student['class']?.toString().trim();
    if (rawClass == null || rawClass.isEmpty || rawClass == '-') continue;
    counts[rawClass] = (counts[rawClass] ?? 0) + 1;
  }
  final entries = counts.entries.toList()
    ..sort((a, b) => a.key.compareTo(b.key));
  return entries;
}

String _teacherName(Map<String, dynamic> teacher) {
  return _teacherValue(teacher, ['name', 'full_name', 'nama']) != '-'
      ? _teacherValue(teacher, ['name', 'full_name', 'nama'])
      : 'Pegawai';
}

String _teacherPosition(Map<String, dynamic> teacher) {
  return _teacherValue(teacher, ['position', 'jabatan', 'role']);
}

String _teacherStatus(Map<String, dynamic> teacher) {
  return _teacherValue(teacher, ['status_kepegawaian', 'employment_status', 'status_kepegawaian_label', 'details.status_kepegawaian', 'details.employment_status']);
}

String _combineTeacherValues(
  Map<String, dynamic> teacher,
  List<List<String>> groups, {
  String separator = ' ',
}) {
  final values = groups
      .map((keys) => _teacherValue(teacher, keys))
      .where((value) => value != '-')
      .toList();
  if (values.isEmpty) return '-';
  return values.join(separator);
}

String _teacherTitle(Map<String, dynamic> teacher) {
  final name = _teacherName(teacher);
  final status = _teacherStatus(teacher);
  return '$name $status';
}

String _teacherInitials(Map<String, dynamic> teacher) {
  final name = _teacherName(teacher);
  final parts = name.split(RegExp(r'\s+')).where((part) => part.isNotEmpty).toList();
  if (parts.isEmpty) return 'G';
  if (parts.length == 1) return parts.first.substring(0, 1).toUpperCase();
  return '${parts.first[0]}${parts[1][0]}'.toUpperCase();
}

String? _teacherPhotoUrl(Map<String, dynamic> teacher) {
  for (final key in ['photo_url', 'photo', 'avatar', 'image_url', 'picture']) {
    final value = _resolveTeacherValue(teacher, key)?.trim();
    if (value != null && value.isNotEmpty && value != '-') return value;
  }
  return null;
}

String _teacherValue(Map<String, dynamic> teacher, List<String> keys) {
  for (final key in keys) {
    final text = _resolveTeacherValue(teacher, key)?.trim();
    if (text != null && text.isNotEmpty && text != '-') return text;
  }
  return '-';
}

String? _resolveTeacherValue(Map<String, dynamic> teacher, String key) {
  final parts = key.split('.');
  dynamic current = teacher;
  for (final part in parts) {
    if (current is! Map) return null;
    current = current[part];
  }
  return current?.toString();
}
