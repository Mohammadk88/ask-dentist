<?php

namespace Database\Seeders;

use App\Models\Clinic;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClinicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clinics = [
            [
                'name' => 'DentCare Istanbul Clinic',
                'country' => 'TR',
                'city' => 'Istanbul',
                'address' => 'Şişli, Halaskargazi Cd. No:125, 34373 Şişli/İstanbul',
                'phone' => '+90 212 555 0101',
                'email' => 'info@dentcare-istanbul.com',
                'website' => 'https://dentcare-istanbul.com',
                'description' => 'Premium dental clinic in Istanbul offering comprehensive dental services with modern technology and experienced dentists.',
                'operating_hours' => [
                    'monday' => ['open' => '09:00', 'close' => '18:00'],
                    'tuesday' => ['open' => '09:00', 'close' => '18:00'],
                    'wednesday' => ['open' => '09:00', 'close' => '18:00'],
                    'thursday' => ['open' => '09:00', 'close' => '18:00'],
                    'friday' => ['open' => '09:00', 'close' => '18:00'],
                    'saturday' => ['open' => '09:00', 'close' => '15:00'],
                    'sunday' => null,
                ],
                'latitude' => 41.0534,
                'longitude' => 28.9735,
                'verified_at' => now(),
            ],
            [
                'name' => 'SmileCare Antalya',
                'country' => 'TR',
                'city' => 'Antalya',
                'address' => 'Muratpaşa, Cumhuriyet Cd. No:85, 07160 Muratpaşa/Antalya',
                'phone' => '+90 242 555 0201',
                'email' => 'contact@smilecare-antalya.com',
                'website' => 'https://smilecare-antalya.com',
                'description' => 'Modern dental clinic in Antalya specializing in cosmetic dentistry and dental tourism.',
                'operating_hours' => [
                    'monday' => ['open' => '08:30', 'close' => '17:30'],
                    'tuesday' => ['open' => '08:30', 'close' => '17:30'],
                    'wednesday' => ['open' => '08:30', 'close' => '17:30'],
                    'thursday' => ['open' => '08:30', 'close' => '17:30'],
                    'friday' => ['open' => '08:30', 'close' => '17:30'],
                    'saturday' => ['open' => '09:00', 'close' => '14:00'],
                    'sunday' => null,
                ],
                'latitude' => 36.8841,
                'longitude' => 30.7056,
                'verified_at' => now(),
            ],
            [
                'name' => 'Elite Dental Ankara',
                'country' => 'TR',
                'city' => 'Ankara',
                'address' => 'Çankaya, Tunalı Hilmi Cd. No:67, 06690 Çankaya/Ankara',
                'phone' => '+90 312 555 0301',
                'email' => 'info@elitedental-ankara.com',
                'website' => 'https://elitedental-ankara.com',
                'description' => 'Elite dental clinic in Ankara providing advanced dental treatments and orthodontic services.',
                'operating_hours' => [
                    'monday' => ['open' => '09:00', 'close' => '19:00'],
                    'tuesday' => ['open' => '09:00', 'close' => '19:00'],
                    'wednesday' => ['open' => '09:00', 'close' => '19:00'],
                    'thursday' => ['open' => '09:00', 'close' => '19:00'],
                    'friday' => ['open' => '09:00', 'close' => '19:00'],
                    'saturday' => ['open' => '10:00', 'close' => '16:00'],
                    'sunday' => null,
                ],
                'latitude' => 39.9334,
                'longitude' => 32.8597,
                'verified_at' => now(),
            ],
        ];

        foreach ($clinics as $clinicData) {
            $clinic = Clinic::firstOrCreate(
                ['name' => $clinicData['name']],
                $clinicData
            );

            $this->command->info("Created/Found clinic: {$clinic->name}");
        }

        // Note: User-clinic relationship is handled through roles/permissions
        // Not through a direct clinic_id column

        $this->command->info('Clinics seeded successfully!');
        $this->command->info('Created ' . count($clinics) . ' dental clinics');
    }
}
