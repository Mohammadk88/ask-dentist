<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthenticationGuardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_accessing_admin_panel_redirects_to_admin_login(): void
    {
        $response = $this->get('/admin');

        $response->assertStatus(302);
        $response->assertRedirect('/admin/login');
    }

    public function test_guest_accessing_doctor_portal_redirects_to_login(): void
    {
        $response = $this->get('/doctor');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_admin_panel_requires_authentication(): void
    {
        // Test that direct access to admin routes requires authentication
        $adminRoutes = [
            '/admin',
            '/admin/dashboard',
        ];

        foreach ($adminRoutes as $route) {
            $response = $this->get($route);
            $this->assertContains($response->getStatusCode(), [302, 403],
                "Route {$route} should redirect or deny access for guests");
        }
    }

    public function test_doctor_portal_requires_authentication(): void
    {
        // Test that direct access to doctor routes requires authentication
        $doctorRoutes = [
            '/doctor',
            '/doctor/treatment-plan-builder',
        ];

        foreach ($doctorRoutes as $route) {
            $response = $this->get($route);
            $this->assertContains($response->getStatusCode(), [302, 403],
                "Route {$route} should redirect or deny access for guests");
        }
    }

    public function test_authenticated_user_without_admin_role_cannot_access_admin(): void
    {
        // This test would require creating a user with doctor role
        // and verifying they can't access admin panel
        $this->markTestIncomplete('Role-based testing requires user and role setup');
    }

    public function test_authenticated_user_without_doctor_role_cannot_access_doctor_portal(): void
    {
        // This test would require creating a user with patient role
        // and verifying they can't access doctor portal
        $this->markTestIncomplete('Role-based testing requires user and role setup');
    }
}
