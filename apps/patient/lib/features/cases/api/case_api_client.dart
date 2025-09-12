import 'package:dio/dio.dart';
import 'package:retrofit/retrofit.dart';
import '../models/case.dart';

part 'case_api_client.g.dart';

@RestApi()
abstract class CaseApiClient {
  factory CaseApiClient(Dio dio, {String baseUrl}) = _CaseApiClient;

  @GET('/cases')
  Future<List<PatientCase>> getCases({
    @Query('page') int? page,
    @Query('limit') int? limit,
    @Query('status') CaseStatus? status,
  });

  @GET('/cases/{id}')
  Future<PatientCase> getCaseById(@Path('id') String id);

  @POST('/cases')
  Future<PatientCase> submitCase(@Body() SubmitCaseRequest request);

  @PUT('/cases/{id}')
  Future<PatientCase> updateCase(
    @Path('id') String id,
    @Body() Map<String, dynamic> request,
  );

  @DELETE('/cases/{id}')
  Future<void> deleteCase(@Path('id') String id);

  @POST('/upload/images')
  Future<List<String>> uploadImages(@Body() FormData formData);
}