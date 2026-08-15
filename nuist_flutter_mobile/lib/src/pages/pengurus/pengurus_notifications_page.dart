part of 'pengurus_shell_page.dart';

class _NotificationsPage extends StatelessWidget {
  const _NotificationsPage();

  @override
  Widget build(BuildContext context) => ListView(padding: const EdgeInsets.all(16), children: const [
    _PageHeading(title: 'Notifikasi', subtitle: 'Informasi yang memerlukan perhatian'),
    SizedBox(height: 18),
    AppSectionCard(child: Column(children: [Icon(Icons.notifications_none_rounded, size: 42, color: Color(0xFF94A3B8)), SizedBox(height: 12), Text('Belum ada notifikasi', style: TextStyle(fontWeight: FontWeight.w800)), SizedBox(height: 4), Text('Notifikasi prioritas akan tampil di halaman ini.', textAlign: TextAlign.center, style: TextStyle(color: Color(0xFF64748B), fontSize: 12))])),
  ]);
}

