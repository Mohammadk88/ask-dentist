import 'package:flutter/material.dart';

class ItineraryScreen extends StatelessWidget {
  final String? itineraryId;
  
  const ItineraryScreen({
    super.key,
    this.itineraryId,
  });

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(itineraryId != null ? 'Itinerary Details' : 'My Itinerary'),
      ),
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(
              Icons.schedule_outlined,
              size: 80,
              color: Theme.of(context).colorScheme.primary,
            ),
            const SizedBox(height: 16),
            Text(
              itineraryId != null ? 'Itinerary Details' : 'My Itinerary',
              style: Theme.of(context).textTheme.headlineSmall,
            ),
            if (itineraryId != null) ...[
              const SizedBox(height: 8),
              Text(
                'Itinerary ID: $itineraryId',
                style: Theme.of(context).textTheme.bodyMedium,
              ),
            ],
            const SizedBox(height: 16),
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 32),
              child: Text(
                itineraryId != null
                    ? 'This screen will show detailed itinerary information including appointments, travel arrangements, accommodation, and day-by-day schedule.'
                    : 'This screen will display the patient\'s complete medical tourism itinerary including appointments, travel, accommodation, and local recommendations.',
                textAlign: TextAlign.center,
              ),
            ),
          ],
        ),
      ),
    );
  }
}