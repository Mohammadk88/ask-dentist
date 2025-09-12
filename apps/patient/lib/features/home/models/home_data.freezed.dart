// coverage:ignore-file
// GENERATED CODE - DO NOT MODIFY BY HAND
// ignore_for_file: type=lint
// ignore_for_file: unused_element, deprecated_member_use, deprecated_member_use_from_same_package, use_function_type_syntax_for_parameters, unnecessary_const, avoid_init_to_null, invalid_override_different_default_values_named, prefer_expression_function_bodies, annotate_overrides, invalid_annotation_target, unnecessary_question_mark

part of 'home_data.dart';

// **************************************************************************
// FreezedGenerator
// **************************************************************************

T _$identity<T>(T value) => value;

final _privateConstructorUsedError = UnsupportedError(
    'It seems like you constructed your class using `MyClass._()`. This constructor is only meant to be used by freezed and you are not supposed to need it nor use it.\nPlease check the documentation here for more information: https://github.com/rrousselGit/freezed#adding-getters-and-methods-to-our-models');

HomeData _$HomeDataFromJson(Map<String, dynamic> json) {
  return _HomeData.fromJson(json);
}

/// @nodoc
mixin _$HomeData {
  List<Story> get stories => throw _privateConstructorUsedError;
  List<Clinic> get topClinics => throw _privateConstructorUsedError;
  List<Doctor> get topDoctors => throw _privateConstructorUsedError;
  List<BeforeAfterCase> get beforeAfter => throw _privateConstructorUsedError;
  List<Doctor> get favoritesDoctors => throw _privateConstructorUsedError;
  ActionFlags? get actionFlags => throw _privateConstructorUsedError;
  String get locale => throw _privateConstructorUsedError;
  String get timestamp => throw _privateConstructorUsedError;
  bool get isGuest => throw _privateConstructorUsedError;
  bool get userAuthenticated => throw _privateConstructorUsedError;

  /// Serializes this HomeData to a JSON map.
  Map<String, dynamic> toJson() => throw _privateConstructorUsedError;

  /// Create a copy of HomeData
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  $HomeDataCopyWith<HomeData> get copyWith =>
      throw _privateConstructorUsedError;
}

/// @nodoc
abstract class $HomeDataCopyWith<$Res> {
  factory $HomeDataCopyWith(HomeData value, $Res Function(HomeData) then) =
      _$HomeDataCopyWithImpl<$Res, HomeData>;
  @useResult
  $Res call(
      {List<Story> stories,
      List<Clinic> topClinics,
      List<Doctor> topDoctors,
      List<BeforeAfterCase> beforeAfter,
      List<Doctor> favoritesDoctors,
      ActionFlags? actionFlags,
      String locale,
      String timestamp,
      bool isGuest,
      bool userAuthenticated});

  $ActionFlagsCopyWith<$Res>? get actionFlags;
}

/// @nodoc
class _$HomeDataCopyWithImpl<$Res, $Val extends HomeData>
    implements $HomeDataCopyWith<$Res> {
  _$HomeDataCopyWithImpl(this._value, this._then);

  // ignore: unused_field
  final $Val _value;
  // ignore: unused_field
  final $Res Function($Val) _then;

  /// Create a copy of HomeData
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? stories = null,
    Object? topClinics = null,
    Object? topDoctors = null,
    Object? beforeAfter = null,
    Object? favoritesDoctors = null,
    Object? actionFlags = freezed,
    Object? locale = null,
    Object? timestamp = null,
    Object? isGuest = null,
    Object? userAuthenticated = null,
  }) {
    return _then(_value.copyWith(
      stories: null == stories
          ? _value.stories
          : stories // ignore: cast_nullable_to_non_nullable
              as List<Story>,
      topClinics: null == topClinics
          ? _value.topClinics
          : topClinics // ignore: cast_nullable_to_non_nullable
              as List<Clinic>,
      topDoctors: null == topDoctors
          ? _value.topDoctors
          : topDoctors // ignore: cast_nullable_to_non_nullable
              as List<Doctor>,
      beforeAfter: null == beforeAfter
          ? _value.beforeAfter
          : beforeAfter // ignore: cast_nullable_to_non_nullable
              as List<BeforeAfterCase>,
      favoritesDoctors: null == favoritesDoctors
          ? _value.favoritesDoctors
          : favoritesDoctors // ignore: cast_nullable_to_non_nullable
              as List<Doctor>,
      actionFlags: freezed == actionFlags
          ? _value.actionFlags
          : actionFlags // ignore: cast_nullable_to_non_nullable
              as ActionFlags?,
      locale: null == locale
          ? _value.locale
          : locale // ignore: cast_nullable_to_non_nullable
              as String,
      timestamp: null == timestamp
          ? _value.timestamp
          : timestamp // ignore: cast_nullable_to_non_nullable
              as String,
      isGuest: null == isGuest
          ? _value.isGuest
          : isGuest // ignore: cast_nullable_to_non_nullable
              as bool,
      userAuthenticated: null == userAuthenticated
          ? _value.userAuthenticated
          : userAuthenticated // ignore: cast_nullable_to_non_nullable
              as bool,
    ) as $Val);
  }

  /// Create a copy of HomeData
  /// with the given fields replaced by the non-null parameter values.
  @override
  @pragma('vm:prefer-inline')
  $ActionFlagsCopyWith<$Res>? get actionFlags {
    if (_value.actionFlags == null) {
      return null;
    }

    return $ActionFlagsCopyWith<$Res>(_value.actionFlags!, (value) {
      return _then(_value.copyWith(actionFlags: value) as $Val);
    });
  }
}

/// @nodoc
abstract class _$$HomeDataImplCopyWith<$Res>
    implements $HomeDataCopyWith<$Res> {
  factory _$$HomeDataImplCopyWith(
          _$HomeDataImpl value, $Res Function(_$HomeDataImpl) then) =
      __$$HomeDataImplCopyWithImpl<$Res>;
  @override
  @useResult
  $Res call(
      {List<Story> stories,
      List<Clinic> topClinics,
      List<Doctor> topDoctors,
      List<BeforeAfterCase> beforeAfter,
      List<Doctor> favoritesDoctors,
      ActionFlags? actionFlags,
      String locale,
      String timestamp,
      bool isGuest,
      bool userAuthenticated});

  @override
  $ActionFlagsCopyWith<$Res>? get actionFlags;
}

