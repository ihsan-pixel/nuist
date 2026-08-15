import 'package:flutter/material.dart';

import '../../controllers/session_controller.dart';
import '../../config/app_config.dart';
import '../../services/pengurus_mobile_repository.dart';
import '../../widgets/app/app_section_card.dart';
import '../../widgets/app/teacher_bottom_nav.dart';
import '../../widgets/app/teacher_page_header.dart';

const _pengurusPrimary = Color(0xFF00745A);
const _pengurusText = Color(0xFF172A24);

class PengurusShellPage extends StatefulWidget {
  const PengurusShellPage({super.key, required this.controller, required this.repository});
  final SessionController controller;
  final PengurusMobileRepository repository;

  @override
  State<PengurusShellPage> createState() => _PengurusShellPageState();
}

class _PengurusShellPageState extends State<PengurusShellPage> {
  int _tab = 0;

  static const _navItems = <TeacherBottomNavItem>[
    TeacherBottomNavItem(label: 'Beranda', icon: Icons.home_rounded, isCenter: false),
    TeacherBottomNavItem(label: 'Sekolah', icon: Icons.school_rounded, isCenter: false),
    TeacherBottomNavItem(label: 'Layanan', icon: Icons.grid_view_rounded, isCenter: true),
    TeacherBottomNavItem(label: 'Notifikasi', icon: Icons.notifications_none_rounded, isCenter: false),
    TeacherBottomNavItem(label: 'Profil', icon: Icons.person_rounded, isCenter: false),
  ];

  @override
  Widget build(BuildContext context) {
    final pages = [
      _PengurusDashboard(
        repository: widget.repository,
        userName: widget.controller.session?.user.name ?? 'Pengurus',
        onOpenSchools: () => setState(() => _tab = 1),
        onOpenProfile: () => setState(() => _tab = 4),
        onLogout: widget.controller.logout,
      ),
      _SchoolMonitor(repository: widget.repository, onBack: () => setState(() => _tab = 0)),
      _ServiceHubPage(onOpenSchools: () => setState(() => _tab = 1)),
      const _NotificationsPage(),
      _PengurusProfile(controller: widget.controller),
    ];

    return Scaffold(
      extendBody: true,
      backgroundColor: Colors.white,
      body: SafeArea(
        bottom: false,
        child: IndexedStack(index: _tab, children: pages),
      ),
      bottomNavigationBar: TeacherBottomNav(
        items: _navItems,
        currentIndex: _tab,
        onSelect: (value) => setState(() => _tab = value),
      ),
    );
  }
}

class _PengurusDashboard extends StatefulWidget {
  const _PengurusDashboard({required this.repository, required this.userName, required this.onOpenSchools, required this.onOpenProfile, required this.onLogout});
  final PengurusMobileRepository repository;
  final String userName;
  final VoidCallback onOpenSchools;
  final VoidCallback onOpenProfile;
  final Future<void> Function() onLogout;

  @override
  State<_PengurusDashboard> createState() => _PengurusDashboardState();
}

class _PengurusDashboardState extends State<_PengurusDashboard> {
  late Future<Map<String, dynamic>> _future;
  final _scrollController = ScrollController();
  bool _hasScrolled = false;

  @override
  void initState() {
    super.initState();
    _future = widget.repository.dashboard();
    _scrollController.addListener(() {
      final hasScrolled = _scrollController.offset > 14;
      if (hasScrolled != _hasScrolled && mounted) setState(() => _hasScrolled = hasScrolled);
    });
  }

  @override
  void dispose() { _scrollController.dispose(); super.dispose(); }
  Future<void> _refresh() async => setState(() => _future = widget.repository.dashboard());

