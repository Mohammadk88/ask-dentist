<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Service;
use App\Models\Pricing;
use App\Models\Story;
use App\Models\BeforeAfterCase;
use App\Infrastructure\Models\TeethReference;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🦷 Starting Ask.Dentist MVP database seeding...');

        // Call other essential seeders first
        $this->call([
            RoleSeeder::class,
            ServiceSeeder::class,
            TeethReferenceSeeder::class,
            ModuleToggleSeeder::class,
            ReviewQuestionSeeder::class,
        ]);

        DB::transaction(function () {
            // Create users with specific emails (idempotent)
            $this->createUsers();

            // Create clinic with cover
            $clinic = $this->createClinic();

            // Create services and pricing
            $this->createServicesAndPricing($clinic);

            // Create content (stories, before/after, etc.)
            $this->createContent($clinic);
        });

        $this->command->info('🎉 Ask.Dentist MVP seeding completed successfully!');
        $this->printCredentials();
    }

    /**
     * Create users with specific credentials (idempotent by email)
     */
    private function createUsers(): void
    {
        // Admin user: admin@ask.dentist / password
        $admin = User::updateOrCreate(
            ['email' => 'admin@ask.dentist'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password'),
                'phone' => '+1234567890',
                'role' => 'admin',
                'locale' => 'en',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('Admin');

        // Clinic manager
        $manager = User::updateOrCreate(
            ['email' => 'manager@ask.dentist'],
            [
                'name' => 'Clinic Manager',
                'password' => Hash::make('password'),
                'phone' => '+1234567891',
                'role' => 'clinic_manager',
                'locale' => 'en',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        $manager->assignRole('ClinicManager');

        // Doctor user: dr@ask.dentist / password
        $doctor = User::updateOrCreate(
            ['email' => 'dr@ask.dentist'],
            [
                'name' => 'Dr. Ahmed Hassan',
                'password' => Hash::make('password'),
                'phone' => '+1234567892',
                'role' => 'doctor',
                'locale' => 'ar',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        $doctor->assignRole('Doctor');

        // 5 sample patients
        $patientData = [
            ['email' => 'patient1@ask.dentist', 'name' => 'Sarah Ahmed', 'phone' => '+1234567893'],
            ['email' => 'patient2@ask.dentist', 'name' => 'Mohammed Ali', 'phone' => '+1234567894'],
            ['email' => 'patient3@ask.dentist', 'name' => 'Fatima Khan', 'phone' => '+1234567895'],
            ['email' => 'patient4@ask.dentist', 'name' => 'Omar Mahmoud', 'phone' => '+1234567896'],
            ['email' => 'patient5@ask.dentist', 'name' => 'Aisha Abdullah', 'phone' => '+1234567897'],
        ];

        foreach ($patientData as $patientInfo) {
            $patient = User::updateOrCreate(
                ['email' => $patientInfo['email']],
                [
                    'name' => $patientInfo['name'],
                    'password' => Hash::make('password'),
                    'phone' => $patientInfo['phone'],
                    'role' => 'patient',
                    'locale' => 'ar',
                    'status' => 'active',
                    'email_verified_at' => now(),
                ]
            );
            $patient->assignRole('Patient');

            // Create patient profile
            Patient::updateOrCreate(
                ['user_id' => $patient->id],
                [
                    'medical_history' => json_encode([
                        'conditions' => 'No significant medical history',
                        'allergies' => 'No known allergies',
                        'medications' => 'None'
                    ]),
                    'emergency_contact_name' => 'Emergency Contact',
                    'emergency_contact_phone' => $patientInfo['phone'],
                    'date_of_birth' => now()->subYears(rand(25, 45)),
                    'gender' => rand(0, 1) ? 'male' : 'female',
                ]
            );
        }

        $this->command->info('✅ Users created (admin, manager, doctor, 5 patients)');
    }

    /**
     * Create clinic with cover image
     */
    private function createClinic(): Clinic
    {
        $managerUser = User::where('email', 'manager@ask.dentist')->first();

        $clinic = Clinic::updateOrCreate(
            ['email' => 'clinic@ask.dentist'],
            [
                'name' => 'Ask.Dentist Premium Clinic',
                'country' => 'EG',
                'city' => 'Cairo',
                'cover_path' => 'https://images.unsplash.com/photo-1629909613654-28e377c37b09?w=800&h=400&fit=crop',
                'address' => '123 Dental Street, Medical District, Cairo, Egypt',
                'phone' => '+201234567890',
                'website' => 'https://ask.dentist',
                'description' => 'Premier dental clinic offering comprehensive oral health care with cutting-edge technology and experienced professionals. We provide world-class dental services in a comfortable, modern environment.',
                'operating_hours' => [
                    'sunday' => ['open' => '09:00', 'close' => '17:00'],
                    'monday' => ['open' => '09:00', 'close' => '18:00'],
                    'tuesday' => ['open' => '09:00', 'close' => '18:00'],
                    'wednesday' => ['open' => '09:00', 'close' => '18:00'],
                    'thursday' => ['open' => '09:00', 'close' => '18:00'],
                    'friday' => ['open' => '14:00', 'close' => '20:00'],
                    'saturday' => ['open' => '10:00', 'close' => '16:00']
                ],
                'latitude' => 30.0444,
                'longitude' => 31.2357,
                'verified_at' => now(),
                'verification_notes' => 'Verified premium clinic with full credentials',
                'rating_avg' => 4.8,
                'rating_count' => 127,
            ]
        );

        // Create doctor profile for the main doctor
        $doctorUser = User::where('email', 'dr@ask.dentist')->first();
        $doctor = Doctor::updateOrCreate(
            ['user_id' => $doctorUser->id],
            [
                'specialty' => 'general',
                'license_number' => 'DEN-2024-001',
                'years_experience' => 8,
                'qualifications' => json_encode([
                    'education' => 'DDS - Cairo University',
                    'specialization' => 'General Dentistry',
                    'certifications' => ['Board Certified General Dentist']
                ]),
                'bio' => 'Experienced dentist specializing in comprehensive oral health care with a focus on patient comfort and modern treatment approaches.',
                'rating' => 4.9,
                'total_reviews' => 89,
                'verified_at' => now(),
                'verification_notes' => 'Verified doctor with valid license and credentials',
                'languages' => json_encode(['en', 'ar']),
                'accepts_emergency' => true,
            ]
        );

        // Link doctor to clinic via doctor_clinics table
        \DB::table('doctor_clinics')->updateOrInsert(
            ['doctor_id' => $doctor->id, 'clinic_id' => $clinic->id],
            [
                'id' => \Str::uuid(),
                'role' => 'owner',
                'started_at' => now(),
                'schedule' => json_encode([
                    'sunday' => ['09:00-13:00', '14:00-17:00'],
                    'monday' => ['09:00-13:00', '14:00-18:00'],
                    'tuesday' => ['09:00-13:00', '14:00-18:00'],
                    'wednesday' => ['09:00-13:00', '14:00-18:00'],
                    'thursday' => ['09:00-13:00', '14:00-18:00'],
                    'friday' => ['14:00-20:00'],
                    'saturday' => ['10:00-16:00']
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        $this->command->info('✅ Clinic with cover and main doctor created');
        return $clinic;
    }

    /**
     * Create services and pricing
     */
    private function createServicesAndPricing(Clinic $clinic): void
    {
        // Sample pricing for common services
        $services = Service::all();
        $defaultPrices = [
            'General Consultation' => 200.00,
            'Dental Cleaning' => 300.00,
            'Tooth Filling' => 400.00,
            'Root Canal' => 1200.00,
            'Crown' => 2000.00,
            'Tooth Extraction' => 500.00,
            'Teeth Whitening' => 800.00,
            'Dental Implant' => 3500.00,
            'Orthodontic Consultation' => 250.00,
            'Braces Installation' => 15000.00,
        ];

        foreach ($services as $service) {
            $price = $defaultPrices[$service->name] ?? 300.00;

            Pricing::updateOrCreate(
                [
                    'clinic_id' => $clinic->id,
                    'service_id' => $service->id,
                    'valid_from' => now(),
                ],
                [
                    'base_price' => $price,
                    'currency' => 'EGP',
                    'discount_percentage' => 0,
                    'valid_from' => now(),
                    'is_negotiable' => false,
                    'notes' => "Standard pricing for {$service->name}",
                ]
            );
        }

        $this->command->info('✅ Services and pricing created');
    }

    /**
     * Create content (stories, before/after cases, etc.)
     */
    private function createContent(Clinic $clinic): void
    {
        // Get doctor associated with this clinic through the pivot table
        $doctorClinic = \DB::table('doctor_clinics')->where('clinic_id', $clinic->id)->first();
        $doctor = $doctorClinic ? Doctor::find($doctorClinic->doctor_id) : null;

        if (!$doctor) {
            $this->command->info('No doctor found for clinic content creation');
            return;
        }

        // Create sample stories
        $stories = [
            [
                'owner_type' => 'clinic',
                'owner_id' => $clinic->id,
                'caption' => 'Amazing smile makeover using advanced cosmetic dentistry techniques.',
                'media' => json_encode([
                    'type' => 'image',
                    'url' => 'https://images.unsplash.com/photo-1606811971618-4486d14f3f99?w=400&h=600&fit=crop',
                    'thumbnail' => 'https://images.unsplash.com/photo-1606811971618-4486d14f3f99?w=200&h=300&fit=crop',
                    'duration' => null
                ]),
                'starts_at' => now(),
                'expires_at' => now()->addHours(24),
                'is_ad' => false
            ],
            [
                'owner_type' => 'clinic',
                'owner_id' => $clinic->id,
                'caption' => 'Essential tips for maintaining optimal oral health and preventing dental problems.',
                'media' => json_encode([
                    'type' => 'image',
                    'url' => 'https://images.unsplash.com/photo-1629909613654-28e377c37b09?w=400&h=600&fit=crop',
                    'thumbnail' => 'https://images.unsplash.com/photo-1629909613654-28e377c37b09?w=200&h=300&fit=crop',
                    'duration' => null
                ]),
                'starts_at' => now(),
                'expires_at' => now()->addHours(24),
                'is_ad' => false
            ],
            [
                'owner_type' => 'doctor',
                'owner_id' => $doctor->id,
                'caption' => 'Exploring the latest advances in dental technology and patient care.',
                'media' => json_encode([
                    'type' => 'image',
                    'url' => 'https://images.unsplash.com/photo-1551190822-a9333d879b1f?w=400&h=600&fit=crop',
                    'thumbnail' => 'https://images.unsplash.com/photo-1551190822-a9333d879b1f?w=200&h=300&fit=crop',
                    'duration' => null
                ]),
                'starts_at' => now(),
                'expires_at' => now()->addHours(24),
                'is_ad' => false
            ],
        ];

        foreach ($stories as $storyData) {
            Story::updateOrCreate(
                [
                    'owner_type' => $storyData['owner_type'],
                    'owner_id' => $storyData['owner_id'],
                    'caption' => $storyData['caption']
                ],
                $storyData
            );
        }

        // Create before/after cases
        $beforeAfterCases = [
            [
                'title' => 'Complete Smile Makeover',
                'description' => 'Comprehensive orthodontic treatment with veneers for a perfect smile.',
                'before_path' => 'sample-images/before-smile-makeover.jpg',
                'after_path' => 'sample-images/after-smile-makeover.jpg',
                'treatment_type' => 'Cosmetic Dentistry',
                'duration_days' => 180,
                'procedure_details' => 'Multiple sessions including orthodontic treatment, teeth whitening, and veneer placement.',
                'cost_range' => '$8000-$12000',
                'tags' => json_encode(['cosmetic', 'orthodontics', 'veneers'])
            ],
            [
                'title' => 'Dental Implant Success',
                'description' => 'Single tooth replacement with dental implant and crown.',
                'before_path' => 'sample-images/before-implant.jpg',
                'after_path' => 'sample-images/after-implant.jpg',
                'treatment_type' => 'Dental Implants',
                'duration_days' => 90,
                'procedure_details' => 'Implant placement, healing period, and crown attachment.',
                'cost_range' => '$3000-$5000',
                'tags' => json_encode(['implant', 'restoration'])
            ],
            [
                'title' => 'Teeth Whitening Results',
                'description' => 'Professional teeth whitening for a brighter, confident smile.',
                'before_path' => 'sample-images/before-whitening.jpg',
                'after_path' => 'sample-images/after-whitening.jpg',
                'treatment_type' => 'Teeth Whitening',
                'duration_days' => 1,
                'procedure_details' => 'In-office professional whitening treatment.',
                'cost_range' => '$500-$800',
                'tags' => json_encode(['whitening', 'cosmetic'])
            ],
        ];

        foreach ($beforeAfterCases as $caseData) {
            BeforeAfterCase::updateOrCreate(
                ['title' => $caseData['title']],
                [
                    'doctor_id' => $doctor->id,
                    'clinic_id' => $clinic->id,
                    'description' => $caseData['description'],
                    'before_path' => $caseData['before_path'],
                    'after_path' => $caseData['after_path'],
                    'treatment_type' => $caseData['treatment_type'],
                    'duration_days' => $caseData['duration_days'],
                    'procedure_details' => $caseData['procedure_details'],
                    'cost_range' => $caseData['cost_range'],
                    'tags' => $caseData['tags'],
                    'is_featured' => true,
                    'status' => 'published',
                    'is_approved' => true,
                ]
            );
        }

        $this->command->info('✅ Content created (stories, before/after cases)');
    }

    /**
     * Get service duration in minutes
     */
    private function getServiceDuration(string $serviceName): int
    {
        $durations = [
            'General Consultation' => 30,
            'Dental Cleaning' => 60,
            'Tooth Filling' => 45,
            'Root Canal' => 90,
            'Crown' => 120,
            'Tooth Extraction' => 30,
            'Teeth Whitening' => 90,
            'Dental Implant' => 180,
            'Orthodontic Consultation' => 45,
            'Braces Installation' => 120,
        ];

        return $durations[$serviceName] ?? 60;
    }

    /**
     * Print login credentials
     */
    private function printCredentials(): void
    {
        $this->command->info('');
        $this->command->info('🔑 Login Credentials:');
        $this->command->info('=====================================');
        $this->command->info('Admin:           admin@ask.dentist / password');
        $this->command->info('Clinic Manager:  manager@ask.dentist / password');
        $this->command->info('Doctor:          dr@ask.dentist / password');
        $this->command->info('Patients:        patient1@ask.dentist to patient5@ask.dentist / password');
        $this->command->info('');
        $this->command->info('🌐 Access URLs:');
        $this->command->info('Admin Panel:     http://localhost:8080/admin');
        $this->command->info('Doctor Portal:   http://localhost:8080/doctor');
        $this->command->info('API Health:      http://localhost:8080/api/health');
        $this->command->info('');
    }
}
