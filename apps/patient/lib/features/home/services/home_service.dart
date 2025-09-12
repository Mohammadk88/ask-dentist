import 'package:dio/dio.dart';
import 'package:flutter/foundation.dart';
import '../../../core/network/dio_config.dart';
import '../api/home_api.dart';
import '../models/home_api_dto.dart';
import 'home_cache.dart';

class HomeService {
  late final HomeApi _homeApi;
  late final HomeCache _cache;

  HomeService() {
    final dio = DioConfig.createDio();
    _homeApi = HomeApi(dio);
    _cache = HomeCache();
  }

  /// Get home feed data with caching
  Future<HomeResponseDto> getHomeFeed({bool forceRefresh = false}) async {
    try {
      // Check cache first unless force refresh
      if (!forceRefresh) {
        final cachedData = _cache.getCachedData();
        if (cachedData != null) {
          if (kDebugMode) {
            final remainingTime = _cache.getRemainingCacheTime();
            print('Returning cached home data (expires in ${remainingTime}s)');
          }
          return cachedData;
        }
      } else {
        // Force refresh: invalidate cache
        _cache.invalidateCache();
      }

      if (kDebugMode) {
        print('Fetching fresh home data from API...');
      }

      // Fetch fresh data from API
      final response = await _homeApi.getHomeFeed();
      
      // Cache the response
      _cache.cacheData(response);
      
      if (kDebugMode) {
        print('Home data fetched and cached successfully');
      }
      
      return response;
    } on DioException catch (e) {
      if (kDebugMode) {
        print('Home API Error: ${e.message}');
        print('Error type: ${e.type}');
        print('Status code: ${e.response?.statusCode}');
      }
      
      // Return cached data as fallback if available
      final cachedData = _cache.getCachedData();
      if (cachedData != null) {
        if (kDebugMode) {
          print('API failed, returning stale cached data');
        }
        return cachedData;
      }
      
      // No cache available, rethrow
      throw _mapDioException(e);
    } catch (e) {
      if (kDebugMode) {
        print('Unexpected home service error: $e');
      }
      
      // Return cached data as fallback if available
      final cachedData = _cache.getCachedData();
      if (cachedData != null) {
        if (kDebugMode) {
          print('Unexpected error, returning stale cached data');
        }
        return cachedData;
      }
      
      rethrow;
    }
  }

  /// Clear cache manually
  void clearCache() {
    _cache.invalidateCache();
  }

  /// Check if data is cached
  bool get hasCachedData => _cache.hasCachedData;

  /// Get remaining cache time
  int? get remainingCacheTime => _cache.getRemainingCacheTime();

  /// Map Dio exceptions to user-friendly messages
  Exception _mapDioException(DioException e) {
    switch (e.type) {
      case DioExceptionType.connectionTimeout:
      case DioExceptionType.receiveTimeout:
      case DioExceptionType.sendTimeout:
        return Exception('Connection timeout. Please check your internet connection.');
      
      case DioExceptionType.connectionError:
        return Exception('No internet connection. Please check your network settings.');
      
      case DioExceptionType.badResponse:
        final statusCode = e.response?.statusCode;
        switch (statusCode) {
          case 401:
            return Exception('Authentication failed. Please log in again.');
          case 403:
            return Exception('Access denied.');
          case 404:
            return Exception('Service not found.');
          case 500:
            return Exception('Server error. Please try again later.');
          default:
            return Exception('Something went wrong. Please try again.');
        }
      
      case DioExceptionType.cancel:
        return Exception('Request was cancelled.');
      
      case DioExceptionType.unknown:
      default:
        return Exception('An unexpected error occurred. Please try again.');
    }
  }
}