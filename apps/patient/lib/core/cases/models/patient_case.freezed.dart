// coverage:ignore-file
// GENERATED CODE - DO NOT MODIFY BY HAND
// ignore_for_file: type=lint
// ignore_for_file: unused_element, deprecated_member_use, deprecated_member_use_from_same_package, use_function_type_syntax_for_parameters, unnecessary_const, avoid_init_to_null, invalid_override_different_default_values_named, prefer_expression_function_bodies, annotate_overrides, invalid_annotation_target, unnecessary_question_mark

part of 'patient_case.dart';

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
  String get description => throw _privateConstructorUsedError;
  CaseUrgency get urgency => throw _privateConstructorUsedError;
  CaseStatus get status => throw _privateConstructorUsedError;
  List<String> get photoUrls => throw _privateConstructorUsedError;
  List<String> get complaintIds => throw _privateConstructorUsedError;
  DateTime get createdAt => throw _privateConstructorUsedError;
  DateTime get updatedAt => throw _privateConstructorUsedError;
  String? get medicalHistory => throw _privateConstructorUsedError;
  String? get currentMedications => throw _privateConstructorUsedError;
  String? get allergies => throw _privateConstructorUsedError;
  String? get doctorId => throw _privateConstructorUsedError;
  String? get treatmentPlanId => throw _privateConstructorUsedError;
  DateTime? get consultationDate => throw _privateConstructorUsedError;
  Map<String, dynamic>? get metadata => throw _privateConstructorUsedError;

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
      String description,
      CaseUrgency urgency,
      CaseStatus status,
      List<String> photoUrls,
      List<String> complaintIds,
      DateTime createdAt,
      DateTime updatedAt,
      String? medicalHistory,
      String? currentMedications,
      String? allergies,
      String? doctorId,
      String? treatmentPlanId,
      DateTime? consultationDate,
      Map<String, dynamic>? metadata});
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
    Object? description = null,
    Object? urgency = null,
    Object? status = null,
    Object? photoUrls = null,
    Object? complaintIds = null,
    Object? createdAt = null,
    Object? updatedAt = null,
    Object? medicalHistory = freezed,
    Object? currentMedications = freezed,
    Object? allergies = freezed,
    Object? doctorId = freezed,
    Object? treatmentPlanId = freezed,
    Object? consultationDate = freezed,
    Object? metadata = freezed,
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
      description: null == description
          ? _value.description
          : description // ignore: cast_nullable_to_non_nullable
              as String,
      urgency: null == urgency
          ? _value.urgency
          : urgency // ignore: cast_nullable_to_non_nullable
              as CaseUrgency,
      status: null == status
          ? _value.status
          : status // ignore: cast_nullable_to_non_nullable
              as CaseStatus,
      photoUrls: null == photoUrls
          ? _value.photoUrls
          : photoUrls // ignore: cast_nullable_to_non_nullable
              as List<String>,
      complaintIds: null == complaintIds
          ? _value.complaintIds
          : complaintIds // ignore: cast_nullable_to_non_nullable
              as List<String>,
      createdAt: null == createdAt
          ? _value.createdAt
          : createdAt // ignore: cast_nullable_to_non_nullable
              as DateTime,
      updatedAt: null == updatedAt
          ? _value.updatedAt
          : updatedAt // ignore: cast_nullable_to_non_nullable
              as DateTime,
      medicalHistory: freezed == medicalHistory
          ? _value.medicalHistory
          : medicalHistory // ignore: cast_nullable_to_non_nullable
              as String?,
      currentMedications: freezed == currentMedications
          ? _value.currentMedications
          : currentMedications // ignore: cast_nullable_to_non_nullable
              as String?,
      allergies: freezed == allergies
          ? _value.allergies
          : allergies // ignore: cast_nullable_to_non_nullable
              as String?,
      doctorId: freezed == doctorId
          ? _value.doctorId
          : doctorId // ignore: cast_nullable_to_non_nullable
              as String?,
      treatmentPlanId: freezed == treatmentPlanId
          ? _value.treatmentPlanId
          : treatmentPlanId // ignore: cast_nullable_to_non_nullable
              as String?,
      consultationDate: freezed == consultationDate
          ? _value.consultationDate
          : consultationDate // ignore: cast_nullable_to_non_nullable
              as DateTime?,
      metadata: freezed == metadata
          ? _value.metadata
          : metadata // ignore: cast_nullable_to_non_nullable
              as Map<String, dynamic>?,
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
      String description,
      CaseUrgency urgency,
      CaseStatus status,
      List<String> photoUrls,
      List<String> complaintIds,
      DateTime createdAt,
      DateTime updatedAt,
      String? medicalHistory,
      String? currentMedications,
      String? allergies,
      String? doctorId,
      String? treatmentPlanId,
      DateTime? consultationDate,
      Map<String, dynamic>? metadata});
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
    Object? description = null,
    Object? urgency = null,
    Object? status = null,
    Object? photoUrls = null,
    Object? complaintIds = null,
    Object? createdAt = null,
    Object? updatedAt = null,
    Object? medicalHistory = freezed,
    Object? currentMedications = freezed,
    Object? allergies = freezed,
    Object? doctorId = freezed,
    Object? treatmentPlanId = freezed,
    Object? consultationDate = freezed,
    Object? metadata = freezed,
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
      description: null == description
          ? _value.description
          : description // ignore: cast_nullable_to_non_nullable
              as String,
      urgency: null == urgency
          ? _value.urgency
          : urgency // ignore: cast_nullable_to_non_nullable
              as CaseUrgency,
      status: null == status
          ? _value.status
          : status // ignore: cast_nullable_to_non_nullable
              as CaseStatus,
      photoUrls: null == photoUrls
          ? _value._photoUrls
          : photoUrls // ignore: cast_nullable_to_non_nullable
              as List<String>,
      complaintIds: null == complaintIds
          ? _value._complaintIds
          : complaintIds // ignore: cast_nullable_to_non_nullable
              as List<String>,
      createdAt: null == createdAt
          ? _value.createdAt
          : createdAt // ignore: cast_nullable_to_non_nullable
              as DateTime,
      updatedAt: null == updatedAt
          ? _value.updatedAt
          : updatedAt // ignore: cast_nullable_to_non_nullable
              as DateTime,
      medicalHistory: freezed == medicalHistory
          ? _value.medicalHistory
          : medicalHistory // ignore: cast_nullable_to_non_nullable
              as String?,
      currentMedications: freezed == currentMedications
          ? _value.currentMedications
          : currentMedications // ignore: cast_nullable_to_non_nullable
              as String?,
      allergies: freezed == allergies
          ? _value.allergies
          : allergies // ignore: cast_nullable_to_non_nullable
              as String?,
      doctorId: freezed == doctorId
          ? _value.doctorId
          : doctorId // ignore: cast_nullable_to_non_nullable
              as String?,
      treatmentPlanId: freezed == treatmentPlanId
          ? _value.treatmentPlanId
          : treatmentPlanId // ignore: cast_nullable_to_non_nullable
              as String?,
      consultationDate: freezed == consultationDate
          ? _value.consultationDate
          : consultationDate // ignore: cast_nullable_to_non_nullable
              as DateTime?,
      metadata: freezed == metadata
          ? _value._metadata
          : metadata // ignore: cast_nullable_to_non_nullable
              as Map<String, dynamic>?,
    ));
  }
}

