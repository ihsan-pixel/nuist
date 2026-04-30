class BillingItem {
  BillingItem({
    required this.id,
    required this.invoiceNumber,
    required this.period,
    required this.totalAmount,
    required this.status,
    this.billType,
    this.dueDate,
  });

  final int id;
  final String invoiceNumber;
  final String period;
  final int totalAmount;
  final String status;
  final String? billType;
  final String? dueDate;

  factory BillingItem.fromJson(Map<String, dynamic> json) {
    return BillingItem(
      id: (json['id'] as num).toInt(),
      invoiceNumber: json['nomor_tagihan']?.toString() ?? '-',
      period: json['periode']?.toString() ?? '-',
      totalAmount: (json['total_tagihan'] as num?)?.toInt() ?? 0,
      status: json['status']?.toString() ?? '-',
      billType: json['jenis_tagihan']?.toString(),
      dueDate: json['jatuh_tempo']?.toString(),
    );
  }
}
