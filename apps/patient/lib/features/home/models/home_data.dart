import 'package:freezed_annotation/freezed_annotation.dart';

part 'home_data.freezed.dart';
part 'home_data.g.dart';

@freezed
class HomeData with _$HomeData {
  const factory HomeData({
    @Default([]) List<Story> stories,
    @Default([]) List<Clinic> topClinics,
    @Default([]) List<Doctor> topDoctors,
    @Default([]) List<BeforeAfterCase> beforeAfter,
    @Default([]) List<Doctor> favoritesDoctors,
    ActionFlags? actionFlags,
    @Default('en') String locale,
    required String timestamp,
    @Default(true) bool isGuest,
    @Default(false) bool userAuthenticated,
  }) = _HomeData;

  factory HomeData.fromJson(Map<String, dynamic> json) => _$HomeDataFromJson(json);
}

@freezed
class Story with _$Story {
  const factory Story({
    required String id,
    required String title,
    String? description,
    String? imageUrl,
    String? ownerType,
    String? ownerId,
    String? ownerName,
    DateTime? createdAt,
    DateTime? expiresAt,
  }) = _Story;

  factory Story.fromJson(Map<String, dynamic> json) => _$StoryFromJson(json);
}

@freezed
class Clinic with _$Clinic {
  const factory Clinic({
    required String id,
    required String name,
    String? description,
    String? imageUrl,
    String? address,
    String? phone,
    double? rating,
    int? reviewCount,
  }) = _Clinic;

  factory Clinic.fromJson(Map<String, dynamic> json) => _$ClinicFromJson(json);
}

@freezed
class Doctor with _$Doctor {
  const factory Doctor({
    required String id,
    required String name,
    String? specialization,
    String? imageUrl,
    String? clinicName,
    double? rating,
    int? reviewCount,
    bool? isFavorite,
  }) = _Doctor;

  factory Doctor.fromJson(Map<String, dynamic> json) => _$DoctorFromJson(json);
}

@freezed
class BeforeAfterCase with _$BeforeAfterCase {
  const factory BeforeAfterCase({
    required String id,
    required String title,
    String? description,
    String? beforeImageUrl,
    String? afterImageUrl,
    String? doctorName,
    String? treatment,
  }) = _BeforeAfterCase;

  factory BeforeAfterCase.fromJson(Map<String, dynamic> json) => _$BeforeAfterCaseFromJson(json);
}

@freezed
class ActionFlags with _$ActionFlags {
  const factory ActionFlags({
    @Default(false) bool canFavorite,
    @Default(false) bool canBook,
    @Default(false) bool canMessage,
  }) = _ActionFlags;

  factory ActionFlags.fromJson(Map<String, dynamic> json) => _$ActionFlagsFromJson(json);
}