/// @nodoc
@JsonSerializable()
class _$PatientCaseImpl implements _PatientCase {
  const _$PatientCaseImpl(
      {required this.id,
      required this.patientId,
      required this.description,
      required this.urgency,
      required this.status,
      required final List<String> photoUrls,
      required final List<String> complaintIds,
      required this.createdAt,
      required this.updatedAt,
      this.medicalHistory,
      this.currentMedications,
      this.allergies,
      this.doctorId,
      this.treatmentPlanId,
      this.consultationDate,
      final Map<String, dynamic>? metadata})
      : _photoUrls = photoUrls,
        _complaintIds = complaintIds,
        _metadata = metadata;

  factory _$PatientCaseImpl.fromJson(Map<String, dynamic> json) =>
      _$$PatientCaseImplFromJson(json);

  @override
  final String id;
  @override
  final String patientId;
  @override
  final String description;
  @override
  final CaseUrgency urgency;
  @override
  final CaseStatus status;
  final List<String> _photoUrls;
  @override
  List<String> get photoUrls {
    if (_photoUrls is EqualUnmodifiableListView) return _photoUrls;
    // ignore: implicit_dynamic_type
    return EqualUnmodifiableListView(_photoUrls);
  }

  final List<String> _complaintIds;
  @override
  List<String> get complaintIds {
    if (_complaintIds is EqualUnmodifiableListView) return _complaintIds;
    // ignore: implicit_dynamic_type
    return EqualUnmodifiableListView(_complaintIds);
  }

