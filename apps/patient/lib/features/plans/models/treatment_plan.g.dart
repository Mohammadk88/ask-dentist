// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'treatment_plan.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

_$TreatmentPlanImpl _$$TreatmentPlanImplFromJson(Map<String, dynamic> json) =>
    _$TreatmentPlanImpl(
      id: json['id'] as String,
      caseId: json['caseId'] as String,
      doctorId: json['doctorId'] as String,
      title: json['title'] as String,
      description: json['description'] as String,
      stages: (json['stages'] as List<dynamic>)
          .map((e) => TreatmentStage.fromJson(e as Map<String, dynamic>))
          .toList(),
      totalPrice: (json['totalPrice'] as num).toDouble(),
      currency: json['currency'] as String,
      estimatedDays: (json['estimatedDays'] as num).toInt(),
      status:
          $enumDecodeNullable(_$TreatmentPlanStatusEnumMap, json['status']) ??
              TreatmentPlanStatus.pending,
      submittedAt: json['submittedAt'] == null
          ? null
          : DateTime.parse(json['submittedAt'] as String),
      respondedAt: json['respondedAt'] == null
          ? null
          : DateTime.parse(json['respondedAt'] as String),
      expiresAt: json['expiresAt'] == null
          ? null
          : DateTime.parse(json['expiresAt'] as String),
      patientResponse: json['patientResponse'] as String?,
      createdAt: json['createdAt'] == null
          ? null
          : DateTime.parse(json['createdAt'] as String),
      updatedAt: json['updatedAt'] == null
          ? null
          : DateTime.parse(json['updatedAt'] as String),
    );

Map<String, dynamic> _$$TreatmentPlanImplToJson(_$TreatmentPlanImpl instance) =>
    <String, dynamic>{
      'id': instance.id,
      'caseId': instance.caseId,
      'doctorId': instance.doctorId,
      'title': instance.title,
      'description': instance.description,
      'stages': instance.stages,
      'totalPrice': instance.totalPrice,
      'currency': instance.currency,
      'estimatedDays': instance.estimatedDays,
      'status': _$TreatmentPlanStatusEnumMap[instance.status]!,
      'submittedAt': instance.submittedAt?.toIso8601String(),
      'respondedAt': instance.respondedAt?.toIso8601String(),
      'expiresAt': instance.expiresAt?.toIso8601String(),
      'patientResponse': instance.patientResponse,
      'createdAt': instance.createdAt?.toIso8601String(),
      'updatedAt': instance.updatedAt?.toIso8601String(),
    };

const _$TreatmentPlanStatusEnumMap = {
  TreatmentPlanStatus.pending: 'pending',
  TreatmentPlanStatus.accepted: 'accepted',
  TreatmentPlanStatus.rejected: 'rejected',
  TreatmentPlanStatus.expired: 'expired',
  TreatmentPlanStatus.cancelled: 'cancelled',
};

_$TreatmentStageImpl _$$TreatmentStageImplFromJson(Map<String, dynamic> json) =>
    _$TreatmentStageImpl(
      id: json['id'] as String,
      name: json['name'] as String,
      description: json['description'] as String,
      items: (json['items'] as List<dynamic>)
          .map((e) => TreatmentItem.fromJson(e as Map<String, dynamic>))
          .toList(),
      stagePrice: (json['stagePrice'] as num).toDouble(),
      estimatedDays: (json['estimatedDays'] as num).toInt(),
      order: (json['order'] as num?)?.toInt() ?? 1,
    );

Map<String, dynamic> _$$TreatmentStageImplToJson(
        _$TreatmentStageImpl instance) =>
    <String, dynamic>{
      'id': instance.id,
      'name': instance.name,
      'description': instance.description,
      'items': instance.items,
      'stagePrice': instance.stagePrice,
      'estimatedDays': instance.estimatedDays,
      'order': instance.order,
    };

_$TreatmentItemImpl _$$TreatmentItemImplFromJson(Map<String, dynamic> json) =>
    _$TreatmentItemImpl(
      id: json['id'] as String,
      serviceId: json['serviceId'] as String,
      quantity: (json['quantity'] as num).toInt(),
      unitPrice: (json['unitPrice'] as num).toDouble(),
      totalPrice: (json['totalPrice'] as num).toDouble(),
      toothNumbers: (json['toothNumbers'] as List<dynamic>?)
          ?.map((e) => e as String)
          .toList(),
      notes: json['notes'] as String?,
    );

