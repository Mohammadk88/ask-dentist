import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../models/auth_event.dart';

class AuthEventController extends StateNotifier<AuthEvent?> {
  AuthEventController() : super(null);

  void emitEvent(AuthEvent event) {
    state = event;
  }

  void clearEvent() {
    state = null;
  }
}

final authEventControllerProvider = StateNotifierProvider<AuthEventController, AuthEvent?>((ref) {
  return AuthEventController();
});