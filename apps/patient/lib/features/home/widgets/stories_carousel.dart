import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:shimmer/shimmer.dart';
import '../models/home_dto.dart';

class StoriesCarousel extends ConsumerWidget {
  final List<StoryItem> stories;
  final bool isLoading;

  const StoriesCarousel({
    super.key,
    required this.stories,
    this.isLoading = false,
  });

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    if (isLoading) {
      return _buildShimmerLoader(context);
    }

    if (stories.isEmpty) {
      return _buildEmptyState(context);
    }

    return SizedBox(
      height: 100,
      child: Directionality(
        textDirection: _getScrollDirection(context),
        child: ListView.builder(
          scrollDirection: Axis.horizontal,
          padding: const EdgeInsets.symmetric(horizontal: 16),
          itemCount: stories.length,
          itemBuilder: (context, index) {
            final story = stories[index];
            return Padding(
              padding: const EdgeInsets.only(right: 12),
              child: _StoryItem(
                story: story,
                onTap: () => _onStoryTap(context, story),
              ),
            );
          },
        ),
      ),
    );
  }

  Widget _buildShimmerLoader(BuildContext context) {
    return SizedBox(
      height: 100,
      child: Directionality(
        textDirection: _getScrollDirection(context),
        child: ListView.builder(
          scrollDirection: Axis.horizontal,
          padding: const EdgeInsets.symmetric(horizontal: 16),
          itemCount: 5,
          itemBuilder: (context, index) {
            return Padding(
              padding: const EdgeInsets.only(right: 12),
              child: Shimmer.fromColors(
                baseColor: Colors.grey.shade300,
                highlightColor: Colors.grey.shade100,
                child: const _ShimmerStoryItem(),
              ),
            );
          },
        ),
      ),
    );
  }

  Widget _buildEmptyState(BuildContext context) {
    return Container(
      height: 100,
      padding: const EdgeInsets.symmetric(horizontal: 16),
      child: Center(
        child: Text(
          'No stories available',
          style: Theme.of(context).textTheme.bodyMedium?.copyWith(
            color: Colors.grey[600],
          ),
        ),
      ),
    );
  }

  void _onStoryTap(BuildContext context, StoryItem story) {
    // Handle story tap - navigate to story view
    showDialog(
      context: context,
      builder: (context) => Dialog(
        child: Container(
          padding: const EdgeInsets.all(20),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              ClipRRect(
                borderRadius: BorderRadius.circular(12),
                child: Image.network(
                  story.contentType == StoryContentType.image ? story.content : story.avatarUrl,
                  height: 200,
                  width: double.infinity,
                  fit: BoxFit.cover,
                  errorBuilder: (context, error, stackTrace) {
                    return Container(
                      height: 200,
                      color: Colors.grey[300],
                      child: const Icon(Icons.image_not_supported),
                    );
                  },
                ),
              ),
              const SizedBox(height: 16),
              Text(
                story.caption,
                style: Theme.of(context).textTheme.headlineSmall,
                textAlign: TextAlign.center,
              ),
              const SizedBox(height: 8),
              Text(
                story.content,
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

  /// Determines scroll direction based on locale for proper RTL support
  TextDirection _getScrollDirection(BuildContext context) {
    final locale = Localizations.localeOf(context);
    // Arabic and other RTL languages should scroll RTL
    if (locale.languageCode == 'ar' || locale.languageCode == 'he' || 
        locale.languageCode == 'fa' || locale.languageCode == 'ur') {
      return TextDirection.rtl;
    }
    return TextDirection.ltr;
  }
}

class _StoryItem extends StatelessWidget {
  final StoryItem story;
  final VoidCallback onTap;

  const _StoryItem({
    required this.story,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    final isTextStory = story.contentType == StoryContentType.text;
    
    return GestureDetector(
      onTap: onTap,
      child: Column(
        children: [
          Container(
            width: 72,
            height: 72,
            decoration: BoxDecoration(
              shape: BoxShape.circle,
              gradient: LinearGradient(
                colors: [
                  Theme.of(context).colorScheme.primary,
                  Theme.of(context).colorScheme.primary.withValues(alpha: 0.7),
                ],
                begin: Alignment.topLeft,
                end: Alignment.bottomRight,
              ),
              border: Border.all(
                color: Colors.white,
                width: 3,
              ),
            ),
            child: Container(
              margin: const EdgeInsets.all(3),
              decoration: BoxDecoration(
                shape: BoxShape.circle,
                color: isTextStory ? Theme.of(context).colorScheme.primary : null,
                image: isTextStory
                    ? null
                    : DecorationImage(
                        image: NetworkImage(story.content),
                        fit: BoxFit.cover,
                      ),
              ),
              child: isTextStory
                  ? Center(
                      child: Text(
                        story.ownerName.isNotEmpty ? story.ownerName[0].toUpperCase() : 'A',
                        style: TextStyle(
                          color: Colors.white,
                          fontSize: 24,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    )
                  : null,
            ),
          ),
          const SizedBox(height: 8),
          SizedBox(
            width: 72,
            child: Text(
              story.ownerName,
              style: Theme.of(context).textTheme.bodySmall?.copyWith(
                fontWeight: FontWeight.w500,
              ),
              textAlign: TextAlign.center,
              maxLines: 2,
              overflow: TextOverflow.ellipsis,
            ),
          ),
        ],
      ),
    );
  }
}

class _ShimmerStoryItem extends StatelessWidget {
  const _ShimmerStoryItem();

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Container(
          width: 72,
          height: 72,
          decoration: const BoxDecoration(
            shape: BoxShape.circle,
            color: Colors.white,
          ),
        ),
        const SizedBox(height: 8),
        Container(
          width: 50,
          height: 12,
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(6),
          ),
        ),
      ],
    );
  }
}

// Mock Story class for this widget (you can replace with your actual model)
class Story {
  final String id;
  final String title;
  final String imageUrl;
  final String type;
  final String? doctorId;
  final String? clinicId;
  final String description;

  const Story({
    required this.id,
    required this.title,
    required this.imageUrl,
    required this.type,
    this.doctorId,
    this.clinicId,
    required this.description,
  });
}