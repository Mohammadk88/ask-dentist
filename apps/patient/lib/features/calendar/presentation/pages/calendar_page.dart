import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../../core/auth/controllers/auth_controller.dart';
import '../../../../core/widgets/guest_placeholders.dart';

class CalendarPage extends ConsumerWidget {
  const CalendarPage({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final authState = ref.watch(authControllerProvider);

    return Scaffold(
      appBar: AppBar(
        title: const Text('Calendar'),
        actions: [
          IconButton(
            icon: const Icon(Icons.today),
            onPressed: () {
              // Go to today
            },
          ),
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
    return Column(
      children: [
        // Calendar Header
        Container(
          padding: const EdgeInsets.all(16),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              IconButton(
                onPressed: () {
                  // Previous month
                },
                icon: const Icon(Icons.chevron_left),
              ),
              Text(
                'September 2025',
                style: Theme.of(context).textTheme.titleLarge,
              ),
              IconButton(
                onPressed: () {
                  // Next month
                },
                icon: const Icon(Icons.chevron_right),
              ),
            ],
          ),
        ),
        
        // Days of week header
        Container(
          padding: const EdgeInsets.symmetric(horizontal: 16),
          child: Row(
            children: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']
                .map((day) => Expanded(
                      child: Center(
                        child: Text(
                          day,
                          style: Theme.of(context).textTheme.bodySmall?.copyWith(
                            fontWeight: FontWeight.w600,
                            color: Colors.grey[600],
                          ),
                        ),
                      ),
                    ))
                .toList(),
          ),
        ),
        
        const SizedBox(height: 8),
        
        // Calendar Grid (read-only for guests)
        Expanded(
          child: LockedContentOverlay(
            title: 'Login to See Your Appointments',
            description: 'Sign in to view and manage your dental appointments',
            child: Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              child: GridView.builder(
                gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                  crossAxisCount: 7,
                  childAspectRatio: 1,
                ),
                itemCount: 42, // 6 weeks * 7 days
                itemBuilder: (context, index) {
                  final day = index - 6 + 1; // Assuming September starts on Sunday
                  final isCurrentMonth = day > 0 && day <= 30;
                  final isToday = day == 8; // Example: 8th is today
                  
                  return _CalendarDay(
                    day: isCurrentMonth ? day : null,
                    isToday: isToday,
                    hasAppointment: false, // No appointments shown for guests
                    onTap: null, // Disabled for guests
                  );
                },
              ),
            ),
          ),
        ),
      ],
    );
  }

  Widget _buildAuthenticatedView(BuildContext context, WidgetRef ref, String userName) {
    return Column(
      children: [
        // Calendar Header
        Container(
          padding: const EdgeInsets.all(16),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              IconButton(
                onPressed: () {
                  // Previous month
                },
                icon: const Icon(Icons.chevron_left),
              ),
              Text(
                'September 2025',
                style: Theme.of(context).textTheme.titleLarge,
              ),
              IconButton(
                onPressed: () {
                  // Next month
                },
                icon: const Icon(Icons.chevron_right),
              ),
            ],
          ),
        ),
        
        // Days of week header
        Container(
          padding: const EdgeInsets.symmetric(horizontal: 16),
          child: Row(
            children: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']
                .map((day) => Expanded(
                      child: Center(
                        child: Text(
                          day,
                          style: Theme.of(context).textTheme.bodySmall?.copyWith(
                            fontWeight: FontWeight.w600,
                            color: Colors.grey[600],
                          ),
                        ),
                      ),
                    ))
                .toList(),
          ),
        ),
        
        const SizedBox(height: 8),
        
        // Calendar Grid
        Expanded(
          child: Padding(
            padding: const EdgeInsets.symmetric(horizontal: 16),
            child: GridView.builder(
              gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                crossAxisCount: 7,
                childAspectRatio: 1,
              ),
              itemCount: 42, // 6 weeks * 7 days
              itemBuilder: (context, index) {
                final day = index - 6 + 1; // Assuming September starts on Sunday
                final isCurrentMonth = day > 0 && day <= 30;
                final isToday = day == 8; // Example: 8th is today
                final hasAppointment = [15, 22, 29].contains(day); // Example appointments
                
                return _CalendarDay(
                  day: isCurrentMonth ? day : null,
                  isToday: isToday,
                  hasAppointment: hasAppointment,
                  onTap: isCurrentMonth
                      ? () {
                          // Handle day selection
                        }
                      : null,
                );
              },
            ),
          ),
        ),
        
