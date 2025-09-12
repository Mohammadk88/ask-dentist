// coverage:ignore-file
// GENERATED CODE - DO NOT MODIFY BY HAND
// ignore_for_file: type=lint
// ignore_for_file: unused_element, deprecated_member_use, deprecated_member_use_from_same_package, use_function_type_syntax_for_parameters, unnecessary_const, avoid_init_to_null, invalid_override_different_default_values_named, prefer_expression_function_bodies, annotate_overrides, invalid_annotation_target, unnecessary_question_mark

part of 'auth_event.dart';

// **************************************************************************
// FreezedGenerator
// **************************************************************************

T _$identity<T>(T value) => value;

final _privateConstructorUsedError = UnsupportedError(
    'It seems like you constructed your class using `MyClass._()`. This constructor is only meant to be used by freezed and you are not supposed to need it nor use it.\nPlease check the documentation here for more information: https://github.com/rrousselGit/freezed#adding-getters-and-methods-to-our-models');

/// @nodoc
mixin _$AuthEvent {
  @optionalTypeArgs
  TResult when<TResult extends Object?>({
    required TResult Function(String? message) showLoginSheet,
    required TResult Function() hideLoginSheet,
    required TResult Function() refreshHome,
  }) =>
      throw _privateConstructorUsedError;
  @optionalTypeArgs
  TResult? whenOrNull<TResult extends Object?>({
    TResult? Function(String? message)? showLoginSheet,
    TResult? Function()? hideLoginSheet,
    TResult? Function()? refreshHome,
  }) =>
      throw _privateConstructorUsedError;
  @optionalTypeArgs
  TResult maybeWhen<TResult extends Object?>({
    TResult Function(String? message)? showLoginSheet,
    TResult Function()? hideLoginSheet,
    TResult Function()? refreshHome,
    required TResult orElse(),
  }) =>
      throw _privateConstructorUsedError;
  @optionalTypeArgs
  TResult map<TResult extends Object?>({
    required TResult Function(_ShowLoginSheet value) showLoginSheet,
    required TResult Function(_HideLoginSheet value) hideLoginSheet,
    required TResult Function(_RefreshHome value) refreshHome,
  }) =>
      throw _privateConstructorUsedError;
  @optionalTypeArgs
  TResult? mapOrNull<TResult extends Object?>({
    TResult? Function(_ShowLoginSheet value)? showLoginSheet,
    TResult? Function(_HideLoginSheet value)? hideLoginSheet,
    TResult? Function(_RefreshHome value)? refreshHome,
  }) =>
      throw _privateConstructorUsedError;
  @optionalTypeArgs
  TResult maybeMap<TResult extends Object?>({
    TResult Function(_ShowLoginSheet value)? showLoginSheet,
    TResult Function(_HideLoginSheet value)? hideLoginSheet,
    TResult Function(_RefreshHome value)? refreshHome,
    required TResult orElse(),
  }) =>
      throw _privateConstructorUsedError;
}

/// @nodoc
abstract class $AuthEventCopyWith<$Res> {
  factory $AuthEventCopyWith(AuthEvent value, $Res Function(AuthEvent) then) =
      _$AuthEventCopyWithImpl<$Res, AuthEvent>;
}

/// @nodoc
class _$AuthEventCopyWithImpl<$Res, $Val extends AuthEvent>
    implements $AuthEventCopyWith<$Res> {
  _$AuthEventCopyWithImpl(this._value, this._then);

  // ignore: unused_field
  final $Val _value;
  // ignore: unused_field
  final $Res Function($Val) _then;

  /// Create a copy of AuthEvent
  /// with the given fields replaced by the non-null parameter values.
}

/// @nodoc
abstract class _$$ShowLoginSheetImplCopyWith<$Res> {
  factory _$$ShowLoginSheetImplCopyWith(_$ShowLoginSheetImpl value,
          $Res Function(_$ShowLoginSheetImpl) then) =
      __$$ShowLoginSheetImplCopyWithImpl<$Res>;
  @useResult
  $Res call({String? message});
}

/// @nodoc
class __$$ShowLoginSheetImplCopyWithImpl<$Res>
    extends _$AuthEventCopyWithImpl<$Res, _$ShowLoginSheetImpl>
    implements _$$ShowLoginSheetImplCopyWith<$Res> {
  __$$ShowLoginSheetImplCopyWithImpl(
      _$ShowLoginSheetImpl _value, $Res Function(_$ShowLoginSheetImpl) _then)
      : super(_value, _then);

  /// Create a copy of AuthEvent
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? message = freezed,
  }) {
    return _then(_$ShowLoginSheetImpl(
      message: freezed == message
          ? _value.message
          : message // ignore: cast_nullable_to_non_nullable
              as String?,
    ));
  }
}

/// @nodoc

class _$ShowLoginSheetImpl implements _ShowLoginSheet {
  const _$ShowLoginSheetImpl({this.message});

  @override
  final String? message;

