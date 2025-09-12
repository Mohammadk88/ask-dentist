// coverage:ignore-file
// GENERATED CODE - DO NOT MODIFY BY HAND
// ignore_for_file: type=lint
// ignore_for_file: unused_element, deprecated_member_use, deprecated_member_use_from_same_package, use_function_type_syntax_for_parameters, unnecessary_const, avoid_init_to_null, invalid_override_different_default_values_named, prefer_expression_function_bodies, annotate_overrides, invalid_annotation_target, unnecessary_question_mark

part of 'treatment_plan.dart';

// **************************************************************************
// FreezedGenerator
// **************************************************************************

T _$identity<T>(T value) => value;

final _privateConstructorUsedError = UnsupportedError(
    'It seems like you constructed your class using `MyClass._()`. This constructor is only meant to be used by freezed and you are not supposed to need it nor use it.\nPlease check the documentation here for more information: https://github.com/rrousselGit/freezed#adding-getters-and-methods-to-our-models');

TreatmentPlan _$TreatmentPlanFromJson(Map<String, dynamic> json) {
  return _TreatmentPlan.fromJson(json);
}

/// @nodoc
mixin _$TreatmentPlan {
  String get id => throw _privateConstructorUsedError;
  String get caseId => throw _privateConstructorUsedError;
  String get doctorId => throw _privateConstructorUsedError;
  @JsonKey(includeFromJson: false, includeToJson: false)
  Doctor? get doctor => throw _privateConstructorUsedError;
  String get title => throw _privateConstructorUsedError;
  String get description => throw _privateConstructorUsedError;
  List<TreatmentStage> get stages => throw _privateConstructorUsedError;
  double get totalPrice => throw _privateConstructorUsedError;
  String get currency => throw _privateConstructorUsedError;
  int get estimatedDays => throw _privateConstructorUsedError;
  TreatmentPlanStatus get status => throw _privateConstructorUsedError;
  DateTime? get submittedAt => throw _privateConstructorUsedError;
  DateTime? get respondedAt => throw _privateConstructorUsedError;
  DateTime? get expiresAt => throw _privateConstructorUsedError;
  String? get patientResponse => throw _privateConstructorUsedError;
  DateTime? get createdAt => throw _privateConstructorUsedError;
  DateTime? get updatedAt => throw _privateConstructorUsedError;

  /// Serializes this TreatmentPlan to a JSON map.
  Map<String, dynamic> toJson() => throw _privateConstructorUsedError;

  /// Create a copy of TreatmentPlan
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  $TreatmentPlanCopyWith<TreatmentPlan> get copyWith =>
      throw _privateConstructorUsedError;
}

/// @nodoc
abstract class $TreatmentPlanCopyWith<$Res> {
  factory $TreatmentPlanCopyWith(
          TreatmentPlan value, $Res Function(TreatmentPlan) then) =
      _$TreatmentPlanCopyWithImpl<$Res, TreatmentPlan>;
  @useResult
  $Res call(
      {String id,
      String caseId,
      String doctorId,
      @JsonKey(includeFromJson: false, includeToJson: false) Doctor? doctor,
      String title,
      String description,
      List<TreatmentStage> stages,
      double totalPrice,
      String currency,
      int estimatedDays,
      TreatmentPlanStatus status,
      DateTime? submittedAt,
      DateTime? respondedAt,
      DateTime? expiresAt,
      String? patientResponse,
      DateTime? createdAt,
      DateTime? updatedAt});

  $DoctorCopyWith<$Res>? get doctor;
}

/// @nodoc
class _$TreatmentPlanCopyWithImpl<$Res, $Val extends TreatmentPlan>
    implements $TreatmentPlanCopyWith<$Res> {
  _$TreatmentPlanCopyWithImpl(this._value, this._then);

  // ignore: unused_field
  final $Val _value;
  // ignore: unused_field
  final $Res Function($Val) _then;

  /// Create a copy of TreatmentPlan
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? caseId = null,
    Object? doctorId = null,
    Object? doctor = freezed,
    Object? title = null,
    Object? description = null,
    Object? stages = null,
    Object? totalPrice = null,
    Object? currency = null,
    Object? estimatedDays = null,
    Object? status = null,
    Object? submittedAt = freezed,
    Object? respondedAt = freezed,
    Object? expiresAt = freezed,
    Object? patientResponse = freezed,
    Object? createdAt = freezed,
    Object? updatedAt = freezed,
  }) {
    return _then(_value.copyWith(
      id: null == id
          ? _value.id
          : id // ignore: cast_nullable_to_non_nullable
              as String,
      caseId: null == caseId
          ? _value.caseId
          : caseId // ignore: cast_nullable_to_non_nullable
              as String,
      doctorId: null == doctorId
          ? _value.doctorId
          : doctorId // ignore: cast_nullable_to_non_nullable
              as String,
      doctor: freezed == doctor
          ? _value.doctor
          : doctor // ignore: cast_nullable_to_non_nullable
              as Doctor?,
      title: null == title
          ? _value.title
          : title // ignore: cast_nullable_to_non_nullable
              as String,
      description: null == description
          ? _value.description
          : description // ignore: cast_nullable_to_non_nullable
              as String,
      stages: null == stages
          ? _value.stages
          : stages // ignore: cast_nullable_to_non_nullable
              as List<TreatmentStage>,
      totalPrice: null == totalPrice
          ? _value.totalPrice
          : totalPrice // ignore: cast_nullable_to_non_nullable
              as double,
      currency: null == currency
          ? _value.currency
          : currency // ignore: cast_nullable_to_non_nullable
              as String,
      estimatedDays: null == estimatedDays
          ? _value.estimatedDays
          : estimatedDays // ignore: cast_nullable_to_non_nullable
              as int,
      status: null == status
          ? _value.status
          : status // ignore: cast_nullable_to_non_nullable
              as TreatmentPlanStatus,
      submittedAt: freezed == submittedAt
          ? _value.submittedAt
          : submittedAt // ignore: cast_nullable_to_non_nullable
              as DateTime?,
      respondedAt: freezed == respondedAt
          ? _value.respondedAt
          : respondedAt // ignore: cast_nullable_to_non_nullable
              as DateTime?,
      expiresAt: freezed == expiresAt
          ? _value.expiresAt
          : expiresAt // ignore: cast_nullable_to_non_nullable
              as DateTime?,
      patientResponse: freezed == patientResponse
          ? _value.patientResponse
          : patientResponse // ignore: cast_nullable_to_non_nullable
              as String?,
      createdAt: freezed == createdAt
          ? _value.createdAt
          : createdAt // ignore: cast_nullable_to_non_nullable
              as DateTime?,
      updatedAt: freezed == updatedAt
          ? _value.updatedAt
          : updatedAt // ignore: cast_nullable_to_non_nullable
              as DateTime?,
    ) as $Val);
  }

  /// Create a copy of TreatmentPlan
  /// with the given fields replaced by the non-null parameter values.
  @override
  @pragma('vm:prefer-inline')
  $DoctorCopyWith<$Res>? get doctor {
    if (_value.doctor == null) {
      return null;
    }

    return $DoctorCopyWith<$Res>(_value.doctor!, (value) {
      return _then(_value.copyWith(doctor: value) as $Val);
    });
  }
}

/// @nodoc
abstract class _$$TreatmentPlanImplCopyWith<$Res>
    implements $TreatmentPlanCopyWith<$Res> {
  factory _$$TreatmentPlanImplCopyWith(
          _$TreatmentPlanImpl value, $Res Function(_$TreatmentPlanImpl) then) =
      __$$TreatmentPlanImplCopyWithImpl<$Res>;
  @override
  @useResult
  $Res call(
      {String id,
      String caseId,
      String doctorId,
      @JsonKey(includeFromJson: false, includeToJson: false) Doctor? doctor,
      String title,
      String description,
      List<TreatmentStage> stages,
      double totalPrice,
      String currency,
      int estimatedDays,
      TreatmentPlanStatus status,
      DateTime? submittedAt,
      DateTime? respondedAt,
      DateTime? expiresAt,
      String? patientResponse,
      DateTime? createdAt,
      DateTime? updatedAt});

  @override
  $DoctorCopyWith<$Res>? get doctor;
}

