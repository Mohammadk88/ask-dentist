import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import 'package:shimmer/shimmer.dart';
import '../models/home_dto.dart';
import '../widgets/doctor_card.dart';
import '../../favorites/notifiers/favorites_notifier.dart';
import '../../favorites/services/favorites_service.dart';

class DoctorsSection extends ConsumerWidget {
  final String title;
  final List<DoctorItem> doctors;
  final bool isLoading;
  final Function(DoctorItem)? onDoctorTap;

  const DoctorsSection({
    required this.title,
    required this.doctors,
    this.isLoading = false,
    this.onDoctorTap,
    super.key,
  });

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    if (isLoading) {
      return _buildShimmerLoader(context);
    }

    if (doctors.isEmpty) return const SizedBox.shrink();

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Padding(
          padding: const EdgeInsets.symmetric(horizontal: 16),
          child: Row(
            children: [
              Text(
                title,
                style: Theme.of(context).textTheme.titleLarge?.copyWith(
                      fontWeight: FontWeight.bold,
                    ),
              ),
              const Spacer(),
              TextButton(
                onPressed: () {
                  // TODO: Navigate to all doctors
                },
                child: const Text('View All'),
              ),
            ],
          ),
        ),
        const SizedBox(height: 12),
        SizedBox(
          height: 320, // Increased height for action buttons
          child: Directionality(
            textDirection: _getScrollDirection(context),
            child: ListView.builder(
              scrollDirection: Axis.horizontal,
              padding: const EdgeInsets.symmetric(horizontal: 16),
              physics: const BouncingScrollPhysics(),
              itemCount: doctors.length,
              itemBuilder: (context, index) {
                final doctor = doctors[index];
                return Container(
                  width: 280, // Increased width for action buttons
                  margin: const EdgeInsets.only(right: 16),
                  child: DoctorCard(
                    doctor: doctor,
                    onTap:
                        onDoctorTap != null ? () => onDoctorTap!(doctor) : null,
                    onFavoriteTap: () =>
                        _handleFavoriteTap(context, ref, doctor.id),
                    onChatTap: () => _handleChatTap(context, doctor),
                    onCallTap: () => _handleCallTap(context, doctor),
                    onVideoTap: () => _handleVideoTap(context, doctor),
                  ),
                );
              },
            ),
          ),
        ),
      ],
    );
  }

  Widget _buildShimmerLoader(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Padding(
          padding: const EdgeInsets.symmetric(horizontal: 16),
          child: Row(
            children: [
              Shimmer.fromColors(
                baseColor: Colors.grey.shade300,
                highlightColor: Colors.grey.shade100,
                child: Container(
                  height: 24,
                  width: 160,
                  decoration: BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(8),
                  ),
                ),
              ),
              const Spacer(),
              Shimmer.fromColors(
                baseColor: Colors.grey.shade300,
                highlightColor: Colors.grey.shade100,
                child: Container(
                  height: 16,
                  width: 60,
                  decoration: BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(8),
                  ),
                ),
              ),
            ],
          ),
        ),
        const SizedBox(height: 12),
        SizedBox(
          height: 320,
          child: Directionality(
            textDirection: _getScrollDirection(context),
            child: ListView.builder(
              scrollDirection: Axis.horizontal,
              padding: const EdgeInsets.symmetric(horizontal: 16),
              physics: const BouncingScrollPhysics(),
              itemCount: 3,
              itemBuilder: (context, index) {
                return Container(
                  width: 280,
                  margin: const EdgeInsets.only(right: 16),
                  child: Shimmer.fromColors(
                    baseColor: Colors.grey.shade300,
                    highlightColor: Colors.grey.shade100,
                    child: const DoctorCardShimmer(),
                  ),
                );
              },
            ),
          ),
        ),
      ],
    );
  }

  void _handleFavoriteTap(
      BuildContext context, WidgetRef ref, String doctorId) async {
    try {
      await ref
          .read(favoriteDoctorsNotifierProvider.notifier)
          .toggleFavorite(doctorId);

      // Show feedback
      if (context.mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('Favorites updated'),
            duration: Duration(seconds: 1),
          ),
        );
      }
    } catch (e) {
      if (e is UnauthorizedException) {
        // Redirect to login
        if (context.mounted) {
          context.push('/auth/login');
        }
      } else {
        // Show error
        if (context.mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              content: Text('Failed to update favorites: ${e.toString()}'),
              backgroundColor: Theme.of(context).colorScheme.error,
            ),
          );
        }
      }
    }
  }

  void _handleChatTap(BuildContext context, DoctorItem doctor) {
    context.push(
        '/chat/${doctor.id}?doctorName=${Uri.encodeComponent(doctor.name)}');
  }

  void _handleCallTap(BuildContext context, DoctorItem doctor) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text('Voice call with ${doctor.name} - Coming soon!'),
        action: SnackBarAction(
          label: 'Book Appointment',
          onPressed: () {
            // TODO: Navigate to booking
          },
        ),
      ),
    );
  }

  void _handleVideoTap(BuildContext context, DoctorItem doctor) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text('Video call with ${doctor.name} - Coming soon!'),
        action: SnackBarAction(
          label: 'Book Appointment',
          onPressed: () {
            // TODO: Navigate to booking
          },
        ),
      ),
    );
  }

  /// Determines scroll direction based on locale for proper RTL support
  TextDirection _getScrollDirection(BuildContext context) {
    final locale = Localizations.localeOf(context);
    // Arabic and other RTL languages should scroll RTL
    if (locale.languageCode == 'ar' ||
        locale.languageCode == 'he' ||
        locale.languageCode == 'fa' ||
        locale.languageCode == 'ur') {
      return TextDirection.rtl;
    }
    return TextDirection.ltr;
  }
}
