import 'package:freezed_annotation/freezed_annotation.dart';

part 'case_submission.freezed.dart';
part 'case_submission.g.dart';

@freezed
class DentalComplaint with _$DentalComplaint {
  const factory DentalComplaint({
    required String id,
    required String name,
    required String title,
    required String description,
    required String iconName,
  }) = _DentalComplaint;

  factory DentalComplaint.fromJson(Map<String, dynamic> json) => _$DentalComplaintFromJson(json);
}