/// @nodoc
class __$$TreatmentPlanImplCopyWithImpl<$Res>
    extends _$TreatmentPlanCopyWithImpl<$Res, _$TreatmentPlanImpl>
    implements _$$TreatmentPlanImplCopyWith<$Res> {
  __$$TreatmentPlanImplCopyWithImpl(
      _$TreatmentPlanImpl _value, $Res Function(_$TreatmentPlanImpl) _then)
      : super(_value, _then);

  /// Create a copy of TreatmentPlan
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? caseId = null,
    Object? doctorId = null,
    Object? doctor = freezed,
    Object? title = null,
    Object? description = null,
    Object? stages = null,
    Object? totalPrice = null,
    Object? currency = null,
    Object? estimatedDays = null,
    Object? status = null,
    Object? submittedAt = freezed,
    Object? respondedAt = freezed,
    Object? expiresAt = freezed,
    Object? patientResponse = freezed,
    Object? createdAt = freezed,
    Object? updatedAt = freezed,
  }) {
    return _then(_$TreatmentPlanImpl(
      id: null == id
          ? _value.id
          : id // ignore: cast_nullable_to_non_nullable
              as String,
      caseId: null == caseId
          ? _value.caseId
          : caseId // ignore: cast_nullable_to_non_nullable
              as String,
      doctorId: null == doctorId
          ? _value.doctorId
          : doctorId // ignore: cast_nullable_to_non_nullable
              as String,
      doctor: freezed == doctor
          ? _value.doctor
          : doctor // ignore: cast_nullable_to_non_nullable
              as Doctor?,
      title: null == title
          ? _value.title
          : title // ignore: cast_nullable_to_non_nullable
              as String,
      description: null == description
          ? _value.description
          : description // ignore: cast_nullable_to_non_nullable
              as String,
      stages: null == stages
          ? _value._stages
          : stages // ignore: cast_nullable_to_non_nullable
              as List<TreatmentStage>,
      totalPrice: null == totalPrice
          ? _value.totalPrice
          : totalPrice // ignore: cast_nullable_to_non_nullable
              as double,
      currency: null == currency
          ? _value.currency
          : currency // ignore: cast_nullable_to_non_nullable
              as String,
      estimatedDays: null == estimatedDays
          ? _value.estimatedDays
          : estimatedDays // ignore: cast_nullable_to_non_nullable
              as int,
      status: null == status
          ? _value.status
          : status // ignore: cast_nullable_to_non_nullable
              as TreatmentPlanStatus,
      submittedAt: freezed == submittedAt
          ? _value.submittedAt
          : submittedAt // ignore: cast_nullable_to_non_nullable
              as DateTime?,
      respondedAt: freezed == respondedAt
          ? _value.respondedAt
          : respondedAt // ignore: cast_nullable_to_non_nullable
              as DateTime?,
      expiresAt: freezed == expiresAt
          ? _value.expiresAt
          : expiresAt // ignore: cast_nullable_to_non_nullable
              as DateTime?,
      patientResponse: freezed == patientResponse
          ? _value.patientResponse
          : patientResponse // ignore: cast_nullable_to_non_nullable
              as String?,
      createdAt: freezed == createdAt
          ? _value.createdAt
          : createdAt // ignore: cast_nullable_to_non_nullable
              as DateTime?,
      updatedAt: freezed == updatedAt
          ? _value.updatedAt
          : updatedAt // ignore: cast_nullable_to_non_nullable
              as DateTime?,
    ));
  }
}

/// @nodoc
@JsonSerializable()
class _$TreatmentPlanImpl implements _TreatmentPlan {
  const _$TreatmentPlanImpl(
      {required this.id,
      required this.caseId,
      required this.doctorId,
      @JsonKey(includeFromJson: false, includeToJson: false) this.doctor,
      required this.title,
      required this.description,
      required final List<TreatmentStage> stages,
      required this.totalPrice,
      required this.currency,
      required this.estimatedDays,
      this.status = TreatmentPlanStatus.pending,
      this.submittedAt,
      this.respondedAt,
      this.expiresAt,
      this.patientResponse,
      this.createdAt,
      this.updatedAt})
      : _stages = stages;

  factory _$TreatmentPlanImpl.fromJson(Map<String, dynamic> json) =>
      _$$TreatmentPlanImplFromJson(json);

  @override
  final String id;
  @override
  final String caseId;
  @override
  final String doctorId;
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  final Doctor? doctor;
  @override
  final String title;
  @override
  final String description;
  final List<TreatmentStage> _stages;
  @override
  List<TreatmentStage> get stages {
    if (_stages is EqualUnmodifiableListView) return _stages;
    // ignore: implicit_dynamic_type
    return EqualUnmodifiableListView(_stages);
  }

  @override
  final double totalPrice;
  @override
  final String currency;
  @override
  final int estimatedDays;
  @override
  @JsonKey()
  final TreatmentPlanStatus status;
  @override
  final DateTime? submittedAt;
  @override
  final DateTime? respondedAt;
  @override
  final DateTime? expiresAt;
  @override
  final String? patientResponse;
  @override
  final DateTime? createdAt;
  @override
  final DateTime? updatedAt;

  @override
  String toString() {
    return 'TreatmentPlan(id: $id, caseId: $caseId, doctorId: $doctorId, doctor: $doctor, title: $title, description: $description, stages: $stages, totalPrice: $totalPrice, currency: $currency, estimatedDays: $estimatedDays, status: $status, submittedAt: $submittedAt, respondedAt: $respondedAt, expiresAt: $expiresAt, patientResponse: $patientResponse, createdAt: $createdAt, updatedAt: $updatedAt)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$TreatmentPlanImpl &&
            (identical(other.id, id) || other.id == id) &&
            (identical(other.caseId, caseId) || other.caseId == caseId) &&
            (identical(other.doctorId, doctorId) ||
                other.doctorId == doctorId) &&
            (identical(other.doctor, doctor) || other.doctor == doctor) &&
            (identical(other.title, title) || other.title == title) &&
            (identical(other.description, description) ||
                other.description == description) &&
            const DeepCollectionEquality().equals(other._stages, _stages) &&
            (identical(other.totalPrice, totalPrice) ||
                other.totalPrice == totalPrice) &&
            (identical(other.currency, currency) ||
                other.currency == currency) &&
            (identical(other.estimatedDays, estimatedDays) ||
                other.estimatedDays == estimatedDays) &&
            (identical(other.status, status) || other.status == status) &&
            (identical(other.submittedAt, submittedAt) ||
                other.submittedAt == submittedAt) &&
            (identical(other.respondedAt, respondedAt) ||
                other.respondedAt == respondedAt) &&
            (identical(other.expiresAt, expiresAt) ||
                other.expiresAt == expiresAt) &&
            (identical(other.patientResponse, patientResponse) ||
                other.patientResponse == patientResponse) &&
            (identical(other.createdAt, createdAt) ||
                other.createdAt == createdAt) &&
            (identical(other.updatedAt, updatedAt) ||
                other.updatedAt == updatedAt));
  }

  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  int get hashCode => Object.hash(
      runtimeType,
      id,
      caseId,
      doctorId,
      doctor,
      title,
      description,
      const DeepCollectionEquality().hash(_stages),
      totalPrice,
      currency,
      estimatedDays,
      status,
      submittedAt,
      respondedAt,
      expiresAt,
      patientResponse,
      createdAt,
      updatedAt);

  /// Create a copy of TreatmentPlan
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  @pragma('vm:prefer-inline')
  _$$TreatmentPlanImplCopyWith<_$TreatmentPlanImpl> get copyWith =>
      __$$TreatmentPlanImplCopyWithImpl<_$TreatmentPlanImpl>(this, _$identity);

  @override
  Map<String, dynamic> toJson() {
    return _$$TreatmentPlanImplToJson(
      this,
    );
  }
}

