// coverage:ignore-file
// GENERATED CODE - DO NOT MODIFY BY HAND
// ignore_for_file: type=lint
// ignore_for_file: unused_element, deprecated_member_use, deprecated_member_use_from_same_package, use_function_type_syntax_for_parameters, unnecessary_const, avoid_init_to_null, invalid_override_different_default_values_named, prefer_expression_function_bodies, annotate_overrides, invalid_annotation_target, unnecessary_question_mark

part of 'home_dto.dart';

// **************************************************************************
// FreezedGenerator
// **************************************************************************

T _$identity<T>(T value) => value;

final _privateConstructorUsedError = UnsupportedError(
    'It seems like you constructed your class using `MyClass._()`. This constructor is only meant to be used by freezed and you are not supposed to need it nor use it.\nPlease check the documentation here for more information: https://github.com/rrousselGit/freezed#adding-getters-and-methods-to-our-models');

HomeDto _$HomeDtoFromJson(Map<String, dynamic> json) {
  return _HomeDto.fromJson(json);
}

/// @nodoc
mixin _$HomeDto {
  List<StoryItem> get stories => throw _privateConstructorUsedError;
  List<ClinicItem> get featuredClinics => throw _privateConstructorUsedError;
  List<DoctorItem> get recommendedDoctors => throw _privateConstructorUsedError;
  List<DoctorItem> get favoriteDoctors => throw _privateConstructorUsedError;
  List<BeforeAfterCase> get beforeAfterGallery =>
      throw _privateConstructorUsedError;

  /// Serializes this HomeDto to a JSON map.
  Map<String, dynamic> toJson() => throw _privateConstructorUsedError;

  /// Create a copy of HomeDto
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  $HomeDtoCopyWith<HomeDto> get copyWith => throw _privateConstructorUsedError;
}

/// @nodoc
abstract class $HomeDtoCopyWith<$Res> {
  factory $HomeDtoCopyWith(HomeDto value, $Res Function(HomeDto) then) =
      _$HomeDtoCopyWithImpl<$Res, HomeDto>;
  @useResult
  $Res call(
      {List<StoryItem> stories,
      List<ClinicItem> featuredClinics,
      List<DoctorItem> recommendedDoctors,
      List<DoctorItem> favoriteDoctors,
      List<BeforeAfterCase> beforeAfterGallery});
}

/// @nodoc
class _$HomeDtoCopyWithImpl<$Res, $Val extends HomeDto>
    implements $HomeDtoCopyWith<$Res> {
  _$HomeDtoCopyWithImpl(this._value, this._then);

  // ignore: unused_field
  final $Val _value;
  // ignore: unused_field
  final $Res Function($Val) _then;

  /// Create a copy of HomeDto
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? stories = null,
    Object? featuredClinics = null,
    Object? recommendedDoctors = null,
    Object? favoriteDoctors = null,
    Object? beforeAfterGallery = null,
  }) {
    return _then(_value.copyWith(
      stories: null == stories
          ? _value.stories
          : stories // ignore: cast_nullable_to_non_nullable
              as List<StoryItem>,
      featuredClinics: null == featuredClinics
          ? _value.featuredClinics
          : featuredClinics // ignore: cast_nullable_to_non_nullable
              as List<ClinicItem>,
      recommendedDoctors: null == recommendedDoctors
          ? _value.recommendedDoctors
          : recommendedDoctors // ignore: cast_nullable_to_non_nullable
              as List<DoctorItem>,
      favoriteDoctors: null == favoriteDoctors
          ? _value.favoriteDoctors
          : favoriteDoctors // ignore: cast_nullable_to_non_nullable
              as List<DoctorItem>,
      beforeAfterGallery: null == beforeAfterGallery
          ? _value.beforeAfterGallery
          : beforeAfterGallery // ignore: cast_nullable_to_non_nullable
              as List<BeforeAfterCase>,
    ) as $Val);
  }
}

/// @nodoc
abstract class _$$HomeDtoImplCopyWith<$Res> implements $HomeDtoCopyWith<$Res> {
  factory _$$HomeDtoImplCopyWith(
          _$HomeDtoImpl value, $Res Function(_$HomeDtoImpl) then) =
      __$$HomeDtoImplCopyWithImpl<$Res>;
  @override
  @useResult
  $Res call(
      {List<StoryItem> stories,
      List<ClinicItem> featuredClinics,
      List<DoctorItem> recommendedDoctors,
      List<DoctorItem> favoriteDoctors,
      List<BeforeAfterCase> beforeAfterGallery});
}

/// @nodoc
class __$$HomeDtoImplCopyWithImpl<$Res>
    extends _$HomeDtoCopyWithImpl<$Res, _$HomeDtoImpl>
    implements _$$HomeDtoImplCopyWith<$Res> {
  __$$HomeDtoImplCopyWithImpl(
      _$HomeDtoImpl _value, $Res Function(_$HomeDtoImpl) _then)
      : super(_value, _then);

  /// Create a copy of HomeDto
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? stories = null,
    Object? featuredClinics = null,
    Object? recommendedDoctors = null,
    Object? favoriteDoctors = null,
    Object? beforeAfterGallery = null,
  }) {
    return _then(_$HomeDtoImpl(
      stories: null == stories
          ? _value._stories
          : stories // ignore: cast_nullable_to_non_nullable
              as List<StoryItem>,
      featuredClinics: null == featuredClinics
          ? _value._featuredClinics
          : featuredClinics // ignore: cast_nullable_to_non_nullable
              as List<ClinicItem>,
      recommendedDoctors: null == recommendedDoctors
          ? _value._recommendedDoctors
          : recommendedDoctors // ignore: cast_nullable_to_non_nullable
              as List<DoctorItem>,
      favoriteDoctors: null == favoriteDoctors
          ? _value._favoriteDoctors
          : favoriteDoctors // ignore: cast_nullable_to_non_nullable
              as List<DoctorItem>,
      beforeAfterGallery: null == beforeAfterGallery
          ? _value._beforeAfterGallery
          : beforeAfterGallery // ignore: cast_nullable_to_non_nullable
              as List<BeforeAfterCase>,
    ));
  }
}

/// @nodoc
@JsonSerializable()
class _$HomeDtoImpl implements _HomeDto {
  const _$HomeDtoImpl(
      {required final List<StoryItem> stories,
      required final List<ClinicItem> featuredClinics,
      required final List<DoctorItem> recommendedDoctors,
      required final List<DoctorItem> favoriteDoctors,
      required final List<BeforeAfterCase> beforeAfterGallery})
      : _stories = stories,
        _featuredClinics = featuredClinics,
        _recommendedDoctors = recommendedDoctors,
        _favoriteDoctors = favoriteDoctors,
        _beforeAfterGallery = beforeAfterGallery;

