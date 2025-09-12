import '../models/home_api_dto.dart';

class HomeCache {
  static const Duration _cacheDuration = Duration(seconds: 60);
  
  HomeResponseDto? _cachedData;
  DateTime? _cacheTimestamp;
  
  // Singleton
  static final HomeCache _instance = HomeCache._internal();
  factory HomeCache() => _instance;
  HomeCache._internal();

  /// Check if cached data is still valid
  bool get _isValid {
    if (_cachedData == null || _cacheTimestamp == null) {
      return false;
    }
    
    final now = DateTime.now();
    final difference = now.difference(_cacheTimestamp!);
    return difference < _cacheDuration;
  }

  /// Get cached data if valid, null otherwise
  HomeResponseDto? getCachedData() {
    if (_isValid) {
      return _cachedData;
    }
    
    // Clear invalid cache
    _clearCache();
    return null;
  }

  /// Cache new data with timestamp
  void cacheData(HomeResponseDto data) {
    _cachedData = data;
    _cacheTimestamp = DateTime.now();
  }

  /// Clear cache manually (used on pull-to-refresh)
  void invalidateCache() {
    _clearCache();
  }

  /// Internal method to clear cache
  void _clearCache() {
    _cachedData = null;
    _cacheTimestamp = null;
  }

  /// Get remaining cache time in seconds
  int? getRemainingCacheTime() {
    if (!_isValid) return null;
    
    final now = DateTime.now();
    final elapsed = now.difference(_cacheTimestamp!);
    final remaining = _cacheDuration - elapsed;
    
    return remaining.inSeconds;
  }

  /// Check if data is cached (regardless of validity)
  bool get hasCachedData => _cachedData != null;

  /// Get cache timestamp
  DateTime? get cacheTimestamp => _cacheTimestamp;
}