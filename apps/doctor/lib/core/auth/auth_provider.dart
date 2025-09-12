import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import '../models/auth_models.dart';

final authProvider = StateNotifierProvider<AuthNotifier, AuthState>((ref) {
  return AuthNotifier();
});

class AuthNotifier extends StateNotifier<AuthState> {
  static const _storage = FlutterSecureStorage();
  static const String _tokenKey = 'auth_token';
  static const String _doctorKey = 'doctor_data';

  AuthNotifier() : super(const AuthState()) {
    _checkAuthStatus();
  }

  Future<void> _checkAuthStatus() async {
    state = state.copyWith(isLoading: true);
    
    try {
      final token = await _storage.read(key: _tokenKey);
      final doctorData = await _storage.read(key: _doctorKey);
      
      if (token != null && doctorData != null) {
        final doctor = Doctor.fromJson(
          Map<String, dynamic>.from(
            // In a real app, parse JSON properly
            {'id': '1', 'name': 'Dr. Smith', 'email': 'doctor@example.com', 
             'photo': '', 'specialization': 'Dentist', 'clinic': 'Main Clinic'}
          )
        );
        state = state.copyWith(
          doctor: doctor,
          isAuthenticated: true,
          isLoading: false,
        );
      } else {
        state = state.copyWith(isLoading: false);
      }
    } catch (e) {
      state = state.copyWith(
        isLoading: false,
        error: 'Authentication check failed',
      );
    }
  }

  Future<bool> signIn(String email, String password) async {
    state = state.copyWith(isLoading: true, error: null);
    
    try {
      // Simulate API call
      await Future.delayed(const Duration(seconds: 2));
      
      // Mock authentication - replace with real API call
      if (email.isNotEmpty && password.isNotEmpty) {
        const doctor = Doctor(
          id: 'doctor_1',
          name: 'Dr. Sarah Johnson',
          email: 'sarah.johnson@askdentist.com',
          photo: 'https://example.com/doctor.jpg',
          specialization: 'Cosmetic Dentistry',
          clinic: 'Smile Center Istanbul',
          phone: '+90 555 123 4567',
          licenseNumber: 'DEN2024001',
          certifications: ['Invisalign Certified', 'Implant Specialist'],
        );
        
        // Store auth data
        await _storage.write(key: _tokenKey, value: 'mock_token');
        await _storage.write(key: _doctorKey, value: 'mock_doctor_data');
        
        state = state.copyWith(
          doctor: doctor,
          isAuthenticated: true,
          isLoading: false,
        );
        return true;
      } else {
        state = state.copyWith(
          isLoading: false,
          error: 'Invalid credentials',
        );
        return false;
      }
    } catch (e) {
      state = state.copyWith(
        isLoading: false,
        error: 'Login failed: ${e.toString()}',
      );
      return false;
    }
  }

  Future<void> signOut() async {
    await _storage.delete(key: _tokenKey);
    await _storage.delete(key: _doctorKey);
    state = const AuthState();
  }

  void clearError() {
    state = state.copyWith(error: null);
  }
}