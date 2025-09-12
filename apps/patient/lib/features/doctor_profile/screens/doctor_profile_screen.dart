import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../core/models/doctor.dart';
import '../../../core/models/review.dart';
import '../../../core/models/clinic.dart';
import '../models/doctor_profile_models.dart';
import '../providers/doctor_profile_provider.dart';
import '../widgets/doctor_header.dart';
import '../widgets/doctor_info_section.dart';
import '../widgets/services_section.dart';
import '../widgets/reviews_section.dart';
import '../widgets/before_after_section.dart';
import '../widgets/consultation_booking.dart';

class DoctorProfileScreen extends ConsumerStatefulWidget {
  final String doctorId;
  final Doctor? doctor; // Optional - if passed from previous screen

  const DoctorProfileScreen({
    required this.doctorId,
    this.doctor,
    super.key,
  });

  @override
  ConsumerState<DoctorProfileScreen> createState() => _DoctorProfileScreenState();
}

class _DoctorProfileScreenState extends ConsumerState<DoctorProfileScreen>
    with TickerProviderStateMixin {
  late TabController _tabController;
  late ScrollController _scrollController;
  bool _isHeaderVisible = true;

  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: 4, vsync: this);
    _scrollController = ScrollController();
    _scrollController.addListener(_handleScroll);
    
    // Load doctor profile
    WidgetsBinding.instance.addPostFrameCallback((_) {
      ref.read(doctorProfileProvider.notifier).loadDoctorProfile(widget.doctorId);
    });
  }

  @override
  void dispose() {
    _tabController.dispose();
    _scrollController.removeListener(_handleScroll);
    _scrollController.dispose();
    super.dispose();
  }

  void _handleScroll() {
    const threshold = 200.0;
    final isVisible = _scrollController.offset < threshold;
    if (isVisible != _isHeaderVisible) {
      setState(() {
        _isHeaderVisible = isVisible;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    final profileState = ref.watch(doctorProfileProvider);

    return Scaffold(
      body: _buildBody(profileState),
      floatingActionButton: profileState.doctor != null 
          ? _buildFloatingActionButton(profileState.availability)
          : null,
    );
  }

  Widget _buildBody(DoctorProfileState state) {
    if (state.isLoading) {
      return const Center(child: CircularProgressIndicator());
    }
    
    if (state.error != null) {
      return _buildErrorState(state.error!);
    }
    
    if (state.doctor == null) {
      return const Center(child: Text('Doctor not found'));
    }
    
    return _buildLoadedState(
      state.doctor!, 
      state.reviews, 
      state.services, 
      state.beforeAfterCases, 
      state.availability, 
      state.isFavorite
    );
  }

  Widget _buildErrorState(String message) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Doctor Profile'),
      ),
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(
              Icons.error_outline,
              size: 64,
              color: Theme.of(context).colorScheme.error,
            ),
            const SizedBox(height: 16),
            Text(
              'Error loading doctor profile',
              style: Theme.of(context).textTheme.headlineSmall,
            ),
            const SizedBox(height: 8),
            Text(
              message,
              style: Theme.of(context).textTheme.bodyMedium,
              textAlign: TextAlign.center,
            ),
            const SizedBox(height: 24),
            ElevatedButton(
              onPressed: () {
                ref.read(doctorProfileProvider.notifier).loadDoctorProfile(widget.doctorId);
              },
              child: const Text('Retry'),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildLoadedState(
    Doctor doctor,
    List<Review> reviews,
    List<DoctorService> services,
    List<BeforeAfterCase> beforeAfterCases,
    List<DoctorAvailability> availability,
    bool isFavorite,
  ) {
    return NestedScrollView(
      controller: _scrollController,
      headerSliverBuilder: (context, innerBoxIsScrolled) {
        return [
          SliverAppBar(
            expandedHeight: 280.0,
            floating: false,
            pinned: true,
            elevation: 0,
            backgroundColor: Theme.of(context).colorScheme.surface,
            foregroundColor: Theme.of(context).colorScheme.onSurface,
            flexibleSpace: FlexibleSpaceBar(
              background: DoctorHeader(
                doctor: doctor,
                isFavorite: isFavorite,
                onFavoriteToggle: () {
                  ref.read(doctorProfileProvider.notifier).toggleFavorite();
                },
              ),
            ),
            actions: [
              IconButton(
                icon: Icon(
                  isFavorite ? Icons.favorite : Icons.favorite_border,
                  color: isFavorite ? Colors.red : null,
                ),
                onPressed: () {
                  ref.read(doctorProfileProvider.notifier).toggleFavorite();
                },
              ),
              IconButton(
                icon: const Icon(Icons.share),
                onPressed: () {
                  // TODO: Implement share functionality
                },
              ),
            ],
          ),
          SliverPersistentHeader(
            delegate: _SliverTabBarDelegate(
              TabBar(
                controller: _tabController,
                indicatorColor: Theme.of(context).colorScheme.primary,
                labelColor: Theme.of(context).colorScheme.primary,
                unselectedLabelColor: Theme.of(context).colorScheme.onSurface.withValues(alpha: 0.6),
                tabs: const [
                  Tab(text: 'About'),
                  Tab(text: 'Services'),
                  Tab(text: 'Reviews'),
                  Tab(text: 'Gallery'),
                ],
              ),
            ),
            pinned: true,
          ),
        ];
      },
      body: TabBarView(
        controller: _tabController,
        children: [
          DoctorInfoSection(doctor: doctor),
          ServicesSection(services: services),
          ReviewsSection(reviews: reviews),
          BeforeAfterSection(cases: beforeAfterCases),
        ],
      ),
    );
  }

  Widget _buildFloatingActionButton(List<DoctorAvailability> availability) {
    return FloatingActionButton.extended(
      onPressed: () {
        _showConsultationBooking(availability);
      },
      icon: const Icon(Icons.video_call),
      label: const Text('Book Consultation'),
    );
  }

  void _showConsultationBooking(List<DoctorAvailability> availability) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (context) => ConsultationBooking(
        availability: availability,
        onBook: (type, date, time) {
          ref.read(doctorProfileProvider.notifier).bookConsultation(
            type: type,
            date: date,
            time: time,
          );
          Navigator.pop(context);
          _showBookingSuccess();
        },
      ),
    );
  }

  void _showBookingSuccess() {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: const Text('Consultation booked successfully!'),
        backgroundColor: Colors.green,
        behavior: SnackBarBehavior.floating,
        action: SnackBarAction(
          label: 'View',
          textColor: Colors.white,
          onPressed: () {
            // TODO: Navigate to bookings/itinerary
          },
        ),
      ),
    );
  }
}

class _SliverTabBarDelegate extends SliverPersistentHeaderDelegate {
  const _SliverTabBarDelegate(this.tabBar);

  final TabBar tabBar;

  @override
  double get minExtent => tabBar.preferredSize.height;

  @override
  double get maxExtent => tabBar.preferredSize.height;

  @override
  Widget build(BuildContext context, double shrinkOffset, bool overlapsContent) {
    return Container(
      color: Theme.of(context).colorScheme.surface,
      child: tabBar,
    );
  }

  @override
  bool shouldRebuild(_SliverTabBarDelegate oldDelegate) {
    return tabBar != oldDelegate.tabBar;
  }
}