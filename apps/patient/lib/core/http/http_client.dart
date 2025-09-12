import 'package:dio/dio.dart';
import 'package:pretty_dio_logger/pretty_dio_logger.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../env/app_environment.dart';
import '../auth/token_storage.dart';

class HttpClient {
  late final Dio _dio;
  final TokenStorage _tokenStorage;

  HttpClient(this._tokenStorage) {
    _dio = Dio(BaseOptions(
      baseUrl: AppEnvironment.baseUrl,
      connectTimeout: const Duration(seconds: 30),
      receiveTimeout: const Duration(seconds: 30),
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
    ));

    _setupInterceptors();
  }

  void _setupInterceptors() {
    // Request interceptor to add auth token
    _dio.interceptors.add(
      InterceptorsWrapper(
        onRequest: (options, handler) async {
          final token = await _tokenStorage.getAccessToken();
          if (token != null) {
            options.headers['Authorization'] = 'Bearer $token';
          }
          handler.next(options);
        },
        onError: (error, handler) async {
          if (error.response?.statusCode == 401) {
            // Try to refresh token
            final refreshToken = await _tokenStorage.getRefreshToken();
            if (refreshToken != null) {
              try {
                final newTokens = await _refreshToken(refreshToken);
                await _tokenStorage.saveTokens(
                  newTokens['access_token'],
                  newTokens['refresh_token'],
                );
                
                // Retry original request
                final options = error.requestOptions;
                options.headers['Authorization'] = 'Bearer ${newTokens['access_token']}';
                final response = await _dio.fetch(options);
                handler.resolve(response);
                return;
              } catch (e) {
                // Refresh failed, logout user
                await _tokenStorage.clearTokens();
              }
            }
          }
          handler.next(error);
        },
      ),
    );

    // Logging interceptor (only in debug mode)
    if (AppEnvironment.isDebug) {
      _dio.interceptors.add(
        PrettyDioLogger(
          requestHeader: true,
          requestBody: true,
          responseHeader: true,
          responseBody: true,
          error: true,
          compact: true,
        ),
      );
    }
  }

  Future<Map<String, dynamic>> _refreshToken(String refreshToken) async {
    final response = await _dio.post(
      '/auth/refresh',
      data: {'refresh_token': refreshToken},
    );
    return response.data;
  }

  Dio get dio => _dio;
}

final httpClientProvider = Provider<HttpClient>((ref) {
  final tokenStorage = ref.read(tokenStorageProvider);
  return HttpClient(tokenStorage);
});