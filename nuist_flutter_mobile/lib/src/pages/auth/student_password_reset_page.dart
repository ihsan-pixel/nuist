import 'package:flutter/material.dart';
import '../../services/auth_repository.dart';
import '../../widgets/auth/status_banner.dart';
import 'turnstile_verification_page.dart';

class StudentPasswordResetPage extends StatefulWidget {
  const StudentPasswordResetPage({super.key, required this.authRepository});
  final AuthRepository authRepository;
  @override
  State<StudentPasswordResetPage> createState() => _StudentPasswordResetPageState();
}

class _StudentPasswordResetPageState extends State<StudentPasswordResetPage> {
  final _formKey = GlobalKey<FormState>();
  final _nisn = TextEditingController();
  final _mother = TextEditingController();
  DateTime? _birthDate;
  bool _submitting = false;
  String? _error;
  @override
  void dispose() { _nisn.dispose(); _mother.dispose(); super.dispose(); }

  Future<void> _submit() async {
    if (!(_formKey.currentState?.validate() ?? false) || _birthDate == null) {
      setState(() => _error = _birthDate == null ? 'Tanggal lahir wajib diisi.' : null); return;
    }
    final token = await Navigator.of(context).push<String>(MaterialPageRoute(builder: (_) => const TurnstileVerificationPage()));
    if (token == null || token.isEmpty || !mounted) return;
    setState(() { _submitting = true; _error = null; });
    try {
      final date = _birthDate!;
      final message = await widget.authRepository.resetStudentPassword(nisn: _nisn.text.trim(), birthDate: '${date.year.toString().padLeft(4, '0')}-${date.month.toString().padLeft(2, '0')}-${date.day.toString().padLeft(2, '0')}', motherName: _mother.text.trim(), turnstileToken: token);
      if (mounted) Navigator.of(context).pop(message);
    } catch (error) { if (mounted) setState(() => _error = error.toString()); }
    finally { if (mounted) setState(() => _submitting = false); }
  }

  @override
  Widget build(BuildContext context) => Scaffold(appBar: AppBar(title: const Text('Reset Password Siswa')), body: ListView(padding: const EdgeInsets.all(20), children: [
    const Text('Verifikasi data diri', style: TextStyle(fontSize: 20, fontWeight: FontWeight.w800)), const SizedBox(height: 8),
    const Text('Lengkapi data sesuai data sekolah. Jika nama ibu belum tercatat, hubungi admin sekolah. Password akan direset ke format Nuistddmmyyyy berdasarkan tanggal lahir.'), const SizedBox(height: 18),
    if (_error != null) ...[StatusBanner(message: _error!, type: StatusBannerType.error), const SizedBox(height: 12)],
    Form(key: _formKey, child: Column(children: [
      TextFormField(controller: _nisn, keyboardType: TextInputType.number, decoration: const InputDecoration(labelText: 'NISN'), validator: (v) => RegExp(r'^\d{6,50}$').hasMatch(v?.trim() ?? '') ? null : 'Masukkan NISN yang valid.'),
      const SizedBox(height: 12), ListTile(contentPadding: EdgeInsets.zero, title: Text(_birthDate == null ? 'Pilih tanggal lahir' : '${_birthDate!.day.toString().padLeft(2, '0')}-${_birthDate!.month.toString().padLeft(2, '0')}-${_birthDate!.year}'), leading: const Icon(Icons.calendar_today_outlined), onTap: () async { final value = await showDatePicker(context: context, firstDate: DateTime(1990), lastDate: DateTime.now(), initialDate: _birthDate ?? DateTime(2010)); if (value != null) setState(() { _birthDate = value; _error = null; }); }),
      TextFormField(controller: _mother, decoration: const InputDecoration(labelText: 'Nama ibu kandung'), validator: (v) => (v?.trim().isNotEmpty ?? false) ? null : 'Nama ibu kandung wajib diisi.'),
      const SizedBox(height: 22), FilledButton(onPressed: _submitting ? null : _submit, child: Text(_submitting ? 'Memproses...' : 'Verifikasi & Reset Password')),
    ])),
  ]));
}
