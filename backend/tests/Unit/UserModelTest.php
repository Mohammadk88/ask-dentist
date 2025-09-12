<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\VerificationToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use App\Notifications\EmailVerificationNotification;
use App\Notifications\PhoneVerificationNotification;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create basic roles
        Role::create(['name' => 'Patient']);
        Role::create(['name' => 'Doctor']);
        Role::create(['name' => 'Admin']);
    }

    /** @test */
    public function user_password_is_hashed_when_set()
    {
        $user = User::factory()->create([
            'password' => 'plaintext-password'
        ]);

        $this->assertTrue(Hash::check('plaintext-password', $user->password));
        $this->assertNotEquals('plaintext-password', $user->password);
    }

    /** @test */
    public function user_has_email_verified_when_timestamp_set()
    {
        $user = User::factory()->create([
            'email_verified_at' => now()
        ]);

        $this->assertTrue($user->hasVerifiedEmail());
    }

    /** @test */
    public function user_does_not_have_email_verified_when_timestamp_null()
    {
        $user = User::factory()->create([
            'email_verified_at' => null
        ]);

        $this->assertFalse($user->hasVerifiedEmail());
    }

    /** @test */
    public function user_has_phone_verified_when_timestamp_set()
    {
        $user = User::factory()->create([
            'phone_verified_at' => now()
        ]);

        $this->assertTrue($user->hasVerifiedPhone());
    }

    /** @test */
    public function user_does_not_have_phone_verified_when_timestamp_null()
    {
        $user = User::factory()->create([
            'phone_verified_at' => null
        ]);

        $this->assertFalse($user->hasVerifiedPhone());
    }

    /** @test */
    public function user_can_have_multiple_roles()
    {
        $user = User::factory()->create();

        $user->assignRole(['Patient', 'Doctor']);

        $this->assertTrue($user->hasRole('Patient'));
        $this->assertTrue($user->hasRole('Doctor'));
        $this->assertEquals(2, $user->roles->count());
    }

    /** @test */
    public function user_can_check_specific_role()
    {
        $user = User::factory()->create();

        $user->assignRole('Patient');

        $this->assertTrue($user->hasRole('Patient'));
        $this->assertFalse($user->hasRole('Doctor'));
        $this->assertFalse($user->hasRole('Admin'));
    }

    /** @test */
    public function user_can_check_any_of_multiple_roles()
    {
        $user = User::factory()->create();

        $user->assignRole('Patient');

        $this->assertTrue($user->hasAnyRole(['Patient', 'Doctor']));
        $this->assertFalse($user->hasAnyRole(['Doctor', 'Admin']));
    }

    /** @test */
    public function user_full_name_returns_name_when_available()
    {
        $user = User::factory()->create([
            'name' => 'John Doe'
        ]);

        $this->assertEquals('John Doe', $user->full_name);
    }

    /** @test */
    public function user_age_calculated_correctly_from_date_of_birth()
    {
        $user = User::factory()->create([
            'date_of_birth' => Carbon::now()->subYears(25)->format('Y-m-d')
        ]);

        $this->assertEquals(25, $user->age);
    }

    /** @test */
    public function user_age_returns_null_when_no_date_of_birth()
    {
        $user = User::factory()->create([
            'date_of_birth' => null
        ]);

        $this->assertNull($user->age);
    }

    /** @test */
    public function user_can_have_profile_relationship()
    {
        $user = User::factory()->create();

        // Test that profile relationship exists (even if null)
        $this->assertNull($user->profile);
    }

    /** @test */
    public function user_can_have_treatment_requests_as_patient()
    {
        $user = User::factory()->create(['user_type' => 'patient']);

        // Test that treatmentRequests relationship exists
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $user->treatmentRequests);
        $this->assertEquals(0, $user->treatmentRequests->count());
    }

    /** @test */
    public function user_can_have_assigned_requests_as_doctor()
    {
        $user = User::factory()->create(['user_type' => 'doctor']);

        // Test that assignedRequests relationship exists
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $user->assignedRequests);
        $this->assertEquals(0, $user->assignedRequests->count());
    }

    /** @test */
    public function user_can_have_verification_tokens()
    {
        $user = User::factory()->create();

        $verificationToken = VerificationToken::create([
            'user_id' => $user->id,
            'type' => 'email',
            'token' => 'test-token',
            'expires_at' => now()->addHours(24),
        ]);

        $this->assertEquals(1, $user->verificationTokens->count());
        $this->assertEquals('test-token', $user->verificationTokens->first()->token);
    }

    /** @test */
    public function user_has_correct_fillable_attributes()
    {
        $user = new User();

        $expectedFillable = [
            'name', 'email', 'password', 'user_type', 'phone',
            'date_of_birth', 'gender', 'last_login_at',
            'phone_verified_at', 'email_verified_at'
        ];

        $this->assertEquals($expectedFillable, $user->getFillable());
    }

    /** @test */
    public function user_has_correct_hidden_attributes()
    {
        $user = new User();

        $expectedHidden = [
            'password', 'remember_token'
        ];

        $this->assertEquals($expectedHidden, $user->getHidden());
    }

    /** @test */
    public function user_has_correct_casts()
    {
        $user = new User();

        $expectedCasts = [
            'id' => 'int',
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'date_of_birth' => 'date',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];

        $this->assertEquals($expectedCasts, $user->getCasts());
    }

    /** @test */
    public function user_factory_creates_valid_patient()
    {
        $user = User::factory()->patient()->create();

        $this->assertEquals('patient', $user->user_type);
        $this->assertNotNull($user->name);
        $this->assertNotNull($user->email);
        $this->assertNotNull($user->password);
    }

    /** @test */
    public function user_factory_creates_valid_doctor()
    {
        $user = User::factory()->doctor()->create();

        $this->assertEquals('doctor', $user->user_type);
        $this->assertNotNull($user->name);
        $this->assertNotNull($user->email);
        $this->assertNotNull($user->password);
    }

    /** @test */
    public function user_can_send_email_verification_notification()
    {
        Notification::fake();

        $user = User::factory()->create([
            'email_verified_at' => null
        ]);

        $user->sendEmailVerificationNotification();

        Notification::assertSentTo($user, EmailVerificationNotification::class);
    }

    /** @test */
    public function user_scope_filters_by_type()
    {
        User::factory()->create(['user_type' => 'patient']);
        User::factory()->create(['user_type' => 'doctor']);
        User::factory()->create(['user_type' => 'patient']);

        $patients = User::ofType('patient')->get();
        $doctors = User::ofType('doctor')->get();

        $this->assertEquals(2, $patients->count());
        $this->assertEquals(1, $doctors->count());
    }

    /** @test */
    public function user_scope_filters_verified_users()
    {
        User::factory()->create(['email_verified_at' => now()]);
        User::factory()->create(['email_verified_at' => null]);
        User::factory()->create(['email_verified_at' => now()]);

        $verified = User::verified()->get();
        $unverified = User::unverified()->get();

        $this->assertEquals(2, $verified->count());
        $this->assertEquals(1, $unverified->count());
    }
}
