import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../../core/auth/controllers/auth_controller.dart';
import '../../../../core/widgets/guest_placeholders.dart';

class HealthPage extends ConsumerWidget {
  const HealthPage({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final authState = ref.watch(authControllerProvider);

    return Scaffold(
      appBar: AppBar(
        title: const Text('My Health'),
        actions: [
          authState.value?.isAuthenticated == true
              ? IconButton(
                  icon: const Icon(Icons.add),
                  onPressed: () async {
                    await ref.requireAuth(context, () async {
                      // Add new health record
                      ScaffoldMessenger.of(context).showSnackBar(
                        const SnackBar(content: Text('Opening health record form...')),
                      );
                    });
                  },
                )
              : const SizedBox.shrink(),
        ],
      ),
      body: authState.when(
        data: (state) => state.when(
          guest: () => _buildGuestView(context, ref),
          authenticated: (user) => _buildAuthenticatedView(context, ref, user.firstName),
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
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            // Guest info card using reusable placeholder
            GuestPlaceholder(
              title: 'Track Your Dental Health',
              description: 'Login to view your treatment history, prescriptions, and health reminders',
              icon: Icons.favorite_outline,
            ),
            
            const SizedBox(height: 24),
            
            // Preview content for guests using reusable cards
            Text(
              'What You Can Track',
              style: Theme.of(context).textTheme.titleLarge,
            ),
            const SizedBox(height: 16),
            
            GuestFeatureCard(
              icon: Icons.medical_services,
              title: 'Treatment History',
              description: 'View all your past dental treatments and procedures',
              isLocked: true,
            ),
            
            GuestFeatureCard(
              icon: Icons.medication,
              title: 'Prescriptions',
              description: 'Track your medications and dosage schedules',
              isLocked: true,
            ),
            
            GuestFeatureCard(
              icon: Icons.notifications,
              title: 'Health Reminders',
              description: 'Get notified about upcoming checkups and treatments',
              isLocked: true,
            ),
            
            GuestFeatureCard(
              icon: Icons.trending_up,
              title: 'Health Score',
              description: 'Monitor your overall dental health progress',
              isLocked: true,
            ),
            
            const SizedBox(height: 24),
            
            // Benefits banner using reusable component
            SignUpBenefitsBanner(
              benefits: [
                'Track your complete dental history',
                'Get personalized health reminders',
                'Access your prescriptions anytime',
                'Monitor your health progress',
              ],
            ),
            
            const SizedBox(height: 100), // Bottom padding for nav bar
          ],
        ),
      ),
    );
  }

  Widget _buildAuthenticatedView(BuildContext context, WidgetRef ref, String userName) {
    return SingleChildScrollView(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Health Summary Card
            Card(
              child: Padding(
                padding: const EdgeInsets.all(20),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      'Welcome back, $userName!',
                      style: Theme.of(context).textTheme.titleLarge,
                    ),
                    const SizedBox(height: 16),
                    Row(
                      children: [
                        _HealthMetric(
                          icon: Icons.calendar_today,
                          label: 'Last Visit',
                          value: '2 weeks ago',
                        ),
                        const SizedBox(width: 24),
                        _HealthMetric(
                          icon: Icons.trending_up,
                          label: 'Health Score',
                          value: '92%',
                          valueColor: Colors.green,
                        ),
                      ],
                    ),
                  ],
                ),
              ),
            ),
            
            const SizedBox(height: 24),
            
            // Treatment History
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text(
                  'Treatment History',
                  style: Theme.of(context).textTheme.titleLarge,
                ),
                TextButton(
                  onPressed: () async {
                    await ref.requireAuth(context, () async {
                      // Navigate to full health records
                      ScaffoldMessenger.of(context).showSnackBar(
                        const SnackBar(content: Text('Opening complete health records...')),
                      );
                    });
                  },
                  child: const Text('View All'),
                ),
              ],
            ),
            const SizedBox(height: 16),
            
            _TreatmentCard(
              treatment: 'Dental Cleaning',
              date: 'Aug 25, 2025',
              dentist: 'Dr. Sarah Johnson',
              status: 'Completed',
              onTap: () async {
                await ref.requireAuth(context, () async {
                  // Navigate to treatment details
                  ScaffoldMessenger.of(context).showSnackBar(
                    const SnackBar(content: Text('Opening treatment details...')),
                  );
                });
              },
            ),
            
            _TreatmentCard(
              treatment: 'Root Canal',
              date: 'Jul 10, 2025',
              dentist: 'Dr. Michael Chen',
              status: 'Completed',
              onTap: () async {
                await ref.requireAuth(context, () async {
                  // Navigate to treatment details
                  ScaffoldMessenger.of(context).showSnackBar(
                    const SnackBar(content: Text('Opening treatment details...')),
                  );
                });
              },
            ),
            
            _TreatmentCard(
              treatment: 'Orthodontic Consultation',
              date: 'Jun 15, 2025',
              dentist: 'Dr. Emily Rodriguez',
              status: 'Follow-up Required',
              onTap: () async {
                await ref.requireAuth(context, () async {
                  // Navigate to treatment details
                  ScaffoldMessenger.of(context).showSnackBar(
                    const SnackBar(content: Text('Opening treatment details...')),
                  );
                });
              },
            ),
            
            const SizedBox(height: 24),
            
            // Prescriptions
            Text(
              'Current Prescriptions',
              style: Theme.of(context).textTheme.titleLarge,
            ),
            const SizedBox(height: 16),
            
            _PrescriptionCard(
              medication: 'Amoxicillin 500mg',
              dosage: '3 times daily',
              remaining: '5 days left',
              onTap: () async {
                await ref.requireAuth(context, () async {
                  // Handle prescription tap
                  ScaffoldMessenger.of(context).showSnackBar(
                    const SnackBar(content: Text('Opening prescription details...')),
                  );
                });
              },
            ),
            
            const SizedBox(height: 24),
            
            // Health Reminders
            Text(
              'Health Reminders',
              style: Theme.of(context).textTheme.titleLarge,
            ),
            const SizedBox(height: 16),
            
            _ReminderCard(
              title: 'Next Checkup Due',
              subtitle: 'Schedule your 6-month checkup',
              dueDate: 'In 2 weeks',
              onTap: () async {
                await ref.requireAuth(context, () async {
                  // Handle reminder tap
                  ScaffoldMessenger.of(context).showSnackBar(
                    const SnackBar(content: Text('Opening appointment booking...')),
                  );
                });
              },
            ),
            
            _ReminderCard(
              title: 'Teeth Cleaning',
              subtitle: 'Professional cleaning recommended',
              dueDate: 'Overdue',
              isOverdue: true,
              onTap: () async {
                await ref.requireAuth(context, () async {
                  // Handle reminder tap
                  ScaffoldMessenger.of(context).showSnackBar(
                    const SnackBar(content: Text('Opening appointment booking...')),
                  );
                });
              },
            ),
            
            const SizedBox(height: 100), // Bottom padding for nav bar
          ],
        ),
      ),
    );
  }
}

