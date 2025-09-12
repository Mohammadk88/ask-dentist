import 'package:dio/dio.dart';
import 'package:retrofit/retrofit.dart';
import '../models/doctor.dart';

part 'doctor_api_client.g.dart';

@RestApi()
abstract class DoctorApiClient {
  factory DoctorApiClient(Dio dio, {String baseUrl}) = _DoctorApiClient;

  @GET('/doctors')
  Future<List<Doctor>> getDoctors({
    @Query('page') int? page,
    @Query('limit') int? limit,
    @Query('search') String? search,
    @Query('specialization') String? specialization,
    @Query('city') String? city,
    @Query('country') String? country,
    @Query('rating_min') double? ratingMin,
    @Query('sort_by') String? sortBy,
  });

  @GET('/doctors/{id}')
  Future<Doctor> getDoctorById(@Path('id') String id);

  @GET('/doctors/{id}/services')
  Future<List<Service>> getDoctorServices(@Path('id') String id);

  @GET('/doctors/{id}/before-after')
  Future<List<BeforeAfterImage>> getDoctorBeforeAfterImages(@Path('id') String id);

  @POST('/doctors/{id}/favorite')
  Future<void> addToFavorites(@Path('id') String id);

  @DELETE('/doctors/{id}/favorite')
  Future<void> removeFromFavorites(@Path('id') String id);

  @GET('/doctors/favorites')
  Future<List<Doctor>> getFavoriteDoctors();

  @GET('/doctors/random')
  Future<List<Doctor>> getRandomDoctors({
    @Query('limit') int? limit,
  });

  @GET('/clinics')
  Future<List<Clinic>> getClinics({
    @Query('page') int? page,
    @Query('limit') int? limit,
    @Query('search') String? search,
    @Query('city') String? city,
    @Query('country') String? country,
  });

  @GET('/clinics/{id}')
  Future<Clinic> getClinicById(@Path('id') String id);
}