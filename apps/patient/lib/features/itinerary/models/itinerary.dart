import 'package:freezed_annotation/freezed_annotation.dart';

part 'itinerary.freezed.dart';
part 'itinerary.g.dart';

@freezed
class Itinerary with _$Itinerary {
  const factory Itinerary({
    required String id,
    required String patientId,
    required String treatmentPlanId,
    required List<ItineraryItem> items,
    required DateTime startDate,
    required DateTime endDate,
    @Default(ItineraryStatus.draft) ItineraryStatus status,
    DateTime? createdAt,
    DateTime? updatedAt,
  }) = _Itinerary;

  factory Itinerary.fromJson(Map<String, dynamic> json) => _$ItineraryFromJson(json);
}

@freezed
class ItineraryItem with _$ItineraryItem {
  const factory ItineraryItem({
    required String id,
    required ItineraryItemType type,
    required String title,
    required String description,
    required DateTime dateTime,
    String? location,
    String? address,
    Map<String, dynamic>? details,
    @Default(ItineraryItemStatus.scheduled) ItineraryItemStatus status,
    @Default(1) int order,
  }) = _ItineraryItem;

  factory ItineraryItem.fromJson(Map<String, dynamic> json) => _$ItineraryItemFromJson(json);
}

enum ItineraryStatus {
  @JsonValue('draft')
  draft,
  @JsonValue('confirmed')
  confirmed,
  @JsonValue('in_progress')
  inProgress,
  @JsonValue('completed')
  completed,
  @JsonValue('cancelled')
  cancelled,
}

enum ItineraryItemType {
  @JsonValue('flight')
  flight,
  @JsonValue('hotel')
  hotel,
  @JsonValue('transport')
  transport,
  @JsonValue('clinic_appointment')
  clinicAppointment,
  @JsonValue('consultation')
  consultation,
  @JsonValue('treatment')
  treatment,
  @JsonValue('follow_up')
  followUp,
  @JsonValue('free_time')
  freeTime,
}

enum ItineraryItemStatus {
  @JsonValue('scheduled')
  scheduled,
  @JsonValue('confirmed')
  confirmed,
  @JsonValue('in_progress')
  inProgress,
  @JsonValue('completed')
  completed,
  @JsonValue('cancelled')
  cancelled,
  @JsonValue('rescheduled')
  rescheduled,
}