part of 'pengurus_shell_page.dart';

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
