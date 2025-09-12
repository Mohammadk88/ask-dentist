import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';

class ProfilePage extends ConsumerWidget {
  const ProfilePage({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Profile'),
        actions: [
          IconButton(
            icon: const Icon(Icons.edit),
            onPressed: () => context.go('/profile/edit'),
          ),
        ],
      ),
      body: SingleChildScrollView(
        child: Column(
          children: [
            // Profile Header
            Container(
              width: double.infinity,
              padding: const EdgeInsets.all(24),
              decoration: BoxDecoration(
                color: Theme.of(context).colorScheme.primary.withValues(alpha: 0.1),
              ),
              child: Column(
                children: [
                  CircleAvatar(
                    radius: 50,
                    backgroundColor: Theme.of(context).colorScheme.primary,
                    child: const Icon(
                      Icons.person,
                      size: 50,
                      color: Colors.white,
                    ),
                  ),
                  const SizedBox(height: 16),
                  Text(
                    'John Doe',
                    style: Theme.of(context).textTheme.headlineSmall?.copyWith(
                      fontWeight: FontWeight.w600,
                    ),
                  ),
                  const SizedBox(height: 4),
                  Text(
                    'john.doe@example.com',
                    style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                      color: Colors.grey[600],
                    ),
                  ),
                  const SizedBox(height: 8),
                  Chip(
                    label: const Text('Premium Member'),
                    backgroundColor: Theme.of(context).colorScheme.primary.withValues(alpha: 0.2),
                    labelStyle: TextStyle(
                      color: Theme.of(context).colorScheme.primary,
                      fontWeight: FontWeight.w600,
                    ),
                  ),
                ],
              ),
            ),
            
            // Profile Options
            Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                children: [
                  // Personal Information Section
                  _SectionTitle(title: 'Personal Information'),
                  _ProfileOption(
                    icon: Icons.person_outline,
                    title: 'Personal Details',
                    subtitle: 'Name, phone, address',
                    onTap: () => context.go('/profile/personal-details'),
                  ),
                  _ProfileOption(
                    icon: Icons.medical_information_outlined,
                    title: 'Medical History',
                    subtitle: 'Allergies, conditions, medications',
                    onTap: () => context.go('/profile/medical-history'),
                  ),
                  _ProfileOption(
                    icon: Icons.family_restroom,
                    title: 'Emergency Contacts',
                    subtitle: 'Family and emergency contacts',
                    onTap: () => context.go('/profile/emergency-contacts'),
                  ),
                  
                  const SizedBox(height: 24),
                  
                  // Preferences Section
                  _SectionTitle(title: 'Preferences'),
                  _ProfileOption(
                    icon: Icons.notifications_outlined,
                    title: 'Notifications',
                    subtitle: 'Appointment reminders, updates',
                    onTap: () => context.go('/profile/notifications'),
                  ),
                  _ProfileOption(
                    icon: Icons.language,
                    title: 'Language',
                    subtitle: 'English',
                    onTap: () => context.go('/profile/language'),
                  ),
                  _ProfileOption(
                    icon: Icons.location_on_outlined,
                    title: 'Location Preferences',
                    subtitle: 'Preferred clinics and areas',
                    onTap: () => context.go('/profile/location-preferences'),
                  ),
                  
                  const SizedBox(height: 24),
                  
                  // Account Section
                  _SectionTitle(title: 'Account'),
                  _ProfileOption(
                    icon: Icons.payment,
                    title: 'Payment Methods',
                    subtitle: 'Cards and payment options',
                    onTap: () => context.go('/profile/payment-methods'),
                  ),
                  _ProfileOption(
                    icon: Icons.history,
                    title: 'Billing History',
                    subtitle: 'Past payments and invoices',
                    onTap: () => context.go('/profile/billing-history'),
                  ),
                  _ProfileOption(
                    icon: Icons.security,
                    title: 'Privacy & Security',
                    subtitle: 'Password, biometrics, data',
                    onTap: () => context.go('/profile/privacy-security'),
                  ),
                  
                  const SizedBox(height: 24),
                  
                  // Support Section
                  _SectionTitle(title: 'Support'),
                  _ProfileOption(
                    icon: Icons.help_outline,
                    title: 'Help & FAQ',
                    subtitle: 'Common questions and support',
                    onTap: () => context.go('/profile/help'),
                  ),
                  _ProfileOption(
                    icon: Icons.feedback_outlined,
                    title: 'Send Feedback',
                    subtitle: 'Help us improve the app',
                    onTap: () => context.go('/profile/feedback'),
                  ),
                  _ProfileOption(
                    icon: Icons.info_outline,
                    title: 'About',
                    subtitle: 'App version and information',
                    onTap: () => context.go('/profile/about'),
                  ),
                  
                  const SizedBox(height: 32),
                  
                  // Logout Button
                  SizedBox(
                    width: double.infinity,
                    child: ElevatedButton(
                      onPressed: () {
                        _showLogoutDialog(context);
                      },
                      style: ElevatedButton.styleFrom(
                        backgroundColor: Colors.red,
                        foregroundColor: Colors.white,
                        padding: const EdgeInsets.symmetric(vertical: 16),
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(16),
                        ),
                      ),
                      child: const Text(
                        'Logout',
                        style: TextStyle(
                          fontSize: 16,
                          fontWeight: FontWeight.w600,
                        ),
                      ),
                    ),
                  ),
                  
                  const SizedBox(height: 100), // Bottom padding for nav bar
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  void _showLogoutDialog(BuildContext context) {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Logout'),
        content: const Text('Are you sure you want to logout?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.of(context).pop(),
            child: const Text('Cancel'),
          ),
          TextButton(
            onPressed: () {
              Navigator.of(context).pop();
              // Handle logout logic here
              // For now, we'll just show a snackbar
              ScaffoldMessenger.of(context).showSnackBar(
                const SnackBar(
                  content: Text('Logged out successfully'),
                  behavior: SnackBarBehavior.floating,
                ),
              );
            },
            style: TextButton.styleFrom(
              foregroundColor: Colors.red,
            ),
            child: const Text('Logout'),
          ),
        ],
      ),
    );
  }
}

