import 'package:riverpod_annotation/riverpod_annotation.dart';
import '../services/favorites_service.dart';

part 'favorites_notifier.g.dart';

@riverpod
class FavoriteDoctorsNotifier extends _$FavoriteDoctorsNotifier {
  @override
  Future<Set<String>> build() async {
    final favoritesService = ref.read(favoritesServiceProvider);
    try {
      final favorites = await favoritesService.getFavoriteDoctors();
      return favorites.toSet();
    } catch (e) {
      // Return empty set if error (e.g., not authenticated)
      return <String>{};
    }
  }

  Future<bool> toggleFavorite(String doctorId) async {
    final favoritesService = ref.read(favoritesServiceProvider);
    
    // Optimistic update
    final currentState = await future;
    final newState = Set<String>.from(currentState);
    final wasAdded = !newState.contains(doctorId);
    
    if (wasAdded) {
      newState.add(doctorId);
    } else {
      newState.remove(doctorId);
    }
    
    // Update state optimistically
    state = AsyncValue.data(newState);
    
    try {
      final isFavorite = await favoritesService.toggleDoctorFavorite(doctorId);
      
      // Update state with server response
      final finalState = Set<String>.from(currentState);
      if (isFavorite) {
        finalState.add(doctorId);
      } else {
        finalState.remove(doctorId);
      }
      state = AsyncValue.data(finalState);
      
      return isFavorite;
    } catch (e) {
      // Revert optimistic update on error
      state = AsyncValue.data(currentState);
      rethrow;
    }
  }
}

@riverpod
class FavoriteClinicsNotifier extends _$FavoriteClinicsNotifier {
  @override
  Future<Set<String>> build() async {
    final favoritesService = ref.read(favoritesServiceProvider);
    try {
      final favorites = await favoritesService.getFavoriteClinics();
      return favorites.toSet();
    } catch (e) {
      // Return empty set if error (e.g., not authenticated)
      return <String>{};
    }
  }

  Future<bool> toggleFavorite(String clinicId) async {
    final favoritesService = ref.read(favoritesServiceProvider);
    
    // Optimistic update
    final currentState = await future;
    final newState = Set<String>.from(currentState);
    final wasAdded = !newState.contains(clinicId);
    
    if (wasAdded) {
      newState.add(clinicId);
    } else {
      newState.remove(clinicId);
    }
    
    // Update state optimistically
    state = AsyncValue.data(newState);
    
    try {
      final isFavorite = await favoritesService.toggleClinicFavorite(clinicId);
      
      // Update state with server response
      final finalState = Set<String>.from(currentState);
      if (isFavorite) {
        finalState.add(clinicId);
      } else {
        finalState.remove(clinicId);
      }
      state = AsyncValue.data(finalState);
      
      return isFavorite;
    } catch (e) {
      // Revert optimistic update on error
      state = AsyncValue.data(currentState);
      rethrow;
    }
  }
}