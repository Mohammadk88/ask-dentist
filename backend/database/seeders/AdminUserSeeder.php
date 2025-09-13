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
        // Create default admin user as per requirement
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

        // Assign admin role
        try {
            $adminRole = Role::findByName('Admin');
            if ($adminRole && !$admin->hasRole('Admin')) {
                $admin->assignRole($adminRole);
            }
        } catch (\Exception $e) {
            $this->command->warn('Could not assign role: ' . $e->getMessage());
        }

        $this->command->info('Admin user created: admin@ask.dentist / password');

        // Create clinic manager as per requirement
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

        // Assign clinic manager role
        try {
            $managerRole = Role::findByName('ClinicManager');
            if ($managerRole && !$clinicManager->hasRole('ClinicManager')) {
                $clinicManager->assignRole($managerRole);
            }
        } catch (\Exception $e) {
            $this->command->warn('Could not assign manager role: ' . $e->getMessage());
        }

        $this->command->info('Clinic Manager created: clinic@ask.dentist / password');

        // Create test doctor as per requirement
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

        // Create test patient
        $patient = User::firstOrCreate(
            ['email' => 'patient@example.com'],
            [
                'name' => 'Test Patient',
                'phone' => '+1234567893',
                'password' => Hash::make('patient123'),
                'role' => 'patient',
                'locale' => 'en',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        // Assign patient role
        try {
            $patientRole = Role::findByName('Patient');
            if ($patientRole && !$patient->hasRole('Patient')) {
                $patient->assignRole($patientRole);
            }
        } catch (\Exception $e) {
            $this->command->warn('Could not assign patient role: ' . $e->getMessage());
        }

        $this->command->info('Test Patient created: patient@example.com / patient123');
    }
}