  factory _$HomeDtoImpl.fromJson(Map<String, dynamic> json) =>
      _$$HomeDtoImplFromJson(json);

  final List<StoryItem> _stories;
  @override
  List<StoryItem> get stories {
    if (_stories is EqualUnmodifiableListView) return _stories;
    // ignore: implicit_dynamic_type
    return EqualUnmodifiableListView(_stories);
  }

  final List<ClinicItem> _featuredClinics;
  @override
  List<ClinicItem> get featuredClinics {
    if (_featuredClinics is EqualUnmodifiableListView) return _featuredClinics;
    // ignore: implicit_dynamic_type
    return EqualUnmodifiableListView(_featuredClinics);
  }

  final List<DoctorItem> _recommendedDoctors;
  @override
  List<DoctorItem> get recommendedDoctors {
    if (_recommendedDoctors is EqualUnmodifiableListView)
      return _recommendedDoctors;
    // ignore: implicit_dynamic_type
    return EqualUnmodifiableListView(_recommendedDoctors);
  }

  final List<DoctorItem> _favoriteDoctors;
  @override
  List<DoctorItem> get favoriteDoctors {
    if (_favoriteDoctors is EqualUnmodifiableListView) return _favoriteDoctors;
    // ignore: implicit_dynamic_type
    return EqualUnmodifiableListView(_favoriteDoctors);
  }

  final List<BeforeAfterCase> _beforeAfterGallery;
  @override
  List<BeforeAfterCase> get beforeAfterGallery {
    if (_beforeAfterGallery is EqualUnmodifiableListView)
      return _beforeAfterGallery;
    // ignore: implicit_dynamic_type
    return EqualUnmodifiableListView(_beforeAfterGallery);
  }

  @override
  String toString() {
    return 'HomeDto(stories: $stories, featuredClinics: $featuredClinics, recommendedDoctors: $recommendedDoctors, favoriteDoctors: $favoriteDoctors, beforeAfterGallery: $beforeAfterGallery)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$HomeDtoImpl &&
            const DeepCollectionEquality().equals(other._stories, _stories) &&
            const DeepCollectionEquality()
                .equals(other._featuredClinics, _featuredClinics) &&
            const DeepCollectionEquality()
                .equals(other._recommendedDoctors, _recommendedDoctors) &&
            const DeepCollectionEquality()
                .equals(other._favoriteDoctors, _favoriteDoctors) &&
            const DeepCollectionEquality()
                .equals(other._beforeAfterGallery, _beforeAfterGallery));
  }

  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  int get hashCode => Object.hash(
      runtimeType,
      const DeepCollectionEquality().hash(_stories),
      const DeepCollectionEquality().hash(_featuredClinics),
      const DeepCollectionEquality().hash(_recommendedDoctors),
      const DeepCollectionEquality().hash(_favoriteDoctors),
      const DeepCollectionEquality().hash(_beforeAfterGallery));

  /// Create a copy of HomeDto
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  @pragma('vm:prefer-inline')
  _$$HomeDtoImplCopyWith<_$HomeDtoImpl> get copyWith =>
      __$$HomeDtoImplCopyWithImpl<_$HomeDtoImpl>(this, _$identity);

  @override
  Map<String, dynamic> toJson() {
    return _$$HomeDtoImplToJson(
      this,
    );
  }
}

abstract class _HomeDto implements HomeDto {
  const factory _HomeDto(
      {required final List<StoryItem> stories,
      required final List<ClinicItem> featuredClinics,
      required final List<DoctorItem> recommendedDoctors,
      required final List<DoctorItem> favoriteDoctors,
      required final List<BeforeAfterCase> beforeAfterGallery}) = _$HomeDtoImpl;

  factory _HomeDto.fromJson(Map<String, dynamic> json) = _$HomeDtoImpl.fromJson;

  @override
  List<StoryItem> get stories;
  @override
  List<ClinicItem> get featuredClinics;
  @override
  List<DoctorItem> get recommendedDoctors;
  @override
  List<DoctorItem> get favoriteDoctors;
  @override
  List<BeforeAfterCase> get beforeAfterGallery;

  /// Create a copy of HomeDto
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$HomeDtoImplCopyWith<_$HomeDtoImpl> get copyWith =>
      throw _privateConstructorUsedError;
}

StoryItem _$StoryItemFromJson(Map<String, dynamic> json) {
  return _StoryItem.fromJson(json);
}

/// @nodoc
mixin _$StoryItem {
  String get id => throw _privateConstructorUsedError;
  String get ownerName => throw _privateConstructorUsedError;
  String get avatarUrl => throw _privateConstructorUsedError;
  String get content => throw _privateConstructorUsedError;
  StoryContentType get contentType => throw _privateConstructorUsedError;
  String get caption => throw _privateConstructorUsedError;
  bool get isViewed => throw _privateConstructorUsedError;
  DateTime get timestamp => throw _privateConstructorUsedError;
  bool get isPromoted => throw _privateConstructorUsedError;

  /// Serializes this StoryItem to a JSON map.
  Map<String, dynamic> toJson() => throw _privateConstructorUsedError;

  /// Create a copy of StoryItem
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  $StoryItemCopyWith<StoryItem> get copyWith =>
      throw _privateConstructorUsedError;
}

/// @nodoc
abstract class $StoryItemCopyWith<$Res> {
  factory $StoryItemCopyWith(StoryItem value, $Res Function(StoryItem) then) =
      _$StoryItemCopyWithImpl<$Res, StoryItem>;
  @useResult
  $Res call(
      {String id,
      String ownerName,
      String avatarUrl,
      String content,
      StoryContentType contentType,
      String caption,
      bool isViewed,
      DateTime timestamp,
      bool isPromoted});
}

