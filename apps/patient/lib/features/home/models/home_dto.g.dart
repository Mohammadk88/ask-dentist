// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'home_dto.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

_$HomeDtoImpl _$$HomeDtoImplFromJson(Map<String, dynamic> json) =>
    _$HomeDtoImpl(
      stories: (json['stories'] as List<dynamic>)
          .map((e) => StoryItem.fromJson(e as Map<String, dynamic>))
          .toList(),
      featuredClinics: (json['featuredClinics'] as List<dynamic>)
          .map((e) => ClinicItem.fromJson(e as Map<String, dynamic>))
          .toList(),
      recommendedDoctors: (json['recommendedDoctors'] as List<dynamic>)
          .map((e) => DoctorItem.fromJson(e as Map<String, dynamic>))
          .toList(),
      favoriteDoctors: (json['favoriteDoctors'] as List<dynamic>)
          .map((e) => DoctorItem.fromJson(e as Map<String, dynamic>))
          .toList(),
      beforeAfterGallery: (json['beforeAfterGallery'] as List<dynamic>)
          .map((e) => BeforeAfterCase.fromJson(e as Map<String, dynamic>))
          .toList(),
    );

Map<String, dynamic> _$$HomeDtoImplToJson(_$HomeDtoImpl instance) =>
    <String, dynamic>{
      'stories': instance.stories,
      'featuredClinics': instance.featuredClinics,
      'recommendedDoctors': instance.recommendedDoctors,
      'favoriteDoctors': instance.favoriteDoctors,
      'beforeAfterGallery': instance.beforeAfterGallery,
    };

_$StoryItemImpl _$$StoryItemImplFromJson(Map<String, dynamic> json) =>
    _$StoryItemImpl(
      id: json['id'] as String,
      ownerName: json['ownerName'] as String,
      avatarUrl: json['avatarUrl'] as String,
      content: json['content'] as String,
      contentType: $enumDecode(_$StoryContentTypeEnumMap, json['contentType']),
      caption: json['caption'] as String,
      isViewed: json['isViewed'] as bool,
      timestamp: DateTime.parse(json['timestamp'] as String),
      isPromoted: json['isPromoted'] as bool,
    );

Map<String, dynamic> _$$StoryItemImplToJson(_$StoryItemImpl instance) =>
    <String, dynamic>{
      'id': instance.id,
      'ownerName': instance.ownerName,
      'avatarUrl': instance.avatarUrl,
      'content': instance.content,
      'contentType': _$StoryContentTypeEnumMap[instance.contentType]!,
      'caption': instance.caption,
      'isViewed': instance.isViewed,
      'timestamp': instance.timestamp.toIso8601String(),
      'isPromoted': instance.isPromoted,
    };

const _$StoryContentTypeEnumMap = {
  StoryContentType.text: 'text',
  StoryContentType.image: 'image',
  StoryContentType.video: 'video',
};

_$ClinicItemImpl _$$ClinicItemImplFromJson(Map<String, dynamic> json) =>
    _$ClinicItemImpl(
      id: json['id'] as String,
      name: json['name'] as String,
      imageUrl: json['imageUrl'] as String,
      rating: (json['rating'] as num).toDouble(),
      reviewCount: (json['reviewCount'] as num).toInt(),
      isPromoted: json['isPromoted'] as bool,
      isVerified: json['isVerified'] as bool,
      location: json['location'] as String,
    );

Map<String, dynamic> _$$ClinicItemImplToJson(_$ClinicItemImpl instance) =>
    <String, dynamic>{
      'id': instance.id,
      'name': instance.name,
      'imageUrl': instance.imageUrl,
      'rating': instance.rating,
      'reviewCount': instance.reviewCount,
      'isPromoted': instance.isPromoted,
      'isVerified': instance.isVerified,
      'location': instance.location,
    };

_$DoctorItemImpl _$$DoctorItemImplFromJson(Map<String, dynamic> json) =>
    _$DoctorItemImpl(
      id: json['id'] as String,
      name: json['name'] as String,
      specialty: json['specialty'] as String,
      avatarUrl: json['avatarUrl'] as String,
      rating: (json['rating'] as num).toDouble(),
      reviewCount: (json['reviewCount'] as num).toInt(),
      experience: json['experience'] as String,
      languages:
          (json['languages'] as List<dynamic>).map((e) => e as String).toList(),
      isOnline: json['isOnline'] as bool,
      isFavorite: json['isFavorite'] as bool,
      clinicName: json['clinicName'] as String,
      consultationFee: (json['consultationFee'] as num).toInt(),
    );

Map<String, dynamic> _$$DoctorItemImplToJson(_$DoctorItemImpl instance) =>
    <String, dynamic>{
      'id': instance.id,
      'name': instance.name,
      'specialty': instance.specialty,
      'avatarUrl': instance.avatarUrl,
      'rating': instance.rating,
      'reviewCount': instance.reviewCount,
      'experience': instance.experience,
      'languages': instance.languages,
      'isOnline': instance.isOnline,
      'isFavorite': instance.isFavorite,
      'clinicName': instance.clinicName,
      'consultationFee': instance.consultationFee,
    };

_$BeforeAfterItemImpl _$$BeforeAfterItemImplFromJson(
        Map<String, dynamic> json) =>
    _$BeforeAfterItemImpl(
      id: json['id'] as String,
      beforeImageUrl: json['beforeImageUrl'] as String,
      afterImageUrl: json['afterImageUrl'] as String,
      caption: json['caption'] as String,
      doctorName: json['doctorName'] as String,
      treatmentType: json['treatmentType'] as String,
    );

Map<String, dynamic> _$$BeforeAfterItemImplToJson(
        _$BeforeAfterItemImpl instance) =>
    <String, dynamic>{
      'id': instance.id,
      'beforeImageUrl': instance.beforeImageUrl,
      'afterImageUrl': instance.afterImageUrl,
      'caption': instance.caption,
      'doctorName': instance.doctorName,
      'treatmentType': instance.treatmentType,
    };

_$BeforeAfterCaseImpl _$$BeforeAfterCaseImplFromJson(
        Map<String, dynamic> json) =>
    _$BeforeAfterCaseImpl(
      id: json['id'] as String,
      title: json['title'] as String,
      beforeImageUrl: json['beforeImageUrl'] as String,
      afterImageUrl: json['afterImageUrl'] as String,
      description: json['description'] as String,
      doctorName: json['doctorName'] as String,
      duration: json['duration'] as String,
      isFeatured: json['isFeatured'] as bool,
      likes: (json['likes'] as num).toInt(),
      comments: (json['comments'] as num).toInt(),
    );

Map<String, dynamic> _$$BeforeAfterCaseImplToJson(
        _$BeforeAfterCaseImpl instance) =>
    <String, dynamic>{
      'id': instance.id,
      'title': instance.title,
      'beforeImageUrl': instance.beforeImageUrl,
      'afterImageUrl': instance.afterImageUrl,
      'description': instance.description,
      'doctorName': instance.doctorName,
      'duration': instance.duration,
      'isFeatured': instance.isFeatured,
      'likes': instance.likes,
      'comments': instance.comments,
    };
