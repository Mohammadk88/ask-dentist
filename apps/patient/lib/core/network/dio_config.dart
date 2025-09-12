import 'package:dio/dio.dart';
import 'package:flutter/foundation.dart';
import 'package:pretty_dio_logger/pretty_dio_logger.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../auth/token_storage.dart';
import '../auth/controllers/auth_controller.dart';
import '../auth/controllers/auth_event_controller.dart';
import '../auth/models/auth_event.dart';
import '../env/env_loader.dart';

class DioConfig {
  static const Duration _connectTimeout = Duration(seconds: 30);
  static const Duration _receiveTimeout = Duration(seconds: 30);

  static Dio createDio([ProviderContainer? container]) {
    final dio = Dio();

    // Use the same base URL logic as NetworkGuard
    final baseUrl = EnvLoader.getApiBaseUrl();

    // Base configuration
    dio.options = BaseOptions(
      baseUrl: baseUrl,
      connectTimeout: _connectTimeout,
      receiveTimeout: _receiveTimeout,
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
    );

    // Add interceptors
    dio.interceptors.addAll([
      AuthInterceptor(container),
      if (kDebugMode)
        PrettyDioLogger(
          requestHeader: true,
          requestBody: true,
          responseBody: true,
          responseHeader: false,
          error: true,
          compact: true,
          maxWidth: 90,
        ),
    ]);

    return dio;
  }
}

class AuthInterceptor extends Interceptor {
  final ProviderContainer? _container;

  AuthInterceptor(this._container);

  @override
  void onRequest(
      RequestOptions options, RequestInterceptorHandler handler) async {
    try {
      if (_container != null) {
        // Read auth state to check if user is authenticated
        final authState = _container!.read(authControllerProvider);
        final authStateValue = authState.valueOrNull;

        // Only attach token if user is authenticated and token exists
        if (authStateValue != null && authStateValue.isAuthenticated) {
          final tokenStorage = _container!.read(tokenStorageProvider);
          final tokens = await tokenStorage.getTokens();
          final accessToken = tokens['access_token'];

          if (accessToken != null && accessToken.isNotEmpty) {
            options.headers['Authorization'] = 'Bearer $accessToken';
          }
        }
      }
    } catch (e) {
      // Handle storage error silently
      if (kDebugMode) {
        print('Auth token retrieval error: $e');
      }
    }

    handler.next(options);
  }

  @override
  void onError(DioException err, ErrorInterceptorHandler handler) async {
    // Handle 401 Unauthorized responses
    if (err.response?.statusCode == 401) {
      try {
        final responseData = err.response?.data;

        // Check if error response has our expected format
        if (responseData is Map<String, dynamic>) {
          final errorCode = responseData['error_code'];
          final message = responseData['message'];

          if (_container != null) {
            // Handle specific error codes
            if (errorCode == 'auth_required') {
              // Emit auth event to show login sheet
              _container!.read(authEventControllerProvider.notifier).emitEvent(
                    AuthEvent.showLoginSheet(
                      message: message ?? 'Authentication required',
                    ),
                  );
            } else if (errorCode == 'token_expired' ||
                errorCode == 'token_invalid') {
              // Handle token expiration by logging out
              final authController =
                  _container!.read(authControllerProvider.notifier);
              await authController.logout();

              _container!.read(authEventControllerProvider.notifier).emitEvent(
                    AuthEvent.showLoginSheet(
                      message:
                          'Your session has expired. Please sign in again.',
                    ),
                  );
            }
          }

          // Clear tokens for any 401 error
          if (_container != null) {
            final tokenStorage = _container!.read(tokenStorageProvider);
            await tokenStorage.clearTokens();
          }

          if (kDebugMode) {
            print('Authentication error: $errorCode - $message');
          }
        }
      } catch (e) {
        if (kDebugMode) {
          print('Error handling 401 response: $e');
        }
      }
    }

    handler.next(err);
  }
}
