class DashboardData {
  DashboardData({
    required this.attendancePercent,
    required this.pendingIzinCount,
    required this.unpaidBillCount,
    this.greeting,
  });

  final double attendancePercent;
  final int pendingIzinCount;
  final int unpaidBillCount;
  final String? greeting;

  factory DashboardData.fromJson(Map<String, dynamic> json) {
    final summary = (json['summary'] as Map<String, dynamic>?) ?? <String, dynamic>{};

    return DashboardData(
      greeting: json['greeting']?.toString(),
      attendancePercent: (summary['attendance_percent'] as num?)?.toDouble() ?? 0,
      pendingIzinCount: (summary['pending_izin_count'] as num?)?.toInt() ?? 0,
      unpaidBillCount: (summary['unpaid_bill_count'] as num?)?.toInt() ?? 0,
    );
  }
}
