import 'package:freezed_annotation/freezed_annotation.dart';

part 'review.freezed.dart';
part 'review.g.dart';

@freezed
class Review with _$Review {
  const factory Review({
    required String id,
    required String patientName,
    required String patientImageUrl,
    required double rating,
    required String comment,
    required DateTime createdAt,
    String? treatmentType,
    bool? isVerified,
  }) = _Review;

  factory Review.fromJson(Map<String, dynamic> json) => _$ReviewFromJson(json);
}

@freezed
class Service with _$Service {
  const factory Service({
    required String id,
    required String name,
    required String description,
    required String price,
    required String duration,
    String? imageUrl,
    bool? isPopular,
  }) = _Service;

  factory Service.fromJson(Map<String, dynamic> json) => _$ServiceFromJson(json);
}

@freezed
class Availability with _$Availability {
  const factory Availability({
    required String id,
    required DateTime dateTime,
    required String type, // 'text', 'video', 'in-person'
    required bool isAvailable,
    String? price,
  }) = _Availability;

  factory Availability.fromJson(Map<String, dynamic> json) => _$AvailabilityFromJson(json);
}