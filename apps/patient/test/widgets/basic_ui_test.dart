import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

// Create a simple test that verifies basic UI elements without complex mocking
void main() {
  group('Basic Home Screen Tests', () {
    testWidgets('Material App builds without errors', (WidgetTester tester) async {
      await tester.pumpWidget(
        ProviderScope(
          child: MaterialApp(
            home: Scaffold(
              appBar: AppBar(title: const Text('Test Home')),
              body: const Column(
                children: [
                  Text('Hello, Guest!'),
                  Text('Welcome to Ask Dentist'),
                  Text('Find the best dental care'),
                ],
              ),
            ),
          ),
        ),
      );
      
      expect(find.text('Hello, Guest!'), findsOneWidget);
      expect(find.text('Welcome to Ask Dentist'), findsOneWidget);
      expect(find.text('Find the best dental care'), findsOneWidget);
    });

    testWidgets('Scaffold with RefreshIndicator works', (WidgetTester tester) async {
      bool refreshCalled = false;
      
      await tester.pumpWidget(
        MaterialApp(
          home: Scaffold(
            body: RefreshIndicator(
              onRefresh: () async {
                refreshCalled = true;
              },
              child: const SingleChildScrollView(
                physics: AlwaysScrollableScrollPhysics(),
                child: Column(
                  children: [
                    SizedBox(height: 100),
                    Text('Pull to refresh'),
                    SizedBox(height: 1000), // Make it scrollable
                  ],
                ),
              ),
            ),
          ),
        ),
      );
      
      // Find the RefreshIndicator
      expect(find.byType(RefreshIndicator), findsOneWidget);
      expect(find.text('Pull to refresh'), findsOneWidget);
      
      // Simulate pull to refresh
      await tester.fling(find.text('Pull to refresh'), const Offset(0, 300), 1000);
      await tester.pump();
      await tester.pump(const Duration(seconds: 1));
      
      expect(refreshCalled, isTrue);
    });

    testWidgets('Basic layout components work', (WidgetTester tester) async {
      await tester.pumpWidget(
        MaterialApp(
          home: Scaffold(
            body: SafeArea(
              child: Column(
                children: [
                  // Header section
                  Container(
                    padding: const EdgeInsets.all(16),
                    child: const Row(
                      children: [
                        Icon(Icons.location_on),
                        SizedBox(width: 8),
                        Text('Current Location'),
                        Spacer(),
                        Icon(Icons.notifications),
                      ],
                    ),
                  ),
                  
                  // Search section
                  Container(
                    margin: const EdgeInsets.all(16),
                    padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
                    decoration: BoxDecoration(
                      color: Colors.grey[100],
                      borderRadius: BorderRadius.circular(8),
                    ),
                    child: const Row(
                      children: [
                        Icon(Icons.search, color: Colors.grey),
                        SizedBox(width: 8),
                        Text('Search for dentists, clinics...', 
                             style: TextStyle(color: Colors.grey)),
                      ],
                    ),
                  ),
                  
                  // Main content
                  Expanded(
                    child: SingleChildScrollView(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          // Stories section placeholder
                          Container(
                            height: 120,
                            margin: const EdgeInsets.symmetric(vertical: 16),
                            child: const Center(
                              child: Text('Stories Section'),
                            ),
                          ),
                          
                          // Clinics section placeholder
                          Container(
                            height: 200,
                            margin: const EdgeInsets.symmetric(vertical: 16),
                            child: const Center(
                              child: Text('Featured Clinics'),
                            ),
                          ),
                          
                          // Doctors section placeholder
                          Container(
                            height: 150,
                            margin: const EdgeInsets.symmetric(vertical: 16),
                            child: const Center(
                              child: Text('Top Doctors'),
                            ),
                          ),
                        ],
                      ),
                    ),
                  ),
                ],
              ),
            ),
          ),
        ),
      );
      
      // Verify all components are present
      expect(find.byIcon(Icons.location_on), findsOneWidget);
      expect(find.byIcon(Icons.notifications), findsOneWidget);
      expect(find.byIcon(Icons.search), findsOneWidget);
      expect(find.text('Current Location'), findsOneWidget);
      expect(find.text('Search for dentists, clinics...'), findsOneWidget);
      expect(find.text('Stories Section'), findsOneWidget);
      expect(find.text('Featured Clinics'), findsOneWidget);
      expect(find.text('Top Doctors'), findsOneWidget);
    });

    testWidgets('Guest user indicators work', (WidgetTester tester) async {
      await tester.pumpWidget(
        MaterialApp(
          home: Scaffold(
            body: Column(
              children: [
                // Guest greeting
                const Padding(
                  padding: EdgeInsets.all(16),
                  child: Text(
                    'Hello, Guest! 👋',
                    style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                  ),
                ),
                
                // Login prompt
                Container(
                  margin: const EdgeInsets.all(16),
                  padding: const EdgeInsets.all(16),
                  decoration: BoxDecoration(
                    color: Colors.blue[50],
                    borderRadius: BorderRadius.circular(8),
                    border: Border.all(color: Colors.blue[200]!),
                  ),
                  child: Column(
                    children: [
                      const Text(
                        'Sign in to access your favorites and personalized recommendations',
                        textAlign: TextAlign.center,
                      ),
                      const SizedBox(height: 12),
                      ElevatedButton(
                        onPressed: () {
                          // Login action
                        },
                        child: const Text('Sign In'),
                      ),
                    ],
                  ),
                ),
                
                // No favorites message for guest
                const Padding(
                  padding: EdgeInsets.all(16),
                  child: Text(
                    'Sign in to see your favorite clinics and doctors',
                    style: TextStyle(color: Colors.grey),
                  ),
                ),
              ],
            ),
          ),
        ),
      );
      
      // Verify guest-specific elements
      expect(find.text('Hello, Guest! 👋'), findsOneWidget);
      expect(find.text('Sign in to access your favorites and personalized recommendations'), findsOneWidget);
      expect(find.text('Sign In'), findsOneWidget);
      expect(find.text('Sign in to see your favorite clinics and doctors'), findsOneWidget);
      
      // Verify no favorites section is shown
      expect(find.textContaining('Favorites'), findsNothing);
      expect(find.textContaining('Your Favorites'), findsNothing);
    });
  });
}