/// @nodoc
class _$StoryItemCopyWithImpl<$Res, $Val extends StoryItem>
    implements $StoryItemCopyWith<$Res> {
  _$StoryItemCopyWithImpl(this._value, this._then);

  // ignore: unused_field
  final $Val _value;
  // ignore: unused_field
  final $Res Function($Val) _then;

  /// Create a copy of StoryItem
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? ownerName = null,
    Object? avatarUrl = null,
    Object? content = null,
    Object? contentType = null,
    Object? caption = null,
    Object? isViewed = null,
    Object? timestamp = null,
    Object? isPromoted = null,
  }) {
    return _then(_value.copyWith(
      id: null == id
          ? _value.id
          : id // ignore: cast_nullable_to_non_nullable
              as String,
      ownerName: null == ownerName
          ? _value.ownerName
          : ownerName // ignore: cast_nullable_to_non_nullable
              as String,
      avatarUrl: null == avatarUrl
          ? _value.avatarUrl
          : avatarUrl // ignore: cast_nullable_to_non_nullable
              as String,
      content: null == content
          ? _value.content
          : content // ignore: cast_nullable_to_non_nullable
              as String,
      contentType: null == contentType
          ? _value.contentType
          : contentType // ignore: cast_nullable_to_non_nullable
              as StoryContentType,
      caption: null == caption
          ? _value.caption
          : caption // ignore: cast_nullable_to_non_nullable
              as String,
      isViewed: null == isViewed
          ? _value.isViewed
          : isViewed // ignore: cast_nullable_to_non_nullable
              as bool,
      timestamp: null == timestamp
          ? _value.timestamp
          : timestamp // ignore: cast_nullable_to_non_nullable
              as DateTime,
      isPromoted: null == isPromoted
          ? _value.isPromoted
          : isPromoted // ignore: cast_nullable_to_non_nullable
              as bool,
    ) as $Val);
  }
}

/// @nodoc
abstract class _$$StoryItemImplCopyWith<$Res>
    implements $StoryItemCopyWith<$Res> {
  factory _$$StoryItemImplCopyWith(
          _$StoryItemImpl value, $Res Function(_$StoryItemImpl) then) =
      __$$StoryItemImplCopyWithImpl<$Res>;
  @override
  @useResult
  $Res call(
      {String id,
      String ownerName,
      String avatarUrl,
      String content,
      StoryContentType contentType,
      String caption,
      bool isViewed,
      DateTime timestamp,
      bool isPromoted});
}

/// @nodoc
class __$$StoryItemImplCopyWithImpl<$Res>
    extends _$StoryItemCopyWithImpl<$Res, _$StoryItemImpl>
    implements _$$StoryItemImplCopyWith<$Res> {
  __$$StoryItemImplCopyWithImpl(
      _$StoryItemImpl _value, $Res Function(_$StoryItemImpl) _then)
      : super(_value, _then);

  /// Create a copy of StoryItem
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? ownerName = null,
    Object? avatarUrl = null,
    Object? content = null,
    Object? contentType = null,
    Object? caption = null,
    Object? isViewed = null,
    Object? timestamp = null,
    Object? isPromoted = null,
  }) {
    return _then(_$StoryItemImpl(
      id: null == id
          ? _value.id
          : id // ignore: cast_nullable_to_non_nullable
              as String,
      ownerName: null == ownerName
          ? _value.ownerName
          : ownerName // ignore: cast_nullable_to_non_nullable
              as String,
      avatarUrl: null == avatarUrl
          ? _value.avatarUrl
          : avatarUrl // ignore: cast_nullable_to_non_nullable
              as String,
      content: null == content
          ? _value.content
          : content // ignore: cast_nullable_to_non_nullable
              as String,
      contentType: null == contentType
          ? _value.contentType
          : contentType // ignore: cast_nullable_to_non_nullable
              as StoryContentType,
      caption: null == caption
          ? _value.caption
          : caption // ignore: cast_nullable_to_non_nullable
              as String,
      isViewed: null == isViewed
          ? _value.isViewed
          : isViewed // ignore: cast_nullable_to_non_nullable
              as bool,
      timestamp: null == timestamp
          ? _value.timestamp
          : timestamp // ignore: cast_nullable_to_non_nullable
              as DateTime,
      isPromoted: null == isPromoted
          ? _value.isPromoted
          : isPromoted // ignore: cast_nullable_to_non_nullable
              as bool,
    ));
  }
}

/// @nodoc
@JsonSerializable()
class _$StoryItemImpl implements _StoryItem {
  const _$StoryItemImpl(
      {required this.id,
      required this.ownerName,
      required this.avatarUrl,
      required this.content,
      required this.contentType,
      required this.caption,
      required this.isViewed,
      required this.timestamp,
      required this.isPromoted});

  factory _$StoryItemImpl.fromJson(Map<String, dynamic> json) =>
      _$$StoryItemImplFromJson(json);

  @override
  final String id;
  @override
  final String ownerName;
  @override
  final String avatarUrl;
  @override
  final String content;
  @override
  final StoryContentType contentType;
  @override
  final String caption;
  @override
  final bool isViewed;
  @override
  final DateTime timestamp;
  @override
  final bool isPromoted;

  @override
  String toString() {
    return 'StoryItem(id: $id, ownerName: $ownerName, avatarUrl: $avatarUrl, content: $content, contentType: $contentType, caption: $caption, isViewed: $isViewed, timestamp: $timestamp, isPromoted: $isPromoted)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$StoryItemImpl &&
            (identical(other.id, id) || other.id == id) &&
            (identical(other.ownerName, ownerName) ||
                other.ownerName == ownerName) &&
            (identical(other.avatarUrl, avatarUrl) ||
                other.avatarUrl == avatarUrl) &&
            (identical(other.content, content) || other.content == content) &&
            (identical(other.contentType, contentType) ||
                other.contentType == contentType) &&
            (identical(other.caption, caption) || other.caption == caption) &&
            (identical(other.isViewed, isViewed) ||
                other.isViewed == isViewed) &&
            (identical(other.timestamp, timestamp) ||
                other.timestamp == timestamp) &&
            (identical(other.isPromoted, isPromoted) ||
                other.isPromoted == isPromoted));
  }

  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  int get hashCode => Object.hash(runtimeType, id, ownerName, avatarUrl,
      content, contentType, caption, isViewed, timestamp, isPromoted);

  /// Create a copy of StoryItem
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  @pragma('vm:prefer-inline')
  _$$StoryItemImplCopyWith<_$StoryItemImpl> get copyWith =>
      __$$StoryItemImplCopyWithImpl<_$StoryItemImpl>(this, _$identity);

  @override
  Map<String, dynamic> toJson() {
    return _$$StoryItemImplToJson(
      this,
    );
  }
}

abstract class _StoryItem implements StoryItem {
  const factory _StoryItem(
      {required final String id,
      required final String ownerName,
      required final String avatarUrl,
      required final String content,
      required final StoryContentType contentType,
      required final String caption,
      required final bool isViewed,
      required final DateTime timestamp,
      required final bool isPromoted}) = _$StoryItemImpl;

  factory _StoryItem.fromJson(Map<String, dynamic> json) =
      _$StoryItemImpl.fromJson;

  @override
  String get id;
  @override
  String get ownerName;
  @override
  String get avatarUrl;
  @override
  String get content;
  @override
  StoryContentType get contentType;
  @override
  String get caption;
  @override
  bool get isViewed;
  @override
  DateTime get timestamp;
  @override
  bool get isPromoted;

