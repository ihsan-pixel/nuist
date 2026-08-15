part of 'pengurus_shell_page.dart';

class _SchoolMonitor extends StatefulWidget { const _SchoolMonitor({required this.repository, required this.onBack}); final PengurusMobileRepository repository; final VoidCallback onBack; @override State<_SchoolMonitor> createState() => _SchoolMonitorState(); }
class _SchoolMonitorState extends State<_SchoolMonitor> {
  late Future<Map<String, dynamic>> _future;
  String _query = '';
  @override void initState() { super.initState(); _future = widget.repository.schools(); }
  Future<void> _refresh() async => setState(() => _future = widget.repository.schools());
  @override Widget build(BuildContext context) => Column(children: [
    TeacherOverlayPageHeader(title: 'Data Sekolah', onBack: widget.onBack),
    Expanded(child: Transform.translate(offset: const Offset(0, -8), child: Container(width: double.infinity, decoration: const BoxDecoration(color: Colors.white, borderRadius: BorderRadius.only(topLeft: Radius.circular(22), topRight: Radius.circular(22))), child: FutureBuilder<Map<String, dynamic>>(future: _future, builder: (context, snapshot) {
      if (snapshot.connectionState == ConnectionState.waiting) return const Center(child: CircularProgressIndicator());
      if (snapshot.hasError || !snapshot.hasData) return _LoadError(onRetry: _refresh);
      final allItems = _updateItems(snapshot.data!['items']);
      final keyword = _query.trim().toLowerCase();
      final items = keyword.isEmpty ? allItems : allItems.where((item) => '${item['name']} ${item['scod']} ${item['kabupaten']}'.toLowerCase().contains(keyword)).toList();
      final districts = <String, List<Map<String, dynamic>>>{};
      for (final item in items) {
        final district = _schoolDistrict(item);
        if (district == null) continue;
        districts.putIfAbsent(district, () => []).add(item);
      }
      final districtEntries = districts.entries.toList()
        ..sort((left, right) => _districtRank(left.key).compareTo(_districtRank(right.key)));
      for (final entry in districtEntries) {
        entry.value.sort((left, right) => _scodValue(left['scod']).compareTo(_scodValue(right['scod'])));
      }
      return RefreshIndicator(onRefresh: _refresh, child: ListView(padding: EdgeInsets.fromLTRB(14, 30, 14, MediaQuery.paddingOf(context).bottom + 128), children: [
        Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
          TextField(onChanged: (value) => setState(() => _query = value), textInputAction: TextInputAction.search, decoration: InputDecoration(hintText: 'Cari nama sekolah atau SCOD...', prefixIcon: const Icon(Icons.search_rounded, color: _pengurusPrimary), filled: true, fillColor: const Color(0xFFF7FAF9), contentPadding: const EdgeInsets.symmetric(vertical: 12), border: OutlineInputBorder(borderRadius: BorderRadius.circular(16), borderSide: BorderSide.none), enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(16), borderSide: const BorderSide(color: Color(0xFFDCE7E3))), focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(16), borderSide: const BorderSide(color: _pengurusPrimary, width: 1.5)))),
          const SizedBox(height: 18),
          if (districtEntries.isEmpty) const _SchoolEmptyState() else ...districtEntries.map((entry) => Padding(padding: const EdgeInsets.only(bottom: 20), child: _DistrictSchoolSection(repository: widget.repository, district: entry.key, items: entry.value))),
        ]),
      ]));
    })))),
  ]);
}

class _DistrictSchoolSection extends StatelessWidget {
  const _DistrictSchoolSection({required this.repository, required this.district, required this.items});
  final PengurusMobileRepository repository;
  final String district;
  final List<Map<String, dynamic>> items;

