import 'package:freezed_annotation/freezed_annotation.dart';

part 'treatment_request.freezed.dart';
part 'treatment_request.g.dart';

@freezed
class TreatmentRequest with _$TreatmentRequest {
  const factory TreatmentRequest({
    required String id,
    required String patientId,
    required String patientName,
    required String patientPhoto,
    required String treatmentType,
    required String description,
    required TreatmentStatus status,
    required List<String> attachments,
    required DateTime submittedAt,
    DateTime? responseDeadline,
    String? urgencyLevel,
    String? location,
    Map<String, dynamic>? medicalHistory,
  }) = _TreatmentRequest;

  factory TreatmentRequest.fromJson(Map<String, dynamic> json) =>
      _$TreatmentRequestFromJson(json);
}

@freezed
class Patient with _$Patient {
  const factory Patient({
    required String id,
    required String name,
    required String email,
    required String photo,
    String? phone,
    String? dateOfBirth,
    String? location,
    Map<String, dynamic>? medicalHistory,
  }) = _Patient;

  factory Patient.fromJson(Map<String, dynamic> json) =>
      _$PatientFromJson(json);
}

@freezed
class Appointment with _$Appointment {
  const factory Appointment({
    required String id,
    required String patientId,
    required String patientName,
    required String treatmentType,
    required DateTime scheduledAt,
    required AppointmentStatus status,
    String? notes,
    String? location,
    int? durationMinutes,
  }) = _Appointment;

  factory Appointment.fromJson(Map<String, dynamic> json) =>
      _$AppointmentFromJson(json);
}

enum TreatmentStatus {
  @JsonValue('new')
  newRequest,
  @JsonValue('pending')
  pending,
  @JsonValue('in_progress')
  inProgress,
  @JsonValue('completed')
  completed,
  @JsonValue('rejected')
  rejected,
}

enum AppointmentStatus {
  @JsonValue('scheduled')
  scheduled,
  @JsonValue('confirmed')
  confirmed,
  @JsonValue('completed')
  completed,
  @JsonValue('cancelled')
  cancelled,
}