/// @nodoc
class __$$HomeDataImplCopyWithImpl<$Res>
    extends _$HomeDataCopyWithImpl<$Res, _$HomeDataImpl>
    implements _$$HomeDataImplCopyWith<$Res> {
  __$$HomeDataImplCopyWithImpl(
      _$HomeDataImpl _value, $Res Function(_$HomeDataImpl) _then)
      : super(_value, _then);

  /// Create a copy of HomeData
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? stories = null,
    Object? topClinics = null,
    Object? topDoctors = null,
    Object? beforeAfter = null,
    Object? favoritesDoctors = null,
    Object? actionFlags = freezed,
    Object? locale = null,
    Object? timestamp = null,
    Object? isGuest = null,
    Object? userAuthenticated = null,
  }) {
    return _then(_$HomeDataImpl(
      stories: null == stories
          ? _value._stories
          : stories // ignore: cast_nullable_to_non_nullable
              as List<Story>,
      topClinics: null == topClinics
          ? _value._topClinics
          : topClinics // ignore: cast_nullable_to_non_nullable
              as List<Clinic>,
      topDoctors: null == topDoctors
          ? _value._topDoctors
          : topDoctors // ignore: cast_nullable_to_non_nullable
              as List<Doctor>,
      beforeAfter: null == beforeAfter
          ? _value._beforeAfter
          : beforeAfter // ignore: cast_nullable_to_non_nullable
              as List<BeforeAfterCase>,
      favoritesDoctors: null == favoritesDoctors
          ? _value._favoritesDoctors
          : favoritesDoctors // ignore: cast_nullable_to_non_nullable
              as List<Doctor>,
      actionFlags: freezed == actionFlags
          ? _value.actionFlags
          : actionFlags // ignore: cast_nullable_to_non_nullable
              as ActionFlags?,
      locale: null == locale
          ? _value.locale
          : locale // ignore: cast_nullable_to_non_nullable
              as String,
      timestamp: null == timestamp
          ? _value.timestamp
          : timestamp // ignore: cast_nullable_to_non_nullable
              as String,
      isGuest: null == isGuest
          ? _value.isGuest
          : isGuest // ignore: cast_nullable_to_non_nullable
              as bool,
      userAuthenticated: null == userAuthenticated
          ? _value.userAuthenticated
          : userAuthenticated // ignore: cast_nullable_to_non_nullable
              as bool,
    ));
  }
}

/// @nodoc
@JsonSerializable()
class _$HomeDataImpl implements _HomeData {
  const _$HomeDataImpl(
      {final List<Story> stories = const [],
      final List<Clinic> topClinics = const [],
      final List<Doctor> topDoctors = const [],
      final List<BeforeAfterCase> beforeAfter = const [],
      final List<Doctor> favoritesDoctors = const [],
      this.actionFlags,
      this.locale = 'en',
      required this.timestamp,
      this.isGuest = true,
      this.userAuthenticated = false})
      : _stories = stories,
        _topClinics = topClinics,
        _topDoctors = topDoctors,
        _beforeAfter = beforeAfter,
        _favoritesDoctors = favoritesDoctors;

  factory _$HomeDataImpl.fromJson(Map<String, dynamic> json) =>
      _$$HomeDataImplFromJson(json);

  final List<Story> _stories;
  @override
  @JsonKey()
  List<Story> get stories {
    if (_stories is EqualUnmodifiableListView) return _stories;
    // ignore: implicit_dynamic_type
    return EqualUnmodifiableListView(_stories);
  }

  final List<Clinic> _topClinics;
  @override
  @JsonKey()
  List<Clinic> get topClinics {
    if (_topClinics is EqualUnmodifiableListView) return _topClinics;
    // ignore: implicit_dynamic_type
    return EqualUnmodifiableListView(_topClinics);
  }

  final List<Doctor> _topDoctors;
  @override
  @JsonKey()
  List<Doctor> get topDoctors {
    if (_topDoctors is EqualUnmodifiableListView) return _topDoctors;
    // ignore: implicit_dynamic_type
    return EqualUnmodifiableListView(_topDoctors);
  }

  final List<BeforeAfterCase> _beforeAfter;
  @override
  @JsonKey()
  List<BeforeAfterCase> get beforeAfter {
    if (_beforeAfter is EqualUnmodifiableListView) return _beforeAfter;
    // ignore: implicit_dynamic_type
    return EqualUnmodifiableListView(_beforeAfter);
  }

  final List<Doctor> _favoritesDoctors;
  @override
  @JsonKey()
  List<Doctor> get favoritesDoctors {
    if (_favoritesDoctors is EqualUnmodifiableListView)
      return _favoritesDoctors;
    // ignore: implicit_dynamic_type
    return EqualUnmodifiableListView(_favoritesDoctors);
  }

  @override
  final ActionFlags? actionFlags;
  @override
  @JsonKey()
  final String locale;
  @override
  final String timestamp;
  @override
  @JsonKey()
  final bool isGuest;
  @override
  @JsonKey()
  final bool userAuthenticated;

