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
  List<Clinic>? get clinics => throw _privateConstructorUsedError;
  List<Service>? get services => throw _privateConstructorUsedError;
  List<BeforeAfterImage>? get beforeAfterImages =>
      throw _privateConstructorUsedError;
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
      List<Clinic>? clinics,
      List<Service>? services,
      List<BeforeAfterImage>? beforeAfterImages,
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
    Object? clinics = freezed,
    Object? services = freezed,
    Object? beforeAfterImages = freezed,
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
      clinics: freezed == clinics
          ? _value.clinics
          : clinics // ignore: cast_nullable_to_non_nullable
              as List<Clinic>?,
      services: freezed == services
          ? _value.services
          : services // ignore: cast_nullable_to_non_nullable
              as List<Service>?,
      beforeAfterImages: freezed == beforeAfterImages
          ? _value.beforeAfterImages
          : beforeAfterImages // ignore: cast_nullable_to_non_nullable
              as List<BeforeAfterImage>?,
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
      List<Clinic>? clinics,
      List<Service>? services,
      List<BeforeAfterImage>? beforeAfterImages,
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
    Object? clinics = freezed,
    Object? services = freezed,
    Object? beforeAfterImages = freezed,
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
      clinics: freezed == clinics
          ? _value._clinics
          : clinics // ignore: cast_nullable_to_non_nullable
              as List<Clinic>?,
      services: freezed == services
          ? _value._services
          : services // ignore: cast_nullable_to_non_nullable
              as List<Service>?,
      beforeAfterImages: freezed == beforeAfterImages
          ? _value._beforeAfterImages
          : beforeAfterImages // ignore: cast_nullable_to_non_nullable
              as List<BeforeAfterImage>?,
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
      final List<Clinic>? clinics,
      final List<Service>? services,
      final List<BeforeAfterImage>? beforeAfterImages,
      this.createdAt,
      this.updatedAt})
      : _languages = languages,
        _certifications = certifications,
        _clinics = clinics,
        _services = services,
        _beforeAfterImages = beforeAfterImages;

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
  final List<Clinic>? _clinics;
  @override
  List<Clinic>? get clinics {
    final value = _clinics;
    if (value == null) return null;
    if (_clinics is EqualUnmodifiableListView) return _clinics;
    // ignore: implicit_dynamic_type
    return EqualUnmodifiableListView(value);
  }

  final List<Service>? _services;
  @override
  List<Service>? get services {
    final value = _services;
    if (value == null) return null;
    if (_services is EqualUnmodifiableListView) return _services;
    // ignore: implicit_dynamic_type
    return EqualUnmodifiableListView(value);
  }

  final List<BeforeAfterImage>? _beforeAfterImages;
  @override
  List<BeforeAfterImage>? get beforeAfterImages {
    final value = _beforeAfterImages;
    if (value == null) return null;
    if (_beforeAfterImages is EqualUnmodifiableListView)
      return _beforeAfterImages;
    // ignore: implicit_dynamic_type
    return EqualUnmodifiableListView(value);
  }

  @override
  final DateTime? createdAt;
  @override
  final DateTime? updatedAt;

  @override
  String toString() {
    return 'Doctor(id: $id, firstName: $firstName, lastName: $lastName, email: $email, phoneNumber: $phoneNumber, profileImageUrl: $profileImageUrl, bio: $bio, specialization: $specialization, experienceYears: $experienceYears, languages: $languages, certifications: $certifications, rating: $rating, reviewsCount: $reviewsCount, isVerified: $isVerified, isOnline: $isOnline, clinics: $clinics, services: $services, beforeAfterImages: $beforeAfterImages, createdAt: $createdAt, updatedAt: $updatedAt)';
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
            const DeepCollectionEquality().equals(other._clinics, _clinics) &&
            const DeepCollectionEquality().equals(other._services, _services) &&
            const DeepCollectionEquality()
                .equals(other._beforeAfterImages, _beforeAfterImages) &&
            (identical(other.createdAt, createdAt) ||
                other.createdAt == createdAt) &&
            (identical(other.updatedAt, updatedAt) ||
                other.updatedAt == updatedAt));
  }

  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  int get hashCode => Object.hashAll([
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
        const DeepCollectionEquality().hash(_clinics),
        const DeepCollectionEquality().hash(_services),
        const DeepCollectionEquality().hash(_beforeAfterImages),
        createdAt,
        updatedAt
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
      final List<Clinic>? clinics,
      final List<Service>? services,
      final List<BeforeAfterImage>? beforeAfterImages,
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
  List<Clinic>? get clinics;
  @override
  List<Service>? get services;
  @override
  List<BeforeAfterImage>? get beforeAfterImages;
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

Clinic _$ClinicFromJson(Map<String, dynamic> json) {
  return _Clinic.fromJson(json);
}

/// @nodoc
mixin _$Clinic {
  String get id => throw _privateConstructorUsedError;
  String get name => throw _privateConstructorUsedError;
  String get address => throw _privateConstructorUsedError;
  String get city => throw _privateConstructorUsedError;
  String get country => throw _privateConstructorUsedError;
  String? get phoneNumber => throw _privateConstructorUsedError;
  String? get email => throw _privateConstructorUsedError;
  String? get website => throw _privateConstructorUsedError;
  String? get description => throw _privateConstructorUsedError;
  List<String>? get imageUrls => throw _privateConstructorUsedError;
  double get rating => throw _privateConstructorUsedError;
  int get reviewsCount => throw _privateConstructorUsedError;
  Map<String, String>? get workingHours => throw _privateConstructorUsedError;
  List<String>? get amenities => throw _privateConstructorUsedError;
  DateTime? get createdAt => throw _privateConstructorUsedError;
  DateTime? get updatedAt => throw _privateConstructorUsedError;

  /// Serializes this Clinic to a JSON map.
  Map<String, dynamic> toJson() => throw _privateConstructorUsedError;

  /// Create a copy of Clinic
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  $ClinicCopyWith<Clinic> get copyWith => throw _privateConstructorUsedError;
}

/// @nodoc
abstract class $ClinicCopyWith<$Res> {
  factory $ClinicCopyWith(Clinic value, $Res Function(Clinic) then) =
      _$ClinicCopyWithImpl<$Res, Clinic>;
  @useResult
  $Res call(
      {String id,
      String name,
      String address,
      String city,
      String country,
      String? phoneNumber,
      String? email,
      String? website,
      String? description,
      List<String>? imageUrls,
      double rating,
      int reviewsCount,
      Map<String, String>? workingHours,
      List<String>? amenities,
      DateTime? createdAt,
      DateTime? updatedAt});
}

/// @nodoc
class _$ClinicCopyWithImpl<$Res, $Val extends Clinic>
    implements $ClinicCopyWith<$Res> {
  _$ClinicCopyWithImpl(this._value, this._then);

  // ignore: unused_field
  final $Val _value;
  // ignore: unused_field
  final $Res Function($Val) _then;

  /// Create a copy of Clinic
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? name = null,
    Object? address = null,
    Object? city = null,
    Object? country = null,
    Object? phoneNumber = freezed,
    Object? email = freezed,
    Object? website = freezed,
    Object? description = freezed,
    Object? imageUrls = freezed,
    Object? rating = null,
    Object? reviewsCount = null,
    Object? workingHours = freezed,
    Object? amenities = freezed,
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
      address: null == address
          ? _value.address
          : address // ignore: cast_nullable_to_non_nullable
              as String,
      city: null == city
          ? _value.city
          : city // ignore: cast_nullable_to_non_nullable
              as String,
      country: null == country
          ? _value.country
          : country // ignore: cast_nullable_to_non_nullable
              as String,
      phoneNumber: freezed == phoneNumber
          ? _value.phoneNumber
          : phoneNumber // ignore: cast_nullable_to_non_nullable
              as String?,
      email: freezed == email
          ? _value.email
          : email // ignore: cast_nullable_to_non_nullable
              as String?,
      website: freezed == website
          ? _value.website
          : website // ignore: cast_nullable_to_non_nullable
              as String?,
      description: freezed == description
          ? _value.description
          : description // ignore: cast_nullable_to_non_nullable
              as String?,
      imageUrls: freezed == imageUrls
          ? _value.imageUrls
          : imageUrls // ignore: cast_nullable_to_non_nullable
              as List<String>?,
      rating: null == rating
          ? _value.rating
          : rating // ignore: cast_nullable_to_non_nullable
              as double,
      reviewsCount: null == reviewsCount
          ? _value.reviewsCount
          : reviewsCount // ignore: cast_nullable_to_non_nullable
              as int,
      workingHours: freezed == workingHours
          ? _value.workingHours
          : workingHours // ignore: cast_nullable_to_non_nullable
              as Map<String, String>?,
      amenities: freezed == amenities
          ? _value.amenities
          : amenities // ignore: cast_nullable_to_non_nullable
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
abstract class _$$ClinicImplCopyWith<$Res> implements $ClinicCopyWith<$Res> {
  factory _$$ClinicImplCopyWith(
          _$ClinicImpl value, $Res Function(_$ClinicImpl) then) =
      __$$ClinicImplCopyWithImpl<$Res>;
  @override
  @useResult
  $Res call(
      {String id,
      String name,
      String address,
      String city,
      String country,
      String? phoneNumber,
      String? email,
      String? website,
      String? description,
      List<String>? imageUrls,
      double rating,
      int reviewsCount,
      Map<String, String>? workingHours,
      List<String>? amenities,
      DateTime? createdAt,
      DateTime? updatedAt});
}

/// @nodoc
class __$$ClinicImplCopyWithImpl<$Res>
    extends _$ClinicCopyWithImpl<$Res, _$ClinicImpl>
    implements _$$ClinicImplCopyWith<$Res> {
  __$$ClinicImplCopyWithImpl(
      _$ClinicImpl _value, $Res Function(_$ClinicImpl) _then)
      : super(_value, _then);

  /// Create a copy of Clinic
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? name = null,
    Object? address = null,
    Object? city = null,
    Object? country = null,
    Object? phoneNumber = freezed,
    Object? email = freezed,
    Object? website = freezed,
    Object? description = freezed,
    Object? imageUrls = freezed,
    Object? rating = null,
    Object? reviewsCount = null,
    Object? workingHours = freezed,
    Object? amenities = freezed,
    Object? createdAt = freezed,
    Object? updatedAt = freezed,
  }) {
    return _then(_$ClinicImpl(
      id: null == id
          ? _value.id
          : id // ignore: cast_nullable_to_non_nullable
              as String,
      name: null == name
          ? _value.name
          : name // ignore: cast_nullable_to_non_nullable
              as String,
      address: null == address
          ? _value.address
          : address // ignore: cast_nullable_to_non_nullable
              as String,
      city: null == city
          ? _value.city
          : city // ignore: cast_nullable_to_non_nullable
              as String,
      country: null == country
          ? _value.country
          : country // ignore: cast_nullable_to_non_nullable
              as String,
      phoneNumber: freezed == phoneNumber
          ? _value.phoneNumber
          : phoneNumber // ignore: cast_nullable_to_non_nullable
              as String?,
      email: freezed == email
          ? _value.email
          : email // ignore: cast_nullable_to_non_nullable
              as String?,
      website: freezed == website
          ? _value.website
          : website // ignore: cast_nullable_to_non_nullable
              as String?,
      description: freezed == description
          ? _value.description
          : description // ignore: cast_nullable_to_non_nullable
              as String?,
      imageUrls: freezed == imageUrls
          ? _value._imageUrls
          : imageUrls // ignore: cast_nullable_to_non_nullable
              as List<String>?,
      rating: null == rating
          ? _value.rating
          : rating // ignore: cast_nullable_to_non_nullable
              as double,
      reviewsCount: null == reviewsCount
          ? _value.reviewsCount
          : reviewsCount // ignore: cast_nullable_to_non_nullable
              as int,
      workingHours: freezed == workingHours
          ? _value._workingHours
          : workingHours // ignore: cast_nullable_to_non_nullable
              as Map<String, String>?,
      amenities: freezed == amenities
          ? _value._amenities
          : amenities // ignore: cast_nullable_to_non_nullable
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
class _$ClinicImpl implements _Clinic {
  const _$ClinicImpl(
      {required this.id,
      required this.name,
      required this.address,
      required this.city,
      required this.country,
      this.phoneNumber,
      this.email,
      this.website,
      this.description,
      final List<String>? imageUrls,
      this.rating = 0.0,
      this.reviewsCount = 0,
      final Map<String, String>? workingHours,
      final List<String>? amenities,
      this.createdAt,
      this.updatedAt})
      : _imageUrls = imageUrls,
        _workingHours = workingHours,
        _amenities = amenities;

  factory _$ClinicImpl.fromJson(Map<String, dynamic> json) =>
      _$$ClinicImplFromJson(json);

  @override
  final String id;
  @override
  final String name;
  @override
  final String address;
  @override
  final String city;
  @override
  final String country;
  @override
  final String? phoneNumber;
  @override
  final String? email;
  @override
  final String? website;
  @override
  final String? description;
  final List<String>? _imageUrls;
  @override
  List<String>? get imageUrls {
    final value = _imageUrls;
    if (value == null) return null;
    if (_imageUrls is EqualUnmodifiableListView) return _imageUrls;
    // ignore: implicit_dynamic_type
    return EqualUnmodifiableListView(value);
  }

  @override
  @JsonKey()
  final double rating;
  @override
  @JsonKey()
  final int reviewsCount;
  final Map<String, String>? _workingHours;
  @override
  Map<String, String>? get workingHours {
    final value = _workingHours;
    if (value == null) return null;
    if (_workingHours is EqualUnmodifiableMapView) return _workingHours;
    // ignore: implicit_dynamic_type
    return EqualUnmodifiableMapView(value);
  }

  final List<String>? _amenities;
  @override
  List<String>? get amenities {
    final value = _amenities;
    if (value == null) return null;
    if (_amenities is EqualUnmodifiableListView) return _amenities;
    // ignore: implicit_dynamic_type
    return EqualUnmodifiableListView(value);
  }

  @override
  final DateTime? createdAt;
  @override
  final DateTime? updatedAt;

  @override
  String toString() {
    return 'Clinic(id: $id, name: $name, address: $address, city: $city, country: $country, phoneNumber: $phoneNumber, email: $email, website: $website, description: $description, imageUrls: $imageUrls, rating: $rating, reviewsCount: $reviewsCount, workingHours: $workingHours, amenities: $amenities, createdAt: $createdAt, updatedAt: $updatedAt)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$ClinicImpl &&
            (identical(other.id, id) || other.id == id) &&
            (identical(other.name, name) || other.name == name) &&
            (identical(other.address, address) || other.address == address) &&
            (identical(other.city, city) || other.city == city) &&
            (identical(other.country, country) || other.country == country) &&
            (identical(other.phoneNumber, phoneNumber) ||
                other.phoneNumber == phoneNumber) &&
            (identical(other.email, email) || other.email == email) &&
            (identical(other.website, website) || other.website == website) &&
            (identical(other.description, description) ||
                other.description == description) &&
            const DeepCollectionEquality()
                .equals(other._imageUrls, _imageUrls) &&
            (identical(other.rating, rating) || other.rating == rating) &&
            (identical(other.reviewsCount, reviewsCount) ||
                other.reviewsCount == reviewsCount) &&
            const DeepCollectionEquality()
                .equals(other._workingHours, _workingHours) &&
            const DeepCollectionEquality()
                .equals(other._amenities, _amenities) &&
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
      name,
      address,
      city,
      country,
      phoneNumber,
      email,
      website,
      description,
      const DeepCollectionEquality().hash(_imageUrls),
      rating,
      reviewsCount,
      const DeepCollectionEquality().hash(_workingHours),
      const DeepCollectionEquality().hash(_amenities),
      createdAt,
      updatedAt);

  /// Create a copy of Clinic
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  @pragma('vm:prefer-inline')
  _$$ClinicImplCopyWith<_$ClinicImpl> get copyWith =>
      __$$ClinicImplCopyWithImpl<_$ClinicImpl>(this, _$identity);

  @override
  Map<String, dynamic> toJson() {
    return _$$ClinicImplToJson(
      this,
    );
  }
}

abstract class _Clinic implements Clinic {
  const factory _Clinic(
      {required final String id,
      required final String name,
      required final String address,
      required final String city,
      required final String country,
      final String? phoneNumber,
      final String? email,
      final String? website,
      final String? description,
      final List<String>? imageUrls,
      final double rating,
      final int reviewsCount,
      final Map<String, String>? workingHours,
      final List<String>? amenities,
      final DateTime? createdAt,
      final DateTime? updatedAt}) = _$ClinicImpl;

  factory _Clinic.fromJson(Map<String, dynamic> json) = _$ClinicImpl.fromJson;

  @override
  String get id;
  @override
  String get name;
  @override
  String get address;
  @override
  String get city;
  @override
  String get country;
  @override
  String? get phoneNumber;
  @override
  String? get email;
  @override
  String? get website;
  @override
  String? get description;
  @override
  List<String>? get imageUrls;
  @override
  double get rating;
  @override
  int get reviewsCount;
  @override
  Map<String, String>? get workingHours;
  @override
  List<String>? get amenities;
  @override
  DateTime? get createdAt;
  @override
  DateTime? get updatedAt;

  /// Create a copy of Clinic
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$ClinicImplCopyWith<_$ClinicImpl> get copyWith =>
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

BeforeAfterImage _$BeforeAfterImageFromJson(Map<String, dynamic> json) {
  return _BeforeAfterImage.fromJson(json);
}

/// @nodoc
mixin _$BeforeAfterImage {
  String get id => throw _privateConstructorUsedError;
  String get beforeImageUrl => throw _privateConstructorUsedError;
  String get afterImageUrl => throw _privateConstructorUsedError;
  String? get description => throw _privateConstructorUsedError;
  String? get treatmentType => throw _privateConstructorUsedError;
  DateTime? get createdAt => throw _privateConstructorUsedError;

  /// Serializes this BeforeAfterImage to a JSON map.
  Map<String, dynamic> toJson() => throw _privateConstructorUsedError;

  /// Create a copy of BeforeAfterImage
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  $BeforeAfterImageCopyWith<BeforeAfterImage> get copyWith =>
      throw _privateConstructorUsedError;
}

/// @nodoc
abstract class $BeforeAfterImageCopyWith<$Res> {
  factory $BeforeAfterImageCopyWith(
          BeforeAfterImage value, $Res Function(BeforeAfterImage) then) =
      _$BeforeAfterImageCopyWithImpl<$Res, BeforeAfterImage>;
  @useResult
  $Res call(
      {String id,
      String beforeImageUrl,
      String afterImageUrl,
      String? description,
      String? treatmentType,
      DateTime? createdAt});
}

/// @nodoc
class _$BeforeAfterImageCopyWithImpl<$Res, $Val extends BeforeAfterImage>
    implements $BeforeAfterImageCopyWith<$Res> {
  _$BeforeAfterImageCopyWithImpl(this._value, this._then);

  // ignore: unused_field
  final $Val _value;
  // ignore: unused_field
  final $Res Function($Val) _then;

  /// Create a copy of BeforeAfterImage
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? beforeImageUrl = null,
    Object? afterImageUrl = null,
    Object? description = freezed,
    Object? treatmentType = freezed,
    Object? createdAt = freezed,
  }) {
    return _then(_value.copyWith(
      id: null == id
          ? _value.id
          : id // ignore: cast_nullable_to_non_nullable
              as String,
      beforeImageUrl: null == beforeImageUrl
          ? _value.beforeImageUrl
          : beforeImageUrl // ignore: cast_nullable_to_non_nullable
              as String,
      afterImageUrl: null == afterImageUrl
          ? _value.afterImageUrl
          : afterImageUrl // ignore: cast_nullable_to_non_nullable
              as String,
      description: freezed == description
          ? _value.description
          : description // ignore: cast_nullable_to_non_nullable
              as String?,
      treatmentType: freezed == treatmentType
          ? _value.treatmentType
          : treatmentType // ignore: cast_nullable_to_non_nullable
              as String?,
      createdAt: freezed == createdAt
          ? _value.createdAt
          : createdAt // ignore: cast_nullable_to_non_nullable
              as DateTime?,
    ) as $Val);
  }
}

/// @nodoc
abstract class _$$BeforeAfterImageImplCopyWith<$Res>
    implements $BeforeAfterImageCopyWith<$Res> {
  factory _$$BeforeAfterImageImplCopyWith(_$BeforeAfterImageImpl value,
          $Res Function(_$BeforeAfterImageImpl) then) =
      __$$BeforeAfterImageImplCopyWithImpl<$Res>;
  @override
  @useResult
  $Res call(
      {String id,
      String beforeImageUrl,
      String afterImageUrl,
      String? description,
      String? treatmentType,
      DateTime? createdAt});
}

/// @nodoc
class __$$BeforeAfterImageImplCopyWithImpl<$Res>
    extends _$BeforeAfterImageCopyWithImpl<$Res, _$BeforeAfterImageImpl>
    implements _$$BeforeAfterImageImplCopyWith<$Res> {
  __$$BeforeAfterImageImplCopyWithImpl(_$BeforeAfterImageImpl _value,
      $Res Function(_$BeforeAfterImageImpl) _then)
      : super(_value, _then);

  /// Create a copy of BeforeAfterImage
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? beforeImageUrl = null,
    Object? afterImageUrl = null,
    Object? description = freezed,
    Object? treatmentType = freezed,
    Object? createdAt = freezed,
  }) {
    return _then(_$BeforeAfterImageImpl(
      id: null == id
          ? _value.id
          : id // ignore: cast_nullable_to_non_nullable
              as String,
      beforeImageUrl: null == beforeImageUrl
          ? _value.beforeImageUrl
          : beforeImageUrl // ignore: cast_nullable_to_non_nullable
              as String,
      afterImageUrl: null == afterImageUrl
          ? _value.afterImageUrl
          : afterImageUrl // ignore: cast_nullable_to_non_nullable
              as String,
      description: freezed == description
          ? _value.description
          : description // ignore: cast_nullable_to_non_nullable
              as String?,
      treatmentType: freezed == treatmentType
          ? _value.treatmentType
          : treatmentType // ignore: cast_nullable_to_non_nullable
              as String?,
      createdAt: freezed == createdAt
          ? _value.createdAt
          : createdAt // ignore: cast_nullable_to_non_nullable
              as DateTime?,
    ));
  }
}

/// @nodoc
@JsonSerializable()
class _$BeforeAfterImageImpl implements _BeforeAfterImage {
  const _$BeforeAfterImageImpl(
      {required this.id,
      required this.beforeImageUrl,
      required this.afterImageUrl,
      this.description,
      this.treatmentType,
      this.createdAt});

  factory _$BeforeAfterImageImpl.fromJson(Map<String, dynamic> json) =>
      _$$BeforeAfterImageImplFromJson(json);

  @override
  final String id;
  @override
  final String beforeImageUrl;
  @override
  final String afterImageUrl;
  @override
  final String? description;
  @override
  final String? treatmentType;
  @override
  final DateTime? createdAt;

  @override
  String toString() {
    return 'BeforeAfterImage(id: $id, beforeImageUrl: $beforeImageUrl, afterImageUrl: $afterImageUrl, description: $description, treatmentType: $treatmentType, createdAt: $createdAt)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$BeforeAfterImageImpl &&
            (identical(other.id, id) || other.id == id) &&
            (identical(other.beforeImageUrl, beforeImageUrl) ||
                other.beforeImageUrl == beforeImageUrl) &&
            (identical(other.afterImageUrl, afterImageUrl) ||
                other.afterImageUrl == afterImageUrl) &&
            (identical(other.description, description) ||
                other.description == description) &&
            (identical(other.treatmentType, treatmentType) ||
                other.treatmentType == treatmentType) &&
            (identical(other.createdAt, createdAt) ||
                other.createdAt == createdAt));
  }

  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  int get hashCode => Object.hash(runtimeType, id, beforeImageUrl,
      afterImageUrl, description, treatmentType, createdAt);

  /// Create a copy of BeforeAfterImage
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  @pragma('vm:prefer-inline')
  _$$BeforeAfterImageImplCopyWith<_$BeforeAfterImageImpl> get copyWith =>
      __$$BeforeAfterImageImplCopyWithImpl<_$BeforeAfterImageImpl>(
          this, _$identity);

  @override
  Map<String, dynamic> toJson() {
    return _$$BeforeAfterImageImplToJson(
      this,
    );
  }
}

abstract class _BeforeAfterImage implements BeforeAfterImage {
  const factory _BeforeAfterImage(
      {required final String id,
      required final String beforeImageUrl,
      required final String afterImageUrl,
      final String? description,
      final String? treatmentType,
      final DateTime? createdAt}) = _$BeforeAfterImageImpl;

  factory _BeforeAfterImage.fromJson(Map<String, dynamic> json) =
      _$BeforeAfterImageImpl.fromJson;

  @override
  String get id;
  @override
  String get beforeImageUrl;
  @override
  String get afterImageUrl;
  @override
  String? get description;
  @override
  String? get treatmentType;
  @override
  DateTime? get createdAt;

  /// Create a copy of BeforeAfterImage
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$BeforeAfterImageImplCopyWith<_$BeforeAfterImageImpl> get copyWith =>
      throw _privateConstructorUsedError;
}
