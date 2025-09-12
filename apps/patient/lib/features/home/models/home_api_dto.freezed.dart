// coverage:ignore-file
// GENERATED CODE - DO NOT MODIFY BY HAND
// ignore_for_file: type=lint
// ignore_for_file: unused_element, deprecated_member_use, deprecated_member_use_from_same_package, use_function_type_syntax_for_parameters, unnecessary_const, avoid_init_to_null, invalid_override_different_default_values_named, prefer_expression_function_bodies, annotate_overrides, invalid_annotation_target, unnecessary_question_mark

part of 'home_api_dto.dart';

// **************************************************************************
// FreezedGenerator
// **************************************************************************

T _$identity<T>(T value) => value;

final _privateConstructorUsedError = UnsupportedError(
    'It seems like you constructed your class using `MyClass._()`. This constructor is only meant to be used by freezed and you are not supposed to need it nor use it.\nPlease check the documentation here for more information: https://github.com/rrousselGit/freezed#adding-getters-and-methods-to-our-models');

HomeResponseDto _$HomeResponseDtoFromJson(Map<String, dynamic> json) {
  return _HomeResponseDto.fromJson(json);
}

/// @nodoc
mixin _$HomeResponseDto {
  bool get success => throw _privateConstructorUsedError;
  String get message => throw _privateConstructorUsedError;
  HomeDataDto get data => throw _privateConstructorUsedError;
  @JsonKey(name: 'action_flags')
  ActionFlagsDto? get actionFlags => throw _privateConstructorUsedError;
  String get locale => throw _privateConstructorUsedError;
  String get timestamp => throw _privateConstructorUsedError;
  @JsonKey(name: 'is_guest')
  bool get isGuest => throw _privateConstructorUsedError;
  @JsonKey(name: 'user_authenticated')
  bool get userAuthenticated => throw _privateConstructorUsedError;

  /// Serializes this HomeResponseDto to a JSON map.
  Map<String, dynamic> toJson() => throw _privateConstructorUsedError;

  /// Create a copy of HomeResponseDto
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  $HomeResponseDtoCopyWith<HomeResponseDto> get copyWith =>
      throw _privateConstructorUsedError;
}

/// @nodoc
abstract class $HomeResponseDtoCopyWith<$Res> {
  factory $HomeResponseDtoCopyWith(
          HomeResponseDto value, $Res Function(HomeResponseDto) then) =
      _$HomeResponseDtoCopyWithImpl<$Res, HomeResponseDto>;
  @useResult
  $Res call(
      {bool success,
      String message,
      HomeDataDto data,
      @JsonKey(name: 'action_flags') ActionFlagsDto? actionFlags,
      String locale,
      String timestamp,
      @JsonKey(name: 'is_guest') bool isGuest,
      @JsonKey(name: 'user_authenticated') bool userAuthenticated});

  $HomeDataDtoCopyWith<$Res> get data;
  $ActionFlagsDtoCopyWith<$Res>? get actionFlags;
}

