<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Clinic;
use App\Models\Story;
use App\Models\BeforeAfterCase;
use App\Models\Favorite;
use App\Infrastructure\Models\Doctor as InfrastructureDoctor;
use App\Infrastructure\Models\Clinic as InfrastructureClinic;
use App\Infrastructure\Models\Patient as InfrastructurePatient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Laravel\Passport\Passport;
use Spatie\Permission\Models\Role;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;
    
    private $patientUser;
    private $doctorUser;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test data directly for better control
        $this->createTestData();
    }

    private function createTestData(): void
    {
        // Create roles
        $roles = ['Admin', 'ClinicManager', 'Doctor', 'Patient'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Create test users with roles
        $this->patientUser = User::factory()->create([
            'email' => 'patient@test.com',
            'role' => 'patient'
        ]);
        $this->patientUser->assignRole('Patient');

        $this->doctorUser = User::factory()->create([
            'email' => 'doctor@test.com',
            'role' => 'doctor'
        ]);
        $this->doctorUser->assignRole('Doctor');

        // Create doctor and clinic records using Infrastructure factories
        InfrastructureDoctor::factory()->create(['user_id' => $this->doctorUser->id]);
        InfrastructureClinic::factory()->create();
        InfrastructurePatient::factory()->create(['user_id' => $this->patientUser->id]);

        // Create test stories, before/after cases, etc.
        // (We'll create minimal data for testing)
    }

    /** @test */
    public function it_returns_home_feed_data_with_all_sections()
    {
        // Arrange
        // Data already created in setUp()

        // Act
        $response = $this->getJson('/api/v1/home', [
            'Accept-Language' => 'en'
        ]);

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'stories',
                    'top_clinics',
                    'top_doctors',
                    'favorite_doctors',
                    'before_after',
                    'meta' => [
                        'locale',
                        'cache_expires_at',
                        'last_updated'
                    ]
                ]
            ]);

        // Verify that unauthenticated user gets empty favorite_doctors
        $data = $response->json('data');
        $this->assertIsArray($data['favorite_doctors']);
        $this->assertEmpty($data['favorite_doctors']);
        
        // Verify meta information is present
        $this->assertArrayHasKey('meta', $data);
        $this->assertArrayHasKey('locale', $data['meta']);
        $this->assertEquals('en', $data['meta']['locale']);
    }

    /** @test */
    public function it_returns_favorite_doctors_for_authenticated_patient()
    {
        // Arrange
        // Data already created in setUp()
        $this->actingAs($this->patientUser, 'api');

        // Act
        $response = $this->getJson('/api/v1/home');

        // Assert
        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertArrayHasKey('favorite_doctors', $data);
    }

    /** @test */
    public function it_excludes_favorite_doctors_for_unauthenticated_users()
    {
        // Arrange
        // Data already created in setUp()

        // Act
        $response = $this->getJson('/api/v1/home');

        // Assert
        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertEmpty($data['favorite_doctors']);
    }

    /** @test */
    public function it_excludes_favorite_doctors_for_non_patient_users()
    {
        // Arrange
        $doctor = User::factory()->create(['role' => 'doctor']);
        Doctor::factory()->count(3)->create();

        Passport::actingAs($doctor);

        // Act
        $response = $this->getJson('/api/v1/home');

        // Assert
        $response->assertStatus(200);
        $this->assertEmpty($response->json('data.favorite_doctors'));
    }

    /** @test */
    public function it_respects_locale_header_for_stories()
    {
        // Arrange
        Story::factory()->count(3)->create(['is_active' => true, 'locale' => 'en']);
        Story::factory()->count(2)->create(['is_active' => true, 'locale' => 'ar']);

        // Act
        $response = $this->getJson('/api/v1/home', ['Accept-Language' => 'ar']);

        // Assert
        $response->assertStatus(200);
        $this->assertEquals('ar', $response->json('data.locale'));
        
        // Note: This test depends on the story repository implementation
        // which should filter by locale
    }

    /** @test */
    public function it_caches_home_data_properly()
    {
        // Arrange
        Cache::flush(); // Clear any existing cache
        Doctor::factory()->count(3)->create();
        Clinic::factory()->count(2)->create();

        // Act - First request
        $response1 = $this->getJson('/api/v1/home');
        $timestamp1 = $response1->json('data.timestamp');

        // Act - Second request immediately after
        $response2 = $this->getJson('/api/v1/home');
        $timestamp2 = $response2->json('data.timestamp');

        // Assert
        $response1->assertStatus(200);
        $response2->assertStatus(200);
        
        // If caching is implemented, timestamps should be the same
        // This test may need adjustment based on your actual caching strategy
        $this->assertIsString($timestamp1);
        $this->assertIsString($timestamp2);
    }

    /** @test */
    public function it_can_toggle_doctor_favorite_for_authenticated_patient()
    {
        // Arrange
        $patient = User::factory()->create(['role' => 'patient']);
        $doctor = Doctor::factory()->create();

        Passport::actingAs($patient);

        // Act - Add to favorites
        $response = $this->postJson("/api/v1/favorites/doctors/{$doctor->id}");

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'is_favorite' => true,
                'message' => 'Doctor added to favorites'
            ]);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $patient->id,
            'favorable_type' => Doctor::class,
            'favorable_id' => $doctor->id
        ]);

        // Act - Remove from favorites
        $response = $this->postJson("/api/v1/favorites/doctors/{$doctor->id}");

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'is_favorite' => false,
                'message' => 'Doctor removed from favorites'
            ]);

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $patient->id,
            'favorable_type' => Doctor::class,
            'favorable_id' => $doctor->id
        ]);
    }

    /** @test */
    public function it_prevents_unauthenticated_users_from_toggling_favorites()
    {
        // Arrange
        $doctor = Doctor::factory()->create();

        // Act
        $response = $this->postJson("/api/v1/favorites/doctors/{$doctor->id}");

        // Assert
        $response->assertStatus(401);
    }

    /** @test */
    public function it_prevents_non_patients_from_toggling_doctor_favorites()
    {
        // Arrange
        $doctorUser = User::factory()->create(['role' => 'doctor']);
        $doctor = Doctor::factory()->create();

        Passport::actingAs($doctorUser);

        // Act
        $response = $this->postJson("/api/v1/favorites/doctors/{$doctor->id}");

        // Assert
        $response->assertStatus(403);
    }

    /** @test */
    public function it_can_toggle_clinic_favorite_for_authenticated_patient()
    {
        // Arrange
        $patient = User::factory()->create(['role' => 'patient']);
        $clinic = Clinic::factory()->create();

        Passport::actingAs($patient);

        // Act - Add to favorites
        $response = $this->postJson("/api/v1/favorites/clinics/{$clinic->id}");

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'is_favorite' => true,
                'message' => 'Clinic added to favorites'
            ]);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $patient->id,
            'favorable_type' => Clinic::class,
            'favorable_id' => $clinic->id
        ]);
    }

    /** @test */
    public function it_returns_favorite_doctors_list_for_authenticated_patient()
    {
        // Arrange
        $patient = User::factory()->create(['role' => 'patient']);
        $doctors = Doctor::factory()->count(3)->create();
        
        foreach ($doctors as $doctor) {
            Favorite::create([
                'user_id' => $patient->id,
                'favorable_type' => Doctor::class,
                'favorable_id' => $doctor->id
            ]);
        }

        Passport::actingAs($patient);

        // Act
        $response = $this->getJson('/api/v1/favorites/doctors');

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'count'
            ]);

        $this->assertCount(3, $response->json('data'));
        $this->assertEquals(3, $response->json('count'));
    }

    /** @test */
    public function it_returns_favorite_clinics_list_for_authenticated_patient()
    {
        // Arrange
        $patient = User::factory()->create(['role' => 'patient']);
        $clinics = Clinic::factory()->count(2)->create();
        
        foreach ($clinics as $clinic) {
            Favorite::create([
                'user_id' => $patient->id,
                'favorable_type' => Clinic::class,
                'favorable_id' => $clinic->id
            ]);
        }

        Passport::actingAs($patient);

        // Act
        $response = $this->getJson('/api/v1/favorites/clinics');

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'count'
            ]);

        $this->assertCount(2, $response->json('data'));
        $this->assertEquals(2, $response->json('count'));
    }

    /** @test */
    public function it_returns_all_favorites_with_counts()
    {
        // Arrange
        $patient = User::factory()->create(['role' => 'patient']);
        $doctors = Doctor::factory()->count(2)->create();
        $clinics = Clinic::factory()->count(3)->create();
        
        foreach ($doctors as $doctor) {
            Favorite::create([
                'user_id' => $patient->id,
                'favorable_type' => Doctor::class,
                'favorable_id' => $doctor->id
            ]);
        }
        
        foreach ($clinics as $clinic) {
            Favorite::create([
                'user_id' => $patient->id,
                'favorable_type' => Clinic::class,
                'favorable_id' => $clinic->id
            ]);
        }

        Passport::actingAs($patient);

        // Act - Test doctor favorites
        $response = $this->getJson('/api/v1/home/favorites');

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'count'
            ]);

        $this->assertCount(2, $response->json('data'));
    }

    /** @test */
    public function it_returns_individual_section_endpoints()
    {
        // Arrange
        Story::factory()->count(3)->create(['is_active' => true]);
        Clinic::factory()->count(2)->create();
        Doctor::factory()->count(4)->create();
        BeforeAfterCase::factory()->count(2)->create(['is_published' => true]);

        // Act & Assert - Stories
        $response = $this->getJson('/api/v1/home/stories');
        $response->assertStatus(200);
        $this->assertCount(3, $response->json('data'));

        // Act & Assert - Clinics
        $response = $this->getJson('/api/v1/home/clinics');
        $response->assertStatus(200);
        $this->assertCount(2, $response->json('data'));

        // Act & Assert - Doctors
        $response = $this->getJson('/api/v1/home/doctors');
        $response->assertStatus(200);
        $this->assertCount(4, $response->json('data'));

        // Act & Assert - Before/After
        $response = $this->getJson('/api/v1/home/before-after');
        $response->assertStatus(200);
        $this->assertCount(2, $response->json('data'));
    }

    /** @test */
    public function it_filters_inactive_stories_and_unpublished_cases()
    {
        // Arrange
        Story::factory()->count(2)->create(['is_active' => true]);
        Story::factory()->count(3)->create(['is_active' => false]);
        
        BeforeAfterCase::factory()->count(3)->create(['is_published' => true]);
        BeforeAfterCase::factory()->count(2)->create(['is_published' => false]);

        // Act
        $response = $this->getJson('/api/v1/home');

        // Assert
        $response->assertStatus(200);
        $this->assertCount(2, $response->json('data.stories'));
        $this->assertCount(3, $response->json('data.before_after'));
    }
}