  @override
  Widget build(BuildContext context) => FutureBuilder<Map<String, dynamic>>(
    future: _future,
    builder: (context, snapshot) {
      if (snapshot.connectionState == ConnectionState.waiting) return const Center(child: CircularProgressIndicator());
      if (snapshot.hasError || !snapshot.hasData) return _LoadError(onRetry: _refresh);
      final data = snapshot.data!;
      final summary = Map<String, dynamic>.from(data['summary'] as Map? ?? {});
      final uppmUpdates = _updateItems(data['recent_uppm_updates']);
      final sppUpdates = _updateItems(data['recent_spp_updates']);
      return RefreshIndicator(
        color: _pengurusPrimary,
        onRefresh: _refresh,
        child: Stack(children: [
          ListView(
          controller: _scrollController,
          physics: const AlwaysScrollableScrollPhysics(),
          padding: const EdgeInsets.only(bottom: 28),
          children: [
            const _PengurusTopBackdrop(),
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              child: Transform.translate(offset: const Offset(0, -196), child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
            _OperationalSummary(summary: summary, onSearch: widget.onOpenSchools),
            const SizedBox(height: 14),
            _DashboardServices(onOpenSchools: widget.onOpenSchools),
            const SizedBox(height: 18),
            _RecentUpdatesSection(title: 'Update Data UPPM', icon: Icons.account_balance_rounded, items: uppmUpdates, onSeeAll: () => Navigator.of(context).push(MaterialPageRoute<void>(builder: (_) => _UpdatesPage(repository: widget.repository, isUppm: true)))),
            const SizedBox(height: 18),
            _RecentUpdatesSection(title: 'Update SPP Siswa', icon: Icons.receipt_long_rounded, items: sppUpdates, onSeeAll: () => Navigator.of(context).push(MaterialPageRoute<void>(builder: (_) => _UpdatesPage(repository: widget.repository, isUppm: false)))),
            const SizedBox(height: 20),
              ])),
            ),
          ],
        ),
        Positioned(top: 0, left: 0, right: 0, child: AnimatedContainer(
          duration: const Duration(milliseconds: 180),
          curve: Curves.easeOut,
          decoration: BoxDecoration(color: _hasScrolled ? Colors.white : Colors.transparent, boxShadow: _hasScrolled ? const [BoxShadow(color: Color(0x14172A24), blurRadius: 20, offset: Offset(0, 8))] : const []),
          padding: const EdgeInsets.fromLTRB(16, 16, 16, 10),
          child: _PengurusHeader(userName: widget.userName, isScrolled: _hasScrolled, onProfile: widget.onOpenProfile, onLogout: widget.onLogout),
        )),
        ]),
      );
    },
  );

}

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
  @override Widget build(BuildContext context) => Scaffold(backgroundColor: const Color(0xFFF7F9FC), body: SafeArea(child: Column(children: [TeacherOverlayPageHeader(title: 'Detail Sekolah', onBack: () => Navigator.of(context).pop(), backgroundColor: Colors.white, foregroundColor: const Color(0xFF00553F), borderRadius: const BorderRadius.vertical(bottom: Radius.circular(28))), Expanded(child: FutureBuilder<Map<String, dynamic>>(future: _future, builder: (context, snapshot) {
    if (snapshot.connectionState == ConnectionState.waiting) return const Center(child: CircularProgressIndicator());
    if (snapshot.hasError || !snapshot.hasData) return _LoadError(onRetry: _refresh, title: 'Detail sekolah belum dapat dimuat', message: 'Data sekolah belum tersedia. Periksa koneksi lalu coba lagi.');
    final data = snapshot.data!; final school = Map<String, dynamic>.from(data['school'] as Map? ?? {}); final headmaster = data['headmaster'] is Map ? Map<String, dynamic>.from(data['headmaster'] as Map) : null; final teachers = _updateItems(data['teachers']); final students = _updateItems(data['students']); final name = school['name']?.toString() ?? 'Sekolah'; final logoUrl = _schoolLogoUrl(school);
    return RefreshIndicator(onRefresh: _refresh, child: ListView(padding: EdgeInsets.zero, children: [
      Container(height: 224, width: double.infinity, color: const Color(0xFF00553F), child: Stack(children: [Padding(padding: const EdgeInsets.fromLTRB(24, 18, 24, 0), child: _SchoolDetailHero(name: name, district: school['kabupaten']?.toString() ?? '-', address: school['alamat']?.toString() ?? 'Alamat belum tersedia', logoUrl: logoUrl)), Positioned(left: 0, right: 0, bottom: 0, child: _SchoolHeroMetrics(teacherCount: '${data['teacher_count'] ?? 0}', studentCount: '${data['student_count'] ?? 0}'))])),
      Transform.translate(offset: const Offset(0, -18), child: Padding(padding: const EdgeInsets.only(bottom: 28), child: Container(width: double.infinity, padding: const EdgeInsets.fromLTRB(20, 28, 20, 22), decoration: const BoxDecoration(color: Colors.white, borderRadius: BorderRadius.vertical(top: Radius.circular(30))), child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
      const SizedBox(height: 14), const _SectionTitle(title: 'Kepala Sekolah'), const SizedBox(height: 8), AppSectionCard(padding: const EdgeInsets.all(14), child: ListTile(contentPadding: EdgeInsets.zero, leading: const CircleAvatar(backgroundColor: Color(0xFFE5F5F0), child: Icon(Icons.person_rounded, color: _pengurusPrimary)), title: Text(headmaster?['name']?.toString() ?? 'Belum ditetapkan', style: const TextStyle(fontWeight: FontWeight.w800)), subtitle: Text(headmaster?['position']?.toString() ?? 'Data kepala sekolah belum tersedia'))),
      const SizedBox(height: 22), const _SchoolDetailSectionHeading(eyebrow: 'SDM', title: 'Data Tenaga Pendidik'), const SizedBox(height: 9), if (teachers.isEmpty) const _DetailEmpty(label: 'Belum ada data tenaga pendidik.') else _SchoolDataTable(headers: const ['NAMA', 'JABATAN'], rows: teachers.map((teacher) => [teacher['name']?.toString() ?? '-', teacher['position']?.toString() ?? 'Tenaga Pendidik']).toList()),
      const SizedBox(height: 20), const _SchoolDetailSectionHeading(eyebrow: 'PESERTA DIDIK', title: 'Data Siswa'), const SizedBox(height: 9), if (students.isEmpty) const _DetailEmpty(label: 'Belum ada data siswa aktif.') else _SchoolDataTable(headers: const ['NAMA SISWA', 'KELAS'], rows: students.map((student) => [student['name']?.toString() ?? '-', student['class']?.toString().isNotEmpty == true ? student['class'].toString() : 'Siswa aktif']).toList()), if (data['students_preview_limited'] == true) const Padding(padding: EdgeInsets.only(top: 8), child: Text('Menampilkan 30 siswa pertama.', style: TextStyle(color: Color(0xFF64748B), fontSize: 11))),
        ])))),
    ]));
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

class _PengurusProfile extends StatelessWidget { const _PengurusProfile({required this.controller}); final SessionController controller; @override Widget build(BuildContext context) { final user = controller.session?.user; return ListView(padding: const EdgeInsets.all(16), children: [_PageHeading(title: 'Profil Pengurus', subtitle: 'Pengaturan akun monitoring'), const SizedBox(height: 18), AppSectionCard(child: ListTile(contentPadding: EdgeInsets.zero, leading: const CircleAvatar(radius: 26, backgroundColor: Color(0xFFE5F5F0), child: Icon(Icons.manage_accounts_rounded, color: _pengurusPrimary)), title: Text(user?.name ?? 'Pengurus', style: const TextStyle(fontWeight: FontWeight.w800)), subtitle: Text(user?.email ?? '-'))), const SizedBox(height: 14), const AppSectionCard(child: Text('Akses ini khusus monitoring dan bersifat read-only.', style: TextStyle(color: Color(0xFF52635C)))), const SizedBox(height: 18), OutlinedButton.icon(onPressed: controller.logout, icon: const Icon(Icons.logout_rounded), label: const Text('Keluar dari akun'))]); } }

class _ServiceHubPage extends StatelessWidget {
  const _ServiceHubPage({required this.onOpenSchools});
  final VoidCallback onOpenSchools;

  @override
  Widget build(BuildContext context) => ListView(padding: const EdgeInsets.all(16), children: [
    const _PageHeading(title: 'Layanan Pengurus', subtitle: 'Akses monitoring dan administrasi'),
    const SizedBox(height: 18),
    _ServiceGrid(onOpenSchools: onOpenSchools, expanded: true),
  ]);
}

class _NotificationsPage extends StatelessWidget {
  const _NotificationsPage();

  @override
  Widget build(BuildContext context) => ListView(padding: const EdgeInsets.all(16), children: const [
    _PageHeading(title: 'Notifikasi', subtitle: 'Informasi yang memerlukan perhatian'),
    SizedBox(height: 18),
    AppSectionCard(child: Column(children: [Icon(Icons.notifications_none_rounded, size: 42, color: Color(0xFF94A3B8)), SizedBox(height: 12), Text('Belum ada notifikasi', style: TextStyle(fontWeight: FontWeight.w800)), SizedBox(height: 4), Text('Notifikasi prioritas akan tampil di halaman ini.', textAlign: TextAlign.center, style: TextStyle(color: Color(0xFF64748B), fontSize: 12))])),
  ]);
}

class _PengurusTopBackdrop extends StatelessWidget {
  const _PengurusTopBackdrop();

  @override
  Widget build(BuildContext context) => SizedBox(
    height: 288,
    child: Container(
      height: 250,
      decoration: const BoxDecoration(
        gradient: LinearGradient(colors: [Color(0xFF00553F), _pengurusPrimary], begin: Alignment.topCenter, end: Alignment.bottomCenter),
        boxShadow: [BoxShadow(color: Color(0x14172A24), blurRadius: 20, offset: Offset(0, 10))],
      ),
    ),
  );
}

class _PengurusHeader extends StatelessWidget {
  const _PengurusHeader({required this.userName, required this.isScrolled, required this.onProfile, required this.onLogout});
  final String userName;
  final bool isScrolled;
  final VoidCallback onProfile;
  final Future<void> Function() onLogout;

  @override
  Widget build(BuildContext context) {
    final foreground = isScrolled ? _pengurusText : Colors.white;
    final muted = isScrolled ? const Color(0xFF64748B) : Colors.white70;
    final iconColor = isScrolled ? const Color(0xFF00553F) : Colors.white;
    return Row(children: [
      Container(width: 52, height: 52, padding: const EdgeInsets.all(2), decoration: BoxDecoration(shape: BoxShape.circle, color: Colors.white, border: Border.all(color: Colors.white, width: 1.5), boxShadow: const [BoxShadow(color: Color(0x14172A24), blurRadius: 14, offset: Offset(0, 6))]), child: const CircleAvatar(backgroundColor: _pengurusPrimary, child: Icon(Icons.manage_accounts_rounded, color: Colors.white))),
      const SizedBox(width: 12),
      Expanded(child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
        Text('NUIST DIY', maxLines: 1, overflow: TextOverflow.ellipsis, style: TextStyle(color: muted, fontSize: 12, fontWeight: FontWeight.w600)),
        const SizedBox(height: 4),
        Text(userName, maxLines: 1, overflow: TextOverflow.ellipsis, style: TextStyle(color: foreground, fontSize: 16, fontWeight: FontWeight.w800, height: 1.05)),
      ])),
      PopupMenuButton<String>(
        tooltip: 'Menu',
        padding: EdgeInsets.zero,
        elevation: 12,
        color: Colors.white,
        surfaceTintColor: Colors.white,
        offset: const Offset(0, 34),
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(18)),
        onSelected: (value) async {
          if (value == 'profile') onProfile();
          if (value == 'logout') await onLogout();
        },
        itemBuilder: (_) => const [
          PopupMenuItem(value: 'profile', child: _PengurusHeaderMenuItem(icon: Icons.person_outline_rounded, label: 'Profil')),
          PopupMenuItem(value: 'logout', child: _PengurusHeaderMenuItem(icon: Icons.logout_rounded, label: 'Keluar', isDestructive: true)),
        ],
        child: SizedBox(width: 22, height: 22, child: Center(child: Icon(Icons.more_vert_rounded, color: iconColor, size: 22))),
      ),
    ]);
  }
}
class _PengurusHeaderMenuItem extends StatelessWidget {
  const _PengurusHeaderMenuItem({required this.icon, required this.label, this.isDestructive = false});
  final IconData icon;
  final String label;
  final bool isDestructive;

  @override
  Widget build(BuildContext context) {
    final color = isDestructive ? const Color(0xFFEF4444) : const Color(0xFF00553F);
    return Row(children: [Icon(icon, size: 20, color: color), const SizedBox(width: 10), Text(label, style: TextStyle(color: color, fontWeight: FontWeight.w700))]);
  }
}
class _PageHeading extends StatelessWidget { const _PageHeading({required this.title, required this.subtitle}); final String title, subtitle; @override Widget build(BuildContext context) => Column(crossAxisAlignment: CrossAxisAlignment.start, children: [Text(title, style: const TextStyle(color: _pengurusText, fontSize: 23, fontWeight: FontWeight.w800)), const SizedBox(height: 4), Text(subtitle, style: const TextStyle(color: Color(0xFF64748B)))]); }
class _SectionTitle extends StatelessWidget { const _SectionTitle({required this.title}); final String title; @override Widget build(BuildContext context) => Text(title, style: const TextStyle(color: _pengurusText, fontSize: 16, fontWeight: FontWeight.w800)); }
class _DashboardServices extends StatelessWidget {
  const _DashboardServices({required this.onOpenSchools});
  final VoidCallback onOpenSchools;

  @override
  Widget build(BuildContext context) {
    final messenger = ScaffoldMessenger.of(context);
    void soon(String label) => messenger.showSnackBar(SnackBar(content: Text('$label akan segera tersedia.'), behavior: SnackBarBehavior.floating));
    final items = [
      _ServiceItem('Data Sekolah', Icons.school_rounded, const Color(0xFFE5F5F0), _pengurusPrimary, onOpenSchools),
      _ServiceItem('Presensi', Icons.fact_check_rounded, const Color(0xFFE9F2FF), const Color(0xFF2563EB), () => soon('Monitoring presensi')),
      _ServiceItem('Jurnal', Icons.menu_book_rounded, const Color(0xFFF3E8FF), const Color(0xFF7E22CE), () => soon('Jurnal mengajar')),
      _ServiceItem('Pengajuan SK', Icons.description_rounded, const Color(0xFFFFF4D6), const Color(0xFFD97706), () => soon('Pengajuan SK')),
      _ServiceItem('Keuangan UPPM', Icons.account_balance_rounded, const Color(0xFFE0F2FE), const Color(0xFF0369A1), () => soon('Keuangan UPPM')),
      _ServiceItem('Tagihan Siswa', Icons.receipt_long_rounded, const Color(0xFFFEE2E2), const Color(0xFFB42318), () => soon('Tagihan siswa')),
      _ServiceItem('Laporan', Icons.summarize_rounded, const Color(0xFFF1F5F9), const Color(0xFF475569), () => soon('Laporan')),
    ];
    return Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
      Row(children: [const Expanded(child: _SectionTitle(title: 'Layanan')), GestureDetector(onTap: () => Navigator.of(context).push(MaterialPageRoute<void>(builder: (_) => _AllPengurusServicesPage(onOpenSchools: onOpenSchools))), child: const Text('See All', style: TextStyle(color: _pengurusPrimary, fontSize: 12, fontWeight: FontWeight.w800)))]),
      const SizedBox(height: 8),
      SizedBox(height: 92, child: ListView.separated(scrollDirection: Axis.horizontal, physics: const BouncingScrollPhysics(), itemCount: items.length, separatorBuilder: (_, __) => const SizedBox(width: 12), itemBuilder: (_, index) => _CompactServiceTile(item: items[index]))),
    ]);
  }
}