  @override
  Widget build(BuildContext context) => Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
    Row(children: [Expanded(child: Text(district, style: const TextStyle(color: _pengurusText, fontSize: 15, fontWeight: FontWeight.w800))), GestureDetector(onTap: () => Navigator.of(context).push(MaterialPageRoute<void>(builder: (_) => _DistrictSchoolsPage(repository: repository, district: district, items: items))), child: const Text('See All', style: TextStyle(color: _pengurusPrimary, fontSize: 12, fontWeight: FontWeight.w800)))]),
    const SizedBox(height: 10),
    SizedBox(height: 108, child: ListView.separated(scrollDirection: Axis.horizontal, physics: const BouncingScrollPhysics(), itemCount: items.take(4).length, separatorBuilder: (_, __) => const SizedBox(width: 14), itemBuilder: (_, index) => SizedBox(width: 84, child: _SchoolServiceTile(item: items[index], onTap: () => _openSchool(context, items[index]))))),
  ]);

  void _openSchool(BuildContext context, Map<String, dynamic> item) => Navigator.of(context).push(MaterialPageRoute<void>(builder: (_) => _SchoolDetailPage(repository: repository, schoolId: int.tryParse(item['id']?.toString() ?? '') ?? 0)));
}

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
  Widget build(BuildContext context) => Scaffold(
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
                      padding: const EdgeInsets.fromLTRB(12, 30, 12, 28),
                      children: [
                        GridView.builder(
                          shrinkWrap: true,
                          physics: const NeverScrollableScrollPhysics(),
                          itemCount: items.length,
                          gridDelegate:
                              const SliverGridDelegateWithFixedCrossAxisCount(
                            crossAxisCount: 4,
                            crossAxisSpacing: 8,
                            mainAxisSpacing: 16,
                            childAspectRatio: 0.72,
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

class _SchoolServiceTile extends StatelessWidget {
  const _SchoolServiceTile({required this.item, this.onTap});
  final Map<String, dynamic> item;
  final VoidCallback? onTap;

  @override
  Widget build(BuildContext context) {
    final name = item['name']?.toString() ?? 'Sekolah';
    final logoUrl = _schoolLogoUrl(item);
    return InkWell(onTap: onTap, borderRadius: BorderRadius.circular(18), child: Column(children: [
      Container(width: 58, height: 58, padding: const EdgeInsets.all(3), decoration: const BoxDecoration(color: Colors.white, shape: BoxShape.circle, boxShadow: [BoxShadow(color: Color(0x14172A24), blurRadius: 12, offset: Offset(0, 4))]), child: ClipOval(child: logoUrl != null && logoUrl.isNotEmpty ? Image.network(logoUrl, fit: BoxFit.cover, errorBuilder: (_, __, ___) => _SchoolLogoFallback(name: name)) : _SchoolLogoFallback(name: name))),
      const SizedBox(height: 7),
      Text(name, maxLines: 2, overflow: TextOverflow.ellipsis, textAlign: TextAlign.center, style: const TextStyle(color: _pengurusText, fontSize: 10, fontWeight: FontWeight.w700, height: 1.1)),
    ]));
  }
}

class _SchoolLogoFallback extends StatelessWidget { const _SchoolLogoFallback({required this.name}); final String name; @override Widget build(BuildContext context) => DecoratedBox(decoration: const BoxDecoration(gradient: LinearGradient(colors: [_pengurusPrimary, Color(0xFF00553F)])), child: Center(child: Text(name.isNotEmpty ? name.substring(0, 1).toUpperCase() : 'S', style: const TextStyle(color: Colors.white, fontSize: 20, fontWeight: FontWeight.w800)))); }
class _SchoolEmptyState extends StatelessWidget { const _SchoolEmptyState(); @override Widget build(BuildContext context) => const Padding(padding: EdgeInsets.symmetric(vertical: 34), child: Center(child: Column(children: [Icon(Icons.school_outlined, size: 40, color: Color(0xFF94A3B8)), SizedBox(height: 10), Text('Sekolah tidak ditemukan', style: TextStyle(color: Color(0xFF64748B), fontWeight: FontWeight.w700))]))); }

class _SchoolDetailPage extends StatefulWidget { const _SchoolDetailPage({required this.repository, required this.schoolId}); final PengurusMobileRepository repository; final int schoolId; @override State<_SchoolDetailPage> createState() => _SchoolDetailPageState(); }
class _SchoolDetailPageState extends State<_SchoolDetailPage> {
  late Future<Map<String, dynamic>> _future;
  @override void initState() { super.initState(); _future = widget.repository.school(widget.schoolId); }
  Future<void> _refresh() async => setState(() => _future = widget.repository.school(widget.schoolId));
  @override Widget build(BuildContext context) => Scaffold(backgroundColor: const Color(0xFFF7F9FC), body: SafeArea(child: Column(children: [TeacherOverlayPageHeader(title: 'Detail Sekolah', onBack: () => Navigator.of(context).pop(), backgroundColor: Colors.white, foregroundColor: const Color(0xFF00553F)), Expanded(child: FutureBuilder<Map<String, dynamic>>(future: _future, builder: (context, snapshot) {
    if (snapshot.connectionState == ConnectionState.waiting) return const Center(child: CircularProgressIndicator());
    if (snapshot.hasError || !snapshot.hasData) return _LoadError(onRetry: _refresh, title: 'Detail sekolah belum dapat dimuat', message: 'Data sekolah belum tersedia. Periksa koneksi lalu coba lagi.');
    final data = snapshot.data!; final school = Map<String, dynamic>.from(data['school'] as Map? ?? {}); final headmaster = data['headmaster'] is Map ? Map<String, dynamic>.from(data['headmaster'] as Map) : null; final teachers = _updateItems(data['teachers']); final students = _updateItems(data['students']); final name = school['name']?.toString() ?? 'Sekolah'; final logoUrl = _schoolLogoUrl(school);
    return RefreshIndicator(onRefresh: _refresh, child: Transform.translate(offset: const Offset(0, -8), child: ListView(padding: EdgeInsets.zero, children: [
      Container(height: 224, width: double.infinity, clipBehavior: Clip.antiAlias, decoration: const BoxDecoration(color: Color(0xFF00553F), borderRadius: BorderRadius.vertical(top: Radius.circular(28))), child: Stack(children: [Padding(padding: const EdgeInsets.fromLTRB(24, 18, 24, 0), child: _SchoolDetailHero(name: name, district: school['kabupaten']?.toString() ?? '-', address: school['alamat']?.toString() ?? 'Alamat belum tersedia', logoUrl: logoUrl)), Positioned(left: 0, right: 0, bottom: 0, child: _SchoolHeroMetrics(teacherCount: '${data['teacher_count'] ?? 0}', studentCount: '${data['student_count'] ?? 0}'))])),
      Transform.translate(offset: const Offset(0, -18), child: Padding(padding: const EdgeInsets.only(bottom: 28), child: Container(width: double.infinity, padding: const EdgeInsets.fromLTRB(20, 28, 20, 22), decoration: const BoxDecoration(color: Colors.white, borderRadius: BorderRadius.vertical(top: Radius.circular(30))), child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
      const SizedBox(height: 14), const _SectionTitle(title: 'Kepala Sekolah'), const SizedBox(height: 8), AppSectionCard(padding: const EdgeInsets.all(14), child: ListTile(contentPadding: EdgeInsets.zero, leading: const CircleAvatar(backgroundColor: Color(0xFFE5F5F0), child: Icon(Icons.person_rounded, color: _pengurusPrimary)), title: Text(headmaster?['name']?.toString() ?? 'Belum ditetapkan', style: const TextStyle(fontWeight: FontWeight.w800)), subtitle: Text(headmaster?['position']?.toString() ?? 'Data kepala sekolah belum tersedia'))),
      const SizedBox(height: 22), const _SchoolDetailSectionHeading(eyebrow: 'SDM', title: 'Data Tenaga Pendidik'), const SizedBox(height: 9), if (teachers.isEmpty) const _DetailEmpty(label: 'Belum ada data tenaga pendidik.') else _SchoolDataTable(headers: const ['NAMA', 'JABATAN'], rows: teachers.map((teacher) => [teacher['name']?.toString() ?? '-', teacher['position']?.toString() ?? 'Tenaga Pendidik']).toList()),
      const SizedBox(height: 20), const _SchoolDetailSectionHeading(eyebrow: 'PESERTA DIDIK', title: 'Data Siswa'), const SizedBox(height: 9), if (students.isEmpty) const _DetailEmpty(label: 'Belum ada data siswa aktif.') else _SchoolDataTable(headers: const ['NAMA SISWA', 'KELAS'], rows: students.map((student) => [student['name']?.toString() ?? '-', student['class']?.toString().isNotEmpty == true ? student['class'].toString() : 'Siswa aktif']).toList()), if (data['students_preview_limited'] == true) const Padding(padding: EdgeInsets.only(top: 8), child: Text('Menampilkan 30 siswa pertama.', style: TextStyle(color: Color(0xFF64748B), fontSize: 11))),
        ])))),
    ])));
  }))])));
}
class _DetailCount extends StatelessWidget { const _DetailCount({required this.label, required this.value, required this.icon}); final String label, value; final IconData icon; @override Widget build(BuildContext context) => AppSectionCard(padding: const EdgeInsets.all(12), child: Row(children: [Icon(icon, color: _pengurusPrimary), const SizedBox(width: 8), Expanded(child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [Text(value, style: const TextStyle(color: _pengurusText, fontSize: 18, fontWeight: FontWeight.w800)), Text(label, style: const TextStyle(color: Color(0xFF64748B), fontSize: 10))]))])); }
class _PersonTile extends StatelessWidget { const _PersonTile({required this.name, required this.subtitle, required this.icon}); final String name, subtitle; final IconData icon; @override Widget build(BuildContext context) => AppSectionCard(padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 9), child: ListTile(contentPadding: EdgeInsets.zero, leading: CircleAvatar(backgroundColor: const Color(0xFFE5F5F0), child: Icon(icon, color: _pengurusPrimary, size: 20)), title: Text(name, style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w800)), subtitle: Text(subtitle, style: const TextStyle(fontSize: 11)))); }
class _DetailEmpty extends StatelessWidget { const _DetailEmpty({required this.label}); final String label; @override Widget build(BuildContext context) => AppSectionCard(padding: const EdgeInsets.all(14), child: Text(label, style: const TextStyle(color: Color(0xFF64748B), fontSize: 12))); }

