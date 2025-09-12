<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User Management
            'view users',
            'create users',
            'edit users',
            'delete users',
            'manage user roles',

            // Doctor Management
            'view doctors',
            'create doctors',
            'edit doctors',
            'delete doctors',
            'verify doctors',
            'manage doctor specializations',

            // Patient Management
            'view patients',
            'create patients',
            'edit patients',
            'delete patients',
            'view patient medical history',

            // Consultation Management
            'view consultations',
            'create consultations',
            'edit consultations',
            'delete consultations',
            'assign consultations',
            'start consultations',
            'complete consultations',
            'cancel consultations',
            'view consultation history',

            // Messaging
            'send messages',
            'view messages',
            'delete messages',
            'upload attachments',
            'download attachments',

            // Specializations
            'view specializations',
            'create specializations',
            'edit specializations',
            'delete specializations',

            // Reports & Analytics
            'view reports',
            'export data',
            'view analytics',

            // System Administration
            'manage system settings',
            'view system logs',
            'backup database',
            'manage notifications',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions

        // Admin Role - Full access
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Doctor Role
        $doctorRole = Role::create(['name' => 'doctor']);
        $doctorRole->givePermissionTo([
            // Profile management
            'edit users', // only their own profile
            
            // Consultation management
            'view consultations',
            'start consultations',
            'complete consultations',
            'view consultation history',
            
            // Messaging
            'send messages',
            'view messages',
            'upload attachments',
            'download attachments',
            
            // Patient interaction
            'view patients', // only assigned patients
            'view patient medical history', // only assigned patients
            
            // Specializations
            'view specializations',
        ]);

        // Patient Role
        $patientRole = Role::create(['name' => 'patient']);
        $patientRole->givePermissionTo([
            // Profile management
            'edit users', // only their own profile
            
            // Consultation management
            'create consultations',
            'view consultations', // only their own
            'cancel consultations', // only their own before assignment
            'view consultation history', // only their own
            
            // Messaging
            'send messages',
            'view messages',
            'upload attachments',
            'download attachments',
            
            // Doctors
            'view doctors',
            
            // Specializations
            'view specializations',
        ]);

        // Create a Support role for customer service
        $supportRole = Role::create(['name' => 'support']);
        $supportRole->givePermissionTo([
            'view users',
            'view doctors',
            'view patients',
            'view consultations',
            'view consultation history',
            'send messages',
            'view messages',
            'view specializations',
        ]);

        echo "Roles and permissions created successfully!\n";
        echo "- Admin: " . $adminRole->permissions->count() . " permissions\n";
        echo "- Doctor: " . $doctorRole->permissions->count() . " permissions\n";
        echo "- Patient: " . $patientRole->permissions->count() . " permissions\n";
        echo "- Support: " . $supportRole->permissions->count() . " permissions\n";
    }
}
