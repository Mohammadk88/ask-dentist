// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'treatment_request.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

_$TreatmentRequestImpl _$$TreatmentRequestImplFromJson(
        Map<String, dynamic> json) =>
    _$TreatmentRequestImpl(
      id: json['id'] as String,
      patientId: json['patientId'] as String,
      patientName: json['patientName'] as String,
      patientPhoto: json['patientPhoto'] as String,
      treatmentType: json['treatmentType'] as String,
      description: json['description'] as String,
      status: $enumDecode(_$TreatmentStatusEnumMap, json['status']),
      attachments: (json['attachments'] as List<dynamic>)
          .map((e) => e as String)
          .toList(),
      submittedAt: DateTime.parse(json['submittedAt'] as String),
      responseDeadline: json['responseDeadline'] == null
          ? null
          : DateTime.parse(json['responseDeadline'] as String),
      urgencyLevel: json['urgencyLevel'] as String?,
      location: json['location'] as String?,
      medicalHistory: json['medicalHistory'] as Map<String, dynamic>?,
    );

Map<String, dynamic> _$$TreatmentRequestImplToJson(
        _$TreatmentRequestImpl instance) =>
    <String, dynamic>{
      'id': instance.id,
      'patientId': instance.patientId,
      'patientName': instance.patientName,
      'patientPhoto': instance.patientPhoto,
      'treatmentType': instance.treatmentType,
      'description': instance.description,
      'status': _$TreatmentStatusEnumMap[instance.status]!,
      'attachments': instance.attachments,
      'submittedAt': instance.submittedAt.toIso8601String(),
      'responseDeadline': instance.responseDeadline?.toIso8601String(),
      'urgencyLevel': instance.urgencyLevel,
      'location': instance.location,
      'medicalHistory': instance.medicalHistory,
    };

const _$TreatmentStatusEnumMap = {
  TreatmentStatus.newRequest: 'new',
  TreatmentStatus.pending: 'pending',
  TreatmentStatus.inProgress: 'in_progress',
  TreatmentStatus.completed: 'completed',
  TreatmentStatus.rejected: 'rejected',
};

_$PatientImpl _$$PatientImplFromJson(Map<String, dynamic> json) =>
    _$PatientImpl(
      id: json['id'] as String,
      name: json['name'] as String,
      email: json['email'] as String,
      photo: json['photo'] as String,
      phone: json['phone'] as String?,
      dateOfBirth: json['dateOfBirth'] as String?,
      location: json['location'] as String?,
      medicalHistory: json['medicalHistory'] as Map<String, dynamic>?,
    );

Map<String, dynamic> _$$PatientImplToJson(_$PatientImpl instance) =>
    <String, dynamic>{
      'id': instance.id,
      'name': instance.name,
      'email': instance.email,
      'photo': instance.photo,
      'phone': instance.phone,
      'dateOfBirth': instance.dateOfBirth,
      'location': instance.location,
      'medicalHistory': instance.medicalHistory,
    };

_$AppointmentImpl _$$AppointmentImplFromJson(Map<String, dynamic> json) =>
    _$AppointmentImpl(
      id: json['id'] as String,
      patientId: json['patientId'] as String,
      patientName: json['patientName'] as String,
      treatmentType: json['treatmentType'] as String,
      scheduledAt: DateTime.parse(json['scheduledAt'] as String),
      status: $enumDecode(_$AppointmentStatusEnumMap, json['status']),
      notes: json['notes'] as String?,
      location: json['location'] as String?,
      durationMinutes: (json['durationMinutes'] as num?)?.toInt(),
    );

Map<String, dynamic> _$$AppointmentImplToJson(_$AppointmentImpl instance) =>
    <String, dynamic>{
      'id': instance.id,
      'patientId': instance.patientId,
      'patientName': instance.patientName,
      'treatmentType': instance.treatmentType,
      'scheduledAt': instance.scheduledAt.toIso8601String(),
      'status': _$AppointmentStatusEnumMap[instance.status]!,
      'notes': instance.notes,
      'location': instance.location,
      'durationMinutes': instance.durationMinutes,
    };

const _$AppointmentStatusEnumMap = {
  AppointmentStatus.scheduled: 'scheduled',
  AppointmentStatus.confirmed: 'confirmed',
  AppointmentStatus.completed: 'completed',
  AppointmentStatus.cancelled: 'cancelled',
};
