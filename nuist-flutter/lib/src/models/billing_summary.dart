import 'billing_item.dart';

class BillingSummary {
  BillingSummary({
    required this.items,
    required this.totalUnpaid,
  });

  final List<BillingItem> items;
  final int totalUnpaid;

  factory BillingSummary.fromJson(Map<String, dynamic> json) {
    final rawItems = (json['items'] as List<dynamic>? ?? <dynamic>[])
        .cast<Map<String, dynamic>>();

    return BillingSummary(
      items: rawItems.map(BillingItem.fromJson).toList(),
      totalUnpaid: (json['total_unpaid'] as num?)?.toInt() ?? 0,
    );
  }
}
