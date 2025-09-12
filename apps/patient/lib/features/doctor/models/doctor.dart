import 'package:freezed_annotation/freezed_annotation.dart';

part 'doctor.freezed.dart';
part 'doctor.g.dart';

@freezed
class Doctor with _$Doctor {
  const factory Doctor({
    required String id,
    required String firstName,
    required String lastName,
    required String email,
    String? phoneNumber,
    String? profileImageUrl,
    String? bio,
    String? specialization,
    int? experienceYears,
    List<String>? languages,
    List<String>? certifications,
    @Default(0.0) double rating,
    @Default(0) int reviewsCount,
    @Default(false) bool isVerified,
    @Default(false) bool isOnline,
    List<Clinic>? clinics,
    List<Service>? services,
    List<BeforeAfterImage>? beforeAfterImages,
    DateTime? createdAt,
    DateTime? updatedAt,
  }) = _Doctor;

  factory Doctor.fromJson(Map<String, dynamic> json) => _$DoctorFromJson(json);
}

@freezed
class Clinic with _$Clinic {
  const factory Clinic({
    required String id,
    required String name,
    required String address,
    required String city,
    required String country,
    String? phoneNumber,
    String? email,
    String? website,
    String? description,
    List<String>? imageUrls,
    @Default(0.0) double rating,
    @Default(0) int reviewsCount,
    Map<String, String>? workingHours,
    List<String>? amenities,
    DateTime? createdAt,
    DateTime? updatedAt,
  }) = _Clinic;

  factory Clinic.fromJson(Map<String, dynamic> json) => _$ClinicFromJson(json);
}

@freezed
class Service with _$Service {
  const factory Service({
    required String id,
    required String name,
    required String description,
    required String category,
    int? durationMinutes,
    double? price,
    String? currency,
    @Default(false) bool isToothSpecific,
    DateTime? createdAt,
    DateTime? updatedAt,
  }) = _Service;

  factory Service.fromJson(Map<String, dynamic> json) => _$ServiceFromJson(json);
}

@freezed
class BeforeAfterImage with _$BeforeAfterImage {
  const factory BeforeAfterImage({
    required String id,
    required String beforeImageUrl,
    required String afterImageUrl,
    String? description,
    String? treatmentType,
    DateTime? createdAt,
  }) = _BeforeAfterImage;

  factory BeforeAfterImage.fromJson(Map<String, dynamic> json) => _$BeforeAfterImageFromJson(json);
}