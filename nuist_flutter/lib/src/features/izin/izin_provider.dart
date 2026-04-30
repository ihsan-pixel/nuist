import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../models/izin_item.dart';
import '../../repositories/mobile_repository.dart';

final izinListProvider = FutureProvider<List<IzinItem>>((ref) {
  return ref.watch(mobileRepositoryProvider).fetchIzinList();
});

final izinDetailProvider = FutureProvider.family<IzinItem, String>((ref, izinId) {
  return ref.watch(mobileRepositoryProvider).fetchIzinDetail(izinId);
});
