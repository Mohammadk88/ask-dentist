import 'package:freezed_annotation/freezed_annotation.dart';

part 'doctor_profile_models.freezed.dart';
part 'doctor_profile_models.g.dart';

enum ConsultationType {
  @JsonValue('in_person')
  inPerson,
  @JsonValue('video')
  video,
  @JsonValue('phone')
  phone,
}

enum ServiceCategory {
  @JsonValue('cosmetic')
  cosmetic,
  @JsonValue('restorative')
  restorative,
  @JsonValue('preventive')
  preventive,
  @JsonValue('surgical')
  surgical,
}

@freezed
class DoctorService with _$DoctorService {
  const factory DoctorService({
    required String id,
    required String name,
    required String description,
    required double price,
    required String duration,
    required ServiceCategory category,
    String? imageUrl,
    @Default(false) bool isPopular,
  }) = _DoctorService;

  factory DoctorService.fromJson(Map<String, dynamic> json) => _$DoctorServiceFromJson(json);
}

@freezed
class TimeSlot with _$TimeSlot {
  const factory TimeSlot({
    required String time,
    required bool isAvailable,
  }) = _TimeSlot;

  factory TimeSlot.fromJson(Map<String, dynamic> json) => _$TimeSlotFromJson(json);
}

@freezed
class DoctorAvailability with _$DoctorAvailability {
  const factory DoctorAvailability({
    required DateTime date,
    required List<TimeSlot> timeSlots,
  }) = _DoctorAvailability;

  factory DoctorAvailability.fromJson(Map<String, dynamic> json) => _$DoctorAvailabilityFromJson(json);
}