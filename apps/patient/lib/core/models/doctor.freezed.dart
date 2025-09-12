// coverage:ignore-file
// GENERATED CODE - DO NOT MODIFY BY HAND
// ignore_for_file: type=lint
// ignore_for_file: unused_element, deprecated_member_use, deprecated_member_use_from_same_package, use_function_type_syntax_for_parameters, unnecessary_const, avoid_init_to_null, invalid_override_different_default_values_named, prefer_expression_function_bodies, annotate_overrides, invalid_annotation_target, unnecessary_question_mark

part of 'doctor.dart';

// **************************************************************************
// FreezedGenerator
// **************************************************************************

T _$identity<T>(T value) => value;

final _privateConstructorUsedError = UnsupportedError(
    'It seems like you constructed your class using `MyClass._()`. This constructor is only meant to be used by freezed and you are not supposed to need it nor use it.\nPlease check the documentation here for more information: https://github.com/rrousselGit/freezed#adding-getters-and-methods-to-our-models');

Doctor _$DoctorFromJson(Map<String, dynamic> json) {
  return _Doctor.fromJson(json);
}

/// @nodoc
mixin _$Doctor {
  String get id => throw _privateConstructorUsedError;
  String get firstName => throw _privateConstructorUsedError;
  String get lastName => throw _privateConstructorUsedError;
  String get profileImageUrl => throw _privateConstructorUsedError;
  String get clinicId => throw _privateConstructorUsedError;
  DoctorSpecialty get specialty => throw _privateConstructorUsedError;
  double get rating => throw _privateConstructorUsedError;
  int get reviewCount => throw _privateConstructorUsedError;
  int get experienceYears => throw _privateConstructorUsedError;
  List<String> get languages => throw _privateConstructorUsedError;
  List<String> get services => throw _privateConstructorUsedError;
  String? get bio => throw _privateConstructorUsedError;
  String? get education => throw _privateConstructorUsedError;
  String? get phone => throw _privateConstructorUsedError;
  String? get email => throw _privateConstructorUsedError;
  bool? get isAvailable => throw _privateConstructorUsedError;
  bool? get isFavorite => throw _privateConstructorUsedError;
  DateTime? get nextAvailableDate => throw _privateConstructorUsedError;
  Map<String, dynamic>? get metadata => throw _privateConstructorUsedError;

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
      String profileImageUrl,
      String clinicId,
      DoctorSpecialty specialty,
      double rating,
      int reviewCount,
      int experienceYears,
      List<String> languages,
      List<String> services,
      String? bio,
      String? education,
      String? phone,
      String? email,
      bool? isAvailable,
      bool? isFavorite,
      DateTime? nextAvailableDate,
      Map<String, dynamic>? metadata});
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
    Object? profileImageUrl = null,
    Object? clinicId = null,
    Object? specialty = null,
    Object? rating = null,
    Object? reviewCount = null,
    Object? experienceYears = null,
    Object? languages = null,
    Object? services = null,
    Object? bio = freezed,
    Object? education = freezed,
    Object? phone = freezed,
    Object? email = freezed,
    Object? isAvailable = freezed,
    Object? isFavorite = freezed,
    Object? nextAvailableDate = freezed,
    Object? metadata = freezed,
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
      profileImageUrl: null == profileImageUrl
          ? _value.profileImageUrl
          : profileImageUrl // ignore: cast_nullable_to_non_nullable
              as String,
      clinicId: null == clinicId
          ? _value.clinicId
          : clinicId // ignore: cast_nullable_to_non_nullable
              as String,
      specialty: null == specialty
          ? _value.specialty
          : specialty // ignore: cast_nullable_to_non_nullable
              as DoctorSpecialty,
      rating: null == rating
          ? _value.rating
          : rating // ignore: cast_nullable_to_non_nullable
              as double,
      reviewCount: null == reviewCount
          ? _value.reviewCount
          : reviewCount // ignore: cast_nullable_to_non_nullable
              as int,
      experienceYears: null == experienceYears
          ? _value.experienceYears
          : experienceYears // ignore: cast_nullable_to_non_nullable
              as int,
      languages: null == languages
          ? _value.languages
          : languages // ignore: cast_nullable_to_non_nullable
              as List<String>,
      services: null == services
          ? _value.services
          : services // ignore: cast_nullable_to_non_nullable
              as List<String>,
      bio: freezed == bio
          ? _value.bio
          : bio // ignore: cast_nullable_to_non_nullable
              as String?,
      education: freezed == education
          ? _value.education
          : education // ignore: cast_nullable_to_non_nullable
              as String?,
      phone: freezed == phone
          ? _value.phone
          : phone // ignore: cast_nullable_to_non_nullable
              as String?,
      email: freezed == email
          ? _value.email
          : email // ignore: cast_nullable_to_non_nullable
              as String?,
      isAvailable: freezed == isAvailable
          ? _value.isAvailable
          : isAvailable // ignore: cast_nullable_to_non_nullable
              as bool?,
      isFavorite: freezed == isFavorite
          ? _value.isFavorite
          : isFavorite // ignore: cast_nullable_to_non_nullable
              as bool?,
      nextAvailableDate: freezed == nextAvailableDate
          ? _value.nextAvailableDate
          : nextAvailableDate // ignore: cast_nullable_to_non_nullable
              as DateTime?,
      metadata: freezed == metadata
          ? _value.metadata
          : metadata // ignore: cast_nullable_to_non_nullable
              as Map<String, dynamic>?,
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
      String profileImageUrl,
      String clinicId,
      DoctorSpecialty specialty,
      double rating,
      int reviewCount,
      int experienceYears,
      List<String> languages,
      List<String> services,
      String? bio,
      String? education,
      String? phone,
      String? email,
      bool? isAvailable,
      bool? isFavorite,
      DateTime? nextAvailableDate,
      Map<String, dynamic>? metadata});
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
    Object? profileImageUrl = null,
    Object? clinicId = null,
    Object? specialty = null,
    Object? rating = null,
    Object? reviewCount = null,
    Object? experienceYears = null,
    Object? languages = null,
    Object? services = null,
    Object? bio = freezed,
    Object? education = freezed,
    Object? phone = freezed,
    Object? email = freezed,
    Object? isAvailable = freezed,
    Object? isFavorite = freezed,
    Object? nextAvailableDate = freezed,
    Object? metadata = freezed,
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
      profileImageUrl: null == profileImageUrl
          ? _value.profileImageUrl
          : profileImageUrl // ignore: cast_nullable_to_non_nullable
              as String,
      clinicId: null == clinicId
          ? _value.clinicId
          : clinicId // ignore: cast_nullable_to_non_nullable
              as String,
      specialty: null == specialty
          ? _value.specialty
          : specialty // ignore: cast_nullable_to_non_nullable
              as DoctorSpecialty,
      rating: null == rating
          ? _value.rating
          : rating // ignore: cast_nullable_to_non_nullable
              as double,
      reviewCount: null == reviewCount
          ? _value.reviewCount
          : reviewCount // ignore: cast_nullable_to_non_nullable
              as int,
      experienceYears: null == experienceYears
          ? _value.experienceYears
          : experienceYears // ignore: cast_nullable_to_non_nullable
              as int,
      languages: null == languages
          ? _value._languages
          : languages // ignore: cast_nullable_to_non_nullable
              as List<String>,
      services: null == services
          ? _value._services
          : services // ignore: cast_nullable_to_non_nullable
              as List<String>,
      bio: freezed == bio
          ? _value.bio
          : bio // ignore: cast_nullable_to_non_nullable
              as String?,
      education: freezed == education
          ? _value.education
          : education // ignore: cast_nullable_to_non_nullable
              as String?,
      phone: freezed == phone
          ? _value.phone
          : phone // ignore: cast_nullable_to_non_nullable
              as String?,
      email: freezed == email
          ? _value.email
          : email // ignore: cast_nullable_to_non_nullable
              as String?,
      isAvailable: freezed == isAvailable
          ? _value.isAvailable
          : isAvailable // ignore: cast_nullable_to_non_nullable
              as bool?,
      isFavorite: freezed == isFavorite
          ? _value.isFavorite
          : isFavorite // ignore: cast_nullable_to_non_nullable
              as bool?,
      nextAvailableDate: freezed == nextAvailableDate
          ? _value.nextAvailableDate
          : nextAvailableDate // ignore: cast_nullable_to_non_nullable
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
class _$DoctorImpl implements _Doctor {
  const _$DoctorImpl(
      {required this.id,
      required this.firstName,
      required this.lastName,
      required this.profileImageUrl,
      required this.clinicId,
      required this.specialty,
      required this.rating,
      required this.reviewCount,
      required this.experienceYears,
      required final List<String> languages,
      required final List<String> services,
      this.bio,
      this.education,
      this.phone,
      this.email,
      this.isAvailable,
      this.isFavorite,
      this.nextAvailableDate,
      final Map<String, dynamic>? metadata})
      : _languages = languages,
        _services = services,
        _metadata = metadata;

