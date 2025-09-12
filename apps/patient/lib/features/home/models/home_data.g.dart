// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'home_data.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

_$HomeDataImpl _$$HomeDataImplFromJson(Map<String, dynamic> json) =>
    _$HomeDataImpl(
      stories: (json['stories'] as List<dynamic>?)
              ?.map((e) => Story.fromJson(e as Map<String, dynamic>))
              .toList() ??
          const [],
      topClinics: (json['topClinics'] as List<dynamic>?)
              ?.map((e) => Clinic.fromJson(e as Map<String, dynamic>))
              .toList() ??
          const [],
      topDoctors: (json['topDoctors'] as List<dynamic>?)
              ?.map((e) => Doctor.fromJson(e as Map<String, dynamic>))
              .toList() ??
          const [],
      beforeAfter: (json['beforeAfter'] as List<dynamic>?)
              ?.map((e) => BeforeAfterCase.fromJson(e as Map<String, dynamic>))
              .toList() ??
          const [],
      favoritesDoctors: (json['favoritesDoctors'] as List<dynamic>?)
              ?.map((e) => Doctor.fromJson(e as Map<String, dynamic>))
              .toList() ??
          const [],
      actionFlags: json['actionFlags'] == null
          ? null
          : ActionFlags.fromJson(json['actionFlags'] as Map<String, dynamic>),
      locale: json['locale'] as String? ?? 'en',
      timestamp: json['timestamp'] as String,
      isGuest: json['isGuest'] as bool? ?? true,
      userAuthenticated: json['userAuthenticated'] as bool? ?? false,
    );

Map<String, dynamic> _$$HomeDataImplToJson(_$HomeDataImpl instance) =>
    <String, dynamic>{
      'stories': instance.stories,
      'topClinics': instance.topClinics,
      'topDoctors': instance.topDoctors,
      'beforeAfter': instance.beforeAfter,
      'favoritesDoctors': instance.favoritesDoctors,
      'actionFlags': instance.actionFlags,
      'locale': instance.locale,
      'timestamp': instance.timestamp,
      'isGuest': instance.isGuest,
      'userAuthenticated': instance.userAuthenticated,
    };

_$StoryImpl _$$StoryImplFromJson(Map<String, dynamic> json) => _$StoryImpl(
      id: json['id'] as String,
      title: json['title'] as String,
      description: json['description'] as String?,
      imageUrl: json['imageUrl'] as String?,
      ownerType: json['ownerType'] as String?,
      ownerId: json['ownerId'] as String?,
      ownerName: json['ownerName'] as String?,
      createdAt: json['createdAt'] == null
          ? null
          : DateTime.parse(json['createdAt'] as String),
      expiresAt: json['expiresAt'] == null
          ? null
          : DateTime.parse(json['expiresAt'] as String),
    );

Map<String, dynamic> _$$StoryImplToJson(_$StoryImpl instance) =>
    <String, dynamic>{
      'id': instance.id,
      'title': instance.title,
      'description': instance.description,
      'imageUrl': instance.imageUrl,
      'ownerType': instance.ownerType,
      'ownerId': instance.ownerId,
      'ownerName': instance.ownerName,
      'createdAt': instance.createdAt?.toIso8601String(),
      'expiresAt': instance.expiresAt?.toIso8601String(),
    };

_$ClinicImpl _$$ClinicImplFromJson(Map<String, dynamic> json) => _$ClinicImpl(
      id: json['id'] as String,
      name: json['name'] as String,
      description: json['description'] as String?,
      imageUrl: json['imageUrl'] as String?,
      address: json['address'] as String?,
      phone: json['phone'] as String?,
      rating: (json['rating'] as num?)?.toDouble(),
      reviewCount: (json['reviewCount'] as num?)?.toInt(),
    );

Map<String, dynamic> _$$ClinicImplToJson(_$ClinicImpl instance) =>
    <String, dynamic>{
      'id': instance.id,
      'name': instance.name,
      'description': instance.description,
      'imageUrl': instance.imageUrl,
      'address': instance.address,
      'phone': instance.phone,
      'rating': instance.rating,
      'reviewCount': instance.reviewCount,
    };

_$DoctorImpl _$$DoctorImplFromJson(Map<String, dynamic> json) => _$DoctorImpl(
      id: json['id'] as String,
      name: json['name'] as String,
      specialization: json['specialization'] as String?,
      imageUrl: json['imageUrl'] as String?,
      clinicName: json['clinicName'] as String?,
      rating: (json['rating'] as num?)?.toDouble(),
      reviewCount: (json['reviewCount'] as num?)?.toInt(),
      isFavorite: json['isFavorite'] as bool?,
    );

Map<String, dynamic> _$$DoctorImplToJson(_$DoctorImpl instance) =>
    <String, dynamic>{
      'id': instance.id,
      'name': instance.name,
      'specialization': instance.specialization,
      'imageUrl': instance.imageUrl,
      'clinicName': instance.clinicName,
      'rating': instance.rating,
      'reviewCount': instance.reviewCount,
      'isFavorite': instance.isFavorite,
    };

_$BeforeAfterCaseImpl _$$BeforeAfterCaseImplFromJson(
        Map<String, dynamic> json) =>
    _$BeforeAfterCaseImpl(
      id: json['id'] as String,
      title: json['title'] as String,
      description: json['description'] as String?,
      beforeImageUrl: json['beforeImageUrl'] as String?,
      afterImageUrl: json['afterImageUrl'] as String?,
      doctorName: json['doctorName'] as String?,
      treatment: json['treatment'] as String?,
    );

Map<String, dynamic> _$$BeforeAfterCaseImplToJson(
        _$BeforeAfterCaseImpl instance) =>
    <String, dynamic>{
      'id': instance.id,
      'title': instance.title,
      'description': instance.description,
      'beforeImageUrl': instance.beforeImageUrl,
      'afterImageUrl': instance.afterImageUrl,
      'doctorName': instance.doctorName,
      'treatment': instance.treatment,
    };

_$ActionFlagsImpl _$$ActionFlagsImplFromJson(Map<String, dynamic> json) =>
    _$ActionFlagsImpl(
      canFavorite: json['canFavorite'] as bool? ?? false,
      canBook: json['canBook'] as bool? ?? false,
      canMessage: json['canMessage'] as bool? ?? false,
    );

Map<String, dynamic> _$$ActionFlagsImplToJson(_$ActionFlagsImpl instance) =>
    <String, dynamic>{
      'canFavorite': instance.canFavorite,
      'canBook': instance.canBook,
      'canMessage': instance.canMessage,
    };
