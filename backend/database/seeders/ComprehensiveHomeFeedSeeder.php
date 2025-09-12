<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Clinic;
use App\Models\Story;
use App\Models\BeforeAfterCase;
use App\Models\Favorite;

class ComprehensiveHomeFeedSeeder extends Seeder
{
    /**
     * Run the database seeds with specific requirements:
     * - 10 doctors with proper ratings distribution
     * - 5 clinics
     * - 10 stories (half promoted)
     * - 8 before/after cases
     */
    public function run(): void
    {
        $this->command->info('Creating test patient...');
        $this->seedUsers();
        
        $this->command->info('Creating 10 doctors...');
        $this->seedDoctors();
        
        $this->command->info('Creating 5 clinics...');
        $this->seedClinics();
        
        $this->command->info('Creating 10 stories...');
        $this->seedStories();
        
        $this->command->info('Creating 8 before/after cases...');
        $this->seedBeforeAfterCases();
        
        $this->command->info('Creating favorites...');
        $this->seedFavorites();
        
        $this->command->info('Seeding completed successfully!');
    }

    private function seedUsers(): void
    {
        // Create patient user for testing favorites (if doesn't exist)
        $patient = User::where('email', 'patient@test.com')->first();
        if (!$patient) {
            User::factory()->create([
                'name' => 'Test Patient',
                'email' => 'patient@test.com',
                'password' => Hash::make('password'),
                'role' => 'patient',
                'status' => 'active',
                'email_verified_at' => now(),
            ]);
            $this->command->info('Created test patient user');
        } else {
            $this->command->info('Test patient user already exists');
        }
    }

    private function seedDoctors(): void
    {
        $specialties = ['general', 'orthodontics', 'oral_surgery', 'endodontics', 'periodontics', 'prosthodontics', 'pediatric', 'cosmetic', 'implantology'];
        $ratings = [4.9, 4.8, 4.7, 4.6, 4.5, 4.4, 4.3, 4.2, 4.1, 4.0];
        
        $doctorNames = [
            'Dr. Sarah Johnson',
            'Dr. Michael Chen', 
            'Dr. Emily Rodriguez',
            'Dr. David Thompson',
            'Dr. Lisa Wang',
            'Dr. James Miller',
            'Dr. Amanda Wilson',
            'Dr. Robert Garcia',
            'Dr. Jennifer Lee',
            'Dr. Thomas Brown'
        ];

        for ($i = 0; $i < 10; $i++) {
            // First create the user
            $user = User::create([
                'name' => $doctorNames[$i],
                'email' => "doctor" . ($i + 1) . "@example.com",
                'email_verified_at' => now(),
                'phone' => "+1555000" . str_pad($i, 3, '0', STR_PAD_LEFT),
                'password' => Hash::make('password'),
                'role' => 'doctor',
                'locale' => 'en',
                'status' => 'active',
            ]);

            // Then create the doctor record
            $doctor = Doctor::create([
                'user_id' => $user->id,
                'license_number' => 'LIC' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                'specialty' => $specialties[$i % count($specialties)],
                'bio' => $this->getDoctorBio($specialties[$i % count($specialties)]),
                'qualifications' => json_encode([
                    'education' => [
                        'degree' => 'DDS',
                        'university' => $this->getRandomUniversity(),
                        'year' => 2000 + $i + 5
                    ],
                    'certifications' => [
                        'Board Certified Dentist',
                        'Advanced ' . ucfirst(str_replace('_', ' ', $specialties[$i % count($specialties)]))
                    ]
                ]),
                'years_experience' => fake()->numberBetween(5, 25),
                'languages' => json_encode(['en', 'ar']),
                'rating' => $ratings[$i],
                'total_reviews' => fake()->numberBetween(50, 300),
                'accepts_emergency' => fake()->boolean(30),
                'verified_at' => now(),
            ]);

            $this->command->info("Created doctor: {$user->name} (Specialty: {$doctor->specialty}, Rating: {$doctor->rating})");
        }
    }

