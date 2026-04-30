class IzinItem {
  IzinItem({
    required this.id,
    required this.type,
    required this.title,
    required this.status,
    this.reason,
    this.submittedAt,
  });

  final int id;
  final String type;
  final String title;
  final String status;
  final String? reason;
  final String? submittedAt;

  factory IzinItem.fromJson(Map<String, dynamic> json) {
    return IzinItem(
      id: (json['id'] as num).toInt(),
      type: json['type']?.toString() ?? '-',
      title: json['title']?.toString() ?? '-',
      status: json['status']?.toString() ?? '-',
      reason: json['reason']?.toString(),
      submittedAt: json['submitted_at']?.toString(),
    );
  }
}
