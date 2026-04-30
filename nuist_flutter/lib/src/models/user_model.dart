class UserModel {
  UserModel({
    required this.id,
    required this.name,
    required this.email,
    required this.role,
    this.nuistId,
    this.photoUrl,
  });

  final int id;
  final String name;
  final String email;
  final String role;
  final String? nuistId;
  final String? photoUrl;

  factory UserModel.fromJson(Map<String, dynamic> json) {
    return UserModel(
      id: (json['id'] as num).toInt(),
      name: json['name']?.toString() ?? '-',
      email: json['email']?.toString() ?? '-',
      role: json['role']?.toString() ?? '-',
      nuistId: json['nuist_id']?.toString(),
      photoUrl: json['photo_url']?.toString(),
    );
  }
}
