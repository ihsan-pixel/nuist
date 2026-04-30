import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../models/billing_summary.dart';
import '../../repositories/mobile_repository.dart';

final billingProvider = FutureProvider<BillingSummary>((ref) {
  return ref.watch(mobileRepositoryProvider).fetchBills();
});
