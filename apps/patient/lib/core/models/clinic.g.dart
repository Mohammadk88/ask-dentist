// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'clinic.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

_$ClinicImpl _$$ClinicImplFromJson(Map<String, dynamic> json) => _$ClinicImpl(
      id: json['id'] as String,
      name: json['name'] as String,
      address: json['address'] as String,
      city: json['city'] as String,
      country: json['country'] as String,
      rating: (json['rating'] as num).toDouble(),
      reviewCount: (json['reviewCount'] as num).toInt(),
      imageUrls:
          (json['imageUrls'] as List<dynamic>).map((e) => e as String).toList(),
      services:
          (json['services'] as List<dynamic>).map((e) => e as String).toList(),
      description: json['description'] as String?,
      phone: json['phone'] as String?,
      email: json['email'] as String?,
      website: json['website'] as String?,
      latitude: (json['latitude'] as num?)?.toDouble(),
      longitude: (json['longitude'] as num?)?.toDouble(),
      metadata: json['metadata'] as Map<String, dynamic>?,
    );

Map<String, dynamic> _$$ClinicImplToJson(_$ClinicImpl instance) =>
    <String, dynamic>{
      'id': instance.id,
      'name': instance.name,
      'address': instance.address,
      'city': instance.city,
      'country': instance.country,
      'rating': instance.rating,
      'reviewCount': instance.reviewCount,
      'imageUrls': instance.imageUrls,
      'services': instance.services,
      'description': instance.description,
      'phone': instance.phone,
      'email': instance.email,
      'website': instance.website,
      'latitude': instance.latitude,
      'longitude': instance.longitude,
      'metadata': instance.metadata,
    };

_$StoryImpl _$$StoryImplFromJson(Map<String, dynamic> json) => _$StoryImpl(
      id: json['id'] as String,
      title: json['title'] as String,
      imageUrl: json['imageUrl'] as String,
      type: json['type'] as String,
      doctorId: json['doctorId'] as String?,
      clinicId: json['clinicId'] as String?,
      videoUrl: json['videoUrl'] as String?,
      description: json['description'] as String?,
      createdAt: json['createdAt'] == null
          ? null
          : DateTime.parse(json['createdAt'] as String),
      metadata: json['metadata'] as Map<String, dynamic>?,
    );

Map<String, dynamic> _$$StoryImplToJson(_$StoryImpl instance) =>
    <String, dynamic>{
      'id': instance.id,
      'title': instance.title,
      'imageUrl': instance.imageUrl,
      'type': instance.type,
      'doctorId': instance.doctorId,
      'clinicId': instance.clinicId,
      'videoUrl': instance.videoUrl,
      'description': instance.description,
      'createdAt': instance.createdAt?.toIso8601String(),
      'metadata': instance.metadata,
    };

_$BeforeAfterCaseImpl _$$BeforeAfterCaseImplFromJson(
        Map<String, dynamic> json) =>
    _$BeforeAfterCaseImpl(
      id: json['id'] as String,
      title: json['title'] as String,
      beforeImageUrl: json['beforeImageUrl'] as String,
      afterImageUrl: json['afterImageUrl'] as String,
      doctorId: json['doctorId'] as String,
      clinicId: json['clinicId'] as String,
      procedure: json['procedure'] as String,
      description: json['description'] as String?,
      durationDays: (json['durationDays'] as num?)?.toInt(),
      createdAt: json['createdAt'] == null
          ? null
          : DateTime.parse(json['createdAt'] as String),
      metadata: json['metadata'] as Map<String, dynamic>?,
    );

Map<String, dynamic> _$$BeforeAfterCaseImplToJson(
        _$BeforeAfterCaseImpl instance) =>
    <String, dynamic>{
      'id': instance.id,
      'title': instance.title,
      'beforeImageUrl': instance.beforeImageUrl,
      'afterImageUrl': instance.afterImageUrl,
      'doctorId': instance.doctorId,
      'clinicId': instance.clinicId,
      'procedure': instance.procedure,
      'description': instance.description,
      'durationDays': instance.durationDays,
      'createdAt': instance.createdAt?.toIso8601String(),
      'metadata': instance.metadata,
    };
