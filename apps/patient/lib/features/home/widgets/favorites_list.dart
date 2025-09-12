import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:shimmer/shimmer.dart';
import '../models/home_dto.dart';

class FavoritesList extends ConsumerWidget {
  final List<DoctorItem> favoriteDoctors;
  final bool isLoading;
  final VoidCallback? onViewAllTap;
  final Function(String)? onDoctorTap;
  final Function(String)? onFavoriteTap;
  final Function(String)? onChatTap;
  final Function(String)? onCallTap;
  final Function(String)? onVideoTap;

  const FavoritesList({
    super.key,
    required this.favoriteDoctors,
    this.isLoading = false,
    this.onViewAllTap,
    this.onDoctorTap,
    this.onFavoriteTap,
    this.onChatTap,
    this.onCallTap,
    this.onVideoTap,
  });

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Padding(
          padding: const EdgeInsets.symmetric(horizontal: 16),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(
                'Favorite Doctors', // TODO: Localize
                style: Theme.of(context).textTheme.titleLarge?.copyWith(
                  fontWeight: FontWeight.w600,
                ),
              ),
              if (favoriteDoctors.isNotEmpty || isLoading)
                TextButton(
                  onPressed: onViewAllTap,
                  child: const Text('View All'),
                ),
            ],
          ),
        ),
        const SizedBox(height: 12),
        if (isLoading)
          _buildShimmerLoader()
        else if (favoriteDoctors.isEmpty)
          _buildEmptyState(context)
        else
          _buildContent(),
      ],
    );
  }

  Widget _buildContent() {
    return Column(
      children: favoriteDoctors.take(3).map((doctor) {
        return _CompactDoctorCard(
          doctor: doctor,
          onTap: () => onDoctorTap?.call(doctor.id),
          onFavoriteTap: () => onFavoriteTap?.call(doctor.id),
          onChatTap: () => onChatTap?.call(doctor.id),
          onCallTap: () => onCallTap?.call(doctor.id),
          onVideoTap: () => onVideoTap?.call(doctor.id),
        );
      }).toList(),
    );
  }

  Widget _buildShimmerLoader() {
    return Column(
      children: List.generate(3, (index) {
        return Shimmer.fromColors(
          baseColor: Colors.grey.shade300,
          highlightColor: Colors.grey.shade100,
          child: const _CompactDoctorCardShimmer(),
        );
      }),
    );
  }

  Widget _buildEmptyState(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(32),
      child: Column(
        children: [
          Icon(
            Icons.favorite_border,
            size: 48,
            color: Colors.grey[400],
          ),
          const SizedBox(height: 16),
          Text(
            'No favorite doctors yet',
            style: Theme.of(context).textTheme.titleMedium?.copyWith(
              color: Colors.grey[600],
            ),
          ),
          const SizedBox(height: 8),
          Text(
            'Add doctors to your favorites to see them here',
            style: Theme.of(context).textTheme.bodyMedium?.copyWith(
              color: Colors.grey[500],
            ),
            textAlign: TextAlign.center,
          ),
          const SizedBox(height: 16),
          ElevatedButton(
            onPressed: onViewAllTap,
            child: const Text('Find Doctors'),
          ),
        ],
      ),
    );
  }
}

class _CompactDoctorCard extends StatelessWidget {
  final DoctorItem doctor;
  final VoidCallback? onTap;
  final VoidCallback? onFavoriteTap;
  final VoidCallback? onChatTap;
  final VoidCallback? onCallTap;
  final VoidCallback? onVideoTap;

