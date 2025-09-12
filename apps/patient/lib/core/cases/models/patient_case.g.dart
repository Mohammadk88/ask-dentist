// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'patient_case.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

_$PatientCaseImpl _$$PatientCaseImplFromJson(Map<String, dynamic> json) =>
    _$PatientCaseImpl(
      id: json['id'] as String,
      patientId: json['patientId'] as String,
      description: json['description'] as String,
      urgency: $enumDecode(_$CaseUrgencyEnumMap, json['urgency']),
      status: $enumDecode(_$CaseStatusEnumMap, json['status']),
      photoUrls:
          (json['photoUrls'] as List<dynamic>).map((e) => e as String).toList(),
      complaintIds: (json['complaintIds'] as List<dynamic>)
          .map((e) => e as String)
          .toList(),
      createdAt: DateTime.parse(json['createdAt'] as String),
      updatedAt: DateTime.parse(json['updatedAt'] as String),
      medicalHistory: json['medicalHistory'] as String?,
      currentMedications: json['currentMedications'] as String?,
      allergies: json['allergies'] as String?,
      doctorId: json['doctorId'] as String?,
      treatmentPlanId: json['treatmentPlanId'] as String?,
      consultationDate: json['consultationDate'] == null
          ? null
          : DateTime.parse(json['consultationDate'] as String),
      metadata: json['metadata'] as Map<String, dynamic>?,
    );

Map<String, dynamic> _$$PatientCaseImplToJson(_$PatientCaseImpl instance) =>
    <String, dynamic>{
      'id': instance.id,
      'patientId': instance.patientId,
      'description': instance.description,
      'urgency': _$CaseUrgencyEnumMap[instance.urgency]!,
      'status': _$CaseStatusEnumMap[instance.status]!,
      'photoUrls': instance.photoUrls,
      'complaintIds': instance.complaintIds,
      'createdAt': instance.createdAt.toIso8601String(),
      'updatedAt': instance.updatedAt.toIso8601String(),
      'medicalHistory': instance.medicalHistory,
      'currentMedications': instance.currentMedications,
      'allergies': instance.allergies,
      'doctorId': instance.doctorId,
      'treatmentPlanId': instance.treatmentPlanId,
      'consultationDate': instance.consultationDate?.toIso8601String(),
      'metadata': instance.metadata,
    };

const _$CaseUrgencyEnumMap = {
  CaseUrgency.low: 'low',
  CaseUrgency.medium: 'medium',
  CaseUrgency.high: 'high',
  CaseUrgency.emergency: 'emergency',
};

const _$CaseStatusEnumMap = {
  CaseStatus.draft: 'draft',
  CaseStatus.submitted: 'submitted',
  CaseStatus.underReview: 'under_review',
  CaseStatus.consultationScheduled: 'consultation_scheduled',
  CaseStatus.treatmentPlanReady: 'treatment_plan_ready',
  CaseStatus.completed: 'completed',
  CaseStatus.cancelled: 'cancelled',
};

_$CasePhotoImpl _$$CasePhotoImplFromJson(Map<String, dynamic> json) =>
    _$CasePhotoImpl(
      id: json['id'] as String,
      caseId: json['caseId'] as String,
      url: json['url'] as String,
      filename: json['filename'] as String,
      uploadedAt: DateTime.parse(json['uploadedAt'] as String),
      description: json['description'] as String?,
      metadata: json['metadata'] as Map<String, dynamic>?,
    );

Map<String, dynamic> _$$CasePhotoImplToJson(_$CasePhotoImpl instance) =>
    <String, dynamic>{
      'id': instance.id,
      'caseId': instance.caseId,
      'url': instance.url,
      'filename': instance.filename,
      'uploadedAt': instance.uploadedAt.toIso8601String(),
      'description': instance.description,
      'metadata': instance.metadata,
    };