abstract class _TreatmentPlan implements TreatmentPlan {
  const factory _TreatmentPlan(
      {required final String id,
      required final String caseId,
      required final String doctorId,
      @JsonKey(includeFromJson: false, includeToJson: false)
      final Doctor? doctor,
      required final String title,
      required final String description,
      required final List<TreatmentStage> stages,
      required final double totalPrice,
      required final String currency,
      required final int estimatedDays,
      final TreatmentPlanStatus status,
      final DateTime? submittedAt,
      final DateTime? respondedAt,
      final DateTime? expiresAt,
      final String? patientResponse,
      final DateTime? createdAt,
      final DateTime? updatedAt}) = _$TreatmentPlanImpl;

  factory _TreatmentPlan.fromJson(Map<String, dynamic> json) =
      _$TreatmentPlanImpl.fromJson;

  @override
  String get id;
  @override
  String get caseId;
  @override
  String get doctorId;
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  Doctor? get doctor;
  @override
  String get title;
  @override
  String get description;
  @override
  List<TreatmentStage> get stages;
  @override
  double get totalPrice;
  @override
  String get currency;
  @override
  int get estimatedDays;
  @override
  TreatmentPlanStatus get status;
  @override
  DateTime? get submittedAt;
  @override
  DateTime? get respondedAt;
  @override
  DateTime? get expiresAt;
  @override
  String? get patientResponse;
  @override
  DateTime? get createdAt;
  @override
  DateTime? get updatedAt;

  /// Create a copy of TreatmentPlan
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$TreatmentPlanImplCopyWith<_$TreatmentPlanImpl> get copyWith =>
      throw _privateConstructorUsedError;
}

TreatmentStage _$TreatmentStageFromJson(Map<String, dynamic> json) {
  return _TreatmentStage.fromJson(json);
}

/// @nodoc
mixin _$TreatmentStage {
  String get id => throw _privateConstructorUsedError;
  String get name => throw _privateConstructorUsedError;
  String get description => throw _privateConstructorUsedError;
  List<TreatmentItem> get items => throw _privateConstructorUsedError;
  double get stagePrice => throw _privateConstructorUsedError;
  int get estimatedDays => throw _privateConstructorUsedError;
  int get order => throw _privateConstructorUsedError;

  /// Serializes this TreatmentStage to a JSON map.
  Map<String, dynamic> toJson() => throw _privateConstructorUsedError;

  /// Create a copy of TreatmentStage
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  $TreatmentStageCopyWith<TreatmentStage> get copyWith =>
      throw _privateConstructorUsedError;
}

/// @nodoc
abstract class $TreatmentStageCopyWith<$Res> {
  factory $TreatmentStageCopyWith(
          TreatmentStage value, $Res Function(TreatmentStage) then) =
      _$TreatmentStageCopyWithImpl<$Res, TreatmentStage>;
  @useResult
  $Res call(
      {String id,
      String name,
      String description,
      List<TreatmentItem> items,
      double stagePrice,
      int estimatedDays,
      int order});
}

/// @nodoc
class _$TreatmentStageCopyWithImpl<$Res, $Val extends TreatmentStage>
    implements $TreatmentStageCopyWith<$Res> {
  _$TreatmentStageCopyWithImpl(this._value, this._then);

  // ignore: unused_field
  final $Val _value;
  // ignore: unused_field
  final $Res Function($Val) _then;

  /// Create a copy of TreatmentStage
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? name = null,
    Object? description = null,
    Object? items = null,
    Object? stagePrice = null,
    Object? estimatedDays = null,
    Object? order = null,
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
      items: null == items
          ? _value.items
          : items // ignore: cast_nullable_to_non_nullable
              as List<TreatmentItem>,
      stagePrice: null == stagePrice
          ? _value.stagePrice
          : stagePrice // ignore: cast_nullable_to_non_nullable
              as double,
      estimatedDays: null == estimatedDays
          ? _value.estimatedDays
          : estimatedDays // ignore: cast_nullable_to_non_nullable
              as int,
      order: null == order
          ? _value.order
          : order // ignore: cast_nullable_to_non_nullable
              as int,
    ) as $Val);
  }
}

/// @nodoc
abstract class _$$TreatmentStageImplCopyWith<$Res>
    implements $TreatmentStageCopyWith<$Res> {
  factory _$$TreatmentStageImplCopyWith(_$TreatmentStageImpl value,
          $Res Function(_$TreatmentStageImpl) then) =
      __$$TreatmentStageImplCopyWithImpl<$Res>;
  @override
  @useResult
  $Res call(
      {String id,
      String name,
      String description,
      List<TreatmentItem> items,
      double stagePrice,
      int estimatedDays,
      int order});
}

/// @nodoc
class __$$TreatmentStageImplCopyWithImpl<$Res>
    extends _$TreatmentStageCopyWithImpl<$Res, _$TreatmentStageImpl>
    implements _$$TreatmentStageImplCopyWith<$Res> {
  __$$TreatmentStageImplCopyWithImpl(
      _$TreatmentStageImpl _value, $Res Function(_$TreatmentStageImpl) _then)
      : super(_value, _then);

  /// Create a copy of TreatmentStage
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? name = null,
    Object? description = null,
    Object? items = null,
    Object? stagePrice = null,
    Object? estimatedDays = null,
    Object? order = null,
  }) {
    return _then(_$TreatmentStageImpl(
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
      items: null == items
          ? _value._items
          : items // ignore: cast_nullable_to_non_nullable
              as List<TreatmentItem>,
      stagePrice: null == stagePrice
          ? _value.stagePrice
          : stagePrice // ignore: cast_nullable_to_non_nullable
              as double,
      estimatedDays: null == estimatedDays
          ? _value.estimatedDays
          : estimatedDays // ignore: cast_nullable_to_non_nullable
              as int,
      order: null == order
          ? _value.order
          : order // ignore: cast_nullable_to_non_nullable
              as int,
    ));
  }
}

/// @nodoc
@JsonSerializable()
class _$TreatmentStageImpl implements _TreatmentStage {
  const _$TreatmentStageImpl(
      {required this.id,
      required this.name,
      required this.description,
      required final List<TreatmentItem> items,
      required this.stagePrice,
      required this.estimatedDays,
      this.order = 1})
      : _items = items;

  factory _$TreatmentStageImpl.fromJson(Map<String, dynamic> json) =>
      _$$TreatmentStageImplFromJson(json);

  @override
  final String id;
  @override
  final String name;
  @override
  final String description;
  final List<TreatmentItem> _items;
  @override
  List<TreatmentItem> get items {
    if (_items is EqualUnmodifiableListView) return _items;
    // ignore: implicit_dynamic_type
    return EqualUnmodifiableListView(_items);
  }

  @override
  final double stagePrice;
  @override
  final int estimatedDays;
  @override
  @JsonKey()
  final int order;

  @override
  String toString() {
    return 'TreatmentStage(id: $id, name: $name, description: $description, items: $items, stagePrice: $stagePrice, estimatedDays: $estimatedDays, order: $order)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$TreatmentStageImpl &&
            (identical(other.id, id) || other.id == id) &&
            (identical(other.name, name) || other.name == name) &&
            (identical(other.description, description) ||
                other.description == description) &&
            const DeepCollectionEquality().equals(other._items, _items) &&
            (identical(other.stagePrice, stagePrice) ||
                other.stagePrice == stagePrice) &&
            (identical(other.estimatedDays, estimatedDays) ||
                other.estimatedDays == estimatedDays) &&
            (identical(other.order, order) || other.order == order));
  }

  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  int get hashCode => Object.hash(
      runtimeType,
      id,
      name,
      description,
      const DeepCollectionEquality().hash(_items),
      stagePrice,
      estimatedDays,
      order);

  /// Create a copy of TreatmentStage
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  @pragma('vm:prefer-inline')
  _$$TreatmentStageImplCopyWith<_$TreatmentStageImpl> get copyWith =>
      __$$TreatmentStageImplCopyWithImpl<_$TreatmentStageImpl>(
          this, _$identity);

  @override
  Map<String, dynamic> toJson() {
    return _$$TreatmentStageImplToJson(
      this,
    );
  }
}

abstract class _TreatmentStage implements TreatmentStage {
  const factory _TreatmentStage(
      {required final String id,
      required final String name,
      required final String description,
      required final List<TreatmentItem> items,
      required final double stagePrice,
      required final int estimatedDays,
      final int order}) = _$TreatmentStageImpl;

