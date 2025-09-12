import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../api/auth_api_client.dart';
import '../models/user.dart';
import '../models/auth_requests.dart';
import '../token_storage.dart';
import '../../http/http_client.dart';

class AuthState {
  final User? user;
  final bool isLoading;
  final bool isAuthenticated;
  final String? error;

  const AuthState({
    this.user,
    this.isLoading = false,
    this.isAuthenticated = false,
    this.error,
  });

  AuthState copyWith({
    User? user,
    bool? isLoading,
    bool? isAuthenticated,
    String? error,
  }) {
    return AuthState(
      user: user ?? this.user,
      isLoading: isLoading ?? this.isLoading,
      isAuthenticated: isAuthenticated ?? this.isAuthenticated,
      error: error,
    );
  }
}

class AuthNotifier extends StateNotifier<AuthState> {
  final AuthApiClient _authApiClient;
  final TokenStorage _tokenStorage;

  AuthNotifier(this._authApiClient, this._tokenStorage) : super(const AuthState()) {
    _checkAuthState();
  }

  Future<void> _checkAuthState() async {
    state = state.copyWith(isLoading: true);
    
    try {
      final hasTokens = await _tokenStorage.hasValidTokens();
      if (hasTokens) {
        final user = await _authApiClient.getCurrentUser();
        state = state.copyWith(
          user: user,
          isAuthenticated: true,
          isLoading: false,
        );
      } else {
        state = state.copyWith(
          isAuthenticated: false,
          isLoading: false,
        );
      }
    } catch (e) {
      await _tokenStorage.clearTokens();
      state = state.copyWith(
        isAuthenticated: false,
        isLoading: false,
        error: e.toString(),
      );
    }
  }

  Future<void> login(String email, String password) async {
    state = state.copyWith(isLoading: true, error: null);
    
    try {
      final request = LoginRequest(email: email, password: password);
      final response = await _authApiClient.login(request);
      
      await _tokenStorage.saveTokens(
        response.tokens.accessToken,
        response.tokens.refreshToken,
      );
      await _tokenStorage.saveUserId(response.user.id);
      
      state = state.copyWith(
        user: response.user,
        isAuthenticated: true,
        isLoading: false,
      );
    } catch (e) {
      state = state.copyWith(
        isLoading: false,
        error: e.toString(),
      );
      rethrow;
    }
  }

  Future<void> register(RegisterRequest request) async {
    state = state.copyWith(isLoading: true, error: null);
    
    try {
      final response = await _authApiClient.register(request);
      
      await _tokenStorage.saveTokens(
        response.tokens.accessToken,
        response.tokens.refreshToken,
      );
      await _tokenStorage.saveUserId(response.user.id);
      
      state = state.copyWith(
        user: response.user,
        isAuthenticated: true,
        isLoading: false,
      );
    } catch (e) {
      state = state.copyWith(
        isLoading: false,
        error: e.toString(),
      );
      rethrow;
    }
  }

  Future<void> logout() async {
    state = state.copyWith(isLoading: true);
    
    try {
      await _authApiClient.logout();
    } catch (e) {
      // Continue with logout even if API call fails
    }
    
    await _tokenStorage.clearTokens();
    state = const AuthState();
  }

  Future<void> updateProfile(Map<String, dynamic> data) async {
    try {
      final updatedUser = await _authApiClient.updateProfile(data);
      state = state.copyWith(user: updatedUser);
    } catch (e) {
      state = state.copyWith(error: e.toString());
      rethrow;
    }
  }

  void clearError() {
    state = state.copyWith(error: null);
  }
}

final authApiClientProvider = Provider<AuthApiClient>((ref) {
  final httpClient = ref.read(httpClientProvider);
  return AuthApiClient(httpClient.dio);
});

final authProvider = StateNotifierProvider<AuthNotifier, AuthState>((ref) {
  final authApiClient = ref.read(authApiClientProvider);
  final tokenStorage = ref.read(tokenStorageProvider);
  return AuthNotifier(authApiClient, tokenStorage);
});