class _HealthMetric extends StatelessWidget {
  final IconData icon;
  final String label;
  final String value;
  final Color? valueColor;

  const _HealthMetric({
    required this.icon,
    required this.label,
    required this.value,
    this.valueColor,
  });

  @override
  Widget build(BuildContext context) {
    return Row(
      children: [
        Icon(
          icon,
          size: 20,
          color: Theme.of(context).colorScheme.primary,
        ),
        const SizedBox(width: 8),
        Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              label,
              style: Theme.of(context).textTheme.bodySmall?.copyWith(
                color: Colors.grey[600],
              ),
            ),
            Text(
              value,
              style: Theme.of(context).textTheme.titleMedium?.copyWith(
                color: valueColor,
                fontWeight: FontWeight.w600,
              ),
            ),
          ],
        ),
      ],
    );
  }
}

class _TreatmentCard extends StatelessWidget {
  final String treatment;
  final String date;
  final String dentist;
  final String status;
  final VoidCallback onTap;

  const _TreatmentCard({
    required this.treatment,
    required this.date,
    required this.dentist,
    required this.status,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    final statusColor = status == 'Completed'
        ? Colors.green
        : status == 'Follow-up Required'
            ? Colors.orange
            : Colors.blue;

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
                  Icons.medical_services,
                  color: Theme.of(context).colorScheme.primary,
                ),
              ),
              const SizedBox(width: 16),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      treatment,
                      style: Theme.of(context).textTheme.titleMedium,
                    ),
                    const SizedBox(height: 4),
                    Text(
                      dentist,
                      style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                        color: Colors.grey[600],
                      ),
                    ),
                    const SizedBox(height: 4),
                    Row(
                      children: [
                        Text(
                          date,
                          style: Theme.of(context).textTheme.bodySmall,
                        ),
                        const SizedBox(width: 12),
                        Container(
                          padding: const EdgeInsets.symmetric(
                            horizontal: 8,
                            vertical: 2,
                          ),
                          decoration: BoxDecoration(
                            color: statusColor.withValues(alpha: 0.1),
                            borderRadius: BorderRadius.circular(12),
                          ),
                          child: Text(
                            status,
                            style: TextStyle(
                              fontSize: 10,
                              fontWeight: FontWeight.w600,
                              color: statusColor,
                            ),
                          ),
                        ),
                      ],
                    ),
                  ],
                ),
              ),
              const Icon(Icons.chevron_right),
            ],
          ),
        ),
      ),
    );
  }
}

class _PrescriptionCard extends StatelessWidget {
  final String medication;
  final String dosage;
  final String remaining;
  final VoidCallback onTap;

  const _PrescriptionCard({
    required this.medication,
    required this.dosage,
    required this.remaining,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
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
                  color: Colors.orange.withValues(alpha: 0.1),
                  borderRadius: BorderRadius.circular(12),
                ),
                child: const Icon(
                  Icons.medication,
                  color: Colors.orange,
                ),
              ),
              const SizedBox(width: 16),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      medication,
                      style: Theme.of(context).textTheme.titleMedium,
                    ),
                    const SizedBox(height: 4),
                    Text(
                      dosage,
                      style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                        color: Colors.grey[600],
                      ),
                    ),
                    const SizedBox(height: 4),
                    Text(
                      remaining,
                      style: Theme.of(context).textTheme.bodySmall?.copyWith(
                        color: Colors.orange,
                        fontWeight: FontWeight.w500,
                      ),
                    ),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

class _ReminderCard extends StatelessWidget {
  final String title;
  final String subtitle;
  final String dueDate;
  final bool isOverdue;
  final VoidCallback onTap;

  const _ReminderCard({
    required this.title,
    required this.subtitle,
    required this.dueDate,
    this.isOverdue = false,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    final color = isOverdue ? Colors.red : Colors.blue;

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
                  color: color.withValues(alpha: 0.1),
                  borderRadius: BorderRadius.circular(12),
                ),
                child: Icon(
                  isOverdue ? Icons.warning : Icons.notifications,
                  color: color,
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
                      style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                        color: Colors.grey[600],
                      ),
                    ),
                  ],
                ),
              ),
              Text(
                dueDate,
                style: Theme.of(context).textTheme.bodySmall?.copyWith(
                  color: color,
                  fontWeight: FontWeight.w600,
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}