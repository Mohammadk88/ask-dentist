import 'package:freezed_annotation/freezed_annotation.dart';

part 'home_api_dto.freezed.dart';
part 'home_api_dto.g.dart';

@freezed
class HomeResponseDto with _$HomeResponseDto {
  const factory HomeResponseDto({
    required bool success,
    required String message,
    required HomeDataDto data,
    @JsonKey(name: 'action_flags') ActionFlagsDto? actionFlags,
    required String locale,
    required String timestamp,
    @JsonKey(name: 'is_guest') required bool isGuest,
    @JsonKey(name: 'user_authenticated') required bool userAuthenticated,
  }) = _HomeResponseDto;

  factory HomeResponseDto.fromJson(Map<String, dynamic> json) =>
      _$HomeResponseDtoFromJson(json);
}

@freezed
class HomeDataDto with _$HomeDataDto {
  const factory HomeDataDto({
    @Default([]) List<StoryDto> stories,
    @JsonKey(name: 'top_clinics') @Default([]) List<ClinicDto> topClinics,
    @JsonKey(name: 'top_doctors') @Default([]) List<DoctorDto> topDoctors,
    @JsonKey(name: 'before_after')
    @Default([])
    List<BeforeAfterDto> beforeAfter,
  }) = _HomeDataDto;

  factory HomeDataDto.fromJson(Map<String, dynamic> json) =>
      _$HomeDataDtoFromJson(json);
}

@freezed
class ActionFlagsDto with _$ActionFlagsDto {
  const factory ActionFlagsDto({
    @JsonKey(name: 'can_favorite') @Default(false) bool canFavorite,
    @JsonKey(name: 'can_book') @Default(false) bool canBook,
    @JsonKey(name: 'can_message') @Default(false) bool canMessage,
  }) = _ActionFlagsDto;

  factory ActionFlagsDto.fromJson(Map<String, dynamic> json) =>
      _$ActionFlagsDtoFromJson(json);
}

@freezed
class StoryDto with _$StoryDto {
  const factory StoryDto({
    required int id,
    required String title,
    required String content,
    required String image,
    @JsonKey(name: 'created_at') required String createdAt,
    @JsonKey(name: 'likes_count') required int likesCount,
  }) = _StoryDto;

  factory StoryDto.fromJson(Map<String, dynamic> json) =>
      _$StoryDtoFromJson(json);
}

@freezed
class ClinicDto with _$ClinicDto {
  const factory ClinicDto({
    required int id,
    required String name,
    required String location,
    required double rating,
    @JsonKey(name: 'reviews_count') required int reviewsCount,
    required String image,
    required List<String> specialties,
  }) = _ClinicDto;

  factory ClinicDto.fromJson(Map<String, dynamic> json) =>
      _$ClinicDtoFromJson(json);
}

@freezed
class DoctorDto with _$DoctorDto {
  const factory DoctorDto({
    required int id,
    required String name,
    required String specialty,
    required double rating,
    @JsonKey(name: 'reviews_count') required int reviewsCount,
    required String image,
    @JsonKey(name: 'years_experience') required int yearsExperience,
    @JsonKey(name: 'clinic_name') required String clinicName,
  }) = _DoctorDto;

  factory DoctorDto.fromJson(Map<String, dynamic> json) =>
      _$DoctorDtoFromJson(json);
}

@freezed
class BeforeAfterDto with _$BeforeAfterDto {
  const factory BeforeAfterDto({
    required int id,
    required String title,
    @JsonKey(name: 'before_image') required String beforeImage,
    @JsonKey(name: 'after_image') required String afterImage,
    @JsonKey(name: 'doctor_name') required String doctorName,
    @JsonKey(name: 'treatment_duration') required String treatmentDuration,
    required String description,
  }) = _BeforeAfterDto;

  factory BeforeAfterDto.fromJson(Map<String, dynamic> json) =>
      _$BeforeAfterDtoFromJson(json);
}