class _CompactServiceTile extends StatelessWidget { const _CompactServiceTile({required this.item}); final _ServiceItem item; @override Widget build(BuildContext context) => SizedBox(width: 72, child: InkWell(onTap: item.onTap, borderRadius: BorderRadius.circular(14), child: Column(children: [Container(width: 50, height: 50, decoration: BoxDecoration(color: item.surface, borderRadius: BorderRadius.circular(15)), child: Icon(item.icon, color: item.color, size: 23)), const SizedBox(height: 6), Text(item.label, textAlign: TextAlign.center, maxLines: 2, overflow: TextOverflow.ellipsis, style: const TextStyle(color: _pengurusText, fontSize: 10, fontWeight: FontWeight.w700, height: 1.1))]))); }

class _RecentUpdatesSection extends StatelessWidget {
  const _RecentUpdatesSection({required this.title, required this.icon, required this.items, required this.onSeeAll});
  final String title;
  final IconData icon;
  final List<Map<String, dynamic>> items;
  final VoidCallback onSeeAll;

  @override
  Widget build(BuildContext context) => Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
    Row(children: [Expanded(child: _SectionTitle(title: title)), GestureDetector(onTap: onSeeAll, child: const Text('See All', style: TextStyle(color: _pengurusPrimary, fontSize: 12, fontWeight: FontWeight.w800)))]),
    const SizedBox(height: 8),
    if (items.isEmpty)
      AppSectionCard(padding: const EdgeInsets.all(14), child: Row(children: [Icon(icon, color: const Color(0xFF94A3B8)), const SizedBox(width: 10), const Text('Belum ada pembaruan data.', style: TextStyle(color: Color(0xFF64748B), fontSize: 12))]))
    else
      ...items.take(3).map((item) => Padding(padding: const EdgeInsets.only(bottom: 8), child: _UpdateTile(item: item, icon: icon))),
  ]);
}

