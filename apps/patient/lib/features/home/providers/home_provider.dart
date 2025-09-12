import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../models/home_dto.dart';
import '../services/home_service.dart';
import '../services/home_dto_mapper.dart';

class HomeState {
  final List<StoryItem> stories;
  final List<ClinicItem> featuredClinics;
  final List<DoctorItem> favoriteDoctors;
  final List<DoctorItem> recommendedDoctors;
  final List<BeforeAfterCase> beforeAfterGallery;
  final bool isLoading;
  final String? error;

  HomeState({
    this.stories = const [],
    this.featuredClinics = const [],
    this.favoriteDoctors = const [],
    this.recommendedDoctors = const [],
    this.beforeAfterGallery = const [],
    this.isLoading = false,
    this.error,
  });

  HomeState copyWith({
    List<StoryItem>? stories,
    List<ClinicItem>? featuredClinics,
    List<DoctorItem>? favoriteDoctors,
    List<DoctorItem>? recommendedDoctors,
    List<BeforeAfterCase>? beforeAfterGallery,
    bool? isLoading,
    String? error,
  }) {
    return HomeState(
      stories: stories ?? this.stories,
      featuredClinics: featuredClinics ?? this.featuredClinics,
      favoriteDoctors: favoriteDoctors ?? this.favoriteDoctors,
      recommendedDoctors: recommendedDoctors ?? this.recommendedDoctors,
      beforeAfterGallery: beforeAfterGallery ?? this.beforeAfterGallery,
      isLoading: isLoading ?? this.isLoading,
      error: error ?? this.error,
    );
  }
}

class HomeNotifier extends StateNotifier<HomeState> {
  final HomeService _homeService;

  HomeNotifier({HomeService? homeService}) 
      : _homeService = homeService ?? HomeService(),
        super(HomeState()) {
    loadHomeData();
  }

  Future<void> loadHomeData({bool forceRefresh = false}) async {
    state = state.copyWith(isLoading: true, error: null);

    try {
      // Fetch data from API with caching
      final apiResponse = await _homeService.getHomeFeed(forceRefresh: forceRefresh);
      
      // Map API response to domain models
      final homeData = HomeDtoMapper.mapApiResponseToHome(apiResponse);

      state = state.copyWith(
        stories: homeData.stories,
        featuredClinics: homeData.featuredClinics,
        favoriteDoctors: homeData.favoriteDoctors,
        recommendedDoctors: homeData.recommendedDoctors,
        beforeAfterGallery: homeData.beforeAfterGallery,
        isLoading: false,
      );
    } catch (e) {
      state = state.copyWith(
        isLoading: false,
        error: e.toString(),
      );
    }
  }

  Future<void> toggleFavoriteDoctor(String doctorId) async {
    // TODO: Implement favorite toggle API call
    final updatedFavorites = state.favoriteDoctors.map((doctor) {
      if (doctor.id == doctorId) {
        return doctor.copyWith(isFavorite: !doctor.isFavorite);
      }
      return doctor;
    }).toList();

    final updatedRecommended = state.recommendedDoctors.map((doctor) {
      if (doctor.id == doctorId) {
        return doctor.copyWith(isFavorite: !doctor.isFavorite);
      }
      return doctor;
    }).toList();

    state = state.copyWith(
      favoriteDoctors: updatedFavorites,
      recommendedDoctors: updatedRecommended,
    );
  }

  /// Clear cache and reload data (used for pull-to-refresh)
  Future<void> refreshData() async {
    await loadHomeData(forceRefresh: true);
  }

  /// Get cache status information
  bool get hasCachedData => _homeService.hasCachedData;
  int? get remainingCacheTime => _homeService.remainingCacheTime;
}

final homeProvider = StateNotifierProvider<HomeNotifier, HomeState>((ref) {
  return HomeNotifier();
});