/// @nodoc
class _$HomeResponseDtoCopyWithImpl<$Res, $Val extends HomeResponseDto>
    implements $HomeResponseDtoCopyWith<$Res> {
  _$HomeResponseDtoCopyWithImpl(this._value, this._then);

  // ignore: unused_field
  final $Val _value;
  // ignore: unused_field
  final $Res Function($Val) _then;

  /// Create a copy of HomeResponseDto
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? success = null,
    Object? message = null,
    Object? data = null,
    Object? actionFlags = freezed,
    Object? locale = null,
    Object? timestamp = null,
    Object? isGuest = null,
    Object? userAuthenticated = null,
  }) {
    return _then(_value.copyWith(
      success: null == success
          ? _value.success
          : success // ignore: cast_nullable_to_non_nullable
              as bool,
      message: null == message
          ? _value.message
          : message // ignore: cast_nullable_to_non_nullable
              as String,
      data: null == data
          ? _value.data
          : data // ignore: cast_nullable_to_non_nullable
              as HomeDataDto,
      actionFlags: freezed == actionFlags
          ? _value.actionFlags
          : actionFlags // ignore: cast_nullable_to_non_nullable
              as ActionFlagsDto?,
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

  /// Create a copy of HomeResponseDto
  /// with the given fields replaced by the non-null parameter values.
  @override
  @pragma('vm:prefer-inline')
  $HomeDataDtoCopyWith<$Res> get data {
    return $HomeDataDtoCopyWith<$Res>(_value.data, (value) {
      return _then(_value.copyWith(data: value) as $Val);
    });
  }

  /// Create a copy of HomeResponseDto
  /// with the given fields replaced by the non-null parameter values.
  @override
  @pragma('vm:prefer-inline')
  $ActionFlagsDtoCopyWith<$Res>? get actionFlags {
    if (_value.actionFlags == null) {
      return null;
    }

    return $ActionFlagsDtoCopyWith<$Res>(_value.actionFlags!, (value) {
      return _then(_value.copyWith(actionFlags: value) as $Val);
    });
  }
}

/// @nodoc
abstract class _$$HomeResponseDtoImplCopyWith<$Res>
    implements $HomeResponseDtoCopyWith<$Res> {
  factory _$$HomeResponseDtoImplCopyWith(_$HomeResponseDtoImpl value,
          $Res Function(_$HomeResponseDtoImpl) then) =
      __$$HomeResponseDtoImplCopyWithImpl<$Res>;
  @override
  @useResult
  $Res call(
      {bool success,
      String message,
      HomeDataDto data,
      @JsonKey(name: 'action_flags') ActionFlagsDto? actionFlags,
      String locale,
      String timestamp,
      @JsonKey(name: 'is_guest') bool isGuest,
      @JsonKey(name: 'user_authenticated') bool userAuthenticated});

  @override
  $HomeDataDtoCopyWith<$Res> get data;
  @override
  $ActionFlagsDtoCopyWith<$Res>? get actionFlags;
}

/// @nodoc
class __$$HomeResponseDtoImplCopyWithImpl<$Res>
    extends _$HomeResponseDtoCopyWithImpl<$Res, _$HomeResponseDtoImpl>
    implements _$$HomeResponseDtoImplCopyWith<$Res> {
  __$$HomeResponseDtoImplCopyWithImpl(
      _$HomeResponseDtoImpl _value, $Res Function(_$HomeResponseDtoImpl) _then)
      : super(_value, _then);

  /// Create a copy of HomeResponseDto
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? success = null,
    Object? message = null,
    Object? data = null,
    Object? actionFlags = freezed,
    Object? locale = null,
    Object? timestamp = null,
    Object? isGuest = null,
    Object? userAuthenticated = null,
  }) {
    return _then(_$HomeResponseDtoImpl(
      success: null == success
          ? _value.success
          : success // ignore: cast_nullable_to_non_nullable
              as bool,
      message: null == message
          ? _value.message
          : message // ignore: cast_nullable_to_non_nullable
              as String,
      data: null == data
          ? _value.data
          : data // ignore: cast_nullable_to_non_nullable
              as HomeDataDto,
      actionFlags: freezed == actionFlags
          ? _value.actionFlags
          : actionFlags // ignore: cast_nullable_to_non_nullable
              as ActionFlagsDto?,
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
class _$HomeResponseDtoImpl implements _HomeResponseDto {
  const _$HomeResponseDtoImpl(
      {required this.success,
      required this.message,
      required this.data,
      @JsonKey(name: 'action_flags') this.actionFlags,
      required this.locale,
      required this.timestamp,
      @JsonKey(name: 'is_guest') required this.isGuest,
      @JsonKey(name: 'user_authenticated') required this.userAuthenticated});

  factory _$HomeResponseDtoImpl.fromJson(Map<String, dynamic> json) =>
      _$$HomeResponseDtoImplFromJson(json);

  @override
  final bool success;
  @override
  final String message;
  @override
  final HomeDataDto data;
  @override
  @JsonKey(name: 'action_flags')
  final ActionFlagsDto? actionFlags;
  @override
  final String locale;
  @override
  final String timestamp;
  @override
  @JsonKey(name: 'is_guest')
  final bool isGuest;
  @override
  @JsonKey(name: 'user_authenticated')
  final bool userAuthenticated;

  @override
  String toString() {
    return 'HomeResponseDto(success: $success, message: $message, data: $data, actionFlags: $actionFlags, locale: $locale, timestamp: $timestamp, isGuest: $isGuest, userAuthenticated: $userAuthenticated)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$HomeResponseDtoImpl &&
            (identical(other.success, success) || other.success == success) &&
            (identical(other.message, message) || other.message == message) &&
            (identical(other.data, data) || other.data == data) &&
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
  int get hashCode => Object.hash(runtimeType, success, message, data,
      actionFlags, locale, timestamp, isGuest, userAuthenticated);

  /// Create a copy of HomeResponseDto
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  @pragma('vm:prefer-inline')
  _$$HomeResponseDtoImplCopyWith<_$HomeResponseDtoImpl> get copyWith =>
      __$$HomeResponseDtoImplCopyWithImpl<_$HomeResponseDtoImpl>(
          this, _$identity);

  @override
  Map<String, dynamic> toJson() {
    return _$$HomeResponseDtoImplToJson(
      this,
    );
  }
}

abstract class _HomeResponseDto implements HomeResponseDto {
  const factory _HomeResponseDto(
      {required final bool success,
      required final String message,
      required final HomeDataDto data,
      @JsonKey(name: 'action_flags') final ActionFlagsDto? actionFlags,
      required final String locale,
      required final String timestamp,
      @JsonKey(name: 'is_guest') required final bool isGuest,
      @JsonKey(name: 'user_authenticated')
      required final bool userAuthenticated}) = _$HomeResponseDtoImpl;

  factory _HomeResponseDto.fromJson(Map<String, dynamic> json) =
      _$HomeResponseDtoImpl.fromJson;

  @override
  bool get success;
  @override
  String get message;
  @override
  HomeDataDto get data;
  @override
  @JsonKey(name: 'action_flags')
  ActionFlagsDto? get actionFlags;
  @override
  String get locale;
  @override
  String get timestamp;
  @override
  @JsonKey(name: 'is_guest')
  bool get isGuest;
  @override
  @JsonKey(name: 'user_authenticated')
  bool get userAuthenticated;

  /// Create a copy of HomeResponseDto
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$HomeResponseDtoImplCopyWith<_$HomeResponseDtoImpl> get copyWith =>
      throw _privateConstructorUsedError;
}

HomeDataDto _$HomeDataDtoFromJson(Map<String, dynamic> json) {
  return _HomeDataDto.fromJson(json);
}

/// @nodoc
mixin _$HomeDataDto {
  List<StoryDto> get stories => throw _privateConstructorUsedError;
  @JsonKey(name: 'top_clinics')
  List<ClinicDto> get topClinics => throw _privateConstructorUsedError;
  @JsonKey(name: 'top_doctors')
  List<DoctorDto> get topDoctors => throw _privateConstructorUsedError;
  @JsonKey(name: 'before_after')
  List<BeforeAfterDto> get beforeAfter => throw _privateConstructorUsedError;

  /// Serializes this HomeDataDto to a JSON map.
  Map<String, dynamic> toJson() => throw _privateConstructorUsedError;

  /// Create a copy of HomeDataDto
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  $HomeDataDtoCopyWith<HomeDataDto> get copyWith =>
      throw _privateConstructorUsedError;
}

/// @nodoc
abstract class $HomeDataDtoCopyWith<$Res> {
  factory $HomeDataDtoCopyWith(
          HomeDataDto value, $Res Function(HomeDataDto) then) =
      _$HomeDataDtoCopyWithImpl<$Res, HomeDataDto>;
  @useResult
  $Res call(
      {List<StoryDto> stories,
      @JsonKey(name: 'top_clinics') List<ClinicDto> topClinics,
      @JsonKey(name: 'top_doctors') List<DoctorDto> topDoctors,
      @JsonKey(name: 'before_after') List<BeforeAfterDto> beforeAfter});
}

/// @nodoc
class _$HomeDataDtoCopyWithImpl<$Res, $Val extends HomeDataDto>
    implements $HomeDataDtoCopyWith<$Res> {
  _$HomeDataDtoCopyWithImpl(this._value, this._then);

  // ignore: unused_field
  final $Val _value;
  // ignore: unused_field
  final $Res Function($Val) _then;

  /// Create a copy of HomeDataDto
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? stories = null,
    Object? topClinics = null,
    Object? topDoctors = null,
    Object? beforeAfter = null,
  }) {
    return _then(_value.copyWith(
      stories: null == stories
          ? _value.stories
          : stories // ignore: cast_nullable_to_non_nullable
              as List<StoryDto>,
      topClinics: null == topClinics
          ? _value.topClinics
          : topClinics // ignore: cast_nullable_to_non_nullable
              as List<ClinicDto>,
      topDoctors: null == topDoctors
          ? _value.topDoctors
          : topDoctors // ignore: cast_nullable_to_non_nullable
              as List<DoctorDto>,
      beforeAfter: null == beforeAfter
          ? _value.beforeAfter
          : beforeAfter // ignore: cast_nullable_to_non_nullable
              as List<BeforeAfterDto>,
    ) as $Val);
  }
}

/// @nodoc
abstract class _$$HomeDataDtoImplCopyWith<$Res>
    implements $HomeDataDtoCopyWith<$Res> {
  factory _$$HomeDataDtoImplCopyWith(
          _$HomeDataDtoImpl value, $Res Function(_$HomeDataDtoImpl) then) =
      __$$HomeDataDtoImplCopyWithImpl<$Res>;
  @override
  @useResult
  $Res call(
      {List<StoryDto> stories,
      @JsonKey(name: 'top_clinics') List<ClinicDto> topClinics,
      @JsonKey(name: 'top_doctors') List<DoctorDto> topDoctors,
      @JsonKey(name: 'before_after') List<BeforeAfterDto> beforeAfter});
}

/// @nodoc
class __$$HomeDataDtoImplCopyWithImpl<$Res>
    extends _$HomeDataDtoCopyWithImpl<$Res, _$HomeDataDtoImpl>
    implements _$$HomeDataDtoImplCopyWith<$Res> {
  __$$HomeDataDtoImplCopyWithImpl(
      _$HomeDataDtoImpl _value, $Res Function(_$HomeDataDtoImpl) _then)
      : super(_value, _then);

  /// Create a copy of HomeDataDto
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? stories = null,
    Object? topClinics = null,
    Object? topDoctors = null,
    Object? beforeAfter = null,
  }) {
    return _then(_$HomeDataDtoImpl(
      stories: null == stories
          ? _value._stories
          : stories // ignore: cast_nullable_to_non_nullable
              as List<StoryDto>,
      topClinics: null == topClinics
          ? _value._topClinics
          : topClinics // ignore: cast_nullable_to_non_nullable
              as List<ClinicDto>,
      topDoctors: null == topDoctors
          ? _value._topDoctors
          : topDoctors // ignore: cast_nullable_to_non_nullable
              as List<DoctorDto>,
      beforeAfter: null == beforeAfter
          ? _value._beforeAfter
          : beforeAfter // ignore: cast_nullable_to_non_nullable
              as List<BeforeAfterDto>,
    ));
  }
}

/// @nodoc
@JsonSerializable()
class _$HomeDataDtoImpl implements _HomeDataDto {
  const _$HomeDataDtoImpl(
      {final List<StoryDto> stories = const [],
      @JsonKey(name: 'top_clinics') final List<ClinicDto> topClinics = const [],
      @JsonKey(name: 'top_doctors') final List<DoctorDto> topDoctors = const [],
      @JsonKey(name: 'before_after')
      final List<BeforeAfterDto> beforeAfter = const []})
      : _stories = stories,
        _topClinics = topClinics,
        _topDoctors = topDoctors,
        _beforeAfter = beforeAfter;

  factory _$HomeDataDtoImpl.fromJson(Map<String, dynamic> json) =>
      _$$HomeDataDtoImplFromJson(json);

  final List<StoryDto> _stories;
  @override
  @JsonKey()
  List<StoryDto> get stories {
    if (_stories is EqualUnmodifiableListView) return _stories;
    // ignore: implicit_dynamic_type
    return EqualUnmodifiableListView(_stories);
  }

  final List<ClinicDto> _topClinics;
  @override
  @JsonKey(name: 'top_clinics')
  List<ClinicDto> get topClinics {
    if (_topClinics is EqualUnmodifiableListView) return _topClinics;
    // ignore: implicit_dynamic_type
    return EqualUnmodifiableListView(_topClinics);
  }

  final List<DoctorDto> _topDoctors;
  @override
  @JsonKey(name: 'top_doctors')
  List<DoctorDto> get topDoctors {
    if (_topDoctors is EqualUnmodifiableListView) return _topDoctors;
    // ignore: implicit_dynamic_type
    return EqualUnmodifiableListView(_topDoctors);
  }

  final List<BeforeAfterDto> _beforeAfter;
  @override
  @JsonKey(name: 'before_after')
  List<BeforeAfterDto> get beforeAfter {
    if (_beforeAfter is EqualUnmodifiableListView) return _beforeAfter;
    // ignore: implicit_dynamic_type
    return EqualUnmodifiableListView(_beforeAfter);
  }

  @override
  String toString() {
    return 'HomeDataDto(stories: $stories, topClinics: $topClinics, topDoctors: $topDoctors, beforeAfter: $beforeAfter)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$HomeDataDtoImpl &&
            const DeepCollectionEquality().equals(other._stories, _stories) &&
            const DeepCollectionEquality()
                .equals(other._topClinics, _topClinics) &&
            const DeepCollectionEquality()
                .equals(other._topDoctors, _topDoctors) &&
            const DeepCollectionEquality()
                .equals(other._beforeAfter, _beforeAfter));
  }

  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  int get hashCode => Object.hash(
      runtimeType,
      const DeepCollectionEquality().hash(_stories),
      const DeepCollectionEquality().hash(_topClinics),
      const DeepCollectionEquality().hash(_topDoctors),
      const DeepCollectionEquality().hash(_beforeAfter));

  /// Create a copy of HomeDataDto
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  @pragma('vm:prefer-inline')
  _$$HomeDataDtoImplCopyWith<_$HomeDataDtoImpl> get copyWith =>
      __$$HomeDataDtoImplCopyWithImpl<_$HomeDataDtoImpl>(this, _$identity);

  @override
  Map<String, dynamic> toJson() {
    return _$$HomeDataDtoImplToJson(
      this,
    );
  }
}

