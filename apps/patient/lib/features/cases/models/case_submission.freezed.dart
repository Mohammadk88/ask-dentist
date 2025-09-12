// coverage:ignore-file
// GENERATED CODE - DO NOT MODIFY BY HAND
// ignore_for_file: type=lint
// ignore_for_file: unused_element, deprecated_member_use, deprecated_member_use_from_same_package, use_function_type_syntax_for_parameters, unnecessary_const, avoid_init_to_null, invalid_override_different_default_values_named, prefer_expression_function_bodies, annotate_overrides, invalid_annotation_target, unnecessary_question_mark

part of 'case_submission.dart';

// **************************************************************************
// FreezedGenerator
// **************************************************************************

T _$identity<T>(T value) => value;

final _privateConstructorUsedError = UnsupportedError(
    'It seems like you constructed your class using `MyClass._()`. This constructor is only meant to be used by freezed and you are not supposed to need it nor use it.\nPlease check the documentation here for more information: https://github.com/rrousselGit/freezed#adding-getters-and-methods-to-our-models');

DentalComplaint _$DentalComplaintFromJson(Map<String, dynamic> json) {
  return _DentalComplaint.fromJson(json);
}

/// @nodoc
mixin _$DentalComplaint {
  String get id => throw _privateConstructorUsedError;
  String get name => throw _privateConstructorUsedError;
  String get title => throw _privateConstructorUsedError;
  String get description => throw _privateConstructorUsedError;
  String get iconName => throw _privateConstructorUsedError;

  /// Serializes this DentalComplaint to a JSON map.
  Map<String, dynamic> toJson() => throw _privateConstructorUsedError;

  /// Create a copy of DentalComplaint
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  $DentalComplaintCopyWith<DentalComplaint> get copyWith =>
      throw _privateConstructorUsedError;
}

/// @nodoc
abstract class $DentalComplaintCopyWith<$Res> {
  factory $DentalComplaintCopyWith(
          DentalComplaint value, $Res Function(DentalComplaint) then) =
      _$DentalComplaintCopyWithImpl<$Res, DentalComplaint>;
  @useResult
  $Res call(
      {String id,
      String name,
      String title,
      String description,
      String iconName});
}

/// @nodoc
class _$DentalComplaintCopyWithImpl<$Res, $Val extends DentalComplaint>
    implements $DentalComplaintCopyWith<$Res> {
  _$DentalComplaintCopyWithImpl(this._value, this._then);

  // ignore: unused_field
  final $Val _value;
  // ignore: unused_field
  final $Res Function($Val) _then;

  /// Create a copy of DentalComplaint
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? name = null,
    Object? title = null,
    Object? description = null,
    Object? iconName = null,
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
      title: null == title
          ? _value.title
          : title // ignore: cast_nullable_to_non_nullable
              as String,
      description: null == description
          ? _value.description
          : description // ignore: cast_nullable_to_non_nullable
              as String,
      iconName: null == iconName
          ? _value.iconName
          : iconName // ignore: cast_nullable_to_non_nullable
              as String,
    ) as $Val);
  }
}

/// @nodoc
abstract class _$$DentalComplaintImplCopyWith<$Res>
    implements $DentalComplaintCopyWith<$Res> {
  factory _$$DentalComplaintImplCopyWith(_$DentalComplaintImpl value,
          $Res Function(_$DentalComplaintImpl) then) =
      __$$DentalComplaintImplCopyWithImpl<$Res>;
  @override
  @useResult
  $Res call(
      {String id,
      String name,
      String title,
      String description,
      String iconName});
}

/// @nodoc
class __$$DentalComplaintImplCopyWithImpl<$Res>
    extends _$DentalComplaintCopyWithImpl<$Res, _$DentalComplaintImpl>
    implements _$$DentalComplaintImplCopyWith<$Res> {
  __$$DentalComplaintImplCopyWithImpl(
      _$DentalComplaintImpl _value, $Res Function(_$DentalComplaintImpl) _then)
      : super(_value, _then);

  /// Create a copy of DentalComplaint
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? name = null,
    Object? title = null,
    Object? description = null,
    Object? iconName = null,
  }) {
    return _then(_$DentalComplaintImpl(
      id: null == id
          ? _value.id
          : id // ignore: cast_nullable_to_non_nullable
              as String,
      name: null == name
          ? _value.name
          : name // ignore: cast_nullable_to_non_nullable
              as String,
      title: null == title
          ? _value.title
          : title // ignore: cast_nullable_to_non_nullable
              as String,
      description: null == description
          ? _value.description
          : description // ignore: cast_nullable_to_non_nullable
              as String,
      iconName: null == iconName
          ? _value.iconName
          : iconName // ignore: cast_nullable_to_non_nullable
              as String,
    ));
  }
}

/// @nodoc
@JsonSerializable()
class _$DentalComplaintImpl implements _DentalComplaint {
  const _$DentalComplaintImpl(
      {required this.id,
      required this.name,
      required this.title,
      required this.description,
      required this.iconName});

  factory _$DentalComplaintImpl.fromJson(Map<String, dynamic> json) =>
      _$$DentalComplaintImplFromJson(json);

  @override
  final String id;
  @override
  final String name;
  @override
  final String title;
  @override
  final String description;
  @override
  final String iconName;

  @override
  String toString() {
    return 'DentalComplaint(id: $id, name: $name, title: $title, description: $description, iconName: $iconName)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$DentalComplaintImpl &&
            (identical(other.id, id) || other.id == id) &&
            (identical(other.name, name) || other.name == name) &&
            (identical(other.title, title) || other.title == title) &&
            (identical(other.description, description) ||
                other.description == description) &&
            (identical(other.iconName, iconName) ||
                other.iconName == iconName));
  }

  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  int get hashCode =>
      Object.hash(runtimeType, id, name, title, description, iconName);

  /// Create a copy of DentalComplaint
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  @pragma('vm:prefer-inline')
  _$$DentalComplaintImplCopyWith<_$DentalComplaintImpl> get copyWith =>
      __$$DentalComplaintImplCopyWithImpl<_$DentalComplaintImpl>(
          this, _$identity);

  @override
  Map<String, dynamic> toJson() {
    return _$$DentalComplaintImplToJson(
      this,
    );
  }
}

abstract class _DentalComplaint implements DentalComplaint {
  const factory _DentalComplaint(
      {required final String id,
      required final String name,
      required final String title,
      required final String description,
      required final String iconName}) = _$DentalComplaintImpl;

  factory _DentalComplaint.fromJson(Map<String, dynamic> json) =
      _$DentalComplaintImpl.fromJson;

  @override
  String get id;
  @override
  String get name;
  @override
  String get title;
  @override
  String get description;
  @override
  String get iconName;

  /// Create a copy of DentalComplaint
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$DentalComplaintImplCopyWith<_$DentalComplaintImpl> get copyWith =>
      throw _privateConstructorUsedError;
}
