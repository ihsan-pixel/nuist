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
    return AppUser(
      id: json['id'] as int,
      name: json['name'] as String? ?? '-',
      email: json['email'] as String? ?? '-',
      role: json['role'] as String?,
    );
  }
}
