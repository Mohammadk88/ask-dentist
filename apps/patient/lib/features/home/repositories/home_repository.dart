import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../core/net/network_guard.dart';
import '../../../core/net/exceptions.dart';
import '../models/home_data.dart';

class HomeRepository {
  /// Fetch home data with proper error handling
  Future<HomeData> getHomeData() async {
    try {
      final response = await NetworkGuard.get('/api/v1/home');

      if (response.statusCode == 200 && response.data != null) {
        final responseData = response.data as Map<String, dynamic>;

        // Extract data safely from API response
        final data = responseData['data'] as Map<String, dynamic>? ?? {};
        final actionFlags =
            responseData['action_flags'] as Map<String, dynamic>?;

        // Create HomeData using factory constructor
        return HomeData(
          stories: const <Story>[],
          topClinics: const <Clinic>[],
          topDoctors: const <Doctor>[],
          beforeAfter: const <BeforeAfterCase>[],
          favoritesDoctors: const <Doctor>[],
          actionFlags:
              actionFlags != null ? ActionFlags.fromJson(actionFlags) : null,
          locale: responseData['locale']?.toString() ?? 'en',
          timestamp: responseData['timestamp']?.toString() ??
              DateTime.now().toIso8601String(),
          isGuest: responseData['is_guest'] ?? true,
          userAuthenticated: responseData['user_authenticated'] ?? false,
        );
      } else {
        throw AppHttpException(
          response.statusCode ?? 500,
          'Invalid response from server',
        );
      }
    } on AppNoInternetException {
      // Re-throw no internet exceptions as-is
      rethrow;
    } on AppHttpException catch (e) {
      // Handle specific HTTP errors
      if (e.isAuthRequired) {
        // For home endpoint, auth is optional, so we don't redirect
        // Just log the issue and let the UI handle it
        print('🔐 Auth required for enhanced features: ${e.message}');
        rethrow;
      } else if (e.isServerError) {
        // Server errors - provide user-friendly message
        throw AppException(
            'Server is temporarily unavailable. Please try again later.');
      } else {
        // Other HTTP errors
        rethrow;
      }
    } catch (e) {
      // Unexpected errors
      print('🔥 Unexpected error in HomeRepository: $e');
      throw AppException('Something went wrong. Please try again.');
    }
  }

  /// Test API connectivity
  Future<Map<String, dynamic>> testConnectivity() async {
    return await NetworkGuard.testHealthEndpoint();
  }
}

// Riverpod provider for HomeRepository
final homeRepositoryProvider = Provider<HomeRepository>((ref) {
  return HomeRepository();
});

// Provider for home data
final homeDataProvider = FutureProvider<HomeData>((ref) async {
  final repository = ref.read(homeRepositoryProvider);
  return repository.getHomeData();
});

// Provider for connectivity test
final connectivityTestProvider =
    FutureProvider<Map<String, dynamic>>((ref) async {
  final repository = ref.read(homeRepositoryProvider);
  return repository.testConnectivity();
});
