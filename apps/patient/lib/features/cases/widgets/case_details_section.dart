import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../providers/case_submission_provider.dart';
import '../../../core/cases/models/patient_case.dart';

class CaseDetailsSection extends ConsumerStatefulWidget {
  const CaseDetailsSection({super.key});

  @override
  ConsumerState<CaseDetailsSection> createState() => _CaseDetailsSectionState();
}

class _CaseDetailsSectionState extends ConsumerState<CaseDetailsSection> {
  final _descriptionController = TextEditingController();
  final _medicalHistoryController = TextEditingController();
  final _medicationsController = TextEditingController();
  final _allergiesController = TextEditingController();

  @override
  void initState() {
    super.initState();
    final state = ref.read(caseSubmissionProvider);
    _descriptionController.text = state.description;
    _medicalHistoryController.text = state.medicalHistory ?? '';
    _medicationsController.text = state.currentMedications ?? '';
    _allergiesController.text = state.allergies ?? '';
  }

  @override
  void dispose() {
    _descriptionController.dispose();
    _medicalHistoryController.dispose();
    _medicationsController.dispose();
    _allergiesController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final caseState = ref.watch(caseSubmissionProvider);
    
    return Padding(
      padding: const EdgeInsets.all(16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            'Case Details',
            style: Theme.of(context).textTheme.headlineSmall?.copyWith(
              fontWeight: FontWeight.bold,
            ),
          ),
          const SizedBox(height: 8),
          Text(
            'Provide detailed information about your condition and medical history.',
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
                  // Description
                  Text(
                    'Description *',
                    style: Theme.of(context).textTheme.titleMedium?.copyWith(
                      fontWeight: FontWeight.w600,
                    ),
                  ),
                  const SizedBox(height: 8),
                  TextFormField(
                    controller: _descriptionController,
                    maxLines: 4,
                    decoration: const InputDecoration(
                      hintText: 'Describe your dental problem in detail...',
                      helperText: 'When did it start? How severe is the pain? Any triggers?',
                    ),
                    onChanged: (value) {
                      ref.read(caseSubmissionProvider.notifier).updateDescription(value);
                    },
                  ),
                  const SizedBox(height: 24),
                  
                  // Urgency
                  Text(
                    'Urgency Level *',
                    style: Theme.of(context).textTheme.titleMedium?.copyWith(
                      fontWeight: FontWeight.w600,
                    ),
                  ),
                  const SizedBox(height: 12),
                  ...CaseUrgency.values.map((urgency) {
                    final isSelected = caseState.urgency == urgency;
                    
                    return Container(
                      margin: const EdgeInsets.only(bottom: 8),
                      child: Card(
                        elevation: isSelected ? 4 : 1,
                        child: InkWell(
                          onTap: () {
                            ref.read(caseSubmissionProvider.notifier).updateUrgency(urgency);
                          },
                          borderRadius: BorderRadius.circular(12),
                          child: Container(
                            padding: const EdgeInsets.all(16),
                            decoration: BoxDecoration(
                              borderRadius: BorderRadius.circular(12),
                              border: isSelected
                                  ? Border.all(
                                      color: _getUrgencyColor(urgency, context),
                                      width: 2,
                                    )
                                  : null,
                              color: isSelected
                                  ? _getUrgencyColor(urgency, context).withValues(alpha: 0.1)
                                  : null,
                            ),
                            child: Row(
                              children: [
                                Container(
                                  width: 40,
                                  height: 40,
                                  decoration: BoxDecoration(
                                    color: _getUrgencyColor(urgency, context),
                                    borderRadius: BorderRadius.circular(8),
                                  ),
                                  child: Icon(
                                    _getUrgencyIcon(urgency),
                                    color: Colors.white,
                                    size: 20,
                                  ),
                                ),
                                const SizedBox(width: 16),
                                Expanded(
                                  child: Column(
                                    crossAxisAlignment: CrossAxisAlignment.start,
                                    children: [
                                      Text(
                                        _getUrgencyTitle(urgency),
                                        style: Theme.of(context).textTheme.titleMedium?.copyWith(
                                          fontWeight: FontWeight.w600,
                                          color: isSelected
                                              ? _getUrgencyColor(urgency, context)
                                              : null,
                                        ),
                                      ),
                                      const SizedBox(height: 4),
                                      Text(
                                        _getUrgencyDescription(urgency),
                                        style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                                          color: Theme.of(context).colorScheme.onSurface.withValues(alpha: 0.7),
                                        ),
                                      ),
                                    ],
                                  ),
                                ),
                                Radio<CaseUrgency>(
                                  value: urgency,
                                  groupValue: caseState.urgency,
                                  onChanged: (value) {
                                    if (value != null) {
                                      ref.read(caseSubmissionProvider.notifier).updateUrgency(value);
                                    }
                                  },
                                  activeColor: _getUrgencyColor(urgency, context),
                                ),
                              ],
                            ),
                          ),
                        ),
                      ),
                    );
                  }),
                  
                  const SizedBox(height: 24),
                  
                  // Medical History
                  Text(
                    'Medical History',
                    style: Theme.of(context).textTheme.titleMedium?.copyWith(
                      fontWeight: FontWeight.w600,
                    ),
                  ),
                  const SizedBox(height: 8),
                  TextFormField(
                    controller: _medicalHistoryController,
                    maxLines: 3,
                    decoration: const InputDecoration(
                      hintText: 'Any relevant medical conditions, surgeries, or treatments...',
                    ),
                    onChanged: (value) {
                      ref.read(caseSubmissionProvider.notifier).updateMedicalHistory(value.isEmpty ? null : value);
                    },
                  ),
                  const SizedBox(height: 16),
                  
                  // Current Medications
                  Text(
                    'Current Medications',
                    style: Theme.of(context).textTheme.titleMedium?.copyWith(
                      fontWeight: FontWeight.w600,
                    ),
                  ),
                  const SizedBox(height: 8),
                  TextFormField(
                    controller: _medicationsController,
                    maxLines: 2,
                    decoration: const InputDecoration(
                      hintText: 'List any medications you are currently taking...',
                    ),
                    onChanged: (value) {
                      ref.read(caseSubmissionProvider.notifier).updateCurrentMedications(value.isEmpty ? null : value);
                    },
                  ),
                  const SizedBox(height: 16),
                  
                  // Allergies
                  Text(
                    'Allergies',
                    style: Theme.of(context).textTheme.titleMedium?.copyWith(
                      fontWeight: FontWeight.w600,
                    ),
                  ),
                  const SizedBox(height: 8),
                  TextFormField(
                    controller: _allergiesController,
                    maxLines: 2,
                    decoration: const InputDecoration(
                      hintText: 'Any known allergies to medications or materials...',
                    ),
                    onChanged: (value) {
                      ref.read(caseSubmissionProvider.notifier).updateAllergies(value.isEmpty ? null : value);
                    },
                  ),
                  const SizedBox(height: 24),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  Color _getUrgencyColor(CaseUrgency urgency, BuildContext context) {
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

  String _getUrgencyDescription(CaseUrgency urgency) {
    switch (urgency) {
      case CaseUrgency.low:
        return 'Routine consultation, no immediate pain';
      case CaseUrgency.medium:
        return 'Mild discomfort, prefer treatment soon';
      case CaseUrgency.high:
        return 'Significant pain or concern, need treatment';
      case CaseUrgency.emergency:
        return 'Severe pain or urgent situation';
    }
  }
}