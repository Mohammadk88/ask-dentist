import 'package:flutter/material.dart';
import 'package:shimmer/shimmer.dart';
import '../models/home_dto.dart';
import '../../../core/widgets/optimized_cached_image.dart';

class StoriesSection extends StatelessWidget {
  final List<StoryItem> stories;
  final bool isLoading;

  const StoriesSection({
    required this.stories,
    this.isLoading = false,
    super.key,
  });

  @override
  Widget build(BuildContext context) {
    if (isLoading) {
      return _buildShimmerLoader(context);
    }

    if (stories.isEmpty) return const SizedBox.shrink();

    return RepaintBoundary(
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 16),
            child: Row(
              children: [
                Text(
                  'Success Stories',
                  style: Theme.of(context).textTheme.titleLarge?.copyWith(
                        fontWeight: FontWeight.bold,
                      ),
                ),
                const Spacer(),
                Semantics(
                  label: 'View all stories',
                  button: true,
                  child: TextButton(
                    onPressed: () {
                      // TODO: Navigate to all stories
                    },
                    child: const Text('View All'),
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(height: 12),
          Directionality(
            textDirection: _getScrollDirection(context),
            child: SizedBox(
              height: 120,
              child: ListView.builder(
                scrollDirection: Axis.horizontal,
                padding: const EdgeInsets.symmetric(horizontal: 16),
                itemCount: stories.length,
                itemBuilder: (context, index) {
                  final story = stories[index];
                  return Container(
                    width: 90,
                    margin: const EdgeInsets.only(right: 16),
                    child: RepaintBoundary(
                      child: Semantics(
                        label:
                            '${story.isPromoted ? 'Promoted story: ' : 'Story: '}${story.ownerName}',
                        hint: 'Tap to view full story',
                        button: true,
                        child: GestureDetector(
                          onTap: () {
                            _showStoryViewer(context, stories, index);
                          },
                          child: Column(
                            children: [
                              Stack(
                                children: [
                                  Container(
                                    width: 70,
                                    height: 70,
                                    decoration: BoxDecoration(
                                      shape: BoxShape.circle,
                                      gradient: LinearGradient(
                                        begin: Alignment.topLeft,
                                        end: Alignment.bottomRight,
                                        colors: story.isPromoted
                                            ? [
                                                Colors.amber,
                                                Colors.orange,
                                              ]
                                            : [
                                                Theme.of(context)
                                                    .colorScheme
                                                    .primary,
                                                Theme.of(context)
                                                    .colorScheme
                                                    .secondary,
                                              ],
                                      ),
                                      boxShadow: [
                                        BoxShadow(
                                          color: (story.isPromoted
                                                  ? Colors.amber
                                                  : Theme.of(context)
                                                      .colorScheme
                                                      .primary)
                                              .withValues(alpha: 0.3),
                                          blurRadius: 8,
                                          offset: const Offset(0, 4),
                                        ),
                                      ],
                                    ),
                                    child: Padding(
                                      padding: const EdgeInsets.all(3),
                                      child: Container(
                                        decoration: BoxDecoration(
                                          shape: BoxShape.circle,
                                          border: Border.all(
                                              color: Colors.white, width: 2),
                                        ),
                                        child: ClipOval(
                                          child: OptimizedCachedImage(
                                            imageUrl: story.content,
                                            width: 64,
                                            height: 64,
                                            fit: BoxFit.cover,
                                            semanticLabel:
                                                '${story.ownerName} story image',
                                            errorWidget: Container(
                                              color: Theme.of(context)
                                                  .colorScheme
                                                  .surfaceVariant,
                                              child: Icon(
                                                Icons.image,
                                                color: Theme.of(context)
                                                    .colorScheme
                                                    .outline,
                                              ),
                                            ),
                                          ),
                                        ),
                                      ),
                                    ),
                                  ),
                                  // Promoted badge
                                  if (story.isPromoted)
                                    Positioned(
                                      top: 0,
                                      right: 0,
                                      child: Semantics(
                                        label: 'Promoted content',
                                        child: Container(
                                          padding: const EdgeInsets.all(2),
                                          decoration: const BoxDecoration(
                                            color: Colors.amber,
                                            shape: BoxShape.circle,
                                          ),
                                          child: const Icon(
                                            Icons.star,
                                            size: 12,
                                            color: Colors.white,
                                          ),
                                        ),
                                      ),
                                    ),
                                  // Viewed indicator
                                  if (story.isViewed)
                                    Positioned(
                                      bottom: 0,
                                      right: 0,
                                      child: Semantics(
                                        label: 'Already viewed',
                                        child: Container(
                                          padding: const EdgeInsets.all(2),
                                          decoration: const BoxDecoration(
                                            color: Colors.green,
                                            shape: BoxShape.circle,
                                          ),
                                          child: const Icon(
                                            Icons.check,
                                            size: 12,
                                            color: Colors.white,
                                          ),
                                        ),
                                      ),
                                    ),
                                ],
                              ),
                              const SizedBox(height: 8),
                              Text(
                                story.ownerName,
                                style: Theme.of(context)
                                    .textTheme
                                    .bodySmall
                                    ?.copyWith(
                                      fontWeight: FontWeight.w500,
                                    ),
                                textAlign: TextAlign.center,
                                maxLines: 2,
                                overflow: TextOverflow.ellipsis,
                              ),
                            ],
                          ),
                        ),
                      ),
                    ),
                  );
                },
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildShimmerLoader(BuildContext context) {
    return RepaintBoundary(
      child: Column(
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
                    width: 140,
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
          Directionality(
            textDirection: _getScrollDirection(context),
            child: SizedBox(
              height: 120,
              child: ListView.builder(
                scrollDirection: Axis.horizontal,
                padding: const EdgeInsets.symmetric(horizontal: 16),
                itemCount: 5,
                itemBuilder: (context, index) {
                  return Container(
                    width: 90,
                    margin: const EdgeInsets.only(right: 16),
                    child: RepaintBoundary(
                      child: Shimmer.fromColors(
                        baseColor: Colors.grey.shade300,
                        highlightColor: Colors.grey.shade100,
                        child: const _ShimmerStoryItem(),
                      ),
                    ),
                  );
                },
              ),
            ),
          ),
        ],
      ),
    );
  }

  void _showStoryViewer(
      BuildContext context, List<StoryItem> stories, int initialIndex) {
    showDialog(
      context: context,
      barrierColor: Colors.black87,
      builder: (context) => StoryViewer(
        stories: stories,
        initialIndex: initialIndex,
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

class StoryViewer extends StatefulWidget {
  final List<StoryItem> stories;
  final int initialIndex;

  const StoryViewer({
    required this.stories,
    required this.initialIndex,
    super.key,
  });

  @override
  State<StoryViewer> createState() => _StoryViewerState();
}

class _StoryViewerState extends State<StoryViewer> {
  late PageController _pageController;
  late int _currentIndex;

  @override
  void initState() {
    super.initState();
    _currentIndex = widget.initialIndex;
    _pageController = PageController(initialPage: widget.initialIndex);
  }

  @override
  void dispose() {
    _pageController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Semantics(
      label: 'Story viewer',
      child: Scaffold(
        backgroundColor: Colors.black,
        appBar: AppBar(
          backgroundColor: Colors.transparent,
          elevation: 0,
          leading: Semantics(
            label: 'Close story viewer',
            button: true,
            child: IconButton(
              icon: const Icon(Icons.close, color: Colors.white),
              onPressed: () => Navigator.of(context).pop(),
            ),
          ),
          title: Semantics(
            label: 'Story ${_currentIndex + 1} of ${widget.stories.length}',
            child: Text(
              '${_currentIndex + 1} / ${widget.stories.length}',
              style: const TextStyle(color: Colors.white),
            ),
          ),
          centerTitle: true,
        ),
        body: RepaintBoundary(
          child: PageView.builder(
            controller: _pageController,
            onPageChanged: (index) {
              setState(() {
                _currentIndex = index;
              });
            },
            itemCount: widget.stories.length,
            itemBuilder: (context, index) {
              final story = widget.stories[index];
              return RepaintBoundary(
                child: Semantics(
                  label: 'Story by ${story.ownerName}: ${story.caption}',
                  child: Column(
                    children: [
                      Expanded(
                        child: Center(
                          child: story.contentType == StoryContentType.image
                              ? OptimizedCachedImage(
                                  imageUrl: story.content,
                                  fit: BoxFit.contain,
                                  semanticLabel:
                                      'Story image by ${story.ownerName}',
                                  errorWidget: Container(
                                    width: double.infinity,
                                    color: Colors.grey[900],
                                    child: const Icon(
                                      Icons.image,
                                      size: 64,
                                      color: Colors.white54,
                                    ),
                                  ),
                                )
                              : Container(
                                  width: double.infinity,
                                  padding: const EdgeInsets.all(24),
                                  child: Center(
                                    child: Text(
                                      story.content,
                                      style: Theme.of(context)
                                          .textTheme
                                          .headlineMedium
                                          ?.copyWith(
                                            color: Colors.white,
                                          ),
                                      textAlign: TextAlign.center,
                                    ),
                                  ),
                                ),
                        ),
                      ),
                      Container(
                        padding: const EdgeInsets.all(24),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Row(
                              children: [
                                OptimizedCachedImage(
                                  imageUrl: story.avatarUrl,
                                  width: 32,
                                  height: 32,
                                  fit: BoxFit.cover,
                                  semanticLabel:
                                      '${story.ownerName} profile picture',
                                  errorWidget: Container(
                                    width: 32,
                                    height: 32,
                                    decoration: BoxDecoration(
                                      color: Theme.of(context)
                                          .colorScheme
                                          .surfaceVariant,
                                      shape: BoxShape.circle,
                                    ),
                                    child: Icon(
                                      Icons.person,
                                      color:
                                          Theme.of(context).colorScheme.outline,
                                      size: 16,
                                    ),
                                  ),
                                ),
                                const SizedBox(width: 12),
                                Expanded(
                                  child: Column(
                                    crossAxisAlignment:
                                        CrossAxisAlignment.start,
                                    children: [
                                      Row(
                                        children: [
                                          Text(
                                            story.ownerName,
                                            style: Theme.of(context)
                                                .textTheme
                                                .titleSmall
                                                ?.copyWith(
                                                  color: Colors.white,
                                                  fontWeight: FontWeight.bold,
                                                ),
                                          ),
                                          if (story.isPromoted) ...[
                                            const SizedBox(width: 8),
                                            Semantics(
                                              label: 'Promoted content',
                                              child: Container(
                                                padding:
                                                    const EdgeInsets.symmetric(
                                                        horizontal: 6,
                                                        vertical: 2),
                                                decoration: BoxDecoration(
                                                  color: Colors.amber,
                                                  borderRadius:
                                                      BorderRadius.circular(4),
                                                ),
                                                child: const Text(
                                                  'Ad',
                                                  style: TextStyle(
                                                    color: Colors.black,
                                                    fontSize: 10,
                                                    fontWeight: FontWeight.bold,
                                                  ),
                                                ),
                                              ),
                                            ),
                                          ],
                                        ],
                                      ),
                                      Text(
                                        _formatTimestamp(story.timestamp),
                                        style: Theme.of(context)
                                            .textTheme
                                            .bodySmall
                                            ?.copyWith(
                                              color: Colors.white70,
                                            ),
                                      ),
                                    ],
                                  ),
                                ),
                              ],
                            ),
                            if (story.caption.isNotEmpty) ...[
                              const SizedBox(height: 12),
                              Text(
                                story.caption,
                                style: Theme.of(context)
                                    .textTheme
                                    .bodyMedium
                                    ?.copyWith(
                                      color: Colors.white70,
                                    ),
                              ),
                            ],
                          ],
                        ),
                      ),
                    ],
                  ),
                ),
              );
            },
          ),
        ),
      ),
    );
  }

  String _formatTimestamp(DateTime timestamp) {
    final now = DateTime.now();
    final difference = now.difference(timestamp);

    if (difference.inDays > 0) {
      return '${difference.inDays}d ago';
    } else if (difference.inHours > 0) {
      return '${difference.inHours}h ago';
    } else if (difference.inMinutes > 0) {
      return '${difference.inMinutes}m ago';
    } else {
      return 'Just now';
    }
  }
}

class _ShimmerStoryItem extends StatelessWidget {
  const _ShimmerStoryItem();

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Container(
          width: 70,
          height: 70,
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