  factory _$DoctorImpl.fromJson(Map<String, dynamic> json) =>
      _$$DoctorImplFromJson(json);

  @override
  final String id;
  @override
  final String firstName;
  @override
  final String lastName;
  @override
  final String profileImageUrl;
  @override
  final String clinicId;
  @override
  final DoctorSpecialty specialty;
  @override
  final double rating;
  @override
  final int reviewCount;
  @override
  final int experienceYears;
  final List<String> _languages;
  @override
  List<String> get languages {
    if (_languages is EqualUnmodifiableListView) return _languages;
    // ignore: implicit_dynamic_type
    return EqualUnmodifiableListView(_languages);
  }

  final List<String> _services;
  @override
  List<String> get services {
    if (_services is EqualUnmodifiableListView) return _services;
    // ignore: implicit_dynamic_type
    return EqualUnmodifiableListView(_services);
  }

  @override
  final String? bio;
  @override
  final String? education;
  @override
  final String? phone;
  @override
  final String? email;
  @override
  final bool? isAvailable;
  @override
  final bool? isFavorite;
  @override
  final DateTime? nextAvailableDate;
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
    return 'Doctor(id: $id, firstName: $firstName, lastName: $lastName, profileImageUrl: $profileImageUrl, clinicId: $clinicId, specialty: $specialty, rating: $rating, reviewCount: $reviewCount, experienceYears: $experienceYears, languages: $languages, services: $services, bio: $bio, education: $education, phone: $phone, email: $email, isAvailable: $isAvailable, isFavorite: $isFavorite, nextAvailableDate: $nextAvailableDate, metadata: $metadata)';
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
            (identical(other.profileImageUrl, profileImageUrl) ||
                other.profileImageUrl == profileImageUrl) &&
            (identical(other.clinicId, clinicId) ||
                other.clinicId == clinicId) &&
            (identical(other.specialty, specialty) ||
                other.specialty == specialty) &&
            (identical(other.rating, rating) || other.rating == rating) &&
            (identical(other.reviewCount, reviewCount) ||
                other.reviewCount == reviewCount) &&
            (identical(other.experienceYears, experienceYears) ||
                other.experienceYears == experienceYears) &&
            const DeepCollectionEquality()
                .equals(other._languages, _languages) &&
            const DeepCollectionEquality().equals(other._services, _services) &&
            (identical(other.bio, bio) || other.bio == bio) &&
            (identical(other.education, education) ||
                other.education == education) &&
            (identical(other.phone, phone) || other.phone == phone) &&
            (identical(other.email, email) || other.email == email) &&
            (identical(other.isAvailable, isAvailable) ||
                other.isAvailable == isAvailable) &&
            (identical(other.isFavorite, isFavorite) ||
                other.isFavorite == isFavorite) &&
            (identical(other.nextAvailableDate, nextAvailableDate) ||
                other.nextAvailableDate == nextAvailableDate) &&
            const DeepCollectionEquality().equals(other._metadata, _metadata));
  }

  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  int get hashCode => Object.hashAll([
        runtimeType,
        id,
        firstName,
        lastName,
        profileImageUrl,
        clinicId,
        specialty,
        rating,
        reviewCount,
        experienceYears,
        const DeepCollectionEquality().hash(_languages),
        const DeepCollectionEquality().hash(_services),
        bio,
        education,
        phone,
        email,
        isAvailable,
        isFavorite,
        nextAvailableDate,
        const DeepCollectionEquality().hash(_metadata)
      ]);

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
      required final String profileImageUrl,
      required final String clinicId,
      required final DoctorSpecialty specialty,
      required final double rating,
      required final int reviewCount,
      required final int experienceYears,
      required final List<String> languages,
      required final List<String> services,
      final String? bio,
      final String? education,
      final String? phone,
      final String? email,
      final bool? isAvailable,
      final bool? isFavorite,
      final DateTime? nextAvailableDate,
      final Map<String, dynamic>? metadata}) = _$DoctorImpl;

  factory _Doctor.fromJson(Map<String, dynamic> json) = _$DoctorImpl.fromJson;

  @override
  String get id;
  @override
  String get firstName;
  @override
  String get lastName;
  @override
  String get profileImageUrl;
  @override
  String get clinicId;
  @override
  DoctorSpecialty get specialty;
  @override
  double get rating;
  @override
  int get reviewCount;
  @override
  int get experienceYears;
  @override
  List<String> get languages;
  @override
  List<String> get services;
  @override
  String? get bio;
  @override
  String? get education;
  @override
  String? get phone;
  @override
  String? get email;
  @override
  bool? get isAvailable;
  @override
  bool? get isFavorite;
  @override
  DateTime? get nextAvailableDate;
  @override
  Map<String, dynamic>? get metadata;

  /// Create a copy of Doctor
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$DoctorImplCopyWith<_$DoctorImpl> get copyWith =>
      throw _privateConstructorUsedError;
}

