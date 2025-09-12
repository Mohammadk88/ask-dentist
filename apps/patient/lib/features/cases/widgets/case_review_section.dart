import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'dart:io';
import '../providers/case_submission_provider.dart';
import '../../../core/cases/models/patient_case.dart';

class CaseReviewSection extends ConsumerWidget {
  final VoidCallback onSubmit;
  
  const CaseReviewSection({
    required this.onSubmit,
    super.key,
  });

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final caseState = ref.watch(caseSubmissionProvider);
    final isSubmitting = caseState.isSubmitting;
    
    return Padding(
      padding: const EdgeInsets.all(16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            'Review Your Case',
            style: Theme.of(context).textTheme.headlineSmall?.copyWith(
              fontWeight: FontWeight.bold,
            ),
          ),
          const SizedBox(height: 8),
          Text(
            'Please review all information before submitting your case.',
            style: Theme.of(context).textTheme.bodyMedium?.copyWith(
              color: Theme.of(context).colorScheme.onSurface.withValues(alpha: 0.7),
            ),
          ),
          const SizedBox(height: 24),
          
          Expanded(
            child: SingleChildScrollView(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Photos Section
                  _buildSection(
                    context,
                    'Photos',
                    child: caseState.photos.isEmpty
                        ? Text(
                            'No photos added',
                            style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                              color: Theme.of(context).colorScheme.onSurface.withValues(alpha: 0.5),
                            ),
                          )
                        : SizedBox(
                            height: 100,
                            child: ListView.builder(
                              scrollDirection: Axis.horizontal,
                              itemCount: caseState.photos.length,
                              itemBuilder: (context, index) {
                                final photo = caseState.photos[index];
                                return Container(
                                  margin: const EdgeInsets.only(right: 8),
                                  width: 100,
                                  decoration: BoxDecoration(
                                    borderRadius: BorderRadius.circular(8),
                                    border: Border.all(
                                      color: Theme.of(context).colorScheme.outline.withValues(alpha: 0.3),
                                    ),
                                  ),
                                  child: ClipRRect(
                                    borderRadius: BorderRadius.circular(8),
                                    child: Image.file(
                                      File(photo.path),
                                      fit: BoxFit.cover,
                                    ),
                                  ),
                                );
                              },
                            ),
                          ),
                  ),
                  
                  // Complaints Section
                  _buildSection(
                    context,
                    'Selected Complaints',
                    child: caseState.selectedComplaints.isEmpty
                        ? Text(
                            'No complaints selected',
                            style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                              color: Theme.of(context).colorScheme.onSurface.withValues(alpha: 0.5),
                            ),
                          )
                        : Wrap(
                            spacing: 8,
                            runSpacing: 8,
                            children: caseState.selectedComplaints.map((complaint) {
                              return Chip(
                                label: Text(complaint.name),
                                backgroundColor: Theme.of(context).colorScheme.secondaryContainer,
                                labelStyle: TextStyle(
                                  color: Theme.of(context).colorScheme.onSecondaryContainer,
                                ),
                              );
                            }).toList(),
                          ),
                  ),
                  
                  // Description Section
                  _buildSection(
                    context,
                    'Description',
                    child: Text(
                      caseState.description.isEmpty ? 'No description provided' : caseState.description,
                      style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                        color: caseState.description.isEmpty 
                            ? Theme.of(context).colorScheme.onSurface.withValues(alpha: 0.5)
                            : null,
                      ),
                    ),
                  ),
                  
