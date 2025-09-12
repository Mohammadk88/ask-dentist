import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';

/// Main navigation shell that provides bottom navigation for the entire app
class MainNavigationShell extends ConsumerStatefulWidget {
  final Widget child;
  final GoRouterState state;

  const MainNavigationShell({
    super.key,
    required this.child,
    required this.state,
  });

  @override
  ConsumerState<MainNavigationShell> createState() => _MainNavigationShellState();
}

class _MainNavigationShellState extends ConsumerState<MainNavigationShell> {
  @override
  Widget build(BuildContext context) {
    final currentIndex = _calculateSelectedIndex(widget.state.uri.path);

    return Scaffold(
      body: widget.child,
      bottomNavigationBar: BottomNavigationBar(
        type: BottomNavigationBarType.fixed,
        currentIndex: currentIndex,
        onTap: (index) => _onTabTapped(context, index),
        selectedItemColor: Theme.of(context).colorScheme.primary,
        unselectedItemColor: Theme.of(context).colorScheme.onSurface.withValues(alpha: 0.6),
        backgroundColor: Theme.of(context).colorScheme.surface,
        elevation: 8,
        items: const [
          BottomNavigationBarItem(
            icon: Icon(Icons.home_outlined),
            activeIcon: Icon(Icons.home),
            label: 'Home',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.calendar_today_outlined),
            activeIcon: Icon(Icons.calendar_today),
            label: 'Calendar',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.favorite_outline),
            activeIcon: Icon(Icons.favorite),
            label: 'My Health',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.person_outlined),
            activeIcon: Icon(Icons.person),
            label: 'Profile',
          ),
        ],
      ),
    );
  }

  /// Calculate which tab should be selected based on current route
  int _calculateSelectedIndex(String path) {
    if (path.startsWith('/calendar')) return 1;
    if (path.startsWith('/health')) return 2;
    if (path.startsWith('/profile')) return 3;
    return 0; // Default to Home
  }

  /// Handle tab taps with guest/authenticated logic
  void _onTabTapped(BuildContext context, int index) {
    switch (index) {
      case 0:
        context.go('/');
        break;
      case 1:
        _navigateToCalendar(context);
        break;
      case 2:
        _navigateToHealth(context);
        break;
      case 3:
        _navigateToProfile(context);
        break;
    }
  }

  /// Navigate to calendar - public access with limited guest view
  void _navigateToCalendar(BuildContext context) {
    context.go('/calendar');
  }

  /// Navigate to health - public access with limited guest view
  void _navigateToHealth(BuildContext context) {
    context.go('/health');
  }

  /// Navigate to profile - shows login options for guests
  void _navigateToProfile(BuildContext context) {
    context.go('/profile');
  }
}

/// Extension to easily get current tab index from any widget
extension BottomNavigationHelper on BuildContext {
  int get currentBottomNavIndex {
    final path = GoRouterState.of(this).uri.path;
    if (path.startsWith('/calendar')) return 1;
    if (path.startsWith('/health')) return 2;
    if (path.startsWith('/profile')) return 3;
    return 0; // Home
  }
}