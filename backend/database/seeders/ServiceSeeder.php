<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            // General Services
            [
                'name' => 'Dental Consultation',
                'slug' => 'dental-consultation',
                'description' => 'Comprehensive dental examination and consultation',
                'category' => 'general',
                'duration_minutes' => 30,
                'requires_anesthesia' => false,
                'requires_followup' => false,
                'is_emergency' => false,
                'is_tooth_specific' => false,
                'prerequisites' => null,
            ],
            [
                'name' => 'Dental X-Ray',
                'slug' => 'dental-x-ray',
                'description' => 'Digital dental radiography for diagnosis',
                'category' => 'general',
                'duration_minutes' => 15,
                'requires_anesthesia' => false,
                'requires_followup' => false,
                'is_emergency' => false,
                'is_tooth_specific' => true,
                'prerequisites' => null,
            ],

            // Preventive Services
            [
                'name' => 'Teeth Cleaning',
                'slug' => 'teeth-cleaning',
                'description' => 'Professional dental cleaning and polishing',
                'category' => 'preventive',
                'duration_minutes' => 45,
                'requires_anesthesia' => false,
                'requires_followup' => false,
                'is_emergency' => false,
                'is_tooth_specific' => false,
                'prerequisites' => null,
            ],
            [
                'name' => 'Fluoride Treatment',
                'slug' => 'fluoride-treatment',
                'description' => 'Professional fluoride application for cavity prevention',
                'category' => 'preventive',
                'duration_minutes' => 20,
                'requires_anesthesia' => false,
                'requires_followup' => false,
                'is_emergency' => false,
                'is_tooth_specific' => false,
                'prerequisites' => ['cleaning'],
            ],

            // Restorative Services
            [
                'name' => 'Dental Filling',
                'slug' => 'dental-filling',
                'description' => 'Tooth restoration using composite or amalgam filling',
                'category' => 'restorative',
                'duration_minutes' => 60,
                'requires_anesthesia' => true,
                'requires_followup' => true,
                'is_emergency' => false,
                'is_tooth_specific' => true,
                'prerequisites' => ['x-ray', 'consultation'],
            ],
            [
                'name' => 'Dental Crown',
                'slug' => 'dental-crown',
                'description' => 'Custom dental crown restoration',
                'category' => 'restorative',
                'duration_minutes' => 120,
                'requires_anesthesia' => true,
                'requires_followup' => true,
                'is_emergency' => false,
                'is_tooth_specific' => true,
                'prerequisites' => ['x-ray', 'consultation', 'root-canal'],
            ],

            // Cosmetic Services
            [
                'name' => 'Teeth Whitening',
                'slug' => 'teeth-whitening',
                'description' => 'Professional teeth whitening treatment',
                'category' => 'cosmetic',
                'duration_minutes' => 90,
                'requires_anesthesia' => false,
                'requires_followup' => true,
                'is_emergency' => false,
                'is_tooth_specific' => false,
                'prerequisites' => ['cleaning', 'consultation'],
            ],
            [
                'name' => 'Dental Veneers',
                'slug' => 'dental-veneers',
                'description' => 'Porcelain veneers for cosmetic enhancement',
                'category' => 'cosmetic',
                'duration_minutes' => 180,
                'requires_anesthesia' => true,
                'requires_followup' => true,
                'is_emergency' => false,
                'is_tooth_specific' => true,
                'prerequisites' => ['consultation', 'cleaning'],
            ],

            // Orthodontic Services
            [
                'name' => 'Braces Installation',
                'slug' => 'braces-installation',
                'description' => 'Traditional metal or ceramic braces installation',
                'category' => 'orthodontic',
                'duration_minutes' => 120,
                'requires_anesthesia' => false,
                'requires_followup' => true,
                'is_emergency' => false,
                'is_tooth_specific' => false,
                'prerequisites' => ['consultation', 'x-ray', 'cleaning'],
            ],
            [
                'name' => 'Invisalign Treatment',
                'slug' => 'invisalign-treatment',
                'description' => 'Clear aligner orthodontic treatment',
                'category' => 'orthodontic',
                'duration_minutes' => 90,
                'requires_anesthesia' => false,
                'requires_followup' => true,
                'is_emergency' => false,
                'is_tooth_specific' => false,
                'prerequisites' => ['consultation', 'x-ray', 'cleaning'],
            ],

            // Surgical Services
            [
                'name' => 'Tooth Extraction',
                'slug' => 'tooth-extraction',
                'description' => 'Simple or surgical tooth extraction',
                'category' => 'surgical',
                'duration_minutes' => 45,
                'requires_anesthesia' => true,
                'requires_followup' => true,
                'is_emergency' => true,
                'is_tooth_specific' => true,
                'prerequisites' => ['x-ray', 'consultation'],
            ],
            [
                'name' => 'Dental Implant',
                'slug' => 'dental-implant',
                'description' => 'Surgical placement of dental implant',
                'category' => 'surgical',
                'duration_minutes' => 150,
                'requires_anesthesia' => true,
                'requires_followup' => true,
                'is_emergency' => false,
                'is_tooth_specific' => true,
                'prerequisites' => ['x-ray', 'consultation', 'bone-analysis'],
            ],

            // Endodontic Services
            [
                'name' => 'Root Canal Treatment',
                'slug' => 'root-canal-treatment',
                'description' => 'Endodontic treatment to save infected tooth',
                'category' => 'endodontic',
                'duration_minutes' => 120,
                'requires_anesthesia' => true,
                'requires_followup' => true,
                'is_emergency' => true,
                'is_tooth_specific' => true,
                'prerequisites' => ['x-ray', 'consultation'],
            ],

            // Periodontal Services
            [
                'name' => 'Deep Cleaning (Scaling)',
                'slug' => 'deep-cleaning-scaling',
                'description' => 'Deep cleaning and root planing for gum disease',
                'category' => 'periodontal',
                'duration_minutes' => 90,
                'requires_anesthesia' => true,
                'requires_followup' => true,
                'is_emergency' => false,
                'is_tooth_specific' => false,
                'prerequisites' => ['consultation', 'x-ray'],
            ],

            // Pediatric Services
            [
                'name' => 'Pediatric Dental Checkup',
                'slug' => 'pediatric-dental-checkup',
                'description' => 'Specialized dental examination for children',
                'category' => 'pediatric',
                'duration_minutes' => 30,
                'requires_anesthesia' => false,
                'requires_followup' => false,
                'is_emergency' => false,
                'is_tooth_specific' => false,
                'prerequisites' => null,
            ],

            // Emergency Services
            [
                'name' => 'Emergency Dental Care',
                'slug' => 'emergency-dental-care',
                'description' => 'Immediate care for dental emergencies',
                'category' => 'emergency',
                'duration_minutes' => 60,
                'requires_anesthesia' => true,
                'requires_followup' => true,
                'is_emergency' => true,
                'is_tooth_specific' => true,
                'prerequisites' => null,
            ],
        ];

        foreach ($services as $service) {
            Service::firstOrCreate(
                ['slug' => $service['slug']], // Find by slug
                $service // Create with all data if not found
            );
        }

        $this->command->info('Services seeded successfully!');
        $this->command->info('Created ' . count($services) . ' dental services');
    }
}