class _SchoolDetailHero extends StatelessWidget {
  const _SchoolDetailHero({required this.name, required this.district, required this.address, required this.logoUrl});
  final String name;
  final String district;
  final String address;
  final String? logoUrl;

  @override
  Widget build(BuildContext context) => Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(children: [
            Container(width: 62, height: 62, padding: const EdgeInsets.all(3), decoration: const BoxDecoration(color: Colors.white, shape: BoxShape.circle), child: ClipOval(child: logoUrl != null && logoUrl!.isNotEmpty ? Image.network(logoUrl!, fit: BoxFit.cover, errorBuilder: (_, __, ___) => _SchoolLogoFallback(name: name)) : _SchoolLogoFallback(name: name))),
            const SizedBox(width: 12),
            Expanded(child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [Text(name, maxLines: 2, overflow: TextOverflow.ellipsis, style: const TextStyle(color: Colors.white, fontSize: 18, fontWeight: FontWeight.w800, height: 1.1)), const SizedBox(height: 4), Text(district, maxLines: 1, overflow: TextOverflow.ellipsis, style: const TextStyle(color: Color(0xCCFFFFFF), fontSize: 12, fontWeight: FontWeight.w600))])),
          ]),
          const SizedBox(height: 13),
          Row(children: [const Icon(Icons.location_on_rounded, size: 17, color: Color(0xD9FFFFFF)), const SizedBox(width: 6), Expanded(child: Text(address, maxLines: 1, overflow: TextOverflow.ellipsis, style: const TextStyle(color: Color(0xD9FFFFFF), fontSize: 11, fontWeight: FontWeight.w600)))]),
        ],
      );
}

