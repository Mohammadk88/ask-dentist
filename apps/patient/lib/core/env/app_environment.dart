class AppEnvironment {
  static const String development = 'development';
  static const String staging = 'staging';
  static const String production = 'production';

  static const String environment = String.fromEnvironment(
    'ENVIRONMENT',
    defaultValue: development,
  );

  static const String baseUrl = String.fromEnvironment(
    'BASE_URL',
    defaultValue: 'http://localhost:8000/api',
  );

  static const String socketUrl = String.fromEnvironment(
    'SOCKET_URL', 
    defaultValue: 'ws://localhost:6001',
  );

  static const String appName = String.fromEnvironment(
    'APP_NAME',
    defaultValue: 'Ask Dentist Patient',
  );

  static bool get isDevelopment => environment == development;
  static bool get isStaging => environment == staging;
  static bool get isProduction => environment == production;
  
  static bool get isDebug => isDevelopment || isStaging;
}