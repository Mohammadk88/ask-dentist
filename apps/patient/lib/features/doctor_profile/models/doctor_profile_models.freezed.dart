// coverage:ignore-file
// GENERATED CODE - DO NOT MODIFY BY HAND
// ignore_for_file: type=lint
// ignore_for_file: unused_element, deprecated_member_use, deprecated_member_use_from_same_package, use_function_type_syntax_for_parameters, unnecessary_const, avoid_init_to_null, invalid_override_different_default_values_named, prefer_expression_function_bodies, annotate_overrides, invalid_annotation_target, unnecessary_question_mark

part of 'doctor_profile_models.dart';

// **************************************************************************
// FreezedGenerator
// **************************************************************************

T _$identity<T>(T value) => value;

final _privateConstructorUsedError = UnsupportedError(
    'It seems like you constructed your class using `MyClass._()`. This constructor is only meant to be used by freezed and you are not supposed to need it nor use it.\nPlease check the documentation here for more information: https://github.com/rrousselGit/freezed#adding-getters-and-methods-to-our-models');

DoctorService _$DoctorServiceFromJson(Map<String, dynamic> json) {
  return _DoctorService.fromJson(json);
}

/// @nodoc
mixin _$DoctorService {
  String get id => throw _privateConstructorUsedError;
  String get name => throw _privateConstructorUsedError;
  String get description => throw _privateConstructorUsedError;
  double get price => throw _privateConstructorUsedError;
  String get duration => throw _privateConstructorUsedError;
  ServiceCategory get category => throw _privateConstructorUsedError;
  String? get imageUrl => throw _privateConstructorUsedError;
  bool get isPopular => throw _privateConstructorUsedError;

  /// Serializes this DoctorService to a JSON map.
  Map<String, dynamic> toJson() => throw _privateConstructorUsedError;

  /// Create a copy of DoctorService
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  $DoctorServiceCopyWith<DoctorService> get copyWith =>
      throw _privateConstructorUsedError;
}

/// @nodoc
abstract class $DoctorServiceCopyWith<$Res> {
  factory $DoctorServiceCopyWith(
          DoctorService value, $Res Function(DoctorService) then) =
      _$DoctorServiceCopyWithImpl<$Res, DoctorService>;
  @useResult
  $Res call(
      {String id,
      String name,
      String description,
      double price,
      String duration,
      ServiceCategory category,
      String? imageUrl,
      bool isPopular});
}

/// @nodoc
class _$DoctorServiceCopyWithImpl<$Res, $Val extends DoctorService>
    implements $DoctorServiceCopyWith<$Res> {
  _$DoctorServiceCopyWithImpl(this._value, this._then);

  // ignore: unused_field
  final $Val _value;
  // ignore: unused_field
  final $Res Function($Val) _then;

  /// Create a copy of DoctorService
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? name = null,
    Object? description = null,
    Object? price = null,
    Object? duration = null,
    Object? category = null,
    Object? imageUrl = freezed,
    Object? isPopular = null,
  }) {
    return _then(_value.copyWith(
      id: null == id
          ? _value.id
          : id // ignore: cast_nullable_to_non_nullable
              as String,
      name: null == name
          ? _value.name
          : name // ignore: cast_nullable_to_non_nullable
              as String,
      description: null == description
          ? _value.description
          : description // ignore: cast_nullable_to_non_nullable
              as String,
      price: null == price
          ? _value.price
          : price // ignore: cast_nullable_to_non_nullable
              as double,
      duration: null == duration
          ? _value.duration
          : duration // ignore: cast_nullable_to_non_nullable
              as String,
      category: null == category
          ? _value.category
          : category // ignore: cast_nullable_to_non_nullable
              as ServiceCategory,
      imageUrl: freezed == imageUrl
          ? _value.imageUrl
          : imageUrl // ignore: cast_nullable_to_non_nullable
              as String?,
      isPopular: null == isPopular
          ? _value.isPopular
          : isPopular // ignore: cast_nullable_to_non_nullable
              as bool,
    ) as $Val);
  }
}

