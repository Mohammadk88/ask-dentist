import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:shimmer/shimmer.dart';
import '../models/home_dto.dart';
import '../../../core/widgets/optimized_cached_image.dart';

class DoctorCard extends ConsumerWidget {
  final DoctorItem doctor;
  final VoidCallback? onChatTap;
  final VoidCallback? onCallTap;
  final VoidCallback? onVideoTap;
  final VoidCallback? onFavoriteTap;
  final VoidCallback? onTap;

  const DoctorCard({
    super.key,
    required this.doctor,
    this.onChatTap,
    this.onCallTap,
    this.onVideoTap,
    this.onFavoriteTap,
    this.onTap,
  });

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    // Wrap in RepaintBoundary for performance optimization
    return RepaintBoundary(
      child: Card(
        margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
        child: InkWell(
          onTap: onTap,
          borderRadius: BorderRadius.circular(16),
          child: Semantics(
            label: 'Doctor ${doctor.name}, ${doctor.specialty}',
            hint:
                'Tap to view profile, or use action buttons to chat, call, or video call',
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Doctor Info Row
                  Row(
                    children: [
                      // Doctor Avatar with accessibility
                      Stack(
                        children: [
                          CachedAvatarImage(
                            imageUrl: doctor.avatarUrl,
                            radius: 32,
                            semanticLabel: '${doctor.name} profile picture',
                          ),
                          // Online indicator
                          if (doctor.isOnline)
                            Positioned(
                              bottom: 2,
                              right: 2,
                              child: Semantics(
                                label: 'Online indicator',
                                child: Container(
                                  width: 14,
                                  height: 14,
                                  decoration: BoxDecoration(
                                    color: Colors.green,
                                    shape: BoxShape.circle,
                                    border: Border.all(
                                      color: Colors.white,
                                      width: 2,
                                    ),
                                  ),
                                ),
                              ),
                            ),
                        ],
                      ),
                      const SizedBox(width: 16),
                      // Doctor Details
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              doctor.name,
                              style: Theme.of(context)
                                  .textTheme
                                  .titleMedium
                                  ?.copyWith(
                                    fontWeight: FontWeight.w600,
                                  ),
                              semanticsLabel: 'Doctor name: ${doctor.name}',
                            ),
                            const SizedBox(height: 4),
                            Text(
                              doctor.specialty,
                              style: Theme.of(context)
                                  .textTheme
                                  .bodyMedium
                                  ?.copyWith(
                                    color:
                                        Theme.of(context).colorScheme.primary,
                                    fontWeight: FontWeight.w500,
                                  ),
                              semanticsLabel: 'Specialty: ${doctor.specialty}',
                            ),
                            const SizedBox(height: 4),
                            Row(
                              children: [
                                Icon(
                                  Icons.location_on,
                                  size: 14,
                                  color: Colors.grey[600],
                                ),
                                const SizedBox(width: 4),
                                Expanded(
                                  child: Text(
                                    doctor.clinicName.isNotEmpty
                                        ? doctor.clinicName
                                        : 'Location',
                                    style: Theme.of(context)
                                        .textTheme
                                        .bodySmall
                                        ?.copyWith(
                                          color: Colors.grey[600],
                                        ),
                                    overflow: TextOverflow.ellipsis,
                                    semanticsLabel:
                                        'Clinic: ${doctor.clinicName}',
                                  ),
                                ),
                                const SizedBox(width: 8),
                                Icon(
                                  Icons.star,
                                  size: 14,
                                  color: Colors.amber,
                                ),
                                const SizedBox(width: 2),
                                Text(
                                  doctor.rating.toStringAsFixed(1),
                                  style: Theme.of(context)
                                      .textTheme
                                      .bodySmall
                                      ?.copyWith(
                                        fontWeight: FontWeight.w600,
                                      ),
                                  semanticsLabel:
                                      'Rating: ${doctor.rating.toStringAsFixed(1)} stars',
                                ),
                                const SizedBox(width: 2),
                                Text(
                                  '(${doctor.reviewCount})',
                                  style: Theme.of(context)
                                      .textTheme
                                      .bodySmall
                                      ?.copyWith(
                                        color: Colors.grey[600],
                                      ),
                                  semanticsLabel:
                                      '${doctor.reviewCount} reviews',
                                ),
                              ],
                            ),
                          ],
                        ),
                      ),
                      // Favorite Button with accessibility
                      Semantics(
                        label: doctor.isFavorite
                            ? 'Remove ${doctor.name} from favorites'
                            : 'Add ${doctor.name} to favorites',
                        button: true,
                        child: IconButton(
                          onPressed: onFavoriteTap,
                          icon: Icon(
                            doctor.isFavorite
                                ? Icons.favorite
                                : Icons.favorite_border,
                            color: doctor.isFavorite
                                ? Colors.red
                                : Colors.grey[600],
                          ),
                          tooltip: doctor.isFavorite
                              ? 'Remove from favorites'
                              : 'Add to favorites',
                        ),
                      ),
                    ],
                  ),

                  const SizedBox(height: 16),

                  // Experience and Services
                  Row(
                    children: [
                      _InfoChip(
                        icon: Icons.work_outline,
                        label: doctor.experience,
                        semanticLabel: 'Experience: ${doctor.experience}',
                      ),
                      const SizedBox(width: 8),
                      if (doctor.languages.isNotEmpty)
                        _InfoChip(
                          icon: Icons.language,
                          label: doctor.languages.take(2).join(', '),
                          semanticLabel:
                              'Languages: ${doctor.languages.take(2).join(', ')}',
                        ),
                    ],
                  ),

