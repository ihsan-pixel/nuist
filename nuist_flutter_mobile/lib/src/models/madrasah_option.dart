class MadrasahOption {
  const MadrasahOption({
    required this.id,
    required this.name,
  });

  final int id;
  final String name;

  factory MadrasahOption.fromJson(Map<String, dynamic> json) {
    return MadrasahOption(
      id: json['id'] as int,
      name: json['name'] as String? ?? '-',
    );
  }
}
