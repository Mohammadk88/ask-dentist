import 'package:flutter/material.dart';
import '../../../core/models/doctor.dart';

class DoctorInfoSection extends StatelessWidget {
  final Doctor doctor;

  const DoctorInfoSection({
    required this.doctor,
    super.key,
  });

  @override
  Widget build(BuildContext context) {
    return SingleChildScrollView(
      padding: const EdgeInsets.all(16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Bio Section
          if (doctor.bio != null) ...[
            _buildSectionTitle(context, 'About', Icons.person_outline),
            const SizedBox(height: 12),
            Text(
              doctor.bio!,
              style: Theme.of(context).textTheme.bodyLarge?.copyWith(
                height: 1.5,
              ),
            ),
            const SizedBox(height: 24),
          ],

          // Education Section
          if (doctor.education != null) ...[
            _buildSectionTitle(context, 'Education', Icons.school_outlined),
            const SizedBox(height: 12),
            Container(
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                color: Theme.of(context).colorScheme.surfaceContainerHighest.withValues(alpha: 0.5),
                borderRadius: BorderRadius.circular(12),
                border: Border.all(
                  color: Theme.of(context).colorScheme.outline.withValues(alpha: 0.2),
                ),
              ),
              child: Row(
                children: [
                  Icon(
                    Icons.school,
                    color: Theme.of(context).colorScheme.primary,
                    size: 24,
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    child: Text(
                      doctor.education!,
                      style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                        height: 1.4,
                      ),
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 24),
          ],

          // Languages Section
          if (doctor.languages != null && doctor.languages!.isNotEmpty) ...[
            _buildSectionTitle(context, 'Languages', Icons.translate_outlined),
            const SizedBox(height: 12),
            Wrap(
              spacing: 8,
              runSpacing: 8,
              children: doctor.languages!.map((language) {
                return Container(
                  padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                  decoration: BoxDecoration(
                    color: Theme.of(context).colorScheme.primaryContainer,
                    borderRadius: BorderRadius.circular(16),
                  ),
                  child: Text(
                    language,
                    style: Theme.of(context).textTheme.bodySmall?.copyWith(
                      color: Theme.of(context).colorScheme.primary,
                      fontWeight: FontWeight.w600,
                    ),
                  ),
                );
              }).toList(),
            ),
            const SizedBox(height: 24),
          ],

          // Quick Stats
          _buildSectionTitle(context, 'Quick Stats', Icons.analytics_outlined),
          const SizedBox(height: 12),
          Row(
            children: [
              Expanded(
                child: _buildStatCard(
                  context,
                  'Experience',
                  '${doctor.experienceYears}+ Years',
                  Icons.work_outline,
                  Theme.of(context).colorScheme.primary,
                ),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: _buildStatCard(
                  context,
                  'Reviews',
                  '${doctor.reviewCount}',
                  Icons.star_outline,
                  Colors.orange,
                ),
              ),
            ],
          ),
          
          const SizedBox(height: 12),
          
          Row(
            children: [
              Expanded(
                child: _buildStatCard(
                  context,
                  'Rating',
                  '${doctor.rating}/5.0',
                  Icons.thumb_up_outlined,
                  Colors.green,
                ),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: _buildStatCard(
                  context,
                  'Consultation',
                  '\$50', // TODO: Add consultation fee to doctor model
                  Icons.video_call_outlined,
                  Colors.blue,
                ),
              ),
            ],
          ),

          const SizedBox(height: 24),

          // Contact Information
          _buildSectionTitle(context, 'Contact Information', Icons.contact_phone_outlined),
          const SizedBox(height: 12),
          
          Container(
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              color: Theme.of(context).colorScheme.surfaceContainerHighest.withValues(alpha: 0.5),
              borderRadius: BorderRadius.circular(12),
              border: Border.all(
                color: Theme.of(context).colorScheme.outline.withValues(alpha: 0.2),
              ),
            ),
            child: Column(
              children: [
                _buildContactRow(
                  context,
                  Icons.location_on_outlined,
                  'Clinic',
                  'Istanbul Dental Center', // TODO: Get from clinic data
                ),
                const SizedBox(height: 12),
                _buildContactRow(
                  context,
                  Icons.medical_services_outlined,
                  'Specialty',
                  _getSpecialtyName(doctor.specialty),
                ),
                // TODO: Add consultation fee to doctor model
                const SizedBox(height: 12),
                _buildContactRow(
                  context,
                  Icons.payment_outlined,
                  'Consultation Fee',
                  '\$50',
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildSectionTitle(BuildContext context, String title, IconData icon) {
    return Row(
      children: [
        Icon(
          icon,
          size: 20,
          color: Theme.of(context).colorScheme.primary,
        ),
        const SizedBox(width: 8),
        Text(
          title,
          style: Theme.of(context).textTheme.titleLarge?.copyWith(
            fontWeight: FontWeight.bold,
          ),
        ),
      ],
    );
  }

  Widget _buildStatCard(
    BuildContext context,
    String label,
    String value,
    IconData icon,
    Color color,
  ) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: color.withValues(alpha: 0.1),
        borderRadius: BorderRadius.circular(12),
        border: Border.all(
          color: color.withValues(alpha: 0.3),
        ),
      ),
      child: Column(
        children: [
          Icon(
            icon,
            color: color,
            size: 24,
          ),
          const SizedBox(height: 8),
          Text(
            value,
            style: Theme.of(context).textTheme.titleMedium?.copyWith(
              color: color,
              fontWeight: FontWeight.bold,
            ),
            textAlign: TextAlign.center,
          ),
          const SizedBox(height: 4),
          Text(
            label,
            style: Theme.of(context).textTheme.bodySmall?.copyWith(
              color: Theme.of(context).colorScheme.onSurface.withValues(alpha: 0.7),
            ),
            textAlign: TextAlign.center,
          ),
        ],
      ),
    );
  }

  Widget _buildContactRow(
    BuildContext context,
    IconData icon,
    String label,
    String value,
  ) {
    return Row(
      children: [
        Icon(
          icon,
          size: 20,
          color: Theme.of(context).colorScheme.outline,
        ),
        const SizedBox(width: 12),
        Text(
          '$label:',
          style: Theme.of(context).textTheme.bodyMedium?.copyWith(
            fontWeight: FontWeight.w600,
            color: Theme.of(context).colorScheme.onSurface.withValues(alpha: 0.7),
          ),
        ),
        const SizedBox(width: 8),
        Expanded(
          child: Text(
            value,
            style: Theme.of(context).textTheme.bodyMedium,
          ),
        ),
      ],
    );
  }

  String _getSpecialtyName(DoctorSpecialty specialty) {
    switch (specialty) {
      case DoctorSpecialty.generalDentistry:
        return 'General Dentistry';
      case DoctorSpecialty.orthodontics:
        return 'Orthodontics';
      case DoctorSpecialty.oralSurgery:
        return 'Oral Surgery';
      case DoctorSpecialty.endodontics:
        return 'Endodontics';
      case DoctorSpecialty.periodontics:
        return 'Periodontics';
      case DoctorSpecialty.prosthodontics:
        return 'Prosthodontics';
      case DoctorSpecialty.pediatricDentistry:
        return 'Pediatric Dentistry';
      case DoctorSpecialty.cosmeticDentistry:
        return 'Cosmetic Dentistry';
    }
  }
}