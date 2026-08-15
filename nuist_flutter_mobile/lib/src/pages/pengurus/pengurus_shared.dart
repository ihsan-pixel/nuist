part of 'pengurus_shell_page.dart';

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
