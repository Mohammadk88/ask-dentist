// coverage:ignore-file
// GENERATED CODE - DO NOT MODIFY BY HAND
// ignore_for_file: type=lint
// ignore_for_file: unused_element, deprecated_member_use, deprecated_member_use_from_same_package, use_function_type_syntax_for_parameters, unnecessary_const, avoid_init_to_null, invalid_override_different_default_values_named, prefer_expression_function_bodies, annotate_overrides, invalid_annotation_target, unnecessary_question_mark

part of 'case.dart';

// **************************************************************************
// FreezedGenerator
// **************************************************************************

T _$identity<T>(T value) => value;

final _privateConstructorUsedError = UnsupportedError(
    'It seems like you constructed your class using `MyClass._()`. This constructor is only meant to be used by freezed and you are not supposed to need it nor use it.\nPlease check the documentation here for more information: https://github.com/rrousselGit/freezed#adding-getters-and-methods-to-our-models');

PatientCase _$PatientCaseFromJson(Map<String, dynamic> json) {
  return _PatientCase.fromJson(json);
}

/// @nodoc
mixin _$PatientCase {
  String get id => throw _privateConstructorUsedError;
  String get patientId => throw _privateConstructorUsedError;
  CaseTypeEnum get type => throw _privateConstructorUsedError;
  String get description => throw _privateConstructorUsedError;
  List<String> get imageUrls => throw _privateConstructorUsedError;
  CaseStatus get status => throw _privateConstructorUsedError;
  List<String>? get doctorIds => throw _privateConstructorUsedError;
  DateTime? get createdAt => throw _privateConstructorUsedError;
  DateTime? get updatedAt => throw _privateConstructorUsedError;

  /// Serializes this PatientCase to a JSON map.
  Map<String, dynamic> toJson() => throw _privateConstructorUsedError;

  /// Create a copy of PatientCase
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  $PatientCaseCopyWith<PatientCase> get copyWith =>
      throw _privateConstructorUsedError;
}

/// @nodoc
abstract class $PatientCaseCopyWith<$Res> {
  factory $PatientCaseCopyWith(
          PatientCase value, $Res Function(PatientCase) then) =
      _$PatientCaseCopyWithImpl<$Res, PatientCase>;
  @useResult
  $Res call(
      {String id,
      String patientId,
      CaseTypeEnum type,
      String description,
      List<String> imageUrls,
      CaseStatus status,
      List<String>? doctorIds,
      DateTime? createdAt,
      DateTime? updatedAt});
}

