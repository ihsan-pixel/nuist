part of 'pengurus_shell_page.dart';

class _SchoolMonitor extends StatefulWidget {
  const _SchoolMonitor({
    required this.repository,
    required this.onBack,
  });

  final PengurusMobileRepository repository;
  final VoidCallback onBack;

  @override
  State<_SchoolMonitor> createState() => _SchoolMonitorState();
}

class _SchoolMonitorState extends State<_SchoolMonitor> {
  late Future<Map<String, dynamic>> _future;

  final TextEditingController _searchController =
  TextEditingController();

  String _query = '';
  String? _districtFilter;

  @override
  void initState() {
    super.initState();
    _future = widget.repository.schools();
  }

  @override
  void dispose() {
    _searchController.dispose();
    super.dispose();
  }

  Future<void> _refresh() async {
    _searchController.clear();

    setState(() {
      _query = '';
      _districtFilter = null;
      _future = widget.repository.schools();
    });
  }

  @override
  Widget build(BuildContext context) {
    return Stack(
      clipBehavior: Clip.none,
      children: [
        // =========================================================
        // MAIN CONTENT
        // =========================================================

        Column(
          children: [
            // =========================================================
            // PREMIUM DARK GREEN HEADER
            // =========================================================

            SizedBox(
              height: 140,
              child: ClipRRect(
                borderRadius: const BorderRadius.vertical(
                  bottom: Radius.circular(0),
                ),
                child: CustomPaint(
                  painter: _PremiumHeaderBackground(),
                  child: Stack(
                    clipBehavior: Clip.none,
                    children: [
                      // Subtle glow kanan atas
                      Positioned(
                        right: -70,
                        top: -90,
                        child: Container(
                          width: 240,
                          height: 240,
                          decoration: BoxDecoration(
                            shape: BoxShape.circle,
                            color: Colors.white.withValues(
                              alpha: 0.035,
                            ),
                          ),
                        ),
                      ),

                      // Small decorative glow
                      Positioned(
                        right: 34,
                        top: 92,
                        child: Container(
                          width: 7,
                          height: 7,
                          decoration: BoxDecoration(
                            shape: BoxShape.circle,
                            color: Colors.white.withValues(
                              alpha: 0.12,
                            ),
                          ),
                        ),
                      ),

                      Positioned(
                        right: 50,
                        top: 112,
                        child: Container(
                          width: 4,
                          height: 4,
                          decoration: BoxDecoration(
                            shape: BoxShape.circle,
                            color: Colors.white.withValues(
                              alpha: 0.08,
                            ),
                          ),
                        ),
                      ),

                      // =================================================
                      // HEADER CONTENT
                      // =================================================

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
                                // BACK
                                IconButton(
                                  onPressed: widget.onBack,
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

                                // TITLE
                                const Expanded(
                                  child: Text(
                                    'Data Sekolah',
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
                                padding: EdgeInsets.only(
                                  left: 56,
                                ),
                                child: Text(
                                  'Kelola dan pantau data sekolah',
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
              ),
            ),

            // =========================================================
            // CONTENT
            // =========================================================

            Expanded(
              child: Transform.translate(
                offset: const Offset(0, -38),
                child: Container(
                  width: double.infinity,
                  decoration: const BoxDecoration(
                    color: Color(0xFFF6F8FA),
                    borderRadius: BorderRadius.vertical(
                      top: Radius.circular(34),
                    ),
                  ),
                  child: FutureBuilder<Map<String, dynamic>>(
                    future: _future,
                    builder: (context, snapshot) {
                      if (snapshot.connectionState ==
                          ConnectionState.waiting) {
                        return const Center(
                          child: CircularProgressIndicator(
                            color: _pengurusPrimary,
                            strokeWidth: 2.5,
                          ),
                        );
                      }

                      if (snapshot.hasError || !snapshot.hasData) {
                        return _LoadError(
                          onRetry: _refresh,
                        );
                      }

                      final allItems = _updateItems(
                        snapshot.data!['items'],
                      );

                      final keyword = _query.trim().toLowerCase();

                      final items = allItems.where((item) {
                        final matchesKeyword = keyword.isEmpty ||
                            '${item['name']} ${item['scod']} ${item['kabupaten']}'
                                .toLowerCase()
                                .contains(keyword);

                        final matchesDistrict = _districtFilter == null ||
                            _schoolDistrict(item) == _districtFilter;

                        return matchesKeyword && matchesDistrict;
                      }).toList();

                      // =====================================================
                      // GROUP BY DISTRICT
                      // =====================================================

                      final districts =
                      <String, List<Map<String, dynamic>>>{};

                      for (final item in items) {
                        final district = _schoolDistrict(item);

                        if (district == null) {
                          continue;
                        }

                        districts
                            .putIfAbsent(
                          district,
                              () => [],
                        )
                            .add(item);
                      }

                      final districtEntries = districts.entries.toList()
                        ..sort(
                              (left, right) => _districtRank(left.key)
                              .compareTo(_districtRank(right.key)),
                        );

                      for (final entry in districtEntries) {
                        entry.value.sort(
                              (left, right) => _scodValue(left['scod'])
                              .compareTo(_scodValue(right['scod'])),
                        );
                      }

                      // =====================================================
                      // SCROLLABLE CONTENT
                      // =====================================================

                      return RefreshIndicator(
                        color: _pengurusPrimary,
                        onRefresh: _refresh,
                        child: ListView(
                          physics: const AlwaysScrollableScrollPhysics(),
                          padding: EdgeInsets.fromLTRB(
                            16,
                            28,
                            16,
                            MediaQuery.paddingOf(context).bottom + 128,
                          ),
                          children: [
                            const SizedBox(height: 25),

                            if (districtEntries.isEmpty)
                              const _SchoolEmptyState()
                            else
                              ...districtEntries.map(
                                    (entry) => Padding(
                                  padding: const EdgeInsets.only(
                                    bottom: 18,
                                  ),
                                  child: _DistrictSchoolSection(
                                    repository: widget.repository,
                                    district: entry.key,
                                    items: entry.value,
                                  ),
                                ),
                              ),
                          ],
                        ),
                      );
                    },
                  ),
                ),
              ),
            ),
          ],
        ),

        // =========================================================
        // SEARCH FIELD
        // =========================================================
        //
        // PENTING:
        // Search sekarang berada di Stack UTAMA,
        // bukan di dalam Container putih.
        //
        // Jadi search bisa keluar dari container putih
        // dan tidak tertutup oleh header.
        // =========================================================

        Positioned(
          top: 80,
          left: 16,
          right: 16,
          child: Row(
            children: [
              Expanded(
                child: _SchoolSearchField(
                  onChanged: (value) {
                    setState(() {
                      _query = value;
                    });
                  },
                ),
              ),

              const SizedBox(width: 10),

              // BUTTON REFRESH
              Material(
                color: Colors.white,
                borderRadius: BorderRadius.circular(18),
                child: InkWell(
                  onTap: _refresh,
                  borderRadius: BorderRadius.circular(18),
                  child: Container(
                    width: 58,
                    height: 58,
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(18),
                      border: Border.all(
                        color: const Color(0xFFE9EEF1),
                        width: 1,
                      ),
                      boxShadow: [
                        BoxShadow(
                          color: const Color(0xFF002F25).withValues(
                            alpha: 0.10,
                          ),
                          blurRadius: 22,
                          spreadRadius: -4,
                          offset: const Offset(0, 8),
                        ),
                      ],
                    ),
                    child: const Icon(
                      Icons.refresh_rounded,
                      color: _pengurusPrimary,
                      size: 24,
                    ),
                  ),
                ),
              ),
            ],
          ),
        ),
      ],
    );
  }
}

// =============================================================
// SEARCH FIELD
// =============================================================

class _SchoolSearchField extends StatelessWidget {
  const _SchoolSearchField({
    required this.onChanged,
  });

  final ValueChanged<String> onChanged;

  @override
  Widget build(BuildContext context) {
    return Container(
      height: 58,
      padding: const EdgeInsets.symmetric(
        horizontal: 6,
        vertical: 6,
      ),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(18),
        border: Border.all(
          color: const Color(0xFFE9EEF1),
          width: 1,
        ),
        boxShadow: [
          BoxShadow(
            color: const Color(0xFF002F25).withValues(
              alpha: 0.10,
            ),
            blurRadius: 22,
            spreadRadius: -4,
            offset: const Offset(0, 8),
          ),
        ],
      ),
      child: TextField(
        onChanged: onChanged,
        textInputAction: TextInputAction.search,
        cursorColor: _pengurusPrimary,
        style: const TextStyle(
          color: _pengurusText,
          fontSize: 13,
          fontWeight: FontWeight.w500,
        ),
        decoration: const InputDecoration(
          hintText: 'Cari nama sekolah atau SCOD...',
          hintStyle: TextStyle(
            color: Color(0xFF9AA5AE),
            fontSize: 13,
            fontWeight: FontWeight.w400,
          ),
          prefixIcon: Icon(
            Icons.search_rounded,
            color: _pengurusPrimary,
            size: 23,
          ),
          prefixIconConstraints: BoxConstraints(
            minWidth: 48,
          ),
          border: InputBorder.none,
          contentPadding: EdgeInsets.symmetric(
            vertical: 12,
          ),
        ),
      ),
    );
  }
}

// =============================================================
// DISTRICT SECTION
// =============================================================

class _DistrictSchoolSection extends StatelessWidget {
  const _DistrictSchoolSection({
    required this.repository,
    required this.district,
    required this.items,
  });

  final PengurusMobileRepository repository;
  final String district;
  final List<Map<String, dynamic>> items;

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Row(
          crossAxisAlignment: CrossAxisAlignment.end,
          children: [
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    district,
                    style: const TextStyle(
                      color: _pengurusText,
                      fontSize: 14,
                      fontWeight: FontWeight.w800,
                    ),
                  ),
                  const SizedBox(height: 3),
                  Text(
                    '${items.length} sekolah',
                    style: const TextStyle(
                      color: Color(0xFF64748B),
                      fontSize: 11,
                      fontWeight: FontWeight.w600,
                    ),
                  ),
                ],
              ),
            ),
            GestureDetector(
              onTap: () {
                Navigator.of(context).push(
                  MaterialPageRoute<void>(
                    builder: (_) => _DistrictSchoolsPage(
                      repository: repository,
                      district: district,
                      items: items,
                    ),
                  ),
                );
              },
              child: const Row(
                children: [
                  Text(
                    'Lihat semua',
                    style: TextStyle(
                      color: _pengurusPrimary,
                      fontSize: 12,
                      fontWeight: FontWeight.w800,
                    ),
                  ),
                  SizedBox(width: 2),
                  Icon(
                    Icons.arrow_forward_rounded,
                    size: 15,
                    color: _pengurusPrimary,
                  ),
                ],
              ),
            ),
          ],
        ),
        const SizedBox(height: 14),

        SizedBox(
          height: 115,
          child: ListView.separated(
            scrollDirection: Axis.horizontal,
            physics: const BouncingScrollPhysics(),
            padding: EdgeInsets.zero,
            itemCount: items.length,
            separatorBuilder: (_, __) => const SizedBox(width: 7),
            itemBuilder: (context, index) {
              final item = items[index];

              return SizedBox(
                width: 72,
                child: _SchoolDirectoryCard(
                  item: item,
                  onTap: () => _openSchool(
                    context,
                    item,
                  ),
                ),
              );
            },
          ),
        ),
      ],
    );
  }

  void _openSchool(
    BuildContext context,
    Map<String, dynamic> item,
  ) {
    Navigator.of(context).push(
      MaterialPageRoute<void>(
        builder: (_) => _SchoolDetailPage(
          repository: repository,
          schoolId: int.tryParse(
                item['id']?.toString() ?? '',
              ) ??
              0,
        ),
      ),
    );
  }
}

