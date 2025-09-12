<?php

namespace Tests\Browser;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class WebBuilderSmokeTest extends DuskTestCase
{
    /** @test */
    public function user_can_access_admin_dashboard()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@example.com',
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->visit('/admin/login')
                    ->type('email', $admin->email)
                    ->type('password', 'password')
                    ->press('Sign in')
                    ->assertPathIs('/admin')
                    ->assertSee('Dashboard');
        });
    }

    /** @test */
    public function admin_can_manage_doctors()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin, 'web')
                    ->visit('/admin')
                    ->clickLink('Doctors')
                    ->assertSee('Doctors')
                    ->assertSee('Create')
                    ->click('@create-doctor-button')
                    ->assertSee('Create Doctor')
                    ->type('name', 'Dr. Test Doctor')
                    ->type('email', 'doctor@example.com')
                    ->select('specialization', 'general_dentistry')
                    ->press('Create')
                    ->assertSee('Doctor created successfully');
        });
    }

    /** @test */
    public function admin_can_manage_patients()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin, 'web')
                    ->visit('/admin')
                    ->clickLink('Patients')
                    ->assertSee('Patients')
                    ->assertSee('Create')
                    ->click('@create-patient-button')
                    ->assertSee('Create Patient')
                    ->type('name', 'John Patient')
                    ->type('email', 'patient@example.com')
                    ->type('phone', '+1234567890')
                    ->press('Create')
                    ->assertSee('Patient created successfully');
        });
    }

    /** @test */
    public function admin_can_view_consultations_dashboard()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        // Create test data
        $doctor = User::factory()->create(['role' => 'doctor']);
        $patient = User::factory()->create(['role' => 'patient']);
        
        \App\Models\TreatmentRequest::factory()->count(5)->create([
            'doctor_id' => $doctor->id,
            'patient_id' => $patient->id,
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin, 'web')
                    ->visit('/admin')
                    ->clickLink('Consultations')
                    ->assertSee('Consultations')
                    ->assertSee('pending')
                    ->assertSee('confirmed')
                    ->assertPresent('@consultation-table')
                    ->assertSeeIn('@consultation-count', '5');
        });
    }

    /** @test */
    public function admin_can_access_real_time_chat()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin, 'web')
                    ->visit('/admin')
                    ->visit('/chat')
                    ->assertSee('Live Chat')
                    ->assertSee('Conversations')
                    ->assertPresent('@chat-sidebar')
                    ->assertPresent('@chat-interface');
        });
    }

    /** @test */
    public function admin_can_view_analytics_dashboard()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin, 'web')
                    ->visit('/admin')
                    ->assertSee('Total Consultations')
                    ->assertSee('Active Doctors')
                    ->assertSee('Total Patients')
                    ->assertSee('Revenue')
                    ->assertPresent('@analytics-widget')
                    ->assertPresent('@chart-container');
        });
    }

    /** @test */
    public function doctor_can_access_their_dashboard()
    {
        $doctor = User::factory()->create(['role' => 'doctor']);

        $this->browse(function (Browser $browser) use ($doctor) {
            $browser->loginAs($doctor, 'web')
                    ->visit('/admin')
                    ->assertSee('My Consultations')
                    ->assertSee('Upcoming Appointments')
                    ->assertSee('Treatment Plans')
                    ->assertPresent('@doctor-dashboard');
        });
    }

    /** @test */
    public function responsive_design_works_on_mobile()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin, 'web')
                    ->resize(375, 667) // iPhone dimensions
                    ->visit('/admin')
                    ->assertSee('Dashboard')
                    ->assertPresent('@mobile-menu-toggle')
                    ->click('@mobile-menu-toggle')
                    ->assertPresent('@mobile-navigation')
                    ->assertSee('Doctors')
                    ->assertSee('Patients');
        });
    }

    /** @test */
    public function search_functionality_works()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $doctor = User::factory()->create([
            'role' => 'doctor',
            'name' => 'Dr. Searchable Doctor',
        ]);

        $this->browse(function (Browser $browser) use ($admin, $doctor) {
            $browser->loginAs($admin, 'web')
                    ->visit('/admin/doctors')
                    ->type('@search-input', 'Searchable')
                    ->keys('@search-input', '{enter}')
                    ->waitForText('Dr. Searchable Doctor')
                    ->assertSee('Dr. Searchable Doctor');
        });
    }

    /** @test */
    public function pagination_works_correctly()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        // Create enough records to trigger pagination
        User::factory()->count(30)->create(['role' => 'doctor']);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin, 'web')
                    ->visit('/admin/doctors')
                    ->assertPresent('@pagination-controls')
                    ->assertSee('Next')
                    ->click('@next-page')
                    ->waitForLocation('/admin/doctors?page=2')
                    ->assertPresent('@pagination-current-page');
        });
    }

    /** @test */
    public function form_validation_works()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin, 'web')
                    ->visit('/admin/doctors')
                    ->click('@create-doctor-button')
                    ->press('Create')
                    ->assertSee('The name field is required')
                    ->assertSee('The email field is required')
                    ->assertPresent('@validation-errors');
        });
    }

    /** @test */
    public function user_can_logout()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin, 'web')
                    ->visit('/admin')
                    ->click('@user-menu')
                    ->clickLink('Logout')
                    ->assertPathIs('/admin/login')
                    ->assertSee('Sign in');
        });
    }

    /** @test */
    public function error_pages_display_correctly()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin, 'web')
                    ->visit('/admin/non-existent-page')
                    ->assertSee('404')
                    ->assertSee('Page Not Found')
                    ->assertPresent('@error-page');
        });
    }
}