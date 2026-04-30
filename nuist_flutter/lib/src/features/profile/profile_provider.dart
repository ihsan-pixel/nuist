import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../models/user_model.dart';
import '../../repositories/mobile_repository.dart';

final profileProvider = FutureProvider<UserModel>((ref) {
  return ref.watch(mobileRepositoryProvider).fetchProfile();
});