/// @nodoc
class _$PatientCaseCopyWithImpl<$Res, $Val extends PatientCase>
    implements $PatientCaseCopyWith<$Res> {
  _$PatientCaseCopyWithImpl(this._value, this._then);

  // ignore: unused_field
  final $Val _value;
  // ignore: unused_field
  final $Res Function($Val) _then;

  /// Create a copy of PatientCase
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? patientId = null,
    Object? type = null,
    Object? description = null,
    Object? imageUrls = null,
    Object? status = null,
    Object? doctorIds = freezed,
    Object? createdAt = freezed,
    Object? updatedAt = freezed,
  }) {
    return _then(_value.copyWith(
      id: null == id
          ? _value.id
          : id // ignore: cast_nullable_to_non_nullable
              as String,
      patientId: null == patientId
          ? _value.patientId
          : patientId // ignore: cast_nullable_to_non_nullable
              as String,
      type: null == type
          ? _value.type
          : type // ignore: cast_nullable_to_non_nullable
              as CaseTypeEnum,
      description: null == description
          ? _value.description
          : description // ignore: cast_nullable_to_non_nullable
              as String,
      imageUrls: null == imageUrls
          ? _value.imageUrls
          : imageUrls // ignore: cast_nullable_to_non_nullable
              as List<String>,
      status: null == status
          ? _value.status
          : status // ignore: cast_nullable_to_non_nullable
              as CaseStatus,
      doctorIds: freezed == doctorIds
          ? _value.doctorIds
          : doctorIds // ignore: cast_nullable_to_non_nullable
              as List<String>?,
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
abstract class _$$PatientCaseImplCopyWith<$Res>
    implements $PatientCaseCopyWith<$Res> {
  factory _$$PatientCaseImplCopyWith(
          _$PatientCaseImpl value, $Res Function(_$PatientCaseImpl) then) =
      __$$PatientCaseImplCopyWithImpl<$Res>;
  @override
  @useResult
  $Res call(
      {String id,
      String patientId,
      CaseTypeEnum type,
      String description,
      List<String> imageUrls,
      CaseStatus status,
      List<String>? doctorIds,
      DateTime? createdAt,
      DateTime? updatedAt});
}

/// @nodoc
class __$$PatientCaseImplCopyWithImpl<$Res>
    extends _$PatientCaseCopyWithImpl<$Res, _$PatientCaseImpl>
    implements _$$PatientCaseImplCopyWith<$Res> {
  __$$PatientCaseImplCopyWithImpl(
      _$PatientCaseImpl _value, $Res Function(_$PatientCaseImpl) _then)
      : super(_value, _then);

  /// Create a copy of PatientCase
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? patientId = null,
    Object? type = null,
    Object? description = null,
    Object? imageUrls = null,
    Object? status = null,
    Object? doctorIds = freezed,
    Object? createdAt = freezed,
    Object? updatedAt = freezed,
  }) {
    return _then(_$PatientCaseImpl(
      id: null == id
          ? _value.id
          : id // ignore: cast_nullable_to_non_nullable
              as String,
      patientId: null == patientId
          ? _value.patientId
          : patientId // ignore: cast_nullable_to_non_nullable
              as String,
      type: null == type
          ? _value.type
          : type // ignore: cast_nullable_to_non_nullable
              as CaseTypeEnum,
      description: null == description
          ? _value.description
          : description // ignore: cast_nullable_to_non_nullable
              as String,
      imageUrls: null == imageUrls
          ? _value._imageUrls
          : imageUrls // ignore: cast_nullable_to_non_nullable
              as List<String>,
      status: null == status
          ? _value.status
          : status // ignore: cast_nullable_to_non_nullable
              as CaseStatus,
      doctorIds: freezed == doctorIds
          ? _value._doctorIds
          : doctorIds // ignore: cast_nullable_to_non_nullable
              as List<String>?,
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
class _$PatientCaseImpl implements _PatientCase {
  const _$PatientCaseImpl(
      {required this.id,
      required this.patientId,
      required this.type,
      required this.description,
      required final List<String> imageUrls,
      this.status = CaseStatus.pending,
      final List<String>? doctorIds,
      this.createdAt,
      this.updatedAt})
      : _imageUrls = imageUrls,
        _doctorIds = doctorIds;

  factory _$PatientCaseImpl.fromJson(Map<String, dynamic> json) =>
      _$$PatientCaseImplFromJson(json);

  @override
  final String id;
  @override
  final String patientId;
  @override
  final CaseTypeEnum type;
  @override
  final String description;
  final List<String> _imageUrls;
  @override
  List<String> get imageUrls {
    if (_imageUrls is EqualUnmodifiableListView) return _imageUrls;
    // ignore: implicit_dynamic_type
    return EqualUnmodifiableListView(_imageUrls);
  }

  @override
  @JsonKey()
  final CaseStatus status;
  final List<String>? _doctorIds;
  @override
  List<String>? get doctorIds {
    final value = _doctorIds;
    if (value == null) return null;
    if (_doctorIds is EqualUnmodifiableListView) return _doctorIds;
    // ignore: implicit_dynamic_type
    return EqualUnmodifiableListView(value);
  }

  @override
  final DateTime? createdAt;
  @override
  final DateTime? updatedAt;

  @override
  String toString() {
    return 'PatientCase(id: $id, patientId: $patientId, type: $type, description: $description, imageUrls: $imageUrls, status: $status, doctorIds: $doctorIds, createdAt: $createdAt, updatedAt: $updatedAt)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$PatientCaseImpl &&
            (identical(other.id, id) || other.id == id) &&
            (identical(other.patientId, patientId) ||
                other.patientId == patientId) &&
            (identical(other.type, type) || other.type == type) &&
            (identical(other.description, description) ||
                other.description == description) &&
            const DeepCollectionEquality()
                .equals(other._imageUrls, _imageUrls) &&
            (identical(other.status, status) || other.status == status) &&
            const DeepCollectionEquality()
                .equals(other._doctorIds, _doctorIds) &&
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
      patientId,
      type,
      description,
      const DeepCollectionEquality().hash(_imageUrls),
      status,
      const DeepCollectionEquality().hash(_doctorIds),
      createdAt,
      updatedAt);

  /// Create a copy of PatientCase
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  @pragma('vm:prefer-inline')
  _$$PatientCaseImplCopyWith<_$PatientCaseImpl> get copyWith =>
      __$$PatientCaseImplCopyWithImpl<_$PatientCaseImpl>(this, _$identity);

  @override
  Map<String, dynamic> toJson() {
    return _$$PatientCaseImplToJson(
      this,
    );
  }
}

abstract class _PatientCase implements PatientCase {
  const factory _PatientCase(
      {required final String id,
      required final String patientId,
      required final CaseTypeEnum type,
      required final String description,
      required final List<String> imageUrls,
      final CaseStatus status,
      final List<String>? doctorIds,
      final DateTime? createdAt,
      final DateTime? updatedAt}) = _$PatientCaseImpl;

  factory _PatientCase.fromJson(Map<String, dynamic> json) =
      _$PatientCaseImpl.fromJson;

  @override
  String get id;
  @override
  String get patientId;
  @override
  CaseTypeEnum get type;
  @override
  String get description;
  @override
  List<String> get imageUrls;
  @override
  CaseStatus get status;
  @override
  List<String>? get doctorIds;
  @override
  DateTime? get createdAt;
  @override
  DateTime? get updatedAt;

  /// Create a copy of PatientCase
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$PatientCaseImplCopyWith<_$PatientCaseImpl> get copyWith =>
      throw _privateConstructorUsedError;
}

SubmitCaseRequest _$SubmitCaseRequestFromJson(Map<String, dynamic> json) {
  return _SubmitCaseRequest.fromJson(json);
}

/// @nodoc
mixin _$SubmitCaseRequest {
  CaseTypeEnum get type => throw _privateConstructorUsedError;
  String get description => throw _privateConstructorUsedError;
  List<String> get imageUrls => throw _privateConstructorUsedError;
  List<String>? get preferredDoctorIds => throw _privateConstructorUsedError;
  List<String>? get medicalHistory => throw _privateConstructorUsedError;

  /// Serializes this SubmitCaseRequest to a JSON map.
  Map<String, dynamic> toJson() => throw _privateConstructorUsedError;

  /// Create a copy of SubmitCaseRequest
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  $SubmitCaseRequestCopyWith<SubmitCaseRequest> get copyWith =>
      throw _privateConstructorUsedError;
}

/// @nodoc
abstract class $SubmitCaseRequestCopyWith<$Res> {
  factory $SubmitCaseRequestCopyWith(
          SubmitCaseRequest value, $Res Function(SubmitCaseRequest) then) =
      _$SubmitCaseRequestCopyWithImpl<$Res, SubmitCaseRequest>;
  @useResult
  $Res call(
      {CaseTypeEnum type,
      String description,
      List<String> imageUrls,
      List<String>? preferredDoctorIds,
      List<String>? medicalHistory});
}

/// @nodoc
class _$SubmitCaseRequestCopyWithImpl<$Res, $Val extends SubmitCaseRequest>
    implements $SubmitCaseRequestCopyWith<$Res> {
  _$SubmitCaseRequestCopyWithImpl(this._value, this._then);

  // ignore: unused_field
  final $Val _value;
  // ignore: unused_field
  final $Res Function($Val) _then;

  /// Create a copy of SubmitCaseRequest
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? type = null,
    Object? description = null,
    Object? imageUrls = null,
    Object? preferredDoctorIds = freezed,
    Object? medicalHistory = freezed,
  }) {
    return _then(_value.copyWith(
      type: null == type
          ? _value.type
          : type // ignore: cast_nullable_to_non_nullable
              as CaseTypeEnum,
      description: null == description
          ? _value.description
          : description // ignore: cast_nullable_to_non_nullable
              as String,
      imageUrls: null == imageUrls
          ? _value.imageUrls
          : imageUrls // ignore: cast_nullable_to_non_nullable
              as List<String>,
      preferredDoctorIds: freezed == preferredDoctorIds
          ? _value.preferredDoctorIds
          : preferredDoctorIds // ignore: cast_nullable_to_non_nullable
              as List<String>?,
      medicalHistory: freezed == medicalHistory
          ? _value.medicalHistory
          : medicalHistory // ignore: cast_nullable_to_non_nullable
              as List<String>?,
    ) as $Val);
  }
}

/// @nodoc
abstract class _$$SubmitCaseRequestImplCopyWith<$Res>
    implements $SubmitCaseRequestCopyWith<$Res> {
  factory _$$SubmitCaseRequestImplCopyWith(_$SubmitCaseRequestImpl value,
          $Res Function(_$SubmitCaseRequestImpl) then) =
      __$$SubmitCaseRequestImplCopyWithImpl<$Res>;
  @override
  @useResult
  $Res call(
      {CaseTypeEnum type,
      String description,
      List<String> imageUrls,
      List<String>? preferredDoctorIds,
      List<String>? medicalHistory});
}

/// @nodoc
class __$$SubmitCaseRequestImplCopyWithImpl<$Res>
    extends _$SubmitCaseRequestCopyWithImpl<$Res, _$SubmitCaseRequestImpl>
    implements _$$SubmitCaseRequestImplCopyWith<$Res> {
  __$$SubmitCaseRequestImplCopyWithImpl(_$SubmitCaseRequestImpl _value,
      $Res Function(_$SubmitCaseRequestImpl) _then)
      : super(_value, _then);

  /// Create a copy of SubmitCaseRequest
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? type = null,
    Object? description = null,
    Object? imageUrls = null,
    Object? preferredDoctorIds = freezed,
    Object? medicalHistory = freezed,
  }) {
    return _then(_$SubmitCaseRequestImpl(
      type: null == type
          ? _value.type
          : type // ignore: cast_nullable_to_non_nullable
              as CaseTypeEnum,
      description: null == description
          ? _value.description
          : description // ignore: cast_nullable_to_non_nullable
              as String,
      imageUrls: null == imageUrls
          ? _value._imageUrls
          : imageUrls // ignore: cast_nullable_to_non_nullable
              as List<String>,
      preferredDoctorIds: freezed == preferredDoctorIds
          ? _value._preferredDoctorIds
          : preferredDoctorIds // ignore: cast_nullable_to_non_nullable
              as List<String>?,
      medicalHistory: freezed == medicalHistory
          ? _value._medicalHistory
          : medicalHistory // ignore: cast_nullable_to_non_nullable
              as List<String>?,
    ));
  }
}

/// @nodoc
@JsonSerializable()
class _$SubmitCaseRequestImpl implements _SubmitCaseRequest {
  const _$SubmitCaseRequestImpl(
      {required this.type,
      required this.description,
      required final List<String> imageUrls,
      final List<String>? preferredDoctorIds,
      final List<String>? medicalHistory})
      : _imageUrls = imageUrls,
        _preferredDoctorIds = preferredDoctorIds,
        _medicalHistory = medicalHistory;

  factory _$SubmitCaseRequestImpl.fromJson(Map<String, dynamic> json) =>
      _$$SubmitCaseRequestImplFromJson(json);

  @override
  final CaseTypeEnum type;
  @override
  final String description;
  final List<String> _imageUrls;
  @override
  List<String> get imageUrls {
    if (_imageUrls is EqualUnmodifiableListView) return _imageUrls;
    // ignore: implicit_dynamic_type
    return EqualUnmodifiableListView(_imageUrls);
  }

  final List<String>? _preferredDoctorIds;
  @override
  List<String>? get preferredDoctorIds {
    final value = _preferredDoctorIds;
    if (value == null) return null;
    if (_preferredDoctorIds is EqualUnmodifiableListView)
      return _preferredDoctorIds;
    // ignore: implicit_dynamic_type
    return EqualUnmodifiableListView(value);
  }

  final List<String>? _medicalHistory;
  @override
  List<String>? get medicalHistory {
    final value = _medicalHistory;
    if (value == null) return null;
    if (_medicalHistory is EqualUnmodifiableListView) return _medicalHistory;
    // ignore: implicit_dynamic_type
    return EqualUnmodifiableListView(value);
  }

  @override
  String toString() {
    return 'SubmitCaseRequest(type: $type, description: $description, imageUrls: $imageUrls, preferredDoctorIds: $preferredDoctorIds, medicalHistory: $medicalHistory)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$SubmitCaseRequestImpl &&
            (identical(other.type, type) || other.type == type) &&
            (identical(other.description, description) ||
                other.description == description) &&
            const DeepCollectionEquality()
                .equals(other._imageUrls, _imageUrls) &&
            const DeepCollectionEquality()
                .equals(other._preferredDoctorIds, _preferredDoctorIds) &&
            const DeepCollectionEquality()
                .equals(other._medicalHistory, _medicalHistory));
  }

  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  int get hashCode => Object.hash(
      runtimeType,
      type,
      description,
      const DeepCollectionEquality().hash(_imageUrls),
      const DeepCollectionEquality().hash(_preferredDoctorIds),
      const DeepCollectionEquality().hash(_medicalHistory));

  /// Create a copy of SubmitCaseRequest
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  @pragma('vm:prefer-inline')
  _$$SubmitCaseRequestImplCopyWith<_$SubmitCaseRequestImpl> get copyWith =>
      __$$SubmitCaseRequestImplCopyWithImpl<_$SubmitCaseRequestImpl>(
          this, _$identity);

  @override
  Map<String, dynamic> toJson() {
    return _$$SubmitCaseRequestImplToJson(
      this,
    );
  }
}

abstract class _SubmitCaseRequest implements SubmitCaseRequest {
  const factory _SubmitCaseRequest(
      {required final CaseTypeEnum type,
      required final String description,
      required final List<String> imageUrls,
      final List<String>? preferredDoctorIds,
      final List<String>? medicalHistory}) = _$SubmitCaseRequestImpl;

  factory _SubmitCaseRequest.fromJson(Map<String, dynamic> json) =
      _$SubmitCaseRequestImpl.fromJson;

  @override
  CaseTypeEnum get type;
  @override
  String get description;
  @override
  List<String> get imageUrls;
  @override
  List<String>? get preferredDoctorIds;
  @override
  List<String>? get medicalHistory;

  /// Create a copy of SubmitCaseRequest
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$SubmitCaseRequestImplCopyWith<_$SubmitCaseRequestImpl> get copyWith =>
      throw _privateConstructorUsedError;
}