  @override
  String toString() {
    return 'HomeData(stories: $stories, topClinics: $topClinics, topDoctors: $topDoctors, beforeAfter: $beforeAfter, favoritesDoctors: $favoritesDoctors, actionFlags: $actionFlags, locale: $locale, timestamp: $timestamp, isGuest: $isGuest, userAuthenticated: $userAuthenticated)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$HomeDataImpl &&
            const DeepCollectionEquality().equals(other._stories, _stories) &&
            const DeepCollectionEquality()
                .equals(other._topClinics, _topClinics) &&
            const DeepCollectionEquality()
                .equals(other._topDoctors, _topDoctors) &&
            const DeepCollectionEquality()
                .equals(other._beforeAfter, _beforeAfter) &&
            const DeepCollectionEquality()
                .equals(other._favoritesDoctors, _favoritesDoctors) &&
            (identical(other.actionFlags, actionFlags) ||
                other.actionFlags == actionFlags) &&
            (identical(other.locale, locale) || other.locale == locale) &&
            (identical(other.timestamp, timestamp) ||
                other.timestamp == timestamp) &&
            (identical(other.isGuest, isGuest) || other.isGuest == isGuest) &&
            (identical(other.userAuthenticated, userAuthenticated) ||
                other.userAuthenticated == userAuthenticated));
  }

  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  int get hashCode => Object.hash(
      runtimeType,
      const DeepCollectionEquality().hash(_stories),
      const DeepCollectionEquality().hash(_topClinics),
      const DeepCollectionEquality().hash(_topDoctors),
      const DeepCollectionEquality().hash(_beforeAfter),
      const DeepCollectionEquality().hash(_favoritesDoctors),
      actionFlags,
      locale,
      timestamp,
      isGuest,
      userAuthenticated);

  /// Create a copy of HomeData
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  @pragma('vm:prefer-inline')
  _$$HomeDataImplCopyWith<_$HomeDataImpl> get copyWith =>
      __$$HomeDataImplCopyWithImpl<_$HomeDataImpl>(this, _$identity);

  @override
  Map<String, dynamic> toJson() {
    return _$$HomeDataImplToJson(
      this,
    );
  }
}

abstract class _HomeData implements HomeData {
  const factory _HomeData(
      {final List<Story> stories,
      final List<Clinic> topClinics,
      final List<Doctor> topDoctors,
      final List<BeforeAfterCase> beforeAfter,
      final List<Doctor> favoritesDoctors,
      final ActionFlags? actionFlags,
      final String locale,
      required final String timestamp,
      final bool isGuest,
      final bool userAuthenticated}) = _$HomeDataImpl;

  factory _HomeData.fromJson(Map<String, dynamic> json) =
      _$HomeDataImpl.fromJson;

  @override
  List<Story> get stories;
  @override
  List<Clinic> get topClinics;
  @override
  List<Doctor> get topDoctors;
  @override
  List<BeforeAfterCase> get beforeAfter;
  @override
  List<Doctor> get favoritesDoctors;
  @override
  ActionFlags? get actionFlags;
  @override
  String get locale;
  @override
  String get timestamp;
  @override
  bool get isGuest;
  @override
  bool get userAuthenticated;

  /// Create a copy of HomeData
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$HomeDataImplCopyWith<_$HomeDataImpl> get copyWith =>
      throw _privateConstructorUsedError;
}

Story _$StoryFromJson(Map<String, dynamic> json) {
  return _Story.fromJson(json);
}

/// @nodoc
mixin _$Story {
  String get id => throw _privateConstructorUsedError;
  String get title => throw _privateConstructorUsedError;
  String? get description => throw _privateConstructorUsedError;
  String? get imageUrl => throw _privateConstructorUsedError;
  String? get ownerType => throw _privateConstructorUsedError;
  String? get ownerId => throw _privateConstructorUsedError;
  String? get ownerName => throw _privateConstructorUsedError;
  DateTime? get createdAt => throw _privateConstructorUsedError;
  DateTime? get expiresAt => throw _privateConstructorUsedError;

  /// Serializes this Story to a JSON map.
  Map<String, dynamic> toJson() => throw _privateConstructorUsedError;

  /// Create a copy of Story
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  $StoryCopyWith<Story> get copyWith => throw _privateConstructorUsedError;
}

/// @nodoc
abstract class $StoryCopyWith<$Res> {
  factory $StoryCopyWith(Story value, $Res Function(Story) then) =
      _$StoryCopyWithImpl<$Res, Story>;
  @useResult
  $Res call(
      {String id,
      String title,
      String? description,
      String? imageUrl,
      String? ownerType,
      String? ownerId,
      String? ownerName,
      DateTime? createdAt,
      DateTime? expiresAt});
}

/// @nodoc
class _$StoryCopyWithImpl<$Res, $Val extends Story>
    implements $StoryCopyWith<$Res> {
  _$StoryCopyWithImpl(this._value, this._then);

  // ignore: unused_field
  final $Val _value;
  // ignore: unused_field
  final $Res Function($Val) _then;

  /// Create a copy of Story
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? title = null,
    Object? description = freezed,
    Object? imageUrl = freezed,
    Object? ownerType = freezed,
    Object? ownerId = freezed,
    Object? ownerName = freezed,
    Object? createdAt = freezed,
    Object? expiresAt = freezed,
  }) {
    return _then(_value.copyWith(
      id: null == id
          ? _value.id
          : id // ignore: cast_nullable_to_non_nullable
              as String,
      title: null == title
          ? _value.title
          : title // ignore: cast_nullable_to_non_nullable
              as String,
      description: freezed == description
          ? _value.description
          : description // ignore: cast_nullable_to_non_nullable
              as String?,
      imageUrl: freezed == imageUrl
          ? _value.imageUrl
          : imageUrl // ignore: cast_nullable_to_non_nullable
              as String?,
      ownerType: freezed == ownerType
          ? _value.ownerType
          : ownerType // ignore: cast_nullable_to_non_nullable
              as String?,
      ownerId: freezed == ownerId
          ? _value.ownerId
          : ownerId // ignore: cast_nullable_to_non_nullable
              as String?,
      ownerName: freezed == ownerName
          ? _value.ownerName
          : ownerName // ignore: cast_nullable_to_non_nullable
              as String?,
      createdAt: freezed == createdAt
          ? _value.createdAt
          : createdAt // ignore: cast_nullable_to_non_nullable
              as DateTime?,
      expiresAt: freezed == expiresAt
          ? _value.expiresAt
          : expiresAt // ignore: cast_nullable_to_non_nullable
              as DateTime?,
    ) as $Val);
  }
}

/// @nodoc
abstract class _$$StoryImplCopyWith<$Res> implements $StoryCopyWith<$Res> {
  factory _$$StoryImplCopyWith(
          _$StoryImpl value, $Res Function(_$StoryImpl) then) =
      __$$StoryImplCopyWithImpl<$Res>;
  @override
  @useResult
  $Res call(
      {String id,
      String title,
      String? description,
      String? imageUrl,
      String? ownerType,
      String? ownerId,
      String? ownerName,
      DateTime? createdAt,
      DateTime? expiresAt});
}