  factory _TreatmentStage.fromJson(Map<String, dynamic> json) =
      _$TreatmentStageImpl.fromJson;

  @override
  String get id;
  @override
  String get name;
  @override
  String get description;
  @override
  List<TreatmentItem> get items;
  @override
  double get stagePrice;
  @override
  int get estimatedDays;
  @override
  int get order;

  /// Create a copy of TreatmentStage
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$TreatmentStageImplCopyWith<_$TreatmentStageImpl> get copyWith =>
      throw _privateConstructorUsedError;
}

TreatmentItem _$TreatmentItemFromJson(Map<String, dynamic> json) {
  return _TreatmentItem.fromJson(json);
}

/// @nodoc
mixin _$TreatmentItem {
  String get id => throw _privateConstructorUsedError;
  String get serviceId => throw _privateConstructorUsedError;
  @JsonKey(includeFromJson: false, includeToJson: false)
  Service? get service => throw _privateConstructorUsedError;
  int get quantity => throw _privateConstructorUsedError;
  double get unitPrice => throw _privateConstructorUsedError;
  double get totalPrice => throw _privateConstructorUsedError;
  List<String>? get toothNumbers => throw _privateConstructorUsedError;
  String? get notes => throw _privateConstructorUsedError;

  /// Serializes this TreatmentItem to a JSON map.
  Map<String, dynamic> toJson() => throw _privateConstructorUsedError;

  /// Create a copy of TreatmentItem
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  $TreatmentItemCopyWith<TreatmentItem> get copyWith =>
      throw _privateConstructorUsedError;
}

/// @nodoc
abstract class $TreatmentItemCopyWith<$Res> {
  factory $TreatmentItemCopyWith(
          TreatmentItem value, $Res Function(TreatmentItem) then) =
      _$TreatmentItemCopyWithImpl<$Res, TreatmentItem>;
  @useResult
  $Res call(
      {String id,
      String serviceId,
      @JsonKey(includeFromJson: false, includeToJson: false) Service? service,
      int quantity,
      double unitPrice,
      double totalPrice,
      List<String>? toothNumbers,
      String? notes});

  $ServiceCopyWith<$Res>? get service;
}

/// @nodoc
class _$TreatmentItemCopyWithImpl<$Res, $Val extends TreatmentItem>
    implements $TreatmentItemCopyWith<$Res> {
  _$TreatmentItemCopyWithImpl(this._value, this._then);

  // ignore: unused_field
  final $Val _value;
  // ignore: unused_field
  final $Res Function($Val) _then;

  /// Create a copy of TreatmentItem
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? serviceId = null,
    Object? service = freezed,
    Object? quantity = null,
    Object? unitPrice = null,
    Object? totalPrice = null,
    Object? toothNumbers = freezed,
    Object? notes = freezed,
  }) {
    return _then(_value.copyWith(
      id: null == id
          ? _value.id
          : id // ignore: cast_nullable_to_non_nullable
              as String,
      serviceId: null == serviceId
          ? _value.serviceId
          : serviceId // ignore: cast_nullable_to_non_nullable
              as String,
      service: freezed == service
          ? _value.service
          : service // ignore: cast_nullable_to_non_nullable
              as Service?,
      quantity: null == quantity
          ? _value.quantity
          : quantity // ignore: cast_nullable_to_non_nullable
              as int,
      unitPrice: null == unitPrice
          ? _value.unitPrice
          : unitPrice // ignore: cast_nullable_to_non_nullable
              as double,
      totalPrice: null == totalPrice
          ? _value.totalPrice
          : totalPrice // ignore: cast_nullable_to_non_nullable
              as double,
      toothNumbers: freezed == toothNumbers
          ? _value.toothNumbers
          : toothNumbers // ignore: cast_nullable_to_non_nullable
              as List<String>?,
      notes: freezed == notes
          ? _value.notes
          : notes // ignore: cast_nullable_to_non_nullable
              as String?,
    ) as $Val);
  }

  /// Create a copy of TreatmentItem
  /// with the given fields replaced by the non-null parameter values.
  @override
  @pragma('vm:prefer-inline')
  $ServiceCopyWith<$Res>? get service {
    if (_value.service == null) {
      return null;
    }

    return $ServiceCopyWith<$Res>(_value.service!, (value) {
      return _then(_value.copyWith(service: value) as $Val);
    });
  }
}

/// @nodoc
abstract class _$$TreatmentItemImplCopyWith<$Res>
    implements $TreatmentItemCopyWith<$Res> {
  factory _$$TreatmentItemImplCopyWith(
          _$TreatmentItemImpl value, $Res Function(_$TreatmentItemImpl) then) =
      __$$TreatmentItemImplCopyWithImpl<$Res>;
  @override
  @useResult
  $Res call(
      {String id,
      String serviceId,
      @JsonKey(includeFromJson: false, includeToJson: false) Service? service,
      int quantity,
      double unitPrice,
      double totalPrice,
      List<String>? toothNumbers,
      String? notes});

  @override
  $ServiceCopyWith<$Res>? get service;
}

/// @nodoc
class __$$TreatmentItemImplCopyWithImpl<$Res>
    extends _$TreatmentItemCopyWithImpl<$Res, _$TreatmentItemImpl>
    implements _$$TreatmentItemImplCopyWith<$Res> {
  __$$TreatmentItemImplCopyWithImpl(
      _$TreatmentItemImpl _value, $Res Function(_$TreatmentItemImpl) _then)
      : super(_value, _then);

  /// Create a copy of TreatmentItem
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? serviceId = null,
    Object? service = freezed,
    Object? quantity = null,
    Object? unitPrice = null,
    Object? totalPrice = null,
    Object? toothNumbers = freezed,
    Object? notes = freezed,
  }) {
    return _then(_$TreatmentItemImpl(
      id: null == id
          ? _value.id
          : id // ignore: cast_nullable_to_non_nullable
              as String,
      serviceId: null == serviceId
          ? _value.serviceId
          : serviceId // ignore: cast_nullable_to_non_nullable
              as String,
      service: freezed == service
          ? _value.service
          : service // ignore: cast_nullable_to_non_nullable
              as Service?,
      quantity: null == quantity
          ? _value.quantity
          : quantity // ignore: cast_nullable_to_non_nullable
              as int,
      unitPrice: null == unitPrice
          ? _value.unitPrice
          : unitPrice // ignore: cast_nullable_to_non_nullable
              as double,
      totalPrice: null == totalPrice
          ? _value.totalPrice
          : totalPrice // ignore: cast_nullable_to_non_nullable
              as double,
      toothNumbers: freezed == toothNumbers
          ? _value._toothNumbers
          : toothNumbers // ignore: cast_nullable_to_non_nullable
              as List<String>?,
      notes: freezed == notes
          ? _value.notes
          : notes // ignore: cast_nullable_to_non_nullable
              as String?,
    ));
  }
}

/// @nodoc
@JsonSerializable()
class _$TreatmentItemImpl implements _TreatmentItem {
  const _$TreatmentItemImpl(
      {required this.id,
      required this.serviceId,
      @JsonKey(includeFromJson: false, includeToJson: false) this.service,
      required this.quantity,
      required this.unitPrice,
      required this.totalPrice,
      final List<String>? toothNumbers,
      this.notes})
      : _toothNumbers = toothNumbers;

  factory _$TreatmentItemImpl.fromJson(Map<String, dynamic> json) =>
      _$$TreatmentItemImplFromJson(json);

  @override
  final String id;
  @override
  final String serviceId;
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  final Service? service;
  @override
  final int quantity;
  @override
  final double unitPrice;
  @override
  final double totalPrice;
  final List<String>? _toothNumbers;
  @override
  List<String>? get toothNumbers {
    final value = _toothNumbers;
    if (value == null) return null;
    if (_toothNumbers is EqualUnmodifiableListView) return _toothNumbers;
    // ignore: implicit_dynamic_type
    return EqualUnmodifiableListView(value);
  }

  @override
  final String? notes;

