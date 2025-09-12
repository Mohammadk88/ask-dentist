import 'dart:convert';
import 'dart:io';
import 'package:flutter/services.dart';
import 'env.dart';

class EnvLoader {
  static Map<String, dynamic>? _envData;

  /// Load environment configuration from assets/.env.json (debug only)
  static Future<void> loadEnvConfig() async {
    if (!Env.debug) return;

    try {
      final envString = await rootBundle.loadString('assets/.env.json');
      _envData = json.decode(envString) as Map<String, dynamic>;
      print('Loaded environment config: $_envData');
    } catch (e) {
      print('No .env.json found or failed to load: $e');
      _envData = <String, dynamic>{};
    }
  }

  /// Get API base URL with optional override from .env.json
  static String getApiBaseUrl() {
    String? override;

    if (Env.debug && _envData != null) {
      override = _envData!['API_BASE_URL'] as String?;
    }

    final baseUrl = Env.apiBaseUrl(override: override);
    print('API base URL: $baseUrl');
    return baseUrl;
  }

  /// Get any environment variable from .env.json (debug only)
  static String? getEnvVar(String key) {
    if (!Env.debug || _envData == null) return null;
    return _envData![key] as String?;
  }
}
