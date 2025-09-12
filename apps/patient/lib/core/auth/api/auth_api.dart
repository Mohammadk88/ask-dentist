import 'package:dio/dio.dart';
import 'package:retrofit/retrofit.dart';
import 'package:riverpod_annotation/riverpod_annotation.dart';
import '../models/user.dart';
import '../models/auth_requests.dart';
import '../models/auth_responses.dart';
import '../../network/dio_provider.dart';

part 'auth_api.g.dart';

@RestApi()
abstract class AuthApi {
  factory AuthApi(Dio dio) = _AuthApi;

  @POST('/auth/login')
  Future<LoginResponse> login(@Body() LoginRequest request);

  @POST('/auth/register')
  Future<RegisterResponse> register(@Body() RegisterRequest request);

  @POST('/auth/logout')
  Future<void> logout();

  @POST('/auth/refresh')
  Future<RefreshResponse> refreshToken(@Field('refresh_token') String refreshToken);

  @GET('/auth/profile')
  Future<User> getProfile();

  @POST('/auth/forgot-password')
  Future<void> forgotPassword(@Body() ForgotPasswordRequest request);

  @POST('/auth/reset-password')
  Future<void> resetPassword(@Body() ResetPasswordRequest request);

  @POST('/auth/verify-email')
  Future<void> verifyEmail(@Body() VerifyEmailRequest request);

  @POST('/auth/resend-verification')
  Future<void> resendVerification();
}

@riverpod
AuthApi authApi(AuthApiRef ref) {
  final dio = ref.read(dioProvider);
  return AuthApi(dio);
}