  @override
  final DateTime createdAt;
  @override
  final DateTime updatedAt;
  @override
  final String? medicalHistory;
  @override
  final String? currentMedications;
  @override
  final String? allergies;
  @override
  final String? doctorId;
  @override
  final String? treatmentPlanId;
  @override
  final DateTime? consultationDate;
  final Map<String, dynamic>? _metadata;
  @override
  Map<String, dynamic>? get metadata {
    final value = _metadata;
    if (value == null) return null;
    if (_metadata is EqualUnmodifiableMapView) return _metadata;
    // ignore: implicit_dynamic_type
    return EqualUnmodifiableMapView(value);
  }

  @override
  String toString() {
    return 'PatientCase(id: $id, patientId: $patientId, description: $description, urgency: $urgency, status: $status, photoUrls: $photoUrls, complaintIds: $complaintIds, createdAt: $createdAt, updatedAt: $updatedAt, medicalHistory: $medicalHistory, currentMedications: $currentMedications, allergies: $allergies, doctorId: $doctorId, treatmentPlanId: $treatmentPlanId, consultationDate: $consultationDate, metadata: $metadata)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$PatientCaseImpl &&
            (identical(other.id, id) || other.id == id) &&
            (identical(other.patientId, patientId) ||
                other.patientId == patientId) &&
            (identical(other.description, description) ||
                other.description == description) &&
            (identical(other.urgency, urgency) || other.urgency == urgency) &&
            (identical(other.status, status) || other.status == status) &&
            const DeepCollectionEquality()
                .equals(other._photoUrls, _photoUrls) &&
            const DeepCollectionEquality()
                .equals(other._complaintIds, _complaintIds) &&
            (identical(other.createdAt, createdAt) ||
                other.createdAt == createdAt) &&
            (identical(other.updatedAt, updatedAt) ||
                other.updatedAt == updatedAt) &&
            (identical(other.medicalHistory, medicalHistory) ||
                other.medicalHistory == medicalHistory) &&
            (identical(other.currentMedications, currentMedications) ||
                other.currentMedications == currentMedications) &&
            (identical(other.allergies, allergies) ||
                other.allergies == allergies) &&
            (identical(other.doctorId, doctorId) ||
                other.doctorId == doctorId) &&
            (identical(other.treatmentPlanId, treatmentPlanId) ||
                other.treatmentPlanId == treatmentPlanId) &&
            (identical(other.consultationDate, consultationDate) ||
                other.consultationDate == consultationDate) &&
            const DeepCollectionEquality().equals(other._metadata, _metadata));
  }

  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  int get hashCode => Object.hash(
      runtimeType,
      id,
      patientId,
      description,
      urgency,
      status,
      const DeepCollectionEquality().hash(_photoUrls),
      const DeepCollectionEquality().hash(_complaintIds),
      createdAt,
      updatedAt,
      medicalHistory,
      currentMedications,
      allergies,
      doctorId,
      treatmentPlanId,
      consultationDate,
      const DeepCollectionEquality().hash(_metadata));

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
      required final String description,
      required final CaseUrgency urgency,
      required final CaseStatus status,
      required final List<String> photoUrls,
      required final List<String> complaintIds,
      required final DateTime createdAt,
      required final DateTime updatedAt,
      final String? medicalHistory,
      final String? currentMedications,
      final String? allergies,
      final String? doctorId,
      final String? treatmentPlanId,
      final DateTime? consultationDate,
      final Map<String, dynamic>? metadata}) = _$PatientCaseImpl;

  factory _PatientCase.fromJson(Map<String, dynamic> json) =
      _$PatientCaseImpl.fromJson;

  @override
  String get id;
  @override
  String get patientId;
  @override
  String get description;
  @override
  CaseUrgency get urgency;
  @override
  CaseStatus get status;
  @override
  List<String> get photoUrls;
  @override
  List<String> get complaintIds;
  @override
  DateTime get createdAt;
  @override
  DateTime get updatedAt;
  @override
  String? get medicalHistory;
  @override
  String? get currentMedications;
  @override
  String? get allergies;
  @override
  String? get doctorId;
  @override
  String? get treatmentPlanId;
  @override
  DateTime? get consultationDate;
  @override
  Map<String, dynamic>? get metadata;

  /// Create a copy of PatientCase
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$PatientCaseImplCopyWith<_$PatientCaseImpl> get copyWith =>
      throw _privateConstructorUsedError;
}