/// @nodoc
class __$$StoryImplCopyWithImpl<$Res>
    extends _$StoryCopyWithImpl<$Res, _$StoryImpl>
    implements _$$StoryImplCopyWith<$Res> {
  __$$StoryImplCopyWithImpl(
      _$StoryImpl _value, $Res Function(_$StoryImpl) _then)
      : super(_value, _then);

  /// Create a copy of Story
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? title = null,
    Object? description = freezed,
    Object? imageUrl = freezed,
    Object? ownerType = freezed,
    Object? ownerId = freezed,
    Object? ownerName = freezed,
    Object? createdAt = freezed,
    Object? expiresAt = freezed,
  }) {
    return _then(_$StoryImpl(
      id: null == id
          ? _value.id
          : id // ignore: cast_nullable_to_non_nullable
              as String,
      title: null == title
          ? _value.title
          : title // ignore: cast_nullable_to_non_nullable
              as String,
      description: freezed == description
          ? _value.description
          : description // ignore: cast_nullable_to_non_nullable
              as String?,
      imageUrl: freezed == imageUrl
          ? _value.imageUrl
          : imageUrl // ignore: cast_nullable_to_non_nullable
              as String?,
      ownerType: freezed == ownerType
          ? _value.ownerType
          : ownerType // ignore: cast_nullable_to_non_nullable
              as String?,
      ownerId: freezed == ownerId
          ? _value.ownerId
          : ownerId // ignore: cast_nullable_to_non_nullable
              as String?,
      ownerName: freezed == ownerName
          ? _value.ownerName
          : ownerName // ignore: cast_nullable_to_non_nullable
              as String?,
      createdAt: freezed == createdAt
          ? _value.createdAt
          : createdAt // ignore: cast_nullable_to_non_nullable
              as DateTime?,
      expiresAt: freezed == expiresAt
          ? _value.expiresAt
          : expiresAt // ignore: cast_nullable_to_non_nullable
              as DateTime?,
    ));
  }
}

/// @nodoc
@JsonSerializable()
class _$StoryImpl implements _Story {
  const _$StoryImpl(
      {required this.id,
      required this.title,
      this.description,
      this.imageUrl,
      this.ownerType,
      this.ownerId,
      this.ownerName,
      this.createdAt,
      this.expiresAt});

  factory _$StoryImpl.fromJson(Map<String, dynamic> json) =>
      _$$StoryImplFromJson(json);

  @override
  final String id;
  @override
  final String title;
  @override
  final String? description;
  @override
  final String? imageUrl;
  @override
  final String? ownerType;
  @override
  final String? ownerId;
  @override
  final String? ownerName;
  @override
  final DateTime? createdAt;
  @override
  final DateTime? expiresAt;

  @override
  String toString() {
    return 'Story(id: $id, title: $title, description: $description, imageUrl: $imageUrl, ownerType: $ownerType, ownerId: $ownerId, ownerName: $ownerName, createdAt: $createdAt, expiresAt: $expiresAt)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$StoryImpl &&
            (identical(other.id, id) || other.id == id) &&
            (identical(other.title, title) || other.title == title) &&
            (identical(other.description, description) ||
                other.description == description) &&
            (identical(other.imageUrl, imageUrl) ||
                other.imageUrl == imageUrl) &&
            (identical(other.ownerType, ownerType) ||
                other.ownerType == ownerType) &&
            (identical(other.ownerId, ownerId) || other.ownerId == ownerId) &&
            (identical(other.ownerName, ownerName) ||
                other.ownerName == ownerName) &&
            (identical(other.createdAt, createdAt) ||
                other.createdAt == createdAt) &&
            (identical(other.expiresAt, expiresAt) ||
                other.expiresAt == expiresAt));
  }

  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  int get hashCode => Object.hash(runtimeType, id, title, description, imageUrl,
      ownerType, ownerId, ownerName, createdAt, expiresAt);

  /// Create a copy of Story
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  @pragma('vm:prefer-inline')
  _$$StoryImplCopyWith<_$StoryImpl> get copyWith =>
      __$$StoryImplCopyWithImpl<_$StoryImpl>(this, _$identity);

  @override
  Map<String, dynamic> toJson() {
    return _$$StoryImplToJson(
      this,
    );
  }
}

abstract class _Story implements Story {
  const factory _Story(
      {required final String id,
      required final String title,
      final String? description,
      final String? imageUrl,
      final String? ownerType,
      final String? ownerId,
      final String? ownerName,
      final DateTime? createdAt,
      final DateTime? expiresAt}) = _$StoryImpl;

  factory _Story.fromJson(Map<String, dynamic> json) = _$StoryImpl.fromJson;

  @override
  String get id;
  @override
  String get title;
  @override
  String? get description;
  @override
  String? get imageUrl;
  @override
  String? get ownerType;
  @override
  String? get ownerId;
  @override
  String? get ownerName;
  @override
  DateTime? get createdAt;
  @override
  DateTime? get expiresAt;

  /// Create a copy of Story
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$StoryImplCopyWith<_$StoryImpl> get copyWith =>
      throw _privateConstructorUsedError;
}

Clinic _$ClinicFromJson(Map<String, dynamic> json) {
  return _Clinic.fromJson(json);
}