class _UpdateTile extends StatelessWidget {
  const _UpdateTile({required this.item, required this.icon});
  final Map<String, dynamic> item;
  final IconData icon;

  @override
  Widget build(BuildContext context) => Container(
    padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
    decoration: BoxDecoration(
      gradient: const LinearGradient(colors: [Color(0xFF00745A), Color(0xFF00553F)]),
      borderRadius: BorderRadius.circular(18),
      boxShadow: const [BoxShadow(color: Color(0x2400745A), blurRadius: 12, offset: Offset(0, 5))],
    ),
    child: Row(children: [
      Container(width: 36, height: 36, decoration: BoxDecoration(color: const Color(0x24FFFFFF), borderRadius: BorderRadius.circular(12)), child: Icon(icon, color: Colors.white, size: 19)),
      const SizedBox(width: 10),
      Expanded(child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [Text(item['title']?.toString() ?? 'Sekolah', maxLines: 1, overflow: TextOverflow.ellipsis, style: const TextStyle(color: Colors.white, fontSize: 12, fontWeight: FontWeight.w800)), const SizedBox(height: 2), Text('${item['subtitle'] ?? ''} • ${_dateLabel(item['date'])}', maxLines: 1, overflow: TextOverflow.ellipsis, style: const TextStyle(color: Color(0xD9FFFFFF), fontSize: 10))])),
      const SizedBox(width: 8),
      Text(_currency(item['amount']), style: const TextStyle(color: Colors.white, fontSize: 11, fontWeight: FontWeight.w800)),
    ]),
  );
}