  @override
  String toString() {
    return 'TreatmentItem(id: $id, serviceId: $serviceId, service: $service, quantity: $quantity, unitPrice: $unitPrice, totalPrice: $totalPrice, toothNumbers: $toothNumbers, notes: $notes)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$TreatmentItemImpl &&
            (identical(other.id, id) || other.id == id) &&
            (identical(other.serviceId, serviceId) ||
                other.serviceId == serviceId) &&
            (identical(other.service, service) || other.service == service) &&
            (identical(other.quantity, quantity) ||
                other.quantity == quantity) &&
            (identical(other.unitPrice, unitPrice) ||
                other.unitPrice == unitPrice) &&
            (identical(other.totalPrice, totalPrice) ||
                other.totalPrice == totalPrice) &&
            const DeepCollectionEquality()
                .equals(other._toothNumbers, _toothNumbers) &&
            (identical(other.notes, notes) || other.notes == notes));
  }

  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  int get hashCode => Object.hash(
      runtimeType,
      id,
      serviceId,
      service,
      quantity,
      unitPrice,
      totalPrice,
      const DeepCollectionEquality().hash(_toothNumbers),
      notes);

  /// Create a copy of TreatmentItem
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  @pragma('vm:prefer-inline')
  _$$TreatmentItemImplCopyWith<_$TreatmentItemImpl> get copyWith =>
      __$$TreatmentItemImplCopyWithImpl<_$TreatmentItemImpl>(this, _$identity);

  @override
  Map<String, dynamic> toJson() {
    return _$$TreatmentItemImplToJson(
      this,
    );
  }
}

abstract class _TreatmentItem implements TreatmentItem {
  const factory _TreatmentItem(
      {required final String id,
      required final String serviceId,
      @JsonKey(includeFromJson: false, includeToJson: false)
      final Service? service,
      required final int quantity,
      required final double unitPrice,
      required final double totalPrice,
      final List<String>? toothNumbers,
      final String? notes}) = _$TreatmentItemImpl;

  factory _TreatmentItem.fromJson(Map<String, dynamic> json) =
      _$TreatmentItemImpl.fromJson;

  @override
  String get id;
  @override
  String get serviceId;
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  Service? get service;
  @override
  int get quantity;
  @override
  double get unitPrice;
  @override
  double get totalPrice;
  @override
  List<String>? get toothNumbers;
  @override
  String? get notes;

  /// Create a copy of TreatmentItem
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$TreatmentItemImplCopyWith<_$TreatmentItemImpl> get copyWith =>
      throw _privateConstructorUsedError;
}

PlanResponse _$PlanResponseFromJson(Map<String, dynamic> json) {
  return _PlanResponse.fromJson(json);
}

/// @nodoc
mixin _$PlanResponse {
  String get planId => throw _privateConstructorUsedError;
  PlanResponseType get responseType => throw _privateConstructorUsedError;
  String? get message => throw _privateConstructorUsedError;
  DateTime? get respondedAt => throw _privateConstructorUsedError;

  /// Serializes this PlanResponse to a JSON map.
  Map<String, dynamic> toJson() => throw _privateConstructorUsedError;

  /// Create a copy of PlanResponse
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  $PlanResponseCopyWith<PlanResponse> get copyWith =>
      throw _privateConstructorUsedError;
}

/// @nodoc
abstract class $PlanResponseCopyWith<$Res> {
  factory $PlanResponseCopyWith(
          PlanResponse value, $Res Function(PlanResponse) then) =
      _$PlanResponseCopyWithImpl<$Res, PlanResponse>;
  @useResult
  $Res call(
      {String planId,
      PlanResponseType responseType,
      String? message,
      DateTime? respondedAt});
}

/// @nodoc
class _$PlanResponseCopyWithImpl<$Res, $Val extends PlanResponse>
    implements $PlanResponseCopyWith<$Res> {
  _$PlanResponseCopyWithImpl(this._value, this._then);

  // ignore: unused_field
  final $Val _value;
  // ignore: unused_field
  final $Res Function($Val) _then;

  /// Create a copy of PlanResponse
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? planId = null,
    Object? responseType = null,
    Object? message = freezed,
    Object? respondedAt = freezed,
  }) {
    return _then(_value.copyWith(
      planId: null == planId
          ? _value.planId
          : planId // ignore: cast_nullable_to_non_nullable
              as String,
      responseType: null == responseType
          ? _value.responseType
          : responseType // ignore: cast_nullable_to_non_nullable
              as PlanResponseType,
      message: freezed == message
          ? _value.message
          : message // ignore: cast_nullable_to_non_nullable
              as String?,
      respondedAt: freezed == respondedAt
          ? _value.respondedAt
          : respondedAt // ignore: cast_nullable_to_non_nullable
              as DateTime?,
    ) as $Val);
  }
}

/// @nodoc
abstract class _$$PlanResponseImplCopyWith<$Res>
    implements $PlanResponseCopyWith<$Res> {
  factory _$$PlanResponseImplCopyWith(
          _$PlanResponseImpl value, $Res Function(_$PlanResponseImpl) then) =
      __$$PlanResponseImplCopyWithImpl<$Res>;
  @override
  @useResult
  $Res call(
      {String planId,
      PlanResponseType responseType,
      String? message,
      DateTime? respondedAt});
}

/// @nodoc
class __$$PlanResponseImplCopyWithImpl<$Res>
    extends _$PlanResponseCopyWithImpl<$Res, _$PlanResponseImpl>
    implements _$$PlanResponseImplCopyWith<$Res> {
  __$$PlanResponseImplCopyWithImpl(
      _$PlanResponseImpl _value, $Res Function(_$PlanResponseImpl) _then)
      : super(_value, _then);

  /// Create a copy of PlanResponse
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? planId = null,
    Object? responseType = null,
    Object? message = freezed,
    Object? respondedAt = freezed,
  }) {
    return _then(_$PlanResponseImpl(
      planId: null == planId
          ? _value.planId
          : planId // ignore: cast_nullable_to_non_nullable
              as String,
      responseType: null == responseType
          ? _value.responseType
          : responseType // ignore: cast_nullable_to_non_nullable
              as PlanResponseType,
      message: freezed == message
          ? _value.message
          : message // ignore: cast_nullable_to_non_nullable
              as String?,
      respondedAt: freezed == respondedAt
          ? _value.respondedAt
          : respondedAt // ignore: cast_nullable_to_non_nullable
              as DateTime?,
    ));
  }
}

/// @nodoc
@JsonSerializable()
class _$PlanResponseImpl implements _PlanResponse {
  const _$PlanResponseImpl(
      {required this.planId,
      required this.responseType,
      this.message,
      this.respondedAt});

  factory _$PlanResponseImpl.fromJson(Map<String, dynamic> json) =>
      _$$PlanResponseImplFromJson(json);

  @override
  final String planId;
  @override
  final PlanResponseType responseType;
  @override
  final String? message;
  @override
  final DateTime? respondedAt;

  @override
  String toString() {
    return 'PlanResponse(planId: $planId, responseType: $responseType, message: $message, respondedAt: $respondedAt)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$PlanResponseImpl &&
            (identical(other.planId, planId) || other.planId == planId) &&
            (identical(other.responseType, responseType) ||
                other.responseType == responseType) &&
            (identical(other.message, message) || other.message == message) &&
            (identical(other.respondedAt, respondedAt) ||
                other.respondedAt == respondedAt));
  }

  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  int get hashCode =>
      Object.hash(runtimeType, planId, responseType, message, respondedAt);

  /// Create a copy of PlanResponse
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  @pragma('vm:prefer-inline')
  _$$PlanResponseImplCopyWith<_$PlanResponseImpl> get copyWith =>
      __$$PlanResponseImplCopyWithImpl<_$PlanResponseImpl>(this, _$identity);

  @override
  Map<String, dynamic> toJson() {
    return _$$PlanResponseImplToJson(
      this,
    );
  }
}

abstract class _PlanResponse implements PlanResponse {
  const factory _PlanResponse(
      {required final String planId,
      required final PlanResponseType responseType,
      final String? message,
      final DateTime? respondedAt}) = _$PlanResponseImpl;

  factory _PlanResponse.fromJson(Map<String, dynamic> json) =
      _$PlanResponseImpl.fromJson;

