import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:url_launcher/url_launcher.dart';
import '../../../core/models/treatment_request.dart';
import '../../inbox/providers/inbox_provider.dart';

class TreatmentDetailsScreen extends ConsumerWidget {
  final String treatmentId;

  const TreatmentDetailsScreen({
    super.key,
    required this.treatmentId,
  });

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final inboxState = ref.watch(inboxProvider);
    final request = inboxState.treatmentRequests
        .where((r) => r.id == treatmentId)
        .firstOrNull;

    if (request == null) {
      return Scaffold(
        appBar: AppBar(title: const Text('Treatment Request')),
        body: const Center(
          child: Text('Treatment request not found'),
        ),
      );
    }

    return Scaffold(
      appBar: AppBar(
        title: const Text('Treatment Details'),
        actions: [
          IconButton(
            icon: const Icon(Icons.phone),
            onPressed: () => _showContactOptions(context, request),
          ),
          IconButton(
            icon: const Icon(Icons.chat),
            onPressed: () => _showChatPlaceholder(context),
          ),
          PopupMenuButton(
            itemBuilder: (context) => [
              const PopupMenuItem(
                value: 'plan_builder',
                child: Row(
                  children: [
                    Icon(Icons.architecture),
                    SizedBox(width: 8),
                    Text('Open Plan Builder'),
                  ],
                ),
              ),
              const PopupMenuItem(
                value: 'share',
                child: Row(
                  children: [
                    Icon(Icons.share),
                    SizedBox(width: 8),
                    Text('Share Request'),
                  ],
                ),
              ),
            ],
            onSelected: (value) {
              if (value == 'plan_builder') {
                _openPlanBuilder(context, request.id);
              }
            },
          ),
        ],
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            _buildPatientInfoCard(request),
            const SizedBox(height: 16),
            _buildTreatmentDetailsCard(request),
            const SizedBox(height: 16),
            _buildAttachmentsCard(request),
            const SizedBox(height: 16),
            _buildMedicalHistoryCard(request),
            const SizedBox(height: 24),
            _buildActionButtons(context, request),
          ],
        ),
      ),
    );
  }

  Widget _buildPatientInfoCard(TreatmentRequest request) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              'Patient Information',
              style: TextStyle(
                fontSize: 18,
                fontWeight: FontWeight.w600,
              ),
            ),
            const SizedBox(height: 16),
            Row(
              children: [
                CircleAvatar(
                  radius: 32,
                  backgroundImage: NetworkImage(request.patientPhoto),
                  onBackgroundImageError: (_, __) {},
                  child: request.patientPhoto.isEmpty
                      ? Text(
                          request.patientName[0],
                          style: const TextStyle(fontSize: 24),
                        )
                      : null,
                ),
                const SizedBox(width: 16),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        request.patientName,
                        style: const TextStyle(
                          fontSize: 18,
                          fontWeight: FontWeight.w600,
                        ),
                      ),
                      const SizedBox(height: 4),
                      Text(
                        'Patient ID: ${request.patientId}',
                        style: TextStyle(
                          color: Colors.grey[600],
                          fontSize: 14,
                        ),
                      ),
                      if (request.location != null) ...[
                        const SizedBox(height: 4),
                        Row(
                          children: [
                            Icon(
                              Icons.location_on,
                              size: 16,
                              color: Colors.grey[600],
                            ),
                            const SizedBox(width: 4),
                            Text(
                              request.location!,
                              style: TextStyle(
                                color: Colors.grey[600],
                                fontSize: 14,
                              ),
                            ),
                          ],
                        ),
                      ],
                    ],
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildTreatmentDetailsCard(TreatmentRequest request) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                const Text(
                  'Treatment Request',
                  style: TextStyle(
                    fontSize: 18,
                    fontWeight: FontWeight.w600,
                  ),
                ),
                _buildStatusChip(request.status),
              ],
            ),
            const SizedBox(height: 16),
            
            _buildDetailRow('Treatment Type', request.treatmentType),
            const SizedBox(height: 12),
            
            _buildDetailRow('Submitted', _formatDateTime(request.submittedAt)),
            const SizedBox(height: 12),
            
            if (request.urgencyLevel != null) ...[
              _buildDetailRow('Urgency', request.urgencyLevel!),
              const SizedBox(height: 12),
            ],
            
            if (request.responseDeadline != null) ...[
              _buildDetailRow(
                'Response Deadline', 
                _formatDateTime(request.responseDeadline!),
              ),
              const SizedBox(height: 16),
            ],
            
            const Text(
              'Description',
              style: TextStyle(
                fontWeight: FontWeight.w600,
                fontSize: 16,
              ),
            ),
            const SizedBox(height: 8),
            Text(
              request.description,
              style: TextStyle(
                color: Colors.grey[700],
                fontSize: 14,
                height: 1.5,
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildAttachmentsCard(TreatmentRequest request) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Attachments (${request.attachments.length})',
              style: const TextStyle(
                fontSize: 18,
                fontWeight: FontWeight.w600,
              ),
            ),
            const SizedBox(height: 16),
            if (request.attachments.isEmpty)
              const Text(
                'No attachments available',
                style: TextStyle(color: Colors.grey),
              )
            else
              ...request.attachments.map((attachment) => 
                _buildAttachmentItem(attachment)
              ),
          ],
        ),
      ),
    );
  }

  Widget _buildAttachmentItem(String filename) {
    IconData icon;
    if (filename.toLowerCase().contains('.jpg') || 
        filename.toLowerCase().contains('.png')) {
      icon = Icons.image;
    } else if (filename.toLowerCase().contains('.pdf')) {
      icon = Icons.picture_as_pdf;
    } else {
      icon = Icons.attach_file;
    }

    return Padding(
      padding: const EdgeInsets.only(bottom: 8),
      child: InkWell(
        onTap: () {
          // TODO: Open/download attachment
        },
        child: Row(
          children: [
            Icon(icon, color: Colors.blue),
            const SizedBox(width: 12),
            Expanded(
              child: Text(
                filename,
                style: const TextStyle(
                  color: Colors.blue,
                  decoration: TextDecoration.underline,
                ),
              ),
            ),
            const Icon(Icons.download, color: Colors.grey),
          ],
        ),
      ),
    );
  }

  Widget _buildMedicalHistoryCard(TreatmentRequest request) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              'Medical History',
              style: TextStyle(
                fontSize: 18,
                fontWeight: FontWeight.w600,
              ),
            ),
            const SizedBox(height: 16),
            if (request.medicalHistory == null || 
                request.medicalHistory!.isEmpty)
              const Text(
                'No medical history provided',
                style: TextStyle(color: Colors.grey),
              )
            else
              // In a real app, you'd iterate through medical history
              const Text(
                'Medical history details would be displayed here based on the data structure.',
                style: TextStyle(color: Colors.grey),
              ),
          ],
        ),
      ),
    );
  }

  Widget _buildActionButtons(BuildContext context, TreatmentRequest request) {
    return Row(
      children: [
        Expanded(
          child: ElevatedButton.icon(
            onPressed: () => _openPlanBuilder(context, request.id),
            icon: const Icon(Icons.architecture),
            label: const Text('Open Plan Builder'),
            style: ElevatedButton.styleFrom(
              padding: const EdgeInsets.symmetric(vertical: 12),
            ),
          ),
        ),
        const SizedBox(width: 12),
        Expanded(
          child: OutlinedButton.icon(
            onPressed: () => _showChatPlaceholder(context),
            icon: const Icon(Icons.chat),
            label: const Text('Start Chat'),
            style: OutlinedButton.styleFrom(
              padding: const EdgeInsets.symmetric(vertical: 12),
            ),
          ),
        ),
      ],
    );
  }

  Widget _buildDetailRow(String label, String value) {
    return Row(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        SizedBox(
          width: 120,
          child: Text(
            label,
            style: const TextStyle(
              fontWeight: FontWeight.w500,
              color: Colors.grey,
            ),
          ),
        ),
        Expanded(
          child: Text(
            value,
            style: const TextStyle(
              fontWeight: FontWeight.w500,
            ),
          ),
        ),
      ],
    );
  }

  Widget _buildStatusChip(TreatmentStatus status) {
    Color color;
    String text;
    
    switch (status) {
      case TreatmentStatus.newRequest:
        color = Colors.blue;
        text = 'New';
        break;
      case TreatmentStatus.pending:
        color = Colors.orange;
        text = 'Pending';
        break;
      case TreatmentStatus.inProgress:
        color = Colors.green;
        text = 'In Progress';
        break;
      case TreatmentStatus.completed:
        color = Colors.grey;
        text = 'Completed';
        break;
      case TreatmentStatus.rejected:
        color = Colors.red;
        text = 'Rejected';
        break;
    }

    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
      decoration: BoxDecoration(
        color: color.withOpacity(0.1),
        borderRadius: BorderRadius.circular(16),
      ),
      child: Text(
        text,
        style: TextStyle(
          color: color,
          fontSize: 12,
          fontWeight: FontWeight.w600,
        ),
      ),
    );
  }

  String _formatDateTime(DateTime dateTime) {
    return '${dateTime.day}/${dateTime.month}/${dateTime.year} at ${dateTime.hour}:${dateTime.minute.toString().padLeft(2, '0')}';
  }

  void _showContactOptions(BuildContext context, TreatmentRequest request) {
    showModalBottomSheet(
      context: context,
      builder: (context) => Container(
        padding: const EdgeInsets.all(16),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            const Text(
              'Contact Patient',
              style: TextStyle(
                fontSize: 18,
                fontWeight: FontWeight.w600,
              ),
            ),
            const SizedBox(height: 16),
            ListTile(
              leading: const Icon(Icons.phone),
              title: const Text('Voice Call'),
              subtitle: const Text('Start a voice call'),
              onTap: () {
                Navigator.pop(context);
                _showCallPlaceholder(context);
              },
            ),
            ListTile(
              leading: const Icon(Icons.videocam),
              title: const Text('Video Call'),
              subtitle: const Text('Start a video consultation'),
              onTap: () {
                Navigator.pop(context);
                _showCallPlaceholder(context);
              },
            ),
            ListTile(
              leading: const Icon(Icons.chat),
              title: const Text('Chat'),
              subtitle: const Text('Send a message'),
              onTap: () {
                Navigator.pop(context);
                _showChatPlaceholder(context);
              },
            ),
          ],
        ),
      ),
    );
  }

  void _showCallPlaceholder(BuildContext context) {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Call Feature'),
        content: const Text(
          'Call functionality would be integrated here using services like Agora, Twilio, or similar.',
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('OK'),
          ),
        ],
      ),
    );
  }

  void _showChatPlaceholder(BuildContext context) {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Chat Feature'),
        content: const Text(
          'Chat functionality would be integrated here with real-time messaging.',
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('OK'),
          ),
        ],
      ),
    );
  }

  void _openPlanBuilder(BuildContext context, String requestId) async {
    final url = 'https://askdentist.com/doctor/requests/$requestId/plan';
    
    try {
      final uri = Uri.parse(url);
      if (await canLaunchUrl(uri)) {
        await launchUrl(
          uri,
          mode: LaunchMode.externalApplication,
        );
      } else {
        if (context.mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(
              content: Text('Could not open plan builder'),
            ),
          );
        }
      }
    } catch (e) {
      if (context.mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('Error opening plan builder: $e'),
          ),
        );
      }
    }
  }
}