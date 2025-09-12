import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:riverpod_annotation/riverpod_annotation.dart';
import 'package:freezed_annotation/freezed_annotation.dart';
import '../models/user.dart';
import '../models/auth_event.dart';
import '../models/auth_requests.dart';
import '../token_storage.dart';
import '../api/auth_api.dart';
import '../../widgets/login_bottom_sheet.dart';
import 'auth_event_controller.dart';

part 'auth_controller.freezed.dart';
part 'auth_controller.g.dart';

@freezed
class AuthState with _$AuthState {
  const factory AuthState.guest() = _Guest;
  const factory AuthState.authenticated(User user) = _Authenticated;
  const factory AuthState.loading() = _Loading;
}

/// Extension to easily check authentication status
extension AuthStateExtension on AuthState {
  bool get isAuthenticated => this is _Authenticated;
  bool get isGuest => this is _Guest;
  bool get isLoading => this is _Loading;
  
  User? get user => when(
    guest: () => null,
    authenticated: (user) => user,
    loading: () => null,
  );
}

@riverpod
class AuthController extends _$AuthController {
  late final TokenStorage _tokenStorage;
  late final AuthApi _authApi;

  @override
  Future<AuthState> build() async {
    _tokenStorage = ref.read(tokenStorageProvider);
    _authApi = ref.read(authApiProvider);
    
    return await _initializeAuthState();
  }

  /// Initialize authentication state on cold start
  Future<AuthState> _initializeAuthState() async {
    try {
      // Check if we have stored tokens
      final hasTokens = await _tokenStorage.hasValidTokens();
      
      if (!hasTokens) {
        // No tokens found, set as guest
        return const AuthState.guest();
      }

      // Try to get user profile with existing tokens
      final user = await _authApi.getProfile();
      return AuthState.authenticated(user);
    } catch (e) {
      // Token might be expired or invalid, clear storage and set as guest
      await _tokenStorage.clearTokens();
      return const AuthState.guest();
    }
  }

  /// Login with email and password
  Future<void> login(String email, String password) async {
    state = const AsyncData(AuthState.loading());
    
    try {
      final request = LoginRequest(email: email, password: password);
      final response = await _authApi.login(request);
      
      // Save tokens
      await _tokenStorage.saveTokens(
        response.tokens.accessToken,
        response.tokens.refreshToken,
      );
      await _tokenStorage.saveUserId(response.user.id);
      
      // Update state to authenticated
      state = AsyncData(AuthState.authenticated(response.user));
    } catch (e) {
      // Keep current state and rethrow for UI handling
      rethrow;
    }
  }

  /// Register new user
  Future<void> register(RegisterRequest request) async {
    state = const AsyncData(AuthState.loading());
    
    try {
      final response = await _authApi.register(request);
      
      // Save tokens
      await _tokenStorage.saveTokens(
        response.tokens.accessToken,
        response.tokens.refreshToken,
      );
      await _tokenStorage.saveUserId(response.user.id);
      
      // Update state to authenticated
      state = AsyncData(AuthState.authenticated(response.user));
    } catch (e) {
      // Revert to guest state on error
      state = const AsyncData(AuthState.guest());
      rethrow;
    }
  }

  /// Logout user
  Future<void> logout() async {
    try {
      // Call logout API if available
      await _authApi.logout();
    } catch (e) {
      // Continue with logout even if API call fails
      debugPrint('Logout API failed: $e');
    } finally {
      // Clear tokens and set as guest
      await _tokenStorage.clearTokens();
      state = const AsyncData(AuthState.guest());
    }
  }

  /// Refresh user profile
  Future<void> refreshProfile() async {
    final currentState = state.value;
    if (currentState is! _Authenticated) return;

    try {
      final user = await _authApi.getProfile();
      state = AsyncData(AuthState.authenticated(user));
    } catch (e) {
      // If refresh fails, logout user
      await logout();
    }
  }

  /// Handle token refresh
  Future<void> refreshTokens() async {
    try {
      final refreshToken = await _tokenStorage.getRefreshToken();
      if (refreshToken == null) {
        await logout();
        return;
      }

      final response = await _authApi.refreshToken(refreshToken);
      
      // Save new tokens
      await _tokenStorage.saveTokens(
        response.tokens.accessToken,
        response.tokens.refreshToken,
      );
    } catch (e) {
      // If refresh fails, logout user
      await logout();
    }
  }

  /// Check if user is authenticated
  bool get isAuthenticated {
    final currentState = state.value;
    return currentState is _Authenticated;
  }

  /// Check if user is guest
  bool get isGuest {
    final currentState = state.value;
    return currentState is _Guest;
  }

  /// Get current user if authenticated
  User? get currentUser {
    final currentState = state.value;
    if (currentState is _Authenticated) {
      return currentState.user;
    }
    return null;
  }

  /// Get current authentication tokens
  Future<AuthTokens?> getTokens() async {
    final tokens = await _tokenStorage.getTokens();
    final accessToken = tokens['access_token'];
    final refreshToken = tokens['refresh_token'];
    
    if (accessToken != null && refreshToken != null) {
      return AuthTokens(
        accessToken: accessToken,
        refreshToken: refreshToken,
        tokenType: 'Bearer',
        expiresIn: 3600, // Default expiry time
      );
    }
    return null;
  }

  /// Check if tokens are valid and not expired
  Future<bool> hasValidTokens() async {
    return await _tokenStorage.hasValidTokens();
  }
}

/// Helper function to require authentication before executing an action
/// If user is guest, shows login bottom sheet
/// If user is authenticated, executes the action
Future<void> requireAuth(
  BuildContext context,
  WidgetRef ref,
  Future<void> Function() action,
) async {
  final authController = ref.read(authControllerProvider);
  final authState = authController.value;

  if (authState is _Guest) {
    // Show login bottom sheet
    final result = await showLoginBottomSheet(context);
    
    // If login was successful, execute the action
    if (result == true) {
      await action();
    }
  } else if (authState is _Authenticated) {
    // User is authenticated, execute action
    await action();
  }
  // If loading, do nothing (could show loading indicator)
}

/// Extension on WidgetRef for easier access to auth helpers
extension AuthHelpers on WidgetRef {
  /// Helper to require authentication before executing an action
  Future<void> requireAuth(
    BuildContext context,
    Future<void> Function() action,
  ) async {
    final authState = read(authControllerProvider).value;
    
    if (authState?.isAuthenticated == true) {
      // User is authenticated, execute the action
      await action();
    } else {
      // User is not authenticated, emit auth event to show login
      read(authEventControllerProvider.notifier).emitEvent(
        const AuthEvent.showLoginSheet(message: 'Please sign in to continue'),
      );
    }
  }

  /// Check if user is authenticated
  bool get isAuthenticated {
    return read(authControllerProvider).value?.isAuthenticated ?? false;
  }

  /// Check if user is guest
  bool get isGuest {
    return read(authControllerProvider).value?.isGuest ?? true;
  }

  /// Get current user
  User? get currentUser {
    return read(authControllerProvider).value?.user;
  }
}