  /// Create a copy of StoryItem
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$StoryItemImplCopyWith<_$StoryItemImpl> get copyWith =>
      throw _privateConstructorUsedError;
}

ClinicItem _$ClinicItemFromJson(Map<String, dynamic> json) {
  return _ClinicItem.fromJson(json);
}

/// @nodoc
mixin _$ClinicItem {
  String get id => throw _privateConstructorUsedError;
  String get name => throw _privateConstructorUsedError;
  String get imageUrl => throw _privateConstructorUsedError;
  double get rating => throw _privateConstructorUsedError;
  int get reviewCount => throw _privateConstructorUsedError;
  bool get isPromoted => throw _privateConstructorUsedError;
  bool get isVerified => throw _privateConstructorUsedError;
  String get location => throw _privateConstructorUsedError;

  /// Serializes this ClinicItem to a JSON map.
  Map<String, dynamic> toJson() => throw _privateConstructorUsedError;

  /// Create a copy of ClinicItem
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  $ClinicItemCopyWith<ClinicItem> get copyWith =>
      throw _privateConstructorUsedError;
}

/// @nodoc
abstract class $ClinicItemCopyWith<$Res> {
  factory $ClinicItemCopyWith(
          ClinicItem value, $Res Function(ClinicItem) then) =
      _$ClinicItemCopyWithImpl<$Res, ClinicItem>;
  @useResult
  $Res call(
      {String id,
      String name,
      String imageUrl,
      double rating,
      int reviewCount,
      bool isPromoted,
      bool isVerified,
      String location});
}

/// @nodoc
class _$ClinicItemCopyWithImpl<$Res, $Val extends ClinicItem>
    implements $ClinicItemCopyWith<$Res> {
  _$ClinicItemCopyWithImpl(this._value, this._then);

  // ignore: unused_field
  final $Val _value;
  // ignore: unused_field
  final $Res Function($Val) _then;

  /// Create a copy of ClinicItem
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? name = null,
    Object? imageUrl = null,
    Object? rating = null,
    Object? reviewCount = null,
    Object? isPromoted = null,
    Object? isVerified = null,
    Object? location = null,
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
      imageUrl: null == imageUrl
          ? _value.imageUrl
          : imageUrl // ignore: cast_nullable_to_non_nullable
              as String,
      rating: null == rating
          ? _value.rating
          : rating // ignore: cast_nullable_to_non_nullable
              as double,
      reviewCount: null == reviewCount
          ? _value.reviewCount
          : reviewCount // ignore: cast_nullable_to_non_nullable
              as int,
      isPromoted: null == isPromoted
          ? _value.isPromoted
          : isPromoted // ignore: cast_nullable_to_non_nullable
              as bool,
      isVerified: null == isVerified
          ? _value.isVerified
          : isVerified // ignore: cast_nullable_to_non_nullable
              as bool,
      location: null == location
          ? _value.location
          : location // ignore: cast_nullable_to_non_nullable
              as String,
    ) as $Val);
  }
}

/// @nodoc
abstract class _$$ClinicItemImplCopyWith<$Res>
    implements $ClinicItemCopyWith<$Res> {
  factory _$$ClinicItemImplCopyWith(
          _$ClinicItemImpl value, $Res Function(_$ClinicItemImpl) then) =
      __$$ClinicItemImplCopyWithImpl<$Res>;
  @override
  @useResult
  $Res call(
      {String id,
      String name,
      String imageUrl,
      double rating,
      int reviewCount,
      bool isPromoted,
      bool isVerified,
      String location});
}

/// @nodoc
class __$$ClinicItemImplCopyWithImpl<$Res>
    extends _$ClinicItemCopyWithImpl<$Res, _$ClinicItemImpl>
    implements _$$ClinicItemImplCopyWith<$Res> {
  __$$ClinicItemImplCopyWithImpl(
      _$ClinicItemImpl _value, $Res Function(_$ClinicItemImpl) _then)
      : super(_value, _then);

  /// Create a copy of ClinicItem
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? name = null,
    Object? imageUrl = null,
    Object? rating = null,
    Object? reviewCount = null,
    Object? isPromoted = null,
    Object? isVerified = null,
    Object? location = null,
  }) {
    return _then(_$ClinicItemImpl(
      id: null == id
          ? _value.id
          : id // ignore: cast_nullable_to_non_nullable
              as String,
      name: null == name
          ? _value.name
          : name // ignore: cast_nullable_to_non_nullable
              as String,
      imageUrl: null == imageUrl
          ? _value.imageUrl
          : imageUrl // ignore: cast_nullable_to_non_nullable
              as String,
      rating: null == rating
          ? _value.rating
          : rating // ignore: cast_nullable_to_non_nullable
              as double,
      reviewCount: null == reviewCount
          ? _value.reviewCount
          : reviewCount // ignore: cast_nullable_to_non_nullable
              as int,
      isPromoted: null == isPromoted
          ? _value.isPromoted
          : isPromoted // ignore: cast_nullable_to_non_nullable
              as bool,
      isVerified: null == isVerified
          ? _value.isVerified
          : isVerified // ignore: cast_nullable_to_non_nullable
              as bool,
      location: null == location
          ? _value.location
          : location // ignore: cast_nullable_to_non_nullable
              as String,
    ));
  }
}

/// @nodoc
@JsonSerializable()
class _$ClinicItemImpl implements _ClinicItem {
  const _$ClinicItemImpl(
      {required this.id,
      required this.name,
      required this.imageUrl,
      required this.rating,
      required this.reviewCount,
      required this.isPromoted,
      required this.isVerified,
      required this.location});

  factory _$ClinicItemImpl.fromJson(Map<String, dynamic> json) =>
      _$$ClinicItemImplFromJson(json);

  @override
  final String id;
  @override
  final String name;
  @override
  final String imageUrl;
  @override
  final double rating;
  @override
  final int reviewCount;
  @override
  final bool isPromoted;
  @override
  final bool isVerified;
  @override
  final String location;

  @override
  String toString() {
    return 'ClinicItem(id: $id, name: $name, imageUrl: $imageUrl, rating: $rating, reviewCount: $reviewCount, isPromoted: $isPromoted, isVerified: $isVerified, location: $location)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$ClinicItemImpl &&
            (identical(other.id, id) || other.id == id) &&
            (identical(other.name, name) || other.name == name) &&
            (identical(other.imageUrl, imageUrl) ||
                other.imageUrl == imageUrl) &&
            (identical(other.rating, rating) || other.rating == rating) &&
            (identical(other.reviewCount, reviewCount) ||
                other.reviewCount == reviewCount) &&
            (identical(other.isPromoted, isPromoted) ||
                other.isPromoted == isPromoted) &&
            (identical(other.isVerified, isVerified) ||
                other.isVerified == isVerified) &&
            (identical(other.location, location) ||
                other.location == location));
  }

  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  int get hashCode => Object.hash(runtimeType, id, name, imageUrl, rating,
      reviewCount, isPromoted, isVerified, location);

  /// Create a copy of ClinicItem
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  @pragma('vm:prefer-inline')
  _$$ClinicItemImplCopyWith<_$ClinicItemImpl> get copyWith =>
      __$$ClinicItemImplCopyWithImpl<_$ClinicItemImpl>(this, _$identity);

  @override
  Map<String, dynamic> toJson() {
    return _$$ClinicItemImplToJson(
      this,
    );
  }
}