  const _CompactDoctorCard({
    required this.doctor,
    this.onTap,
    this.onFavoriteTap,
    this.onChatTap,
    this.onCallTap,
    this.onVideoTap,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 4),
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(16),
        child: Padding(
          padding: const EdgeInsets.all(12),
          child: Row(
            children: [
              // Doctor Avatar
              Stack(
                children: [
                  CircleAvatar(
                    radius: 24,
                    backgroundImage: doctor.avatarUrl.isNotEmpty
                        ? NetworkImage(doctor.avatarUrl)
                        : null,
                    backgroundColor: Colors.grey[300],
                    child: doctor.avatarUrl.isEmpty
                        ? Icon(
                            Icons.person,
                            size: 24,
                            color: Colors.grey[600],
                          )
                        : null,
                  ),
                  // Online indicator
                  if (doctor.isOnline)
                    Positioned(
                      bottom: 0,
                      right: 0,
                      child: Container(
                        width: 12,
                        height: 12,
                        decoration: BoxDecoration(
                          color: Colors.green,
                          shape: BoxShape.circle,
                          border: Border.all(
                            color: Colors.white,
                            width: 1.5,
                          ),
                        ),
                      ),
                    ),
                ],
              ),
              const SizedBox(width: 12),
              // Doctor Info
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      doctor.name,
                      style: Theme.of(context).textTheme.titleSmall?.copyWith(
                        fontWeight: FontWeight.w600,
                      ),
                    ),
                    const SizedBox(height: 2),
                    Text(
                      doctor.specialty,
                      style: Theme.of(context).textTheme.bodySmall?.copyWith(
                        color: Theme.of(context).colorScheme.primary,
                      ),
                    ),
                    const SizedBox(height: 4),
                    Row(
                      children: [
                        Icon(
                          Icons.star,
                          size: 12,
                          color: Colors.amber,
                        ),
                        const SizedBox(width: 2),
                        Text(
                          doctor.rating.toStringAsFixed(1),
                          style: Theme.of(context).textTheme.bodySmall?.copyWith(
                            fontWeight: FontWeight.w500,
                          ),
                        ),
                        const SizedBox(width: 4),
                        Text(
                          '• ${doctor.experience}',
                          style: Theme.of(context).textTheme.bodySmall?.copyWith(
                            color: Colors.grey[600],
                          ),
                        ),
                      ],
                    ),
                  ],
                ),
              ),
              // Action Buttons
              Row(
                mainAxisSize: MainAxisSize.min,
                children: [
                  _CompactActionButton(
                    icon: Icons.chat_bubble_outline,
                    onTap: onChatTap,
                  ),
                  const SizedBox(width: 8),
                  _CompactActionButton(
                    icon: Icons.phone,
                    onTap: onCallTap,
                  ),
                  const SizedBox(width: 8),
                  _CompactActionButton(
                    icon: Icons.videocam,
                    onTap: onVideoTap,
                    isPrimary: true,
                  ),
                ],
              ),
            ],
          ),
        ),
      ),
    );
  }
}

class _CompactActionButton extends StatelessWidget {
  final IconData icon;
  final VoidCallback? onTap;
  final bool isPrimary;

  const _CompactActionButton({
    required this.icon,
    this.onTap,
    this.isPrimary = false,
  });

  @override
  Widget build(BuildContext context) {
    return Material(
      color: isPrimary 
          ? Theme.of(context).colorScheme.primary
          : Theme.of(context).colorScheme.primary.withValues(alpha: 0.1),
      borderRadius: BorderRadius.circular(8),
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(8),
        child: Container(
          padding: const EdgeInsets.all(8),
          child: Icon(
            icon,
            size: 16,
            color: isPrimary 
                ? Colors.white
                : Theme.of(context).colorScheme.primary,
          ),
        ),
      ),
    );
  }
}

class _CompactDoctorCardShimmer extends StatelessWidget {
  const _CompactDoctorCardShimmer();

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 4),
      child: Padding(
        padding: const EdgeInsets.all(12),
        child: Row(
          children: [
            const CircleAvatar(
              radius: 24,
              backgroundColor: Colors.white,
            ),
            const SizedBox(width: 12),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Container(
                    height: 14,
                    width: 120,
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(7),
                    ),
                  ),
                  const SizedBox(height: 6),
                  Container(
                    height: 12,
                    width: 80,
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(6),
                    ),
                  ),
                  const SizedBox(height: 6),
                  Container(
                    height: 10,
                    width: 100,
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(5),
                    ),
                  ),
                ],
              ),
            ),
            Row(
              children: [
                Container(
                  width: 32,
                  height: 32,
                  decoration: BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(8),
                  ),
                ),
                const SizedBox(width: 8),
                Container(
                  width: 32,
                  height: 32,
                  decoration: BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(8),
                  ),
                ),
                const SizedBox(width: 8),
                Container(
                  width: 32,
                  height: 32,
                  decoration: BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(8),
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }
}