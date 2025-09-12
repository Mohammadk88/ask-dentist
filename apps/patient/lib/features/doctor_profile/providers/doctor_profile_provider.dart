import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../core/models/doctor.dart';
import '../../../core/models/review.dart';
import '../../../core/models/clinic.dart';
import '../models/doctor_profile_models.dart';

class DoctorProfileState {
  final Doctor? doctor;
  final List<Review> reviews;
  final List<DoctorService> services;
  final List<BeforeAfterCase> beforeAfterCases;
  final List<DoctorAvailability> availability;
  final bool isFavorite;
  final bool isLoading;
  final String? error;

  const DoctorProfileState({
    this.doctor,
    this.reviews = const [],
    this.services = const [],
    this.beforeAfterCases = const [],
    this.availability = const [],
    this.isFavorite = false,
    this.isLoading = false,
    this.error,
  });

  DoctorProfileState copyWith({
    Doctor? doctor,
    List<Review>? reviews,
    List<DoctorService>? services,
    List<BeforeAfterCase>? beforeAfterCases,
    List<DoctorAvailability>? availability,
    bool? isFavorite,
    bool? isLoading,
    String? error,
  }) {
    return DoctorProfileState(
      doctor: doctor ?? this.doctor,
      reviews: reviews ?? this.reviews,
      services: services ?? this.services,
      beforeAfterCases: beforeAfterCases ?? this.beforeAfterCases,
      availability: availability ?? this.availability,
      isFavorite: isFavorite ?? this.isFavorite,
      isLoading: isLoading ?? this.isLoading,
      error: error ?? this.error,
    );
  }
}

class DoctorProfileNotifier extends StateNotifier<DoctorProfileState> {
  DoctorProfileNotifier() : super(const DoctorProfileState());

