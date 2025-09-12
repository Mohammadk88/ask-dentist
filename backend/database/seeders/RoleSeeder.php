<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $roles = [
            'Admin',
            'ClinicManager',
            'Doctor',
            'Patient'
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Create permissions
        $permissions = [
            // User management
            'manage_users',
            'view_users',
            'create_users',
            'update_users',
            'delete_users',

            // Clinic management
            'manage_clinics',
            'view_clinics',
            'create_clinics',
            'update_clinics',

            // Doctor management
            'manage_doctors',
            'view_doctors',
            'update_doctor_profile',

            // Patient management
            'manage_patients',
            'view_patients',
            'update_patient_profile',

            // Treatment requests
            'create_treatment_requests',
            'view_treatment_requests',
            'update_treatment_requests',
            'respond_to_treatment_requests',

            // Treatment plans
            'create_treatment_plans',
            'view_treatment_plans',
            'update_treatment_plans',
            'approve_treatment_plans',

            // Appointments
            'create_appointments',
            'view_appointments',
            'update_appointments',
            'cancel_appointments',

            // Messages
            'send_messages',
            'view_messages',

            // Reviews
            'create_reviews',
            'view_reviews',
            'moderate_reviews',

            // System
            'manage_modules',
            'view_activity_logs',
            'manage_settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to roles
        $adminRole = Role::findByName('Admin');
        $adminRole->givePermissionTo(Permission::all());

        $clinicManagerRole = Role::findByName('ClinicManager');
        $clinicManagerRole->givePermissionTo([
            'view_clinics',
            'update_clinics',
            'manage_doctors',
            'view_doctors',
            'view_patients',
            'view_treatment_requests',
            'view_treatment_plans',
            'view_appointments',
            'view_messages',
            'view_reviews',
            'moderate_reviews',
        ]);

        $doctorRole = Role::findByName('Doctor');
        $doctorRole->givePermissionTo([
            'update_doctor_profile',
            'view_patients',
            'view_treatment_requests',
            'respond_to_treatment_requests',
            'create_treatment_plans',
            'view_treatment_plans',
            'update_treatment_plans',
            'create_appointments',
            'view_appointments',
            'update_appointments',
            'send_messages',
            'view_messages',
            'view_reviews',
        ]);

        $patientRole = Role::findByName('Patient');
        $patientRole->givePermissionTo([
            'update_patient_profile',
            'create_treatment_requests',
            'view_treatment_requests',
            'view_treatment_plans',
            'approve_treatment_plans',
            'view_appointments',
            'send_messages',
            'view_messages',
            'create_reviews',
            'view_reviews',
        ]);
    }
}