/// @nodoc
mixin _$Clinic {
  String get id => throw _privateConstructorUsedError;
  String get name => throw _privateConstructorUsedError;
  String? get description => throw _privateConstructorUsedError;
  String? get imageUrl => throw _privateConstructorUsedError;
  String? get address => throw _privateConstructorUsedError;
  String? get phone => throw _privateConstructorUsedError;
  double? get rating => throw _privateConstructorUsedError;
  int? get reviewCount => throw _privateConstructorUsedError;

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
      String? description,
      String? imageUrl,
      String? address,
      String? phone,
      double? rating,
      int? reviewCount});
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
    Object? description = freezed,
    Object? imageUrl = freezed,
    Object? address = freezed,
    Object? phone = freezed,
    Object? rating = freezed,
    Object? reviewCount = freezed,
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
      description: freezed == description
          ? _value.description
          : description // ignore: cast_nullable_to_non_nullable
              as String?,
      imageUrl: freezed == imageUrl
          ? _value.imageUrl
          : imageUrl // ignore: cast_nullable_to_non_nullable
              as String?,
      address: freezed == address
          ? _value.address
          : address // ignore: cast_nullable_to_non_nullable
              as String?,
      phone: freezed == phone
          ? _value.phone
          : phone // ignore: cast_nullable_to_non_nullable
              as String?,
      rating: freezed == rating
          ? _value.rating
          : rating // ignore: cast_nullable_to_non_nullable
              as double?,
      reviewCount: freezed == reviewCount
          ? _value.reviewCount
          : reviewCount // ignore: cast_nullable_to_non_nullable
              as int?,
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
      String? description,
      String? imageUrl,
      String? address,
      String? phone,
      double? rating,
      int? reviewCount});
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
    Object? description = freezed,
    Object? imageUrl = freezed,
    Object? address = freezed,
    Object? phone = freezed,
    Object? rating = freezed,
    Object? reviewCount = freezed,
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
      description: freezed == description
          ? _value.description
          : description // ignore: cast_nullable_to_non_nullable
              as String?,
      imageUrl: freezed == imageUrl
          ? _value.imageUrl
          : imageUrl // ignore: cast_nullable_to_non_nullable
              as String?,
      address: freezed == address
          ? _value.address
          : address // ignore: cast_nullable_to_non_nullable
              as String?,
      phone: freezed == phone
          ? _value.phone
          : phone // ignore: cast_nullable_to_non_nullable
              as String?,
      rating: freezed == rating
          ? _value.rating
          : rating // ignore: cast_nullable_to_non_nullable
              as double?,
      reviewCount: freezed == reviewCount
          ? _value.reviewCount
          : reviewCount // ignore: cast_nullable_to_non_nullable
              as int?,
    ));
  }
}

/// @nodoc
@JsonSerializable()
class _$ClinicImpl implements _Clinic {
  const _$ClinicImpl(
      {required this.id,
      required this.name,
      this.description,
      this.imageUrl,
      this.address,
      this.phone,
      this.rating,
      this.reviewCount});

  factory _$ClinicImpl.fromJson(Map<String, dynamic> json) =>
      _$$ClinicImplFromJson(json);

  @override
  final String id;
  @override
  final String name;
  @override
  final String? description;
  @override
  final String? imageUrl;
  @override
  final String? address;
  @override
  final String? phone;
  @override
  final double? rating;
  @override
  final int? reviewCount;

  @override
  String toString() {
    return 'Clinic(id: $id, name: $name, description: $description, imageUrl: $imageUrl, address: $address, phone: $phone, rating: $rating, reviewCount: $reviewCount)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$ClinicImpl &&
            (identical(other.id, id) || other.id == id) &&
            (identical(other.name, name) || other.name == name) &&
            (identical(other.description, description) ||
                other.description == description) &&
            (identical(other.imageUrl, imageUrl) ||
                other.imageUrl == imageUrl) &&
            (identical(other.address, address) || other.address == address) &&
            (identical(other.phone, phone) || other.phone == phone) &&
            (identical(other.rating, rating) || other.rating == rating) &&
            (identical(other.reviewCount, reviewCount) ||
                other.reviewCount == reviewCount));
  }

  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  int get hashCode => Object.hash(runtimeType, id, name, description, imageUrl,
      address, phone, rating, reviewCount);

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
      final String? description,
      final String? imageUrl,
      final String? address,
      final String? phone,
      final double? rating,
      final int? reviewCount}) = _$ClinicImpl;

  factory _Clinic.fromJson(Map<String, dynamic> json) = _$ClinicImpl.fromJson;

  @override
  String get id;
  @override
  String get name;
  @override
  String? get description;
  @override
  String? get imageUrl;
  @override
  String? get address;
  @override
  String? get phone;
  @override
  double? get rating;
  @override
  int? get reviewCount;

  /// Create a copy of Clinic
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$ClinicImplCopyWith<_$ClinicImpl> get copyWith =>
      throw _privateConstructorUsedError;
}

Doctor _$DoctorFromJson(Map<String, dynamic> json) {
  return _Doctor.fromJson(json);
}

/// @nodoc
mixin _$Doctor {
  String get id => throw _privateConstructorUsedError;
  String get name => throw _privateConstructorUsedError;
  String? get specialization => throw _privateConstructorUsedError;
  String? get imageUrl => throw _privateConstructorUsedError;
  String? get clinicName => throw _privateConstructorUsedError;
  double? get rating => throw _privateConstructorUsedError;
  int? get reviewCount => throw _privateConstructorUsedError;
  bool? get isFavorite => throw _privateConstructorUsedError;

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
      String name,
      String? specialization,
      String? imageUrl,
      String? clinicName,
      double? rating,
      int? reviewCount,
      bool? isFavorite});
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
    Object? name = null,
    Object? specialization = freezed,
    Object? imageUrl = freezed,
    Object? clinicName = freezed,
    Object? rating = freezed,
    Object? reviewCount = freezed,
    Object? isFavorite = freezed,
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
      specialization: freezed == specialization
          ? _value.specialization
          : specialization // ignore: cast_nullable_to_non_nullable
              as String?,
      imageUrl: freezed == imageUrl
          ? _value.imageUrl
          : imageUrl // ignore: cast_nullable_to_non_nullable
              as String?,
      clinicName: freezed == clinicName
          ? _value.clinicName
          : clinicName // ignore: cast_nullable_to_non_nullable
              as String?,
      rating: freezed == rating
          ? _value.rating
          : rating // ignore: cast_nullable_to_non_nullable
              as double?,
      reviewCount: freezed == reviewCount
          ? _value.reviewCount
          : reviewCount // ignore: cast_nullable_to_non_nullable
              as int?,
      isFavorite: freezed == isFavorite
          ? _value.isFavorite
          : isFavorite // ignore: cast_nullable_to_non_nullable
              as bool?,
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
      String name,
      String? specialization,
      String? imageUrl,
      String? clinicName,
      double? rating,
      int? reviewCount,
      bool? isFavorite});
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
    Object? name = null,
    Object? specialization = freezed,
    Object? imageUrl = freezed,
    Object? clinicName = freezed,
    Object? rating = freezed,
    Object? reviewCount = freezed,
    Object? isFavorite = freezed,
  }) {
    return _then(_$DoctorImpl(
      id: null == id
          ? _value.id
          : id // ignore: cast_nullable_to_non_nullable
              as String,
      name: null == name
          ? _value.name
          : name // ignore: cast_nullable_to_non_nullable
              as String,
      specialization: freezed == specialization
          ? _value.specialization
          : specialization // ignore: cast_nullable_to_non_nullable
              as String?,
      imageUrl: freezed == imageUrl
          ? _value.imageUrl
          : imageUrl // ignore: cast_nullable_to_non_nullable
              as String?,
      clinicName: freezed == clinicName
          ? _value.clinicName
          : clinicName // ignore: cast_nullable_to_non_nullable
              as String?,
      rating: freezed == rating
          ? _value.rating
          : rating // ignore: cast_nullable_to_non_nullable
              as double?,
      reviewCount: freezed == reviewCount
          ? _value.reviewCount
          : reviewCount // ignore: cast_nullable_to_non_nullable
              as int?,
      isFavorite: freezed == isFavorite
          ? _value.isFavorite
          : isFavorite // ignore: cast_nullable_to_non_nullable
              as bool?,
    ));
  }
}

