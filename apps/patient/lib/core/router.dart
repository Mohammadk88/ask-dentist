import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'navigation/main_navigation_shell.dart';
import '../features/home/screens/home_screen.dart';
import '../features/auth/screens/login_screen.dart';
import '../features/auth/screens/register_screen.dart';
import '../features/onboarding/screens/onboarding_screen.dart';
import '../features/doctor_profile/screens/doctor_profile_screen.dart';
import '../features/cases/screens/submit_case_screen.dart';
import '../features/plans/screens/treatment_plans_screen.dart';
import '../features/itinerary/screens/itinerary_screen.dart';
import '../features/profile/screens/profile_screen.dart';
import '../features/profile/screens/settings_screen.dart';
import '../features/calendar/presentation/pages/calendar_page.dart';
import '../features/health/presentation/pages/health_page.dart';

final routerProvider = Provider<GoRouter>((ref) {
  return GoRouter(
    initialLocation: '/',
    routes: [
      // Shell route for bottom navigation
      ShellRoute(
        builder: (context, state, child) {
          return MainNavigationShell(
            state: state,
            child: child,
          );
        },
        routes: [
          // Main tabs accessible to both guest and authenticated users
          GoRoute(
            path: '/',
            name: 'home',
            builder: (context, state) => const HomeScreen(),
          ),
          GoRoute(
            path: '/calendar',
            name: 'calendar',
            builder: (context, state) => const CalendarPage(),
          ),
          GoRoute(
            path: '/health',
            name: 'health',
            builder: (context, state) => const HealthPage(),
          ),
          GoRoute(
            path: '/profile',
            name: 'profile',
            builder: (context, state) => const ProfileScreen(),
          ),
        ],
      ),
      
      // Standalone routes (outside shell navigation)
      GoRoute(
        path: '/onboarding',
        name: 'onboarding',
        builder: (context, state) => const OnboardingScreen(),
      ),
      GoRoute(
        path: '/login',
        name: 'login',
        builder: (context, state) => const LoginScreen(),
      ),
      GoRoute(
        path: '/register',
        name: 'register',
        builder: (context, state) => const RegisterScreen(),
      ),
      
      // Doctor Routes
      GoRoute(
        path: '/doctor/:doctorId',
        name: 'doctor_profile',
        builder: (context, state) {
          final doctorId = state.pathParameters['doctorId']!;
          return DoctorProfileScreen(doctorId: doctorId);
        },
      ),
      
      // Case Routes (protected - will use requireAuth)
      GoRoute(
        path: '/submit-case',
        name: 'submit_case',
        builder: (context, state) => const SubmitCaseScreen(),
      ),
      
      // Treatment Plans Routes (protected - will use requireAuth)
      GoRoute(
        path: '/plans',
        name: 'treatment_plans',
        builder: (context, state) => const TreatmentPlansScreen(),
      ),
      GoRoute(
        path: '/plans/:planId',
        name: 'plan_details',
        builder: (context, state) {
          final planId = state.pathParameters['planId']!;
          return TreatmentPlansScreen(selectedPlanId: planId);
        },
      ),
      
      // Itinerary Routes (protected - will use requireAuth)
      GoRoute(
        path: '/itinerary',
        name: 'itinerary',
        builder: (context, state) => const ItineraryScreen(),
      ),
      GoRoute(
        path: '/itinerary/:itineraryId',
        name: 'itinerary_details',
        builder: (context, state) {
          final itineraryId = state.pathParameters['itineraryId']!;
          return ItineraryScreen(itineraryId: itineraryId);
        },
      ),
      
      // Settings Route
      GoRoute(
        path: '/settings',
        name: 'settings',
        builder: (context, state) => const SettingsScreen(),
      ),
    ],
    errorBuilder: (context, state) => Scaffold(
      appBar: AppBar(title: const Text('Error')),
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const Icon(Icons.error_outline, size: 64, color: Colors.red),
            const SizedBox(height: 16),
            Text(
              'Page not found: ${state.matchedLocation}',
              style: Theme.of(context).textTheme.titleLarge,
            ),
            const SizedBox(height: 16),
            ElevatedButton(
              onPressed: () => context.go('/'),
              child: const Text('Go Home'),
            ),
          ],
        ),
      ),
    ),
  );
});

// Navigation helper methods
extension AppNavigation on BuildContext {
  void goToOnboarding() => go('/onboarding');
  void goToLogin() => go('/login');
  void goToRegister() => go('/register');
  void goToHome() => go('/');
  void goToCalendar() => go('/calendar');
  void goToHealth() => go('/health');
  void goToProfile() => go('/profile');
  void goToDoctorProfile(String doctorId) => go('/doctor/$doctorId');
  void goToSubmitCase() => go('/submit-case');
  void goToTreatmentPlans() => go('/plans');
  void goToPlanDetails(String planId) => go('/plans/$planId');
  void goToItinerary() => go('/itinerary');
  void goToItineraryDetails(String itineraryId) => go('/itinerary/$itineraryId');
  void goToSettings() => go('/settings');
}