// =============================================================
// DISTRICT PAGE
// =============================================================

class _DistrictSchoolsPage extends StatelessWidget {
  const _DistrictSchoolsPage({
    required this.repository,
    required this.district,
    required this.items,
  });

  final PengurusMobileRepository repository;
  final String district;
  final List<Map<String, dynamic>> items;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF7F9FC),
      body: SafeArea(
        child: Column(
          children: [
            TeacherOverlayPageHeader(
              title: district,
              onBack: () => Navigator.of(context).pop(),
            ),
            Expanded(
              child: Transform.translate(
                offset: const Offset(0, -8),
                child: Container(
                  width: double.infinity,
                  decoration: const BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.only(
                      topLeft: Radius.circular(22),
                      topRight: Radius.circular(22),
                    ),
                  ),
                  child: ListView(
                    padding: const EdgeInsets.fromLTRB(
                      12,
                      30,
                      12,
                      28,
                    ),
                    children: [
                      LayoutBuilder(
                        builder: (context, constraints) {
                          return GridView.builder(
                            shrinkWrap: true,
                            physics: const NeverScrollableScrollPhysics(),
                            itemCount: items.length,
                            gridDelegate:
                                SliverGridDelegateWithFixedCrossAxisCount(
                              crossAxisCount:
                                  constraints.maxWidth >= 360 ? 4 : 3,
                              crossAxisSpacing: 10,
                              mainAxisSpacing: 12,
                              childAspectRatio: 0.65,
                            ),
                            itemBuilder: (_, index) => _SchoolServiceTile(
                              item: items[index],
                              onTap: () => Navigator.of(context).push(
                                MaterialPageRoute<void>(
                                  builder: (_) => _SchoolDetailPage(
                                    repository: repository,
                                    schoolId: int.tryParse(
                                          items[index]['id']?.toString() ?? '',
                                        ) ??
                                        0,
                                  ),
                                ),
                              ),
                            ),
                          );
                        },
                      ),
                    ],
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}

// =============================================================
// SCHOOL DIRECTORY CARD
// =============================================================

class _SchoolDirectoryCard extends StatelessWidget {
  const _SchoolDirectoryCard({
    required this.item,
    required this.onTap,
  });

  final Map<String, dynamic> item;
  final VoidCallback onTap;

  @override
  Widget build(BuildContext context) {
    final name = item['name']?.toString() ?? 'Sekolah';
    final scod = item['scod']?.toString() ?? '-';
    final logoUrl = _schoolLogoUrl(item);

    return Material(
      color: Colors.transparent,
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(16),
        child: Ink(
          width: 72,
          padding: const EdgeInsets.fromLTRB(
            5,
            7,
            5,
            2,
          ),
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(16),
            border: Border.all(
              color: const Color(0xFFF0F2F4),
              width: 1,
            ),
            boxShadow: [
              BoxShadow(
                color: const Color(0xFF002F25).withValues(
                  alpha: 0.06,
                ),
                blurRadius: 12,
                spreadRadius: -3,
                offset: const Offset(0, 5),
              ),
            ],
          ),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.start,
            crossAxisAlignment: CrossAxisAlignment.center,
            children: [
              // =====================================================
              // LOGO
              // =====================================================

              SizedBox(
                width: 48,
                height: 48,
                child: ClipRRect(
                  borderRadius: BorderRadius.circular(12),
                  child: Container(
                    padding: const EdgeInsets.all(3),
                    color: const Color(0xFFF7F9FC),
                    child: logoUrl != null && logoUrl.isNotEmpty
                        ? Image.network(
                      logoUrl,
                      fit: BoxFit.contain,
                      errorBuilder: (_, __, ___) {
                        return _SchoolLogoFallback(
                          name: name,
                        );
                      },
                    )
                        : _SchoolLogoFallback(
                      name: name,
                    ),
                  ),
                ),
              ),

              // Jarak logo → nama sangat dekat
              const SizedBox(height: 5),

              // =====================================================
              // NAMA SEKOLAH
              // =====================================================

              SizedBox(
                height: 27,
                child: Text(
                  name,
                  maxLines: 2,
                  overflow: TextOverflow.ellipsis,
                  textAlign: TextAlign.center,
                  style: const TextStyle(
                    color: _pengurusText,
                    fontSize: 9,
                    fontWeight: FontWeight.w700,
                    height: 1.05,
                  ),
                ),
              ),

              // Jarak nama → SCOD sangat dekat
              const SizedBox(height: 2),

              // =====================================================
              // SCOD
              // =====================================================

              Text(
                'SCOD$scod',
                maxLines: 1,
                overflow: TextOverflow.ellipsis,
                textAlign: TextAlign.center,
                style: const TextStyle(
                  color: Color(0xFF9AA5AE),
                  fontSize: 7.5,
                  fontWeight: FontWeight.w500,
                  height: 1,
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

// =============================================================
// SCHOOL SERVICE TILE
// =============================================================

class _SchoolServiceTile extends StatelessWidget {
  const _SchoolServiceTile({
    required this.item,
    this.onTap,
  });

  final Map<String, dynamic> item;
  final VoidCallback? onTap;

  @override
  Widget build(BuildContext context) {
    final name = item['name']?.toString() ?? 'Sekolah';

    final logoUrl = _schoolLogoUrl(item);

    return Material(
      color: Colors.transparent,
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(18),
        child: Ink(
          padding: const EdgeInsets.all(8),
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(18),
            boxShadow: const [
              BoxShadow(
                color: Color(0x12000000),
                blurRadius: 18,
                offset: Offset(0, 6),
              ),
            ],
          ),
          child: Column(
            children: [
              Container(
                width: 54,
                height: 54,
                padding: const EdgeInsets.all(5),
                decoration: const BoxDecoration(
                  color: Color(0xFFF7F9FC),
                  shape: BoxShape.circle,
                ),
                child: ClipOval(
                  child: logoUrl != null && logoUrl.isNotEmpty
                      ? Image.network(
                          logoUrl,
                          fit: BoxFit.cover,
                          errorBuilder: (_, __, ___) => _SchoolLogoFallback(
                            name: name,
                          ),
                        )
                      : _SchoolLogoFallback(
                          name: name,
                        ),
                ),
              ),
              const SizedBox(height: 8),
              Expanded(
                child: Text(
                  name,
                  maxLines: 2,
                  overflow: TextOverflow.ellipsis,
                  textAlign: TextAlign.center,
                  style: const TextStyle(
                    color: _pengurusText,
                    fontSize: 10,
                    fontWeight: FontWeight.w700,
                    height: 1.15,
                  ),
                ),
              ),
              Text(
                item['scod']?.toString().isNotEmpty == true
                    ? 'SCOD${item['scod']}'
                    : 'SCOD -',
                maxLines: 1,
                overflow: TextOverflow.ellipsis,
                style: const TextStyle(
                  color: Color(0xFF64748B),
                  fontSize: 8.5,
                  fontWeight: FontWeight.w600,
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

// =============================================================
// SCHOOL LOGO FALLBACK
// =============================================================

class _SchoolLogoFallback extends StatelessWidget {
  const _SchoolLogoFallback({
    this.name,
  });

  final String? name;

  @override
  Widget build(BuildContext context) {
    return DecoratedBox(
      decoration: const BoxDecoration(
        gradient: LinearGradient(
          colors: [
            _pengurusPrimary,
            Color(0xFF00553F),
          ],
        ),
      ),
      child: Center(
        child: Text(
          (name?.isNotEmpty ?? false)
              ? name!.substring(0, 1).toUpperCase()
              : 'S',
          style: const TextStyle(
            color: Colors.white,
            fontSize: 20,
            fontWeight: FontWeight.w800,
          ),
        ),
      ),
    );
  }
}

// =============================================================
// EMPTY STATE
// =============================================================

class _SchoolEmptyState extends StatelessWidget {
  const _SchoolEmptyState();

  @override
  Widget build(BuildContext context) {
    return const Padding(
      padding: EdgeInsets.symmetric(
        vertical: 34,
      ),
      child: Center(
        child: Column(
          children: [
            Icon(
              Icons.school_outlined,
              size: 40,
              color: Color(0xFF94A3B8),
            ),
            SizedBox(height: 10),
            Text(
              'Sekolah tidak ditemukan',
              style: TextStyle(
                color: Color(0xFF64748B),
                fontWeight: FontWeight.w700,
              ),
            ),
          ],
        ),
      ),
    );
  }
}

// =============================================================
// SCHOOL DETAIL PAGE
// =============================================================
/*

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

    _future = widget.repository.school(
      widget.schoolId,
    );
  }

  Future<void> _refresh() async {
    setState(() {
      _future = widget.repository.school(
        widget.schoolId,
      );
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
              return const Center(
                child: CircularProgressIndicator(),
              );
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

            final school = Map<String, dynamic>.from(
              data['school'] as Map? ?? {},
            );

            final headmaster = data['headmaster'] is Map
                ? Map<String, dynamic>.from(
                    data['headmaster'] as Map,
                  )
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
                    height: 140,
                    child: Stack(
                      clipBehavior: Clip.none,
                      children: [
                        Positioned.fill(
                          child: CustomPaint(
                            painter: _PremiumHeaderBackground(),
                          ),
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
                                    onPressed: () =>
                                        Navigator.of(context).pop(),
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
                                  padding: EdgeInsets.only(
                                    left: 56,
                                  ),
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
                        Positioned(
                          left: 14,
                          right: 14,
                          bottom: -30,
                          child: _SchoolDetailHero(
                            name: name,
                            scod: scod,
                            accreditation: accreditation,
                            logoUrl: logoUrl,
                          ),
                        ),
                      ],
                    ),
                  ),
                  Container(
                    width: double.infinity,
                    margin: const EdgeInsets.only(top: 10),
                    decoration: const BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.vertical(
                        top: Radius.circular(34),
                      ),
                    ),
                    child: Padding(
                      padding: const EdgeInsets.fromLTRB(14, 12, 14, 18),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          _SchoolDetailHero(
                            name: name,
                            scod: scod,
                            accreditation: accreditation,
                            logoUrl: logoUrl,
                          ),
                          const SizedBox(height: 16),
                          _SchoolTabs(
                            index: _tabIndex,
                            onChanged: (index) {
                              setState(() => _tabIndex = index);
                            },
                          ),
                          const SizedBox(height: 14),
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
                                _InfoRow(
                                  icon: Icons.school_outlined,
                                  label: 'Nama Sekolah',
                                  value: name,
                                ),
                                _InfoRow(
                                  icon: Icons.qr_code_rounded,
                                  label: 'SCOD',
                                  value: scod,
                                ),
                                _InfoRow(
                                  icon: Icons.location_city_outlined,
                                  label: 'Kabupaten',
                                  value:
                                      school['kabupaten']?.toString() ?? '-',
                                ),
                                _InfoRow(
                                  icon: Icons.apartment_outlined,
                                  label: 'Kecamatan',
                                  value:
                                      school['kecamatan']?.toString() ?? '-',
                                ),
                                _InfoRow(
                                  icon: Icons.location_on_outlined,
                                  label: 'Alamat',
                                  value: school['alamat']?.toString() ??
                                      'Alamat belum tersedia',
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
                                Text(
                                  'Sekolah ini merupakan sekolah menengah kejuruan yang berkomitmen mencetak lulusan berkualitas dan berakhlak mulia.',
                                  style: const TextStyle(
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
                                          minimumSize: const Size.fromHeight(
                                            52,
                                          ),
                                          side: const BorderSide(
                                            color: Color(0xFF7EC4B4),
                                            width: 1.5,
                                          ),
                                          shape: RoundedRectangleBorder(
                                            borderRadius:
                                                BorderRadius.circular(14),
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
                                          backgroundColor: const Color(
                                            0xFF0E8F6E,
                                          ),
                                          minimumSize:
                                              const Size.fromHeight(52),
                                          shape: RoundedRectangleBorder(
                                            borderRadius:
                                                BorderRadius.circular(14),
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
                ],
              ),
            );
          },
        ),
      ),
    );
  }
}

// =============================================================
// DETAIL EMPTY
// =============================================================

class _DetailEmpty extends StatelessWidget {
  const _DetailEmpty({
    required this.label,
  });

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

// =============================================================
// DETAIL WIDGETS
// =============================================================

class _SchoolTabs extends StatelessWidget {
  const _SchoolTabs({
    required this.index,
    required this.onChanged,
  });

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
                      color: selected
                          ? const Color(0xFF0E8F6E)
                          : const Color(0xFF8B95A7),
                      fontSize: 15,
                      fontWeight: FontWeight.w700,
                    ),
                  ),
                ),
                Container(
                  height: 3,
                  decoration: BoxDecoration(
                    color: selected
                        ? const Color(0xFF0E8F6E)
                        : Colors.transparent,
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
      padding: const EdgeInsets.symmetric(vertical: 11),
      child: Row(
        crossAxisAlignment:
            multiline ? CrossAxisAlignment.start : CrossAxisAlignment.center,
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
        style: TextStyle(
          color: foreground,
          fontSize: 13,
          fontWeight: FontWeight.w700,
        ),
      ),
    );
  }
}

// =============================================================
// SCHOOL DETAIL HERO
// =============================================================

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
              borderRadius: BorderRadius.circular(16),
            ),
            child: ClipRRect(
              borderRadius: BorderRadius.circular(12),
              child: logoUrl != null && logoUrl!.isNotEmpty
                  ? Image.network(
                      logoUrl!,
                      fit: BoxFit.contain,
                      errorBuilder: (_, __, ___) =>
                          const _SchoolLogoFallback(),
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

// =============================================================
// SCHOOL HERO METRICS
// =============================================================

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
            value: headmasterName?.trim().isNotEmpty == true
                ? headmasterName!
                : 'Belum ditetapkan',
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

// =============================================================
// DETAIL SECTION HEADING
// =============================================================

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

// =============================================================
// DATA TABLE
// =============================================================

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
            padding: const EdgeInsets.symmetric(
              horizontal: 12,
              vertical: 10,
            ),
            decoration: const BoxDecoration(
              color: Color(0xFFE5F5F0),
              borderRadius: BorderRadius.vertical(
                top: Radius.circular(23),
              ),
            ),
            child: _SchoolDataRow(
              values: headers,
              isHeader: true,
            ),
          ),
          ...rows.asMap().entries.map(
                (entry) => Container(
                  padding: const EdgeInsets.symmetric(
                    horizontal: 12,
                    vertical: 10,
                  ),
                  decoration: BoxDecoration(
                    border: entry.key == rows.length - 1
                        ? null
                        : const Border(
                            bottom: BorderSide(
                              color: Color(
                                0xFFDCE7E3,
                              ),
                            ),
                          ),
                  ),
                  child: _SchoolDataRow(
                    values: entry.value,
                  ),
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

// =============================================================
// PREMIUM HEADER BACKGROUND
// =============================================================
*/

class _PremiumHeaderBackground extends CustomPainter {
  @override
  void paint(
    Canvas canvas,
    Size size,
  ) {
    // =========================================================
    // PREMIUM BASE
    // Referensi: dark emerald / deep green
    // =========================================================

    final basePaint = Paint()..color = const Color(0xFF004838);

    canvas.drawRect(
      Offset.zero & size,
      basePaint,
    );

    // =========================================================
    // DARK TOP-RIGHT ANGULAR SHAPE
    // =========================================================

    final darkTopPaint = Paint()
      ..color = const Color(0xFF002F25).withValues(
        alpha: 0.72,
      );

    final darkTopPath = Path()
      ..moveTo(
        size.width * 0.28,
        0,
      )
      ..lineTo(
        size.width,
        0,
      )
      ..lineTo(
        size.width,
        size.height * 0.42,
      )
      ..quadraticBezierTo(
        size.width * 0.80,
        size.height * 0.25,
        size.width * 0.55,
        size.height * 0.13,
      )
      ..quadraticBezierTo(
        size.width * 0.39,
        size.height * 0.05,
        size.width * 0.28,
        0,
      )
      ..close();

    canvas.drawPath(
      darkTopPath,
      darkTopPaint,
    );

    // =========================================================
    // SECOND DIAGONAL LAYER
    // =========================================================

    final diagonalPaint = Paint()
      ..color = const Color(0xFF0B6650).withValues(
        alpha: 0.24,
      );

    final diagonalPath = Path()
      ..moveTo(
        size.width * 0.16,
        0,
      )
      ..quadraticBezierTo(
        size.width * 0.47,
        size.height * 0.10,
        size.width,
        size.height * 0.40,
      )
      ..lineTo(
        size.width,
        size.height * 0.57,
      )
      ..quadraticBezierTo(
        size.width * 0.58,
        size.height * 0.22,
        size.width * 0.16,
        size.height * 0.06,
      )
      ..close();

    canvas.drawPath(
      diagonalPath,
      diagonalPaint,
    );

    // =========================================================
    // SUBTLE LIGHT DIAGONAL
    // =========================================================

    final lightDiagonalPaint = Paint()
      ..color = Colors.white.withValues(
        alpha: 0.025,
      );

    final lightDiagonalPath = Path()
      ..moveTo(
        0,
        size.height * 0.02,
      )
      ..quadraticBezierTo(
        size.width * 0.40,
        size.height * 0.20,
        size.width,
        size.height * 0.06,
      )
      ..lineTo(
        size.width,
        size.height * 0.15,
      )
      ..quadraticBezierTo(
        size.width * 0.48,
        size.height * 0.30,
        0,
        size.height * 0.10,
      )
      ..close();

    canvas.drawPath(
      lightDiagonalPath,
      lightDiagonalPaint,
    );

    // =========================================================
    // BOTTOM ORGANIC SHAPE
    // =========================================================

    final bottomPaint = Paint()
      ..color = const Color(0xFF006E53).withValues(
        alpha: 0.22,
      );

    final bottomPath = Path()
      ..moveTo(
        0,
        size.height * 0.70,
      )
      ..quadraticBezierTo(
        size.width * 0.18,
        size.height * 0.54,
        size.width * 0.42,
        size.height * 0.69,
      )
      ..quadraticBezierTo(
        size.width * 0.72,
        size.height * 0.89,
        size.width,
        size.height * 0.62,
      )
      ..lineTo(
        size.width,
        size.height,
      )
      ..lineTo(
        0,
        size.height,
      )
      ..close();

    canvas.drawPath(
      bottomPath,
      bottomPaint,
    );

    // =========================================================
    // BOTTOM DARK SHADOW
    // =========================================================

    final bottomDarkPaint = Paint()
      ..color = const Color(0xFF00352A).withValues(
        alpha: 0.24,
      );

    final bottomDarkPath = Path()
      ..moveTo(
        0,
        size.height * 0.86,
      )
      ..quadraticBezierTo(
        size.width * 0.30,
        size.height * 0.68,
        size.width * 0.62,
        size.height * 0.87,
      )
      ..quadraticBezierTo(
        size.width * 0.84,
        size.height * 0.99,
        size.width,
        size.height * 0.78,
      )
      ..lineTo(
        size.width,
        size.height,
      )
      ..lineTo(
        0,
        size.height,
      )
      ..close();

    canvas.drawPath(
      bottomDarkPath,
      bottomDarkPaint,
    );

    // =========================================================
    // SOFT RADIAL GLOW
    // =========================================================

    final glowCenter = Offset(
      size.width * 0.88,
      size.height * 0.16,
    );

    final glowPaint = Paint()
      ..shader = RadialGradient(
        colors: [
          Colors.white.withValues(
            alpha: 0.055,
          ),
          Colors.white.withValues(
            alpha: 0.018,
          ),
          Colors.transparent,
        ],
        stops: const [
          0.0,
          0.35,
          1.0,
        ],
      ).createShader(
        Rect.fromCircle(
          center: glowCenter,
          radius: 150,
        ),
      );

    canvas.drawCircle(
      glowCenter,
      150,
      glowPaint,
    );

    // =========================================================
    // VERY SUBTLE TOP HIGHLIGHT
    // =========================================================

    final topHighlightPaint = Paint()
      ..shader = LinearGradient(
        begin: Alignment.topCenter,
        end: Alignment.bottomCenter,
        colors: [
          Colors.white.withValues(
            alpha: 0.018,
          ),
          Colors.transparent,
        ],
      ).createShader(
        Rect.fromLTWH(
          0,
          0,
          size.width,
          size.height * 0.55,
        ),
      );

    canvas.drawRect(
      Rect.fromLTWH(
        0,
        0,
        size.width,
        size.height * 0.55,
      ),
      topHighlightPaint,
    );
  }

  @override
  bool shouldRepaint(
    covariant CustomPainter oldDelegate,
  ) {
    return false;
  }
}
