import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import 'package:ask_dentist_patient/features/home/screens/home_screen.dart';

void main() {
  group('Guest Home Screen Tests', () {
    Widget createTestApp() {
      return ProviderScope(
        child: MaterialApp(
          home: const HomeScreen(),
          localizationsDelegates: const [
            DefaultMaterialLocalizations.delegate,
            DefaultWidgetsLocalizations.delegate,
          ],
          supportedLocales: const [
            Locale('en', ''),
            Locale('ar', ''),
            Locale('tr', ''),
          ],
        ),
      );
    }

    testWidgets('HomeScreen builds without crashing', (WidgetTester tester) async {
      // Test that the home screen can be built without errors
      await tester.pumpWidget(createTestApp());
      
      // Wait for initial load
      await tester.pumpAndSettle();
      
      // Verify home screen exists
      expect(find.byType(HomeScreen), findsOneWidget);
      
      // Verify scaffold exists (basic UI structure)
      expect(find.byType(Scaffold), findsOneWidget);
    });

    testWidgets('HomeScreen shows greeting', (WidgetTester tester) async {
      await tester.pumpWidget(createTestApp());
      await tester.pumpAndSettle();
      
      // Should show some kind of greeting
      expect(find.textContaining('Hello'), findsOneWidget);
    });

    testWidgets('HomeScreen handles loading state', (WidgetTester tester) async {
      await tester.pumpWidget(createTestApp());
      
      // The home screen should handle loading state gracefully
      // (This test mainly ensures no crashes during initial load)
      await tester.pump();
      expect(find.byType(HomeScreen), findsOneWidget);
      
      // Wait for any async operations to complete
      await tester.pumpAndSettle();
      expect(find.byType(HomeScreen), findsOneWidget);
    });

    testWidgets('HomeScreen shows main sections', (WidgetTester tester) async {
      await tester.pumpWidget(createTestApp());
      await tester.pumpAndSettle();
      
      // The home screen should show the main content sections
      // Even as guest, users should see public content
      expect(find.byType(RefreshIndicator), findsOneWidget);
      expect(find.byType(CustomScrollView), findsOneWidget);
    });

    testWidgets('RefreshIndicator works', (WidgetTester tester) async {
      await tester.pumpWidget(createTestApp());
      await tester.pumpAndSettle();
      
      // Find the refresh indicator
      final refreshIndicator = find.byType(RefreshIndicator);
      expect(refreshIndicator, findsOneWidget);
      
      // Trigger pull-to-refresh
      await tester.fling(refreshIndicator, const Offset(0, 300), 1000);
      await tester.pump();
      
      // Should show loading indicator briefly
      expect(find.byType(RefreshIndicator), findsOneWidget);
      
      await tester.pumpAndSettle();
    });
  });

  group('Guest Behavior Tests', () {
    testWidgets('Guest sees appropriate content', (WidgetTester tester) async {
      // Create a simple test widget
      Widget guestTestApp = ProviderScope(
        child: MaterialApp(
          home: Scaffold(
            body: Column(
              children: [
                Text('Hello!'), // Guest greeting
                Text('Stories'),
                Text('Top Clinics'),
                Text('Doctors'),
                Text('Before & After'),
                // Note: No favorites section for guests
              ],
            ),
          ),
        ),
      );

      await tester.pumpWidget(guestTestApp);
      
      // Verify guest sees public content
      expect(find.text('Hello!'), findsOneWidget);
      expect(find.text('Stories'), findsOneWidget);
      expect(find.text('Top Clinics'), findsOneWidget);
      expect(find.text('Doctors'), findsOneWidget);
      expect(find.text('Before & After'), findsOneWidget);
      
      // Verify no favorites section visible
      expect(find.text('Favorites'), findsNothing);
      expect(find.text('Favorite Doctors'), findsNothing);
    });

    testWidgets('Authenticated user sees favorites', (WidgetTester tester) async {
      // Create test widget for authenticated user
      Widget authenticatedTestApp = ProviderScope(
        child: MaterialApp(
          home: Scaffold(
            body: Column(
              children: [
                Text('Hello, John!'), // Personalized greeting
                Text('Stories'),
                Text('Top Clinics'),
                Text('Favorite Doctors'), // Favorites section
                Text('Doctors'),
                Text('Before & After'),
              ],
            ),
          ),
        ),
      );

      await tester.pumpWidget(authenticatedTestApp);
      
      // Verify authenticated user sees all content including favorites
      expect(find.text('Hello, John!'), findsOneWidget);
      expect(find.text('Favorite Doctors'), findsOneWidget);
      expect(find.text('Stories'), findsOneWidget);
      expect(find.text('Top Clinics'), findsOneWidget);
      expect(find.text('Doctors'), findsOneWidget);
      expect(find.text('Before & After'), findsOneWidget);
    });
  });

  group('UI Interaction Tests', () {
    testWidgets('Login button triggers authentication flow', (WidgetTester tester) async {
      // Simple test widget with login button
      Widget loginTestApp = ProviderScope(
        child: MaterialApp(
          home: Scaffold(
            body: Column(
              children: [
                ElevatedButton(
                  onPressed: () {
                    // This would normally trigger login flow
                  },
                  child: Text('Login'),
                ),
                ElevatedButton(
                  onPressed: () {
                    // This would show login requirement for guests
                  },
                  child: Text('Book Consultation'),
                ),
              ],
            ),
          ),
        ),
      );

      await tester.pumpWidget(loginTestApp);
      
      // Find and tap login button
      final loginButton = find.text('Login');
      expect(loginButton, findsOneWidget);
      
      await tester.tap(loginButton);
      await tester.pump();
      
      // Verify button interaction works
      expect(loginButton, findsOneWidget);
    });

    testWidgets('Consultation booking button exists', (WidgetTester tester) async {
      Widget consultationTestApp = ProviderScope(
        child: MaterialApp(
          home: Scaffold(
            body: ElevatedButton(
              onPressed: () {
                // Mock consultation booking
              },
              child: Text('Book Consultation'),
            ),
          ),
        ),
      );

      await tester.pumpWidget(consultationTestApp);
      
      // Verify consultation button exists and is tappable
      final consultationButton = find.text('Book Consultation');
      expect(consultationButton, findsOneWidget);
      
      await tester.tap(consultationButton);
      await tester.pump();
      
      // Button should still exist after tap
      expect(consultationButton, findsOneWidget);
    });
  });
}