CasePhoto _$CasePhotoFromJson(Map<String, dynamic> json) {
  return _CasePhoto.fromJson(json);
}

/// @nodoc
mixin _$CasePhoto {
  String get id => throw _privateConstructorUsedError;
  String get caseId => throw _privateConstructorUsedError;
  String get url => throw _privateConstructorUsedError;
  String get filename => throw _privateConstructorUsedError;
  DateTime get uploadedAt => throw _privateConstructorUsedError;
  String? get description => throw _privateConstructorUsedError;
  Map<String, dynamic>? get metadata => throw _privateConstructorUsedError;

  /// Serializes this CasePhoto to a JSON map.
  Map<String, dynamic> toJson() => throw _privateConstructorUsedError;

  /// Create a copy of CasePhoto
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  $CasePhotoCopyWith<CasePhoto> get copyWith =>
      throw _privateConstructorUsedError;
}

/// @nodoc
abstract class $CasePhotoCopyWith<$Res> {
  factory $CasePhotoCopyWith(CasePhoto value, $Res Function(CasePhoto) then) =
      _$CasePhotoCopyWithImpl<$Res, CasePhoto>;
  @useResult
  $Res call(
      {String id,
      String caseId,
      String url,
      String filename,
      DateTime uploadedAt,
      String? description,
      Map<String, dynamic>? metadata});
}

/// @nodoc
class _$CasePhotoCopyWithImpl<$Res, $Val extends CasePhoto>
    implements $CasePhotoCopyWith<$Res> {
  _$CasePhotoCopyWithImpl(this._value, this._then);

  // ignore: unused_field
  final $Val _value;
  // ignore: unused_field
  final $Res Function($Val) _then;

  /// Create a copy of CasePhoto
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? caseId = null,
    Object? url = null,
    Object? filename = null,
    Object? uploadedAt = null,
    Object? description = freezed,
    Object? metadata = freezed,
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
      url: null == url
          ? _value.url
          : url // ignore: cast_nullable_to_non_nullable
              as String,
      filename: null == filename
          ? _value.filename
          : filename // ignore: cast_nullable_to_non_nullable
              as String,
      uploadedAt: null == uploadedAt
          ? _value.uploadedAt
          : uploadedAt // ignore: cast_nullable_to_non_nullable
              as DateTime,
      description: freezed == description
          ? _value.description
          : description // ignore: cast_nullable_to_non_nullable
              as String?,
      metadata: freezed == metadata
          ? _value.metadata
          : metadata // ignore: cast_nullable_to_non_nullable
              as Map<String, dynamic>?,
    ) as $Val);
  }
}

/// @nodoc
abstract class _$$CasePhotoImplCopyWith<$Res>
    implements $CasePhotoCopyWith<$Res> {
  factory _$$CasePhotoImplCopyWith(
          _$CasePhotoImpl value, $Res Function(_$CasePhotoImpl) then) =
      __$$CasePhotoImplCopyWithImpl<$Res>;
  @override
  @useResult
  $Res call(
      {String id,
      String caseId,
      String url,
      String filename,
      DateTime uploadedAt,
      String? description,
      Map<String, dynamic>? metadata});
}

