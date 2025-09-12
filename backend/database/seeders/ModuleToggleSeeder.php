<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ModuleToggle;

class ModuleToggleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            [
                'key' => 'stories',
                'enabled' => true,
                'description' => 'Patient stories and testimonials feature'
            ],
            [
                'key' => 'swiping',
                'enabled' => true,
                'description' => 'Tinder-like swiping interface for doctor selection'
            ],
            [
                'key' => 'travel_booking',
                'enabled' => false,
                'description' => 'Medical tourism and travel booking assistance'
            ],
            [
                'key' => 'reviews',
                'enabled' => true,
                'description' => 'Patient reviews and ratings system for doctors and clinics'
            ],
            [
                'key' => 'telemedicine',
                'enabled' => true,
                'description' => 'Video/voice consultation capabilities'
            ],
            [
                'key' => 'appointments',
                'enabled' => true,
                'description' => 'Appointment scheduling and management'
            ],
            [
                'key' => 'messaging',
                'enabled' => true,
                'description' => 'Real-time messaging between patients and doctors'
            ],
            [
                'key' => 'treatment_plans',
                'enabled' => true,
                'description' => 'Treatment plan creation and management'
            ],
            [
                'key' => 'file_uploads',
                'enabled' => true,
                'description' => 'File upload and attachment system'
            ],
            [
                'key' => 'notifications',
                'enabled' => true,
                'description' => 'Push notifications and email alerts'
            ],
            [
                'key' => 'analytics',
                'enabled' => false,
                'description' => 'Advanced analytics and reporting dashboard'
            ],
            [
                'key' => 'multi_clinic',
                'enabled' => false,
                'description' => 'Multi-clinic support for doctors'
            ],
            [
                'key' => 'payment_processing',
                'enabled' => false,
                'description' => 'Integrated payment processing system'
            ],
            [
                'key' => 'insurance_integration',
                'enabled' => false,
                'description' => 'Insurance provider integration'
            ]
        ];

        foreach ($modules as $module) {
            ModuleToggle::firstOrCreate(
                ['key' => $module['key']],
                [
                    'enabled' => $module['enabled'],
                    'description' => $module['description']
                ]
            );
        }

        $this->command->info('Module toggles seeded successfully');
        $this->command->info('Enabled modules: reviews, telemedicine, appointments, messaging, treatment_plans, file_uploads, notifications');
        $this->command->info('Disabled modules: travel, analytics, multi_clinic, payment_processing, insurance_integration');
    }
}