  @override
  String get planId;
  @override
  PlanResponseType get responseType;
  @override
  String? get message;
  @override
  DateTime? get respondedAt;

  /// Create a copy of PlanResponse
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$PlanResponseImplCopyWith<_$PlanResponseImpl> get copyWith =>
      throw _privateConstructorUsedError;
}

Doctor _$DoctorFromJson(Map<String, dynamic> json) {
  return _Doctor.fromJson(json);
}

/// @nodoc
mixin _$Doctor {
  String get id => throw _privateConstructorUsedError;
  String get firstName => throw _privateConstructorUsedError;
  String get lastName => throw _privateConstructorUsedError;
  String get email => throw _privateConstructorUsedError;
  String? get phoneNumber => throw _privateConstructorUsedError;
  String? get profileImageUrl => throw _privateConstructorUsedError;
  String? get bio => throw _privateConstructorUsedError;
  String? get specialization => throw _privateConstructorUsedError;
  int? get experienceYears => throw _privateConstructorUsedError;
  List<String>? get languages => throw _privateConstructorUsedError;
  List<String>? get certifications => throw _privateConstructorUsedError;
  double get rating => throw _privateConstructorUsedError;
  int get reviewsCount => throw _privateConstructorUsedError;
  bool get isVerified => throw _privateConstructorUsedError;
  bool get isOnline => throw _privateConstructorUsedError;
  DateTime? get createdAt => throw _privateConstructorUsedError;
  DateTime? get updatedAt => throw _privateConstructorUsedError;

  /// Serializes this Doctor to a JSON map.
  Map<String, dynamic> toJson() => throw _privateConstructorUsedError;

  /// Create a copy of Doctor
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  $DoctorCopyWith<Doctor> get copyWith => throw _privateConstructorUsedError;
}

/// @nodoc
abstract class $DoctorCopyWith<$Res> {
  factory $DoctorCopyWith(Doctor value, $Res Function(Doctor) then) =
      _$DoctorCopyWithImpl<$Res, Doctor>;
  @useResult
  $Res call(
      {String id,
      String firstName,
      String lastName,
      String email,
      String? phoneNumber,
      String? profileImageUrl,
      String? bio,
      String? specialization,
      int? experienceYears,
      List<String>? languages,
      List<String>? certifications,
      double rating,
      int reviewsCount,
      bool isVerified,
      bool isOnline,
      DateTime? createdAt,
      DateTime? updatedAt});
}

/// @nodoc
class _$DoctorCopyWithImpl<$Res, $Val extends Doctor>
    implements $DoctorCopyWith<$Res> {
  _$DoctorCopyWithImpl(this._value, this._then);

  // ignore: unused_field
  final $Val _value;
  // ignore: unused_field
  final $Res Function($Val) _then;

  /// Create a copy of Doctor
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? firstName = null,
    Object? lastName = null,
    Object? email = null,
    Object? phoneNumber = freezed,
    Object? profileImageUrl = freezed,
    Object? bio = freezed,
    Object? specialization = freezed,
    Object? experienceYears = freezed,
    Object? languages = freezed,
    Object? certifications = freezed,
    Object? rating = null,
    Object? reviewsCount = null,
    Object? isVerified = null,
    Object? isOnline = null,
    Object? createdAt = freezed,
    Object? updatedAt = freezed,
  }) {
    return _then(_value.copyWith(
      id: null == id
          ? _value.id
          : id // ignore: cast_nullable_to_non_nullable
              as String,
      firstName: null == firstName
          ? _value.firstName
          : firstName // ignore: cast_nullable_to_non_nullable
              as String,
      lastName: null == lastName
          ? _value.lastName
          : lastName // ignore: cast_nullable_to_non_nullable
              as String,
      email: null == email
          ? _value.email
          : email // ignore: cast_nullable_to_non_nullable
              as String,
      phoneNumber: freezed == phoneNumber
          ? _value.phoneNumber
          : phoneNumber // ignore: cast_nullable_to_non_nullable
              as String?,
      profileImageUrl: freezed == profileImageUrl
          ? _value.profileImageUrl
          : profileImageUrl // ignore: cast_nullable_to_non_nullable
              as String?,
      bio: freezed == bio
          ? _value.bio
          : bio // ignore: cast_nullable_to_non_nullable
              as String?,
      specialization: freezed == specialization
          ? _value.specialization
          : specialization // ignore: cast_nullable_to_non_nullable
              as String?,
      experienceYears: freezed == experienceYears
          ? _value.experienceYears
          : experienceYears // ignore: cast_nullable_to_non_nullable
              as int?,
      languages: freezed == languages
          ? _value.languages
          : languages // ignore: cast_nullable_to_non_nullable
              as List<String>?,
      certifications: freezed == certifications
          ? _value.certifications
          : certifications // ignore: cast_nullable_to_non_nullable
              as List<String>?,
      rating: null == rating
          ? _value.rating
          : rating // ignore: cast_nullable_to_non_nullable
              as double,
      reviewsCount: null == reviewsCount
          ? _value.reviewsCount
          : reviewsCount // ignore: cast_nullable_to_non_nullable
              as int,
      isVerified: null == isVerified
          ? _value.isVerified
          : isVerified // ignore: cast_nullable_to_non_nullable
              as bool,
      isOnline: null == isOnline
          ? _value.isOnline
          : isOnline // ignore: cast_nullable_to_non_nullable
              as bool,
      createdAt: freezed == createdAt
          ? _value.createdAt
          : createdAt // ignore: cast_nullable_to_non_nullable
              as DateTime?,
      updatedAt: freezed == updatedAt
          ? _value.updatedAt
          : updatedAt // ignore: cast_nullable_to_non_nullable
              as DateTime?,
    ) as $Val);
  }
}

/// @nodoc
abstract class _$$DoctorImplCopyWith<$Res> implements $DoctorCopyWith<$Res> {
  factory _$$DoctorImplCopyWith(
          _$DoctorImpl value, $Res Function(_$DoctorImpl) then) =
      __$$DoctorImplCopyWithImpl<$Res>;
  @override
  @useResult
  $Res call(
      {String id,
      String firstName,
      String lastName,
      String email,
      String? phoneNumber,
      String? profileImageUrl,
      String? bio,
      String? specialization,
      int? experienceYears,
      List<String>? languages,
      List<String>? certifications,
      double rating,
      int reviewsCount,
      bool isVerified,
      bool isOnline,
      DateTime? createdAt,
      DateTime? updatedAt});
}

/// @nodoc
class __$$DoctorImplCopyWithImpl<$Res>
    extends _$DoctorCopyWithImpl<$Res, _$DoctorImpl>
    implements _$$DoctorImplCopyWith<$Res> {
  __$$DoctorImplCopyWithImpl(
      _$DoctorImpl _value, $Res Function(_$DoctorImpl) _then)
      : super(_value, _then);

  /// Create a copy of Doctor
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? firstName = null,
    Object? lastName = null,
    Object? email = null,
    Object? phoneNumber = freezed,
    Object? profileImageUrl = freezed,
    Object? bio = freezed,
    Object? specialization = freezed,
    Object? experienceYears = freezed,
    Object? languages = freezed,
    Object? certifications = freezed,
    Object? rating = null,
    Object? reviewsCount = null,
    Object? isVerified = null,
    Object? isOnline = null,
    Object? createdAt = freezed,
    Object? updatedAt = freezed,
  }) {
    return _then(_$DoctorImpl(
      id: null == id
          ? _value.id
          : id // ignore: cast_nullable_to_non_nullable
              as String,
      firstName: null == firstName
          ? _value.firstName
          : firstName // ignore: cast_nullable_to_non_nullable
              as String,
      lastName: null == lastName
          ? _value.lastName
          : lastName // ignore: cast_nullable_to_non_nullable
              as String,
      email: null == email
          ? _value.email
          : email // ignore: cast_nullable_to_non_nullable
              as String,
      phoneNumber: freezed == phoneNumber
          ? _value.phoneNumber
          : phoneNumber // ignore: cast_nullable_to_non_nullable
              as String?,
      profileImageUrl: freezed == profileImageUrl
          ? _value.profileImageUrl
          : profileImageUrl // ignore: cast_nullable_to_non_nullable
              as String?,
      bio: freezed == bio
          ? _value.bio
          : bio // ignore: cast_nullable_to_non_nullable
              as String?,
      specialization: freezed == specialization
          ? _value.specialization
          : specialization // ignore: cast_nullable_to_non_nullable
              as String?,
      experienceYears: freezed == experienceYears
          ? _value.experienceYears
          : experienceYears // ignore: cast_nullable_to_non_nullable
              as int?,
      languages: freezed == languages
          ? _value._languages
          : languages // ignore: cast_nullable_to_non_nullable
              as List<String>?,
      certifications: freezed == certifications
          ? _value._certifications
          : certifications // ignore: cast_nullable_to_non_nullable
              as List<String>?,
      rating: null == rating
          ? _value.rating
          : rating // ignore: cast_nullable_to_non_nullable
              as double,
      reviewsCount: null == reviewsCount
          ? _value.reviewsCount
          : reviewsCount // ignore: cast_nullable_to_non_nullable
              as int,
      isVerified: null == isVerified
          ? _value.isVerified
          : isVerified // ignore: cast_nullable_to_non_nullable
              as bool,
      isOnline: null == isOnline
          ? _value.isOnline
          : isOnline // ignore: cast_nullable_to_non_nullable
              as bool,
      createdAt: freezed == createdAt
          ? _value.createdAt
          : createdAt // ignore: cast_nullable_to_non_nullable
              as DateTime?,
      updatedAt: freezed == updatedAt
          ? _value.updatedAt
          : updatedAt // ignore: cast_nullable_to_non_nullable
              as DateTime?,
    ));
  }
}