                  // Urgency Section
                  _buildSection(
                    context,
                    'Urgency Level',
                    child: Row(
                      children: [
                        Container(
                          padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                          decoration: BoxDecoration(
                            color: _getUrgencyColor(caseState.urgency).withValues(alpha: 0.1),
                            borderRadius: BorderRadius.circular(16),
                            border: Border.all(
                              color: _getUrgencyColor(caseState.urgency),
                            ),
                          ),
                          child: Row(
                            mainAxisSize: MainAxisSize.min,
                            children: [
                              Icon(
                                _getUrgencyIcon(caseState.urgency),
                                size: 16,
                                color: _getUrgencyColor(caseState.urgency),
                              ),
                              const SizedBox(width: 6),
                              Text(
                                _getUrgencyTitle(caseState.urgency),
                                style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                                  color: _getUrgencyColor(caseState.urgency),
                                  fontWeight: FontWeight.w600,
                                ),
                              ),
                            ],
                          ),
                        ),
                      ],
                    ),
                  ),
                  
                  // Medical History Section
                  if (caseState.medicalHistory?.isNotEmpty == true)
                    _buildSection(
                      context,
                      'Medical History',
                      child: Text(
                        caseState.medicalHistory!,
                        style: Theme.of(context).textTheme.bodyMedium,
                      ),
                    ),
                  
                  // Current Medications Section
                  if (caseState.currentMedications?.isNotEmpty == true)
                    _buildSection(
                      context,
                      'Current Medications',
                      child: Text(
                        caseState.currentMedications!,
                        style: Theme.of(context).textTheme.bodyMedium,
                      ),
                    ),
                  
                  // Allergies Section
                  if (caseState.allergies?.isNotEmpty == true)
                    _buildSection(
                      context,
                      'Allergies',
                      child: Text(
                        caseState.allergies!,
                        style: Theme.of(context).textTheme.bodyMedium,
                      ),
                    ),
                  
                  const SizedBox(height: 24),
                  
                  // Terms and Conditions
                  Card(
                    child: Padding(
                      padding: const EdgeInsets.all(16),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Row(
                            children: [
                              Icon(
                                Icons.info_outline,
                                color: Theme.of(context).colorScheme.primary,
                                size: 20,
                              ),
                              const SizedBox(width: 8),
                              Text(
                                'Important Information',
                                style: Theme.of(context).textTheme.titleMedium?.copyWith(
                                  fontWeight: FontWeight.w600,
                                  color: Theme.of(context).colorScheme.primary,
                                ),
                              ),
                            ],
                          ),
                          const SizedBox(height: 12),
                          Text(
                            '• Your case will be reviewed by qualified dental professionals\n'
                            '• You will receive a response within 24-48 hours\n'
                            '• This is not an emergency service - seek immediate care if needed\n'
                            '• All information is kept strictly confidential',
                            style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                              color: Theme.of(context).colorScheme.onSurface.withValues(alpha: 0.8),
                            ),
                          ),
                        ],
                      ),
                    ),
                  ),
                  
                  const SizedBox(height: 32),
                ],
              ),
            ),
          ),
          
          // Submit Button
          SafeArea(
            child: Container(
              width: double.infinity,
              padding: const EdgeInsets.only(top: 16),
              child: ElevatedButton(
                onPressed: _canSubmit(caseState) && !isSubmitting ? onSubmit : null,
                style: ElevatedButton.styleFrom(
                  padding: const EdgeInsets.symmetric(vertical: 16),
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(12),
                  ),
                ),
                child: isSubmitting
                    ? const SizedBox(
                        height: 20,
                        width: 20,
                        child: CircularProgressIndicator(
                          strokeWidth: 2,
                          valueColor: AlwaysStoppedAnimation<Color>(Colors.white),
                        ),
                      )
                    : Row(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          const Icon(Icons.send),
                          const SizedBox(width: 8),
                          Text(
                            'Submit Case',
                            style: Theme.of(context).textTheme.titleMedium?.copyWith(
                              color: Colors.white,
                              fontWeight: FontWeight.w600,
                            ),
                          ),
                        ],
                      ),
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildSection(BuildContext context, String title, {required Widget child}) {
    return Container(
      margin: const EdgeInsets.only(bottom: 20),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            title,
            style: Theme.of(context).textTheme.titleMedium?.copyWith(
              fontWeight: FontWeight.w600,
            ),
          ),
          const SizedBox(height: 8),
          Container(
            width: double.infinity,
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              color: Theme.of(context).colorScheme.surface,
              borderRadius: BorderRadius.circular(12),
              border: Border.all(
                color: Theme.of(context).colorScheme.outline.withValues(alpha: 0.2),
              ),
            ),
            child: child,
          ),
        ],
      ),
    );
  }

  bool _canSubmit(CaseSubmissionState state) {
    return state.description.isNotEmpty &&
           state.selectedComplaints.isNotEmpty;
  }

  Color _getUrgencyColor(CaseUrgency urgency) {
    switch (urgency) {
      case CaseUrgency.low:
        return Colors.green;
      case CaseUrgency.medium:
        return Colors.orange;
      case CaseUrgency.high:
        return Colors.red;
      case CaseUrgency.emergency:
        return Colors.red.shade700;
    }
  }

  IconData _getUrgencyIcon(CaseUrgency urgency) {
    switch (urgency) {
      case CaseUrgency.low:
        return Icons.schedule;
      case CaseUrgency.medium:
        return Icons.access_time;
      case CaseUrgency.high:
        return Icons.priority_high;
      case CaseUrgency.emergency:
        return Icons.emergency;
    }
  }

  String _getUrgencyTitle(CaseUrgency urgency) {
    switch (urgency) {
      case CaseUrgency.low:
        return 'Low Priority';
      case CaseUrgency.medium:
        return 'Medium Priority';
      case CaseUrgency.high:
        return 'High Priority';
      case CaseUrgency.emergency:
        return 'Emergency';
    }
  }
}