  @override
  String toString() {
    return 'AuthEvent.showLoginSheet(message: $message)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$ShowLoginSheetImpl &&
            (identical(other.message, message) || other.message == message));
  }

  @override
  int get hashCode => Object.hash(runtimeType, message);

  /// Create a copy of AuthEvent
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  @pragma('vm:prefer-inline')
  _$$ShowLoginSheetImplCopyWith<_$ShowLoginSheetImpl> get copyWith =>
      __$$ShowLoginSheetImplCopyWithImpl<_$ShowLoginSheetImpl>(
          this, _$identity);

  @override
  @optionalTypeArgs
  TResult when<TResult extends Object?>({
    required TResult Function(String? message) showLoginSheet,
    required TResult Function() hideLoginSheet,
    required TResult Function() refreshHome,
  }) {
    return showLoginSheet(message);
  }

  @override
  @optionalTypeArgs
  TResult? whenOrNull<TResult extends Object?>({
    TResult? Function(String? message)? showLoginSheet,
    TResult? Function()? hideLoginSheet,
    TResult? Function()? refreshHome,
  }) {
    return showLoginSheet?.call(message);
  }

  @override
  @optionalTypeArgs
  TResult maybeWhen<TResult extends Object?>({
    TResult Function(String? message)? showLoginSheet,
    TResult Function()? hideLoginSheet,
    TResult Function()? refreshHome,
    required TResult orElse(),
  }) {
    if (showLoginSheet != null) {
      return showLoginSheet(message);
    }
    return orElse();
  }

  @override
  @optionalTypeArgs
  TResult map<TResult extends Object?>({
    required TResult Function(_ShowLoginSheet value) showLoginSheet,
    required TResult Function(_HideLoginSheet value) hideLoginSheet,
    required TResult Function(_RefreshHome value) refreshHome,
  }) {
    return showLoginSheet(this);
  }

  @override
  @optionalTypeArgs
  TResult? mapOrNull<TResult extends Object?>({
    TResult? Function(_ShowLoginSheet value)? showLoginSheet,
    TResult? Function(_HideLoginSheet value)? hideLoginSheet,
    TResult? Function(_RefreshHome value)? refreshHome,
  }) {
    return showLoginSheet?.call(this);
  }

  @override
  @optionalTypeArgs
  TResult maybeMap<TResult extends Object?>({
    TResult Function(_ShowLoginSheet value)? showLoginSheet,
    TResult Function(_HideLoginSheet value)? hideLoginSheet,
    TResult Function(_RefreshHome value)? refreshHome,
    required TResult orElse(),
  }) {
    if (showLoginSheet != null) {
      return showLoginSheet(this);
    }
    return orElse();
  }
}

abstract class _ShowLoginSheet implements AuthEvent {
  const factory _ShowLoginSheet({final String? message}) = _$ShowLoginSheetImpl;

  String? get message;

  /// Create a copy of AuthEvent
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$ShowLoginSheetImplCopyWith<_$ShowLoginSheetImpl> get copyWith =>
      throw _privateConstructorUsedError;
}

/// @nodoc
abstract class _$$HideLoginSheetImplCopyWith<$Res> {
  factory _$$HideLoginSheetImplCopyWith(_$HideLoginSheetImpl value,
          $Res Function(_$HideLoginSheetImpl) then) =
      __$$HideLoginSheetImplCopyWithImpl<$Res>;
}

/// @nodoc
class __$$HideLoginSheetImplCopyWithImpl<$Res>
    extends _$AuthEventCopyWithImpl<$Res, _$HideLoginSheetImpl>
    implements _$$HideLoginSheetImplCopyWith<$Res> {
  __$$HideLoginSheetImplCopyWithImpl(
      _$HideLoginSheetImpl _value, $Res Function(_$HideLoginSheetImpl) _then)
      : super(_value, _then);

  /// Create a copy of AuthEvent
  /// with the given fields replaced by the non-null parameter values.
}

/// @nodoc

class _$HideLoginSheetImpl implements _HideLoginSheet {
  const _$HideLoginSheetImpl();