DoctorReview _$DoctorReviewFromJson(Map<String, dynamic> json) {
  return _DoctorReview.fromJson(json);
}

/// @nodoc
mixin _$DoctorReview {
  String get id => throw _privateConstructorUsedError;
  String get doctorId => throw _privateConstructorUsedError;
  String get patientName => throw _privateConstructorUsedError;
  double get rating => throw _privateConstructorUsedError;
  String get comment => throw _privateConstructorUsedError;
  DateTime get createdAt => throw _privateConstructorUsedError;
  String? get procedure => throw _privateConstructorUsedError;
  List<String>? get imageUrls => throw _privateConstructorUsedError;
  Map<String, dynamic>? get metadata => throw _privateConstructorUsedError;

  /// Serializes this DoctorReview to a JSON map.
  Map<String, dynamic> toJson() => throw _privateConstructorUsedError;

  /// Create a copy of DoctorReview
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  $DoctorReviewCopyWith<DoctorReview> get copyWith =>
      throw _privateConstructorUsedError;
}

/// @nodoc
abstract class $DoctorReviewCopyWith<$Res> {
  factory $DoctorReviewCopyWith(
          DoctorReview value, $Res Function(DoctorReview) then) =
      _$DoctorReviewCopyWithImpl<$Res, DoctorReview>;
  @useResult
  $Res call(
      {String id,
      String doctorId,
      String patientName,
      double rating,
      String comment,
      DateTime createdAt,
      String? procedure,
      List<String>? imageUrls,
      Map<String, dynamic>? metadata});
}

