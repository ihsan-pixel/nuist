import 'app_user.dart';

class Session {
  const Session({
    required this.token,
    required this.user,
    this.mobileRoute,
  });

  final String token;
  final AppUser user;
  final String? mobileRoute;

  factory Session.fromLoginJson(Map<String, dynamic> json) {
    return Session(
      token: json['token'] as String,
      user: AppUser.fromJson(json['user'] as Map<String, dynamic>),
      mobileRoute: json['mobile_route'] as String?,
    );
  }

  Session copyWith({
    String? token,
    AppUser? user,
    String? mobileRoute,
  }) {
    return Session(
      token: token ?? this.token,
      user: user ?? this.user,
      mobileRoute: mobileRoute ?? this.mobileRoute,
    );
  }
}
