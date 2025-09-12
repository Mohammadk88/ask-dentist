// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'home_api_dto.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

_$HomeResponseDtoImpl _$$HomeResponseDtoImplFromJson(
        Map<String, dynamic> json) =>
    _$HomeResponseDtoImpl(
      success: json['success'] as bool,
      message: json['message'] as String,
      data: HomeDataDto.fromJson(json['data'] as Map<String, dynamic>),
      actionFlags: json['action_flags'] == null
          ? null
          : ActionFlagsDto.fromJson(
              json['action_flags'] as Map<String, dynamic>),
      locale: json['locale'] as String,
      timestamp: json['timestamp'] as String,
      isGuest: json['is_guest'] as bool,
      userAuthenticated: json['user_authenticated'] as bool,
    );

Map<String, dynamic> _$$HomeResponseDtoImplToJson(
        _$HomeResponseDtoImpl instance) =>
    <String, dynamic>{
      'success': instance.success,
      'message': instance.message,
      'data': instance.data,
      'action_flags': instance.actionFlags,
      'locale': instance.locale,
      'timestamp': instance.timestamp,
      'is_guest': instance.isGuest,
      'user_authenticated': instance.userAuthenticated,
    };

_$HomeDataDtoImpl _$$HomeDataDtoImplFromJson(Map<String, dynamic> json) =>
    _$HomeDataDtoImpl(
      stories: (json['stories'] as List<dynamic>?)
              ?.map((e) => StoryDto.fromJson(e as Map<String, dynamic>))
              .toList() ??
          const [],
      topClinics: (json['top_clinics'] as List<dynamic>?)
              ?.map((e) => ClinicDto.fromJson(e as Map<String, dynamic>))
              .toList() ??
          const [],
      topDoctors: (json['top_doctors'] as List<dynamic>?)
              ?.map((e) => DoctorDto.fromJson(e as Map<String, dynamic>))
              .toList() ??
          const [],
      beforeAfter: (json['before_after'] as List<dynamic>?)
              ?.map((e) => BeforeAfterDto.fromJson(e as Map<String, dynamic>))
              .toList() ??
          const [],
    );

Map<String, dynamic> _$$HomeDataDtoImplToJson(_$HomeDataDtoImpl instance) =>
    <String, dynamic>{
      'stories': instance.stories,
      'top_clinics': instance.topClinics,
      'top_doctors': instance.topDoctors,
      'before_after': instance.beforeAfter,
    };

_$ActionFlagsDtoImpl _$$ActionFlagsDtoImplFromJson(Map<String, dynamic> json) =>
    _$ActionFlagsDtoImpl(
      canFavorite: json['can_favorite'] as bool? ?? false,
      canBook: json['can_book'] as bool? ?? false,
      canMessage: json['can_message'] as bool? ?? false,
    );

Map<String, dynamic> _$$ActionFlagsDtoImplToJson(
        _$ActionFlagsDtoImpl instance) =>
    <String, dynamic>{
      'can_favorite': instance.canFavorite,
      'can_book': instance.canBook,
      'can_message': instance.canMessage,
    };

_$StoryDtoImpl _$$StoryDtoImplFromJson(Map<String, dynamic> json) =>
    _$StoryDtoImpl(
      id: (json['id'] as num).toInt(),
      title: json['title'] as String,
      content: json['content'] as String,
      image: json['image'] as String,
      createdAt: json['created_at'] as String,
      likesCount: (json['likes_count'] as num).toInt(),
    );

Map<String, dynamic> _$$StoryDtoImplToJson(_$StoryDtoImpl instance) =>
    <String, dynamic>{
      'id': instance.id,
      'title': instance.title,
      'content': instance.content,
      'image': instance.image,
      'created_at': instance.createdAt,
      'likes_count': instance.likesCount,
    };

_$ClinicDtoImpl _$$ClinicDtoImplFromJson(Map<String, dynamic> json) =>
    _$ClinicDtoImpl(
      id: (json['id'] as num).toInt(),
      name: json['name'] as String,
      location: json['location'] as String,
      rating: (json['rating'] as num).toDouble(),
      reviewsCount: (json['reviews_count'] as num).toInt(),
      image: json['image'] as String,
      specialties: (json['specialties'] as List<dynamic>)
          .map((e) => e as String)
          .toList(),
    );

Map<String, dynamic> _$$ClinicDtoImplToJson(_$ClinicDtoImpl instance) =>
    <String, dynamic>{
      'id': instance.id,
      'name': instance.name,
      'location': instance.location,
      'rating': instance.rating,
      'reviews_count': instance.reviewsCount,
      'image': instance.image,
      'specialties': instance.specialties,
    };

_$DoctorDtoImpl _$$DoctorDtoImplFromJson(Map<String, dynamic> json) =>
    _$DoctorDtoImpl(
      id: (json['id'] as num).toInt(),
      name: json['name'] as String,
      specialty: json['specialty'] as String,
      rating: (json['rating'] as num).toDouble(),
      reviewsCount: (json['reviews_count'] as num).toInt(),
      image: json['image'] as String,
      yearsExperience: (json['years_experience'] as num).toInt(),
      clinicName: json['clinic_name'] as String,
    );

Map<String, dynamic> _$$DoctorDtoImplToJson(_$DoctorDtoImpl instance) =>
    <String, dynamic>{
      'id': instance.id,
      'name': instance.name,
      'specialty': instance.specialty,
      'rating': instance.rating,
      'reviews_count': instance.reviewsCount,
      'image': instance.image,
      'years_experience': instance.yearsExperience,
      'clinic_name': instance.clinicName,
    };

_$BeforeAfterDtoImpl _$$BeforeAfterDtoImplFromJson(Map<String, dynamic> json) =>
    _$BeforeAfterDtoImpl(
      id: (json['id'] as num).toInt(),
      title: json['title'] as String,
      beforeImage: json['before_image'] as String,
      afterImage: json['after_image'] as String,
      doctorName: json['doctor_name'] as String,
      treatmentDuration: json['treatment_duration'] as String,
      description: json['description'] as String,
    );

Map<String, dynamic> _$$BeforeAfterDtoImplToJson(
        _$BeforeAfterDtoImpl instance) =>
    <String, dynamic>{
      'id': instance.id,
      'title': instance.title,
      'before_image': instance.beforeImage,
      'after_image': instance.afterImage,
      'doctor_name': instance.doctorName,
      'treatment_duration': instance.treatmentDuration,
      'description': instance.description,
    };