  @override
  String toString() {
    return 'AuthEvent.hideLoginSheet()';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType && other is _$HideLoginSheetImpl);
  }

  @override
  int get hashCode => runtimeType.hashCode;

  @override
  @optionalTypeArgs
  TResult when<TResult extends Object?>({
    required TResult Function(String? message) showLoginSheet,
    required TResult Function() hideLoginSheet,
    required TResult Function() refreshHome,
  }) {
    return hideLoginSheet();
  }

  @override
  @optionalTypeArgs
  TResult? whenOrNull<TResult extends Object?>({
    TResult? Function(String? message)? showLoginSheet,
    TResult? Function()? hideLoginSheet,
    TResult? Function()? refreshHome,
  }) {
    return hideLoginSheet?.call();
  }

  @override
  @optionalTypeArgs
  TResult maybeWhen<TResult extends Object?>({
    TResult Function(String? message)? showLoginSheet,
    TResult Function()? hideLoginSheet,
    TResult Function()? refreshHome,
    required TResult orElse(),
  }) {
    if (hideLoginSheet != null) {
      return hideLoginSheet();
    }
    return orElse();
  }

  @override
  @optionalTypeArgs
  TResult map<TResult extends Object?>({
    required TResult Function(_ShowLoginSheet value) showLoginSheet,
    required TResult Function(_HideLoginSheet value) hideLoginSheet,
    required TResult Function(_RefreshHome value) refreshHome,
  }) {
    return hideLoginSheet(this);
  }

  @override
  @optionalTypeArgs
  TResult? mapOrNull<TResult extends Object?>({
    TResult? Function(_ShowLoginSheet value)? showLoginSheet,
    TResult? Function(_HideLoginSheet value)? hideLoginSheet,
    TResult? Function(_RefreshHome value)? refreshHome,
  }) {
    return hideLoginSheet?.call(this);
  }

  @override
  @optionalTypeArgs
  TResult maybeMap<TResult extends Object?>({
    TResult Function(_ShowLoginSheet value)? showLoginSheet,
    TResult Function(_HideLoginSheet value)? hideLoginSheet,
    TResult Function(_RefreshHome value)? refreshHome,
    required TResult orElse(),
  }) {
    if (hideLoginSheet != null) {
      return hideLoginSheet(this);
    }
    return orElse();
  }
}

abstract class _HideLoginSheet implements AuthEvent {
  const factory _HideLoginSheet() = _$HideLoginSheetImpl;
}

/// @nodoc
abstract class _$$RefreshHomeImplCopyWith<$Res> {
  factory _$$RefreshHomeImplCopyWith(
          _$RefreshHomeImpl value, $Res Function(_$RefreshHomeImpl) then) =
      __$$RefreshHomeImplCopyWithImpl<$Res>;
}

/// @nodoc
class __$$RefreshHomeImplCopyWithImpl<$Res>
    extends _$AuthEventCopyWithImpl<$Res, _$RefreshHomeImpl>
    implements _$$RefreshHomeImplCopyWith<$Res> {
  __$$RefreshHomeImplCopyWithImpl(
      _$RefreshHomeImpl _value, $Res Function(_$RefreshHomeImpl) _then)
      : super(_value, _then);

  /// Create a copy of AuthEvent
  /// with the given fields replaced by the non-null parameter values.
}

/// @nodoc

class _$RefreshHomeImpl implements _RefreshHome {
  const _$RefreshHomeImpl();

  @override
  String toString() {
    return 'AuthEvent.refreshHome()';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType && other is _$RefreshHomeImpl);
  }

  @override
  int get hashCode => runtimeType.hashCode;

  @override
  @optionalTypeArgs
  TResult when<TResult extends Object?>({
    required TResult Function(String? message) showLoginSheet,
    required TResult Function() hideLoginSheet,
    required TResult Function() refreshHome,
  }) {
    return refreshHome();
  }

  @override
  @optionalTypeArgs
  TResult? whenOrNull<TResult extends Object?>({
    TResult? Function(String? message)? showLoginSheet,
    TResult? Function()? hideLoginSheet,
    TResult? Function()? refreshHome,
  }) {
    return refreshHome?.call();
  }

  @override
  @optionalTypeArgs
  TResult maybeWhen<TResult extends Object?>({
    TResult Function(String? message)? showLoginSheet,
    TResult Function()? hideLoginSheet,
    TResult Function()? refreshHome,
    required TResult orElse(),
  }) {
    if (refreshHome != null) {
      return refreshHome();
    }
    return orElse();
  }

  @override
  @optionalTypeArgs
  TResult map<TResult extends Object?>({
    required TResult Function(_ShowLoginSheet value) showLoginSheet,
    required TResult Function(_HideLoginSheet value) hideLoginSheet,
    required TResult Function(_RefreshHome value) refreshHome,
  }) {
    return refreshHome(this);
  }

  @override
  @optionalTypeArgs
  TResult? mapOrNull<TResult extends Object?>({
    TResult? Function(_ShowLoginSheet value)? showLoginSheet,
    TResult? Function(_HideLoginSheet value)? hideLoginSheet,
    TResult? Function(_RefreshHome value)? refreshHome,
  }) {
    return refreshHome?.call(this);
  }

  @override
  @optionalTypeArgs
  TResult maybeMap<TResult extends Object?>({
    TResult Function(_ShowLoginSheet value)? showLoginSheet,
    TResult Function(_HideLoginSheet value)? hideLoginSheet,
    TResult Function(_RefreshHome value)? refreshHome,
    required TResult orElse(),
  }) {
    if (refreshHome != null) {
      return refreshHome(this);
    }
    return orElse();
  }
}

abstract class _RefreshHome implements AuthEvent {
  const factory _RefreshHome() = _$RefreshHomeImpl;
}
