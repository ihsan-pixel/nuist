part of 'pengurus_shell_page.dart';

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
