import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../core/auth/controllers/auth_controller.dart';
import '../../../core/auth/models/user.dart';
import '../../../core/router.dart';
import '../../../core/widgets/login_bottom_sheet.dart';

class ProfileScreen extends ConsumerWidget {
  const ProfileScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final authState = ref.watch(authControllerProvider);

    return Scaffold(
      appBar: AppBar(
        title: const Text('Profile'),
        actions: [
          authState.value?.isAuthenticated == true
              ? IconButton(
                  icon: const Icon(Icons.settings),
                  onPressed: () => context.goToSettings(),
                )
              : const SizedBox.shrink(),
        ],
      ),
      body: authState.when(
        data: (state) => state.when(
          guest: () => _buildGuestView(context, ref),
          authenticated: (user) => _buildAuthenticatedView(context, ref, user),
          loading: () => const Center(child: CircularProgressIndicator()),
        ),
        loading: () => const Center(child: CircularProgressIndicator()),
        error: (error, stack) => Center(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              const Icon(Icons.error_outline, size: 64, color: Colors.red),
              const SizedBox(height: 16),
              Text('Error: $error'),
              const SizedBox(height: 16),
              ElevatedButton(
                onPressed: () => ref.invalidate(authControllerProvider),
                child: const Text('Retry'),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildGuestView(BuildContext context, WidgetRef ref) {
    return SingleChildScrollView(
      padding: const EdgeInsets.all(16),
      child: Column(
        children: [
          // Guest welcome card
          Card(
            child: Padding(
              padding: const EdgeInsets.all(20),
              child: Column(
                children: [
                  CircleAvatar(
                    radius: 50,
                    backgroundColor: Colors.grey[300],
                    child: Icon(
                      Icons.person_outline,
                      size: 48,
                      color: Colors.grey[600],
                    ),
                  ),
                  const SizedBox(height: 16),
                  Text(
                    'Welcome to Ask Dentist',
                    style: Theme.of(context).textTheme.headlineSmall?.copyWith(
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  const SizedBox(height: 8),
                  Text(
                    'Sign in to access your profile and manage your dental care',
                    style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                      color: Theme.of(context).colorScheme.onSurface.withValues(alpha: 0.7),
                    ),
                    textAlign: TextAlign.center,
                  ),
                  const SizedBox(height: 20),
                  SizedBox(
                    width: double.infinity,
                    child: ElevatedButton(
                      onPressed: () async {
                        await showLoginBottomSheet(context);
                      },
                      child: const Text('Sign In'),
                    ),
                  ),
                  const SizedBox(height: 12),
                  SizedBox(
                    width: double.infinity,
                    child: OutlinedButton(
                      onPressed: () async {
                        await showLoginBottomSheet(context);
                        // The login bottom sheet handles both login and register
                      },
                      child: const Text('Create Account'),
                    ),
                  ),
                ],
              ),
            ),
          ),
          
          const SizedBox(height: 24),
          
          // What you can do section
          Text(
            'What You Can Do',
            style: Theme.of(context).textTheme.titleLarge?.copyWith(
              fontWeight: FontWeight.bold,
            ),
          ),
          const SizedBox(height: 16),
          
          // Public menu items (available to guests)
          _GuestFeatureCard(
            icon: Icons.search,
            title: 'Browse Doctors',
            description: 'Find dentists and clinics near you',
            onTap: () {
              context.goToHome();
            },
          ),
          
          _GuestFeatureCard(
            icon: Icons.info_outline,
            title: 'Learn About Treatments',
            description: 'Discover dental procedures and treatments',
            onTap: () {
              // Navigate to treatments info
            },
          ),
          
          _GuestFeatureCard(
            icon: Icons.help_outline,
            title: 'Help & Support',
            description: 'Get help and contact support',
            onTap: () {
              // Navigate to help
            },
          ),
          
          const SizedBox(height: 24),
          
          // Locked features section
          Text(
            'Get More with an Account',
            style: Theme.of(context).textTheme.titleLarge?.copyWith(
              fontWeight: FontWeight.bold,
            ),
          ),
          const SizedBox(height: 16),
          
          _LockedFeatureCard(
            icon: Icons.calendar_today,
            title: 'Book Appointments',
            description: 'Schedule appointments with dentists',
            onLogin: () async {
              await showLoginBottomSheet(context);
            },
          ),
          
          _LockedFeatureCard(
            icon: Icons.medical_services,
            title: 'Submit Cases',
            description: 'Get treatment plans from experts',
            onLogin: () async {
              await showLoginBottomSheet(context);
            },
          ),
          
          _LockedFeatureCard(
            icon: Icons.favorite,
            title: 'Save Favorites',
            description: 'Keep track of your preferred doctors',
            onLogin: () async {
              await showLoginBottomSheet(context);
            },
          ),
          
          _LockedFeatureCard(
            icon: Icons.chat,
            title: 'Chat with Doctors',
            description: 'Direct communication with dental experts',
            onLogin: () async {
              await showLoginBottomSheet(context);
            },
          ),
          
          const SizedBox(height: 24),
          
          // Benefits card
          Container(
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              gradient: LinearGradient(
                colors: [
                  Theme.of(context).colorScheme.primary.withValues(alpha: 0.1),
                  Theme.of(context).colorScheme.secondary.withValues(alpha: 0.1),
                ],
              ),
              borderRadius: BorderRadius.circular(12),
            ),
            child: Column(
              children: [
                Icon(
                  Icons.star,
                  size: 48,
                  color: Theme.of(context).colorScheme.primary,
                ),
                const SizedBox(height: 12),
                Text(
                  'Join thousands of satisfied patients',
                  style: Theme.of(context).textTheme.titleMedium?.copyWith(
                    fontWeight: FontWeight.w600,
                  ),
                ),
                const SizedBox(height: 8),
                Text(
                  'Creating an account is free and helps you get the best dental care',
                  style: Theme.of(context).textTheme.bodyMedium,
                  textAlign: TextAlign.center,
                ),
              ],
            ),
          ),
          
          const SizedBox(height: 100), // Bottom padding for nav bar
        ],
      ),
    );
  }

  Widget _buildAuthenticatedView(BuildContext context, WidgetRef ref, User user) {
    return SingleChildScrollView(
      padding: const EdgeInsets.all(16),
      child: Column(
        children: [
          // Profile Header
          Card(
            child: Padding(
              padding: const EdgeInsets.all(20),
              child: Column(
                children: [
                  CircleAvatar(
                    radius: 50,
                    backgroundColor: Theme.of(context).colorScheme.primary,
                    child: Text(
                      user.firstName.isNotEmpty ? user.firstName[0].toUpperCase() : 'P',
                      style: const TextStyle(
                        fontSize: 32,
                        fontWeight: FontWeight.bold,
                        color: Colors.white,
                      ),
                    ),
                  ),
                  const SizedBox(height: 16),
                  Text(
                    '${user.firstName} ${user.lastName}',
                    style: Theme.of(context).textTheme.headlineSmall?.copyWith(
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  const SizedBox(height: 4),
                  Text(
                    user.email,
                    style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                      color: Theme.of(context).colorScheme.onSurface.withValues(alpha: 0.7),
                    ),
                  ),
                  if (user.phoneNumber != null) ...[
                    const SizedBox(height: 4),
                    Text(
                      user.phoneNumber!,
                      style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                        color: Theme.of(context).colorScheme.onSurface.withValues(alpha: 0.7),
                      ),
                    ),
                  ],
                ],
              ),
            ),
          ),
          
          const SizedBox(height: 24),
          
          // Menu Items
          _ProfileMenuItem(
            icon: Icons.person_outline,
            title: 'Edit Profile',
            subtitle: 'Update your personal information',
            onTap: () async {
              await ref.requireAuth(context, () async {
                // Navigate to edit profile
                ScaffoldMessenger.of(context).showSnackBar(
                  const SnackBar(content: Text('Opening profile editor...')),
                );
              });
            },
          ),
          
          _ProfileMenuItem(
            icon: Icons.medical_services_outlined,
            title: 'My Cases',
            subtitle: 'View submitted cases and status',
            onTap: () async {
              await ref.requireAuth(context, () async {
                // Navigate to my cases
                ScaffoldMessenger.of(context).showSnackBar(
                  const SnackBar(content: Text('Opening your cases...')),
                );
              });
            },
          ),
          
          _ProfileMenuItem(
            icon: Icons.schedule_outlined,
            title: 'Appointments',
            subtitle: 'Manage your appointments',
            onTap: () async {
              await ref.requireAuth(context, () async {
                context.goToItinerary();
              });
            },
          ),
          
          _ProfileMenuItem(
            icon: Icons.favorite_outline,
            title: 'Favorite Doctors',
            subtitle: 'View your saved doctors',
            onTap: () async {
              await ref.requireAuth(context, () async {
                // Navigate to favorites
                ScaffoldMessenger.of(context).showSnackBar(
                  const SnackBar(content: Text('Opening your favorites...')),
                );
              });
            },
          ),
          
          _ProfileMenuItem(
            icon: Icons.payment_outlined,
            title: 'Payment History',
            subtitle: 'View payment history and invoices',
            onTap: () async {
              await ref.requireAuth(context, () async {
                // Navigate to payment history
                ScaffoldMessenger.of(context).showSnackBar(
                  const SnackBar(content: Text('Opening payment history...')),
                );
              });
            },
          ),
          
          _ProfileMenuItem(
            icon: Icons.help_outline,
            title: 'Help & Support',
            subtitle: 'Get help and contact support',
            onTap: () {
              // Navigate to help (public)
              ScaffoldMessenger.of(context).showSnackBar(
                const SnackBar(content: Text('Opening help center...')),
              );
            },
          ),
          
          _ProfileMenuItem(
            icon: Icons.settings_outlined,
            title: 'Settings',
            subtitle: 'App preferences and settings',
            onTap: () => context.goToSettings(),
          ),
          
          const SizedBox(height: 24),
          
          // Logout Button
          SizedBox(
            width: double.infinity,
            child: ElevatedButton.icon(
              onPressed: () async {
                final confirmed = await showDialog<bool>(
                  context: context,
                  builder: (context) => AlertDialog(
                    title: const Text('Logout'),
                    content: const Text('Are you sure you want to logout?'),
                    actions: [
                      TextButton(
                        onPressed: () => Navigator.of(context).pop(false),
                        child: const Text('Cancel'),
                      ),
                      ElevatedButton(
                        onPressed: () => Navigator.of(context).pop(true),
                        child: const Text('Logout'),
                      ),
                    ],
                  ),
                );
                
                if (confirmed == true) {
                  await ref.read(authControllerProvider.notifier).logout();
                }
              },
              icon: const Icon(Icons.logout),
              label: const Text('Logout'),
              style: ElevatedButton.styleFrom(
                backgroundColor: Colors.red,
                foregroundColor: Colors.white,
                minimumSize: const Size(double.infinity, 48),
              ),
            ),
          ),
          
          const SizedBox(height: 100), // Bottom padding for nav bar
        ],
      ),
    );
  }
}

class _GuestFeatureCard extends StatelessWidget {
  final IconData icon;
  final String title;
  final String description;
  final VoidCallback onTap;

  const _GuestFeatureCard({
    required this.icon,
    required this.title,
    required this.description,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.only(bottom: 8),
      child: ListTile(
        leading: Container(
          width: 48,
          height: 48,
          decoration: BoxDecoration(
            color: Theme.of(context).colorScheme.primary.withValues(alpha: 0.1),
            borderRadius: BorderRadius.circular(12),
          ),
          child: Icon(
            icon,
            color: Theme.of(context).colorScheme.primary,
          ),
        ),
        title: Text(
          title,
          style: Theme.of(context).textTheme.titleMedium?.copyWith(
            fontWeight: FontWeight.w600,
          ),
        ),
        subtitle: Text(description),
        trailing: const Icon(Icons.chevron_right),
        onTap: onTap,
      ),
    );
  }
}

class _LockedFeatureCard extends StatelessWidget {
  final IconData icon;
  final String title;
  final String description;
  final VoidCallback onLogin;

  const _LockedFeatureCard({
    required this.icon,
    required this.title,
    required this.description,
    required this.onLogin,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.only(bottom: 8),
      child: ListTile(
        leading: Container(
          width: 48,
          height: 48,
          decoration: BoxDecoration(
            color: Colors.grey.withValues(alpha: 0.1),
            borderRadius: BorderRadius.circular(12),
          ),
          child: Stack(
            children: [
              Center(
                child: Icon(
                  icon,
                  color: Colors.grey[400],
                ),
              ),
              Positioned(
                bottom: 2,
                right: 2,
                child: Container(
                  width: 16,
                  height: 16,
                  decoration: BoxDecoration(
                    color: Colors.orange,
                    borderRadius: BorderRadius.circular(8),
                  ),
                  child: const Icon(
                    Icons.lock,
                    size: 12,
                    color: Colors.white,
                  ),
                ),
              ),
            ],
          ),
        ),
        title: Text(
          title,
          style: Theme.of(context).textTheme.titleMedium?.copyWith(
            fontWeight: FontWeight.w600,
            color: Colors.grey[600],
          ),
        ),
        subtitle: Text(
          description,
          style: TextStyle(color: Colors.grey[500]),
        ),
        trailing: TextButton(
          onPressed: onLogin,
          child: const Text('Login'),
        ),
      ),
    );
  }
}

class _ProfileMenuItem extends StatelessWidget {
  final IconData icon;
  final String title;
  final String subtitle;
  final VoidCallback onTap;

  const _ProfileMenuItem({
    required this.icon,
    required this.title,
    required this.subtitle,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.only(bottom: 8),
      child: ListTile(
        leading: Container(
          width: 48,
          height: 48,
          decoration: BoxDecoration(
            color: Theme.of(context).colorScheme.primary.withValues(alpha: 0.1),
            borderRadius: BorderRadius.circular(12),
          ),
          child: Icon(
            icon,
            color: Theme.of(context).colorScheme.primary,
          ),
        ),
        title: Text(
          title,
          style: Theme.of(context).textTheme.titleMedium?.copyWith(
            fontWeight: FontWeight.w600,
          ),
        ),
        subtitle: Text(subtitle),
        trailing: const Icon(Icons.chevron_right),
        onTap: onTap,
      ),
    );
  }
}