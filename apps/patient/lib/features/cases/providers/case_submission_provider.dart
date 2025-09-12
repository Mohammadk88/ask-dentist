import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:image_picker/image_picker.dart';
import '../models/case_submission.dart';
import '../../../core/cases/models/patient_case.dart';

class CaseSubmissionState {
  final List<XFile> photos;
  final List<DentalComplaint> selectedComplaints;
  final String description;
  final CaseUrgency urgency;
  final String? medicalHistory;
  final String? currentMedications;
  final String? allergies;
  final bool isSubmitting;
  final String? error;

  CaseSubmissionState({
    this.photos = const [],
    this.selectedComplaints = const [],
    this.description = '',
    this.urgency = CaseUrgency.low,
    this.medicalHistory,
    this.currentMedications,
    this.allergies,
    this.isSubmitting = false,
    this.error,
  });

  CaseSubmissionState copyWith({
    List<XFile>? photos,
    List<DentalComplaint>? selectedComplaints,
    String? description,
    CaseUrgency? urgency,
    String? medicalHistory,
    String? currentMedications,
    String? allergies,
    bool? isSubmitting,
    String? error,
  }) {
    return CaseSubmissionState(
      photos: photos ?? this.photos,
      selectedComplaints: selectedComplaints ?? this.selectedComplaints,
      description: description ?? this.description,
      urgency: urgency ?? this.urgency,
      medicalHistory: medicalHistory ?? this.medicalHistory,
      currentMedications: currentMedications ?? this.currentMedications,
      allergies: allergies ?? this.allergies,
      isSubmitting: isSubmitting ?? this.isSubmitting,
      error: error ?? this.error,
    );
  }
}

class CaseSubmissionNotifier extends StateNotifier<CaseSubmissionState> {
  CaseSubmissionNotifier() : super(CaseSubmissionState());

  void addPhoto(XFile photo) {
    state = state.copyWith(
      photos: [...state.photos, photo],
    );
  }

  void removePhoto(int index) {
    final photos = List<XFile>.from(state.photos);
    photos.removeAt(index);
    state = state.copyWith(photos: photos);
  }

  void updateSelectedComplaints(List<DentalComplaint> complaints) {
    state = state.copyWith(selectedComplaints: complaints);
  }

  void toggleComplaint(DentalComplaint complaint) {
    final currentComplaints = List<DentalComplaint>.from(state.selectedComplaints);
    if (currentComplaints.any((c) => c.id == complaint.id)) {
      currentComplaints.removeWhere((c) => c.id == complaint.id);
    } else {
      currentComplaints.add(complaint);
    }
    state = state.copyWith(selectedComplaints: currentComplaints);
  }

  void updateDescription(String description) {
    state = state.copyWith(description: description);
  }

  void updateUrgency(CaseUrgency urgency) {
    state = state.copyWith(urgency: urgency);
  }

  void updateMedicalHistory(String? medicalHistory) {
    state = state.copyWith(medicalHistory: medicalHistory);
  }

  void updateCurrentMedications(String? medications) {
    state = state.copyWith(currentMedications: medications);
  }

  void updateAllergies(String? allergies) {
    state = state.copyWith(allergies: allergies);
  }

  Future<void> submitCase() async {
    state = state.copyWith(isSubmitting: true, error: null);
    
    try {
      // TODO: Implement actual API call
      // For now, simulate submission
      await Future.delayed(const Duration(seconds: 2));
      
      // Reset form after successful submission
      state = CaseSubmissionState();
      
      // Show success message or navigate
    } catch (e) {
      state = state.copyWith(
        isSubmitting: false,
        error: e.toString(),
      );
    }
  }

  void reset() {
    state = CaseSubmissionState();
  }
}

final caseSubmissionProvider = StateNotifierProvider<CaseSubmissionNotifier, CaseSubmissionState>((ref) {
  return CaseSubmissionNotifier();
});

// Dental complaints data
final dentalComplaintsProvider = Provider<List<DentalComplaint>>((ref) {
  return [
    const DentalComplaint(
      id: 'pain',
      name: 'pain',
      title: 'Tooth Pain',
      description: 'Sharp, throbbing, or persistent tooth pain',
      iconName: 'sentiment_very_dissatisfied',
    ),
    const DentalComplaint(
      id: 'sensitivity',
      name: 'sensitivity',
      title: 'Sensitivity',
      description: 'Pain when eating hot, cold, or sweet foods',
      iconName: 'ac_unit',
    ),
    const DentalComplaint(
      id: 'bleeding',
      name: 'bleeding',
      title: 'Bleeding Gums',
      description: 'Gums bleed when brushing or flossing',
      iconName: 'water_drop',
    ),
    const DentalComplaint(
      id: 'swelling',
      name: 'swelling',
      title: 'Swelling',
      description: 'Swollen gums, face, or jaw',
      iconName: 'face',
    ),
    const DentalComplaint(
      id: 'broken',
      name: 'broken',
      title: 'Broken Tooth',
      description: 'Chipped, cracked, or broken tooth',
      iconName: 'warning',
    ),
    const DentalComplaint(
      id: 'missing',
      name: 'missing',
      title: 'Missing Tooth',
      description: 'Lost or extracted tooth replacement',
      iconName: 'radio_button_unchecked',
    ),
    const DentalComplaint(
      id: 'cosmetic',
      name: 'cosmetic',
      title: 'Cosmetic Issues',
      description: 'Discoloration, alignment, or appearance concerns',
      iconName: 'auto_fix_high',
    ),
    const DentalComplaint(
      id: 'bad_breath',
      name: 'bad_breath',
      title: 'Bad Breath',
      description: 'Persistent bad breath or bad taste',
      iconName: 'air',
    ),
    const DentalComplaint(
      id: 'orthodontic',
      name: 'orthodontic',
      title: 'Alignment Issues',
      description: 'Crooked, crowded, or misaligned teeth',
      iconName: 'straighten',
    ),
    const DentalComplaint(
      id: 'other',
      name: 'other',
      title: 'Other',
      description: 'Other dental concerns not listed above',
      iconName: 'help_outline',
    ),
  ];
});