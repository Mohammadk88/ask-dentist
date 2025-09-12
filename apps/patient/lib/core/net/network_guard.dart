import 'package:connectivity_plus/connectivity_plus.dart';
import 'package:dio/dio.dart';
import 'package:pretty_dio_logger/pretty_dio_logger.dart';
import '../env/env.dart';
import '../env/env_loader.dart';
import 'exceptions.dart';

class NetworkGuard {
  static late Dio _dio;
  static final Connectivity _connectivity = Connectivity();

  static Dio get dio => _dio;

  /// Initialize Dio with proper configuration
  static void initialize() {
    final baseUrl = EnvLoader.getApiBaseUrl();

    _dio = Dio(BaseOptions(
      baseUrl: baseUrl,
      connectTimeout: const Duration(seconds: 10),
      receiveTimeout: const Duration(seconds: 15),
      followRedirects: true,
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
    ));

    // Add pretty logger in debug mode
    if (Env.debug) {
      _dio.interceptors.add(PrettyDioLogger(
        requestHeader: true,
        requestBody: true,
        responseBody: true,
        responseHeader: false,
        error: true,
        compact: true,
      ));
    }

    print('🌐 NetworkGuard initialized with base URL: $baseUrl');
  }

  /// Perform GET request with connectivity and error handling
  static Future<Response<T>> get<T>(
    String path, {
    Map<String, dynamic>? queryParameters,
    Options? options,
    CancelToken? cancelToken,
  }) async {
    await _checkConnectivity();

    try {
      final response = await _dio.get<T>(
        path,
        queryParameters: queryParameters,
        options: options,
        cancelToken: cancelToken,
      );
      return response;
    } on DioException catch (e) {
      throw _mapDioException(e);
    }
  }

  /// Perform POST request with connectivity and error handling
  static Future<Response<T>> post<T>(
    String path, {
    dynamic data,
    Map<String, dynamic>? queryParameters,
    Options? options,
    CancelToken? cancelToken,
  }) async {
    await _checkConnectivity();

    try {
      final response = await _dio.post<T>(
        path,
        data: data,
        queryParameters: queryParameters,
        options: options,
        cancelToken: cancelToken,
      );
      return response;
    } on DioException catch (e) {
      throw _mapDioException(e);
    }
  }

  /// Perform PUT request with connectivity and error handling
  static Future<Response<T>> put<T>(
    String path, {
    dynamic data,
    Map<String, dynamic>? queryParameters,
    Options? options,
    CancelToken? cancelToken,
  }) async {
    await _checkConnectivity();

    try {
      final response = await _dio.put<T>(
        path,
        data: data,
        queryParameters: queryParameters,
        options: options,
        cancelToken: cancelToken,
      );
      return response;
    } on DioException catch (e) {
      throw _mapDioException(e);
    }
  }

  /// Perform DELETE request with connectivity and error handling
  static Future<Response<T>> delete<T>(
    String path, {
    dynamic data,
    Map<String, dynamic>? queryParameters,
    Options? options,
    CancelToken? cancelToken,
  }) async {
    await _checkConnectivity();

    try {
      final response = await _dio.delete<T>(
        path,
        data: data,
        queryParameters: queryParameters,
        options: options,
        cancelToken: cancelToken,
      );
      return response;
    } on DioException catch (e) {
      throw _mapDioException(e);
    }
  }

  /// Check connectivity before making requests
  static Future<void> _checkConnectivity() async {
    final connectivityResult = await _connectivity.checkConnectivity();

    if (connectivityResult.contains(ConnectivityResult.none)) {
      throw const AppNoInternetException();
    }
  }

  /// Map DioException to appropriate app exceptions
  static Exception _mapDioException(DioException e) {
    switch (e.type) {
      case DioExceptionType.connectionError:
      case DioExceptionType.connectionTimeout:
      case DioExceptionType.receiveTimeout:
      case DioExceptionType.sendTimeout:
        return const AppNoInternetException();

      case DioExceptionType.badResponse:
        final statusCode = e.response?.statusCode ?? 0;
        final message = _extractErrorMessage(e.response?.data) ??
            e.response?.statusMessage ??
            'HTTP Error $statusCode';

        return AppHttpException(
          statusCode,
          message,
          data: e.response?.data is Map<String, dynamic>
              ? e.response!.data as Map<String, dynamic>
              : null,
        );

      case DioExceptionType.cancel:
        return AppException('Request cancelled');

      case DioExceptionType.badCertificate:
        return const AppException('SSL certificate error');

      case DioExceptionType.unknown:
      default:
        // Check if it's actually a connectivity issue
        if (e.message?.contains('SocketException') == true ||
            e.message?.contains('Connection refused') == true ||
            e.message?.contains('Network is unreachable') == true) {
          return const AppNoInternetException();
        }

        return AppException(e.message ?? 'Unknown network error');
    }
  }

  /// Extract error message from response data
  static String? _extractErrorMessage(dynamic data) {
    if (data is Map<String, dynamic>) {
      return data['message'] as String? ??
          data['error'] as String? ??
          data['detail'] as String?;
    }
    return null;
  }

  /// Test connectivity to API health endpoint
  static Future<Map<String, dynamic>> testHealthEndpoint() async {
    final stopwatch = Stopwatch()..start();

    try {
      final response = await get('/api/v1/health');
      stopwatch.stop();

      return {
        'success': true,
        'latency': stopwatch.elapsedMilliseconds,
        'data': response.data,
      };
    } catch (e) {
      stopwatch.stop();

      return {
        'success': false,
        'latency': stopwatch.elapsedMilliseconds,
        'error': e.toString(),
      };
    }
  }
}