abstract class _HomeDataDto implements HomeDataDto {
  const factory _HomeDataDto(
      {final List<StoryDto> stories,
      @JsonKey(name: 'top_clinics') final List<ClinicDto> topClinics,
      @JsonKey(name: 'top_doctors') final List<DoctorDto> topDoctors,
      @JsonKey(name: 'before_after')
      final List<BeforeAfterDto> beforeAfter}) = _$HomeDataDtoImpl;

  factory _HomeDataDto.fromJson(Map<String, dynamic> json) =
      _$HomeDataDtoImpl.fromJson;

  @override
  List<StoryDto> get stories;
  @override
  @JsonKey(name: 'top_clinics')
  List<ClinicDto> get topClinics;
  @override
  @JsonKey(name: 'top_doctors')
  List<DoctorDto> get topDoctors;
  @override
  @JsonKey(name: 'before_after')
  List<BeforeAfterDto> get beforeAfter;

  /// Create a copy of HomeDataDto
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$HomeDataDtoImplCopyWith<_$HomeDataDtoImpl> get copyWith =>
      throw _privateConstructorUsedError;
}

ActionFlagsDto _$ActionFlagsDtoFromJson(Map<String, dynamic> json) {
  return _ActionFlagsDto.fromJson(json);
}

/// @nodoc
mixin _$ActionFlagsDto {
  @JsonKey(name: 'can_favorite')
  bool get canFavorite => throw _privateConstructorUsedError;
  @JsonKey(name: 'can_book')
  bool get canBook => throw _privateConstructorUsedError;
  @JsonKey(name: 'can_message')
  bool get canMessage => throw _privateConstructorUsedError;

  /// Serializes this ActionFlagsDto to a JSON map.
  Map<String, dynamic> toJson() => throw _privateConstructorUsedError;

  /// Create a copy of ActionFlagsDto
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  $ActionFlagsDtoCopyWith<ActionFlagsDto> get copyWith =>
      throw _privateConstructorUsedError;
}

/// @nodoc
abstract class $ActionFlagsDtoCopyWith<$Res> {
  factory $ActionFlagsDtoCopyWith(
          ActionFlagsDto value, $Res Function(ActionFlagsDto) then) =
      _$ActionFlagsDtoCopyWithImpl<$Res, ActionFlagsDto>;
  @useResult
  $Res call(
      {@JsonKey(name: 'can_favorite') bool canFavorite,
      @JsonKey(name: 'can_book') bool canBook,
      @JsonKey(name: 'can_message') bool canMessage});
}

