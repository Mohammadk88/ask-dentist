import 'package:freezed_annotation/freezed_annotation.dart';

part 'auth_event.freezed.dart';

@freezed
class AuthEvent with _$AuthEvent {
  const factory AuthEvent.showLoginSheet({
    String? message,
  }) = _ShowLoginSheet;
  
  const factory AuthEvent.hideLoginSheet() = _HideLoginSheet;
  
  const factory AuthEvent.refreshHome() = _RefreshHome;
}