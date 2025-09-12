import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';

import '../../../router.dart';

/// Navigation tab definition
class NavTab {
  final String label;
  final IconData icon;
  final IconData selectedIcon;
  final String route;

  const NavTab({
    required this.label,
    required this.icon,
    required this.selectedIcon,
    required this.route,
  });
}

/// Main navigation bar with 4 tabs
class MainNavBar extends ConsumerWidget {
  const MainNavBar({super.key});

  // Define the 4 navigation tabs
  static const List<NavTab> _tabs = [
    NavTab(
      label: 'Home',
      icon: Icons.home_outlined,
      selectedIcon: Icons.home,
      route: '/home',
    ),
    NavTab(
      label: 'Calendar',
      icon: Icons.calendar_today_outlined,
      selectedIcon: Icons.calendar_today,
      route: '/calendar',
    ),
    NavTab(
      label: 'My Health',
      icon: Icons.favorite_outline,
      selectedIcon: Icons.favorite,
      route: '/health',
    ),
    NavTab(
      label: 'Profile',
      icon: Icons.person_outline,
      selectedIcon: Icons.person,
      route: '/profile',
    ),
  ];

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final currentIndex = ref.watch(navigationIndexProvider);
    final currentLocation = GoRouterState.of(context).uri.path;

    // Update navigation index based on current route
    WidgetsBinding.instance.addPostFrameCallback((_) {
      final newIndex = _getIndexFromRoute(currentLocation);
      if (newIndex != -1 && newIndex != currentIndex) {
        ref.read(navigationIndexProvider.notifier).state = newIndex;
      }
    });

    return Container(
      decoration: BoxDecoration(
        color: Theme.of(context).bottomNavigationBarTheme.backgroundColor,
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(alpha: 0.1),
            blurRadius: 10,
            offset: const Offset(0, -2),
          ),
        ],
      ),
      child: SafeArea(
        child: Padding(
          padding: const EdgeInsets.symmetric(vertical: 8),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceAround,
            children: _tabs.asMap().entries.map((entry) {
              final index = entry.key;
              final tab = entry.value;
              final isSelected = index == currentIndex;

              return _NavBarItem(
                tab: tab,
                isSelected: isSelected,
                onTap: () => _onTabTapped(context, ref, index, tab.route),
              );
            }).toList(),
          ),
        ),
      ),
    );
  }

  /// Handle tab selection and navigation
  void _onTabTapped(BuildContext context, WidgetRef ref, int index, String route) {
    // Update selected tab index
    ref.read(navigationIndexProvider.notifier).state = index;
    
    // Navigate to the selected tab
    if (GoRouterState.of(context).uri.path != route) {
      context.go(route);
    }
  }

  /// Get navigation index from current route
  int _getIndexFromRoute(String route) {
    for (int i = 0; i < _tabs.length; i++) {
      if (route.startsWith(_tabs[i].route)) {
        return i;
      }
    }
    return -1;
  }
}

/// Individual navigation bar item
class _NavBarItem extends StatelessWidget {
  final NavTab tab;
  final bool isSelected;
  final VoidCallback onTap;

  const _NavBarItem({
    required this.tab,
    required this.isSelected,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final colorScheme = theme.colorScheme;
    
    final selectedColor = theme.bottomNavigationBarTheme.selectedItemColor ?? 
                         colorScheme.primary;
    final unselectedColor = theme.bottomNavigationBarTheme.unselectedItemColor ?? 
                           colorScheme.onSurface.withValues(alpha: 0.6);

    return Expanded(
      child: Material(
        color: Colors.transparent,
        child: InkWell(
          onTap: onTap,
          borderRadius: BorderRadius.circular(12),
          child: Padding(
            padding: const EdgeInsets.symmetric(vertical: 8, horizontal: 4),
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                // Icon with animation
                AnimatedSwitcher(
                  duration: const Duration(milliseconds: 200),
                  child: Icon(
                    isSelected ? tab.selectedIcon : tab.icon,
                    key: ValueKey(isSelected),
                    color: isSelected ? selectedColor : unselectedColor,
                    size: 24,
                  ),
                ),
                
                const SizedBox(height: 4),
                
                // Label
                AnimatedDefaultTextStyle(
                  duration: const Duration(milliseconds: 200),
                  style: TextStyle(
                    fontSize: 12,
                    fontWeight: isSelected ? FontWeight.w600 : FontWeight.w400,
                    color: isSelected ? selectedColor : unselectedColor,
                  ),
                  child: Text(
                    tab.label,
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                  ),
                ),
                
                // Selection indicator
                const SizedBox(height: 4),
                AnimatedContainer(
                  duration: const Duration(milliseconds: 200),
                  height: 2,
                  width: isSelected ? 20 : 0,
                  decoration: BoxDecoration(
                    color: selectedColor,
                    borderRadius: BorderRadius.circular(1),
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}