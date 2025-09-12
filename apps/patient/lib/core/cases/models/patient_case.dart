import 'package:freezed_annotation/freezed_annotation.dart';

part 'patient_case.freezed.dart';
part 'patient_case.g.dart';

enum CaseUrgency {
  @JsonValue('low')
  low,
  @JsonValue('medium')
  medium,
  @JsonValue('high')
  high,
  @JsonValue('emergency')
  emergency,
}

enum CaseStatus {
  @JsonValue('draft')
  draft,
  @JsonValue('submitted')
  submitted,
  @JsonValue('under_review')
  underReview,
  @JsonValue('consultation_scheduled')
  consultationScheduled,
  @JsonValue('treatment_plan_ready')
  treatmentPlanReady,
  @JsonValue('completed')
  completed,
  @JsonValue('cancelled')
  cancelled,
}

@freezed
class PatientCase with _$PatientCase {
  const factory PatientCase({
    required String id,
    required String patientId,
    required String description,
    required CaseUrgency urgency,
    required CaseStatus status,
    required List<String> photoUrls,
    required List<String> complaintIds,
    required DateTime createdAt,
    required DateTime updatedAt,
    String? medicalHistory,
    String? currentMedications,
    String? allergies,
    String? doctorId,
    String? treatmentPlanId,
    DateTime? consultationDate,
    Map<String, dynamic>? metadata,
  }) = _PatientCase;

  factory PatientCase.fromJson(Map<String, dynamic> json) => _$PatientCaseFromJson(json);
}

@freezed
class CasePhoto with _$CasePhoto {
  const factory CasePhoto({
    required String id,
    required String caseId,
    required String url,
    required String filename,
    required DateTime uploadedAt,
    String? description,
    Map<String, dynamic>? metadata,
  }) = _CasePhoto;

  factory CasePhoto.fromJson(Map<String, dynamic> json) => _$CasePhotoFromJson(json);
}