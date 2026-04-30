import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../core/network/api_exception.dart';
import '../../core/storage/token_storage.dart';
import '../../models/user_model.dart';
import '../../repositories/auth_repository.dart';

enum SessionStatus {
  checking,
  authenticated,
  unauthenticated,
}

class AuthState {
  const AuthState({
    required this.status,
    this.user,
    this.errorMessage,
    this.isSubmitting = false,
  });

  const AuthState.initial() : this(status: SessionStatus.checking);

  final SessionStatus status;
  final UserModel? user;
  final String? errorMessage;
  final bool isSubmitting;

  AuthState copyWith({
    SessionStatus? status,
    UserModel? user,
    String? errorMessage,
    bool? isSubmitting,
    bool clearErrorMessage = false,
    bool clearUser = false,
  }) {
    return AuthState(
      status: status ?? this.status,
      user: clearUser ? null : (user ?? this.user),
      errorMessage: clearErrorMessage ? null : (errorMessage ?? this.errorMessage),
      isSubmitting: isSubmitting ?? this.isSubmitting,
    );
  }
}

final authControllerProvider =
    StateNotifierProvider<AuthController, AuthState>((ref) {
  return AuthController(
    authRepository: ref.watch(authRepositoryProvider),
    tokenStorage: ref.watch(tokenStorageProvider),
  );
});

class AuthController extends StateNotifier<AuthState> {
  AuthController({
    required AuthRepository authRepository,
    required TokenStorage tokenStorage,
  })  : _authRepository = authRepository,
        _tokenStorage = tokenStorage,
        super(const AuthState.initial());

  final AuthRepository _authRepository;
  final TokenStorage _tokenStorage;

  Future<void> restoreSession() async {
    state = state.copyWith(
      status: SessionStatus.checking,
      clearErrorMessage: true,
    );

    try {
      final token = await _tokenStorage.readToken();
      if (token == null || token.isEmpty) {
        state = state.copyWith(
          status: SessionStatus.unauthenticated,
          clearUser: true,
        );
        return;
      }

      final user = await _authRepository.me();
      state = state.copyWith(
        status: SessionStatus.authenticated,
        user: user,
        clearErrorMessage: true,
      );
    } on ApiException catch (error) {
      await _tokenStorage.clear();
      state = state.copyWith(
        status: SessionStatus.unauthenticated,
        errorMessage: error.message,
        clearUser: true,
      );
    } catch (_) {
      await _tokenStorage.clear();
      state = state.copyWith(
        status: SessionStatus.unauthenticated,
        clearUser: true,
      );
    }
  }

  Future<void> login({
    required String email,
    required String password,
  }) async {
    state = state.copyWith(
      isSubmitting: true,
      clearErrorMessage: true,
    );

    try {
      final result = await _authRepository.login(
        email: email,
        password: password,
      );
      await _tokenStorage.writeToken(result.token);
      state = state.copyWith(
        status: SessionStatus.authenticated,
        user: result.user,
        isSubmitting: false,
        clearErrorMessage: true,
      );
    } on ApiException catch (error) {
      state = state.copyWith(
        status: SessionStatus.unauthenticated,
        isSubmitting: false,
        errorMessage: error.message,
        clearUser: true,
      );
    }
  }

  Future<void> logout() async {
    state = state.copyWith(isSubmitting: true, clearErrorMessage: true);

    try {
      await _authRepository.logout();
    } on ApiException {
      // Local session is still cleared even if server logout fails.
    }

    await _tokenStorage.clear();
    state = state.copyWith(
      status: SessionStatus.unauthenticated,
      isSubmitting: false,
      clearUser: true,
      clearErrorMessage: true,
    );
  }
}