    private function seedClinics(): void
    {
        $clinicData = [
            [
                'name' => 'Smile Center Dental Clinic',
                'description' => 'Modern dental clinic offering comprehensive oral health services.',
                'rating' => 4.8,
                'total_reviews' => 324,
            ],
            [
                'name' => 'Perfect Smile Dentistry',
                'description' => 'Specialized in cosmetic dentistry and smile makeovers.',
                'rating' => 4.7,
                'total_reviews' => 267,
            ],
            [
                'name' => 'Family Dental Care',
                'description' => 'Gentle dental care for patients of all ages.',
                'rating' => 4.6,
                'total_reviews' => 198,
            ],
            [
                'name' => 'Advanced Oral Surgery Center',
                'description' => 'Specialized surgical procedures and dental implants.',
                'rating' => 4.5,
                'total_reviews' => 145,
            ],
            [
                'name' => 'Bright Smiles Orthodontics',
                'description' => 'Expert orthodontic treatment with latest technology.',
                'rating' => 4.4,
                'total_reviews' => 112,
            ],
        ];

        // First check if clinics table exists and what fields it has
        if (!Schema::hasTable('clinics')) {
            $this->command->warn('Clinics table not found, skipping clinic seeding');
            return;
        }

        foreach ($clinicData as $index => $data) {
            try {
                Clinic::create(array_merge($data, [
                    'address' => "123 Main St, Suite " . ($index + 1) . "00",
                    'phone' => "+1555100" . str_pad($index, 3, '0', STR_PAD_LEFT),
                    'email' => "contact@clinic{$index}.com",
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
                
                $this->command->info("Created clinic: {$data['name']} (Rating: {$data['rating']})");
            } catch (\Exception $e) {
                $this->command->warn("Failed to create clinic {$data['name']}: " . $e->getMessage());
            }
        }
    }

    private function seedStories(): void
    {
        $doctors = Doctor::with('user')->limit(5)->get();

        if ($doctors->isEmpty()) {
            $this->command->warn('No doctors found, skipping story seeding');
            return;
        }

        // First check if stories table exists
        if (!Schema::hasTable('stories')) {
            $this->command->warn('Stories table not found, skipping story seeding');
            return;
        }

        // Create 10 stories (5 promoted, 5 regular)
        $storyData = [];
        
        // 5 promoted stories
        for ($i = 0; $i < 5; $i++) {
            $doctor = $doctors[$i % $doctors->count()];
            $storyData[] = [
                'title' => "Promoted Story " . ($i + 1),
                'content' => "This is a promoted dental story showcasing excellent results from " . $doctor->user->name,
                'doctor_id' => $doctor->id,
                'is_promoted' => true,
                'is_active' => true,
                'created_at' => now()->subHours($i * 2),
                'updated_at' => now()->subHours($i * 2),
            ];
        }
        
        // 5 regular stories
        for ($i = 0; $i < 5; $i++) {
            $doctor = $doctors[$i % $doctors->count()];
            $storyData[] = [
                'title' => "Regular Story " . ($i + 1),
                'content' => "This is a regular dental story from " . $doctor->user->name,
                'doctor_id' => $doctor->id,
                'is_promoted' => false,
                'is_active' => true,
                'created_at' => now()->subHours(($i + 10) * 2),
                'updated_at' => now()->subHours(($i + 10) * 2),
            ];
        }

        foreach ($storyData as $story) {
            try {
                Story::create($story);
                $promotedText = $story['is_promoted'] ? 'promoted' : 'regular';
                $this->command->info("Created {$promotedText} story: {$story['title']}");
            } catch (\Exception $e) {
                $this->command->warn("Failed to create story {$story['title']}: " . $e->getMessage());
            }
        }
    }

    private function seedBeforeAfterCases(): void
    {
        $doctors = Doctor::with('user')->limit(4)->get();

        if ($doctors->isEmpty()) {
            $this->command->warn('No doctors found, skipping before/after cases seeding');
            return;
        }

        // First check if before_after_cases table exists
        if (!Schema::hasTable('before_after_cases')) {
            $this->command->warn('Before/after cases table not found, skipping case seeding');
            return;
        }

        $casesData = [
            [
                'title' => 'Complete Smile Makeover',
                'procedure' => 'Porcelain Veneers + Whitening',
                'description' => 'Full smile transformation with 8 porcelain veneers and professional whitening.',
                'duration_days' => 21,
            ],
            [
                'title' => 'Orthodontic Transformation',
                'procedure' => 'Clear Aligners (Invisalign)',
                'description' => '18-month clear aligner treatment for severely crooked teeth.',
                'duration_days' => 540,
            ],
            [
                'title' => 'Dental Implant Success',
                'procedure' => 'Single Tooth Implant',
                'description' => 'Replacement of missing front tooth with titanium implant and ceramic crown.',
                'duration_days' => 120,
            ],
            [
                'title' => 'Teeth Whitening Results',
                'procedure' => 'Professional Laser Whitening',
                'description' => 'Dramatic whitening results achieved in single 90-minute session.',
                'duration_days' => 1,
            ],
            [
                'title' => 'Gum Disease Treatment',
                'procedure' => 'Deep Cleaning + Gum Grafting',
                'description' => 'Treatment of advanced periodontitis with tissue regeneration.',
                'duration_days' => 90,
            ],
            [
                'title' => 'Broken Tooth Restoration',
                'procedure' => 'Ceramic Crown',
                'description' => 'Restoration of severely damaged molar with all-ceramic crown.',
                'duration_days' => 14,
            ],
            [
                'title' => 'Multiple Implants',
                'procedure' => 'Full Arch Restoration',
                'description' => 'Complete upper arch replacement with 6 implants supporting bridge.',
                'duration_days' => 180,
            ],
            [
                'title' => 'Gap Closure',
                'procedure' => 'Composite Bonding',
                'description' => 'Aesthetic closure of gaps between front teeth using composite resin.',
                'duration_days' => 1,
            ],
        ];

        foreach ($casesData as $index => $case) {
            $doctor = $doctors[$index % $doctors->count()];
            try {
                BeforeAfterCase::create(array_merge($case, [
                    'doctor_id' => $doctor->id,
                    'is_published' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
                
                $this->command->info("Created before/after case: {$case['title']} by {$doctor->user->name}");
            } catch (\Exception $e) {
                $this->command->warn("Failed to create case {$case['title']}: " . $e->getMessage());
            }
        }
    }

    private function seedFavorites(): void
    {
        $patient = User::where('role', 'patient')->first();
        if (!$patient) {
            $this->command->warn('No patient found, skipping favorites seeding');
            return;
        }

        $doctors = Doctor::limit(3)->get();
        $clinics = Clinic::limit(2)->get();

        // Add some favorite doctors
        foreach ($doctors as $doctor) {
            try {
                Favorite::create([
                    'user_id' => $patient->id,
                    'favoritable_type' => Doctor::class,
                    'favoritable_id' => $doctor->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                $this->command->info("Added favorite doctor: {$doctor->user->name}");
            } catch (\Exception $e) {
                $this->command->warn("Failed to add favorite doctor: " . $e->getMessage());
            }
        }

        // Add some favorite clinics if they exist
        foreach ($clinics as $clinic) {
            try {
                Favorite::create([
                    'user_id' => $patient->id,
                    'favoritable_type' => Clinic::class,
                    'favoritable_id' => $clinic->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                $this->command->info("Added favorite clinic: {$clinic->name}");
            } catch (\Exception $e) {
                $this->command->warn("Failed to add favorite clinic: " . $e->getMessage());
            }
        }
    }

    private function getDoctorBio(string $specialty): string
    {
        $bios = [
            'general' => 'Comprehensive dental care for the whole family with a focus on preventive dentistry and patient comfort.',
            'orthodontics' => 'Expert in braces and clear aligners, helping patients achieve perfect smiles with the latest orthodontic technology.',
            'oral_surgery' => 'Specialized in dental implants and complex oral surgical procedures with minimal discomfort and optimal outcomes.',
            'endodontics' => 'Root canal specialist using advanced techniques to save natural teeth and eliminate pain.',
            'periodontics' => 'Gum disease treatment and prevention specialist focused on maintaining healthy gums and supporting structures.',
            'prosthodontics' => 'Expert in dental crowns, bridges, and dentures for complete smile restoration.',
            'pediatric' => 'Gentle care for children, making dental visits fun and stress-free with specialized pediatric techniques.',
            'cosmetic' => 'Specialist in smile makeovers and aesthetic dentistry with years of experience in cosmetic procedures.',
            'implantology' => 'Dental implant specialist providing permanent solutions for missing teeth with state-of-the-art technology.',
        ];

        return $bios[$specialty] ?? 'Dedicated dental professional committed to providing excellent patient care.';
    }

    private function getRandomUniversity(): string
    {
        $universities = [
            'Harvard School of Dental Medicine',
            'University of Pennsylvania School of Dental Medicine',
            'University of Michigan School of Dentistry',
            'Columbia University College of Dental Medicine',
            'UCLA School of Dentistry',
            'New York University College of Dentistry',
            'University of California San Francisco School of Dentistry',
            'University of Southern California School of Dentistry',
            'Boston University Henry M. Goldman School of Dental Medicine',
            'Northwestern University Feinberg School of Medicine'
        ];

        return $universities[array_rand($universities)];
    }
}