abstract class _ClinicItem implements ClinicItem {
  const factory _ClinicItem(
      {required final String id,
      required final String name,
      required final String imageUrl,
      required final double rating,
      required final int reviewCount,
      required final bool isPromoted,
      required final bool isVerified,
      required final String location}) = _$ClinicItemImpl;

  factory _ClinicItem.fromJson(Map<String, dynamic> json) =
      _$ClinicItemImpl.fromJson;

  @override
  String get id;
  @override
  String get name;
  @override
  String get imageUrl;
  @override
  double get rating;
  @override
  int get reviewCount;
  @override
  bool get isPromoted;
  @override
  bool get isVerified;
  @override
  String get location;

  /// Create a copy of ClinicItem
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$ClinicItemImplCopyWith<_$ClinicItemImpl> get copyWith =>
      throw _privateConstructorUsedError;
}

DoctorItem _$DoctorItemFromJson(Map<String, dynamic> json) {
  return _DoctorItem.fromJson(json);
}

/// @nodoc
mixin _$DoctorItem {
  String get id => throw _privateConstructorUsedError;
  String get name => throw _privateConstructorUsedError;
  String get specialty => throw _privateConstructorUsedError;
  String get avatarUrl => throw _privateConstructorUsedError;
  double get rating => throw _privateConstructorUsedError;
  int get reviewCount => throw _privateConstructorUsedError;
  String get experience => throw _privateConstructorUsedError;
  List<String> get languages => throw _privateConstructorUsedError;
  bool get isOnline => throw _privateConstructorUsedError;
  bool get isFavorite => throw _privateConstructorUsedError;
  String get clinicName => throw _privateConstructorUsedError;
  int get consultationFee => throw _privateConstructorUsedError;

  /// Serializes this DoctorItem to a JSON map.
  Map<String, dynamic> toJson() => throw _privateConstructorUsedError;

  /// Create a copy of DoctorItem
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  $DoctorItemCopyWith<DoctorItem> get copyWith =>
      throw _privateConstructorUsedError;
}

/// @nodoc
abstract class $DoctorItemCopyWith<$Res> {
  factory $DoctorItemCopyWith(
          DoctorItem value, $Res Function(DoctorItem) then) =
      _$DoctorItemCopyWithImpl<$Res, DoctorItem>;
  @useResult
  $Res call(
      {String id,
      String name,
      String specialty,
      String avatarUrl,
      double rating,
      int reviewCount,
      String experience,
      List<String> languages,
      bool isOnline,
      bool isFavorite,
      String clinicName,
      int consultationFee});
}

/// @nodoc
class _$DoctorItemCopyWithImpl<$Res, $Val extends DoctorItem>
    implements $DoctorItemCopyWith<$Res> {
  _$DoctorItemCopyWithImpl(this._value, this._then);

  // ignore: unused_field
  final $Val _value;
  // ignore: unused_field
  final $Res Function($Val) _then;

  /// Create a copy of DoctorItem
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? name = null,
    Object? specialty = null,
    Object? avatarUrl = null,
    Object? rating = null,
    Object? reviewCount = null,
    Object? experience = null,
    Object? languages = null,
    Object? isOnline = null,
    Object? isFavorite = null,
    Object? clinicName = null,
    Object? consultationFee = null,
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
      specialty: null == specialty
          ? _value.specialty
          : specialty // ignore: cast_nullable_to_non_nullable
              as String,
      avatarUrl: null == avatarUrl
          ? _value.avatarUrl
          : avatarUrl // ignore: cast_nullable_to_non_nullable
              as String,
      rating: null == rating
          ? _value.rating
          : rating // ignore: cast_nullable_to_non_nullable
              as double,
      reviewCount: null == reviewCount
          ? _value.reviewCount
          : reviewCount // ignore: cast_nullable_to_non_nullable
              as int,
      experience: null == experience
          ? _value.experience
          : experience // ignore: cast_nullable_to_non_nullable
              as String,
      languages: null == languages
          ? _value.languages
          : languages // ignore: cast_nullable_to_non_nullable
              as List<String>,
      isOnline: null == isOnline
          ? _value.isOnline
          : isOnline // ignore: cast_nullable_to_non_nullable
              as bool,
      isFavorite: null == isFavorite
          ? _value.isFavorite
          : isFavorite // ignore: cast_nullable_to_non_nullable
              as bool,
      clinicName: null == clinicName
          ? _value.clinicName
          : clinicName // ignore: cast_nullable_to_non_nullable
              as String,
      consultationFee: null == consultationFee
          ? _value.consultationFee
          : consultationFee // ignore: cast_nullable_to_non_nullable
              as int,
    ) as $Val);
  }
}

/// @nodoc
abstract class _$$DoctorItemImplCopyWith<$Res>
    implements $DoctorItemCopyWith<$Res> {
  factory _$$DoctorItemImplCopyWith(
          _$DoctorItemImpl value, $Res Function(_$DoctorItemImpl) then) =
      __$$DoctorItemImplCopyWithImpl<$Res>;
  @override
  @useResult
  $Res call(
      {String id,
      String name,
      String specialty,
      String avatarUrl,
      double rating,
      int reviewCount,
      String experience,
      List<String> languages,
      bool isOnline,
      bool isFavorite,
      String clinicName,
      int consultationFee});
}