class _UpdatesPage extends StatefulWidget {
  const _UpdatesPage({required this.repository, required this.isUppm});
  final PengurusMobileRepository repository;
  final bool isUppm;

  @override
  State<_UpdatesPage> createState() => _UpdatesPageState();
}

class _UpdatesPageState extends State<_UpdatesPage> {
  late Future<Map<String, dynamic>> _future;
  @override void initState() { super.initState(); _future = widget.isUppm ? widget.repository.uppmUpdates() : widget.repository.sppUpdates(); }
  Future<void> _refresh() async => setState(() => _future = widget.isUppm ? widget.repository.uppmUpdates() : widget.repository.sppUpdates());

  @override
  Widget build(BuildContext context) {
    final title = widget.isUppm ? 'Update Data UPPM' : 'Update SPP Siswa';
    final icon = widget.isUppm ? Icons.account_balance_rounded : Icons.receipt_long_rounded;
    return Scaffold(backgroundColor: const Color(0xFFF7F9FC), appBar: AppBar(elevation: 0, backgroundColor: Colors.white, foregroundColor: _pengurusText, title: Text(title, style: const TextStyle(fontSize: 18, fontWeight: FontWeight.w800))), body: FutureBuilder<Map<String, dynamic>>(future: _future, builder: (context, snapshot) {
      if (snapshot.connectionState == ConnectionState.waiting) return const Center(child: CircularProgressIndicator());
      if (snapshot.hasError) return _LoadError(onRetry: _refresh);
      final items = _updateItems(snapshot.data?['items']);
      return RefreshIndicator(onRefresh: _refresh, child: ListView(padding: const EdgeInsets.all(16), children: [if (items.isEmpty) AppSectionCard(child: const Text('Belum ada pembaruan data.')) else ...items.map((item) => Padding(padding: const EdgeInsets.only(bottom: 8), child: _UpdateTile(item: item, icon: icon)))]));
    }));
  }
}

class _AllPengurusServicesPage extends StatelessWidget {
  const _AllPengurusServicesPage({required this.onOpenSchools});
  final VoidCallback onOpenSchools;

