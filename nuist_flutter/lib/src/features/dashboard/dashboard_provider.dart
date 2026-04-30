import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../models/dashboard_data.dart';
import '../../repositories/mobile_repository.dart';

final dashboardProvider = FutureProvider<DashboardData>((ref) {
  return ref.watch(mobileRepositoryProvider).fetchDashboard();
});
