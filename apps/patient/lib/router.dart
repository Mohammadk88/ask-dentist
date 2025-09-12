import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';

import 'ui/widgets/nav/main_nav_bar.dart';
import 'features/home/presentation/pages/home_page.dart';
import 'features/calendar/presentation/pages/calendar_page.dart';
import 'features/health/presentation/pages/health_page.dart';
import 'features/profile/presentation/pages/profile_page.dart';
import 'features/chat/screens/chat_screen.dart';

// Navigation state provider for persistent tab selection
final navigationIndexProvider = StateProvider<int>((ref) => 0);

// Global navigation key for programmatic navigation
final GlobalKey<NavigatorState> _rootNavigatorKey = GlobalKey<NavigatorState>();
final GlobalKey<NavigatorState> _shellNavigatorKey = GlobalKey<NavigatorState>();

// Router provider with state restoration
final routerProvider = Provider<GoRouter>((ref) {
  return GoRouter(
    navigatorKey: _rootNavigatorKey,
    initialLocation: '/home',
    restorationScopeId: 'router',
    
    routes: [
      // Shell route for persistent bottom navigation
      ShellRoute(
        navigatorKey: _shellNavigatorKey,
        builder: (context, state, child) {
          return MainScaffold(child: child);
        },
        routes: [
          // Home Tab Routes
          GoRoute(
            path: '/home',
            name: 'home',
            pageBuilder: (context, state) => const NoTransitionPage(
              child: HomePage(),
            ),
            routes: [
              GoRoute(
                path: '/appointment/:id',
                name: 'appointment-detail',
                pageBuilder: (context, state) {
                  final appointmentId = state.pathParameters['id']!;
                  return NoTransitionPage(
                    child: AppointmentDetailPage(appointmentId: appointmentId),
                  );
                },
              ),
              GoRoute(
                path: '/dentist/:id',
                name: 'dentist-detail',
                pageBuilder: (context, state) {
                  final dentistId = state.pathParameters['id']!;
                  return NoTransitionPage(
                    child: DentistDetailPage(dentistId: dentistId),
                  );
                },
              ),
            ],
          ),
          
          // Calendar Tab Routes
          GoRoute(
            path: '/calendar',
            name: 'calendar',
            pageBuilder: (context, state) => const NoTransitionPage(
              child: CalendarPage(),
            ),
            routes: [
              GoRoute(
                path: '/booking',
                name: 'booking',
                pageBuilder: (context, state) => const NoTransitionPage(
                  child: BookingPage(),
                ),
              ),
            ],
          ),
          
          // My Health Tab Routes
          GoRoute(
            path: '/health',
            name: 'health',
            pageBuilder: (context, state) => const NoTransitionPage(
              child: HealthPage(),
            ),
            routes: [
              GoRoute(
                path: '/treatment/:id',
                name: 'treatment-detail',
                pageBuilder: (context, state) {
                  final treatmentId = state.pathParameters['id']!;
                  return NoTransitionPage(
                    child: TreatmentDetailPage(treatmentId: treatmentId),
                  );
                },
              ),
              GoRoute(
                path: '/health-records',
                name: 'health-records',
                pageBuilder: (context, state) => const NoTransitionPage(
                  child: HealthRecordsPage(),
                ),
              ),
            ],
          ),
          
          // Profile Tab Routes
          GoRoute(
            path: '/profile',
            name: 'profile',
            pageBuilder: (context, state) => const NoTransitionPage(
              child: ProfilePage(),
            ),
            routes: [
              GoRoute(
                path: '/settings',
                name: 'settings',
                pageBuilder: (context, state) => const NoTransitionPage(
                  child: SettingsPage(),
                ),
              ),
              GoRoute(
                path: '/edit-profile',
                name: 'edit-profile',
                pageBuilder: (context, state) => const NoTransitionPage(
                  child: EditProfilePage(),
                ),
              ),
            ],
          ),
        ],
      ),
      
      // Full-screen routes (outside shell)
      GoRoute(
        parentNavigatorKey: _rootNavigatorKey,
        path: '/chat/:doctorId',
        name: 'chat',
        pageBuilder: (context, state) {
          final doctorId = state.pathParameters['doctorId']!;
          final doctorName = state.uri.queryParameters['doctorName'];
          return NoTransitionPage(
            child: ChatScreen(
              doctorId: doctorId,
              doctorName: doctorName,
            ),
          );
        },
      ),
      
      GoRoute(
        parentNavigatorKey: _rootNavigatorKey,
        path: '/auth/login',
        name: 'login',
        pageBuilder: (context, state) => const NoTransitionPage(
          child: LoginPage(),
        ),
      ),
      
      GoRoute(
        parentNavigatorKey: _rootNavigatorKey,
        path: '/auth/register',
        name: 'register',
        pageBuilder: (context, state) => const NoTransitionPage(
          child: RegisterPage(),
        ),
      ),
      
      GoRoute(
        parentNavigatorKey: _rootNavigatorKey,
        path: '/onboarding',
        name: 'onboarding',
        pageBuilder: (context, state) => const NoTransitionPage(
          child: OnboardingPage(),
        ),
      ),
    ],
    
    // Error handling
    errorPageBuilder: (context, state) => NoTransitionPage(
      child: ErrorPage(error: state.error.toString()),
    ),
    
    // Redirect logic (e.g., auth guards)
    redirect: (context, state) {
      // Add authentication logic here if needed
      return null;
    },
  );
});