  @override
  Widget build(BuildContext context) {
    final messenger = ScaffoldMessenger.of(context);
    void soon(String label) => messenger.showSnackBar(SnackBar(content: Text('$label akan segera tersedia.'), behavior: SnackBarBehavior.floating));
    final sections = <_PengurusServiceSection>[
      _PengurusServiceSection('Monitoring', [
        _ServiceItem('Data Sekolah', Icons.school_rounded, const Color(0xFFE5F5F0), _pengurusPrimary, onOpenSchools),
        _ServiceItem('Presensi', Icons.fact_check_rounded, const Color(0xFFE9F2FF), const Color(0xFF2563EB), () => soon('Monitoring presensi')),
        _ServiceItem('Jurnal Mengajar', Icons.menu_book_rounded, const Color(0xFFF3E8FF), const Color(0xFF7E22CE), () => soon('Jurnal mengajar')),
      ]),
      _PengurusServiceSection('Administrasi & Keuangan', [
        _ServiceItem('Pengajuan SK', Icons.description_rounded, const Color(0xFFFFF4D6), const Color(0xFFD97706), () => soon('Pengajuan SK')),
        _ServiceItem('Keuangan UPPM', Icons.account_balance_rounded, const Color(0xFFE0F2FE), const Color(0xFF0369A1), () => soon('Keuangan UPPM')),
        _ServiceItem('Tagihan Siswa', Icons.receipt_long_rounded, const Color(0xFFFEE2E2), const Color(0xFFB42318), () => soon('Tagihan siswa')),
        _ServiceItem('Laporan', Icons.summarize_rounded, const Color(0xFFF1F5F9), const Color(0xFF475569), () => soon('Laporan')),
      ]),
    ];
    return Scaffold(
      backgroundColor: const Color(0xFFF7F9FC),
      appBar: AppBar(elevation: 0, backgroundColor: Colors.white, foregroundColor: _pengurusText, title: const Text('Semua Layanan', style: TextStyle(fontSize: 18, fontWeight: FontWeight.w800, color: _pengurusText))),
      body: ListView.separated(
        padding: const EdgeInsets.fromLTRB(16, 16, 16, 24),
        itemCount: sections.length,
        separatorBuilder: (_, __) => const SizedBox(height: 16),
        itemBuilder: (_, index) {
          final section = sections[index];
          return AppSectionCard(padding: const EdgeInsets.fromLTRB(14, 14, 14, 16), child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
            Text(section.title, style: const TextStyle(color: _pengurusText, fontSize: 15, fontWeight: FontWeight.w800)),
            const SizedBox(height: 14),
            GridView.builder(shrinkWrap: true, physics: const NeverScrollableScrollPhysics(), itemCount: section.items.length, gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(crossAxisCount: 4, crossAxisSpacing: 10, mainAxisSpacing: 16, childAspectRatio: 0.85), itemBuilder: (_, itemIndex) => _AllServiceTile(section.items[itemIndex], onTap: () { Navigator.of(context).pop(); Future<void>.microtask(section.items[itemIndex].onTap); })),
          ]));
        },
      ),
    );
  }
}

class _PengurusServiceSection { const _PengurusServiceSection(this.title, this.items); final String title; final List<_ServiceItem> items; }
class _AllServiceTile extends StatelessWidget { const _AllServiceTile(this.item, {required this.onTap}); final _ServiceItem item; final VoidCallback onTap; @override Widget build(BuildContext context) => InkWell(onTap: onTap, borderRadius: BorderRadius.circular(99), child: Column(mainAxisSize: MainAxisSize.min, children: [Container(width: 58, height: 58, decoration: const BoxDecoration(color: Colors.white, shape: BoxShape.circle, boxShadow: [BoxShadow(color: Color(0x14172A24), blurRadius: 12, offset: Offset(0, 4))]), child: Icon(item.icon, size: 24, color: item.color)), const SizedBox(height: 8), Text(item.label, textAlign: TextAlign.center, maxLines: 2, overflow: TextOverflow.ellipsis, style: const TextStyle(color: _pengurusText, fontSize: 10, fontWeight: FontWeight.w700, height: 1.15))])); }
class _ServiceGrid extends StatelessWidget {
  const _ServiceGrid({required this.onOpenSchools, this.expanded = false});
  final VoidCallback onOpenSchools;
  final bool expanded;

