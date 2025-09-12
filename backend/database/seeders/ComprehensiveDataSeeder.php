<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Service;
use App\Models\Pricing;
use App\Models\TreatmentRequest;
use App\Infrastructure\Models\TeethReference;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class ComprehensiveDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🦷 Starting comprehensive dental data seeding...');

        // Ensure roles exist first
        $this->ensureRolesExist();

        // Create admin user
        $admin = $this->createAdminUser();
        $this->command->info('✅ Admin user created');

        // Create clinic with manager
        $clinic = $this->createClinicWithManager();
        $this->command->info('✅ Clinic and manager created');

        // Create doctors
        $doctors = $this->createDoctors($clinic, 10);
        $this->command->info('✅ 10 doctors created');

        // Create patients
        $patients = $this->createPatients(5);
        $this->command->info('✅ 5 patients created');

        // Create services (if not already exists)
        $this->ensureServicesExist();
        $this->command->info('✅ Services verified');

        // Create pricing for clinic
        $this->createPricingForClinic($clinic);
        $this->command->info('✅ Default price list created');

        // Create teeth reference (if not already exists)
        $this->ensureTeethReferenceExists();
        $this->command->info('✅ Teeth reference (FDI 11-48) verified');

        // Create treatment requests with sample images
        $this->createTreatmentRequests($patients, $doctors);
        $this->command->info('✅ Treatment requests with sample images created');

        $this->command->info('🎉 Comprehensive dental data seeding completed successfully!');
        $this->printSummary($admin, $clinic, $doctors, $patients);
    }

    /**
     * Ensure all required roles exist
     */
    private function ensureRolesExist(): void
    {
        $roles = ['Admin', 'ClinicManager', 'Doctor', 'Patient'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }
    }

    /**
     * Create admin user
     */
    private function createAdminUser(): User
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@askdentist.app'],
            [
                'name' => 'System Administrator',
                'email' => 'admin@askdentist.app',
                'password' => Hash::make('admin123'),
                'phone' => '+1234567890',
                'role' => 'admin',
                'locale' => 'en',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        $adminRole = Role::where('name', 'Admin')->where('guard_name', 'web')->first();
        $admin->assignRole($adminRole);
        return $admin;
    }

    /**
     * Create clinic with manager
     */
    private function createClinicWithManager(): Clinic
    {
        // Create clinic manager user
        $manager = User::firstOrCreate(
            ['email' => 'manager@brightsmiles.clinic'],
            [
                'name' => 'Sarah Johnson',
                'email' => 'manager@brightsmiles.clinic',
                'password' => Hash::make('manager123'),
                'phone' => '+1555123456',
                'role' => 'clinic_manager',
                'locale' => 'en',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        $managerRole = Role::where('name', 'ClinicManager')->where('guard_name', 'web')->first();
        $manager->assignRole($managerRole);

        // Create clinic
        $clinic = Clinic::firstOrCreate(
            ['email' => 'info@brightsmiles.clinic'],
            [
                'name' => 'Bright Smiles Dental Clinic',
                'country' => 'United States',
                'city' => 'San Francisco',
                'address' => '123 Dental Avenue, Suite 200',
                'phone' => '+1555987654',
                'email' => 'info@brightsmiles.clinic',
                'website' => 'https://brightsmiles.clinic',
                'description' => 'Modern dental clinic providing comprehensive oral health care with state-of-the-art technology and experienced professionals.',
                'operating_hours' => [
                    'monday' => ['open' => '08:00', 'close' => '18:00'],
                    'tuesday' => ['open' => '08:00', 'close' => '18:00'],
                    'wednesday' => ['open' => '08:00', 'close' => '18:00'],
                    'thursday' => ['open' => '08:00', 'close' => '18:00'],
                    'friday' => ['open' => '08:00', 'close' => '17:00'],
                    'saturday' => ['open' => '09:00', 'close' => '15:00'],
                    'sunday' => ['open' => null, 'close' => null]
                ],
                'latitude' => 37.7749,
                'longitude' => -122.4194,
                'verified_at' => now(),
                'verification_notes' => 'Verified clinic with proper licensing and credentials',
            ]
        );

        return $clinic;
    }

    /**
     * Create doctors
     */
    private function createDoctors(Clinic $clinic, int $count): array
    {
        $doctors = [];
        $specializations = [
            'general',
            'orthodontics',
            'periodontics',
            'endodontics',
            'oral_surgery',
            'pediatric',
            'prosthodontics',
            'cosmetic',
            'implantology'
        ];

        $doctorNames = [
            ['Dr. Michael Chen', 'michael.chen@brightsmiles.clinic'],
            ['Dr. Emily Rodriguez', 'emily.rodriguez@brightsmiles.clinic'],
            ['Dr. David Thompson', 'david.thompson@brightsmiles.clinic'],
            ['Dr. Sarah Kim', 'sarah.kim@brightsmiles.clinic'],
            ['Dr. James Wilson', 'james.wilson@brightsmiles.clinic'],
            ['Dr. Lisa Anderson', 'lisa.anderson@brightsmiles.clinic'],
            ['Dr. Robert Martinez', 'robert.martinez@brightsmiles.clinic'],
            ['Dr. Jennifer Lee', 'jennifer.lee@brightsmiles.clinic'],
            ['Dr. Christopher Brown', 'christopher.brown@brightsmiles.clinic'],
            ['Dr. Amanda Davis', 'amanda.davis@brightsmiles.clinic'],
        ];

        for ($i = 0; $i < min($count, count($doctorNames)); $i++) {
            [$name, $email] = $doctorNames[$i];
            $specialization = $specializations[$i % count($specializations)];

            // Create user
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make('doctor123'),
                    'phone' => '+1555' . str_pad($i + 100, 6, '0', STR_PAD_LEFT),
                    'role' => 'doctor',
                    'locale' => 'en',
                    'status' => 'active',
                    'email_verified_at' => now(),
                ]
            );
            $doctorRole = Role::where('name', 'Doctor')->where('guard_name', 'web')->first();
            $user->assignRole($doctorRole);

            // Create doctor profile
            $doctor = Doctor::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'user_id' => $user->id,
                    'license_number' => 'DDS' . str_pad($i + 1001, 6, '0', STR_PAD_LEFT),
                    'specialty' => $specialization,
                    'bio' => "Experienced {$specialization} specialist with over " . (5 + $i) . " years of practice. Committed to providing excellent patient care using the latest dental techniques and technologies.",
                    'qualifications' => [
                        'degree' => 'Doctor of Dental Surgery (DDS)',
                        'university' => 'University of California, San Francisco',
                        'graduation_year' => 2015 + $i,
                        'certifications' => ['ADA Certified', 'Board Certified in ' . $specialization]
                    ],
                    'years_experience' => 5 + $i,
                    'rating' => 4.2 + (($i % 8) * 0.1),
                    'total_reviews' => rand(10, 150),
                    'languages' => ['English', $i % 3 == 0 ? 'Spanish' : ($i % 2 == 0 ? 'French' : 'Mandarin')],
                    'accepts_emergency' => $i % 3 == 0,
                    'verified_at' => now(),
                    'verification_notes' => 'Verified doctor with proper credentials and licensing',
                ]
            );

            $doctors[] = $doctor;
        }

        return $doctors;
    }

    /**
     * Create patients
     */
    private function createPatients(int $count): array
    {
        $patients = [];
        $patientData = [
            ['John Smith', 'john.smith@email.com', '1985-03-15'],
            ['Maria Garcia', 'maria.garcia@email.com', '1990-07-22'],
            ['Alex Johnson', 'alex.johnson@email.com', '1988-11-08'],
            ['Emma Wilson', 'emma.wilson@email.com', '1995-01-30'],
            ['Daniel Brown', 'daniel.brown@email.com', '1992-09-14'],
        ];

        for ($i = 0; $i < min($count, count($patientData)); $i++) {
            [$name, $email, $birthDate] = $patientData[$i];

            // Create user
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make('patient123'),
                    'phone' => '+1555' . str_pad($i + 200, 6, '0', STR_PAD_LEFT),
                    'role' => 'patient',
                    'locale' => 'en',
                    'status' => 'active',
                    'email_verified_at' => now(),
                ]
            );
            $patientRole = Role::where('name', 'Patient')->where('guard_name', 'web')->first();
            $user->assignRole($patientRole);

            // Create patient profile
            $patient = Patient::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'user_id' => $user->id,
                    'date_of_birth' => $birthDate,
                    'gender' => $i % 2 == 0 ? 'male' : 'female',
                    'emergency_contact_name' => 'Emergency Contact ' . ($i + 1),
                    'emergency_contact_phone' => '+1555' . str_pad($i + 300, 6, '0', STR_PAD_LEFT),
                    'insurance_provider' => $i % 2 == 0 ? 'BlueCross BlueShield' : 'Aetna',
                    'insurance_number' => 'INS' . str_pad($i + 1000, 8, '0', STR_PAD_LEFT),
                    'medical_history' => [
                        'allergies' => $i % 3 == 0 ? ['Penicillin'] : [],
                        'medications' => $i % 4 == 0 ? ['Aspirin daily'] : [],
                        'conditions' => $i % 5 == 0 ? ['Diabetes'] : [],
                    ],
                    'dental_history' => [
                        'last_checkup' => now()->subMonths(6 + $i)->format('Y-m-d'),
                        'previous_treatments' => $i % 2 == 0 ? ['Cleaning', 'Filling'] : ['Cleaning'],
                        'dental_fears' => $i % 3 == 0 ? ['Injections'] : [],
                    ],
                    'notes' => 'Patient has good oral hygiene and follows recommended dental care.',
                ]
            );

            $patients[] = $patient;
        }

        return $patients;
    }

    /**
     * Ensure services exist
     */
    private function ensureServicesExist(): void
    {
        if (Service::count() == 0) {
            $this->call(ServiceSeeder::class);
        }
    }

    /**
     * Create pricing for clinic
     */
    private function createPricingForClinic(Clinic $clinic): void
    {
        $services = Service::all();
        $basePrices = [
            'dental-consultation' => 150,
            'dental-x-ray' => 75,
            'teeth-cleaning' => 120,
            'fluoride-treatment' => 50,
            'dental-filling' => 250,
            'dental-crown' => 800,
            'teeth-whitening' => 400,
            'dental-veneers' => 1200,
            'braces-installation' => 2500,
            'invisalign-treatment' => 3500,
            'tooth-extraction' => 300,
            'dental-implant' => 2000,
            'root-canal-treatment' => 800,
            'deep-cleaning-scaling' => 400,
            'pediatric-dental-checkup' => 100,
            'emergency-dental-care' => 200,
        ];

        foreach ($services as $service) {
            Pricing::firstOrCreate(
                [
                    'clinic_id' => $clinic->id,
                    'service_id' => $service->id,
                ],
                [
                    'clinic_id' => $clinic->id,
                    'service_id' => $service->id,
                    'base_price' => $basePrices[$service->slug] ?? 200,
                    'currency' => 'USD',
                    'discount_percentage' => 0,
                    'valid_from' => now(),
                    'valid_until' => null,
                    'conditions' => [],
                    'is_negotiable' => false,
                    'notes' => 'Standard pricing for ' . $service->name,
                ]
            );
        }
    }

    /**
     * Ensure teeth reference exists
     */
    private function ensureTeethReferenceExists(): void
    {
        if (TeethReference::count() == 0) {
            $this->call(TeethReferenceSeeder::class);
        }
    }

    /**
     * Create treatment requests with sample images
     */
    private function createTreatmentRequests(array $patients, array $doctors): void
    {
        $urgencyLevels = ['low', 'medium', 'high', 'emergency'];
        $statusOptions = ['pending', 'reviewing', 'quoted', 'accepted', 'in_progress'];

        $sampleTitles = [
            'low' => 'Routine Dental Checkup',
            'medium' => 'Tooth Pain and Sensitivity',
            'high' => 'Cosmetic Consultation Required',
            'emergency' => 'Emergency Dental Care Needed'
        ];

        $sampleDescriptions = [
            'low' => 'I would like to schedule a routine dental checkup. It has been about 6 months since my last visit.',
            'medium' => 'I have been experiencing tooth pain in my upper right molar for the past 3 days. The pain gets worse when eating or drinking cold items.',
            'high' => 'I am interested in improving the appearance of my teeth. I would like to discuss options for teeth whitening and possibly veneers.',
            'emergency' => 'I had a dental accident and chipped my front tooth. I need urgent dental care.'
        ];

        $samplePhotos = [
            'low' => ['images/sample_smile.jpg'],
            'medium' => ['images/tooth_pain_xray.jpg', 'images/affected_tooth.jpg'],
            'high' => ['images/current_smile.jpg', 'images/desired_result.jpg'],
            'emergency' => ['images/chipped_tooth.jpg', 'images/injury_detail.jpg']
        ];

        foreach ($patients as $index => $patient) {
            $urgency = $urgencyLevels[$index % count($urgencyLevels)];
            
            $treatmentRequest = TreatmentRequest::create([
                'patient_id' => $patient->id,
                'title' => $sampleTitles[$urgency],
                'description' => $sampleDescriptions[$urgency],
                'urgency' => $urgency,
                'symptoms' => [
                    'pain_level' => $urgency === 'emergency' ? 9 : ($urgency === 'high' ? 6 : ($urgency === 'medium' ? 4 : 1)),
                    'duration' => $urgency === 'emergency' ? '1 hour' : ($urgency === 'medium' ? '3 days' : 'none'),
                    'location' => $urgency === 'medium' ? 'upper right molar' : ($urgency === 'emergency' ? 'front tooth' : 'none')
                ],
                'affected_teeth' => $urgency === 'medium' ? ['16'] : ($urgency === 'emergency' ? ['11'] : []),
                'photos' => $samplePhotos[$urgency],
                'status' => $statusOptions[$index % count($statusOptions)],
                'is_emergency' => $urgency === 'emergency',
                'preferred_date' => now()->addDays(rand(1, 30)),
                'preferred_times' => ['morning', 'afternoon'],
                'notes' => 'Patient created via comprehensive data seeding.',
            ]);

            $this->command->info("Created treatment request for {$patient->user->name} - Urgency: {$urgency}");
        }
    }

    /**
     * Print summary of created data
     */
    private function printSummary(User $admin, Clinic $clinic, array $doctors, array $patients): void
    {
        $this->command->info('');
        $this->command->info('=== SEEDING SUMMARY ===');
        $this->command->info("👤 Admin: {$admin->name} ({$admin->email})");
        $this->command->info("🏥 Clinic: {$clinic->name}");
        $this->command->info("🦷 Doctors: " . count($doctors) . " created");
        $this->command->info("👥 Patients: " . count($patients) . " created");
        $this->command->info("🛠️  Services: " . Service::count() . " available");
        $this->command->info("💰 Pricing entries: " . Pricing::count());
        $this->command->info("🦷 Teeth reference: " . TeethReference::count() . " (FDI 11-48)");
        $this->command->info("📋 Treatment requests: " . TreatmentRequest::count() . " with sample images");
        $this->command->info('');
        $this->command->info('=== LOGIN CREDENTIALS ===');
        $this->command->info('Admin: admin@askdentist.app / admin123');
        $this->command->info('Manager: manager@brightsmiles.clinic / manager123');
        $this->command->info('Doctors: <doctor-email> / doctor123');
        $this->command->info('Patients: <patient-email> / patient123');
        $this->command->info('');
    }
}