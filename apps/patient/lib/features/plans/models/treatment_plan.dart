import 'package:freezed_annotation/freezed_annotation.dart';

part 'treatment_plan.freezed.dart';
part 'treatment_plan.g.dart';

@freezed
class TreatmentPlan with _$TreatmentPlan {
  const factory TreatmentPlan({
    required String id,
    required String caseId,
    required String doctorId,
    @JsonKey(includeFromJson: false, includeToJson: false) Doctor? doctor,
    required String title,
    required String description,
    required List<TreatmentStage> stages,
    required double totalPrice,
    required String currency,
    required int estimatedDays,
    @Default(TreatmentPlanStatus.pending) TreatmentPlanStatus status,
    DateTime? submittedAt,
    DateTime? respondedAt,
    DateTime? expiresAt,
    String? patientResponse,
    DateTime? createdAt,
    DateTime? updatedAt,
  }) = _TreatmentPlan;

  factory TreatmentPlan.fromJson(Map<String, dynamic> json) => _$TreatmentPlanFromJson(json);
}

@freezed
class TreatmentStage with _$TreatmentStage {
  const factory TreatmentStage({
    required String id,
    required String name,
    required String description,
    required List<TreatmentItem> items,
    required double stagePrice,
    required int estimatedDays,
    @Default(1) int order,
  }) = _TreatmentStage;

  factory TreatmentStage.fromJson(Map<String, dynamic> json) => _$TreatmentStageFromJson(json);
}

@freezed
class TreatmentItem with _$TreatmentItem {
  const factory TreatmentItem({
    required String id,
    required String serviceId,
    @JsonKey(includeFromJson: false, includeToJson: false) Service? service,
    required int quantity,
    required double unitPrice,
    required double totalPrice,
    List<String>? toothNumbers,
    String? notes,
  }) = _TreatmentItem;

  factory TreatmentItem.fromJson(Map<String, dynamic> json) => _$TreatmentItemFromJson(json);
}

@freezed
class PlanResponse with _$PlanResponse {
  const factory PlanResponse({
    required String planId,
    required PlanResponseType responseType,
    String? message,
    DateTime? respondedAt,
  }) = _PlanResponse;

  factory PlanResponse.fromJson(Map<String, dynamic> json) => _$PlanResponseFromJson(json);
}

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
    DateTime? createdAt,
    DateTime? updatedAt,
  }) = _Doctor;

  factory Doctor.fromJson(Map<String, dynamic> json) => _$DoctorFromJson(json);
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

enum TreatmentPlanStatus {
  @JsonValue('pending')
  pending,
  @JsonValue('accepted')
  accepted,
  @JsonValue('rejected')
  rejected,
  @JsonValue('expired')
  expired,
  @JsonValue('cancelled')
  cancelled,
}

enum PlanResponseType {
  @JsonValue('accept')
  accept,
  @JsonValue('reject')
  reject,
}