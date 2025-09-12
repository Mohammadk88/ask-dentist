import 'package:dio/dio.dart';
import 'package:riverpod_annotation/riverpod_annotation.dart';

part 'favorites_service.g.dart';

@riverpod
FavoritesService favoritesService(FavoritesServiceRef ref) {
  // This would typically inject Dio from a provider
  final dio = Dio(BaseOptions(
    baseUrl: 'http://10.0.2.2:8000/api', // Android emulator localhost
  ));
  return FavoritesService(dio);
}

class FavoritesService {
  final Dio _dio;

  FavoritesService(this._dio);

  /// Toggle doctor favorite status
  Future<bool> toggleDoctorFavorite(String doctorId) async {
    try {
      final response = await _dio.post('/favorites/doctors/$doctorId');
      return response.data['is_favorite'] ?? false;
    } on DioException catch (e) {
      if (e.response?.statusCode == 401) {
        throw UnauthorizedException('Please login to add favorites');
      }
      throw Exception('Failed to toggle favorite: ${e.message}');
    }
  }

  /// Toggle clinic favorite status
  Future<bool> toggleClinicFavorite(String clinicId) async {
    try {
      final response = await _dio.post('/favorites/clinics/$clinicId');
      return response.data['is_favorite'] ?? false;
    } on DioException catch (e) {
      if (e.response?.statusCode == 401) {
        throw UnauthorizedException('Please login to add favorites');
      }
      throw Exception('Failed to toggle favorite: ${e.message}');
    }
  }

  /// Get favorite doctors
  Future<List<String>> getFavoriteDoctors() async {
    try {
      final response = await _dio.get('/favorites/doctors');
      final List<dynamic> data = response.data['data'] ?? [];
      return data.map((item) => item['id'].toString()).toList();
    } on DioException catch (e) {
      if (e.response?.statusCode == 401) {
        return []; // Return empty list if not authenticated
      }
      throw Exception('Failed to get favorite doctors: ${e.message}');
    }
  }

  /// Get favorite clinics
  Future<List<String>> getFavoriteClinics() async {
    try {
      final response = await _dio.get('/favorites/clinics');
      final List<dynamic> data = response.data['data'] ?? [];
      return data.map((item) => item['id'].toString()).toList();
    } on DioException catch (e) {
      if (e.response?.statusCode == 401) {
        return []; // Return empty list if not authenticated
      }
      throw Exception('Failed to get favorite clinics: ${e.message}');
    }
  }
}

class UnauthorizedException implements Exception {
  final String message;
  UnauthorizedException(this.message);
}