/// @nodoc
class _$ActionFlagsDtoCopyWithImpl<$Res, $Val extends ActionFlagsDto>
    implements $ActionFlagsDtoCopyWith<$Res> {
  _$ActionFlagsDtoCopyWithImpl(this._value, this._then);

  // ignore: unused_field
  final $Val _value;
  // ignore: unused_field
  final $Res Function($Val) _then;

  /// Create a copy of ActionFlagsDto
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
abstract class _$$ActionFlagsDtoImplCopyWith<$Res>
    implements $ActionFlagsDtoCopyWith<$Res> {
  factory _$$ActionFlagsDtoImplCopyWith(_$ActionFlagsDtoImpl value,
          $Res Function(_$ActionFlagsDtoImpl) then) =
      __$$ActionFlagsDtoImplCopyWithImpl<$Res>;
  @override
  @useResult
  $Res call(
      {@JsonKey(name: 'can_favorite') bool canFavorite,
      @JsonKey(name: 'can_book') bool canBook,
      @JsonKey(name: 'can_message') bool canMessage});
}

/// @nodoc
class __$$ActionFlagsDtoImplCopyWithImpl<$Res>
    extends _$ActionFlagsDtoCopyWithImpl<$Res, _$ActionFlagsDtoImpl>
    implements _$$ActionFlagsDtoImplCopyWith<$Res> {
  __$$ActionFlagsDtoImplCopyWithImpl(
      _$ActionFlagsDtoImpl _value, $Res Function(_$ActionFlagsDtoImpl) _then)
      : super(_value, _then);

  /// Create a copy of ActionFlagsDto
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? canFavorite = null,
    Object? canBook = null,
    Object? canMessage = null,
  }) {
    return _then(_$ActionFlagsDtoImpl(
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
class _$ActionFlagsDtoImpl implements _ActionFlagsDto {
  const _$ActionFlagsDtoImpl(
      {@JsonKey(name: 'can_favorite') this.canFavorite = false,
      @JsonKey(name: 'can_book') this.canBook = false,
      @JsonKey(name: 'can_message') this.canMessage = false});

  factory _$ActionFlagsDtoImpl.fromJson(Map<String, dynamic> json) =>
      _$$ActionFlagsDtoImplFromJson(json);

  @override
  @JsonKey(name: 'can_favorite')
  final bool canFavorite;
  @override
  @JsonKey(name: 'can_book')
  final bool canBook;
  @override
  @JsonKey(name: 'can_message')
  final bool canMessage;

  @override
  String toString() {
    return 'ActionFlagsDto(canFavorite: $canFavorite, canBook: $canBook, canMessage: $canMessage)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$ActionFlagsDtoImpl &&
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

  /// Create a copy of ActionFlagsDto
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  @pragma('vm:prefer-inline')
  _$$ActionFlagsDtoImplCopyWith<_$ActionFlagsDtoImpl> get copyWith =>
      __$$ActionFlagsDtoImplCopyWithImpl<_$ActionFlagsDtoImpl>(
          this, _$identity);

  @override
  Map<String, dynamic> toJson() {
    return _$$ActionFlagsDtoImplToJson(
      this,
    );
  }
}

abstract class _ActionFlagsDto implements ActionFlagsDto {
  const factory _ActionFlagsDto(
          {@JsonKey(name: 'can_favorite') final bool canFavorite,
          @JsonKey(name: 'can_book') final bool canBook,
          @JsonKey(name: 'can_message') final bool canMessage}) =
      _$ActionFlagsDtoImpl;

  factory _ActionFlagsDto.fromJson(Map<String, dynamic> json) =
      _$ActionFlagsDtoImpl.fromJson;

  @override
  @JsonKey(name: 'can_favorite')
  bool get canFavorite;
  @override
  @JsonKey(name: 'can_book')
  bool get canBook;
  @override
  @JsonKey(name: 'can_message')
  bool get canMessage;

  /// Create a copy of ActionFlagsDto
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$ActionFlagsDtoImplCopyWith<_$ActionFlagsDtoImpl> get copyWith =>
      throw _privateConstructorUsedError;
}

StoryDto _$StoryDtoFromJson(Map<String, dynamic> json) {
  return _StoryDto.fromJson(json);
}

/// @nodoc
mixin _$StoryDto {
  int get id => throw _privateConstructorUsedError;
  String get title => throw _privateConstructorUsedError;
  String get content => throw _privateConstructorUsedError;
  String get image => throw _privateConstructorUsedError;
  @JsonKey(name: 'created_at')
  String get createdAt => throw _privateConstructorUsedError;
  @JsonKey(name: 'likes_count')
  int get likesCount => throw _privateConstructorUsedError;

  /// Serializes this StoryDto to a JSON map.
  Map<String, dynamic> toJson() => throw _privateConstructorUsedError;

  /// Create a copy of StoryDto
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  $StoryDtoCopyWith<StoryDto> get copyWith =>
      throw _privateConstructorUsedError;
}

/// @nodoc
abstract class $StoryDtoCopyWith<$Res> {
  factory $StoryDtoCopyWith(StoryDto value, $Res Function(StoryDto) then) =
      _$StoryDtoCopyWithImpl<$Res, StoryDto>;
  @useResult
  $Res call(
      {int id,
      String title,
      String content,
      String image,
      @JsonKey(name: 'created_at') String createdAt,
      @JsonKey(name: 'likes_count') int likesCount});
}

/// @nodoc
class _$StoryDtoCopyWithImpl<$Res, $Val extends StoryDto>
    implements $StoryDtoCopyWith<$Res> {
  _$StoryDtoCopyWithImpl(this._value, this._then);

  // ignore: unused_field
  final $Val _value;
  // ignore: unused_field
  final $Res Function($Val) _then;

  /// Create a copy of StoryDto
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? title = null,
    Object? content = null,
    Object? image = null,
    Object? createdAt = null,
    Object? likesCount = null,
  }) {
    return _then(_value.copyWith(
      id: null == id
          ? _value.id
          : id // ignore: cast_nullable_to_non_nullable
              as int,
      title: null == title
          ? _value.title
          : title // ignore: cast_nullable_to_non_nullable
              as String,
      content: null == content
          ? _value.content
          : content // ignore: cast_nullable_to_non_nullable
              as String,
      image: null == image
          ? _value.image
          : image // ignore: cast_nullable_to_non_nullable
              as String,
      createdAt: null == createdAt
          ? _value.createdAt
          : createdAt // ignore: cast_nullable_to_non_nullable
              as String,
      likesCount: null == likesCount
          ? _value.likesCount
          : likesCount // ignore: cast_nullable_to_non_nullable
              as int,
    ) as $Val);
  }
}

/// @nodoc
abstract class _$$StoryDtoImplCopyWith<$Res>
    implements $StoryDtoCopyWith<$Res> {
  factory _$$StoryDtoImplCopyWith(
          _$StoryDtoImpl value, $Res Function(_$StoryDtoImpl) then) =
      __$$StoryDtoImplCopyWithImpl<$Res>;
  @override
  @useResult
  $Res call(
      {int id,
      String title,
      String content,
      String image,
      @JsonKey(name: 'created_at') String createdAt,
      @JsonKey(name: 'likes_count') int likesCount});
}

/// @nodoc
class __$$StoryDtoImplCopyWithImpl<$Res>
    extends _$StoryDtoCopyWithImpl<$Res, _$StoryDtoImpl>
    implements _$$StoryDtoImplCopyWith<$Res> {
  __$$StoryDtoImplCopyWithImpl(
      _$StoryDtoImpl _value, $Res Function(_$StoryDtoImpl) _then)
      : super(_value, _then);

  /// Create a copy of StoryDto
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? title = null,
    Object? content = null,
    Object? image = null,
    Object? createdAt = null,
    Object? likesCount = null,
  }) {
    return _then(_$StoryDtoImpl(
      id: null == id
          ? _value.id
          : id // ignore: cast_nullable_to_non_nullable
              as int,
      title: null == title
          ? _value.title
          : title // ignore: cast_nullable_to_non_nullable
              as String,
      content: null == content
          ? _value.content
          : content // ignore: cast_nullable_to_non_nullable
              as String,
      image: null == image
          ? _value.image
          : image // ignore: cast_nullable_to_non_nullable
              as String,
      createdAt: null == createdAt
          ? _value.createdAt
          : createdAt // ignore: cast_nullable_to_non_nullable
              as String,
      likesCount: null == likesCount
          ? _value.likesCount
          : likesCount // ignore: cast_nullable_to_non_nullable
              as int,
    ));
  }
}

