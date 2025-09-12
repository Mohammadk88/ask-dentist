import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../core/models/treatment_request.dart';

final inboxProvider = StateNotifierProvider<InboxNotifier, InboxState>((ref) {
  return InboxNotifier();
});

class InboxNotifier extends StateNotifier<InboxState> {
  InboxNotifier() : super(const InboxState()) {
    loadTreatmentRequests();
  }

  Future<void> loadTreatmentRequests() async {
    state = state.copyWith(isLoading: true);
    
    try {
      // Simulate API call
      await Future.delayed(const Duration(seconds: 1));
      
      final mockRequests = [
        TreatmentRequest(
          id: 'tr_001',
          patientId: 'patient_001',
          patientName: 'Emily Rodriguez',
          patientPhoto: 'https://example.com/patient1.jpg',
          treatmentType: 'Dental Implants',
          description: 'Looking for full mouth dental implants. Missing several teeth on upper jaw.',
          status: TreatmentStatus.newRequest,
          attachments: ['xray1.jpg', 'photo1.jpg'],
          submittedAt: DateTime.now().subtract(const Duration(hours: 2)),
          responseDeadline: DateTime.now().add(const Duration(days: 3)),
          urgencyLevel: 'High',
          location: 'Istanbul, Turkey',
        ),
        TreatmentRequest(
          id: 'tr_002',
          patientId: 'patient_002',
          patientName: 'James Wilson',
          patientPhoto: 'https://example.com/patient2.jpg',
          treatmentType: 'Veneers',
          description: 'Interested in porcelain veneers for front 6 teeth. Looking for Hollywood smile.',
          status: TreatmentStatus.pending,
          attachments: ['smile_photo.jpg'],
          submittedAt: DateTime.now().subtract(const Duration(days: 1)),
          responseDeadline: DateTime.now().add(const Duration(days: 2)),
          urgencyLevel: 'Medium',
          location: 'Antalya, Turkey',
        ),
        TreatmentRequest(
          id: 'tr_003',
          patientId: 'patient_003',
          patientName: 'Sarah Anderson',
          patientPhoto: 'https://example.com/patient3.jpg',
          treatmentType: 'Teeth Whitening',
          description: 'Professional teeth whitening treatment needed. Heavy coffee staining.',
          status: TreatmentStatus.inProgress,
          attachments: ['before_photo.jpg'],
          submittedAt: DateTime.now().subtract(const Duration(days: 3)),
          urgencyLevel: 'Low',
          location: 'Bodrum, Turkey',
        ),
        TreatmentRequest(
          id: 'tr_004',
          patientId: 'patient_004',
          patientName: 'Michael Chen',
          patientPhoto: 'https://example.com/patient4.jpg',
          treatmentType: 'Root Canal',
          description: 'Severe tooth pain in molar. Need urgent root canal treatment.',
          status: TreatmentStatus.newRequest,
          attachments: ['xray_molar.jpg'],
          submittedAt: DateTime.now().subtract(const Duration(minutes: 30)),
          responseDeadline: DateTime.now().add(const Duration(hours: 24)),
          urgencyLevel: 'Urgent',
          location: 'Istanbul, Turkey',
        ),
      ];
      
      state = state.copyWith(
        treatmentRequests: mockRequests,
        isLoading: false,
      );
    } catch (e) {
      state = state.copyWith(
        isLoading: false,
        error: 'Failed to load treatment requests',
      );
    }
  }

  List<TreatmentRequest> getRequestsByStatus(TreatmentStatus status) {
    return state.treatmentRequests
        .where((request) => request.status == status)
        .toList();
  }

  List<TreatmentRequest> getNewRequests() => 
      getRequestsByStatus(TreatmentStatus.newRequest);

  List<TreatmentRequest> getPendingRequests() => 
      getRequestsByStatus(TreatmentStatus.pending);

  List<TreatmentRequest> getInProgressRequests() => 
      getRequestsByStatus(TreatmentStatus.inProgress);

  Future<void> refreshRequests() async {
    await loadTreatmentRequests();
  }

  void markAsRead(String requestId) {
    // In a real app, this would make an API call
    // For now, we can just update local state if needed
  }
}

class InboxState {
  final List<TreatmentRequest> treatmentRequests;
  final bool isLoading;
  final String? error;

  const InboxState({
    this.treatmentRequests = const [],
    this.isLoading = false,
    this.error,
  });

  InboxState copyWith({
    List<TreatmentRequest>? treatmentRequests,
    bool? isLoading,
    String? error,
  }) {
    return InboxState(
      treatmentRequests: treatmentRequests ?? this.treatmentRequests,
      isLoading: isLoading ?? this.isLoading,
      error: error,
    );
  }
}