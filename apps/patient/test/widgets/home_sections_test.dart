import 'package:flutter/material.dart';
import 'package:flutter/semantics.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import 'package:ask_dentist_patient/features/home/models/home_dto.dart';
import 'package:ask_dentist_patient/core/models/clinic.dart' as clinic_models;
import 'package:ask_dentist_patient/features/home/widgets/stories_section.dart';
import 'package:ask_dentist_patient/features/home/widgets/clinics_slider.dart';
import 'package:ask_dentist_patient/features/home/widgets/before_after_gallery.dart';
import 'package:ask_dentist_patient/features/home/widgets/doctors_section.dart';

void main() {
  group('Home Sections Widget Tests', () {
    Widget createTestWidget(Widget child) {
      return ProviderScope(
        child: MaterialApp(
          home: Scaffold(body: child),
        ),
      );
    }

    group('StoriesSection Tests', () {
      testWidgets('StoriesSection renders with dummy data', (WidgetTester tester) async {
        // Arrange
        final stories = [
          StoryItem(
            id: '1',
            ownerName: 'Dr. Smith',
            avatarUrl: 'https://example.com/avatar1.jpg',
            content: 'Amazing transformation!',
            contentType: StoryContentType.text,
            caption: 'Patient had incredible results',
            isViewed: false,
            timestamp: DateTime.now().subtract(const Duration(hours: 2)),
            isPromoted: false,
          ),
          StoryItem(
            id: '2',
            ownerName: 'Dr. Johnson',
            avatarUrl: 'https://example.com/avatar2.jpg',
            content: 'https://example.com/story2.jpg',
            contentType: StoryContentType.image,
            caption: 'Another success story',
            isViewed: true,
            timestamp: DateTime.now().subtract(const Duration(hours: 5)),
            isPromoted: true,
          ),
        ];

        // Act
        await tester.pumpWidget(
          createTestWidget(StoriesSection(stories: stories)),
        );

        // Assert
        expect(find.byType(StoriesSection), findsOneWidget);
        expect(find.text('Success Stories'), findsOneWidget);
        expect(find.text('View All'), findsOneWidget);
        expect(find.text('Dr. Smith'), findsOneWidget);
        expect(find.text('Dr. Johnson'), findsOneWidget);
        
        // Check for promoted indicator
        expect(find.byIcon(Icons.star), findsOneWidget);
        
        // Check for viewed indicator
        expect(find.byIcon(Icons.check), findsOneWidget);
      });

      testWidgets('StoriesSection shows empty state when no stories', (WidgetTester tester) async {
        // Act
        await tester.pumpWidget(
          createTestWidget(const StoriesSection(stories: [])),
        );

        // Assert
        expect(find.byType(SizedBox), findsOneWidget); // SizedBox.shrink()
      });

      testWidgets('Story tap opens viewer', (WidgetTester tester) async {
        // Arrange
        final stories = [
          StoryItem(
            id: '1',
            ownerName: 'Dr. Test',
            avatarUrl: 'https://example.com/avatar.jpg',
            content: 'Test story',
            contentType: StoryContentType.text,
            caption: 'Test caption',
            isViewed: false,
            timestamp: DateTime.now(),
            isPromoted: false,
          ),
        ];

        // Act
        await tester.pumpWidget(
          createTestWidget(StoriesSection(stories: stories)),
        );

        // Tap on story
        await tester.tap(find.text('Dr. Test'));
        await tester.pumpAndSettle();

        // Assert
        expect(find.byType(StoryViewer), findsOneWidget);
        expect(find.text('1 / 1'), findsOneWidget);
      });
    });

    group('ClinicsSlider Tests', () {
      testWidgets('ClinicsSlider renders with dummy data', (WidgetTester tester) async {
        // Arrange
        final clinics = [
          ClinicItem(
            id: '1',
            name: 'Bright Dental',
            imageUrl: 'https://example.com/clinic1.jpg',
            rating: 4.8,
            reviewCount: 120,
            isPromoted: false,
            isVerified: true,
            location: 'New York',
          ),
          ClinicItem(
            id: '2',
            name: 'Smile Clinic',
            imageUrl: 'https://example.com/clinic2.jpg',
            rating: 4.6,
            reviewCount: 95,
            isPromoted: true,
            isVerified: true,
            location: 'Los Angeles',
          ),
        ];

        // Act
        await tester.pumpWidget(
          createTestWidget(ClinicsSlider(clinics: clinics)),
        );

        // Assert
        expect(find.byType(ClinicsSlider), findsOneWidget);
        expect(find.text('Bright Dental'), findsOneWidget);
        expect(find.text('Smile Clinic'), findsOneWidget);
        expect(find.text('4.8'), findsOneWidget);
        expect(find.text('4.6'), findsOneWidget);
      });

      testWidgets('ClinicsSlider shows empty state when no clinics', (WidgetTester tester) async {
        // Act
        await tester.pumpWidget(
          createTestWidget(const ClinicsSlider(clinics: [])),
        );

        // Assert
        expect(find.byType(SizedBox), findsOneWidget); // SizedBox.shrink()
      });
    });

    group('BeforeAfterGallery Tests', () {
      testWidgets('BeforeAfterGallery renders with dummy data', (WidgetTester tester) async {
        // Arrange
        final cases = [
          BeforeAfterCase(
            id: '1',
            title: 'Dental Transformation',
            beforeImageUrl: 'https://example.com/before1.jpg',
            afterImageUrl: 'https://example.com/after1.jpg',
            doctorId: 'doctor1',
            clinicId: 'clinic1',
            procedure: 'Porcelain Veneers',
            description: 'Amazing transformation with veneers',
            durationDays: 14,
            createdAt: DateTime.now(),
          ),
          BeforeAfterCase(
            id: '2',
            title: 'Whitening Results',
            beforeImageUrl: 'https://example.com/before2.jpg',
            afterImageUrl: 'https://example.com/after2.jpg',
            doctorId: 'doctor2',
            clinicId: 'clinic2',
            procedure: 'Laser Whitening',
            description: 'Professional whitening results',
            durationDays: 7,
            createdAt: DateTime.now(),
          ),
        ];

        // Act
        await tester.pumpWidget(
          createTestWidget(BeforeAfterGallery(cases: cases)),
        );

        // Assert
        expect(find.byType(BeforeAfterGallery), findsOneWidget);
        expect(find.text('Dental Transformation'), findsOneWidget);
        expect(find.text('Whitening Results'), findsOneWidget);
        expect(find.text('Porcelain Veneers'), findsOneWidget);
        expect(find.text('Laser Whitening'), findsOneWidget);
      });

      testWidgets('BeforeAfterGallery shows empty state when no cases', (WidgetTester tester) async {
        // Act
        await tester.pumpWidget(
          createTestWidget(const BeforeAfterGallery(cases: [])),
        );

        // Assert
        expect(find.byType(SizedBox), findsOneWidget); // SizedBox.shrink()
      });
    });

    group('DoctorsSection Tests', () {
      testWidgets('DoctorsSection renders with dummy data', (WidgetTester tester) async {
        // Arrange
        final doctors = [
          DoctorItem(
            id: '1',
            name: 'Dr. Sarah Wilson',
            specialty: 'Cosmetic Dentistry',
            avatarUrl: 'https://example.com/doctor1.jpg',
            rating: 4.9,
            reviewCount: 156,
            experience: '10 years',
            languages: ['English', 'Spanish'],
            isOnline: true,
            isFavorite: false,
            clinicName: 'Bright Dental Clinic',
            consultationFee: 150,
          ),
          DoctorItem(
            id: '2',
            name: 'Dr. Michael Brown',
            specialty: 'Orthodontics',
            avatarUrl: 'https://example.com/doctor2.jpg',
            rating: 4.7,
            reviewCount: 134,
            experience: '8 years',
            languages: ['English', 'French'],
            isOnline: false,
            isFavorite: true,
            clinicName: 'Smile Center',
            consultationFee: 120,
          ),
        ];

        // Act
        await tester.pumpWidget(
          createTestWidget(
            DoctorsSection(
              title: 'Recommended Doctors',
              doctors: doctors,
            ),
          ),
        );

        // Assert
        expect(find.byType(DoctorsSection), findsOneWidget);
        expect(find.text('Recommended Doctors'), findsOneWidget);
        expect(find.text('View All'), findsOneWidget);
        expect(find.text('Dr. Sarah Wilson'), findsOneWidget);
        expect(find.text('Dr. Michael Brown'), findsOneWidget);
        expect(find.text('Cosmetic Dentistry'), findsOneWidget);
        expect(find.text('Orthodontics'), findsOneWidget);
      });

      testWidgets('DoctorsSection shows empty state when no doctors', (WidgetTester tester) async {
        // Act
        await tester.pumpWidget(
          createTestWidget(
            const DoctorsSection(
              title: 'Test Doctors',
              doctors: [],
            ),
          ),
        );

        // Assert
        expect(find.byType(SizedBox), findsOneWidget); // SizedBox.shrink()
      });

      testWidgets('DoctorsSection handles doctor tap', (WidgetTester tester) async {
        // Arrange
        bool wasTapped = false;
        DoctorItem? tappedDoctor;
        
        final doctors = [
          DoctorItem(
            id: '1',
            name: 'Dr. Test',
            specialty: 'General Dentistry',
            avatarUrl: 'https://example.com/doctor.jpg',
            rating: 4.5,
            reviewCount: 100,
            experience: '5 years',
            languages: ['English'],
            isOnline: true,
            isFavorite: false,
            clinicName: 'Test Clinic',
            consultationFee: 100,
          ),
        ];

        // Act
        await tester.pumpWidget(
          createTestWidget(
            DoctorsSection(
              title: 'Test Doctors',
              doctors: doctors,
              onDoctorTap: (doctor) {
                wasTapped = true;
                tappedDoctor = doctor;
              },
            ),
          ),
        );

        // Tap the doctor card
        await tester.tap(find.text('Dr. Test'));
        await tester.pump();

        // Assert
        expect(wasTapped, isTrue);
        expect(tappedDoctor?.id, equals('1'));
      });
    });

    group('Integration Tests', () {
      testWidgets('All sections render together with consistent styling', (WidgetTester tester) async {
        // Arrange
        final stories = [
          StoryItem(
            id: '1',
            ownerName: 'Dr. Test',
            avatarUrl: 'https://example.com/avatar.jpg',
            content: 'Test content',
            contentType: StoryContentType.text,
            caption: 'Test caption',
            isViewed: false,
            timestamp: DateTime.now(),
            isPromoted: false,
          ),
        ];

        final clinics = [
          ClinicItem(
            id: '1',
            name: 'Test Clinic',
            imageUrl: 'https://example.com/clinic.jpg',
            rating: 4.5,
            reviewCount: 100,
            isPromoted: false,
            isVerified: true,
            location: 'Test City',
          ),
        ];

        final cases = [
          BeforeAfterCase(
            id: '1',
            title: 'Test Case',
            beforeImageUrl: 'https://example.com/before.jpg',
            afterImageUrl: 'https://example.com/after.jpg',
            doctorId: 'doctor1',
            clinicId: 'clinic1',
            procedure: 'Test Procedure',
            description: 'Test description',
            durationDays: 7,
          ),
        ];

        final doctors = [
          DoctorItem(
            id: '1',
            name: 'Dr. Test',
            specialty: 'Test Specialty',
            avatarUrl: 'https://example.com/doctor.jpg',
            rating: 4.5,
            reviewCount: 100,
            experience: '5 years',
            languages: ['English'],
            isOnline: true,
            isFavorite: false,
            clinicName: 'Test Clinic',
            consultationFee: 100,
          ),
        ];

        // Act
        await tester.pumpWidget(
          createTestWidget(
            Column(
              children: [
                StoriesSection(stories: stories),
                ClinicsSlider(clinics: clinics),
                BeforeAfterGallery(cases: cases),
                DoctorsSection(title: 'Test Doctors', doctors: doctors),
              ],
            ),
          ),
        );

        // Assert
        expect(find.byType(StoriesSection), findsOneWidget);
        expect(find.byType(ClinicsSlider), findsOneWidget);
        expect(find.byType(BeforeAfterGallery), findsOneWidget);
        expect(find.byType(DoctorsSection), findsOneWidget);
      });

      testWidgets('Accessibility labels are present', (WidgetTester tester) async {
        // Arrange
        final stories = [
          StoryItem(
            id: '1',
            ownerName: 'Dr. Accessible',
            avatarUrl: 'https://example.com/avatar.jpg',
            content: 'Accessible content',
            contentType: StoryContentType.text,
            caption: 'Accessible caption',
            isViewed: false,
            timestamp: DateTime.now(),
            isPromoted: true,
          ),
        ];

        // Act
        await tester.pumpWidget(
          createTestWidget(StoriesSection(stories: stories)),
        );

        // Assert - Check for semantic labels
        final semantics = tester.getSemantics(find.text('View All'));
        expect(semantics.label, contains('View all stories'));
        expect(semantics.hasFlag(SemanticsFlag.isButton), isTrue);
      });

      testWidgets('Promoted content indicators work correctly', (WidgetTester tester) async {
        // Arrange
        final promotedStory = StoryItem(
          id: '1',
          ownerName: 'Dr. Promoted',
          avatarUrl: 'https://example.com/avatar.jpg',
          content: 'Promoted content',
          contentType: StoryContentType.text,
          caption: 'Promoted caption',
          isViewed: false,
          timestamp: DateTime.now(),
          isPromoted: true,
        );

        final regularStory = StoryItem(
          id: '2',
          ownerName: 'Dr. Regular',
          avatarUrl: 'https://example.com/avatar2.jpg',
          content: 'Regular content',
          contentType: StoryContentType.text,
          caption: 'Regular caption',
          isViewed: false,
          timestamp: DateTime.now(),
          isPromoted: false,
        );

        // Act
        await tester.pumpWidget(
          createTestWidget(StoriesSection(stories: [promotedStory, regularStory])),
        );

        // Assert
        expect(find.byIcon(Icons.star), findsOneWidget); // Only one promoted
      });
    });
  });
}