// Main scaffold with persistent bottom navigation
class MainScaffold extends ConsumerWidget {
  final Widget child;

  const MainScaffold({
    super.key,
    required this.child,
  });

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    return Scaffold(
      body: child,
      bottomNavigationBar: const MainNavBar(),
    );
  }
}

// Placeholder pages for the detail routes
class AppointmentDetailPage extends StatelessWidget {
  final String appointmentId;

  const AppointmentDetailPage({
    super.key,
    required this.appointmentId,
  });

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Appointment Details'),
      ),
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Text('Appointment ID: $appointmentId'),
            const SizedBox(height: 16),
            ElevatedButton(
              onPressed: () => context.pop(),
              child: const Text('Go Back'),
            ),
          ],
        ),
      ),
    );
  }
}

class DentistDetailPage extends StatelessWidget {
  final String dentistId;

  const DentistDetailPage({
    super.key,
    required this.dentistId,
  });

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Dentist Profile'),
      ),
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Text('Dentist ID: $dentistId'),
            const SizedBox(height: 16),
            ElevatedButton(
              onPressed: () => context.pop(),
              child: const Text('Go Back'),
            ),
          ],
        ),
      ),
    );
  }
}

class BookingPage extends StatelessWidget {
  const BookingPage({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Book Appointment'),
      ),
      body: const Center(
        child: Text('Booking Page'),
      ),
    );
  }
}

class TreatmentDetailPage extends StatelessWidget {
  final String treatmentId;

  const TreatmentDetailPage({
    super.key,
    required this.treatmentId,
  });

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Treatment Details'),
      ),
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Text('Treatment ID: $treatmentId'),
            const SizedBox(height: 16),
            ElevatedButton(
              onPressed: () => context.pop(),
              child: const Text('Go Back'),
            ),
          ],
        ),
      ),
    );
  }
}

class HealthRecordsPage extends StatelessWidget {
  const HealthRecordsPage({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Health Records'),
      ),
      body: const Center(
        child: Text('Health Records Page'),
      ),
    );
  }
}

class SettingsPage extends StatelessWidget {
  const SettingsPage({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Settings'),
      ),
      body: const Center(
        child: Text('Settings Page'),
      ),
    );
  }
}

class EditProfilePage extends StatelessWidget {
  const EditProfilePage({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Edit Profile'),
      ),
      body: const Center(
        child: Text('Edit Profile Page'),
      ),
    );
  }
}

class LoginPage extends StatelessWidget {
  const LoginPage({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Login'),
      ),
      body: const Center(
        child: Text('Login Page'),
      ),
    );
  }
}

class RegisterPage extends StatelessWidget {
  const RegisterPage({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Register'),
      ),
      body: const Center(
        child: Text('Register Page'),
      ),
    );
  }
}

class OnboardingPage extends StatelessWidget {
  const OnboardingPage({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Onboarding'),
      ),
      body: const Center(
        child: Text('Onboarding Page'),
      ),
    );
  }
}

class ErrorPage extends StatelessWidget {
  final String error;

  const ErrorPage({
    super.key,
    required this.error,
  });

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Error'),
      ),
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const Icon(
              Icons.error_outline,
              size: 64,
              color: Colors.red,
            ),
            const SizedBox(height: 16),
            Text('Error: $error'),
            const SizedBox(height: 16),
            ElevatedButton(
              onPressed: () => context.go('/home'),
              child: const Text('Go Home'),
            ),
          ],
        ),
      ),
    );
  }
}