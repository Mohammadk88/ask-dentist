class AppException implements Exception {
  final String message;
  final String? code;

  const AppException(this.message, {this.code});

  @override
  String toString() => 'AppException: $message ${code != null ? '($code)' : ''}';
}

class AppNoInternetException extends AppException {
  const AppNoInternetException() : super('No internet connection');
}

class AppHttpException extends AppException {
  final int statusCode;
  final Map<String, dynamic>? data;

  const AppHttpException(
    this.statusCode,
    String message, {
    this.data,
    String? code,
  }) : super(message, code: code);

  bool get isAuthRequired => statusCode == 401;
  bool get isNotFound => statusCode == 404;
  bool get isServerError => statusCode >= 500;

  @override
  String toString() => 'AppHttpException: $statusCode - $message';
}
