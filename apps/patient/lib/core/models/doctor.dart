import 'package:freezed_annotation/freezed_annotation.dart';

part 'doctor.freezed.dart';
part 'doctor.g.dart';

enum DoctorSpecialty {
  @JsonValue('general_dentistry')
  generalDentistry,
  @JsonValue('cosmetic_dentistry')
  cosmeticDentistry,
  @JsonValue('orthodontics')
  orthodontics,
  @JsonValue('oral_surgery')
  oralSurgery,
  @JsonValue('endodontics')
  endodontics,
  @JsonValue('periodontics')
  periodontics,
  @JsonValue('prosthodontics')
  prosthodontics,
  @JsonValue('pediatric_dentistry')
  pediatricDentistry,
}

@freezed
class Doctor with _$Doctor {
  const factory Doctor({
    required String id,
    required String firstName,
    required String lastName,
    required String profileImageUrl,
    required String clinicId,
    required DoctorSpecialty specialty,
    required double rating,
    required int reviewCount,
    required int experienceYears,
    required List<String> languages,
    required List<String> services,
    String? bio,
    String? education,
    String? phone,
    String? email,
    bool? isAvailable,
    bool? isFavorite,
    DateTime? nextAvailableDate,
    Map<String, dynamic>? metadata,
  }) = _Doctor;

  factory Doctor.fromJson(Map<String, dynamic> json) => _$DoctorFromJson(json);
}

@freezed
class DoctorReview with _$DoctorReview {
  const factory DoctorReview({
    required String id,
    required String doctorId,
    required String patientName,
    required double rating,
    required String comment,
    required DateTime createdAt,
    String? procedure,
    List<String>? imageUrls,
    Map<String, dynamic>? metadata,
  }) = _DoctorReview;

  factory DoctorReview.fromJson(Map<String, dynamic> json) => _$DoctorReviewFromJson(json);
}