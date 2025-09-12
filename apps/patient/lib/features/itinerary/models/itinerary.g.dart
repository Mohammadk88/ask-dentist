// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'itinerary.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

_$ItineraryImpl _$$ItineraryImplFromJson(Map<String, dynamic> json) =>
    _$ItineraryImpl(
      id: json['id'] as String,
      patientId: json['patientId'] as String,
      treatmentPlanId: json['treatmentPlanId'] as String,
      items: (json['items'] as List<dynamic>)
          .map((e) => ItineraryItem.fromJson(e as Map<String, dynamic>))
          .toList(),
      startDate: DateTime.parse(json['startDate'] as String),
      endDate: DateTime.parse(json['endDate'] as String),
      status: $enumDecodeNullable(_$ItineraryStatusEnumMap, json['status']) ??
          ItineraryStatus.draft,
      createdAt: json['createdAt'] == null
          ? null
          : DateTime.parse(json['createdAt'] as String),
      updatedAt: json['updatedAt'] == null
          ? null
          : DateTime.parse(json['updatedAt'] as String),
    );

Map<String, dynamic> _$$ItineraryImplToJson(_$ItineraryImpl instance) =>
    <String, dynamic>{
      'id': instance.id,
      'patientId': instance.patientId,
      'treatmentPlanId': instance.treatmentPlanId,
      'items': instance.items,
      'startDate': instance.startDate.toIso8601String(),
      'endDate': instance.endDate.toIso8601String(),
      'status': _$ItineraryStatusEnumMap[instance.status]!,
      'createdAt': instance.createdAt?.toIso8601String(),
      'updatedAt': instance.updatedAt?.toIso8601String(),
    };

const _$ItineraryStatusEnumMap = {
  ItineraryStatus.draft: 'draft',
  ItineraryStatus.confirmed: 'confirmed',
  ItineraryStatus.inProgress: 'in_progress',
  ItineraryStatus.completed: 'completed',
  ItineraryStatus.cancelled: 'cancelled',
};

_$ItineraryItemImpl _$$ItineraryItemImplFromJson(Map<String, dynamic> json) =>
    _$ItineraryItemImpl(
      id: json['id'] as String,
      type: $enumDecode(_$ItineraryItemTypeEnumMap, json['type']),
      title: json['title'] as String,
      description: json['description'] as String,
      dateTime: DateTime.parse(json['dateTime'] as String),
      location: json['location'] as String?,
      address: json['address'] as String?,
      details: json['details'] as Map<String, dynamic>?,
      status:
          $enumDecodeNullable(_$ItineraryItemStatusEnumMap, json['status']) ??
              ItineraryItemStatus.scheduled,
      order: (json['order'] as num?)?.toInt() ?? 1,
    );

Map<String, dynamic> _$$ItineraryItemImplToJson(_$ItineraryItemImpl instance) =>
    <String, dynamic>{
      'id': instance.id,
      'type': _$ItineraryItemTypeEnumMap[instance.type]!,
      'title': instance.title,
      'description': instance.description,
      'dateTime': instance.dateTime.toIso8601String(),
      'location': instance.location,
      'address': instance.address,
      'details': instance.details,
      'status': _$ItineraryItemStatusEnumMap[instance.status]!,
      'order': instance.order,
    };

const _$ItineraryItemTypeEnumMap = {
  ItineraryItemType.flight: 'flight',
  ItineraryItemType.hotel: 'hotel',
  ItineraryItemType.transport: 'transport',
  ItineraryItemType.clinicAppointment: 'clinic_appointment',
  ItineraryItemType.consultation: 'consultation',
  ItineraryItemType.treatment: 'treatment',
  ItineraryItemType.followUp: 'follow_up',
  ItineraryItemType.freeTime: 'free_time',
};

const _$ItineraryItemStatusEnumMap = {
  ItineraryItemStatus.scheduled: 'scheduled',
  ItineraryItemStatus.confirmed: 'confirmed',
  ItineraryItemStatus.inProgress: 'in_progress',
  ItineraryItemStatus.completed: 'completed',
  ItineraryItemStatus.cancelled: 'cancelled',
  ItineraryItemStatus.rescheduled: 'rescheduled',
};
