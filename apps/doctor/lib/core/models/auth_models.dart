import 'package:freezed_annotation/freezed_annotation.dart';

part 'auth_models.freezed.dart';
part 'auth_models.g.dart';

@freezed
class Doctor with _$Doctor {
  const factory Doctor({
    required String id,
    required String name,
    required String email,
    required String photo,
    required String specialization,
    required String clinic,
    String? phone,
    String? licenseNumber,
    List<String>? certifications,
    Map<String, dynamic>? preferences,
  }) = _Doctor;

  factory Doctor.fromJson(Map<String, dynamic> json) =>
      _$DoctorFromJson(json);
}

@freezed
class AuthState with _$AuthState {
  const factory AuthState({
    Doctor? doctor,
    @Default(false) bool isLoading,
    @Default(false) bool isAuthenticated,
    String? error,
  }) = _AuthState;

  factory AuthState.fromJson(Map<String, dynamic> json) =>
      _$AuthStateFromJson(json);
}