class AppUser {
  const AppUser({
    required this.id,
    required this.name,
    required this.email,
    this.role,
  });

  final int id;
  final String name;
  final String email;
  final String? role;

  factory AppUser.fromJson(Map<String, dynamic> json) {
    final rawRole = json['role'];
    final normalizedRole =
        rawRole is String ? rawRole.trim().toLowerCase() : null;

    return AppUser(
      id: json['id'] as int,
      name: json['name'] as String? ?? '-',
      email: json['email'] as String? ?? '-',
      role: normalizedRole,
    );
  }
}