/// @nodoc
@JsonSerializable()
class _$DoctorImpl implements _Doctor {
  const _$DoctorImpl(
      {required this.id,
      required this.firstName,
      required this.lastName,
      required this.email,
      this.phoneNumber,
      this.profileImageUrl,
      this.bio,
      this.specialization,
      this.experienceYears,
      final List<String>? languages,
      final List<String>? certifications,
      this.rating = 0.0,
      this.reviewsCount = 0,
      this.isVerified = false,
      this.isOnline = false,
      this.createdAt,
      this.updatedAt})
      : _languages = languages,
        _certifications = certifications;

  factory _$DoctorImpl.fromJson(Map<String, dynamic> json) =>
      _$$DoctorImplFromJson(json);

  @override
  final String id;
  @override
  final String firstName;
  @override
  final String lastName;
  @override
  final String email;
  @override
  final String? phoneNumber;
  @override
  final String? profileImageUrl;
  @override
  final String? bio;
  @override
  final String? specialization;
  @override
  final int? experienceYears;
  final List<String>? _languages;
  @override
  List<String>? get languages {
    final value = _languages;
    if (value == null) return null;
    if (_languages is EqualUnmodifiableListView) return _languages;
    // ignore: implicit_dynamic_type
    return EqualUnmodifiableListView(value);
  }

  final List<String>? _certifications;
  @override
  List<String>? get certifications {
    final value = _certifications;
    if (value == null) return null;
    if (_certifications is EqualUnmodifiableListView) return _certifications;
    // ignore: implicit_dynamic_type
    return EqualUnmodifiableListView(value);
  }

  @override
  @JsonKey()
  final double rating;
  @override
  @JsonKey()
  final int reviewsCount;
  @override
  @JsonKey()
  final bool isVerified;
  @override
  @JsonKey()
  final bool isOnline;
  @override
  final DateTime? createdAt;
  @override
  final DateTime? updatedAt;

  @override
  String toString() {
    return 'Doctor(id: $id, firstName: $firstName, lastName: $lastName, email: $email, phoneNumber: $phoneNumber, profileImageUrl: $profileImageUrl, bio: $bio, specialization: $specialization, experienceYears: $experienceYears, languages: $languages, certifications: $certifications, rating: $rating, reviewsCount: $reviewsCount, isVerified: $isVerified, isOnline: $isOnline, createdAt: $createdAt, updatedAt: $updatedAt)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$DoctorImpl &&
            (identical(other.id, id) || other.id == id) &&
            (identical(other.firstName, firstName) ||
                other.firstName == firstName) &&
            (identical(other.lastName, lastName) ||
                other.lastName == lastName) &&
            (identical(other.email, email) || other.email == email) &&
            (identical(other.phoneNumber, phoneNumber) ||
                other.phoneNumber == phoneNumber) &&
            (identical(other.profileImageUrl, profileImageUrl) ||
                other.profileImageUrl == profileImageUrl) &&
            (identical(other.bio, bio) || other.bio == bio) &&
            (identical(other.specialization, specialization) ||
                other.specialization == specialization) &&
            (identical(other.experienceYears, experienceYears) ||
                other.experienceYears == experienceYears) &&
            const DeepCollectionEquality()
                .equals(other._languages, _languages) &&
            const DeepCollectionEquality()
                .equals(other._certifications, _certifications) &&
            (identical(other.rating, rating) || other.rating == rating) &&
            (identical(other.reviewsCount, reviewsCount) ||
                other.reviewsCount == reviewsCount) &&
            (identical(other.isVerified, isVerified) ||
                other.isVerified == isVerified) &&
            (identical(other.isOnline, isOnline) ||
                other.isOnline == isOnline) &&
            (identical(other.createdAt, createdAt) ||
                other.createdAt == createdAt) &&
            (identical(other.updatedAt, updatedAt) ||
                other.updatedAt == updatedAt));
  }

  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  int get hashCode => Object.hash(
      runtimeType,
      id,
      firstName,
      lastName,
      email,
      phoneNumber,
      profileImageUrl,
      bio,
      specialization,
      experienceYears,
      const DeepCollectionEquality().hash(_languages),
      const DeepCollectionEquality().hash(_certifications),
      rating,
      reviewsCount,
      isVerified,
      isOnline,
      createdAt,
      updatedAt);

  /// Create a copy of Doctor
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  @pragma('vm:prefer-inline')
  _$$DoctorImplCopyWith<_$DoctorImpl> get copyWith =>
      __$$DoctorImplCopyWithImpl<_$DoctorImpl>(this, _$identity);

  @override
  Map<String, dynamic> toJson() {
    return _$$DoctorImplToJson(
      this,
    );
  }
}

abstract class _Doctor implements Doctor {
  const factory _Doctor(
      {required final String id,
      required final String firstName,
      required final String lastName,
      required final String email,
      final String? phoneNumber,
      final String? profileImageUrl,
      final String? bio,
      final String? specialization,
      final int? experienceYears,
      final List<String>? languages,
      final List<String>? certifications,
      final double rating,
      final int reviewsCount,
      final bool isVerified,
      final bool isOnline,
      final DateTime? createdAt,
      final DateTime? updatedAt}) = _$DoctorImpl;

  factory _Doctor.fromJson(Map<String, dynamic> json) = _$DoctorImpl.fromJson;

  @override
  String get id;
  @override
  String get firstName;
  @override
  String get lastName;
  @override
  String get email;
  @override
  String? get phoneNumber;
  @override
  String? get profileImageUrl;
  @override
  String? get bio;
  @override
  String? get specialization;
  @override
  int? get experienceYears;
  @override
  List<String>? get languages;
  @override
  List<String>? get certifications;
  @override
  double get rating;
  @override
  int get reviewsCount;
  @override
  bool get isVerified;
  @override
  bool get isOnline;
  @override
  DateTime? get createdAt;
  @override
  DateTime? get updatedAt;

  /// Create a copy of Doctor
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$DoctorImplCopyWith<_$DoctorImpl> get copyWith =>
      throw _privateConstructorUsedError;
}

Service _$ServiceFromJson(Map<String, dynamic> json) {
  return _Service.fromJson(json);
}

