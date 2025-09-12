import 'package:flutter/material.dart';
import 'package:shimmer/shimmer.dart';
import '../models/home_dto.dart';
import '../../../core/widgets/optimized_cached_image.dart';

class BeforeAfterGallery extends StatelessWidget {
  final List<BeforeAfterCase> cases;
  final bool isLoading;

  const BeforeAfterGallery({
    required this.cases,
    this.isLoading = false,
    super.key,
  });

  @override
  Widget build(BuildContext context) {
    if (isLoading) {
      return _buildShimmerLoader(context);
    }

    if (cases.isEmpty) return const SizedBox.shrink();

    return RepaintBoundary(
      child: Semantics(
        label: 'Before and after gallery section',
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              child: Row(
                children: [
                  Text(
                    'Before & After Gallery',
                    style: Theme.of(context).textTheme.titleLarge?.copyWith(
                          fontWeight: FontWeight.bold,
                        ),
                  ),
                  const Spacer(),
                  Semantics(
                    label: 'View all before and after cases',
                    button: true,
                    child: TextButton(
                      onPressed: () {
                        // TODO: Navigate to full gallery
                      },
                      child: const Text('View All'),
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 12),
            Semantics(
              label:
                  'Before and after cases horizontal list, ${cases.length} cases',
              child: Directionality(
                textDirection: _getScrollDirection(context),
                child: SizedBox(
                  height: 320,
                  child: RepaintBoundary(
                    child: ListView.builder(
                      scrollDirection: Axis.horizontal,
                      padding: const EdgeInsets.symmetric(horizontal: 16),
                      physics: const BouncingScrollPhysics(),
                      itemCount: cases.length,
                      itemBuilder: (context, index) {
                        final caseItem = cases[index];
                        return RepaintBoundary(
                          child: Container(
                            width: 280,
                            margin: const EdgeInsets.only(right: 16),
                            child: BeforeAfterCard(caseItem: caseItem),
                          ),
                        );
                      },
                    ),
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildShimmerLoader(BuildContext context) {
    return RepaintBoundary(
      child: Semantics(
        label: 'Loading before and after gallery',
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
                      width: 180,
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
                height: 320,
                child: RepaintBoundary(
                  child: ListView.builder(
                    scrollDirection: Axis.horizontal,
                    padding: const EdgeInsets.symmetric(horizontal: 16),
                    physics: const BouncingScrollPhysics(),
                    itemCount: 3,
                    itemBuilder: (context, index) {
                      return RepaintBoundary(
                        child: Container(
                          width: 280,
                          margin: const EdgeInsets.only(right: 16),
                          child: Shimmer.fromColors(
                            baseColor: Colors.grey.shade300,
                            highlightColor: Colors.grey.shade100,
                            child: const BeforeAfterCardShimmer(),
                          ),
                        ),
                      );
                    },
                  ),
                ),
              ),
            ),
          ],
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

class BeforeAfterCard extends StatelessWidget {
  final BeforeAfterCase caseItem;

  const BeforeAfterCard({
    required this.caseItem,
    super.key,
  });

  @override
  Widget build(BuildContext context) {
    return RepaintBoundary(
      child: Semantics(
        label:
            'Before and after case: ${caseItem.title}, ${caseItem.doctorName}${caseItem.duration.isNotEmpty ? ", completed in ${caseItem.duration}" : ""}',
        button: true,
        child: Card(
          clipBehavior: Clip.antiAlias,
          elevation: 4,
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(16),
          ),
          child: InkWell(
            onTap: () {
              _showBeforeAfterViewer(context, caseItem);
            },
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // Before/After Images
                RepaintBoundary(
                  child: Container(
                    height: 200,
                    child: Row(
                      children: [
                        // Before Image
                        Expanded(
                          child: Container(
                            decoration: const BoxDecoration(
                              border: Border(
                                right:
                                    BorderSide(color: Colors.white, width: 2),
                              ),
                            ),
                            child: Stack(
                              children: [
                                OptimizedCachedImage(
                                  imageUrl: caseItem.beforeImageUrl,
                                  width: double.infinity,
                                  height: double.infinity,
                                  fit: BoxFit.cover,
                                  semanticLabel:
                                      'Before image for ${caseItem.doctorName}',
                                  errorWidget: Container(
                                    color: Theme.of(context)
                                        .colorScheme
                                        .surfaceVariant,
                                    child: Icon(
                                      Icons.image,
                                      color:
                                          Theme.of(context).colorScheme.outline,
                                    ),
                                  ),
                                ),
                                Positioned(
                                  bottom: 8,
                                  left: 8,
                                  child: Semantics(
                                    label: 'Before treatment image',
                                    child: Container(
                                      padding: const EdgeInsets.symmetric(
                                          horizontal: 6, vertical: 2),
                                      decoration: BoxDecoration(
                                        color:
                                            Colors.black.withValues(alpha: 0.7),
                                        borderRadius: BorderRadius.circular(4),
                                      ),
                                      child: const Text(
                                        'BEFORE',
                                        style: TextStyle(
                                          color: Colors.white,
                                          fontSize: 10,
                                          fontWeight: FontWeight.bold,
                                        ),
                                      ),
                                    ),
                                  ),
                                ),
                              ],
                            ),
                          ),
                        ),

                        // After Image
                        Expanded(
                          child: Stack(
                            children: [
                              OptimizedCachedImage(
                                imageUrl: caseItem.afterImageUrl,
                                width: double.infinity,
                                height: double.infinity,
                                fit: BoxFit.cover,
                                semanticLabel:
                                    'After image for ${caseItem.doctorName}',
                                errorWidget: Container(
                                  color: Theme.of(context)
                                      .colorScheme
                                      .surfaceVariant,
                                  child: Icon(
                                    Icons.image,
                                    color:
                                        Theme.of(context).colorScheme.outline,
                                  ),
                                ),
                              ),
                              Positioned(
                                bottom: 8,
                                right: 8,
                                child: Semantics(
                                  label: 'After treatment image',
                                  child: Container(
                                    padding: const EdgeInsets.symmetric(
                                        horizontal: 6, vertical: 2),
                                    decoration: BoxDecoration(
                                      color:
                                          Colors.green.withValues(alpha: 0.8),
                                      borderRadius: BorderRadius.circular(4),
                                    ),
                                    child: const Text(
                                      'AFTER',
                                      style: TextStyle(
                                        color: Colors.white,
                                        fontSize: 10,
                                        fontWeight: FontWeight.bold,
                                      ),
                                    ),
                                  ),
                                ),
                              ),
                            ],
                          ),
                        ),
                      ],
                    ),
                  ),
                ),

                // Case Info
                Expanded(
                  child: Padding(
                    padding: const EdgeInsets.all(16),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        // Title
                        Text(
                          caseItem.title,
                          style:
                              Theme.of(context).textTheme.titleMedium?.copyWith(
                                    fontWeight: FontWeight.bold,
                                  ),
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                        ),
                        const SizedBox(height: 2),

                        // Doctor Name
                        Text(
                          caseItem.doctorName,
                          style: Theme.of(context)
                              .textTheme
                              .bodyMedium
                              ?.copyWith(
                                color: Theme.of(context).colorScheme.primary,
                                fontWeight: FontWeight.w600,
                              ),
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                        ),

                        const SizedBox(height: 4),

                        // Description
                        if (caseItem.description.isNotEmpty) ...[
                          Flexible(
                            child: Text(
                              caseItem.description,
                              style: Theme.of(context)
                                  .textTheme
                                  .bodySmall
                                  ?.copyWith(
                                    color: Theme.of(context)
                                        .colorScheme
                                        .onSurface
                                        .withValues(alpha: 0.7),
                                  ),
                              maxLines: 1,
                              overflow: TextOverflow.ellipsis,
                            ),
                          ),
                          const SizedBox(height: 4),
                        ],

                        const Spacer(),

                        // Duration and engagement
                        Row(
                          children: [
                            if (caseItem.duration.isNotEmpty) ...[
                              Icon(
                                Icons.schedule,
                                size: 14,
                                color: Theme.of(context).colorScheme.outline,
                              ),
                              const SizedBox(width: 4),
                              Text(
                                caseItem.duration,
                                style: Theme.of(context)
                                    .textTheme
                                    .bodySmall
                                    ?.copyWith(
                                      color:
                                          Theme.of(context).colorScheme.outline,
                                    ),
                              ),
                              const Spacer(),
                            ],
                            Icon(
                              Icons.favorite_border,
                              size: 14,
                              color: Theme.of(context).colorScheme.outline,
                            ),
                            const SizedBox(width: 4),
                            Text(
                              '${caseItem.likes}',
                              style: Theme.of(context)
                                  .textTheme
                                  .bodySmall
                                  ?.copyWith(
                                    color:
                                        Theme.of(context).colorScheme.outline,
                                  ),
                            ),
                          ],
                        ),
                      ],
                    ),
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }

  void _showBeforeAfterViewer(BuildContext context, BeforeAfterCase caseItem) {
    showDialog(
      context: context,
      barrierColor: Colors.black87,
      builder: (context) => BeforeAfterViewer(caseItem: caseItem),
    );
  }
}

class BeforeAfterViewer extends StatefulWidget {
  final BeforeAfterCase caseItem;

  const BeforeAfterViewer({
    required this.caseItem,
    super.key,
  });

  @override
  State<BeforeAfterViewer> createState() => _BeforeAfterViewerState();
}

class _BeforeAfterViewerState extends State<BeforeAfterViewer> {
  bool _showAfter = false;

  @override
  Widget build(BuildContext context) {
    return Semantics(
      label: 'Before and after image viewer for ${widget.caseItem.doctorName}',
      child: Scaffold(
        backgroundColor: Colors.black,
        appBar: AppBar(
          backgroundColor: Colors.transparent,
          elevation: 0,
          leading: Semantics(
            label: 'Close before and after viewer',
            button: true,
            child: IconButton(
              icon: const Icon(Icons.close, color: Colors.white),
              onPressed: () => Navigator.of(context).pop(),
            ),
          ),
          title: Text(
            widget.caseItem.title,
            style: const TextStyle(color: Colors.white),
          ),
          centerTitle: true,
        ),
        body: RepaintBoundary(
          child: Column(
            children: [
              Expanded(
                child: Semantics(
                  label:
                      'Tap to switch between before and after images. Currently showing ${_showAfter ? "after" : "before"} treatment image',
                  button: true,
                  child: GestureDetector(
                    onTap: () {
                      setState(() {
                        _showAfter = !_showAfter;
                      });
                    },
                    child: Stack(
                      children: [
                        // Image
                        Center(
                          child: RepaintBoundary(
                            child: AnimatedSwitcher(
                              duration: const Duration(milliseconds: 300),
                              child: OptimizedCachedImage(
                                key: ValueKey(_showAfter),
                                imageUrl: _showAfter
                                    ? widget.caseItem.afterImageUrl
                                    : widget.caseItem.beforeImageUrl,
                                fit: BoxFit.contain,
                                semanticLabel:
                                    '${_showAfter ? "After" : "Before"} treatment image for ${widget.caseItem.doctorName}',
                                errorWidget: Container(
                                  width: double.infinity,
                                  color: Colors.grey[900],
                                  child: const Icon(
                                    Icons.image,
                                    size: 64,
                                    color: Colors.white54,
                                  ),
                                ),
                              ),
                            ),
                          ),
                        ),

                        // Label
                        Positioned(
                          top: 20,
                          left: 20,
                          child: Semantics(
                            label:
                                '${_showAfter ? "After" : "Before"} treatment image',
                            child: Container(
                              padding: const EdgeInsets.symmetric(
                                  horizontal: 12, vertical: 6),
                              decoration: BoxDecoration(
                                color: _showAfter
                                    ? Colors.green.withValues(alpha: 0.8)
                                    : Colors.black.withValues(alpha: 0.7),
                                borderRadius: BorderRadius.circular(8),
                              ),
                              child: Text(
                                _showAfter ? 'AFTER' : 'BEFORE',
                                style: const TextStyle(
                                  color: Colors.white,
                                  fontSize: 14,
                                  fontWeight: FontWeight.bold,
                                ),
                              ),
                            ),
                          ),
                        ),

                        // Tap instruction
                        Positioned(
                          bottom: 100,
                          left: 0,
                          right: 0,
                          child: Center(
                            child: Semantics(
                              label:
                                  'Tap anywhere on the image to compare before and after',
                              child: Container(
                                padding: const EdgeInsets.symmetric(
                                    horizontal: 16, vertical: 8),
                                decoration: BoxDecoration(
                                  color: Colors.black.withValues(alpha: 0.7),
                                  borderRadius: BorderRadius.circular(20),
                                ),
                                child: const Text(
                                  'Tap to compare',
                                  style: TextStyle(
                                    color: Colors.white,
                                    fontSize: 14,
                                  ),
                                ),
                              ),
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
              ),

              // Case Details
              Container(
                padding: const EdgeInsets.all(24),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      widget.caseItem.doctorName,
                      style:
                          Theme.of(context).textTheme.headlineSmall?.copyWith(
                                color: Colors.white,
                                fontWeight: FontWeight.bold,
                              ),
                    ),
                    if (widget.caseItem.description.isNotEmpty) ...[
                      const SizedBox(height: 8),
                      Text(
                        widget.caseItem.description,
                        style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                              color: Colors.white70,
                            ),
                      ),
                    ],
                    if (widget.caseItem.duration.isNotEmpty) ...[
                      const SizedBox(height: 16),
                      Semantics(
                        label: 'Treatment duration ${widget.caseItem.duration}',
                        child: Row(
                          children: [
                            const Icon(Icons.schedule,
                                color: Colors.white70, size: 16),
                            const SizedBox(width: 8),
                            Text(
                              'Treatment duration: ${widget.caseItem.duration}',
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

class BeforeAfterCardShimmer extends StatelessWidget {
  const BeforeAfterCardShimmer({super.key});

  @override
  Widget build(BuildContext context) {
    return Card(
      clipBehavior: Clip.antiAlias,
      elevation: 4,
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(16),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Before/After Images placeholder
          Container(
            height: 200,
            child: Row(
              children: [
                // Before Image placeholder
                Expanded(
                  child: Container(
                    decoration: const BoxDecoration(
                      border: Border(
                        right: BorderSide(color: Colors.white, width: 2),
                      ),
                    ),
                    child: Container(
                      color: Colors.white,
                    ),
                  ),
                ),

                // After Image placeholder
                Expanded(
                  child: Container(
                    color: Colors.white,
                  ),
                ),
              ],
            ),
          ),

          // Case Info placeholder
          Expanded(
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Title placeholder
                  Container(
                    height: 16,
                    width: 150,
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(8),
                    ),
                  ),
                  const SizedBox(height: 8),

                  // Procedure placeholder
                  Container(
                    height: 14,
                    width: 120,
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(7),
                    ),
                  ),
                  const SizedBox(height: 12),

                  // Description placeholder
                  Container(
                    height: 12,
                    width: double.infinity,
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(6),
                    ),
                  ),
                  const SizedBox(height: 4),
                  Container(
                    height: 12,
                    width: 180,
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(6),
                    ),
                  ),

                  const Spacer(),

                  // Duration placeholder
                  Row(
                    children: [
                      Container(
                        height: 12,
                        width: 80,
                        decoration: BoxDecoration(
                          color: Colors.white,
                          borderRadius: BorderRadius.circular(6),
                        ),
                      ),
                      const Spacer(),
                      Container(
                        height: 12,
                        width: 60,
                        decoration: BoxDecoration(
                          color: Colors.white,
                          borderRadius: BorderRadius.circular(6),
                        ),
                      ),
                    ],
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }
}