/// @nodoc
@JsonSerializable()
class _$StoryDtoImpl implements _StoryDto {
  const _$StoryDtoImpl(
      {required this.id,
      required this.title,
      required this.content,
      required this.image,
      @JsonKey(name: 'created_at') required this.createdAt,
      @JsonKey(name: 'likes_count') required this.likesCount});

  factory _$StoryDtoImpl.fromJson(Map<String, dynamic> json) =>
      _$$StoryDtoImplFromJson(json);

  @override
  final int id;
  @override
  final String title;
  @override
  final String content;
  @override
  final String image;
  @override
  @JsonKey(name: 'created_at')
  final String createdAt;
  @override
  @JsonKey(name: 'likes_count')
  final int likesCount;

  @override
  String toString() {
    return 'StoryDto(id: $id, title: $title, content: $content, image: $image, createdAt: $createdAt, likesCount: $likesCount)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$StoryDtoImpl &&
            (identical(other.id, id) || other.id == id) &&
            (identical(other.title, title) || other.title == title) &&
            (identical(other.content, content) || other.content == content) &&
            (identical(other.image, image) || other.image == image) &&
            (identical(other.createdAt, createdAt) ||
                other.createdAt == createdAt) &&
            (identical(other.likesCount, likesCount) ||
                other.likesCount == likesCount));
  }

  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  int get hashCode => Object.hash(
      runtimeType, id, title, content, image, createdAt, likesCount);

  /// Create a copy of StoryDto
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  @pragma('vm:prefer-inline')
  _$$StoryDtoImplCopyWith<_$StoryDtoImpl> get copyWith =>
      __$$StoryDtoImplCopyWithImpl<_$StoryDtoImpl>(this, _$identity);

  @override
  Map<String, dynamic> toJson() {
    return _$$StoryDtoImplToJson(
      this,
    );
  }
}

abstract class _StoryDto implements StoryDto {
  const factory _StoryDto(
          {required final int id,
          required final String title,
          required final String content,
          required final String image,
          @JsonKey(name: 'created_at') required final String createdAt,
          @JsonKey(name: 'likes_count') required final int likesCount}) =
      _$StoryDtoImpl;

  factory _StoryDto.fromJson(Map<String, dynamic> json) =
      _$StoryDtoImpl.fromJson;

  @override
  int get id;
  @override
  String get title;
  @override
  String get content;
  @override
  String get image;
  @override
  @JsonKey(name: 'created_at')
  String get createdAt;
  @override
  @JsonKey(name: 'likes_count')
  int get likesCount;

  /// Create a copy of StoryDto
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$StoryDtoImplCopyWith<_$StoryDtoImpl> get copyWith =>
      throw _privateConstructorUsedError;
}

ClinicDto _$ClinicDtoFromJson(Map<String, dynamic> json) {
  return _ClinicDto.fromJson(json);
}

/// @nodoc
mixin _$ClinicDto {
  int get id => throw _privateConstructorUsedError;
  String get name => throw _privateConstructorUsedError;
  String get location => throw _privateConstructorUsedError;
  double get rating => throw _privateConstructorUsedError;
  @JsonKey(name: 'reviews_count')
  int get reviewsCount => throw _privateConstructorUsedError;
  String get image => throw _privateConstructorUsedError;
  List<String> get specialties => throw _privateConstructorUsedError;

  /// Serializes this ClinicDto to a JSON map.
  Map<String, dynamic> toJson() => throw _privateConstructorUsedError;

  /// Create a copy of ClinicDto
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  $ClinicDtoCopyWith<ClinicDto> get copyWith =>
      throw _privateConstructorUsedError;
}

/// @nodoc
abstract class $ClinicDtoCopyWith<$Res> {
  factory $ClinicDtoCopyWith(ClinicDto value, $Res Function(ClinicDto) then) =
      _$ClinicDtoCopyWithImpl<$Res, ClinicDto>;
  @useResult
  $Res call(
      {int id,
      String name,
      String location,
      double rating,
      @JsonKey(name: 'reviews_count') int reviewsCount,
      String image,
      List<String> specialties});
}

/// @nodoc
class _$ClinicDtoCopyWithImpl<$Res, $Val extends ClinicDto>
    implements $ClinicDtoCopyWith<$Res> {
  _$ClinicDtoCopyWithImpl(this._value, this._then);

  // ignore: unused_field
  final $Val _value;
  // ignore: unused_field
  final $Res Function($Val) _then;

  /// Create a copy of ClinicDto
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? name = null,
    Object? location = null,
    Object? rating = null,
    Object? reviewsCount = null,
    Object? image = null,
    Object? specialties = null,
  }) {
    return _then(_value.copyWith(
      id: null == id
          ? _value.id
          : id // ignore: cast_nullable_to_non_nullable
              as int,
      name: null == name
          ? _value.name
          : name // ignore: cast_nullable_to_non_nullable
              as String,
      location: null == location
          ? _value.location
          : location // ignore: cast_nullable_to_non_nullable
              as String,
      rating: null == rating
          ? _value.rating
          : rating // ignore: cast_nullable_to_non_nullable
              as double,
      reviewsCount: null == reviewsCount
          ? _value.reviewsCount
          : reviewsCount // ignore: cast_nullable_to_non_nullable
              as int,
      image: null == image
          ? _value.image
          : image // ignore: cast_nullable_to_non_nullable
              as String,
      specialties: null == specialties
          ? _value.specialties
          : specialties // ignore: cast_nullable_to_non_nullable
              as List<String>,
    ) as $Val);
  }
}

/// @nodoc
abstract class _$$ClinicDtoImplCopyWith<$Res>
    implements $ClinicDtoCopyWith<$Res> {
  factory _$$ClinicDtoImplCopyWith(
          _$ClinicDtoImpl value, $Res Function(_$ClinicDtoImpl) then) =
      __$$ClinicDtoImplCopyWithImpl<$Res>;
  @override
  @useResult
  $Res call(
      {int id,
      String name,
      String location,
      double rating,
      @JsonKey(name: 'reviews_count') int reviewsCount,
      String image,
      List<String> specialties});
}

/// @nodoc
class __$$ClinicDtoImplCopyWithImpl<$Res>
    extends _$ClinicDtoCopyWithImpl<$Res, _$ClinicDtoImpl>
    implements _$$ClinicDtoImplCopyWith<$Res> {
  __$$ClinicDtoImplCopyWithImpl(
      _$ClinicDtoImpl _value, $Res Function(_$ClinicDtoImpl) _then)
      : super(_value, _then);

  /// Create a copy of ClinicDto
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? name = null,
    Object? location = null,
    Object? rating = null,
    Object? reviewsCount = null,
    Object? image = null,
    Object? specialties = null,
  }) {
    return _then(_$ClinicDtoImpl(
      id: null == id
          ? _value.id
          : id // ignore: cast_nullable_to_non_nullable
              as int,
      name: null == name
          ? _value.name
          : name // ignore: cast_nullable_to_non_nullable
              as String,
      location: null == location
          ? _value.location
          : location // ignore: cast_nullable_to_non_nullable
              as String,
      rating: null == rating
          ? _value.rating
          : rating // ignore: cast_nullable_to_non_nullable
              as double,
      reviewsCount: null == reviewsCount
          ? _value.reviewsCount
          : reviewsCount // ignore: cast_nullable_to_non_nullable
              as int,
      image: null == image
          ? _value.image
          : image // ignore: cast_nullable_to_non_nullable
              as String,
      specialties: null == specialties
          ? _value._specialties
          : specialties // ignore: cast_nullable_to_non_nullable
              as List<String>,
    ));
  }
}

