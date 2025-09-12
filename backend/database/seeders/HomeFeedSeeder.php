<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Doctor;
use App\Models\Clinic;
use App\Models\User;

class HomeFeedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedStories();
        $this->seedBeforeAfterCases();
        $this->updatePromotionFields();
        $this->seedFavorites();
    }

    private function seedStories(): void
    {
        // Get existing doctors and clinics
        $doctorIds = Doctor::limit(2)->pluck('id')->toArray();
        $clinicIds = Clinic::limit(1)->pluck('id')->toArray();

        if (empty($doctorIds) || empty($clinicIds)) {
            echo "Warning: Not enough doctors or clinics found. Skipping stories seeding.\n";
            return;
        }

        $stories = [
            [
                'owner_type' => 'doctor',
                'owner_id' => $doctorIds[0],
                'media' => json_encode([
                    'type' => 'image',
                    'url' => 'stories/doctor_1_consultation.jpg',
                    'thumbnail' => 'stories/thumbs/doctor_1_consultation.jpg'
                ]),
                'caption' => 'Successful dental implant procedure completed today! 🦷✨',
                'lang' => 'en',
                'starts_at' => Carbon::now()->subHours(2),
                'expires_at' => Carbon::now()->addHours(22),
                'is_ad' => false,
            ],
            [
                'owner_type' => 'clinic',
                'owner_id' => $clinicIds[0],
                'media' => json_encode([
                    'type' => 'video',
                    'url' => 'stories/clinic_1_tour.mp4',
                    'thumbnail' => 'stories/thumbs/clinic_1_tour.jpg',
                    'duration' => 15
                ]),
                'caption' => 'Take a virtual tour of our modern dental facility',
                'lang' => 'en',
                'starts_at' => Carbon::now()->subHours(4),
                'expires_at' => Carbon::now()->addHours(20),
                'is_ad' => true,
            ],
        ];

        if (count($doctorIds) > 1) {
            $stories[] = [
                'owner_type' => 'doctor',
                'owner_id' => $doctorIds[1],
                'media' => json_encode([
                    'type' => 'image',
                    'url' => 'stories/doctor_2_whitening.jpg',
                    'thumbnail' => 'stories/thumbs/doctor_2_whitening.jpg'
                ]),
                'caption' => 'Amazing teeth whitening results! Before & after',
                'lang' => 'en',
                'starts_at' => Carbon::now()->subHours(1),
                'expires_at' => Carbon::now()->addHours(23),
                'is_ad' => false,
            ];
        }

        foreach ($stories as $story) {
            DB::table('stories')->insert(array_merge($story, [
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]));
        }
    }

    private function seedBeforeAfterCases(): void
    {
        // Get existing doctors and clinics
        $doctorIds = Doctor::limit(2)->pluck('id')->toArray();
        $clinicIds = Clinic::limit(1)->pluck('id')->toArray();

        if (empty($doctorIds)) {
            echo "Warning: No doctors found. Skipping before/after cases seeding.\n";
            return;
        }

        $cases = [
            [
                'doctor_id' => $doctorIds[0],
                'clinic_id' => !empty($clinicIds) ? $clinicIds[0] : null,
                'title' => 'Complete Smile Makeover',
                'description' => 'Full dental reconstruction with veneers and implants',
                'before_path' => 'before_after/case_1_before.jpg',
                'after_path' => 'before_after/case_1_after.jpg',
                'tags' => json_encode(['smile makeover', 'veneers', 'implants', 'cosmetic dentistry']),
                'treatment_type' => 'Cosmetic Dentistry',
                'duration_days' => 90,
                'procedure_details' => 'Patient received 8 porcelain veneers on upper teeth and 2 dental implants to replace missing molars.',
                'cost_range' => '$15,000 - $25,000',
                'is_featured' => true,
                'status' => 'published',
                'is_approved' => true,
            ],
            [
                'doctor_id' => count($doctorIds) > 1 ? $doctorIds[1] : $doctorIds[0],
                'clinic_id' => null,
                'title' => 'Teeth Whitening Transformation',
                'description' => 'Professional laser whitening treatment',
                'before_path' => 'before_after/case_2_before.jpg',
                'after_path' => 'before_after/case_2_after.jpg',
                'tags' => json_encode(['whitening', 'cosmetic', 'laser treatment']),
                'treatment_type' => 'Teeth Whitening',
                'duration_days' => 1,
                'procedure_details' => 'Single session laser whitening treatment with professional-grade bleaching gel.',
                'cost_range' => '$500 - $800',
                'is_featured' => false,
                'status' => 'published',
                'is_approved' => true,
            ],
            [
                'doctor_id' => $doctorIds[0],
                'clinic_id' => !empty($clinicIds) ? $clinicIds[0] : null,
                'title' => 'Orthodontic Treatment Results',
                'description' => 'Clear aligner treatment for crooked teeth',
                'before_path' => 'before_after/case_3_before.jpg',
                'after_path' => 'before_after/case_3_after.jpg',
                'tags' => json_encode(['orthodontics', 'aligners', 'invisalign', 'teeth straightening']),
                'treatment_type' => 'Orthodontics',
                'duration_days' => 365,
                'procedure_details' => '12-month treatment with clear aligners, patient wore 24 sets of aligners.',
                'cost_range' => '$3,000 - $5,000',
                'is_featured' => true,
                'status' => 'published',
                'is_approved' => true,
            ],
        ];

        foreach ($cases as $case) {
            DB::table('before_after_cases')->insert(array_merge($case, [
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]));
        }
    }

    private function updatePromotionFields(): void
    {
        // Get existing doctors and clinics
        $doctorIds = Doctor::limit(2)->pluck('id')->toArray();
        $clinicIds = Clinic::limit(1)->pluck('id')->toArray();

        // Update some doctors with promotion fields
        if (!empty($doctorIds)) {
            DB::table('doctors')->where('id', $doctorIds[0])->update([
                'is_promoted' => true,
                'promoted_until' => Carbon::now()->addDays(30),
                'rating_avg' => 4.8,
                'rating_count' => 124,
                'cover_path' => 'doctors/covers/doctor_1_cover.jpg',
            ]);

            if (count($doctorIds) > 1) {
                DB::table('doctors')->where('id', $doctorIds[1])->update([
                    'is_promoted' => false,
                    'rating_avg' => 4.6,
                    'rating_count' => 89,
                    'cover_path' => 'doctors/covers/doctor_2_cover.jpg',
                ]);
            }
        }

        // Update some clinics with promotion fields
        if (!empty($clinicIds)) {
            DB::table('clinics')->where('id', $clinicIds[0])->update([
                'is_promoted' => true,
                'promoted_until' => Carbon::now()->addDays(15),
                'rating_avg' => 4.9,
                'rating_count' => 256,
                'cover_path' => 'clinics/covers/clinic_1_cover.jpg',
            ]);
        }
    }

    private function seedFavorites(): void
    {
        // Get first patient user (assuming they exist)
        $patientUser = User::whereHas('roles', function ($query) {
            $query->where('name', 'Patient');
        })->first();

        if (!$patientUser) {
            echo "Warning: No patient users found. Skipping favorites seeding.\n";
            return;
        }

        // Get existing doctors and clinics
        $doctorIds = Doctor::limit(2)->pluck('id')->toArray();
        $clinicIds = Clinic::limit(1)->pluck('id')->toArray();

        if (!empty($doctorIds)) {
            // Add some favorite doctors
            foreach ($doctorIds as $doctorId) {
                DB::table('favorite_doctors')->insert([
                    'patient_id' => $patientUser->id,
                    'doctor_id' => $doctorId,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        if (!empty($clinicIds)) {
            // Add some favorite clinics
            foreach ($clinicIds as $clinicId) {
                DB::table('favorite_clinics')->insert([
                    'patient_id' => $patientUser->id,
                    'clinic_id' => $clinicId,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}
