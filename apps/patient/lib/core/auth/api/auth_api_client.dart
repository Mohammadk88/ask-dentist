import 'package:dio/dio.dart';
import 'package:retrofit/retrofit.dart';
import '../models/user.dart';
import '../models/auth_requests.dart';

part 'auth_api_client.g.dart';

@RestApi()
abstract class AuthApiClient {
  factory AuthApiClient(Dio dio, {String baseUrl}) = _AuthApiClient;

  @POST('/auth/login')
  Future<AuthResponse> login(@Body() LoginRequest request);

  @POST('/auth/register')
  Future<AuthResponse> register(@Body() RegisterRequest request);

  @POST('/auth/refresh')
  Future<AuthTokens> refreshToken(@Body() Map<String, String> request);

  @POST('/auth/logout')
  Future<void> logout();

  @POST('/auth/forgot-password')
  Future<void> forgotPassword(@Body() Map<String, String> request);

  @POST('/auth/reset-password')
  Future<void> resetPassword(@Body() Map<String, String> request);

  @GET('/auth/me')
  Future<User> getCurrentUser();

  @PUT('/auth/profile')
  Future<User> updateProfile(@Body() Map<String, dynamic> request);
}