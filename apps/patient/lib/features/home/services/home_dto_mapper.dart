import '../models/home_dto.dart';
import '../models/home_api_dto.dart';

class HomeDtoMapper {
  static HomeDto mapApiResponseToHome(HomeResponseDto apiResponse) {
    final data = apiResponse.data;
    return HomeDto(
      stories: data.stories.map(mapStoryDtoToStoryItem).toList(),
      featuredClinics: data.topClinics.map(mapClinicDtoToClinicItem).toList(),
      recommendedDoctors:
          data.topDoctors.map(mapDoctorDtoToDoctorItem).toList(),
      favoriteDoctors: const <DoctorItem>[], // Empty for now since API doesn't provide favorites
      beforeAfterGallery:
          data.beforeAfter.map(mapBeforeAfterDtoToBeforeAfterItem).toList(),
    );
  }

  static StoryItem mapStoryDtoToStoryItem(StoryDto dto) {
    return StoryItem(
      id: dto.id.toString(),
      ownerName: 'Ask Dentist',
      avatarUrl: 'https://via.placeholder.com/50x50/4F46E5/ffffff?text=AD',
      content: dto.image,
      contentType: StoryContentType.image,
      caption: dto.title,
      isViewed: false,
      timestamp: DateTime.tryParse(dto.createdAt) ?? DateTime.now(),
      isPromoted: false,
    );
  }

  static ClinicItem mapClinicDtoToClinicItem(ClinicDto dto) {
    return ClinicItem(
      id: dto.id.toString(),
      name: dto.name,
      imageUrl: dto.image,
      rating: dto.rating,
      reviewCount: dto.reviewsCount,
      isPromoted: false,
      isVerified: true,
      location: dto.location,
    );
  }

  static DoctorItem mapDoctorDtoToDoctorItem(DoctorDto dto) {
    return DoctorItem(
      id: dto.id.toString(),
      name: dto.name,
      specialty: dto.specialty,
      avatarUrl: dto.image,
      rating: dto.rating,
      reviewCount: dto.reviewsCount,
      experience: '${dto.yearsExperience}+ years',
      languages: ['English', 'Arabic'],
      isOnline: true,
      isFavorite: false,
      clinicName: dto.clinicName,
      consultationFee: 150, // Default consultation fee
    );
  }

  static BeforeAfterCase mapBeforeAfterDtoToBeforeAfterItem(
      BeforeAfterDto dto) {
    return BeforeAfterCase(
      id: dto.id.toString(),
      title: dto.title,
      beforeImageUrl: dto.beforeImage,
      afterImageUrl: dto.afterImage,
      description: dto.description,
      doctorName: dto.doctorName,
      duration: dto.treatmentDuration,
      isFeatured: true,
      likes: 15 + (dto.id * 3), // Generate some likes based on ID
      comments: 5 + (dto.id * 2), // Generate some comments based on ID
    );
  }
}
