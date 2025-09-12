import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter_localizations/flutter_localizations.dart';
import 'core/env/env_loader.dart';
import 'core/net/network_guard.dart';
import 'core/router.dart';
import 'core/widgets/performance_debug_overlay.dart';
import 'core/widgets/auth_event_listener.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();

  // Load environment configuration
  await EnvLoader.loadEnvConfig();

  // Initialize networking
  NetworkGuard.initialize();

  // Test API health on startup
  _testApiHealth();

  runApp(const ProviderScope(child: PatientApp()));
}

Future<void> _testApiHealth() async {
  try {
    final result = await NetworkGuard.testHealthEndpoint();
    print('🏥 API Health Check: ${result['success'] ? 'PASS' : 'FAIL'}');
    if (result['success']) {
      print('📊 Latency: ${result['latency']}ms');
    } else {
      print('❌ Error: ${result['error']}');
    }
  } catch (e) {
    print('🔥 Health check failed: $e');
  }
}

class PatientApp extends ConsumerWidget {
  const PatientApp({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final router = ref.watch(routerProvider);

    return MaterialApp.router(
      title: 'Ask Dentist Patient',
      debugShowCheckedModeBanner: false,
      routerConfig: router,

      // Performance monitoring in debug mode
      builder: (context, child) {
        // Wrap with AuthEventListener to handle global auth events
        Widget wrappedChild = AuthEventListener(child: child!);

        if (kDebugMode) {
          return PerformanceDebugOverlay(
            child: wrappedChild,
          );
        }
        return wrappedChild;
      },

      // Localization
      localizationsDelegates: const [
        GlobalMaterialLocalizations.delegate,
        GlobalWidgetsLocalizations.delegate,
        GlobalCupertinoLocalizations.delegate,
      ],
      supportedLocales: const [
        Locale('en', ''),
        Locale('ar', ''),
        Locale('tr', ''),
      ],

      // Theme
      theme: ThemeData(
        useMaterial3: true,
        colorScheme: ColorScheme.fromSeed(
          seedColor: const Color(0xFF2E7D9A), // Medical blue
          brightness: Brightness.light,
        ),
        appBarTheme: const AppBarTheme(
          centerTitle: true,
          elevation: 0,
        ),
        cardTheme: CardTheme(
          elevation: 2,
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(12),
          ),
        ),
        elevatedButtonTheme: ElevatedButtonThemeData(
          style: ElevatedButton.styleFrom(
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(8),
            ),
            padding: const EdgeInsets.symmetric(
              horizontal: 24,
              vertical: 12,
            ),
          ),
        ),
        inputDecorationTheme: InputDecorationTheme(
          border: OutlineInputBorder(
            borderRadius: BorderRadius.circular(8),
          ),
          contentPadding: const EdgeInsets.symmetric(
            horizontal: 16,
            vertical: 12,
          ),
        ),
      ),

      darkTheme: ThemeData(
        useMaterial3: true,
        colorScheme: ColorScheme.fromSeed(
          seedColor: const Color(0xFF2E7D9A),
          brightness: Brightness.dark,
        ),
        appBarTheme: const AppBarTheme(
          centerTitle: true,
          elevation: 0,
        ),
      ),
    );
  }
}