/// @nodoc
@JsonSerializable()
class _$DoctorImpl implements _Doctor {
  const _$DoctorImpl(
      {required this.id,
      required this.name,
      this.specialization,
      this.imageUrl,
      this.clinicName,
      this.rating,
      this.reviewCount,
      this.isFavorite});

  factory _$DoctorImpl.fromJson(Map<String, dynamic> json) =>
      _$$DoctorImplFromJson(json);

  @override
  final String id;
  @override
  final String name;
  @override
  final String? specialization;
  @override
  final String? imageUrl;
  @override
  final String? clinicName;
  @override
  final double? rating;
  @override
  final int? reviewCount;
  @override
  final bool? isFavorite;

  @override
  String toString() {
    return 'Doctor(id: $id, name: $name, specialization: $specialization, imageUrl: $imageUrl, clinicName: $clinicName, rating: $rating, reviewCount: $reviewCount, isFavorite: $isFavorite)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$DoctorImpl &&
            (identical(other.id, id) || other.id == id) &&
            (identical(other.name, name) || other.name == name) &&
            (identical(other.specialization, specialization) ||
                other.specialization == specialization) &&
            (identical(other.imageUrl, imageUrl) ||
                other.imageUrl == imageUrl) &&
            (identical(other.clinicName, clinicName) ||
                other.clinicName == clinicName) &&
            (identical(other.rating, rating) || other.rating == rating) &&
            (identical(other.reviewCount, reviewCount) ||
                other.reviewCount == reviewCount) &&
            (identical(other.isFavorite, isFavorite) ||
                other.isFavorite == isFavorite));
  }

  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  int get hashCode => Object.hash(runtimeType, id, name, specialization,
      imageUrl, clinicName, rating, reviewCount, isFavorite);

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
      required final String name,
      final String? specialization,
      final String? imageUrl,
      final String? clinicName,
      final double? rating,
      final int? reviewCount,
      final bool? isFavorite}) = _$DoctorImpl;

  factory _Doctor.fromJson(Map<String, dynamic> json) = _$DoctorImpl.fromJson;

  @override
  String get id;
  @override
  String get name;
  @override
  String? get specialization;
  @override
  String? get imageUrl;
  @override
  String? get clinicName;
  @override
  double? get rating;
  @override
  int? get reviewCount;
  @override
  bool? get isFavorite;

  /// Create a copy of Doctor
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$DoctorImplCopyWith<_$DoctorImpl> get copyWith =>
      throw _privateConstructorUsedError;
}

BeforeAfterCase _$BeforeAfterCaseFromJson(Map<String, dynamic> json) {
  return _BeforeAfterCase.fromJson(json);
}

/// @nodoc
mixin _$BeforeAfterCase {
  String get id => throw _privateConstructorUsedError;
  String get title => throw _privateConstructorUsedError;
  String? get description => throw _privateConstructorUsedError;
  String? get beforeImageUrl => throw _privateConstructorUsedError;
  String? get afterImageUrl => throw _privateConstructorUsedError;
  String? get doctorName => throw _privateConstructorUsedError;
  String? get treatment => throw _privateConstructorUsedError;

  /// Serializes this BeforeAfterCase to a JSON map.
  Map<String, dynamic> toJson() => throw _privateConstructorUsedError;

  /// Create a copy of BeforeAfterCase
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  $BeforeAfterCaseCopyWith<BeforeAfterCase> get copyWith =>
      throw _privateConstructorUsedError;
}

/// @nodoc
abstract class $BeforeAfterCaseCopyWith<$Res> {
  factory $BeforeAfterCaseCopyWith(
          BeforeAfterCase value, $Res Function(BeforeAfterCase) then) =
      _$BeforeAfterCaseCopyWithImpl<$Res, BeforeAfterCase>;
  @useResult
  $Res call(
      {String id,
      String title,
      String? description,
      String? beforeImageUrl,
      String? afterImageUrl,
      String? doctorName,
      String? treatment});
}