Map<String, dynamic> _$$TreatmentItemImplToJson(_$TreatmentItemImpl instance) =>
    <String, dynamic>{
      'id': instance.id,
      'serviceId': instance.serviceId,
      'quantity': instance.quantity,
      'unitPrice': instance.unitPrice,
      'totalPrice': instance.totalPrice,
      'toothNumbers': instance.toothNumbers,
      'notes': instance.notes,
    };

_$PlanResponseImpl _$$PlanResponseImplFromJson(Map<String, dynamic> json) =>
    _$PlanResponseImpl(
      planId: json['planId'] as String,
      responseType:
          $enumDecode(_$PlanResponseTypeEnumMap, json['responseType']),
      message: json['message'] as String?,
      respondedAt: json['respondedAt'] == null
          ? null
          : DateTime.parse(json['respondedAt'] as String),
    );

Map<String, dynamic> _$$PlanResponseImplToJson(_$PlanResponseImpl instance) =>
    <String, dynamic>{
      'planId': instance.planId,
      'responseType': _$PlanResponseTypeEnumMap[instance.responseType]!,
      'message': instance.message,
      'respondedAt': instance.respondedAt?.toIso8601String(),
    };

const _$PlanResponseTypeEnumMap = {
  PlanResponseType.accept: 'accept',
  PlanResponseType.reject: 'reject',
};

_$DoctorImpl _$$DoctorImplFromJson(Map<String, dynamic> json) => _$DoctorImpl(
      id: json['id'] as String,
      firstName: json['firstName'] as String,
      lastName: json['lastName'] as String,
      email: json['email'] as String,
      phoneNumber: json['phoneNumber'] as String?,
      profileImageUrl: json['profileImageUrl'] as String?,
      bio: json['bio'] as String?,
      specialization: json['specialization'] as String?,
      experienceYears: (json['experienceYears'] as num?)?.toInt(),
      languages: (json['languages'] as List<dynamic>?)
          ?.map((e) => e as String)
          .toList(),
      certifications: (json['certifications'] as List<dynamic>?)
          ?.map((e) => e as String)
          .toList(),
      rating: (json['rating'] as num?)?.toDouble() ?? 0.0,
      reviewsCount: (json['reviewsCount'] as num?)?.toInt() ?? 0,
      isVerified: json['isVerified'] as bool? ?? false,
      isOnline: json['isOnline'] as bool? ?? false,
      createdAt: json['createdAt'] == null
          ? null
          : DateTime.parse(json['createdAt'] as String),
      updatedAt: json['updatedAt'] == null
          ? null
          : DateTime.parse(json['updatedAt'] as String),
    );

Map<String, dynamic> _$$DoctorImplToJson(_$DoctorImpl instance) =>
    <String, dynamic>{
      'id': instance.id,
      'firstName': instance.firstName,
      'lastName': instance.lastName,
      'email': instance.email,
      'phoneNumber': instance.phoneNumber,
      'profileImageUrl': instance.profileImageUrl,
      'bio': instance.bio,
      'specialization': instance.specialization,
      'experienceYears': instance.experienceYears,
      'languages': instance.languages,
      'certifications': instance.certifications,
      'rating': instance.rating,
      'reviewsCount': instance.reviewsCount,
      'isVerified': instance.isVerified,
      'isOnline': instance.isOnline,
      'createdAt': instance.createdAt?.toIso8601String(),
      'updatedAt': instance.updatedAt?.toIso8601String(),
    };

_$ServiceImpl _$$ServiceImplFromJson(Map<String, dynamic> json) =>
    _$ServiceImpl(
      id: json['id'] as String,
      name: json['name'] as String,
      description: json['description'] as String,
      category: json['category'] as String,
      durationMinutes: (json['durationMinutes'] as num?)?.toInt(),
      price: (json['price'] as num?)?.toDouble(),
      currency: json['currency'] as String?,
      isToothSpecific: json['isToothSpecific'] as bool? ?? false,
      createdAt: json['createdAt'] == null
          ? null
          : DateTime.parse(json['createdAt'] as String),
      updatedAt: json['updatedAt'] == null
          ? null
          : DateTime.parse(json['updatedAt'] as String),
    );

Map<String, dynamic> _$$ServiceImplToJson(_$ServiceImpl instance) =>
    <String, dynamic>{
      'id': instance.id,
      'name': instance.name,
      'description': instance.description,
      'category': instance.category,
      'durationMinutes': instance.durationMinutes,
      'price': instance.price,
      'currency': instance.currency,
      'isToothSpecific': instance.isToothSpecific,
      'createdAt': instance.createdAt?.toIso8601String(),
      'updatedAt': instance.updatedAt?.toIso8601String(),
    };