class _SchoolHeroMetrics extends StatelessWidget {
  const _SchoolHeroMetrics({required this.teacherCount, required this.studentCount});
  final String teacherCount;
  final String studentCount;

  @override
  Widget build(BuildContext context) => Container(
        width: double.infinity,
        height: 86,
        padding: const EdgeInsets.symmetric(horizontal: 28, vertical: 12),
        decoration: BoxDecoration(color: Colors.black.withValues(alpha: 0.18), borderRadius: const BorderRadius.vertical(top: Radius.circular(30)), border: Border.all(color: Colors.white.withValues(alpha: 0.10))),
        child: Row(children: [const Expanded(child: _SchoolHeroMetric(value: '1', label: 'SEKOLAH')), const _HeroDivider(), Expanded(child: _SchoolHeroMetric(value: teacherCount, label: 'GURU')), const _HeroDivider(), Expanded(child: _SchoolHeroMetric(value: studentCount, label: 'SISWA'))]),
      );
}

class _SchoolHeroMetric extends StatelessWidget {
  const _SchoolHeroMetric({required this.value, required this.label});
  final String value;
  final String label;
  @override Widget build(BuildContext context) => Column(crossAxisAlignment: CrossAxisAlignment.start, children: [Text(value, style: const TextStyle(color: Colors.white, fontSize: 20, fontWeight: FontWeight.w800)), const SizedBox(height: 2), Text(label, style: const TextStyle(color: Color(0xCCFFFFFF), fontSize: 8.5, fontWeight: FontWeight.w800, letterSpacing: 0.5))]);
}

