<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@ask.dentist'],
            [
                'name' => 'System Administrator',
                'phone' => '+1234567890',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'locale' => 'en',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Admin user created: admin@ask.dentist / password');

        // Create test clinic manager
        $clinicManager = User::firstOrCreate(
            ['email' => 'clinic@ask.dentist'],
            [
                'name' => 'Clinic Manager',
                'phone' => '+1234567891',
                'password' => Hash::make('password'),
                'role' => 'clinic_manager',
                'locale' => 'en',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Clinic Manager created: clinic@ask.dentist / password');

        // Create test doctor
        $doctor = User::firstOrCreate(
            ['email' => 'dr@ask.dentist'],
            [
                'name' => 'Dr. Test Doctor',
                'phone' => '+1234567892',
                'password' => Hash::make('password'),
                'role' => 'doctor',
                'locale' => 'en',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        // Assign doctor role
        try {
            $doctorRole = Role::findByName('Doctor');
            if ($doctorRole && !$doctor->hasRole('Doctor')) {
                $doctor->assignRole($doctorRole);
            }
        } catch (\Exception $e) {
            $this->command->warn('Could not assign doctor role: ' . $e->getMessage());
        }

        $this->command->info('Test Doctor created: dr@ask.dentist / password');

        // Link clinic manager and doctor to Clinic #1 via many-to-many relationship
        $clinic = \App\Models\Clinic::first();
        if ($clinic) {
            // Create doctor profile if it doesn't exist
            $doctorProfile = $doctor->doctor;
            if (!$doctorProfile) {
                $doctorProfile = \App\Models\Doctor::create([
                    'user_id' => $doctor->id,
                    'license_number' => 'TEST123',
                    'specialty' => 'General Dentistry',
                    'bio' => 'Test doctor profile',
                    'years_experience' => 5,
                    'languages' => ['English', 'Arabic'],
                    'verified_at' => now(),
                ]);
            }
            
            // Link doctor to clinic via pivot table
            if (!$doctorProfile->clinics()->where('clinic_id', $clinic->id)->exists()) {
                $doctorProfile->clinics()->attach($clinic->id, [
                    'role' => 'associate',
                    'started_at' => now(),
                ]);
            }
            
            $this->command->info('Doctor linked to Clinic: ' . $clinic->name);
        }

        // Create test patient (optional)
        $patient = User::firstOrCreate(
            ['email' => 'patient@example.com'],
            [
                'name' => 'Test Patient',
                'phone' => '+1234567893',
                'password' => Hash::make('password'),
                'role' => 'patient',
                'locale' => 'en',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Test Patient created: patient@example.com / password');
    }
}