class _SectionTitle extends StatelessWidget {
  final String title;

  const _SectionTitle({required this.title});

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 16),
      child: Align(
        alignment: Alignment.centerLeft,
        child: Text(
          title,
          style: Theme.of(context).textTheme.titleMedium?.copyWith(
            fontWeight: FontWeight.w600,
            color: Theme.of(context).colorScheme.primary,
          ),
        ),
      ),
    );
  }
}

class _ProfileOption extends StatelessWidget {
  final IconData icon;
  final String title;
  final String subtitle;
  final VoidCallback onTap;

  const _ProfileOption({
    required this.icon,
    required this.title,
    required this.subtitle,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.only(bottom: 8),
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(16),
        child: Padding(
          padding: const EdgeInsets.all(16),
          child: Row(
            children: [
              Container(
                padding: const EdgeInsets.all(12),
                decoration: BoxDecoration(
                  color: Theme.of(context).colorScheme.primary.withValues(alpha: 0.1),
                  borderRadius: BorderRadius.circular(12),
                ),
                child: Icon(
                  icon,
                  color: Theme.of(context).colorScheme.primary,
                  size: 20,
                ),
              ),
              const SizedBox(width: 16),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      title,
                      style: Theme.of(context).textTheme.titleMedium,
                    ),
                    const SizedBox(height: 4),
                    Text(
                      subtitle,
                      style: Theme.of(context).textTheme.bodySmall?.copyWith(
                        color: Colors.grey[600],
                      ),
                    ),
                  ],
                ),
              ),
              const Icon(
                Icons.chevron_right,
                color: Colors.grey,
              ),
            ],
          ),
        ),
      ),
    );
  }
}