class _HeroDivider extends StatelessWidget { const _HeroDivider(); @override Widget build(BuildContext context) => Container(width: 1, height: 30, margin: const EdgeInsets.symmetric(horizontal: 10), color: const Color(0x40FFFFFF)); }

class _SchoolDetailSectionHeading extends StatelessWidget {
  const _SchoolDetailSectionHeading({required this.eyebrow, required this.title});
  final String eyebrow;
  final String title;

  @override
  Widget build(BuildContext context) => Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(eyebrow, style: const TextStyle(color: _pengurusPrimary, fontSize: 9, fontWeight: FontWeight.w800, letterSpacing: 0.8)),
          const SizedBox(height: 2),
          Text(title, style: const TextStyle(color: _pengurusText, fontSize: 16, fontWeight: FontWeight.w800)),
        ],
      );
}

class _SchoolDataTable extends StatelessWidget {
  const _SchoolDataTable({required this.headers, required this.rows});
  final List<String> headers;
  final List<List<String>> rows;

  @override
  Widget build(BuildContext context) => AppSectionCard(
        padding: EdgeInsets.zero,
        child: Column(
          children: [
            Container(
              width: double.infinity,
              padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
              decoration: const BoxDecoration(color: Color(0xFFE5F5F0), borderRadius: BorderRadius.vertical(top: Radius.circular(23))),
              child: _SchoolDataRow(values: headers, isHeader: true),
            ),
            ...rows.asMap().entries.map((entry) => Container(
              padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
              decoration: BoxDecoration(border: entry.key == rows.length - 1 ? null : const Border(bottom: BorderSide(color: Color(0xFFDCE7E3)))),
              child: _SchoolDataRow(values: entry.value),
            )),
          ],
        ),
      );
}

class _SchoolDataRow extends StatelessWidget {
  const _SchoolDataRow({required this.values, this.isHeader = false});
  final List<String> values;
  final bool isHeader;

  @override
  Widget build(BuildContext context) {
    final style = TextStyle(color: isHeader ? const Color(0xFF00553F) : _pengurusText, fontSize: isHeader ? 9 : 11, fontWeight: isHeader ? FontWeight.w800 : FontWeight.w700, height: 1.25);
    return Row(children: [
      Expanded(flex: 3, child: Text(values.isNotEmpty ? values.first : '-', maxLines: 2, overflow: TextOverflow.ellipsis, style: style)),
      const SizedBox(width: 10),
      Expanded(flex: 2, child: Text(values.length > 1 ? values[1] : '-', textAlign: TextAlign.right, maxLines: 2, overflow: TextOverflow.ellipsis, style: style)),
    ]);
  }
}