/// @nodoc
class _$DoctorReviewCopyWithImpl<$Res, $Val extends DoctorReview>
    implements $DoctorReviewCopyWith<$Res> {
  _$DoctorReviewCopyWithImpl(this._value, this._then);

  // ignore: unused_field
  final $Val _value;
  // ignore: unused_field
  final $Res Function($Val) _then;

  /// Create a copy of DoctorReview
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? doctorId = null,
    Object? patientName = null,
    Object? rating = null,
    Object? comment = null,
    Object? createdAt = null,
    Object? procedure = freezed,
    Object? imageUrls = freezed,
    Object? metadata = freezed,
  }) {
    return _then(_value.copyWith(
      id: null == id
          ? _value.id
          : id // ignore: cast_nullable_to_non_nullable
              as String,
      doctorId: null == doctorId
          ? _value.doctorId
          : doctorId // ignore: cast_nullable_to_non_nullable
              as String,
      patientName: null == patientName
          ? _value.patientName
          : patientName // ignore: cast_nullable_to_non_nullable
              as String,
      rating: null == rating
          ? _value.rating
          : rating // ignore: cast_nullable_to_non_nullable
              as double,
      comment: null == comment
          ? _value.comment
          : comment // ignore: cast_nullable_to_non_nullable
              as String,
      createdAt: null == createdAt
          ? _value.createdAt
          : createdAt // ignore: cast_nullable_to_non_nullable
              as DateTime,
      procedure: freezed == procedure
          ? _value.procedure
          : procedure // ignore: cast_nullable_to_non_nullable
              as String?,
      imageUrls: freezed == imageUrls
          ? _value.imageUrls
          : imageUrls // ignore: cast_nullable_to_non_nullable
              as List<String>?,
      metadata: freezed == metadata
          ? _value.metadata
          : metadata // ignore: cast_nullable_to_non_nullable
              as Map<String, dynamic>?,
    ) as $Val);
  }
}

