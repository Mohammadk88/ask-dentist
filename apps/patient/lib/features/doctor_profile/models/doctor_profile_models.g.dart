// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'doctor_profile_models.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

_$DoctorServiceImpl _$$DoctorServiceImplFromJson(Map<String, dynamic> json) =>
    _$DoctorServiceImpl(
      id: json['id'] as String,
      name: json['name'] as String,
      description: json['description'] as String,
      price: (json['price'] as num).toDouble(),
      duration: json['duration'] as String,
      category: $enumDecode(_$ServiceCategoryEnumMap, json['category']),
      imageUrl: json['imageUrl'] as String?,
      isPopular: json['isPopular'] as bool? ?? false,
    );

Map<String, dynamic> _$$DoctorServiceImplToJson(_$DoctorServiceImpl instance) =>
    <String, dynamic>{
      'id': instance.id,
      'name': instance.name,
      'description': instance.description,
      'price': instance.price,
      'duration': instance.duration,
      'category': _$ServiceCategoryEnumMap[instance.category]!,
      'imageUrl': instance.imageUrl,
      'isPopular': instance.isPopular,
    };

const _$ServiceCategoryEnumMap = {
  ServiceCategory.cosmetic: 'cosmetic',
  ServiceCategory.restorative: 'restorative',
  ServiceCategory.preventive: 'preventive',
  ServiceCategory.surgical: 'surgical',
};

_$TimeSlotImpl _$$TimeSlotImplFromJson(Map<String, dynamic> json) =>
    _$TimeSlotImpl(
      time: json['time'] as String,
      isAvailable: json['isAvailable'] as bool,
    );

Map<String, dynamic> _$$TimeSlotImplToJson(_$TimeSlotImpl instance) =>
    <String, dynamic>{
      'time': instance.time,
      'isAvailable': instance.isAvailable,
    };

_$DoctorAvailabilityImpl _$$DoctorAvailabilityImplFromJson(
        Map<String, dynamic> json) =>
    _$DoctorAvailabilityImpl(
      date: DateTime.parse(json['date'] as String),
      timeSlots: (json['timeSlots'] as List<dynamic>)
          .map((e) => TimeSlot.fromJson(e as Map<String, dynamic>))
          .toList(),
    );

Map<String, dynamic> _$$DoctorAvailabilityImplToJson(
        _$DoctorAvailabilityImpl instance) =>
    <String, dynamic>{
      'date': instance.date.toIso8601String(),
      'timeSlots': instance.timeSlots,
    };