/// @nodoc
class __$$DoctorItemImplCopyWithImpl<$Res>
    extends _$DoctorItemCopyWithImpl<$Res, _$DoctorItemImpl>
    implements _$$DoctorItemImplCopyWith<$Res> {
  __$$DoctorItemImplCopyWithImpl(
      _$DoctorItemImpl _value, $Res Function(_$DoctorItemImpl) _then)
      : super(_value, _then);

  /// Create a copy of DoctorItem
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? name = null,
    Object? specialty = null,
    Object? avatarUrl = null,
    Object? rating = null,
    Object? reviewCount = null,
    Object? experience = null,
    Object? languages = null,
    Object? isOnline = null,
    Object? isFavorite = null,
    Object? clinicName = null,
    Object? consultationFee = null,
  }) {
    return _then(_$DoctorItemImpl(
      id: null == id
          ? _value.id
          : id // ignore: cast_nullable_to_non_nullable
              as String,
      name: null == name
          ? _value.name
          : name // ignore: cast_nullable_to_non_nullable
              as String,
      specialty: null == specialty
          ? _value.specialty
          : specialty // ignore: cast_nullable_to_non_nullable
              as String,
      avatarUrl: null == avatarUrl
          ? _value.avatarUrl
          : avatarUrl // ignore: cast_nullable_to_non_nullable
              as String,
      rating: null == rating
          ? _value.rating
          : rating // ignore: cast_nullable_to_non_nullable
              as double,
      reviewCount: null == reviewCount
          ? _value.reviewCount
          : reviewCount // ignore: cast_nullable_to_non_nullable
              as int,
      experience: null == experience
          ? _value.experience
          : experience // ignore: cast_nullable_to_non_nullable
              as String,
      languages: null == languages
          ? _value._languages
          : languages // ignore: cast_nullable_to_non_nullable
              as List<String>,
      isOnline: null == isOnline
          ? _value.isOnline
          : isOnline // ignore: cast_nullable_to_non_nullable
              as bool,
      isFavorite: null == isFavorite
          ? _value.isFavorite
          : isFavorite // ignore: cast_nullable_to_non_nullable
              as bool,
      clinicName: null == clinicName
          ? _value.clinicName
          : clinicName // ignore: cast_nullable_to_non_nullable
              as String,
      consultationFee: null == consultationFee
          ? _value.consultationFee
          : consultationFee // ignore: cast_nullable_to_non_nullable
              as int,
    ));
  }
}

/// @nodoc
@JsonSerializable()
class _$DoctorItemImpl implements _DoctorItem {
  const _$DoctorItemImpl(
      {required this.id,
      required this.name,
      required this.specialty,
      required this.avatarUrl,
      required this.rating,
      required this.reviewCount,
      required this.experience,
      required final List<String> languages,
      required this.isOnline,
      required this.isFavorite,
      required this.clinicName,
      required this.consultationFee})
      : _languages = languages;

  factory _$DoctorItemImpl.fromJson(Map<String, dynamic> json) =>
      _$$DoctorItemImplFromJson(json);

  @override
  final String id;
  @override
  final String name;
  @override
  final String specialty;
  @override
  final String avatarUrl;
  @override
  final double rating;
  @override
  final int reviewCount;
  @override
  final String experience;
  final List<String> _languages;
  @override
  List<String> get languages {
    if (_languages is EqualUnmodifiableListView) return _languages;
    // ignore: implicit_dynamic_type
    return EqualUnmodifiableListView(_languages);
  }

  @override
  final bool isOnline;
  @override
  final bool isFavorite;
  @override
  final String clinicName;
  @override
  final int consultationFee;

  @override
  String toString() {
    return 'DoctorItem(id: $id, name: $name, specialty: $specialty, avatarUrl: $avatarUrl, rating: $rating, reviewCount: $reviewCount, experience: $experience, languages: $languages, isOnline: $isOnline, isFavorite: $isFavorite, clinicName: $clinicName, consultationFee: $consultationFee)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$DoctorItemImpl &&
            (identical(other.id, id) || other.id == id) &&
            (identical(other.name, name) || other.name == name) &&
            (identical(other.specialty, specialty) ||
                other.specialty == specialty) &&
            (identical(other.avatarUrl, avatarUrl) ||
                other.avatarUrl == avatarUrl) &&
            (identical(other.rating, rating) || other.rating == rating) &&
            (identical(other.reviewCount, reviewCount) ||
                other.reviewCount == reviewCount) &&
            (identical(other.experience, experience) ||
                other.experience == experience) &&
            const DeepCollectionEquality()
                .equals(other._languages, _languages) &&
            (identical(other.isOnline, isOnline) ||
                other.isOnline == isOnline) &&
            (identical(other.isFavorite, isFavorite) ||
                other.isFavorite == isFavorite) &&
            (identical(other.clinicName, clinicName) ||
                other.clinicName == clinicName) &&
            (identical(other.consultationFee, consultationFee) ||
                other.consultationFee == consultationFee));
  }

  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  int get hashCode => Object.hash(
      runtimeType,
      id,
      name,
      specialty,
      avatarUrl,
      rating,
      reviewCount,
      experience,
      const DeepCollectionEquality().hash(_languages),
      isOnline,
      isFavorite,
      clinicName,
      consultationFee);

  /// Create a copy of DoctorItem
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  @pragma('vm:prefer-inline')
  _$$DoctorItemImplCopyWith<_$DoctorItemImpl> get copyWith =>
      __$$DoctorItemImplCopyWithImpl<_$DoctorItemImpl>(this, _$identity);

  @override
  Map<String, dynamic> toJson() {
    return _$$DoctorItemImplToJson(
      this,
    );
  }
}

abstract class _DoctorItem implements DoctorItem {
  const factory _DoctorItem(
      {required final String id,
      required final String name,
      required final String specialty,
      required final String avatarUrl,
      required final double rating,
      required final int reviewCount,
      required final String experience,
      required final List<String> languages,
      required final bool isOnline,
      required final bool isFavorite,
      required final String clinicName,
      required final int consultationFee}) = _$DoctorItemImpl;

  factory _DoctorItem.fromJson(Map<String, dynamic> json) =
      _$DoctorItemImpl.fromJson;

  @override
  String get id;
  @override
  String get name;
  @override
  String get specialty;
  @override
  String get avatarUrl;
  @override
  double get rating;
  @override
  int get reviewCount;
  @override
  String get experience;
  @override
  List<String> get languages;
  @override
  bool get isOnline;
  @override
  bool get isFavorite;
  @override
  String get clinicName;
  @override
  int get consultationFee;

  /// Create a copy of DoctorItem
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$DoctorItemImplCopyWith<_$DoctorItemImpl> get copyWith =>
      throw _privateConstructorUsedError;
}

BeforeAfterItem _$BeforeAfterItemFromJson(Map<String, dynamic> json) {
  return _BeforeAfterItem.fromJson(json);
}

/// @nodoc
mixin _$BeforeAfterItem {
  String get id => throw _privateConstructorUsedError;
  String get beforeImageUrl => throw _privateConstructorUsedError;
  String get afterImageUrl => throw _privateConstructorUsedError;
  String get caption => throw _privateConstructorUsedError;
  String get doctorName => throw _privateConstructorUsedError;
  String get treatmentType => throw _privateConstructorUsedError;

  /// Serializes this BeforeAfterItem to a JSON map.
  Map<String, dynamic> toJson() => throw _privateConstructorUsedError;

  /// Create a copy of BeforeAfterItem
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  $BeforeAfterItemCopyWith<BeforeAfterItem> get copyWith =>
      throw _privateConstructorUsedError;
}