/// @nodoc
abstract class _$$DoctorReviewImplCopyWith<$Res>
    implements $DoctorReviewCopyWith<$Res> {
  factory _$$DoctorReviewImplCopyWith(
          _$DoctorReviewImpl value, $Res Function(_$DoctorReviewImpl) then) =
      __$$DoctorReviewImplCopyWithImpl<$Res>;
  @override
  @useResult
  $Res call(
      {String id,
      String doctorId,
      String patientName,
      double rating,
      String comment,
      DateTime createdAt,
      String? procedure,
      List<String>? imageUrls,
      Map<String, dynamic>? metadata});
}

/// @nodoc
class __$$DoctorReviewImplCopyWithImpl<$Res>
    extends _$DoctorReviewCopyWithImpl<$Res, _$DoctorReviewImpl>
    implements _$$DoctorReviewImplCopyWith<$Res> {
  __$$DoctorReviewImplCopyWithImpl(
      _$DoctorReviewImpl _value, $Res Function(_$DoctorReviewImpl) _then)
      : super(_value, _then);

  /// Create a copy of DoctorReview
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? doctorId = null,
    Object? patientName = null,
    Object? rating = null,
    Object? comment = null,
    Object? createdAt = null,
    Object? procedure = freezed,
    Object? imageUrls = freezed,
    Object? metadata = freezed,
  }) {
    return _then(_$DoctorReviewImpl(
      id: null == id
          ? _value.id
          : id // ignore: cast_nullable_to_non_nullable
              as String,
      doctorId: null == doctorId
          ? _value.doctorId
          : doctorId // ignore: cast_nullable_to_non_nullable
              as String,
      patientName: null == patientName
          ? _value.patientName
          : patientName // ignore: cast_nullable_to_non_nullable
              as String,
      rating: null == rating
          ? _value.rating
          : rating // ignore: cast_nullable_to_non_nullable
              as double,
      comment: null == comment
          ? _value.comment
          : comment // ignore: cast_nullable_to_non_nullable
              as String,
      createdAt: null == createdAt
          ? _value.createdAt
          : createdAt // ignore: cast_nullable_to_non_nullable
              as DateTime,
      procedure: freezed == procedure
          ? _value.procedure
          : procedure // ignore: cast_nullable_to_non_nullable
              as String?,
      imageUrls: freezed == imageUrls
          ? _value._imageUrls
          : imageUrls // ignore: cast_nullable_to_non_nullable
              as List<String>?,
      metadata: freezed == metadata
          ? _value._metadata
          : metadata // ignore: cast_nullable_to_non_nullable
              as Map<String, dynamic>?,
    ));
  }
}

