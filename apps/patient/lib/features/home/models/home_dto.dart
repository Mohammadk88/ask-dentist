import 'package:freezed_annotation/freezed_annotation.dart';

part 'home_dto.freezed.dart';
part 'home_dto.g.dart';

@freezed
class HomeDto with _$HomeDto {
  const factory HomeDto({
    required List<StoryItem> stories,
    required List<ClinicItem> featuredClinics,
    required List<DoctorItem> recommendedDoctors,
    required List<DoctorItem> favoriteDoctors,
    required List<BeforeAfterCase> beforeAfterGallery,
  }) = _HomeDto;

  factory HomeDto.fromJson(Map<String, dynamic> json) => _$HomeDtoFromJson(json);
}

@freezed
class StoryItem with _$StoryItem {
  const factory StoryItem({
    required String id,
    required String ownerName,
    required String avatarUrl,
    required String content,
    required StoryContentType contentType,
    required String caption,
    required bool isViewed,
    required DateTime timestamp,
    required bool isPromoted,
  }) = _StoryItem;

  factory StoryItem.fromJson(Map<String, dynamic> json) => _$StoryItemFromJson(json);
}

enum StoryContentType { text, image, video }

@freezed
class ClinicItem with _$ClinicItem {
  const factory ClinicItem({
    required String id,
    required String name,
    required String imageUrl,
    required double rating,
    required int reviewCount,
    required bool isPromoted,
    required bool isVerified,
    required String location,
  }) = _ClinicItem;

  factory ClinicItem.fromJson(Map<String, dynamic> json) => _$ClinicItemFromJson(json);
}

@freezed
class DoctorItem with _$DoctorItem {
  const factory DoctorItem({
    required String id,
    required String name,
    required String specialty,
    required String avatarUrl,
    required double rating,
    required int reviewCount,
    required String experience,
    required List<String> languages,
    required bool isOnline,
    required bool isFavorite,
    required String clinicName,
    required int consultationFee,
  }) = _DoctorItem;

  factory DoctorItem.fromJson(Map<String, dynamic> json) => _$DoctorItemFromJson(json);
}

@freezed
class BeforeAfterItem with _$BeforeAfterItem {
  const factory BeforeAfterItem({
    required String id,
    required String beforeImageUrl,
    required String afterImageUrl,
    required String caption,
    required String doctorName,
    required String treatmentType,
  }) = _BeforeAfterItem;

  factory BeforeAfterItem.fromJson(Map<String, dynamic> json) => _$BeforeAfterItemFromJson(json);
}

@freezed
class BeforeAfterCase with _$BeforeAfterCase {
  const factory BeforeAfterCase({
    required String id,
    required String title,
    required String beforeImageUrl,
    required String afterImageUrl,
    required String description,
    required String doctorName,
    required String duration,
    required bool isFeatured,
    required int likes,
    required int comments,
  }) = _BeforeAfterCase;

  factory BeforeAfterCase.fromJson(Map<String, dynamic> json) => _$BeforeAfterCaseFromJson(json);
}