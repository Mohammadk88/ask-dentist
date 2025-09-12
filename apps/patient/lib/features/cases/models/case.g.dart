// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'case.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

_$PatientCaseImpl _$$PatientCaseImplFromJson(Map<String, dynamic> json) =>
    _$PatientCaseImpl(
      id: json['id'] as String,
      patientId: json['patientId'] as String,
      type: $enumDecode(_$CaseTypeEnumEnumMap, json['type']),
      description: json['description'] as String,
      imageUrls:
          (json['imageUrls'] as List<dynamic>).map((e) => e as String).toList(),
      status: $enumDecodeNullable(_$CaseStatusEnumMap, json['status']) ??
          CaseStatus.pending,
      doctorIds: (json['doctorIds'] as List<dynamic>?)
          ?.map((e) => e as String)
          .toList(),
      createdAt: json['createdAt'] == null
          ? null
          : DateTime.parse(json['createdAt'] as String),
      updatedAt: json['updatedAt'] == null
          ? null
          : DateTime.parse(json['updatedAt'] as String),
    );

Map<String, dynamic> _$$PatientCaseImplToJson(_$PatientCaseImpl instance) =>
    <String, dynamic>{
      'id': instance.id,
      'patientId': instance.patientId,
      'type': _$CaseTypeEnumEnumMap[instance.type]!,
      'description': instance.description,
      'imageUrls': instance.imageUrls,
      'status': _$CaseStatusEnumMap[instance.status]!,
      'doctorIds': instance.doctorIds,
      'createdAt': instance.createdAt?.toIso8601String(),
      'updatedAt': instance.updatedAt?.toIso8601String(),
    };

const _$CaseTypeEnumEnumMap = {
  CaseTypeEnum.dental: 'dental',
  CaseTypeEnum.cosmetic: 'cosmetic',
};

const _$CaseStatusEnumMap = {
  CaseStatus.pending: 'pending',
  CaseStatus.inReview: 'in_review',
  CaseStatus.treatmentPlansReceived: 'treatment_plans_received',
  CaseStatus.planAccepted: 'plan_accepted',
  CaseStatus.treatmentCompleted: 'treatment_completed',
  CaseStatus.cancelled: 'cancelled',
};

_$SubmitCaseRequestImpl _$$SubmitCaseRequestImplFromJson(
        Map<String, dynamic> json) =>
    _$SubmitCaseRequestImpl(
      type: $enumDecode(_$CaseTypeEnumEnumMap, json['type']),
      description: json['description'] as String,
      imageUrls:
          (json['imageUrls'] as List<dynamic>).map((e) => e as String).toList(),
      preferredDoctorIds: (json['preferredDoctorIds'] as List<dynamic>?)
          ?.map((e) => e as String)
          .toList(),
      medicalHistory: (json['medicalHistory'] as List<dynamic>?)
          ?.map((e) => e as String)
          .toList(),
    );

Map<String, dynamic> _$$SubmitCaseRequestImplToJson(
        _$SubmitCaseRequestImpl instance) =>
    <String, dynamic>{
      'type': _$CaseTypeEnumEnumMap[instance.type]!,
      'description': instance.description,
      'imageUrls': instance.imageUrls,
      'preferredDoctorIds': instance.preferredDoctorIds,
      'medicalHistory': instance.medicalHistory,
    };