/// @nodoc
abstract class $BeforeAfterItemCopyWith<$Res> {
  factory $BeforeAfterItemCopyWith(
          BeforeAfterItem value, $Res Function(BeforeAfterItem) then) =
      _$BeforeAfterItemCopyWithImpl<$Res, BeforeAfterItem>;
  @useResult
  $Res call(
      {String id,
      String beforeImageUrl,
      String afterImageUrl,
      String caption,
      String doctorName,
      String treatmentType});
}

/// @nodoc
class _$BeforeAfterItemCopyWithImpl<$Res, $Val extends BeforeAfterItem>
    implements $BeforeAfterItemCopyWith<$Res> {
  _$BeforeAfterItemCopyWithImpl(this._value, this._then);

  // ignore: unused_field
  final $Val _value;
  // ignore: unused_field
  final $Res Function($Val) _then;

  /// Create a copy of BeforeAfterItem
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? beforeImageUrl = null,
    Object? afterImageUrl = null,
    Object? caption = null,
    Object? doctorName = null,
    Object? treatmentType = null,
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
      caption: null == caption
          ? _value.caption
          : caption // ignore: cast_nullable_to_non_nullable
              as String,
      doctorName: null == doctorName
          ? _value.doctorName
          : doctorName // ignore: cast_nullable_to_non_nullable
              as String,
      treatmentType: null == treatmentType
          ? _value.treatmentType
          : treatmentType // ignore: cast_nullable_to_non_nullable
              as String,
    ) as $Val);
  }
}

/// @nodoc
abstract class _$$BeforeAfterItemImplCopyWith<$Res>
    implements $BeforeAfterItemCopyWith<$Res> {
  factory _$$BeforeAfterItemImplCopyWith(_$BeforeAfterItemImpl value,
          $Res Function(_$BeforeAfterItemImpl) then) =
      __$$BeforeAfterItemImplCopyWithImpl<$Res>;
  @override
  @useResult
  $Res call(
      {String id,
      String beforeImageUrl,
      String afterImageUrl,
      String caption,
      String doctorName,
      String treatmentType});
}

/// @nodoc
class __$$BeforeAfterItemImplCopyWithImpl<$Res>
    extends _$BeforeAfterItemCopyWithImpl<$Res, _$BeforeAfterItemImpl>
    implements _$$BeforeAfterItemImplCopyWith<$Res> {
  __$$BeforeAfterItemImplCopyWithImpl(
      _$BeforeAfterItemImpl _value, $Res Function(_$BeforeAfterItemImpl) _then)
      : super(_value, _then);

  /// Create a copy of BeforeAfterItem
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? beforeImageUrl = null,
    Object? afterImageUrl = null,
    Object? caption = null,
    Object? doctorName = null,
    Object? treatmentType = null,
  }) {
    return _then(_$BeforeAfterItemImpl(
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
      caption: null == caption
          ? _value.caption
          : caption // ignore: cast_nullable_to_non_nullable
              as String,
      doctorName: null == doctorName
          ? _value.doctorName
          : doctorName // ignore: cast_nullable_to_non_nullable
              as String,
      treatmentType: null == treatmentType
          ? _value.treatmentType
          : treatmentType // ignore: cast_nullable_to_non_nullable
              as String,
    ));
  }
}

/// @nodoc
@JsonSerializable()
class _$BeforeAfterItemImpl implements _BeforeAfterItem {
  const _$BeforeAfterItemImpl(
      {required this.id,
      required this.beforeImageUrl,
      required this.afterImageUrl,
      required this.caption,
      required this.doctorName,
      required this.treatmentType});

  factory _$BeforeAfterItemImpl.fromJson(Map<String, dynamic> json) =>
      _$$BeforeAfterItemImplFromJson(json);

  @override
  final String id;
  @override
  final String beforeImageUrl;
  @override
  final String afterImageUrl;
  @override
  final String caption;
  @override
  final String doctorName;
  @override
  final String treatmentType;

  @override
  String toString() {
    return 'BeforeAfterItem(id: $id, beforeImageUrl: $beforeImageUrl, afterImageUrl: $afterImageUrl, caption: $caption, doctorName: $doctorName, treatmentType: $treatmentType)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$BeforeAfterItemImpl &&
            (identical(other.id, id) || other.id == id) &&
            (identical(other.beforeImageUrl, beforeImageUrl) ||
                other.beforeImageUrl == beforeImageUrl) &&
            (identical(other.afterImageUrl, afterImageUrl) ||
                other.afterImageUrl == afterImageUrl) &&
            (identical(other.caption, caption) || other.caption == caption) &&
            (identical(other.doctorName, doctorName) ||
                other.doctorName == doctorName) &&
            (identical(other.treatmentType, treatmentType) ||
                other.treatmentType == treatmentType));
  }

  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  int get hashCode => Object.hash(runtimeType, id, beforeImageUrl,
      afterImageUrl, caption, doctorName, treatmentType);

  /// Create a copy of BeforeAfterItem
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  @pragma('vm:prefer-inline')
  _$$BeforeAfterItemImplCopyWith<_$BeforeAfterItemImpl> get copyWith =>
      __$$BeforeAfterItemImplCopyWithImpl<_$BeforeAfterItemImpl>(
          this, _$identity);

  @override
  Map<String, dynamic> toJson() {
    return _$$BeforeAfterItemImplToJson(
      this,
    );
  }
}

abstract class _BeforeAfterItem implements BeforeAfterItem {
  const factory _BeforeAfterItem(
      {required final String id,
      required final String beforeImageUrl,
      required final String afterImageUrl,
      required final String caption,
      required final String doctorName,
      required final String treatmentType}) = _$BeforeAfterItemImpl;

  factory _BeforeAfterItem.fromJson(Map<String, dynamic> json) =
      _$BeforeAfterItemImpl.fromJson;

  @override
  String get id;
  @override
  String get beforeImageUrl;
  @override
  String get afterImageUrl;
  @override
  String get caption;
  @override
  String get doctorName;
  @override
  String get treatmentType;

  /// Create a copy of BeforeAfterItem
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$BeforeAfterItemImplCopyWith<_$BeforeAfterItemImpl> get copyWith =>
      throw _privateConstructorUsedError;
}

BeforeAfterCase _$BeforeAfterCaseFromJson(Map<String, dynamic> json) {
  return _BeforeAfterCase.fromJson(json);
}