/// @nodoc
class __$$CasePhotoImplCopyWithImpl<$Res>
    extends _$CasePhotoCopyWithImpl<$Res, _$CasePhotoImpl>
    implements _$$CasePhotoImplCopyWith<$Res> {
  __$$CasePhotoImplCopyWithImpl(
      _$CasePhotoImpl _value, $Res Function(_$CasePhotoImpl) _then)
      : super(_value, _then);

  /// Create a copy of CasePhoto
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? caseId = null,
    Object? url = null,
    Object? filename = null,
    Object? uploadedAt = null,
    Object? description = freezed,
    Object? metadata = freezed,
  }) {
    return _then(_$CasePhotoImpl(
      id: null == id
          ? _value.id
          : id // ignore: cast_nullable_to_non_nullable
              as String,
      caseId: null == caseId
          ? _value.caseId
          : caseId // ignore: cast_nullable_to_non_nullable
              as String,
      url: null == url
          ? _value.url
          : url // ignore: cast_nullable_to_non_nullable
              as String,
      filename: null == filename
          ? _value.filename
          : filename // ignore: cast_nullable_to_non_nullable
              as String,
      uploadedAt: null == uploadedAt
          ? _value.uploadedAt
          : uploadedAt // ignore: cast_nullable_to_non_nullable
              as DateTime,
      description: freezed == description
          ? _value.description
          : description // ignore: cast_nullable_to_non_nullable
              as String?,
      metadata: freezed == metadata
          ? _value._metadata
          : metadata // ignore: cast_nullable_to_non_nullable
              as Map<String, dynamic>?,
    ));
  }
}

/// @nodoc
@JsonSerializable()
class _$CasePhotoImpl implements _CasePhoto {
  const _$CasePhotoImpl(
      {required this.id,
      required this.caseId,
      required this.url,
      required this.filename,
      required this.uploadedAt,
      this.description,
      final Map<String, dynamic>? metadata})
      : _metadata = metadata;

  factory _$CasePhotoImpl.fromJson(Map<String, dynamic> json) =>
      _$$CasePhotoImplFromJson(json);

  @override
  final String id;
  @override
  final String caseId;
  @override
  final String url;
  @override
  final String filename;
  @override
  final DateTime uploadedAt;
  @override
  final String? description;
  final Map<String, dynamic>? _metadata;
  @override
  Map<String, dynamic>? get metadata {
    final value = _metadata;
    if (value == null) return null;
    if (_metadata is EqualUnmodifiableMapView) return _metadata;
    // ignore: implicit_dynamic_type
    return EqualUnmodifiableMapView(value);
  }

  @override
  String toString() {
    return 'CasePhoto(id: $id, caseId: $caseId, url: $url, filename: $filename, uploadedAt: $uploadedAt, description: $description, metadata: $metadata)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$CasePhotoImpl &&
            (identical(other.id, id) || other.id == id) &&
            (identical(other.caseId, caseId) || other.caseId == caseId) &&
            (identical(other.url, url) || other.url == url) &&
            (identical(other.filename, filename) ||
                other.filename == filename) &&
            (identical(other.uploadedAt, uploadedAt) ||
                other.uploadedAt == uploadedAt) &&
            (identical(other.description, description) ||
                other.description == description) &&
            const DeepCollectionEquality().equals(other._metadata, _metadata));
  }

  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  int get hashCode => Object.hash(runtimeType, id, caseId, url, filename,
      uploadedAt, description, const DeepCollectionEquality().hash(_metadata));

  /// Create a copy of CasePhoto
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  @pragma('vm:prefer-inline')
  _$$CasePhotoImplCopyWith<_$CasePhotoImpl> get copyWith =>
      __$$CasePhotoImplCopyWithImpl<_$CasePhotoImpl>(this, _$identity);

  @override
  Map<String, dynamic> toJson() {
    return _$$CasePhotoImplToJson(
      this,
    );
  }
}

abstract class _CasePhoto implements CasePhoto {
  const factory _CasePhoto(
      {required final String id,
      required final String caseId,
      required final String url,
      required final String filename,
      required final DateTime uploadedAt,
      final String? description,
      final Map<String, dynamic>? metadata}) = _$CasePhotoImpl;

  factory _CasePhoto.fromJson(Map<String, dynamic> json) =
      _$CasePhotoImpl.fromJson;

  @override
  String get id;
  @override
  String get caseId;
  @override
  String get url;
  @override
  String get filename;
  @override
  DateTime get uploadedAt;
  @override
  String? get description;
  @override
  Map<String, dynamic>? get metadata;

  /// Create a copy of CasePhoto
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$CasePhotoImplCopyWith<_$CasePhotoImpl> get copyWith =>
      throw _privateConstructorUsedError;
}
