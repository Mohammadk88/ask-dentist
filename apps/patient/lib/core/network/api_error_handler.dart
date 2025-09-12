import 'package:dio/dio.dart';
import 'package:flutter/material.dart';
import '../widgets/login_bottom_sheet.dart';

/// Global error handler for API responses
class ApiErrorHandler {
  /// Handle API errors and show appropriate UI
  static Future<void> handleError(
    DioException error,
    BuildContext context,
  ) async {
    final response = error.response;
    
    if (response?.statusCode == 401) {
      final data = response?.data;
      
      // Check if this is our expected auth_required error
      if (data is Map<String, dynamic> && 
          data['error_code'] == 'auth_required') {
        
        // Show login modal automatically
        await showLoginBottomSheet(context);
        return;
      }
    }
    
    // Handle other errors
    String message = 'An error occurred';
    
    if (response?.data is Map<String, dynamic>) {
      final data = response!.data as Map<String, dynamic>;
      message = data['message'] ?? message;
    } else if (error.message != null) {
      message = error.message!;
    }
    
    if (context.mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(message),
          backgroundColor: Colors.red,
        ),
      );
    }
  }
}

/// Extension on Future\<void\> to easily add error handling
extension ApiErrorHandling<T> on Future<T> {
  Future<T> handleApiErrors(BuildContext context) async {
    try {
      return await this;
    } on DioException catch (e) {
      await ApiErrorHandler.handleError(e, context);
      rethrow;
    }
  }
}