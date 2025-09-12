import 'package:dio/dio.dart';
import 'package:retrofit/retrofit.dart';
import '../models/treatment_plan.dart';

part 'plans_api_client.g.dart';

@RestApi()
abstract class PlansApiClient {
  factory PlansApiClient(Dio dio, {String baseUrl}) = _PlansApiClient;

  @GET('/treatment-plans')
  Future<List<TreatmentPlan>> getTreatmentPlans({
    @Query('page') int? page,
    @Query('limit') int? limit,
    @Query('status') TreatmentPlanStatus? status,
    @Query('case_id') String? caseId,
  });

  @GET('/treatment-plans/{id}')
  Future<TreatmentPlan> getTreatmentPlanById(@Path('id') String id);

  @POST('/treatment-plans/{id}/respond')
  Future<void> respondToPlan(
    @Path('id') String id,
    @Body() PlanResponse response,
  );

  @GET('/treatment-plans/{id}/itinerary')
  Future<dynamic> getTreatmentPlanItinerary(@Path('id') String id);
}