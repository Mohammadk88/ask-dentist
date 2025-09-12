<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

class RoleBasedAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        Role::create(['name' => 'admin', 'guard_name' => 'web']);
        Role::create(['name' => 'doctor', 'guard_name' => 'web']);
        Role::create(['name' => 'patient', 'guard_name' => 'web']);
    }

    public function test_admin_user_can_access_admin_panel(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get('/admin');

        // Admin should be able to access (not get a 403)
        $this->assertNotEquals(403, $response->getStatusCode());
    }

    public function test_doctor_user_can_access_doctor_portal(): void
    {
        $doctor = User::factory()->create([
            'email' => 'doctor@test.com',
            'password' => bcrypt('password'),
        ]);
        $doctor->assignRole('doctor');

        $response = $this->actingAs($doctor)->get('/doctor');

        // Doctor should be able to access (200 or successful redirect)
        $this->assertContains($response->getStatusCode(), [200, 302]);

        // If it's a redirect, it shouldn't be to login
        if ($response->getStatusCode() === 302) {
            $this->assertNotEquals('/login', $response->getTargetUrl());
        }
    }

    public function test_doctor_user_cannot_access_admin_panel(): void
    {
        $doctor = User::factory()->create([
            'email' => 'doctor@test.com',
            'password' => bcrypt('password'),
        ]);
        $doctor->assignRole('doctor');

        $response = $this->actingAs($doctor)->get('/admin');

        // Doctor should be redirected away from admin
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertStringContainsString('/doctor', $response->getTargetUrl());
    }

    public function test_patient_user_cannot_access_doctor_portal(): void
    {
        $patient = User::factory()->create([
            'email' => 'patient@test.com',
            'password' => bcrypt('password'),
        ]);
        $patient->assignRole('patient');

        $response = $this->actingAs($patient)->get('/doctor');

        // Patient should be redirected away from doctor portal
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertNotEquals('/doctor', $response->getTargetUrl());
    }

    public function test_patient_user_cannot_access_admin_panel(): void
    {
        $patient = User::factory()->create([
            'email' => 'patient@test.com',
            'password' => bcrypt('password'),
        ]);
        $patient->assignRole('patient');

        $response = $this->actingAs($patient)->get('/admin');

        // Patient should be redirected away from admin
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertNotEquals('/admin', $response->getTargetUrl());
    }

    public function test_user_without_roles_cannot_access_protected_areas(): void
    {
        $user = User::factory()->create([
            'email' => 'norole@test.com',
            'password' => bcrypt('password'),
        ]);
        // No roles assigned

        $adminResponse = $this->actingAs($user)->get('/admin');
        $doctorResponse = $this->actingAs($user)->get('/doctor');

        // User without roles should be redirected from both areas
        $this->assertEquals(302, $adminResponse->getStatusCode());
        $this->assertEquals(302, $doctorResponse->getStatusCode());

        // Should be redirected to login
        $this->assertStringContainsString('/login', $adminResponse->getTargetUrl());
        $this->assertStringContainsString('/login', $doctorResponse->getTargetUrl());
    }
}
