import 'dart:io';

class Env {
  static const bool debug = bool.fromEnvironment('dart.vm.product') == false;

  // Detect platform and return correct base URL for local dev:
  // - Android emulator -> http://10.0.2.2:8000
  // - iOS simulator   -> http://localhost:8000
  // - Physical device -> fallback to LAN IP, read from .env.json key "API_BASE_URL"
  static String apiBaseUrl({String? override}) {
    if (override != null && override.isNotEmpty) return override;

    if (Env.debug) {
      if (Platform.isAndroid) return "http://10.0.2.2:8000";
      if (Platform.isIOS) return "http://localhost:8000";
      // web/desktop fallback:
      return "http://localhost:8000";
    }

    // Production uses injected value at build time.
    return const String.fromEnvironment("API_BASE_URL",
        defaultValue: "https://api.ask.dentist");
  }
}