/// @nodoc
class _$BeforeAfterCaseCopyWithImpl<$Res, $Val extends BeforeAfterCase>
    implements $BeforeAfterCaseCopyWith<$Res> {
  _$BeforeAfterCaseCopyWithImpl(this._value, this._then);

  // ignore: unused_field
  final $Val _value;
  // ignore: unused_field
  final $Res Function($Val) _then;

  /// Create a copy of BeforeAfterCase
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? title = null,
    Object? description = freezed,
    Object? beforeImageUrl = freezed,
    Object? afterImageUrl = freezed,
    Object? doctorName = freezed,
    Object? treatment = freezed,
  }) {
    return _then(_value.copyWith(
      id: null == id
          ? _value.id
          : id // ignore: cast_nullable_to_non_nullable
              as String,
      title: null == title
          ? _value.title
          : title // ignore: cast_nullable_to_non_nullable
              as String,
      description: freezed == description
          ? _value.description
          : description // ignore: cast_nullable_to_non_nullable
              as String?,
      beforeImageUrl: freezed == beforeImageUrl
          ? _value.beforeImageUrl
          : beforeImageUrl // ignore: cast_nullable_to_non_nullable
              as String?,
      afterImageUrl: freezed == afterImageUrl
          ? _value.afterImageUrl
          : afterImageUrl // ignore: cast_nullable_to_non_nullable
              as String?,
      doctorName: freezed == doctorName
          ? _value.doctorName
          : doctorName // ignore: cast_nullable_to_non_nullable
              as String?,
      treatment: freezed == treatment
          ? _value.treatment
          : treatment // ignore: cast_nullable_to_non_nullable
              as String?,
    ) as $Val);
  }
}

/// @nodoc
abstract class _$$BeforeAfterCaseImplCopyWith<$Res>
    implements $BeforeAfterCaseCopyWith<$Res> {
  factory _$$BeforeAfterCaseImplCopyWith(_$BeforeAfterCaseImpl value,
          $Res Function(_$BeforeAfterCaseImpl) then) =
      __$$BeforeAfterCaseImplCopyWithImpl<$Res>;
  @override
  @useResult
  $Res call(
      {String id,
      String title,
      String? description,
      String? beforeImageUrl,
      String? afterImageUrl,
      String? doctorName,
      String? treatment});
}

/// @nodoc
class __$$BeforeAfterCaseImplCopyWithImpl<$Res>
    extends _$BeforeAfterCaseCopyWithImpl<$Res, _$BeforeAfterCaseImpl>
    implements _$$BeforeAfterCaseImplCopyWith<$Res> {
  __$$BeforeAfterCaseImplCopyWithImpl(
      _$BeforeAfterCaseImpl _value, $Res Function(_$BeforeAfterCaseImpl) _then)
      : super(_value, _then);

  /// Create a copy of BeforeAfterCase
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? title = null,
    Object? description = freezed,
    Object? beforeImageUrl = freezed,
    Object? afterImageUrl = freezed,
    Object? doctorName = freezed,
    Object? treatment = freezed,
  }) {
    return _then(_$BeforeAfterCaseImpl(
      id: null == id
          ? _value.id
          : id // ignore: cast_nullable_to_non_nullable
              as String,
      title: null == title
          ? _value.title
          : title // ignore: cast_nullable_to_non_nullable
              as String,
      description: freezed == description
          ? _value.description
          : description // ignore: cast_nullable_to_non_nullable
              as String?,
      beforeImageUrl: freezed == beforeImageUrl
          ? _value.beforeImageUrl
          : beforeImageUrl // ignore: cast_nullable_to_non_nullable
              as String?,
      afterImageUrl: freezed == afterImageUrl
          ? _value.afterImageUrl
          : afterImageUrl // ignore: cast_nullable_to_non_nullable
              as String?,
      doctorName: freezed == doctorName
          ? _value.doctorName
          : doctorName // ignore: cast_nullable_to_non_nullable
              as String?,
      treatment: freezed == treatment
          ? _value.treatment
          : treatment // ignore: cast_nullable_to_non_nullable
              as String?,
    ));
  }
}

/// @nodoc
@JsonSerializable()
class _$BeforeAfterCaseImpl implements _BeforeAfterCase {
  const _$BeforeAfterCaseImpl(
      {required this.id,
      required this.title,
      this.description,
      this.beforeImageUrl,
      this.afterImageUrl,
      this.doctorName,
      this.treatment});

  factory _$BeforeAfterCaseImpl.fromJson(Map<String, dynamic> json) =>
      _$$BeforeAfterCaseImplFromJson(json);

  @override
  final String id;
  @override
  final String title;
  @override
  final String? description;
  @override
  final String? beforeImageUrl;
  @override
  final String? afterImageUrl;
  @override
  final String? doctorName;
  @override
  final String? treatment;

  @override
  String toString() {
    return 'BeforeAfterCase(id: $id, title: $title, description: $description, beforeImageUrl: $beforeImageUrl, afterImageUrl: $afterImageUrl, doctorName: $doctorName, treatment: $treatment)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$BeforeAfterCaseImpl &&
            (identical(other.id, id) || other.id == id) &&
            (identical(other.title, title) || other.title == title) &&
            (identical(other.description, description) ||
                other.description == description) &&
            (identical(other.beforeImageUrl, beforeImageUrl) ||
                other.beforeImageUrl == beforeImageUrl) &&
            (identical(other.afterImageUrl, afterImageUrl) ||
                other.afterImageUrl == afterImageUrl) &&
            (identical(other.doctorName, doctorName) ||
                other.doctorName == doctorName) &&
            (identical(other.treatment, treatment) ||
                other.treatment == treatment));
  }

  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  int get hashCode => Object.hash(runtimeType, id, title, description,
      beforeImageUrl, afterImageUrl, doctorName, treatment);

  /// Create a copy of BeforeAfterCase
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  @pragma('vm:prefer-inline')
  _$$BeforeAfterCaseImplCopyWith<_$BeforeAfterCaseImpl> get copyWith =>
      __$$BeforeAfterCaseImplCopyWithImpl<_$BeforeAfterCaseImpl>(
          this, _$identity);

  @override
  Map<String, dynamic> toJson() {
    return _$$BeforeAfterCaseImplToJson(
      this,
    );
  }
}

abstract class _BeforeAfterCase implements BeforeAfterCase {
  const factory _BeforeAfterCase(
      {required final String id,
      required final String title,
      final String? description,
      final String? beforeImageUrl,
      final String? afterImageUrl,
      final String? doctorName,
      final String? treatment}) = _$BeforeAfterCaseImpl;

  factory _BeforeAfterCase.fromJson(Map<String, dynamic> json) =
      _$BeforeAfterCaseImpl.fromJson;

