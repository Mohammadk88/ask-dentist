import 'package:flutter/material.dart';
import 'package:cached_network_image/cached_network_image.dart';

/// An optimized cached network image widget with performance and accessibility enhancements
class OptimizedCachedImage extends StatelessWidget {
  final String imageUrl;
  final double? width;
  final double? height;
  final BoxFit fit;
  final Widget? placeholder;
  final Widget? errorWidget;
  final String? semanticLabel;
  final bool excludeFromSemantics;
  final BorderRadius? borderRadius;
  final Duration fadeInDuration;
  final Duration placeholderFadeInDuration;

  const OptimizedCachedImage({
    super.key,
    required this.imageUrl,
    this.width,
    this.height,
    this.fit = BoxFit.cover,
    this.placeholder,
    this.errorWidget,
    this.semanticLabel,
    this.excludeFromSemantics = false,
    this.borderRadius,
    this.fadeInDuration = const Duration(milliseconds: 300),
    this.placeholderFadeInDuration = const Duration(milliseconds: 100),
  });

  @override
  Widget build(BuildContext context) {
    Widget imageWidget = CachedNetworkImage(
      imageUrl: imageUrl,
      width: width,
      height: height,
      fit: fit,
      fadeInDuration: fadeInDuration,
      placeholderFadeInDuration: placeholderFadeInDuration,
      placeholder: (context, url) => placeholder ?? _defaultPlaceholder(),
      errorWidget: (context, url, error) {
        print('Image loading error for $url: $error');
        return errorWidget ?? _defaultErrorWidget();
      },
      // Performance optimizations
      memCacheWidth: width?.isFinite == true ? width?.round() : null,
      memCacheHeight: height?.isFinite == true ? height?.round() : null,
      maxWidthDiskCache: 1000,
      maxHeightDiskCache: 1000,
    );

    if (borderRadius != null) {
      imageWidget = ClipRRect(
        borderRadius: borderRadius!,
        child: imageWidget,
      );
    }

    // Add accessibility support
    if (semanticLabel != null || excludeFromSemantics) {
      imageWidget = Semantics(
        label: semanticLabel,
        excludeSemantics: excludeFromSemantics,
        child: imageWidget,
      );
    }

    return imageWidget;
  }

  Widget _defaultPlaceholder() {
    return Container(
      width: width,
      height: height,
      color: Colors.grey[300],
      child: const Center(
        child: CircularProgressIndicator(
          strokeWidth: 2,
        ),
      ),
    );
  }

  Widget _defaultErrorWidget() {
    return Container(
      width: width,
      height: height,
      color: Colors.grey[200],
      child: Icon(
        Icons.broken_image,
        size: (width != null && height != null)
            ? (width! < height! ? width! * 0.4 : height! * 0.4)
            : 32,
        color: Colors.grey[400],
      ),
    );
  }
}

/// A specialized cached image for avatars with optimized settings
class CachedAvatarImage extends StatelessWidget {
  final String imageUrl;
  final double radius;
  final String? semanticLabel;
  final Widget? fallback;

  const CachedAvatarImage({
    super.key,
    required this.imageUrl,
    required this.radius,
    this.semanticLabel,
    this.fallback,
  });

  @override
  Widget build(BuildContext context) {
    return CircleAvatar(
      radius: radius,
      backgroundColor: Colors.grey[300],
      child: ClipOval(
        child: OptimizedCachedImage(
          imageUrl: imageUrl,
          width: radius * 2,
          height: radius * 2,
          fit: BoxFit.cover,
          semanticLabel: semanticLabel ?? 'Profile picture',
          errorWidget: fallback ??
              Icon(
                Icons.person,
                size: radius * 0.8,
                color: Colors.grey[600],
              ),
        ),
      ),
    );
  }
}