/// @nodoc
abstract class _$$DoctorServiceImplCopyWith<$Res>
    implements $DoctorServiceCopyWith<$Res> {
  factory _$$DoctorServiceImplCopyWith(
          _$DoctorServiceImpl value, $Res Function(_$DoctorServiceImpl) then) =
      __$$DoctorServiceImplCopyWithImpl<$Res>;
  @override
  @useResult
  $Res call(
      {String id,
      String name,
      String description,
      double price,
      String duration,
      ServiceCategory category,
      String? imageUrl,
      bool isPopular});
}

/// @nodoc
class __$$DoctorServiceImplCopyWithImpl<$Res>
    extends _$DoctorServiceCopyWithImpl<$Res, _$DoctorServiceImpl>
    implements _$$DoctorServiceImplCopyWith<$Res> {
  __$$DoctorServiceImplCopyWithImpl(
      _$DoctorServiceImpl _value, $Res Function(_$DoctorServiceImpl) _then)
      : super(_value, _then);

  /// Create a copy of DoctorService
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? name = null,
    Object? description = null,
    Object? price = null,
    Object? duration = null,
    Object? category = null,
    Object? imageUrl = freezed,
    Object? isPopular = null,
  }) {
    return _then(_$DoctorServiceImpl(
      id: null == id
          ? _value.id
          : id // ignore: cast_nullable_to_non_nullable
              as String,
      name: null == name
          ? _value.name
          : name // ignore: cast_nullable_to_non_nullable
              as String,
      description: null == description
          ? _value.description
          : description // ignore: cast_nullable_to_non_nullable
              as String,
      price: null == price
          ? _value.price
          : price // ignore: cast_nullable_to_non_nullable
              as double,
      duration: null == duration
          ? _value.duration
          : duration // ignore: cast_nullable_to_non_nullable
              as String,
      category: null == category
          ? _value.category
          : category // ignore: cast_nullable_to_non_nullable
              as ServiceCategory,
      imageUrl: freezed == imageUrl
          ? _value.imageUrl
          : imageUrl // ignore: cast_nullable_to_non_nullable
              as String?,
      isPopular: null == isPopular
          ? _value.isPopular
          : isPopular // ignore: cast_nullable_to_non_nullable
              as bool,
    ));
  }
}

/// @nodoc
@JsonSerializable()
class _$DoctorServiceImpl implements _DoctorService {
  const _$DoctorServiceImpl(
      {required this.id,
      required this.name,
      required this.description,
      required this.price,
      required this.duration,
      required this.category,
      this.imageUrl,
      this.isPopular = false});

  factory _$DoctorServiceImpl.fromJson(Map<String, dynamic> json) =>
      _$$DoctorServiceImplFromJson(json);

  @override
  final String id;
  @override
  final String name;
  @override
  final String description;
  @override
  final double price;
  @override
  final String duration;
  @override
  final ServiceCategory category;
  @override
  final String? imageUrl;
  @override
  @JsonKey()
  final bool isPopular;

  @override
  String toString() {
    return 'DoctorService(id: $id, name: $name, description: $description, price: $price, duration: $duration, category: $category, imageUrl: $imageUrl, isPopular: $isPopular)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$DoctorServiceImpl &&
            (identical(other.id, id) || other.id == id) &&
            (identical(other.name, name) || other.name == name) &&
            (identical(other.description, description) ||
                other.description == description) &&
            (identical(other.price, price) || other.price == price) &&
            (identical(other.duration, duration) ||
                other.duration == duration) &&
            (identical(other.category, category) ||
                other.category == category) &&
            (identical(other.imageUrl, imageUrl) ||
                other.imageUrl == imageUrl) &&
            (identical(other.isPopular, isPopular) ||
                other.isPopular == isPopular));
  }

  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  int get hashCode => Object.hash(runtimeType, id, name, description, price,
      duration, category, imageUrl, isPopular);

  /// Create a copy of DoctorService
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  @pragma('vm:prefer-inline')
  _$$DoctorServiceImplCopyWith<_$DoctorServiceImpl> get copyWith =>
      __$$DoctorServiceImplCopyWithImpl<_$DoctorServiceImpl>(this, _$identity);

  @override
  Map<String, dynamic> toJson() {
    return _$$DoctorServiceImplToJson(
      this,
    );
  }
}

