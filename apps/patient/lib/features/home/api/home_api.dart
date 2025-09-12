import 'package:dio/dio.dart';
import 'package:retrofit/retrofit.dart';
import '../models/home_api_dto.dart';

part 'home_api.g.dart';

@RestApi()
abstract class HomeApi {
  factory HomeApi(Dio dio, {String baseUrl}) = _HomeApi;

  @GET('/api/v1/home')
  Future<HomeResponseDto> getHomeFeed();
}