  void loadDoctorProfile(String doctorId) async {
    state = state.copyWith(isLoading: true, error: null);

    try {
      // Mock data - replace with actual API call
      final doctor = Doctor(
        id: doctorId,
        firstName: 'Sarah',
        lastName: 'Johnson',
        profileImageUrl: 'https://randomuser.me/api/portraits/women/1.jpg',
        clinicId: '1',
        specialty: DoctorSpecialty.cosmeticDentistry,
        rating: 4.8,
        reviewCount: 156,
        experienceYears: 12,
        languages: ['English', 'Turkish', 'Spanish'],
        services: ['Dental Veneers', 'Dental Implants', 'Teeth Whitening'],
        bio: 'Dr. Sarah Johnson is a highly experienced cosmetic dentist with over 12 years of practice. She specializes in smile makeovers, veneers, and advanced dental implants. Dr. Johnson has helped thousands of patients achieve their dream smiles.',
        education: 'DDS - Harvard School of Dental Medicine (2011), Residency in Cosmetic Dentistry - UCLA (2013)',
        isAvailable: true,
      );

      final reviews = [
        Review(
          id: '1',
          patientName: 'Maria Garcia',
          patientImageUrl: 'https://randomuser.me/api/portraits/women/2.jpg',
          rating: 5,
          comment: 'Excellent service! Dr. Johnson transformed my smile completely. The whole process was professional and comfortable.',
          createdAt: DateTime.now().subtract(const Duration(days: 5)),
          treatmentType: 'Dental Veneers',
          isVerified: true,
        ),
        Review(
          id: '2',
          patientName: 'John Smith',
          patientImageUrl: 'https://randomuser.me/api/portraits/men/1.jpg',
          rating: 5,
          comment: 'Amazing results! The dental implants look and feel completely natural. Highly recommend Dr. Johnson.',
          createdAt: DateTime.now().subtract(const Duration(days: 12)),
          treatmentType: 'Dental Implants',
          isVerified: true,
        ),
        Review(
          id: '3',
          patientName: 'Elena Rodriguez',
          patientImageUrl: 'https://randomuser.me/api/portraits/women/3.jpg',
          rating: 4,
          comment: 'Very professional and caring. The teeth whitening results exceeded my expectations.',
          createdAt: DateTime.now().subtract(const Duration(days: 18)),
          treatmentType: 'Teeth Whitening',
          isVerified: true,
        ),
      ];

      final services = [
        DoctorService(
          id: '1',
          name: 'Dental Veneers',
          description: 'Transform your smile with custom porcelain veneers',
          price: 800,
          duration: '2-3 visits',
          category: ServiceCategory.cosmetic,
        ),
        DoctorService(
          id: '2',
          name: 'Dental Implants',
          description: 'Permanent tooth replacement solution',
          price: 1200,
          duration: '3-6 months',
          category: ServiceCategory.restorative,
        ),
        DoctorService(
          id: '3',
          name: 'Teeth Whitening',
          description: 'Professional teeth whitening treatment',
          price: 300,
          duration: '1 visit',
          category: ServiceCategory.cosmetic,
        ),
        DoctorService(
          id: '4',
          name: 'Smile Makeover',
          description: 'Complete smile transformation package',
          price: 2500,
          duration: '4-6 visits',
          category: ServiceCategory.cosmetic,
        ),
      ];

      final beforeAfterCases = [
        BeforeAfterCase(
          id: '1',
          title: 'Dental Veneers Transformation',
          beforeImageUrl: 'https://via.placeholder.com/300x200?text=Before+1',
          afterImageUrl: 'https://via.placeholder.com/300x200?text=After+1',
          doctorId: doctorId,
          clinicId: '1',
          procedure: 'Dental Veneers',
          description: 'Complete smile makeover with porcelain veneers',
          durationDays: 14,
          createdAt: DateTime.now().subtract(const Duration(days: 30)),
        ),
        BeforeAfterCase(
          id: '2',
          title: 'Dental Implants Success',
          beforeImageUrl: 'https://via.placeholder.com/300x200?text=Before+2',
          afterImageUrl: 'https://via.placeholder.com/300x200?text=After+2',
          doctorId: doctorId,
          clinicId: '1',
          procedure: 'Dental Implants',
          description: 'Full mouth reconstruction with implants',
          durationDays: 120,
          createdAt: DateTime.now().subtract(const Duration(days: 60)),
        ),
        BeforeAfterCase(
          id: '3',
          title: 'Professional Whitening',
          beforeImageUrl: 'https://via.placeholder.com/300x200?text=Before+3',
          afterImageUrl: 'https://via.placeholder.com/300x200?text=After+3',
          doctorId: doctorId,
          clinicId: '1',
          procedure: 'Teeth Whitening',
          description: 'Professional whitening treatment results',
          durationDays: 1,
          createdAt: DateTime.now().subtract(const Duration(days: 10)),
        ),
      ];

      final availability = [
        DoctorAvailability(
          date: DateTime.now().add(const Duration(days: 1)),
          timeSlots: [
            TimeSlot(time: '09:00', isAvailable: true),
            TimeSlot(time: '10:30', isAvailable: true),
            TimeSlot(time: '14:00', isAvailable: false),
            TimeSlot(time: '15:30', isAvailable: true),
          ],
        ),
        DoctorAvailability(
          date: DateTime.now().add(const Duration(days: 2)),
          timeSlots: [
            TimeSlot(time: '09:00', isAvailable: false),
            TimeSlot(time: '11:00', isAvailable: true),
            TimeSlot(time: '13:30', isAvailable: true),
            TimeSlot(time: '16:00', isAvailable: true),
          ],
        ),
      ];

      state = state.copyWith(
        doctor: doctor,
        reviews: reviews,
        services: services,
        beforeAfterCases: beforeAfterCases,
        availability: availability,
        isLoading: false,
      );
    } catch (e) {
      state = state.copyWith(
        isLoading: false,
        error: e.toString(),
      );
    }
  }

  void toggleFavorite() {
    state = state.copyWith(isFavorite: !state.isFavorite);
  }

  Future<bool> bookConsultation({
    required ConsultationType type,
    required DateTime date,
    required String time,
    String? notes,
  }) async {
    try {
      // Mock booking - replace with actual API call
      await Future.delayed(const Duration(seconds: 2));
      
      // Update availability to mark the slot as booked
      final updatedAvailability = state.availability.map((availability) {
        if (availability.date.day == date.day) {
          final updatedSlots = availability.timeSlots.map((slot) {
            if (slot.time == time) {
              return slot.copyWith(isAvailable: false);
            }
            return slot;
          }).toList();
          return availability.copyWith(timeSlots: updatedSlots);
        }
        return availability;
      }).toList();

      state = state.copyWith(availability: updatedAvailability);
      return true;
    } catch (e) {
      state = state.copyWith(error: e.toString());
      return false;
    }
  }
}

final doctorProfileProvider = StateNotifierProvider<DoctorProfileNotifier, DoctorProfileState>(
  (ref) => DoctorProfileNotifier(),
);