  @override
  Widget build(BuildContext context) {
    void soon(String label) => ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('$label akan segera tersedia.'), behavior: SnackBarBehavior.floating));
    final items = <_ServiceItem>[
      _ServiceItem('Data Sekolah', Icons.school_rounded, const Color(0xFFE5F5F0), _pengurusPrimary, onOpenSchools),
      _ServiceItem('Presensi', Icons.fact_check_rounded, const Color(0xFFE9F2FF), const Color(0xFF2563EB), () => soon('Monitoring presensi')),
      _ServiceItem('Jurnal Mengajar', Icons.menu_book_rounded, const Color(0xFFF3E8FF), const Color(0xFF7E22CE), () => soon('Jurnal mengajar')),
      _ServiceItem('Pengajuan SK', Icons.description_rounded, const Color(0xFFFFF4D6), const Color(0xFFD97706), () => soon('Pengajuan SK')),
      _ServiceItem('Keuangan UPPM', Icons.account_balance_rounded, const Color(0xFFE0F2FE), const Color(0xFF0369A1), () => soon('Keuangan UPPM')),
      _ServiceItem('Tagihan Siswa', Icons.receipt_long_rounded, const Color(0xFFFEE2E2), const Color(0xFFB42318), () => soon('Tagihan siswa')),
      if (expanded) _ServiceItem('Laporan', Icons.summarize_rounded, const Color(0xFFF1F5F9), const Color(0xFF475569), () => soon('Laporan')),
    ];
    return GridView.builder(
      shrinkWrap: true,
      physics: const NeverScrollableScrollPhysics(),
      itemCount: items.length,
      gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(crossAxisCount: 3, crossAxisSpacing: 10, mainAxisSpacing: 12, childAspectRatio: 0.78),
      itemBuilder: (_, index) => _ServiceTile(item: items[index]),
    );
  }
}

class _ServiceItem { const _ServiceItem(this.label, this.icon, this.surface, this.color, this.onTap); final String label; final IconData icon; final Color surface, color; final VoidCallback onTap; }
class _ServiceTile extends StatelessWidget { const _ServiceTile({required this.item}); final _ServiceItem item; @override Widget build(BuildContext context) => Material(color: Colors.transparent, child: InkWell(onTap: item.onTap, borderRadius: BorderRadius.circular(18), child: Ink(padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 10), decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(18), border: Border.all(color: const Color(0xFFDCE7E3))), child: Column(mainAxisAlignment: MainAxisAlignment.center, children: [Container(width: 46, height: 46, decoration: BoxDecoration(color: item.surface, borderRadius: BorderRadius.circular(14)), child: Icon(item.icon, color: item.color, size: 23)), const SizedBox(height: 8), Text(item.label, textAlign: TextAlign.center, maxLines: 2, overflow: TextOverflow.ellipsis, style: const TextStyle(color: _pengurusText, fontSize: 11, fontWeight: FontWeight.w700, height: 1.15))])))); }
class _OperationalSummary extends StatelessWidget {
  const _OperationalSummary({required this.summary, required this.onSearch});
  final Map<String, dynamic> summary;
  final VoidCallback onSearch;

  @override
  Widget build(BuildContext context) => AppSectionCard(
    padding: const EdgeInsets.all(12),
    child: Column(mainAxisSize: MainAxisSize.min, crossAxisAlignment: CrossAxisAlignment.start, children: [
      TextField(
        textInputAction: TextInputAction.search,
        onSubmitted: (_) => onSearch(),
        decoration: InputDecoration(
          hintText: 'Cari sekolah...',
          hintStyle: const TextStyle(color: Color(0xFF94A3B8), fontSize: 14),
          prefixIcon: const Icon(Icons.search_rounded, color: _pengurusPrimary),
          filled: true,
          fillColor: const Color(0xFFF7FAF9),
          contentPadding: const EdgeInsets.symmetric(vertical: 14),
          border: OutlineInputBorder(borderRadius: BorderRadius.circular(16), borderSide: BorderSide.none),
          enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(16), borderSide: const BorderSide(color: Color(0xFFDCE7E3))),
          focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(16), borderSide: const BorderSide(color: _pengurusPrimary, width: 1.5)),
        ),
      ),
      const Padding(padding: EdgeInsets.symmetric(vertical: 10), child: Divider(height: 1, color: Color(0xFFDCE7E3))),
      _SchoolOverviewCard(total: '${summary['schools'] ?? 0}'),
      const SizedBox(height: 8),
      SizedBox(
        height: 74,
        child: Row(
          children: [
            Expanded(child: _SummaryMetric(label: 'Guru & Pegawai', value: '${summary['teachers'] ?? 0}', icon: Icons.badge_rounded, surface: const Color(0xFFFFF4D6), color: const Color(0xFFD97706))),
            const SizedBox(width: 8),
            Expanded(child: _SummaryMetric(label: 'Siswa aktif', value: '${summary['students'] ?? 0}', icon: Icons.groups_rounded, surface: const Color(0xFFE9F2FF), color: const Color(0xFF2563EB))),
          ],
        ),
      ),
    ]),
  );
}

class _SchoolOverviewCard extends StatelessWidget {
  const _SchoolOverviewCard({required this.total});
  final String total;

  @override
  Widget build(BuildContext context) => Container(
    padding: const EdgeInsets.all(12),
    decoration: BoxDecoration(gradient: const LinearGradient(colors: [Color(0xFF00553F), _pengurusPrimary]), borderRadius: BorderRadius.circular(16)),
    child: Row(children: [
      Container(width: 42, height: 42, decoration: BoxDecoration(color: Colors.white.withValues(alpha: 0.16), borderRadius: BorderRadius.circular(13)), child: const Icon(Icons.school_rounded, color: Colors.white, size: 21)),
      const SizedBox(width: 11),
      Expanded(child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [const Text('Total sekolah yang dinaungi', style: TextStyle(color: Color(0xD9FFFFFF), fontSize: 11, fontWeight: FontWeight.w600)), const SizedBox(height: 2), Text(total, style: const TextStyle(color: Colors.white, fontSize: 24, fontWeight: FontWeight.w800, height: 1))])),
      const Icon(Icons.arrow_forward_rounded, color: Color(0xCCFFFFFF)),
    ]),
  );
}

