<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SpecializationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specializations = [
            [
                'name' => 'General Dentistry',
                'slug' => 'general-dentistry',
                'description' => 'Comprehensive dental care including cleanings, fillings, and routine checkups',
                'sort_order' => 1
            ],
            [
                'name' => 'Orthodontics',
                'slug' => 'orthodontics',
                'description' => 'Teeth alignment and bite correction using braces, aligners, and other devices',
                'sort_order' => 2
            ],
            [
                'name' => 'Oral Surgery',
                'slug' => 'oral-surgery',
                'description' => 'Surgical procedures including extractions, implants, and jaw surgery',
                'sort_order' => 3
            ],
            [
                'name' => 'Endodontics',
                'slug' => 'endodontics',
                'description' => 'Root canal treatment and other procedures involving tooth pulp',
                'sort_order' => 4
            ],
            [
                'name' => 'Periodontics',
                'slug' => 'periodontics',
                'description' => 'Treatment of gum disease and supporting structures of teeth',
                'sort_order' => 5
            ],
            [
                'name' => 'Prosthodontics',
                'slug' => 'prosthodontics',
                'description' => 'Restoration and replacement of teeth using crowns, bridges, and dentures',
                'sort_order' => 6
            ],
            [
                'name' => 'Pediatric Dentistry',
                'slug' => 'pediatric-dentistry',
                'description' => 'Specialized dental care for infants, children, and adolescents',
                'sort_order' => 7
            ],
            [
                'name' => 'Oral Pathology',
                'slug' => 'oral-pathology',
                'description' => 'Diagnosis and treatment of diseases affecting the mouth and jaw',
                'sort_order' => 8
            ],
            [
                'name' => 'Cosmetic Dentistry',
                'slug' => 'cosmetic-dentistry',
                'description' => 'Aesthetic dental procedures including whitening and veneers',
                'sort_order' => 9
            ],
            [
                'name' => 'Emergency Dentistry',
                'slug' => 'emergency-dentistry',
                'description' => 'Urgent dental care for accidents, severe pain, and dental trauma',
                'sort_order' => 10
            ]
        ];

        foreach ($specializations as $specialization) {
            \DB::table('specializations')->updateOrInsert(
                ['slug' => $specialization['slug']],
                array_merge($specialization, [
                    'created_at' => now(),
                    'updated_at' => now()
                ])
            );
        }
    }
}