/// @nodoc
mixin _$BeforeAfterCase {
  String get id => throw _privateConstructorUsedError;
  String get title => throw _privateConstructorUsedError;
  String get beforeImageUrl => throw _privateConstructorUsedError;
  String get afterImageUrl => throw _privateConstructorUsedError;
  String get description => throw _privateConstructorUsedError;
  String get doctorName => throw _privateConstructorUsedError;
  String get duration => throw _privateConstructorUsedError;
  bool get isFeatured => throw _privateConstructorUsedError;
  int get likes => throw _privateConstructorUsedError;
  int get comments => throw _privateConstructorUsedError;

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
      String beforeImageUrl,
      String afterImageUrl,
      String description,
      String doctorName,
      String duration,
      bool isFeatured,
      int likes,
      int comments});
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
    Object? beforeImageUrl = null,
    Object? afterImageUrl = null,
    Object? description = null,
    Object? doctorName = null,
    Object? duration = null,
    Object? isFeatured = null,
    Object? likes = null,
    Object? comments = null,
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
      beforeImageUrl: null == beforeImageUrl
          ? _value.beforeImageUrl
          : beforeImageUrl // ignore: cast_nullable_to_non_nullable
              as String,
      afterImageUrl: null == afterImageUrl
          ? _value.afterImageUrl
          : afterImageUrl // ignore: cast_nullable_to_non_nullable
              as String,
      description: null == description
          ? _value.description
          : description // ignore: cast_nullable_to_non_nullable
              as String,
      doctorName: null == doctorName
          ? _value.doctorName
          : doctorName // ignore: cast_nullable_to_non_nullable
              as String,
      duration: null == duration
          ? _value.duration
          : duration // ignore: cast_nullable_to_non_nullable
              as String,
      isFeatured: null == isFeatured
          ? _value.isFeatured
          : isFeatured // ignore: cast_nullable_to_non_nullable
              as bool,
      likes: null == likes
          ? _value.likes
          : likes // ignore: cast_nullable_to_non_nullable
              as int,
      comments: null == comments
          ? _value.comments
          : comments // ignore: cast_nullable_to_non_nullable
              as int,
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
      String beforeImageUrl,
      String afterImageUrl,
      String description,
      String doctorName,
      String duration,
      bool isFeatured,
      int likes,
      int comments});
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
    Object? beforeImageUrl = null,
    Object? afterImageUrl = null,
    Object? description = null,
    Object? doctorName = null,
    Object? duration = null,
    Object? isFeatured = null,
    Object? likes = null,
    Object? comments = null,
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
      beforeImageUrl: null == beforeImageUrl
          ? _value.beforeImageUrl
          : beforeImageUrl // ignore: cast_nullable_to_non_nullable
              as String,
      afterImageUrl: null == afterImageUrl
          ? _value.afterImageUrl
          : afterImageUrl // ignore: cast_nullable_to_non_nullable
              as String,
      description: null == description
          ? _value.description
          : description // ignore: cast_nullable_to_non_nullable
              as String,
      doctorName: null == doctorName
          ? _value.doctorName
          : doctorName // ignore: cast_nullable_to_non_nullable
              as String,
      duration: null == duration
          ? _value.duration
          : duration // ignore: cast_nullable_to_non_nullable
              as String,
      isFeatured: null == isFeatured
          ? _value.isFeatured
          : isFeatured // ignore: cast_nullable_to_non_nullable
              as bool,
      likes: null == likes
          ? _value.likes
          : likes // ignore: cast_nullable_to_non_nullable
              as int,
      comments: null == comments
          ? _value.comments
          : comments // ignore: cast_nullable_to_non_nullable
              as int,
    ));
  }
}

/// @nodoc
@JsonSerializable()
class _$BeforeAfterCaseImpl implements _BeforeAfterCase {
  const _$BeforeAfterCaseImpl(
      {required this.id,
      required this.title,
      required this.beforeImageUrl,
      required this.afterImageUrl,
      required this.description,
      required this.doctorName,
      required this.duration,
      required this.isFeatured,
      required this.likes,
      required this.comments});

  factory _$BeforeAfterCaseImpl.fromJson(Map<String, dynamic> json) =>
      _$$BeforeAfterCaseImplFromJson(json);

  @override
  final String id;
  @override
  final String title;
  @override
  final String beforeImageUrl;
  @override
  final String afterImageUrl;
  @override
  final String description;
  @override
  final String doctorName;
  @override
  final String duration;
  @override
  final bool isFeatured;
  @override
  final int likes;
  @override
  final int comments;

  @override
  String toString() {
    return 'BeforeAfterCase(id: $id, title: $title, beforeImageUrl: $beforeImageUrl, afterImageUrl: $afterImageUrl, description: $description, doctorName: $doctorName, duration: $duration, isFeatured: $isFeatured, likes: $likes, comments: $comments)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$BeforeAfterCaseImpl &&
            (identical(other.id, id) || other.id == id) &&
            (identical(other.title, title) || other.title == title) &&
            (identical(other.beforeImageUrl, beforeImageUrl) ||
                other.beforeImageUrl == beforeImageUrl) &&
            (identical(other.afterImageUrl, afterImageUrl) ||
                other.afterImageUrl == afterImageUrl) &&
            (identical(other.description, description) ||
                other.description == description) &&
            (identical(other.doctorName, doctorName) ||
                other.doctorName == doctorName) &&
            (identical(other.duration, duration) ||
                other.duration == duration) &&
            (identical(other.isFeatured, isFeatured) ||
                other.isFeatured == isFeatured) &&
            (identical(other.likes, likes) || other.likes == likes) &&
            (identical(other.comments, comments) ||
                other.comments == comments));
  }

  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  int get hashCode => Object.hash(
      runtimeType,
      id,
      title,
      beforeImageUrl,
      afterImageUrl,
      description,
      doctorName,
      duration,
      isFeatured,
      likes,
      comments);

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
      required final String beforeImageUrl,
      required final String afterImageUrl,
      required final String description,
      required final String doctorName,
      required final String duration,
      required final bool isFeatured,
      required final int likes,
      required final int comments}) = _$BeforeAfterCaseImpl;

  factory _BeforeAfterCase.fromJson(Map<String, dynamic> json) =
      _$BeforeAfterCaseImpl.fromJson;

  @override
  String get id;
  @override
  String get title;
  @override
  String get beforeImageUrl;
  @override
  String get afterImageUrl;
  @override
  String get description;
  @override
  String get doctorName;
  @override
  String get duration;
  @override
  bool get isFeatured;
  @override
  int get likes;
  @override
  int get comments;

  /// Create a copy of BeforeAfterCase
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$BeforeAfterCaseImplCopyWith<_$BeforeAfterCaseImpl> get copyWith =>
      throw _privateConstructorUsedError;
}
