import 'package:freezed_annotation/freezed_annotation.dart';

part 'case.freezed.dart';
part 'case.g.dart';

enum CaseTypeEnum {
  @JsonValue('dental')
  dental,
  @JsonValue('cosmetic')
  cosmetic,
}

@freezed
class PatientCase with _$PatientCase {
  const factory PatientCase({
    required String id,
    required String patientId,
    required CaseTypeEnum type,
    required String description,
    required List<String> imageUrls,
    @Default(CaseStatus.pending) CaseStatus status,
    List<String>? doctorIds,
    DateTime? createdAt,
    DateTime? updatedAt,
  }) = _PatientCase;

  factory PatientCase.fromJson(Map<String, dynamic> json) => _$PatientCaseFromJson(json);
}

@freezed
class SubmitCaseRequest with _$SubmitCaseRequest {
  const factory SubmitCaseRequest({
    required CaseTypeEnum type,
    required String description,
    required List<String> imageUrls,
    List<String>? preferredDoctorIds,
    List<String>? medicalHistory,
  }) = _SubmitCaseRequest;

  factory SubmitCaseRequest.fromJson(Map<String, dynamic> json) => _$SubmitCaseRequestFromJson(json);
}

enum CaseStatus {
  @JsonValue('pending')
  pending,
  @JsonValue('in_review')
  inReview,
  @JsonValue('treatment_plans_received')
  treatmentPlansReceived,
  @JsonValue('plan_accepted')
  planAccepted,
  @JsonValue('treatment_completed')
  treatmentCompleted,
  @JsonValue('cancelled')
  cancelled,
}