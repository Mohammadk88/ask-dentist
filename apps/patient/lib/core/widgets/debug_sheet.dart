import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:connectivity_plus/connectivity_plus.dart';
import '../env/env_loader.dart';
import '../net/network_guard.dart';
import '../../features/home/repositories/home_repository.dart';

class DebugSheet extends ConsumerStatefulWidget {
  const DebugSheet({super.key});

  @override
  ConsumerState<DebugSheet> createState() => _DebugSheetState();
}

class _DebugSheetState extends ConsumerState<DebugSheet> {
  Map<String, dynamic>? _healthData;
  ConnectivityResult? _connectivity;
  String? _lastError;
  bool _isLoading = false;

  @override
  void initState() {
    super.initState();
    _checkConnectivity();
    _testHealth();
  }

  Future<void> _checkConnectivity() async {
    try {
      final result = await Connectivity().checkConnectivity();
      if (mounted) {
        setState(() {
          _connectivity = result.first;
        });
      }
    } catch (e) {
      if (mounted) {
        setState(() {
          _lastError = 'Connectivity check failed: $e';
        });
      }
    }
  }

  Future<void> _testHealth() async {
    setState(() {
      _isLoading = true;
      _lastError = null;
    });

    try {
      final result = await ref.read(connectivityTestProvider.future);
      if (mounted) {
        setState(() {
          _healthData = result;
          _isLoading = false;
        });
      }
    } catch (e) {
      if (mounted) {
        setState(() {
          _lastError = e.toString();
          _isLoading = false;
        });
      }
    }
  }

  void _copyLogs() {
    final logs = _generateLogData();
    Clipboard.setData(ClipboardData(text: logs));
    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(content: Text('Debug logs copied to clipboard')),
    );
  }

  String _generateLogData() {
    final buffer = StringBuffer();
    buffer.writeln('=== Ask.Dentist Patient App Debug Info ===');
    buffer.writeln('Timestamp: ${DateTime.now().toIso8601String()}');
    buffer.writeln('');

    buffer.writeln('API Configuration:');
    buffer.writeln('  Base URL: ${EnvLoader.getApiBaseUrl()}');
    buffer.writeln('  Debug Mode: ${EnvLoader.getEnvVar('DEBUG') ?? 'true'}');
    buffer.writeln('');

    buffer.writeln('Connectivity:');
    buffer.writeln('  Status: ${_connectivity?.name ?? 'Unknown'}');
    buffer.writeln('');

    buffer.writeln('Health Check:');
    if (_healthData != null) {
      buffer.writeln('  Success: ${_healthData!['success']}');
      buffer.writeln('  Latency: ${_healthData!['latency']}ms');
      if (_healthData!['data'] != null) {
        buffer.writeln('  Response: ${_healthData!['data']}');
      }
      if (_healthData!['error'] != null) {
        buffer.writeln('  Error: ${_healthData!['error']}');
      }
    } else {
      buffer.writeln('  Not tested yet');
    }
    buffer.writeln('');

    if (_lastError != null) {
      buffer.writeln('Last Error:');
      buffer.writeln('  $_lastError');
    }

    return buffer.toString();
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      height: MediaQuery.of(context).size.height * 0.8,
      padding: const EdgeInsets.all(16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Header
          Row(
            children: [
              const Icon(Icons.bug_report, color: Colors.orange),
              const SizedBox(width: 8),
              const Text(
                'Debug Information',
                style: TextStyle(
                  fontSize: 18,
                  fontWeight: FontWeight.bold,
                ),
              ),
              const Spacer(),
              IconButton(
                onPressed: () => Navigator.of(context).pop(),
                icon: const Icon(Icons.close),
              ),
            ],
          ),
          const Divider(),

          // Debug Information
          Expanded(
            child: SingleChildScrollView(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  _buildSection(
                    'API Configuration',
                    [
                      'Base URL: ${EnvLoader.getApiBaseUrl()}',
                      'Debug Mode: ${EnvLoader.getEnvVar('DEBUG') ?? 'true'}',
                    ],
                  ),

                  _buildSection(
                    'Connectivity Status',
                    [
                      'Current: ${_connectivity?.name ?? 'Checking...'}',
                      'Last checked: ${DateTime.now().toString().substring(11, 19)}',
                    ],
                    action: TextButton.icon(
                      onPressed: _checkConnectivity,
                      icon: const Icon(Icons.refresh),
                      label: const Text('Refresh'),
                    ),
                  ),

                  _buildSection(
                    'API Health Check',
                    _buildHealthInfo(),
                    action: TextButton.icon(
                      onPressed: _isLoading ? null : _testHealth,
                      icon: _isLoading
                          ? const SizedBox(
                              width: 16,
                              height: 16,
                              child: CircularProgressIndicator(strokeWidth: 2),
                            )
                          : const Icon(Icons.health_and_safety),
                      label: Text(_isLoading ? 'Testing...' : 'Test Health'),
                    ),
                  ),

                  if (_lastError != null)
                    _buildSection(
                      'Last Error',
                      [_lastError!],
                      isError: true,
                    ),
                ],
              ),
            ),
          ),

          // Actions
          const Divider(),
          Row(
            children: [
              Expanded(
                child: ElevatedButton.icon(
                  onPressed: _copyLogs,
                  icon: const Icon(Icons.copy),
                  label: const Text('Copy Logs'),
                ),
              ),
              const SizedBox(width: 16),
              Expanded(
                child: ElevatedButton.icon(
                  onPressed: () {
                    _testHealth();
                    _checkConnectivity();
                  },
                  icon: const Icon(Icons.refresh),
                  label: const Text('Refresh All'),
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildSection(
    String title,
    List<String> items, {
    Widget? action,
    bool isError = false,
  }) {
    return Card(
      margin: const EdgeInsets.only(bottom: 16),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                Text(
                  title,
                  style: TextStyle(
                    fontWeight: FontWeight.bold,
                    color: isError ? Colors.red : null,
                  ),
                ),
                if (action != null) ...[
                  const Spacer(),
                  action,
                ],
              ],
            ),
            const SizedBox(height: 8),
            ...items.map(
              (item) => Padding(
                padding: const EdgeInsets.only(bottom: 4),
                child: Text(
                  item,
                  style: TextStyle(
                    fontFamily: 'monospace',
                    fontSize: 12,
                    color: isError ? Colors.red : Colors.grey[600],
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  List<String> _buildHealthInfo() {
    if (_healthData == null) {
      return ['Not tested yet'];
    }

    final info = <String>[];
    info.add('Success: ${_healthData!['success']}');
    info.add('Latency: ${_healthData!['latency']}ms');

    if (_healthData!['data'] != null) {
      final data = _healthData!['data'];
      if (data is Map) {
        info.add('App: ${data['app'] ?? 'unknown'}');
        info.add('Environment: ${data['env'] ?? 'unknown'}');
        info.add('Response Time: ${data['response_time_ms'] ?? 'unknown'}ms');
      }
    }

    if (_healthData!['error'] != null) {
      info.add('Error: ${_healthData!['error']}');
    }

    return info;
  }
}

// Helper function to show debug sheet
void showDebugSheet(BuildContext context) {
  showModalBottomSheet(
    context: context,
    isScrollControlled: true,
    shape: const RoundedRectangleBorder(
      borderRadius: BorderRadius.vertical(top: Radius.circular(16)),
    ),
    builder: (context) => const DebugSheet(),
  );
}
