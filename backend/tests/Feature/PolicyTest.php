<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\TreatmentRequest;
use App\Models\Profile;
use App\Models\Clinic;
use App\Policies\TreatmentRequestPolicy;
use App\Policies\ProfilePolicy;
use App\Policies\ClinicPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PolicyTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles and permissions
        $this->createRolesAndPermissions();
    }

    protected function createRolesAndPermissions(): void
    {
        // Create roles
        Role::create(['name' => 'Patient']);
        Role::create(['name' => 'Doctor']);
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'ClinicManager']);

        // Create permissions
        $permissions = [
            'read-profile', 'write-profile', 'create-consultation',
            'doctor-access', 'create-consultation-response',
            'manage-users', 'manage-doctors', 'manage-patients',
            'admin-access', 'view-all-consultations'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign permissions to roles
        $patientRole = Role::where('name', 'Patient')->first();
        $patientRole->givePermissionTo(['read-profile', 'write-profile', 'create-consultation']);

        $doctorRole = Role::where('name', 'Doctor')->first();
        $doctorRole->givePermissionTo([
            'read-profile', 'write-profile', 'doctor-access',
            'create-consultation-response'
        ]);

        $adminRole = Role::where('name', 'Admin')->first();
        $adminRole->givePermissionTo([
            'manage-users', 'manage-doctors', 'manage-patients',
            'admin-access', 'view-all-consultations'
        ]);

        $managerRole = Role::where('name', 'ClinicManager')->first();
        $managerRole->givePermissionTo([
            'manage-doctors', 'manage-patients', 'admin-access'
        ]);
    }

    protected function createUser($userType = 'patient'): User
    {
        $user = User::factory()->create([
            'user_type' => $userType,
            'email_verified_at' => now(),
        ]);

        // Assign role based on user type
        $roleName = ucfirst($userType);
        if (Role::where('name', $roleName)->exists()) {
            $user->assignRole($roleName);
        }

        return $user;
    }

    /** @test */
    public function patient_can_view_own_treatment_request()
    {
        $patient = $this->createUser('patient');

        $treatmentRequest = TreatmentRequest::factory()->create([
            'patient_id' => $patient->id,
            'status' => 'pending'
        ]);

        $policy = new TreatmentRequestPolicy();

        $this->assertTrue($policy->view($patient, $treatmentRequest));
    }

    /** @test */
    public function patient_cannot_view_other_patients_treatment_request()
    {
        $patient1 = $this->createUser('patient');
        $patient2 = $this->createUser('patient');

        $treatmentRequest = TreatmentRequest::factory()->create([
            'patient_id' => $patient2->id,
            'status' => 'pending'
        ]);

        $policy = new TreatmentRequestPolicy();

        $this->assertFalse($policy->view($patient1, $treatmentRequest));
    }

    /** @test */
    public function doctor_can_view_assigned_treatment_request()
    {
        $doctor = $this->createUser('doctor');
        $patient = $this->createUser('patient');

        $treatmentRequest = TreatmentRequest::factory()->create([
            'patient_id' => $patient->id,
            'assigned_doctor_id' => $doctor->id,
            'status' => 'assigned'
        ]);

        $policy = new TreatmentRequestPolicy();

        $this->assertTrue($policy->view($doctor, $treatmentRequest));
    }

    /** @test */
    public function doctor_cannot_view_unassigned_treatment_request()
    {
        $doctor = $this->createUser('doctor');
        $patient = $this->createUser('patient');

        $treatmentRequest = TreatmentRequest::factory()->create([
            'patient_id' => $patient->id,
            'assigned_doctor_id' => null,
            'status' => 'pending'
        ]);

        $policy = new TreatmentRequestPolicy();

        $this->assertFalse($policy->view($doctor, $treatmentRequest));
    }

    /** @test */
    public function admin_can_view_any_treatment_request()
    {
        $admin = $this->createUser('admin');
        $patient = $this->createUser('patient');

        $treatmentRequest = TreatmentRequest::factory()->create([
            'patient_id' => $patient->id,
            'status' => 'pending'
        ]);

        $policy = new TreatmentRequestPolicy();

        $this->assertTrue($policy->view($admin, $treatmentRequest));
    }

    /** @test */
    public function patient_can_update_own_treatment_request_when_pending()
    {
        $patient = $this->createUser('patient');

        $treatmentRequest = TreatmentRequest::factory()->create([
            'patient_id' => $patient->id,
            'status' => 'pending'
        ]);

        $policy = new TreatmentRequestPolicy();

        $this->assertTrue($policy->update($patient, $treatmentRequest));
    }

    /** @test */
    public function patient_cannot_update_treatment_request_when_assigned()
    {
        $patient = $this->createUser('patient');
        $doctor = $this->createUser('doctor');

        $treatmentRequest = TreatmentRequest::factory()->create([
            'patient_id' => $patient->id,
            'assigned_doctor_id' => $doctor->id,
            'status' => 'assigned'
        ]);

        $policy = new TreatmentRequestPolicy();

        $this->assertFalse($policy->update($patient, $treatmentRequest));
    }

    /** @test */
    public function doctor_can_update_assigned_treatment_request()
    {
        $doctor = $this->createUser('doctor');
        $patient = $this->createUser('patient');

        $treatmentRequest = TreatmentRequest::factory()->create([
            'patient_id' => $patient->id,
            'assigned_doctor_id' => $doctor->id,
            'status' => 'assigned'
        ]);

        $policy = new TreatmentRequestPolicy();

        $this->assertTrue($policy->update($doctor, $treatmentRequest));
    }

    /** @test */
    public function patient_can_view_own_profile()
    {
        $patient = $this->createUser('patient');

        $profile = Profile::factory()->create([
            'user_id' => $patient->id
        ]);

        $policy = new ProfilePolicy();

        $this->assertTrue($policy->view($patient, $profile));
    }

    /** @test */
    public function patient_cannot_view_other_patients_profile()
    {
        $patient1 = $this->createUser('patient');
        $patient2 = $this->createUser('patient');

        $profile = Profile::factory()->create([
            'user_id' => $patient2->id
        ]);

        $policy = new ProfilePolicy();

        $this->assertFalse($policy->view($patient1, $profile));
    }

    /** @test */
    public function doctor_can_view_patient_profile_with_consent()
    {
        $doctor = $this->createUser('doctor');
        $patient = $this->createUser('patient');

        $profile = Profile::factory()->create([
            'user_id' => $patient->id,
            'medical_history_consent' => true
        ]);

        // Create treatment request to establish doctor-patient relationship
        TreatmentRequest::factory()->create([
            'patient_id' => $patient->id,
            'assigned_doctor_id' => $doctor->id,
            'status' => 'assigned'
        ]);

        $policy = new ProfilePolicy();

        $this->assertTrue($policy->view($doctor, $profile));
    }

    /** @test */
    public function doctor_cannot_view_patient_profile_without_consent()
    {
        $doctor = $this->createUser('doctor');
        $patient = $this->createUser('patient');

        $profile = Profile::factory()->create([
            'user_id' => $patient->id,
            'medical_history_consent' => false
        ]);

        $policy = new ProfilePolicy();

        $this->assertFalse($policy->view($doctor, $profile));
    }

    /** @test */
    public function doctor_cannot_view_unrelated_patient_profile()
    {
        $doctor = $this->createUser('doctor');
        $patient = $this->createUser('patient');

        $profile = Profile::factory()->create([
            'user_id' => $patient->id,
            'medical_history_consent' => true
        ]);

        // No treatment request relationship
        $policy = new ProfilePolicy();

        $this->assertFalse($policy->view($doctor, $profile));
    }

    /** @test */
    public function admin_can_view_any_profile()
    {
        $admin = $this->createUser('admin');
        $patient = $this->createUser('patient');

        $profile = Profile::factory()->create([
            'user_id' => $patient->id
        ]);

        $policy = new ProfilePolicy();

        $this->assertTrue($policy->view($admin, $profile));
    }

    /** @test */
    public function clinic_manager_can_view_clinic_in_scope()
    {
        $manager = $this->createUser('clinicmanager');

        $clinic = Clinic::factory()->create([
            'manager_id' => $manager->id,
            'name' => 'Test Clinic'
        ]);

        $policy = new ClinicPolicy();

        $this->assertTrue($policy->view($manager, $clinic));
    }

    /** @test */
    public function clinic_manager_cannot_view_clinic_outside_scope()
    {
        $manager1 = $this->createUser('clinicmanager');
        $manager2 = $this->createUser('clinicmanager');

        $clinic = Clinic::factory()->create([
            'manager_id' => $manager2->id,
            'name' => 'Test Clinic'
        ]);

        $policy = new ClinicPolicy();

        $this->assertFalse($policy->view($manager1, $clinic));
    }

    /** @test */
    public function admin_can_view_any_clinic()
    {
        $admin = $this->createUser('admin');
        $manager = $this->createUser('clinicmanager');

        $clinic = Clinic::factory()->create([
            'manager_id' => $manager->id,
            'name' => 'Test Clinic'
        ]);

        $policy = new ClinicPolicy();

        $this->assertTrue($policy->view($admin, $clinic));
    }

    /** @test */
    public function clinic_manager_can_manage_clinic_in_scope()
    {
        $manager = $this->createUser('clinicmanager');

        $clinic = Clinic::factory()->create([
            'manager_id' => $manager->id,
            'name' => 'Test Clinic'
        ]);

        $policy = new ClinicPolicy();

        $this->assertTrue($policy->update($manager, $clinic));
        $this->assertTrue($policy->delete($manager, $clinic));
    }

    /** @test */
    public function doctor_cannot_manage_any_clinic()
    {
        $doctor = $this->createUser('doctor');
        $manager = $this->createUser('clinicmanager');

        $clinic = Clinic::factory()->create([
            'manager_id' => $manager->id,
            'name' => 'Test Clinic'
        ]);

        $policy = new ClinicPolicy();

        $this->assertFalse($policy->update($doctor, $clinic));
        $this->assertFalse($policy->delete($doctor, $clinic));
    }

    /** @test */
    public function patient_cannot_manage_any_clinic()
    {
        $patient = $this->createUser('patient');
        $manager = $this->createUser('clinicmanager');

        $clinic = Clinic::factory()->create([
            'manager_id' => $manager->id,
            'name' => 'Test Clinic'
        ]);

        $policy = new ClinicPolicy();

        $this->assertFalse($policy->view($patient, $clinic));
        $this->assertFalse($policy->update($patient, $clinic));
        $this->assertFalse($policy->delete($patient, $clinic));
    }
}
