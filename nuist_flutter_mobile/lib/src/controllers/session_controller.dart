import 'package:flutter/foundation.dart';

import '../models/session.dart';
import '../services/auth_repository.dart';

class SessionController extends ChangeNotifier {
  SessionController(this._authRepository);

  final AuthRepository _authRepository;

  bool _isBootstrapping = true;
  bool _isLoggingIn = false;
  bool _isDashboardLoading = false;
  String? _errorMessage;
  Session? _session;
  Map<String, dynamic>? _dashboardData;

  bool get isBootstrapping => _isBootstrapping;
  bool get isLoggingIn => _isLoggingIn;
  bool get isDashboardLoading => _isDashboardLoading;
  bool get isBusy => _isLoggingIn || _isDashboardLoading;
  String? get errorMessage => _errorMessage;
  Session? get session => _session;
  Map<String, dynamic>? get dashboardData => _dashboardData;

  Future<void> bootstrap() async {
    try {
      _session = await _authRepository.restoreSession();
    } catch (_) {
      _errorMessage = 'Sesi lama tidak bisa dipulihkan. Silakan login ulang.';
    } finally {
      _isBootstrapping = false;
      notifyListeners();
    }
  }

  Future<void> login({
    required String email,
    required String password,
    required bool rememberSession,
  }) async {
    _isLoggingIn = true;
    _errorMessage = null;
    notifyListeners();

    try {
      _session = await _authRepository.login(
        email: email,
        password: password,
        rememberSession: rememberSession,
      );
      _dashboardData = null;
    } catch (error) {
      _errorMessage = error.toString();
    } finally {
      _isLoggingIn = false;
      notifyListeners();
    }
  }

  Future<void> refreshMe() async {
    _errorMessage = null;
    notifyListeners();

    try {
      _session = await _authRepository.refreshSession();
      notifyListeners();
    } catch (error) {
      _errorMessage = error.toString();
      notifyListeners();
    }
  }

  Future<void> loadDashboard() async {
    _isDashboardLoading = true;
    _errorMessage = null;
    notifyListeners();

    try {
      _dashboardData = await _authRepository.getDashboard();
    } catch (error) {
      _errorMessage = error.toString();
    } finally {
      _isDashboardLoading = false;
      notifyListeners();
    }
  }

  Future<void> logout() async {
    _errorMessage = null;
    notifyListeners();

    try {
      await _authRepository.logout();
    } catch (error) {
      _errorMessage = error.toString();
    } finally {
      _session = null;
      _dashboardData = null;
      notifyListeners();
    }
  }
}
