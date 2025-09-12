import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../auth/controllers/auth_event_controller.dart';
import '../auth/models/auth_event.dart';
import 'login_bottom_sheet.dart';

class AuthEventListener extends ConsumerWidget {
  final Widget child;

  const AuthEventListener({
    super.key,
    required this.child,
  });

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    // Listen to auth events
    ref.listen<AuthEvent?>(
      authEventControllerProvider,
      (previous, next) async {
        if (next != null) {
          await _handleAuthEvent(context, ref, next);
          // Clear the event after handling
          ref.read(authEventControllerProvider.notifier).clearEvent();
        }
      },
    );

    return child;
  }

  Future<void> _handleAuthEvent(
    BuildContext context, 
    WidgetRef ref, 
    AuthEvent event,
  ) async {
    await event.when(
      showLoginSheet: (message) async {
        // Show a snackbar with the message if provided
        if (message != null && message.isNotEmpty) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              content: Text(message),
              backgroundColor: Colors.orange,
              duration: const Duration(seconds: 3),
            ),
          );
          
          // Small delay to let snackbar show before modal
          await Future.delayed(const Duration(milliseconds: 500));
        }
        
        // Show login bottom sheet
        final result = await showLoginBottomSheet(context);
        
        if (result == true) {
          // Successfully authenticated
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(
              content: Text('Successfully signed in!'),
              backgroundColor: Colors.green,
              duration: Duration(seconds: 2),
            ),
          );
        }
      },
      hideLoginSheet: () async {
        // If there's an active bottom sheet, close it
        Navigator.of(context).popUntil((route) => !route.isFirst);
      },
      refreshHome: () async {
        // Trigger home data refresh
        // This could invalidate home-related providers
        _refreshHomeData(ref);
      },
    );
  }

  void _refreshHomeData(WidgetRef ref) {
    // Invalidate providers that need to be refreshed after login
    // This will trigger re-fetching of favorites and other user-specific data
    
    // Example: If you have home providers, invalidate them here
    // ref.invalidate(homeDataProvider);
    // ref.invalidate(favoritesProvider);
    // ref.invalidate(userProfileProvider);
    
    // For now, just log the refresh action
    debugPrint('Refreshing home data after successful authentication');
  }
}