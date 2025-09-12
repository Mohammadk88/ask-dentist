import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter_localizations/flutter_localizations.dart';

import 'package:ask_dentist_patient/features/home/widgets/clinics_slider.dart';
import 'package:ask_dentist_patient/features/home/models/home_dto.dart';

void main() {
  group('Isolated Widget Tests', () {
    
    // Create mock clinic data
    final List<ClinicItem> mockClinics = [
      const ClinicItem(
        id: '1',
        name: 'Test Clinic 1',
        location: 'Test Location 1',
        imageUrl: 'https://example.com/image1.jpg',
        rating: 4.5,
        reviewCount: 100,
        isPromoted: false,
        isVerified: true,
      ),
      const ClinicItem(
        id: '2',
        name: 'Test Clinic 2', 
        location: 'Test Location 2',
        imageUrl: 'https://example.com/image2.jpg',
        rating: 4.8,
        reviewCount: 200,
        isPromoted: true,
        isVerified: true,
      ),
    ];
    
    testWidgets('ClinicsSlider renders without layout errors', (WidgetTester tester) async {
      await tester.pumpWidget(
        ProviderScope(
          child: MaterialApp(
            home: Scaffold(
              body: Container(
                height: 400,
                child: ClinicsSlider(
                  clinics: mockClinics,
                  isLoading: false,
                ),
              ),
            ),
            localizationsDelegates: const [
              GlobalMaterialLocalizations.delegate,
              GlobalWidgetsLocalizations.delegate,
              GlobalCupertinoLocalizations.delegate,
            ],
            supportedLocales: const [
              Locale('en', 'US'),
              Locale('ar', 'SA'),
            ],
          ),
        ),
      );

      // Wait for initial render
      await tester.pump();
      
      // Verify the widget doesn't crash during rendering
      expect(find.byType(ClinicsSlider), findsOneWidget);
      
      // Give it time to settle any layout issues
      await tester.pump(const Duration(milliseconds: 100));
      
      // Should not have thrown any errors during layout
    });
    
    testWidgets('ClinicsSlider shows loading state without layout errors', (WidgetTester tester) async {
      await tester.pumpWidget(
        ProviderScope(
          child: MaterialApp(
            home: Scaffold(
              body: ClinicsSlider(
                clinics: [],
                isLoading: true,
              ),
            ),
            localizationsDelegates: const [
              GlobalMaterialLocalizations.delegate,
              GlobalWidgetsLocalizations.delegate, 
              GlobalCupertinoLocalizations.delegate,
            ],
            supportedLocales: const [
              Locale('en', 'US'),
              Locale('ar', 'SA'),
            ],
          ),
        ),
      );

      await tester.pump();
      
      // Look for shimmer loading state
      expect(find.byType(ClinicsSlider), findsOneWidget);
      
      // Give shimmer time to render without layout issues
      await tester.pump(const Duration(milliseconds: 100));
    });
    
    testWidgets('ClinicsSlider shows title', (WidgetTester tester) async {
      await tester.pumpWidget(
        ProviderScope(
          child: MaterialApp(
            home: Scaffold(
              body: ClinicsSlider(
                clinics: mockClinics,
                isLoading: false,
              ),
            ),
            localizationsDelegates: const [
              GlobalMaterialLocalizations.delegate,
              GlobalWidgetsLocalizations.delegate,
              GlobalCupertinoLocalizations.delegate,
            ],
            supportedLocales: const [
              Locale('en', 'US'),
              Locale('ar', 'SA'),
            ],
          ),
        ),
      );

      await tester.pump();
      
      // Look for the "Featured Clinics" title
      expect(find.text('Featured Clinics'), findsOneWidget);
    });
  });
}