                  const SizedBox(height: 16),

                  // Action Buttons with RTL support
                  Directionality(
                    textDirection: Directionality.of(context),
                    child: Row(
                      children: [
                        Expanded(
                          child: _ActionButton(
                            icon: Icons.chat_bubble_outline,
                            label: 'Chat',
                            onTap: onChatTap,
                            isPrimary: false,
                            semanticLabel: 'Start chat with ${doctor.name}',
                          ),
                        ),
                        const SizedBox(width: 8),
                        Expanded(
                          child: _ActionButton(
                            icon: Icons.phone,
                            label: 'Call',
                            onTap: onCallTap,
                            isPrimary: false,
                            semanticLabel: 'Call ${doctor.name}',
                          ),
                        ),
                        const SizedBox(width: 8),
                        Expanded(
                          child: _ActionButton(
                            icon: Icons.videocam,
                            label: 'Video',
                            onTap: onVideoTap,
                            isPrimary: true,
                            semanticLabel: 'Video call ${doctor.name}',
                          ),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),
          ),
        ),
      ),
    );
  }
}

class _InfoChip extends StatelessWidget {
  final IconData icon;
  final String label;
  final String? semanticLabel;

  const _InfoChip({
    required this.icon,
    required this.label,
    this.semanticLabel,
  });

  @override
  Widget build(BuildContext context) {
    return Semantics(
      label: semanticLabel ?? label,
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
        decoration: BoxDecoration(
          color: Theme.of(context).colorScheme.primary.withValues(alpha: 0.1),
          borderRadius: BorderRadius.circular(12),
        ),
        child: Row(
          mainAxisSize: MainAxisSize.min,
          children: [
            Icon(
              icon,
              size: 14,
              color: Theme.of(context).colorScheme.primary,
            ),
            const SizedBox(width: 4),
            Text(
              label,
              style: TextStyle(
                fontSize: 12,
                color: Theme.of(context).colorScheme.primary,
                fontWeight: FontWeight.w500,
              ),
            ),
          ],
        ),
      ),
    );
  }
}

class _ActionButton extends StatelessWidget {
  final IconData icon;
  final String label;
  final VoidCallback? onTap;
  final bool isPrimary;
  final String? semanticLabel;

  const _ActionButton({
    required this.icon,
    required this.label,
    this.onTap,
    this.isPrimary = false,
    this.semanticLabel,
  });

  @override
  Widget build(BuildContext context) {
    return Semantics(
      label: semanticLabel ?? '$label button',
      button: true,
      child: Material(
        color: isPrimary
            ? Theme.of(context).colorScheme.primary
            : Theme.of(context).colorScheme.primary.withValues(alpha: 0.1),
        borderRadius: BorderRadius.circular(12),
        child: InkWell(
          onTap: onTap,
          borderRadius: BorderRadius.circular(12),
          child: Container(
            padding: const EdgeInsets.symmetric(vertical: 12),
            child: Column(
              children: [
                Icon(
                  icon,
                  size: 20,
                  color: isPrimary
                      ? Colors.white
                      : Theme.of(context).colorScheme.primary,
                ),
                const SizedBox(height: 4),
                Text(
                  label,
                  style: TextStyle(
                    fontSize: 12,
                    fontWeight: FontWeight.w500,
                    color: isPrimary
                        ? Colors.white
                        : Theme.of(context).colorScheme.primary,
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

class DoctorCardShimmer extends StatelessWidget {
  const DoctorCardShimmer({super.key});

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
      child: Shimmer.fromColors(
        baseColor: Colors.grey.shade300,
        highlightColor: Colors.grey.shade100,
        child: Padding(
          padding: const EdgeInsets.all(16),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                children: [
                  const CircleAvatar(
                    radius: 32,
                    backgroundColor: Colors.white,
                  ),
                  const SizedBox(width: 16),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Container(
                          height: 16,
                          width: 140,
                          decoration: BoxDecoration(
                            color: Colors.white,
                            borderRadius: BorderRadius.circular(8),
                          ),
                        ),
                        const SizedBox(height: 8),
                        Container(
                          height: 14,
                          width: 100,
                          decoration: BoxDecoration(
                            color: Colors.white,
                            borderRadius: BorderRadius.circular(7),
                          ),
                        ),
                        const SizedBox(height: 8),
                        Container(
                          height: 12,
                          width: 80,
                          decoration: BoxDecoration(
                            color: Colors.white,
                            borderRadius: BorderRadius.circular(6),
                          ),
                        ),
                      ],
                    ),
                  ),
                  Container(
                    width: 40,
                    height: 40,
                    decoration: const BoxDecoration(
                      color: Colors.white,
                      shape: BoxShape.circle,
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 16),
              Row(
                children: [
                  Container(
                    height: 24,
                    width: 80,
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(12),
                    ),
                  ),
                  const SizedBox(width: 8),
                  Container(
                    height: 24,
                    width: 60,
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(12),
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 16),
              Row(
                children: [
                  Expanded(
                    child: Container(
                      height: 48,
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(12),
                      ),
                    ),
                  ),
                  const SizedBox(width: 8),
                  Expanded(
                    child: Container(
                      height: 48,
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(12),
                      ),
                    ),
                  ),
                  const SizedBox(width: 8),
                  Expanded(
                    child: Container(
                      height: 48,
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(12),
                      ),
                    ),
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