/// @nodoc
@JsonSerializable()
class _$ClinicDtoImpl implements _ClinicDto {
  const _$ClinicDtoImpl(
      {required this.id,
      required this.name,
      required this.location,
      required this.rating,
      @JsonKey(name: 'reviews_count') required this.reviewsCount,
      required this.image,
      required final List<String> specialties})
      : _specialties = specialties;

  factory _$ClinicDtoImpl.fromJson(Map<String, dynamic> json) =>
      _$$ClinicDtoImplFromJson(json);

  @override
  final int id;
  @override
  final String name;
  @override
  final String location;
  @override
  final double rating;
  @override
  @JsonKey(name: 'reviews_count')
  final int reviewsCount;
  @override
  final String image;
  final List<String> _specialties;
  @override
  List<String> get specialties {
    if (_specialties is EqualUnmodifiableListView) return _specialties;
    // ignore: implicit_dynamic_type
    return EqualUnmodifiableListView(_specialties);
  }

  @override
  String toString() {
    return 'ClinicDto(id: $id, name: $name, location: $location, rating: $rating, reviewsCount: $reviewsCount, image: $image, specialties: $specialties)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$ClinicDtoImpl &&
            (identical(other.id, id) || other.id == id) &&
            (identical(other.name, name) || other.name == name) &&
            (identical(other.location, location) ||
                other.location == location) &&
            (identical(other.rating, rating) || other.rating == rating) &&
            (identical(other.reviewsCount, reviewsCount) ||
                other.reviewsCount == reviewsCount) &&
            (identical(other.image, image) || other.image == image) &&
            const DeepCollectionEquality()
                .equals(other._specialties, _specialties));
  }

  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  int get hashCode => Object.hash(runtimeType, id, name, location, rating,
      reviewsCount, image, const DeepCollectionEquality().hash(_specialties));

  /// Create a copy of ClinicDto
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  @pragma('vm:prefer-inline')
  _$$ClinicDtoImplCopyWith<_$ClinicDtoImpl> get copyWith =>
      __$$ClinicDtoImplCopyWithImpl<_$ClinicDtoImpl>(this, _$identity);

  @override
  Map<String, dynamic> toJson() {
    return _$$ClinicDtoImplToJson(
      this,
    );
  }
}

abstract class _ClinicDto implements ClinicDto {
  const factory _ClinicDto(
      {required final int id,
      required final String name,
      required final String location,
      required final double rating,
      @JsonKey(name: 'reviews_count') required final int reviewsCount,
      required final String image,
      required final List<String> specialties}) = _$ClinicDtoImpl;

  factory _ClinicDto.fromJson(Map<String, dynamic> json) =
      _$ClinicDtoImpl.fromJson;

  @override
  int get id;
  @override
  String get name;
  @override
  String get location;
  @override
  double get rating;
  @override
  @JsonKey(name: 'reviews_count')
  int get reviewsCount;
  @override
  String get image;
  @override
  List<String> get specialties;

  /// Create a copy of ClinicDto
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$ClinicDtoImplCopyWith<_$ClinicDtoImpl> get copyWith =>
      throw _privateConstructorUsedError;
}

DoctorDto _$DoctorDtoFromJson(Map<String, dynamic> json) {
  return _DoctorDto.fromJson(json);
}

/// @nodoc
mixin _$DoctorDto {
  int get id => throw _privateConstructorUsedError;
  String get name => throw _privateConstructorUsedError;
  String get specialty => throw _privateConstructorUsedError;
  double get rating => throw _privateConstructorUsedError;
  @JsonKey(name: 'reviews_count')
  int get reviewsCount => throw _privateConstructorUsedError;
  String get image => throw _privateConstructorUsedError;
  @JsonKey(name: 'years_experience')
  int get yearsExperience => throw _privateConstructorUsedError;
  @JsonKey(name: 'clinic_name')
  String get clinicName => throw _privateConstructorUsedError;

  /// Serializes this DoctorDto to a JSON map.
  Map<String, dynamic> toJson() => throw _privateConstructorUsedError;

  /// Create a copy of DoctorDto
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  $DoctorDtoCopyWith<DoctorDto> get copyWith =>
      throw _privateConstructorUsedError;
}

/// @nodoc
abstract class $DoctorDtoCopyWith<$Res> {
  factory $DoctorDtoCopyWith(DoctorDto value, $Res Function(DoctorDto) then) =
      _$DoctorDtoCopyWithImpl<$Res, DoctorDto>;
  @useResult
  $Res call(
      {int id,
      String name,
      String specialty,
      double rating,
      @JsonKey(name: 'reviews_count') int reviewsCount,
      String image,
      @JsonKey(name: 'years_experience') int yearsExperience,
      @JsonKey(name: 'clinic_name') String clinicName});
}

/// @nodoc
class _$DoctorDtoCopyWithImpl<$Res, $Val extends DoctorDto>
    implements $DoctorDtoCopyWith<$Res> {
  _$DoctorDtoCopyWithImpl(this._value, this._then);

  // ignore: unused_field
  final $Val _value;
  // ignore: unused_field
  final $Res Function($Val) _then;

  /// Create a copy of DoctorDto
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? name = null,
    Object? specialty = null,
    Object? rating = null,
    Object? reviewsCount = null,
    Object? image = null,
    Object? yearsExperience = null,
    Object? clinicName = null,
  }) {
    return _then(_value.copyWith(
      id: null == id
          ? _value.id
          : id // ignore: cast_nullable_to_non_nullable
              as int,
      name: null == name
          ? _value.name
          : name // ignore: cast_nullable_to_non_nullable
              as String,
      specialty: null == specialty
          ? _value.specialty
          : specialty // ignore: cast_nullable_to_non_nullable
              as String,
      rating: null == rating
          ? _value.rating
          : rating // ignore: cast_nullable_to_non_nullable
              as double,
      reviewsCount: null == reviewsCount
          ? _value.reviewsCount
          : reviewsCount // ignore: cast_nullable_to_non_nullable
              as int,
      image: null == image
          ? _value.image
          : image // ignore: cast_nullable_to_non_nullable
              as String,
      yearsExperience: null == yearsExperience
          ? _value.yearsExperience
          : yearsExperience // ignore: cast_nullable_to_non_nullable
              as int,
      clinicName: null == clinicName
          ? _value.clinicName
          : clinicName // ignore: cast_nullable_to_non_nullable
              as String,
    ) as $Val);
  }
}

/// @nodoc
abstract class _$$DoctorDtoImplCopyWith<$Res>
    implements $DoctorDtoCopyWith<$Res> {
  factory _$$DoctorDtoImplCopyWith(
          _$DoctorDtoImpl value, $Res Function(_$DoctorDtoImpl) then) =
      __$$DoctorDtoImplCopyWithImpl<$Res>;
  @override
  @useResult
  $Res call(
      {int id,
      String name,
      String specialty,
      double rating,
      @JsonKey(name: 'reviews_count') int reviewsCount,
      String image,
      @JsonKey(name: 'years_experience') int yearsExperience,
      @JsonKey(name: 'clinic_name') String clinicName});
}

