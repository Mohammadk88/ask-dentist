import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'providers/home_provider.dart';
import 'widgets/stories_carousel.dart';
import 'widgets/clinics_slider.dart';
import 'widgets/doctor_card.dart';
import 'widgets/favorites_list.dart';
import 'widgets/before_after_carousel.dart';
import 'models/home_dto.dart';

class HomeScreen extends ConsumerWidget {
  const HomeScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final homeState = ref.watch(homeProvider);

    return Scaffold(
      appBar: AppBar(
        title: Text(
          'Ask.Dentist', // TODO: Localize
          style: Theme.of(context).textTheme.headlineSmall?.copyWith(
            fontWeight: FontWeight.bold,
            color: Theme.of(context).colorScheme.primary,
          ),
        ),
        backgroundColor: Colors.transparent,
        elevation: 0,
        actions: [
          IconButton(
            icon: const Icon(Icons.notifications_outlined),
            onPressed: () {
              // Navigate to notifications
            },
          ),
          IconButton(
            icon: const Icon(Icons.search),
            onPressed: () {
              // Navigate to search
            },
          ),
        ],
      ),
      body: RefreshIndicator(
        onRefresh: () async {
          await ref.read(homeProvider.notifier).refreshData();
        },
        child: CustomScrollView(
          slivers: [
            // Search Bar
            SliverToBoxAdapter(
              child: Container(
                margin: const EdgeInsets.all(16),
                child: _SearchBar(
                  onTap: () {
                    // Navigate to search screen
                  },
                ),
              ),
            ),

            // Stories Section
            SliverToBoxAdapter(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  StoriesCarousel(
                    stories: homeState.stories,
                    isLoading: homeState.isLoading,
                  ),
                  const SizedBox(height: 24),
                ],
              ),
            ),

            // Top Clinics Section
            SliverToBoxAdapter(
              child: Column(
                children: [
                  ClinicsSlider(
                    clinics: homeState.featuredClinics,
                    isLoading: homeState.isLoading,
                    onViewAllTap: () {
                      // Navigate to all clinics
                    },
                  ),
                  const SizedBox(height: 24),
                ],
              ),
            ),

            // Top Doctors Section
            SliverToBoxAdapter(
              child: Padding(
                padding: const EdgeInsets.symmetric(horizontal: 16),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Text(
                      'Top Doctors', // TODO: Localize
                      style: Theme.of(context).textTheme.titleLarge?.copyWith(
                        fontWeight: FontWeight.w600,
                      ),
                    ),
                    TextButton(
                      onPressed: () {
                        // Navigate to all doctors
                      },
                      child: const Text('View All'),
                    ),
                  ],
                ),
              ),
            ),

            // Top Doctors List
            homeState.isLoading
                ? SliverList(
                    delegate: SliverChildBuilderDelegate(
                      (context, index) => const DoctorCardShimmer(),
                      childCount: 3,
                    ),
                  )
                : homeState.recommendedDoctors.isEmpty
                    ? SliverToBoxAdapter(
                        child: _buildEmptyDoctorsState(context),
                      )
                    : SliverList(
                        delegate: SliverChildBuilderDelegate(
                          (context, index) {
                            final doctor = homeState.recommendedDoctors[index];
                            return DoctorCard(
                              doctor: doctor,
                              onTap: () => _onDoctorTap(context, doctor.id),
                              onFavoriteTap: () => ref
                                  .read(homeProvider.notifier)
                                  .toggleFavoriteDoctor(doctor.id),
                              onChatTap: () => _onChatTap(context, doctor.id),
                              onCallTap: () => _onCallTap(context, doctor.id),
                              onVideoTap: () => _onVideoTap(context, doctor.id),
                            );
                          },
                          childCount: homeState.recommendedDoctors.length > 3
                              ? 3
                              : homeState.recommendedDoctors.length,
                        ),
                      ),

            // Spacing
            const SliverToBoxAdapter(
              child: SizedBox(height: 24),
            ),

            // Favorite Doctors Section
            SliverToBoxAdapter(
              child: FavoritesList(
                favoriteDoctors: homeState.favoriteDoctors,
                isLoading: homeState.isLoading,
                onViewAllTap: () {
                  // Navigate to all favorites
                },
                onDoctorTap: (doctorId) => _onDoctorTap(context, doctorId),
                onFavoriteTap: (doctorId) => ref
                    .read(homeProvider.notifier)
                    .toggleFavoriteDoctor(doctorId),
                onChatTap: (doctorId) => _onChatTap(context, doctorId),
                onCallTap: (doctorId) => _onCallTap(context, doctorId),
                onVideoTap: (doctorId) => _onVideoTap(context, doctorId),
              ),
            ),

            // Spacing
            const SliverToBoxAdapter(
              child: SizedBox(height: 24),
            ),

