// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'doctor.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

_$DoctorImpl _$$DoctorImplFromJson(Map<String, dynamic> json) => _$DoctorImpl(
      id: json['id'] as String,
      firstName: json['firstName'] as String,
      lastName: json['lastName'] as String,
      profileImageUrl: json['profileImageUrl'] as String,
      clinicId: json['clinicId'] as String,
      specialty: $enumDecode(_$DoctorSpecialtyEnumMap, json['specialty']),
      rating: (json['rating'] as num).toDouble(),
      reviewCount: (json['reviewCount'] as num).toInt(),
      experienceYears: (json['experienceYears'] as num).toInt(),
      languages:
          (json['languages'] as List<dynamic>).map((e) => e as String).toList(),
      services:
          (json['services'] as List<dynamic>).map((e) => e as String).toList(),
      bio: json['bio'] as String?,
      education: json['education'] as String?,
      phone: json['phone'] as String?,
      email: json['email'] as String?,
      isAvailable: json['isAvailable'] as bool?,
      isFavorite: json['isFavorite'] as bool?,
      nextAvailableDate: json['nextAvailableDate'] == null
          ? null
          : DateTime.parse(json['nextAvailableDate'] as String),
      metadata: json['metadata'] as Map<String, dynamic>?,
    );

Map<String, dynamic> _$$DoctorImplToJson(_$DoctorImpl instance) =>
    <String, dynamic>{
      'id': instance.id,
      'firstName': instance.firstName,
      'lastName': instance.lastName,
      'profileImageUrl': instance.profileImageUrl,
      'clinicId': instance.clinicId,
      'specialty': _$DoctorSpecialtyEnumMap[instance.specialty]!,
      'rating': instance.rating,
      'reviewCount': instance.reviewCount,
      'experienceYears': instance.experienceYears,
      'languages': instance.languages,
      'services': instance.services,
      'bio': instance.bio,
      'education': instance.education,
      'phone': instance.phone,
      'email': instance.email,
      'isAvailable': instance.isAvailable,
      'isFavorite': instance.isFavorite,
      'nextAvailableDate': instance.nextAvailableDate?.toIso8601String(),
      'metadata': instance.metadata,
    };

const _$DoctorSpecialtyEnumMap = {
  DoctorSpecialty.generalDentistry: 'general_dentistry',
  DoctorSpecialty.cosmeticDentistry: 'cosmetic_dentistry',
  DoctorSpecialty.orthodontics: 'orthodontics',
  DoctorSpecialty.oralSurgery: 'oral_surgery',
  DoctorSpecialty.endodontics: 'endodontics',
  DoctorSpecialty.periodontics: 'periodontics',
  DoctorSpecialty.prosthodontics: 'prosthodontics',
  DoctorSpecialty.pediatricDentistry: 'pediatric_dentistry',
};

_$DoctorReviewImpl _$$DoctorReviewImplFromJson(Map<String, dynamic> json) =>
    _$DoctorReviewImpl(
      id: json['id'] as String,
      doctorId: json['doctorId'] as String,
      patientName: json['patientName'] as String,
      rating: (json['rating'] as num).toDouble(),
      comment: json['comment'] as String,
      createdAt: DateTime.parse(json['createdAt'] as String),
      procedure: json['procedure'] as String?,
      imageUrls: (json['imageUrls'] as List<dynamic>?)
          ?.map((e) => e as String)
          .toList(),
      metadata: json['metadata'] as Map<String, dynamic>?,
    );

Map<String, dynamic> _$$DoctorReviewImplToJson(_$DoctorReviewImpl instance) =>
    <String, dynamic>{
      'id': instance.id,
      'doctorId': instance.doctorId,
      'patientName': instance.patientName,
      'rating': instance.rating,
      'comment': instance.comment,
      'createdAt': instance.createdAt.toIso8601String(),
      'procedure': instance.procedure,
      'imageUrls': instance.imageUrls,
      'metadata': instance.metadata,
    };