/// @nodoc
class __$$DoctorDtoImplCopyWithImpl<$Res>
    extends _$DoctorDtoCopyWithImpl<$Res, _$DoctorDtoImpl>
    implements _$$DoctorDtoImplCopyWith<$Res> {
  __$$DoctorDtoImplCopyWithImpl(
      _$DoctorDtoImpl _value, $Res Function(_$DoctorDtoImpl) _then)
      : super(_value, _then);

  /// Create a copy of DoctorDto
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? name = null,
    Object? specialty = null,
    Object? rating = null,
    Object? reviewsCount = null,
    Object? image = null,
    Object? yearsExperience = null,
    Object? clinicName = null,
  }) {
    return _then(_$DoctorDtoImpl(
      id: null == id
          ? _value.id
          : id // ignore: cast_nullable_to_non_nullable
              as int,
      name: null == name
          ? _value.name
          : name // ignore: cast_nullable_to_non_nullable
              as String,
      specialty: null == specialty
          ? _value.specialty
          : specialty // ignore: cast_nullable_to_non_nullable
              as String,
      rating: null == rating
          ? _value.rating
          : rating // ignore: cast_nullable_to_non_nullable
              as double,
      reviewsCount: null == reviewsCount
          ? _value.reviewsCount
          : reviewsCount // ignore: cast_nullable_to_non_nullable
              as int,
      image: null == image
          ? _value.image
          : image // ignore: cast_nullable_to_non_nullable
              as String,
      yearsExperience: null == yearsExperience
          ? _value.yearsExperience
          : yearsExperience // ignore: cast_nullable_to_non_nullable
              as int,
      clinicName: null == clinicName
          ? _value.clinicName
          : clinicName // ignore: cast_nullable_to_non_nullable
              as String,
    ));
  }
}

/// @nodoc
@JsonSerializable()
class _$DoctorDtoImpl implements _DoctorDto {
  const _$DoctorDtoImpl(
      {required this.id,
      required this.name,
      required this.specialty,
      required this.rating,
      @JsonKey(name: 'reviews_count') required this.reviewsCount,
      required this.image,
      @JsonKey(name: 'years_experience') required this.yearsExperience,
      @JsonKey(name: 'clinic_name') required this.clinicName});

  factory _$DoctorDtoImpl.fromJson(Map<String, dynamic> json) =>
      _$$DoctorDtoImplFromJson(json);

  @override
  final int id;
  @override
  final String name;
  @override
  final String specialty;
  @override
  final double rating;
  @override
  @JsonKey(name: 'reviews_count')
  final int reviewsCount;
  @override
  final String image;
  @override
  @JsonKey(name: 'years_experience')
  final int yearsExperience;
  @override
  @JsonKey(name: 'clinic_name')
  final String clinicName;

  @override
  String toString() {
    return 'DoctorDto(id: $id, name: $name, specialty: $specialty, rating: $rating, reviewsCount: $reviewsCount, image: $image, yearsExperience: $yearsExperience, clinicName: $clinicName)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$DoctorDtoImpl &&
            (identical(other.id, id) || other.id == id) &&
            (identical(other.name, name) || other.name == name) &&
            (identical(other.specialty, specialty) ||
                other.specialty == specialty) &&
            (identical(other.rating, rating) || other.rating == rating) &&
            (identical(other.reviewsCount, reviewsCount) ||
                other.reviewsCount == reviewsCount) &&
            (identical(other.image, image) || other.image == image) &&
            (identical(other.yearsExperience, yearsExperience) ||
                other.yearsExperience == yearsExperience) &&
            (identical(other.clinicName, clinicName) ||
                other.clinicName == clinicName));
  }

  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  int get hashCode => Object.hash(runtimeType, id, name, specialty, rating,
      reviewsCount, image, yearsExperience, clinicName);

  /// Create a copy of DoctorDto
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  @pragma('vm:prefer-inline')
  _$$DoctorDtoImplCopyWith<_$DoctorDtoImpl> get copyWith =>
      __$$DoctorDtoImplCopyWithImpl<_$DoctorDtoImpl>(this, _$identity);

  @override
  Map<String, dynamic> toJson() {
    return _$$DoctorDtoImplToJson(
      this,
    );
  }
}

abstract class _DoctorDto implements DoctorDto {
  const factory _DoctorDto(
          {required final int id,
          required final String name,
          required final String specialty,
          required final double rating,
          @JsonKey(name: 'reviews_count') required final int reviewsCount,
          required final String image,
          @JsonKey(name: 'years_experience') required final int yearsExperience,
          @JsonKey(name: 'clinic_name') required final String clinicName}) =
      _$DoctorDtoImpl;

  factory _DoctorDto.fromJson(Map<String, dynamic> json) =
      _$DoctorDtoImpl.fromJson;

  @override
  int get id;
  @override
  String get name;
  @override
  String get specialty;
  @override
  double get rating;
  @override
  @JsonKey(name: 'reviews_count')
  int get reviewsCount;
  @override
  String get image;
  @override
  @JsonKey(name: 'years_experience')
  int get yearsExperience;
  @override
  @JsonKey(name: 'clinic_name')
  String get clinicName;

  /// Create a copy of DoctorDto
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$DoctorDtoImplCopyWith<_$DoctorDtoImpl> get copyWith =>
      throw _privateConstructorUsedError;
}

BeforeAfterDto _$BeforeAfterDtoFromJson(Map<String, dynamic> json) {
  return _BeforeAfterDto.fromJson(json);
}

/// @nodoc
mixin _$BeforeAfterDto {
  int get id => throw _privateConstructorUsedError;
  String get title => throw _privateConstructorUsedError;
  @JsonKey(name: 'before_image')
  String get beforeImage => throw _privateConstructorUsedError;
  @JsonKey(name: 'after_image')
  String get afterImage => throw _privateConstructorUsedError;
  @JsonKey(name: 'doctor_name')
  String get doctorName => throw _privateConstructorUsedError;
  @JsonKey(name: 'treatment_duration')
  String get treatmentDuration => throw _privateConstructorUsedError;
  String get description => throw _privateConstructorUsedError;

  /// Serializes this BeforeAfterDto to a JSON map.
  Map<String, dynamic> toJson() => throw _privateConstructorUsedError;

  /// Create a copy of BeforeAfterDto
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  $BeforeAfterDtoCopyWith<BeforeAfterDto> get copyWith =>
      throw _privateConstructorUsedError;
}

/// @nodoc
abstract class $BeforeAfterDtoCopyWith<$Res> {
  factory $BeforeAfterDtoCopyWith(
          BeforeAfterDto value, $Res Function(BeforeAfterDto) then) =
      _$BeforeAfterDtoCopyWithImpl<$Res, BeforeAfterDto>;
  @useResult
  $Res call(
      {int id,
      String title,
      @JsonKey(name: 'before_image') String beforeImage,
      @JsonKey(name: 'after_image') String afterImage,
      @JsonKey(name: 'doctor_name') String doctorName,
      @JsonKey(name: 'treatment_duration') String treatmentDuration,
      String description});
}

