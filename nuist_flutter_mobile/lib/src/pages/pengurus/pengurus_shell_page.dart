import 'package:flutter/material.dart';

import '../../controllers/session_controller.dart';
import '../../services/pengurus_mobile_repository.dart';

class PengurusShellPage extends StatefulWidget {
  const PengurusShellPage({super.key, required this.controller, required this.repository});
  final SessionController controller;
  final PengurusMobileRepository repository;
  @override State<PengurusShellPage> createState() => _PengurusShellPageState();
}

class _PengurusShellPageState extends State<PengurusShellPage> {
  int _tab = 0;
  @override Widget build(BuildContext context) => Scaffold(
    backgroundColor: const Color(0xFFF7F9FC),
    body: SafeArea(child: _tab == 0 ? _PengurusDashboard(repository: widget.repository) : _tab == 1 ? _SchoolMonitor(repository: widget.repository) : _PengurusProfile(controller: widget.controller)),
    bottomNavigationBar: NavigationBar(selectedIndex: _tab, onDestinationSelected: (value) => setState(() => _tab = value), destinations: const [NavigationDestination(icon: Icon(Icons.space_dashboard_outlined), selectedIcon: Icon(Icons.space_dashboard_rounded), label: 'Beranda'), NavigationDestination(icon: Icon(Icons.school_outlined), selectedIcon: Icon(Icons.school_rounded), label: 'Sekolah'), NavigationDestination(icon: Icon(Icons.person_outline_rounded), selectedIcon: Icon(Icons.person_rounded), label: 'Profil')]),
  );
}

class _PengurusDashboard extends StatefulWidget { const _PengurusDashboard({required this.repository}); final PengurusMobileRepository repository; @override State<_PengurusDashboard> createState() => _PengurusDashboardState(); }
class _PengurusDashboardState extends State<_PengurusDashboard> {
  late Future<Map<String, dynamic>> _future;
  @override void initState() { super.initState(); _future = widget.repository.dashboard(); }
  @override Widget build(BuildContext context) => FutureBuilder<Map<String, dynamic>>(future: _future, builder: (context, snapshot) {
    if (!snapshot.hasData) return const Center(child: CircularProgressIndicator());
    final data = snapshot.data!; final summary = Map<String, dynamic>.from(data['summary'] as Map? ?? {}); final finance = Map<String, dynamic>.from(data['finance'] as Map? ?? {});
    return RefreshIndicator(onRefresh: () async => setState(() => _future = widget.repository.dashboard()), child: ListView(padding: const EdgeInsets.all(18), children: [Text(data['greeting'] as String? ?? 'Monitoring Pengurus', style: const TextStyle(fontSize: 22, fontWeight: FontWeight.w800, color: Color(0xFF172A24))), const SizedBox(height: 5), const Text('Ringkasan operasional tingkat provinsi', style: TextStyle(color: Color(0xFF5F6F68))), const SizedBox(height: 18), Wrap(spacing: 10, runSpacing: 10, children: [_Metric('Sekolah', '${summary['schools'] ?? 0}', Icons.school_rounded), _Metric('Siswa Aktif', '${summary['students'] ?? 0}', Icons.groups_rounded), _Metric('Tenaga Pendidik', '${summary['teachers'] ?? 0}', Icons.badge_rounded), _Metric('Presensi Hari Ini', '${summary['attendance_today'] ?? 0}', Icons.how_to_reg_rounded)]), const SizedBox(height: 18), _Panel(title: 'Monitoring Keuangan', children: [Text('Tagihan terbuka: ${finance['open_bills'] ?? 0}'), const SizedBox(height: 6), Text('Nilai tertunggak: Rp ${finance['outstanding_amount'] ?? 0}', style: const TextStyle(fontWeight: FontWeight.w800))]), const SizedBox(height: 12), const _Panel(title: 'Akses pengurus', children: [Text('Dashboard ini bersifat monitoring (read-only). Seluruh akses data penting dicatat untuk audit.')]), ]));
  });
}

class _SchoolMonitor extends StatefulWidget { const _SchoolMonitor({required this.repository}); final PengurusMobileRepository repository; @override State<_SchoolMonitor> createState() => _SchoolMonitorState(); }
class _SchoolMonitorState extends State<_SchoolMonitor> {
  late Future<Map<String, dynamic>> _future;
  @override void initState() { super.initState(); _future = widget.repository.schools(); }
  @override Widget build(BuildContext context) => FutureBuilder<Map<String, dynamic>>(
    future: _future,
    builder: (context, snapshot) {
      if (!snapshot.hasData) return const Center(child: CircularProgressIndicator());
      final items = (snapshot.data!['items'] as List? ?? const []);
      return ListView(padding: const EdgeInsets.all(18), children: [
        const Text('Monitoring Sekolah', style: TextStyle(fontSize: 22, fontWeight: FontWeight.w800)),
        const SizedBox(height: 14),
        ...items.whereType<Map>().map((item) {
          final row = Map<String, dynamic>.from(item);
          return Card(child: ListTile(
            leading: const CircleAvatar(child: Icon(Icons.school_rounded)),
            title: Text(row['name']?.toString() ?? '-'),
            subtitle: Text('Siswa aktif: ${row['students'] ?? 0} • Pendidik: ${row['teachers'] ?? 0}'),
            trailing: Text(row['scod']?.toString() ?? '-'),
          ));
        }),
      ]);
    },
  );
}

class _PengurusProfile extends StatelessWidget { const _PengurusProfile({required this.controller}); final SessionController controller; @override Widget build(BuildContext context) { final user=controller.session?.user; return ListView(padding:const EdgeInsets.all(18),children:[const Text('Profil Pengurus',style:TextStyle(fontSize:22,fontWeight:FontWeight.w800)),const SizedBox(height:16),Card(child:ListTile(leading:const CircleAvatar(child:Icon(Icons.manage_accounts_rounded)),title:Text(user?.name??'Pengurus'),subtitle:Text(user?.email??'-'))),const SizedBox(height:12),const _Panel(title:'Akses akun',children:[Text('Akses ini khusus monitoring dan bersifat read-only.')]),const SizedBox(height:18),OutlinedButton.icon(onPressed:controller.logout,icon:const Icon(Icons.logout_rounded),label:const Text('Keluar dari akun'))]); } }

class _Metric extends StatelessWidget { const _Metric(this.label,this.value,this.icon); final String label,value; final IconData icon; @override Widget build(BuildContext context)=>SizedBox(width:(MediaQuery.sizeOf(context).width-46)/2,child:Card(child:Padding(padding:const EdgeInsets.all(14),child:Column(crossAxisAlignment:CrossAxisAlignment.start,children:[Icon(icon,color:const Color(0xFF00745A)),const SizedBox(height:10),Text(value,style:const TextStyle(fontSize:22,fontWeight:FontWeight.w800)),Text(label,style:const TextStyle(fontSize:12,color:Color(0xFF5F6F68)))])))); }
class _Panel extends StatelessWidget { const _Panel({required this.title,required this.children}); final String title; final List<Widget> children; @override Widget build(BuildContext context)=>Card(child:Padding(padding:const EdgeInsets.all(16),child:Column(crossAxisAlignment:CrossAxisAlignment.start,children:[Text(title,style:const TextStyle(fontSize:16,fontWeight:FontWeight.w800)),const SizedBox(height:10),...children]))); }