  @override
  String get id;
  @override
  String get title;
  @override
  String? get description;
  @override
  String? get beforeImageUrl;
  @override
  String? get afterImageUrl;
  @override
  String? get doctorName;
  @override
  String? get treatment;

  /// Create a copy of BeforeAfterCase
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$BeforeAfterCaseImplCopyWith<_$BeforeAfterCaseImpl> get copyWith =>
      throw _privateConstructorUsedError;
}

ActionFlags _$ActionFlagsFromJson(Map<String, dynamic> json) {
  return _ActionFlags.fromJson(json);
}

/// @nodoc
mixin _$ActionFlags {
  bool get canFavorite => throw _privateConstructorUsedError;
  bool get canBook => throw _privateConstructorUsedError;
  bool get canMessage => throw _privateConstructorUsedError;

  /// Serializes this ActionFlags to a JSON map.
  Map<String, dynamic> toJson() => throw _privateConstructorUsedError;

  /// Create a copy of ActionFlags
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  $ActionFlagsCopyWith<ActionFlags> get copyWith =>
      throw _privateConstructorUsedError;
}

/// @nodoc
abstract class $ActionFlagsCopyWith<$Res> {
  factory $ActionFlagsCopyWith(
          ActionFlags value, $Res Function(ActionFlags) then) =
      _$ActionFlagsCopyWithImpl<$Res, ActionFlags>;
  @useResult
  $Res call({bool canFavorite, bool canBook, bool canMessage});
}

/// @nodoc
class _$ActionFlagsCopyWithImpl<$Res, $Val extends ActionFlags>
    implements $ActionFlagsCopyWith<$Res> {
  _$ActionFlagsCopyWithImpl(this._value, this._then);

  // ignore: unused_field
  final $Val _value;
  // ignore: unused_field
  final $Res Function($Val) _then;

  /// Create a copy of ActionFlags
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? canFavorite = null,
    Object? canBook = null,
    Object? canMessage = null,
  }) {
    return _then(_value.copyWith(
      canFavorite: null == canFavorite
          ? _value.canFavorite
          : canFavorite // ignore: cast_nullable_to_non_nullable
              as bool,
      canBook: null == canBook
          ? _value.canBook
          : canBook // ignore: cast_nullable_to_non_nullable
              as bool,
      canMessage: null == canMessage
          ? _value.canMessage
          : canMessage // ignore: cast_nullable_to_non_nullable
              as bool,
    ) as $Val);
  }
}

/// @nodoc
abstract class _$$ActionFlagsImplCopyWith<$Res>
    implements $ActionFlagsCopyWith<$Res> {
  factory _$$ActionFlagsImplCopyWith(
          _$ActionFlagsImpl value, $Res Function(_$ActionFlagsImpl) then) =
      __$$ActionFlagsImplCopyWithImpl<$Res>;
  @override
  @useResult
  $Res call({bool canFavorite, bool canBook, bool canMessage});
}

/// @nodoc
class __$$ActionFlagsImplCopyWithImpl<$Res>
    extends _$ActionFlagsCopyWithImpl<$Res, _$ActionFlagsImpl>
    implements _$$ActionFlagsImplCopyWith<$Res> {
  __$$ActionFlagsImplCopyWithImpl(
      _$ActionFlagsImpl _value, $Res Function(_$ActionFlagsImpl) _then)
      : super(_value, _then);

  /// Create a copy of ActionFlags
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? canFavorite = null,
    Object? canBook = null,
    Object? canMessage = null,
  }) {
    return _then(_$ActionFlagsImpl(
      canFavorite: null == canFavorite
          ? _value.canFavorite
          : canFavorite // ignore: cast_nullable_to_non_nullable
              as bool,
      canBook: null == canBook
          ? _value.canBook
          : canBook // ignore: cast_nullable_to_non_nullable
              as bool,
      canMessage: null == canMessage
          ? _value.canMessage
          : canMessage // ignore: cast_nullable_to_non_nullable
              as bool,
    ));
  }
}

/// @nodoc
@JsonSerializable()
class _$ActionFlagsImpl implements _ActionFlags {
  const _$ActionFlagsImpl(
      {this.canFavorite = false,
      this.canBook = false,
      this.canMessage = false});

  factory _$ActionFlagsImpl.fromJson(Map<String, dynamic> json) =>
      _$$ActionFlagsImplFromJson(json);

  @override
  @JsonKey()
  final bool canFavorite;
  @override
  @JsonKey()
  final bool canBook;
  @override
  @JsonKey()
  final bool canMessage;

  @override
  String toString() {
    return 'ActionFlags(canFavorite: $canFavorite, canBook: $canBook, canMessage: $canMessage)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$ActionFlagsImpl &&
            (identical(other.canFavorite, canFavorite) ||
                other.canFavorite == canFavorite) &&
            (identical(other.canBook, canBook) || other.canBook == canBook) &&
            (identical(other.canMessage, canMessage) ||
                other.canMessage == canMessage));
  }

  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  int get hashCode =>
      Object.hash(runtimeType, canFavorite, canBook, canMessage);

  /// Create a copy of ActionFlags
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  @pragma('vm:prefer-inline')
  _$$ActionFlagsImplCopyWith<_$ActionFlagsImpl> get copyWith =>
      __$$ActionFlagsImplCopyWithImpl<_$ActionFlagsImpl>(this, _$identity);

  @override
  Map<String, dynamic> toJson() {
    return _$$ActionFlagsImplToJson(
      this,
    );
  }
}

abstract class _ActionFlags implements ActionFlags {
  const factory _ActionFlags(
      {final bool canFavorite,
      final bool canBook,
      final bool canMessage}) = _$ActionFlagsImpl;

  factory _ActionFlags.fromJson(Map<String, dynamic> json) =
      _$ActionFlagsImpl.fromJson;

  @override
  bool get canFavorite;
  @override
  bool get canBook;
  @override
  bool get canMessage;

  /// Create a copy of ActionFlags
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$ActionFlagsImplCopyWith<_$ActionFlagsImpl> get copyWith =>
      throw _privateConstructorUsedError;
}