/// @nodoc
class _$BeforeAfterDtoCopyWithImpl<$Res, $Val extends BeforeAfterDto>
    implements $BeforeAfterDtoCopyWith<$Res> {
  _$BeforeAfterDtoCopyWithImpl(this._value, this._then);

  // ignore: unused_field
  final $Val _value;
  // ignore: unused_field
  final $Res Function($Val) _then;

  /// Create a copy of BeforeAfterDto
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? title = null,
    Object? beforeImage = null,
    Object? afterImage = null,
    Object? doctorName = null,
    Object? treatmentDuration = null,
    Object? description = null,
  }) {
    return _then(_value.copyWith(
      id: null == id
          ? _value.id
          : id // ignore: cast_nullable_to_non_nullable
              as int,
      title: null == title
          ? _value.title
          : title // ignore: cast_nullable_to_non_nullable
              as String,
      beforeImage: null == beforeImage
          ? _value.beforeImage
          : beforeImage // ignore: cast_nullable_to_non_nullable
              as String,
      afterImage: null == afterImage
          ? _value.afterImage
          : afterImage // ignore: cast_nullable_to_non_nullable
              as String,
      doctorName: null == doctorName
          ? _value.doctorName
          : doctorName // ignore: cast_nullable_to_non_nullable
              as String,
      treatmentDuration: null == treatmentDuration
          ? _value.treatmentDuration
          : treatmentDuration // ignore: cast_nullable_to_non_nullable
              as String,
      description: null == description
          ? _value.description
          : description // ignore: cast_nullable_to_non_nullable
              as String,
    ) as $Val);
  }
}

/// @nodoc
abstract class _$$BeforeAfterDtoImplCopyWith<$Res>
    implements $BeforeAfterDtoCopyWith<$Res> {
  factory _$$BeforeAfterDtoImplCopyWith(_$BeforeAfterDtoImpl value,
          $Res Function(_$BeforeAfterDtoImpl) then) =
      __$$BeforeAfterDtoImplCopyWithImpl<$Res>;
  @override
  @useResult
  $Res call(
      {int id,
      String title,
      @JsonKey(name: 'before_image') String beforeImage,
      @JsonKey(name: 'after_image') String afterImage,
      @JsonKey(name: 'doctor_name') String doctorName,
      @JsonKey(name: 'treatment_duration') String treatmentDuration,
      String description});
}

/// @nodoc
class __$$BeforeAfterDtoImplCopyWithImpl<$Res>
    extends _$BeforeAfterDtoCopyWithImpl<$Res, _$BeforeAfterDtoImpl>
    implements _$$BeforeAfterDtoImplCopyWith<$Res> {
  __$$BeforeAfterDtoImplCopyWithImpl(
      _$BeforeAfterDtoImpl _value, $Res Function(_$BeforeAfterDtoImpl) _then)
      : super(_value, _then);

  /// Create a copy of BeforeAfterDto
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? title = null,
    Object? beforeImage = null,
    Object? afterImage = null,
    Object? doctorName = null,
    Object? treatmentDuration = null,
    Object? description = null,
  }) {
    return _then(_$BeforeAfterDtoImpl(
      id: null == id
          ? _value.id
          : id // ignore: cast_nullable_to_non_nullable
              as int,
      title: null == title
          ? _value.title
          : title // ignore: cast_nullable_to_non_nullable
              as String,
      beforeImage: null == beforeImage
          ? _value.beforeImage
          : beforeImage // ignore: cast_nullable_to_non_nullable
              as String,
      afterImage: null == afterImage
          ? _value.afterImage
          : afterImage // ignore: cast_nullable_to_non_nullable
              as String,
      doctorName: null == doctorName
          ? _value.doctorName
          : doctorName // ignore: cast_nullable_to_non_nullable
              as String,
      treatmentDuration: null == treatmentDuration
          ? _value.treatmentDuration
          : treatmentDuration // ignore: cast_nullable_to_non_nullable
              as String,
      description: null == description
          ? _value.description
          : description // ignore: cast_nullable_to_non_nullable
              as String,
    ));
  }
}

/// @nodoc
@JsonSerializable()
class _$BeforeAfterDtoImpl implements _BeforeAfterDto {
  const _$BeforeAfterDtoImpl(
      {required this.id,
      required this.title,
      @JsonKey(name: 'before_image') required this.beforeImage,
      @JsonKey(name: 'after_image') required this.afterImage,
      @JsonKey(name: 'doctor_name') required this.doctorName,
      @JsonKey(name: 'treatment_duration') required this.treatmentDuration,
      required this.description});

  factory _$BeforeAfterDtoImpl.fromJson(Map<String, dynamic> json) =>
      _$$BeforeAfterDtoImplFromJson(json);

  @override
  final int id;
  @override
  final String title;
  @override
  @JsonKey(name: 'before_image')
  final String beforeImage;
  @override
  @JsonKey(name: 'after_image')
  final String afterImage;
  @override
  @JsonKey(name: 'doctor_name')
  final String doctorName;
  @override
  @JsonKey(name: 'treatment_duration')
  final String treatmentDuration;
  @override
  final String description;

  @override
  String toString() {
    return 'BeforeAfterDto(id: $id, title: $title, beforeImage: $beforeImage, afterImage: $afterImage, doctorName: $doctorName, treatmentDuration: $treatmentDuration, description: $description)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$BeforeAfterDtoImpl &&
            (identical(other.id, id) || other.id == id) &&
            (identical(other.title, title) || other.title == title) &&
            (identical(other.beforeImage, beforeImage) ||
                other.beforeImage == beforeImage) &&
            (identical(other.afterImage, afterImage) ||
                other.afterImage == afterImage) &&
            (identical(other.doctorName, doctorName) ||
                other.doctorName == doctorName) &&
            (identical(other.treatmentDuration, treatmentDuration) ||
                other.treatmentDuration == treatmentDuration) &&
            (identical(other.description, description) ||
                other.description == description));
  }

  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  int get hashCode => Object.hash(runtimeType, id, title, beforeImage,
      afterImage, doctorName, treatmentDuration, description);

  /// Create a copy of BeforeAfterDto
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  @pragma('vm:prefer-inline')
  _$$BeforeAfterDtoImplCopyWith<_$BeforeAfterDtoImpl> get copyWith =>
      __$$BeforeAfterDtoImplCopyWithImpl<_$BeforeAfterDtoImpl>(
          this, _$identity);

  @override
  Map<String, dynamic> toJson() {
    return _$$BeforeAfterDtoImplToJson(
      this,
    );
  }
}

abstract class _BeforeAfterDto implements BeforeAfterDto {
  const factory _BeforeAfterDto(
      {required final int id,
      required final String title,
      @JsonKey(name: 'before_image') required final String beforeImage,
      @JsonKey(name: 'after_image') required final String afterImage,
      @JsonKey(name: 'doctor_name') required final String doctorName,
      @JsonKey(name: 'treatment_duration')
      required final String treatmentDuration,
      required final String description}) = _$BeforeAfterDtoImpl;

  factory _BeforeAfterDto.fromJson(Map<String, dynamic> json) =
      _$BeforeAfterDtoImpl.fromJson;

  @override
  int get id;
  @override
  String get title;
  @override
  @JsonKey(name: 'before_image')
  String get beforeImage;
  @override
  @JsonKey(name: 'after_image')
  String get afterImage;
  @override
  @JsonKey(name: 'doctor_name')
  String get doctorName;
  @override
  @JsonKey(name: 'treatment_duration')
  String get treatmentDuration;
  @override
  String get description;

  /// Create a copy of BeforeAfterDto
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$BeforeAfterDtoImplCopyWith<_$BeforeAfterDtoImpl> get copyWith =>
      throw _privateConstructorUsedError;
}