abstract class _DoctorService implements DoctorService {
  const factory _DoctorService(
      {required final String id,
      required final String name,
      required final String description,
      required final double price,
      required final String duration,
      required final ServiceCategory category,
      final String? imageUrl,
      final bool isPopular}) = _$DoctorServiceImpl;

  factory _DoctorService.fromJson(Map<String, dynamic> json) =
      _$DoctorServiceImpl.fromJson;

  @override
  String get id;
  @override
  String get name;
  @override
  String get description;
  @override
  double get price;
  @override
  String get duration;
  @override
  ServiceCategory get category;
  @override
  String? get imageUrl;
  @override
  bool get isPopular;

  /// Create a copy of DoctorService
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$DoctorServiceImplCopyWith<_$DoctorServiceImpl> get copyWith =>
      throw _privateConstructorUsedError;
}

TimeSlot _$TimeSlotFromJson(Map<String, dynamic> json) {
  return _TimeSlot.fromJson(json);
}

/// @nodoc
mixin _$TimeSlot {
  String get time => throw _privateConstructorUsedError;
  bool get isAvailable => throw _privateConstructorUsedError;

  /// Serializes this TimeSlot to a JSON map.
  Map<String, dynamic> toJson() => throw _privateConstructorUsedError;

  /// Create a copy of TimeSlot
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  $TimeSlotCopyWith<TimeSlot> get copyWith =>
      throw _privateConstructorUsedError;
}

/// @nodoc
abstract class $TimeSlotCopyWith<$Res> {
  factory $TimeSlotCopyWith(TimeSlot value, $Res Function(TimeSlot) then) =
      _$TimeSlotCopyWithImpl<$Res, TimeSlot>;
  @useResult
  $Res call({String time, bool isAvailable});
}

/// @nodoc
class _$TimeSlotCopyWithImpl<$Res, $Val extends TimeSlot>
    implements $TimeSlotCopyWith<$Res> {
  _$TimeSlotCopyWithImpl(this._value, this._then);

  // ignore: unused_field
  final $Val _value;
  // ignore: unused_field
  final $Res Function($Val) _then;

  /// Create a copy of TimeSlot
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? time = null,
    Object? isAvailable = null,
  }) {
    return _then(_value.copyWith(
      time: null == time
          ? _value.time
          : time // ignore: cast_nullable_to_non_nullable
              as String,
      isAvailable: null == isAvailable
          ? _value.isAvailable
          : isAvailable // ignore: cast_nullable_to_non_nullable
              as bool,
    ) as $Val);
  }
}

/// @nodoc
abstract class _$$TimeSlotImplCopyWith<$Res>
    implements $TimeSlotCopyWith<$Res> {
  factory _$$TimeSlotImplCopyWith(
          _$TimeSlotImpl value, $Res Function(_$TimeSlotImpl) then) =
      __$$TimeSlotImplCopyWithImpl<$Res>;
  @override
  @useResult
  $Res call({String time, bool isAvailable});
}