            // Before & After Section
            SliverToBoxAdapter(
              child: BeforeAfterCarousel(
                beforeAfterCases: homeState.beforeAfterGallery,
                isLoading: homeState.isLoading,
                onCaseTap: (beforeAfterCase) {
                  // Navigate to case details
                  _showCaseDetails(context, beforeAfterCase);
                },
              ),
            ),

            // Bottom Spacing for Navigation Bar
            const SliverToBoxAdapter(
              child: SizedBox(height: 100),
            ),

            if (homeState.error != null)
              SliverToBoxAdapter(
                child: _buildErrorState(context, ref, homeState.error!),
              ),
          ],
        ),
      ),
    );
  }

  Widget _buildEmptyDoctorsState(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(32),
      child: Column(
        children: [
          Icon(
            Icons.medical_services_outlined,
            size: 48,
            color: Colors.grey[400],
          ),
          const SizedBox(height: 16),
          Text(
            'No doctors available',
            style: Theme.of(context).textTheme.titleMedium?.copyWith(
              color: Colors.grey[600],
            ),
          ),
          const SizedBox(height: 8),
          Text(
            'Check back later for available doctors',
            style: Theme.of(context).textTheme.bodyMedium?.copyWith(
              color: Colors.grey[500],
            ),
            textAlign: TextAlign.center,
          ),
        ],
      ),
    );
  }

  Widget _buildErrorState(BuildContext context, WidgetRef ref, String error) {
    return Container(
      padding: const EdgeInsets.all(32),
      child: Column(
        children: [
          Icon(
            Icons.error_outline,
            size: 48,
            color: Colors.red[400],
          ),
          const SizedBox(height: 16),
          Text(
            'Something went wrong',
            style: Theme.of(context).textTheme.titleMedium?.copyWith(
              color: Colors.red[600],
            ),
          ),
          const SizedBox(height: 8),
          Text(
            error,
            style: Theme.of(context).textTheme.bodyMedium?.copyWith(
              color: Colors.grey[600],
            ),
            textAlign: TextAlign.center,
          ),
          const SizedBox(height: 16),
          ElevatedButton(
            onPressed: () => ref.read(homeProvider.notifier).refreshData(),
            child: const Text('Try Again'),
          ),
        ],
      ),
    );
  }

  void _onDoctorTap(BuildContext context, String doctorId) {
    // Navigate to doctor profile
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text('Opening doctor profile: $doctorId'),
        duration: const Duration(seconds: 1),
      ),
    );
  }

  void _onChatTap(BuildContext context, String doctorId) {
    // Start chat with doctor
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text('Starting chat with doctor: $doctorId'),
        duration: const Duration(seconds: 1),
      ),
    );
  }

  void _onCallTap(BuildContext context, String doctorId) {
    // Start voice call with doctor
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text('Starting call with doctor: $doctorId'),
        duration: const Duration(seconds: 1),
      ),
    );
  }

  void _onVideoTap(BuildContext context, String doctorId) {
    // Start video call with doctor
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text('Starting video call with doctor: $doctorId'),
        duration: const Duration(seconds: 1),
      ),
    );
  }

  void _showCaseDetails(BuildContext context, BeforeAfterCase beforeAfterCase) {
    showDialog(
      context: context,
      builder: (context) => Dialog(
        child: Container(
          padding: const EdgeInsets.all(20),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              Row(
                children: [
                  Expanded(
                    child: ClipRRect(
                      borderRadius: BorderRadius.circular(8),
                      child: Image.network(
                        beforeAfterCase.beforeImageUrl,
                        height: 150,
                        fit: BoxFit.cover,
                      ),
                    ),
                  ),
                  const SizedBox(width: 8),
                  Expanded(
                    child: ClipRRect(
                      borderRadius: BorderRadius.circular(8),
                      child: Image.network(
                        beforeAfterCase.afterImageUrl,
                        height: 150,
                        fit: BoxFit.cover,
                      ),
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 16),
              Text(
                beforeAfterCase.title,
                style: Theme.of(context).textTheme.headlineSmall,
                textAlign: TextAlign.center,
              ),
              const SizedBox(height: 8),
              Text(
                beforeAfterCase.description,
                style: Theme.of(context).textTheme.bodyMedium,
                textAlign: TextAlign.center,
              ),
              const SizedBox(height: 16),
              ElevatedButton(
                onPressed: () => Navigator.of(context).pop(),
                child: const Text('Close'),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

class _SearchBar extends StatelessWidget {
  final VoidCallback? onTap;

  const _SearchBar({this.onTap});

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
        decoration: BoxDecoration(
          color: Colors.grey[100],
          borderRadius: BorderRadius.circular(12),
          border: Border.all(
            color: Colors.grey[300]!,
            width: 1,
          ),
        ),
        child: Row(
          children: [
            Icon(
              Icons.search,
              color: Colors.grey[600],
              size: 20,
            ),
            const SizedBox(width: 12),
            Text(
              'Search doctors, clinics...', // TODO: Localize
              style: TextStyle(
                color: Colors.grey[600],
                fontSize: 16,
              ),
            ),
          ],
        ),
      ),
    );
  }
}