/// @nodoc
mixin _$Service {
  String get id => throw _privateConstructorUsedError;
  String get name => throw _privateConstructorUsedError;
  String get description => throw _privateConstructorUsedError;
  String get category => throw _privateConstructorUsedError;
  int? get durationMinutes => throw _privateConstructorUsedError;
  double? get price => throw _privateConstructorUsedError;
  String? get currency => throw _privateConstructorUsedError;
  bool get isToothSpecific => throw _privateConstructorUsedError;
  DateTime? get createdAt => throw _privateConstructorUsedError;
  DateTime? get updatedAt => throw _privateConstructorUsedError;

  /// Serializes this Service to a JSON map.
  Map<String, dynamic> toJson() => throw _privateConstructorUsedError;

  /// Create a copy of Service
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  $ServiceCopyWith<Service> get copyWith => throw _privateConstructorUsedError;
}

/// @nodoc
abstract class $ServiceCopyWith<$Res> {
  factory $ServiceCopyWith(Service value, $Res Function(Service) then) =
      _$ServiceCopyWithImpl<$Res, Service>;
  @useResult
  $Res call(
      {String id,
      String name,
      String description,
      String category,
      int? durationMinutes,
      double? price,
      String? currency,
      bool isToothSpecific,
      DateTime? createdAt,
      DateTime? updatedAt});
}

/// @nodoc
class _$ServiceCopyWithImpl<$Res, $Val extends Service>
    implements $ServiceCopyWith<$Res> {
  _$ServiceCopyWithImpl(this._value, this._then);

  // ignore: unused_field
  final $Val _value;
  // ignore: unused_field
  final $Res Function($Val) _then;

  /// Create a copy of Service
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? name = null,
    Object? description = null,
    Object? category = null,
    Object? durationMinutes = freezed,
    Object? price = freezed,
    Object? currency = freezed,
    Object? isToothSpecific = null,
    Object? createdAt = freezed,
    Object? updatedAt = freezed,
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
      category: null == category
          ? _value.category
          : category // ignore: cast_nullable_to_non_nullable
              as String,
      durationMinutes: freezed == durationMinutes
          ? _value.durationMinutes
          : durationMinutes // ignore: cast_nullable_to_non_nullable
              as int?,
      price: freezed == price
          ? _value.price
          : price // ignore: cast_nullable_to_non_nullable
              as double?,
      currency: freezed == currency
          ? _value.currency
          : currency // ignore: cast_nullable_to_non_nullable
              as String?,
      isToothSpecific: null == isToothSpecific
          ? _value.isToothSpecific
          : isToothSpecific // ignore: cast_nullable_to_non_nullable
              as bool,
      createdAt: freezed == createdAt
          ? _value.createdAt
          : createdAt // ignore: cast_nullable_to_non_nullable
              as DateTime?,
      updatedAt: freezed == updatedAt
          ? _value.updatedAt
          : updatedAt // ignore: cast_nullable_to_non_nullable
              as DateTime?,
    ) as $Val);
  }
}

/// @nodoc
abstract class _$$ServiceImplCopyWith<$Res> implements $ServiceCopyWith<$Res> {
  factory _$$ServiceImplCopyWith(
          _$ServiceImpl value, $Res Function(_$ServiceImpl) then) =
      __$$ServiceImplCopyWithImpl<$Res>;
  @override
  @useResult
  $Res call(
      {String id,
      String name,
      String description,
      String category,
      int? durationMinutes,
      double? price,
      String? currency,
      bool isToothSpecific,
      DateTime? createdAt,
      DateTime? updatedAt});
}

/// @nodoc
class __$$ServiceImplCopyWithImpl<$Res>
    extends _$ServiceCopyWithImpl<$Res, _$ServiceImpl>
    implements _$$ServiceImplCopyWith<$Res> {
  __$$ServiceImplCopyWithImpl(
      _$ServiceImpl _value, $Res Function(_$ServiceImpl) _then)
      : super(_value, _then);

  /// Create a copy of Service
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? name = null,
    Object? description = null,
    Object? category = null,
    Object? durationMinutes = freezed,
    Object? price = freezed,
    Object? currency = freezed,
    Object? isToothSpecific = null,
    Object? createdAt = freezed,
    Object? updatedAt = freezed,
  }) {
    return _then(_$ServiceImpl(
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
      category: null == category
          ? _value.category
          : category // ignore: cast_nullable_to_non_nullable
              as String,
      durationMinutes: freezed == durationMinutes
          ? _value.durationMinutes
          : durationMinutes // ignore: cast_nullable_to_non_nullable
              as int?,
      price: freezed == price
          ? _value.price
          : price // ignore: cast_nullable_to_non_nullable
              as double?,
      currency: freezed == currency
          ? _value.currency
          : currency // ignore: cast_nullable_to_non_nullable
              as String?,
      isToothSpecific: null == isToothSpecific
          ? _value.isToothSpecific
          : isToothSpecific // ignore: cast_nullable_to_non_nullable
              as bool,
      createdAt: freezed == createdAt
          ? _value.createdAt
          : createdAt // ignore: cast_nullable_to_non_nullable
              as DateTime?,
      updatedAt: freezed == updatedAt
          ? _value.updatedAt
          : updatedAt // ignore: cast_nullable_to_non_nullable
              as DateTime?,
    ));
  }
}

/// @nodoc
@JsonSerializable()
class _$ServiceImpl implements _Service {
  const _$ServiceImpl(
      {required this.id,
      required this.name,
      required this.description,
      required this.category,
      this.durationMinutes,
      this.price,
      this.currency,
      this.isToothSpecific = false,
      this.createdAt,
      this.updatedAt});

  factory _$ServiceImpl.fromJson(Map<String, dynamic> json) =>
      _$$ServiceImplFromJson(json);

  @override
  final String id;
  @override
  final String name;
  @override
  final String description;
  @override
  final String category;
  @override
  final int? durationMinutes;
  @override
  final double? price;
  @override
  final String? currency;
  @override
  @JsonKey()
  final bool isToothSpecific;
  @override
  final DateTime? createdAt;
  @override
  final DateTime? updatedAt;

  @override
  String toString() {
    return 'Service(id: $id, name: $name, description: $description, category: $category, durationMinutes: $durationMinutes, price: $price, currency: $currency, isToothSpecific: $isToothSpecific, createdAt: $createdAt, updatedAt: $updatedAt)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$ServiceImpl &&
            (identical(other.id, id) || other.id == id) &&
            (identical(other.name, name) || other.name == name) &&
            (identical(other.description, description) ||
                other.description == description) &&
            (identical(other.category, category) ||
                other.category == category) &&
            (identical(other.durationMinutes, durationMinutes) ||
                other.durationMinutes == durationMinutes) &&
            (identical(other.price, price) || other.price == price) &&
            (identical(other.currency, currency) ||
                other.currency == currency) &&
            (identical(other.isToothSpecific, isToothSpecific) ||
                other.isToothSpecific == isToothSpecific) &&
            (identical(other.createdAt, createdAt) ||
                other.createdAt == createdAt) &&
            (identical(other.updatedAt, updatedAt) ||
                other.updatedAt == updatedAt));
  }

  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  int get hashCode => Object.hash(runtimeType, id, name, description, category,
      durationMinutes, price, currency, isToothSpecific, createdAt, updatedAt);

  /// Create a copy of Service
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  @pragma('vm:prefer-inline')
  _$$ServiceImplCopyWith<_$ServiceImpl> get copyWith =>
      __$$ServiceImplCopyWithImpl<_$ServiceImpl>(this, _$identity);

  @override
  Map<String, dynamic> toJson() {
    return _$$ServiceImplToJson(
      this,
    );
  }
}

abstract class _Service implements Service {
  const factory _Service(
      {required final String id,
      required final String name,
      required final String description,
      required final String category,
      final int? durationMinutes,
      final double? price,
      final String? currency,
      final bool isToothSpecific,
      final DateTime? createdAt,
      final DateTime? updatedAt}) = _$ServiceImpl;

  factory _Service.fromJson(Map<String, dynamic> json) = _$ServiceImpl.fromJson;

  @override
  String get id;
  @override
  String get name;
  @override
  String get description;
  @override
  String get category;
  @override
  int? get durationMinutes;
  @override
  double? get price;
  @override
  String? get currency;
  @override
  bool get isToothSpecific;
  @override
  DateTime? get createdAt;
  @override
  DateTime? get updatedAt;

  /// Create a copy of Service
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$ServiceImplCopyWith<_$ServiceImpl> get copyWith =>
      throw _privateConstructorUsedError;
}