/// @nodoc
class __$$TimeSlotImplCopyWithImpl<$Res>
    extends _$TimeSlotCopyWithImpl<$Res, _$TimeSlotImpl>
    implements _$$TimeSlotImplCopyWith<$Res> {
  __$$TimeSlotImplCopyWithImpl(
      _$TimeSlotImpl _value, $Res Function(_$TimeSlotImpl) _then)
      : super(_value, _then);

  /// Create a copy of TimeSlot
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? time = null,
    Object? isAvailable = null,
  }) {
    return _then(_$TimeSlotImpl(
      time: null == time
          ? _value.time
          : time // ignore: cast_nullable_to_non_nullable
              as String,
      isAvailable: null == isAvailable
          ? _value.isAvailable
          : isAvailable // ignore: cast_nullable_to_non_nullable
              as bool,
    ));
  }
}

/// @nodoc
@JsonSerializable()
class _$TimeSlotImpl implements _TimeSlot {
  const _$TimeSlotImpl({required this.time, required this.isAvailable});

  factory _$TimeSlotImpl.fromJson(Map<String, dynamic> json) =>
      _$$TimeSlotImplFromJson(json);

  @override
  final String time;
  @override
  final bool isAvailable;

  @override
  String toString() {
    return 'TimeSlot(time: $time, isAvailable: $isAvailable)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$TimeSlotImpl &&
            (identical(other.time, time) || other.time == time) &&
            (identical(other.isAvailable, isAvailable) ||
                other.isAvailable == isAvailable));
  }

  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  int get hashCode => Object.hash(runtimeType, time, isAvailable);

  /// Create a copy of TimeSlot
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  @pragma('vm:prefer-inline')
  _$$TimeSlotImplCopyWith<_$TimeSlotImpl> get copyWith =>
      __$$TimeSlotImplCopyWithImpl<_$TimeSlotImpl>(this, _$identity);

  @override
  Map<String, dynamic> toJson() {
    return _$$TimeSlotImplToJson(
      this,
    );
  }
}

abstract class _TimeSlot implements TimeSlot {
  const factory _TimeSlot(
      {required final String time,
      required final bool isAvailable}) = _$TimeSlotImpl;

  factory _TimeSlot.fromJson(Map<String, dynamic> json) =
      _$TimeSlotImpl.fromJson;

  @override
  String get time;
  @override
  bool get isAvailable;

  /// Create a copy of TimeSlot
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$TimeSlotImplCopyWith<_$TimeSlotImpl> get copyWith =>
      throw _privateConstructorUsedError;
}

DoctorAvailability _$DoctorAvailabilityFromJson(Map<String, dynamic> json) {
  return _DoctorAvailability.fromJson(json);
}

/// @nodoc
mixin _$DoctorAvailability {
  DateTime get date => throw _privateConstructorUsedError;
  List<TimeSlot> get timeSlots => throw _privateConstructorUsedError;

  /// Serializes this DoctorAvailability to a JSON map.
  Map<String, dynamic> toJson() => throw _privateConstructorUsedError;

  /// Create a copy of DoctorAvailability
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  $DoctorAvailabilityCopyWith<DoctorAvailability> get copyWith =>
      throw _privateConstructorUsedError;
}

/// @nodoc
abstract class $DoctorAvailabilityCopyWith<$Res> {
  factory $DoctorAvailabilityCopyWith(
          DoctorAvailability value, $Res Function(DoctorAvailability) then) =
      _$DoctorAvailabilityCopyWithImpl<$Res, DoctorAvailability>;
  @useResult
  $Res call({DateTime date, List<TimeSlot> timeSlots});
}

/// @nodoc
class _$DoctorAvailabilityCopyWithImpl<$Res, $Val extends DoctorAvailability>
    implements $DoctorAvailabilityCopyWith<$Res> {
  _$DoctorAvailabilityCopyWithImpl(this._value, this._then);

  // ignore: unused_field
  final $Val _value;
  // ignore: unused_field
  final $Res Function($Val) _then;

  /// Create a copy of DoctorAvailability
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? date = null,
    Object? timeSlots = null,
  }) {
    return _then(_value.copyWith(
      date: null == date
          ? _value.date
          : date // ignore: cast_nullable_to_non_nullable
              as DateTime,
      timeSlots: null == timeSlots
          ? _value.timeSlots
          : timeSlots // ignore: cast_nullable_to_non_nullable
              as List<TimeSlot>,
    ) as $Val);
  }
}

