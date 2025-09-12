import 'package:flutter/material.dart';

class TreatmentPlansScreen extends StatelessWidget {
  final String? selectedPlanId;
  
  const TreatmentPlansScreen({
    super.key,
    this.selectedPlanId,
  });

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(selectedPlanId != null ? 'Plan Details' : 'Treatment Plans'),
      ),
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(
              Icons.medical_services_outlined,
              size: 80,
              color: Theme.of(context).colorScheme.primary,
            ),
            const SizedBox(height: 16),
            Text(
              selectedPlanId != null ? 'Plan Details' : 'Treatment Plans',
              style: Theme.of(context).textTheme.headlineSmall,
            ),
            if (selectedPlanId != null) ...[
              const SizedBox(height: 8),
              Text(
                'Plan ID: $selectedPlanId',
                style: Theme.of(context).textTheme.bodyMedium,
              ),
            ],
            const SizedBox(height: 16),
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 32),
              child: Text(
                selectedPlanId != null
                    ? 'This screen will show detailed treatment plan information, costs, timeline, and options to accept or request modifications.'
                    : 'This screen will display all treatment plans received from doctors, with options to view details, compare, and make decisions.',
                textAlign: TextAlign.center,
              ),
            ),
          ],
        ),
      ),
    );
  }
}