/// @nodoc
@JsonSerializable()
class _$DoctorReviewImpl implements _DoctorReview {
  const _$DoctorReviewImpl(
      {required this.id,
      required this.doctorId,
      required this.patientName,
      required this.rating,
      required this.comment,
      required this.createdAt,
      this.procedure,
      final List<String>? imageUrls,
      final Map<String, dynamic>? metadata})
      : _imageUrls = imageUrls,
        _metadata = metadata;

  factory _$DoctorReviewImpl.fromJson(Map<String, dynamic> json) =>
      _$$DoctorReviewImplFromJson(json);

  @override
  final String id;
  @override
  final String doctorId;
  @override
  final String patientName;
  @override
  final double rating;
  @override
  final String comment;
  @override
  final DateTime createdAt;
  @override
  final String? procedure;
  final List<String>? _imageUrls;
  @override
  List<String>? get imageUrls {
    final value = _imageUrls;
    if (value == null) return null;
    if (_imageUrls is EqualUnmodifiableListView) return _imageUrls;
    // ignore: implicit_dynamic_type
    return EqualUnmodifiableListView(value);
  }

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
    return 'DoctorReview(id: $id, doctorId: $doctorId, patientName: $patientName, rating: $rating, comment: $comment, createdAt: $createdAt, procedure: $procedure, imageUrls: $imageUrls, metadata: $metadata)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$DoctorReviewImpl &&
            (identical(other.id, id) || other.id == id) &&
            (identical(other.doctorId, doctorId) ||
                other.doctorId == doctorId) &&
            (identical(other.patientName, patientName) ||
                other.patientName == patientName) &&
            (identical(other.rating, rating) || other.rating == rating) &&
            (identical(other.comment, comment) || other.comment == comment) &&
            (identical(other.createdAt, createdAt) ||
                other.createdAt == createdAt) &&
            (identical(other.procedure, procedure) ||
                other.procedure == procedure) &&
            const DeepCollectionEquality()
                .equals(other._imageUrls, _imageUrls) &&
            const DeepCollectionEquality().equals(other._metadata, _metadata));
  }

  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  int get hashCode => Object.hash(
      runtimeType,
      id,
      doctorId,
      patientName,
      rating,
      comment,
      createdAt,
      procedure,
      const DeepCollectionEquality().hash(_imageUrls),
      const DeepCollectionEquality().hash(_metadata));

  /// Create a copy of DoctorReview
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  @pragma('vm:prefer-inline')
  _$$DoctorReviewImplCopyWith<_$DoctorReviewImpl> get copyWith =>
      __$$DoctorReviewImplCopyWithImpl<_$DoctorReviewImpl>(this, _$identity);

  @override
  Map<String, dynamic> toJson() {
    return _$$DoctorReviewImplToJson(
      this,
    );
  }
}

abstract class _DoctorReview implements DoctorReview {
  const factory _DoctorReview(
      {required final String id,
      required final String doctorId,
      required final String patientName,
      required final double rating,
      required final String comment,
      required final DateTime createdAt,
      final String? procedure,
      final List<String>? imageUrls,
      final Map<String, dynamic>? metadata}) = _$DoctorReviewImpl;

  factory _DoctorReview.fromJson(Map<String, dynamic> json) =
      _$DoctorReviewImpl.fromJson;

  @override
  String get id;
  @override
  String get doctorId;
  @override
  String get patientName;
  @override
  double get rating;
  @override
  String get comment;
  @override
  DateTime get createdAt;
  @override
  String? get procedure;
  @override
  List<String>? get imageUrls;
  @override
  Map<String, dynamic>? get metadata;

  /// Create a copy of DoctorReview
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$DoctorReviewImplCopyWith<_$DoctorReviewImpl> get copyWith =>
      throw _privateConstructorUsedError;
}