class _SummaryMetric extends StatelessWidget {
  const _SummaryMetric({required this.label, required this.value, required this.icon, required this.surface, this.color = _pengurusPrimary});
  final String label, value;
  final IconData icon;
  final Color surface, color;

  @override
  Widget build(BuildContext context) => Container(
    padding: const EdgeInsets.all(9),
    decoration: BoxDecoration(color: const Color(0xFFF9FBFA), borderRadius: BorderRadius.circular(14), border: Border.all(color: const Color(0xFFEEF2F0))),
    child: Row(mainAxisAlignment: MainAxisAlignment.center, children: [
      Container(width: 38, height: 38, decoration: BoxDecoration(color: surface, borderRadius: BorderRadius.circular(12)), child: Icon(icon, color: color, size: 22)),
      const SizedBox(width: 8),
      Column(crossAxisAlignment: CrossAxisAlignment.start, mainAxisAlignment: MainAxisAlignment.center, children: [Text(value, style: const TextStyle(color: _pengurusText, fontSize: 18, fontWeight: FontWeight.w800, height: 1)), const SizedBox(height: 3), Text(label, style: const TextStyle(color: Color(0xFF64748B), fontSize: 9, fontWeight: FontWeight.w600))]),
    ]),
  );
}
class _LoadError extends StatelessWidget { const _LoadError({required this.onRetry, this.title = 'Dashboard belum dapat dimuat', this.message = 'Terjadi gangguan saat memuat data. Periksa koneksi lalu coba lagi.'}); final Future<void> Function() onRetry; final String title; final String message; @override Widget build(BuildContext context) => Center(child: Padding(padding: const EdgeInsets.all(24), child: Column(mainAxisSize: MainAxisSize.min, children: [const Icon(Icons.cloud_off_rounded, size: 42, color: Color(0xFFB42318)), const SizedBox(height: 12), Text(title, style: const TextStyle(fontWeight: FontWeight.w800)), const SizedBox(height: 6), Text(message, textAlign: TextAlign.center, style: const TextStyle(fontSize: 12)), const SizedBox(height: 14), FilledButton.icon(onPressed: () => onRetry(), icon: const Icon(Icons.refresh_rounded), label: const Text('Coba lagi'))]))); }

List<Map<String, dynamic>> _updateItems(dynamic value) => (value as List? ?? const []).whereType<Map>().map((item) => Map<String, dynamic>.from(item)).toList();
String _currency(dynamic value) { final amount = value is num ? value.round() : num.tryParse(value?.toString() ?? '')?.round() ?? 0; final digits = amount.toString(); final buffer = StringBuffer(); for (var index = 0; index < digits.length; index++) { buffer.write(digits[index]); if (digits.length - index > 1 && (digits.length - index - 1) % 3 == 0) buffer.write('.'); } return 'Rp $buffer'; }
String _dateLabel(dynamic value) { final date = DateTime.tryParse(value?.toString() ?? ''); if (date == null) return '-'; const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']; return '${date.day} ${months[date.month - 1]}'; }
String? _schoolDistrict(Map<String, dynamic> item) {
  final stored = item['kabupaten']?.toString().trim();
  if (stored != null && stored.isNotEmpty) return _districtTitle(stored);
  final scod = item['scod']?.toString().trim() ?? '';
  return switch (scod.isEmpty ? '' : scod.substring(0, 1)) {
    '1' => 'Kabupaten Bantul',
    '2' => 'Kabupaten Gunungkidul',
    '3' => 'Kabupaten Kulon Progo',
    '4' => 'Kabupaten Sleman',
    '5' => 'Kota Yogyakarta',
    _ => null,
  };
}
String _districtTitle(String value) => value.trimLeft().toLowerCase().startsWith('kabupaten') || value.trimLeft().toLowerCase().startsWith('kota') ? value : 'Kabupaten $value';
int _districtRank(String district) => const {'Kabupaten Bantul': 1, 'Kabupaten Gunungkidul': 2, 'Kabupaten Kulon Progo': 3, 'Kabupaten Sleman': 4, 'Kota Yogyakarta': 5}[district] ?? 99;
int _scodValue(dynamic value) => int.tryParse(value?.toString() ?? '') ?? 999999;
String? _schoolLogoUrl(Map<String, dynamic> item) {
  final value = item['logo_url']?.toString().trim().isNotEmpty == true ? item['logo_url'].toString().trim() : item['logo']?.toString().trim();
  if (value == null || value.isEmpty) return null;
  if (value.startsWith('http://') || value.startsWith('https://')) return value;
  final path = value.startsWith('storage/') ? value : 'storage/$value';
  return '${AppConfig.webBaseUrl}/$path';
}