/// @nodoc
abstract class _$$DoctorAvailabilityImplCopyWith<$Res>
    implements $DoctorAvailabilityCopyWith<$Res> {
  factory _$$DoctorAvailabilityImplCopyWith(_$DoctorAvailabilityImpl value,
          $Res Function(_$DoctorAvailabilityImpl) then) =
      __$$DoctorAvailabilityImplCopyWithImpl<$Res>;
  @override
  @useResult
  $Res call({DateTime date, List<TimeSlot> timeSlots});
}

/// @nodoc
class __$$DoctorAvailabilityImplCopyWithImpl<$Res>
    extends _$DoctorAvailabilityCopyWithImpl<$Res, _$DoctorAvailabilityImpl>
    implements _$$DoctorAvailabilityImplCopyWith<$Res> {
  __$$DoctorAvailabilityImplCopyWithImpl(_$DoctorAvailabilityImpl _value,
      $Res Function(_$DoctorAvailabilityImpl) _then)
      : super(_value, _then);

  /// Create a copy of DoctorAvailability
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? date = null,
    Object? timeSlots = null,
  }) {
    return _then(_$DoctorAvailabilityImpl(
      date: null == date
          ? _value.date
          : date // ignore: cast_nullable_to_non_nullable
              as DateTime,
      timeSlots: null == timeSlots
          ? _value._timeSlots
          : timeSlots // ignore: cast_nullable_to_non_nullable
              as List<TimeSlot>,
    ));
  }
}

/// @nodoc
@JsonSerializable()
class _$DoctorAvailabilityImpl implements _DoctorAvailability {
  const _$DoctorAvailabilityImpl(
      {required this.date, required final List<TimeSlot> timeSlots})
      : _timeSlots = timeSlots;

  factory _$DoctorAvailabilityImpl.fromJson(Map<String, dynamic> json) =>
      _$$DoctorAvailabilityImplFromJson(json);

  @override
  final DateTime date;
  final List<TimeSlot> _timeSlots;
  @override
  List<TimeSlot> get timeSlots {
    if (_timeSlots is EqualUnmodifiableListView) return _timeSlots;
    // ignore: implicit_dynamic_type
    return EqualUnmodifiableListView(_timeSlots);
  }

  @override
  String toString() {
    return 'DoctorAvailability(date: $date, timeSlots: $timeSlots)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$DoctorAvailabilityImpl &&
            (identical(other.date, date) || other.date == date) &&
            const DeepCollectionEquality()
                .equals(other._timeSlots, _timeSlots));
  }

  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  int get hashCode => Object.hash(
      runtimeType, date, const DeepCollectionEquality().hash(_timeSlots));

  /// Create a copy of DoctorAvailability
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  @pragma('vm:prefer-inline')
  _$$DoctorAvailabilityImplCopyWith<_$DoctorAvailabilityImpl> get copyWith =>
      __$$DoctorAvailabilityImplCopyWithImpl<_$DoctorAvailabilityImpl>(
          this, _$identity);

  @override
  Map<String, dynamic> toJson() {
    return _$$DoctorAvailabilityImplToJson(
      this,
    );
  }
}

abstract class _DoctorAvailability implements DoctorAvailability {
  const factory _DoctorAvailability(
      {required final DateTime date,
      required final List<TimeSlot> timeSlots}) = _$DoctorAvailabilityImpl;

  factory _DoctorAvailability.fromJson(Map<String, dynamic> json) =
      _$DoctorAvailabilityImpl.fromJson;

  @override
  DateTime get date;
  @override
  List<TimeSlot> get timeSlots;

  /// Create a copy of DoctorAvailability
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$DoctorAvailabilityImplCopyWith<_$DoctorAvailabilityImpl> get copyWith =>
      throw _privateConstructorUsedError;
}
