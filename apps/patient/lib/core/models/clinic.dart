import 'package:freezed_annotation/freezed_annotation.dart';

part 'clinic.freezed.dart';
part 'clinic.g.dart';

@freezed
class Clinic with _$Clinic {
  const factory Clinic({
    required String id,
    required String name,
    required String address,
    required String city,
    required String country,
    required double rating,
    required int reviewCount,
    required List<String> imageUrls,
    required List<String> services,
    String? description,
    String? phone,
    String? email,
    String? website,
    double? latitude,
    double? longitude,
    Map<String, dynamic>? metadata,
  }) = _Clinic;

  factory Clinic.fromJson(Map<String, dynamic> json) => _$ClinicFromJson(json);
}

@freezed
class Story with _$Story {
  const factory Story({
    required String id,
    required String title,
    required String imageUrl,
    required String type, // 'before_after', 'testimonial', 'procedure'
    String? doctorId,
    String? clinicId,
    String? videoUrl,
    String? description,
    DateTime? createdAt,
    Map<String, dynamic>? metadata,
  }) = _Story;

  factory Story.fromJson(Map<String, dynamic> json) => _$StoryFromJson(json);
}

@freezed
class BeforeAfterCase with _$BeforeAfterCase {
  const factory BeforeAfterCase({
    required String id,
    required String title,
    required String beforeImageUrl,
    required String afterImageUrl,
    required String doctorId,
    required String clinicId,
    required String procedure,
    String? description,
    int? durationDays,
    DateTime? createdAt,
    Map<String, dynamic>? metadata,
  }) = _BeforeAfterCase;

  factory BeforeAfterCase.fromJson(Map<String, dynamic> json) => _$BeforeAfterCaseFromJson(json);
}