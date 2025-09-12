import 'package:flutter/material.dart';
import 'package:flutter/foundation.dart';

/// A development-only performance overlay that provides frame timing information
/// and shows when the 16ms frame budget is exceeded
class PerformanceDebugOverlay extends StatelessWidget {
  final Widget child;
  final bool showPerformanceOverlay;

  const PerformanceDebugOverlay({
    super.key,
    required this.child,
    this.showPerformanceOverlay = false,
  });

  @override
  Widget build(BuildContext context) {
    if (kDebugMode && showPerformanceOverlay) {
      return Stack(
        children: [
          child,
          Positioned(
            top: 50,
            right: 10,
            child: Material(
              color: Colors.black54,
              borderRadius: const BorderRadius.all(Radius.circular(8)),
              child: Padding(
                padding: const EdgeInsets.all(8),
                child: PerformanceOverlay.allEnabled(),
              ),
            ),
          ),
          const Positioned(
            top: 50,
            left: 10,
            child: _FrameBudgetIndicator(),
          ),
        ],
      );
    }
    return child;
  }
}

/// A custom indicator that shows frame budget status
class _FrameBudgetIndicator extends StatefulWidget {
  const _FrameBudgetIndicator();

  @override
  State<_FrameBudgetIndicator> createState() => _FrameBudgetIndicatorState();
}

class _FrameBudgetIndicatorState extends State<_FrameBudgetIndicator>
    with WidgetsBindingObserver {
  bool _isFrameDropped = false;
  int _frameCount = 0;
  DateTime _lastCheck = DateTime.now();

  @override
  void initState() {
    super.initState();
    if (kDebugMode) {
      WidgetsBinding.instance.addObserver(this);
      WidgetsBinding.instance.addPostFrameCallback(_onFrame);
    }
  }

  @override
  void dispose() {
    if (kDebugMode) {
      WidgetsBinding.instance.removeObserver(this);
    }
    super.dispose();
  }

  void _onFrame(Duration timestamp) {
    if (!mounted) return;
    
    final now = DateTime.now();
    final timeSinceLastCheck = now.difference(_lastCheck).inMilliseconds;
    
    // Check if frame took longer than 16ms (60fps)
    if (timeSinceLastCheck > 16) {
      setState(() {
        _isFrameDropped = true;
      });
      
      // Reset indicator after a short delay
      Future.delayed(const Duration(milliseconds: 100), () {
        if (mounted) {
          setState(() {
            _isFrameDropped = false;
          });
        }
      });
    }
    
    _frameCount++;
    _lastCheck = now;
    
    // Schedule next frame callback
    WidgetsBinding.instance.addPostFrameCallback(_onFrame);
  }

  @override
  Widget build(BuildContext context) {
    if (!kDebugMode) return const SizedBox.shrink();
    
    return Material(
      color: _isFrameDropped ? Colors.red.withValues(alpha: 0.8) : Colors.green.withValues(alpha: 0.8),
      borderRadius: const BorderRadius.all(Radius.circular(4)),
      child: Padding(
        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
        child: Text(
          _isFrameDropped ? 'FRAME DROP' : '60 FPS',
          style: const TextStyle(
            color: Colors.white,
            fontSize: 10,
            fontWeight: FontWeight.bold,
          ),
        ),
      ),
    );
  }
}