        // Appointments list
        Container(
          height: 200,
          padding: const EdgeInsets.all(16),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                children: [
                  Text(
                    'Hello $userName, here are your appointments',
                    style: Theme.of(context).textTheme.titleMedium,
                  ),
                  const Spacer(),
                  IconButton(
                    icon: const Icon(Icons.add),
                    onPressed: () async {
                      // Book new appointment - requires auth
                      await ref.requireAuth(context, () async {
                        // Navigate to booking
                        ScaffoldMessenger.of(context).showSnackBar(
                          const SnackBar(content: Text('Opening appointment booking...')),
                        );
                      });
                    },
                  ),
                ],
              ),
              const SizedBox(height: 12),
              Expanded(
                child: ListView(
                  children: [
                    _AppointmentListItem(
                      time: '10:00 AM',
                      dentistName: 'Dr. Sarah Johnson',
                      treatment: 'Regular Checkup',
                      status: 'Confirmed',
                      onTap: () {
                        // Handle appointment tap
                      },
                    ),
                    _AppointmentListItem(
                      time: '2:30 PM',
                      dentistName: 'Dr. Michael Chen',
                      treatment: 'Teeth Cleaning',
                      status: 'Pending',
                      onTap: () {
                        // Handle appointment tap
                      },
                    ),
                  ],
                ),
              ),
            ],
          ),
        ),
      ],
    );
  }
}

class _CalendarDay extends StatelessWidget {
  final int? day;
  final bool isToday;
  final bool hasAppointment;
  final VoidCallback? onTap;

  const _CalendarDay({
    this.day,
    this.isToday = false,
    this.hasAppointment = false,
    this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    if (day == null) {
      return const SizedBox();
    }

    return GestureDetector(
      onTap: onTap,
      child: Container(
        margin: const EdgeInsets.all(2),
        decoration: BoxDecoration(
          color: isToday
              ? Theme.of(context).colorScheme.primary
              : hasAppointment
                  ? Theme.of(context).colorScheme.primary.withValues(alpha: 0.1)
                  : null,
          borderRadius: BorderRadius.circular(8),
        ),
        child: Stack(
          children: [
            Center(
              child: Text(
                day.toString(),
                style: TextStyle(
                  color: isToday
                      ? Colors.white
                      : hasAppointment
                          ? Theme.of(context).colorScheme.primary
                          : null,
                  fontWeight: isToday || hasAppointment
                      ? FontWeight.w600
                      : FontWeight.normal,
                ),
              ),
            ),
            if (hasAppointment && !isToday)
              Positioned(
                top: 4,
                right: 4,
                child: Container(
                  width: 6,
                  height: 6,
                  decoration: BoxDecoration(
                    color: Theme.of(context).colorScheme.primary,
                    shape: BoxShape.circle,
                  ),
                ),
              ),
          ],
        ),
      ),
    );
  }
}

class _AppointmentListItem extends StatelessWidget {
  final String time;
  final String dentistName;
  final String treatment;
  final String status;
  final VoidCallback onTap;

  const _AppointmentListItem({
    required this.time,
    required this.dentistName,
    required this.treatment,
    required this.status,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    final statusColor = status == 'Confirmed'
        ? Colors.green
        : status == 'Pending'
            ? Colors.orange
            : Colors.red;

    return Card(
      margin: const EdgeInsets.only(bottom: 8),
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(16),
        child: Padding(
          padding: const EdgeInsets.all(12),
          child: Row(
            children: [
              Container(
                width: 4,
                height: 40,
                decoration: BoxDecoration(
                  color: Theme.of(context).colorScheme.primary,
                  borderRadius: BorderRadius.circular(2),
                ),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: [
                        Text(
                          time,
                          style: Theme.of(context).textTheme.titleSmall?.copyWith(
                            fontWeight: FontWeight.w600,
                          ),
                        ),
                        const Spacer(),
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
                    const SizedBox(height: 2),
                    Text(
                      dentistName,
                      style: Theme.of(context).textTheme.bodyMedium,
                    ),
                    Text(
                      treatment,
                      style: Theme.of(context).textTheme.bodySmall?.copyWith(
                        color: Colors.grey[600],
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