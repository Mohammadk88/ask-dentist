<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\VerificationToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Laravel\Passport\Passport;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles and permissions
        $this->createRolesAndPermissions();

        // Set up Passport for testing
        Passport::actingAs($this->createUser());
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
            'admin-access'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    protected function createUser($userType = 'patient', $verified = true): User
    {
        $user = User::factory()->create([
            'user_type' => $userType,
            'email_verified_at' => $verified ? now() : null,
            'password' => Hash::make('password123'),
        ]);

        // Assign role based on user type
        $roleName = ucfirst($userType);
        if (Role::where('name', $roleName)->exists()) {
            $user->assignRole($roleName);
        }

        return $user;
    }

    /** @test */
    public function patient_can_register_successfully()
    {
        Notification::fake();

        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '+1234567890',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
        ];

        $response = $this->postJson('/api/auth/register/patient', $userData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'message',
                    'user' => [
                        'id', 'name', 'email', 'user_type', 'phone',
                        'roles', 'profile_complete', 'verification_status'
                    ],
                    'token' => [
                        'access_token', 'token_type', 'expires_in', 'scopes'
                    ]
                ]);

        $this->assertDatabaseHas('users', [
            'email' => $userData['email'],
            'user_type' => 'patient',
        ]);

        // Verify user has patient role
        $user = User::where('email', $userData['email'])->first();
        $this->assertTrue($user->hasRole('Patient'));
    }

    /** @test */
    public function doctor_can_register_successfully()
    {
        Notification::fake();

        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '+1234567890',
            'license_number' => 'DOC123456',
            'specialization' => 'General Dentistry',
            'years_of_experience' => 5,
        ];

        $response = $this->postJson('/api/auth/register/doctor', $userData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'message',
                    'user' => [
                        'id', 'name', 'email', 'user_type', 'phone',
                        'roles', 'profile_complete', 'verification_status'
                    ],
                    'token' => [
                        'access_token', 'token_type', 'expires_in', 'scopes'
                    ]
                ]);

        $this->assertDatabaseHas('users', [
            'email' => $userData['email'],
            'user_type' => 'doctor',
        ]);

        // Verify user has doctor role
        $user = User::where('email', $userData['email'])->first();
        $this->assertTrue($user->hasRole('Doctor'));
    }

    /** @test */
    public function user_can_login_with_valid_credentials()
    {
        $user = $this->createUser('patient');

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'message',
                    'user' => [
                        'id', 'name', 'email', 'user_type', 'phone',
                        'roles', 'profile_complete', 'verification_status'
                    ],
                    'token' => [
                        'access_token', 'token_type', 'expires_in', 'scopes'
                    ]
                ]);

        $this->assertNotNull($user->fresh()->last_login_at);
    }

    /** @test */
    public function user_cannot_login_with_invalid_credentials()
    {
        $user = $this->createUser('patient');

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
                ->assertJson([
                    'message' => 'Invalid credentials'
                ]);
    }

    /** @test */
    public function authenticated_user_can_get_profile()
    {
        $user = $this->createUser('patient');
        Passport::actingAs($user);

        $response = $this->getJson('/api/me');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'user' => [
                        'id', 'name', 'email', 'user_type', 'phone',
                        'roles', 'permissions', 'profile_completion',
                        'available_actions'
                    ]
                ]);
    }

    /** @test */
    public function authenticated_user_can_logout()
    {
        $user = $this->createUser('patient');
        $token = $user->createToken('Test Token');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token->accessToken,
        ])->postJson('/api/auth/logout');

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Successfully logged out'
                ]);

        // Verify token is revoked
        $this->assertTrue($token->token->fresh()->revoked);
    }

    /** @test */
    public function authenticated_user_can_logout_all_devices()
    {
        $user = $this->createUser('patient');
        $token1 = $user->createToken('Token 1');
        $token2 = $user->createToken('Token 2');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token1->accessToken,
        ])->postJson('/api/auth/logout', [
            'revoke_all' => true
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'message', 'tokens_revoked'
                ]);

        // Verify all tokens are revoked
        $this->assertTrue($token1->token->fresh()->revoked);
        $this->assertTrue($token2->token->fresh()->revoked);
    }

    /** @test */
    public function authenticated_user_can_refresh_token()
    {
        $user = $this->createUser('patient');
        $token = $user->createToken('Test Token');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token->accessToken,
        ])->postJson('/api/auth/refresh');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'token' => [
                        'access_token', 'token_type', 'expires_in', 'scopes'
                    ],
                    'user' => [
                        'id', 'name', 'email', 'user_type', 'roles'
                    ]
                ]);

        // Verify old token is revoked
        $this->assertTrue($token->token->fresh()->revoked);
    }

    /** @test */
    public function authenticated_user_can_get_token_info()
    {
        $user = $this->createUser('patient');
        $token = $user->createToken('Test Token', ['read-profile']);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token->accessToken,
        ])->getJson('/api/auth/token-info');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'token' => [
                        'id', 'name', 'scopes', 'created_at', 'expires_at',
                        'time_to_expiry_seconds', 'can_refresh', 'is_revoked'
                    ],
                    'session' => [
                        'ip_address', 'user_agent', 'last_activity'
                    ]
                ]);
    }

    /** @test */
    public function authenticated_user_can_list_active_tokens()
    {
        $user = $this->createUser('patient');
        $token1 = $user->createToken('Token 1');
        $token2 = $user->createToken('Token 2');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token1->accessToken,
        ])->getJson('/api/auth/tokens');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'tokens' => [
                        '*' => [
                            'id', 'name', 'scopes', 'created_at',
                            'last_used_at', 'expires_at', 'is_current'
                        ]
                    ],
                    'total_tokens'
                ]);

        $this->assertEquals(2, $response->json('total_tokens'));
    }

    /** @test */
    public function authenticated_user_can_revoke_specific_token()
    {
        $user = $this->createUser('patient');
        $token1 = $user->createToken('Token 1');
        $token2 = $user->createToken('Token 2');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token1->accessToken,
        ])->postJson('/api/auth/revoke-token', [
            'token_id' => $token2->token->id
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Token revoked successfully'
                ]);

        // Verify specific token is revoked
        $this->assertTrue($token2->token->fresh()->revoked);
        $this->assertFalse($token1->token->fresh()->revoked);
    }

    /** @test */
    public function user_can_request_password_reset()
    {
        Mail::fake();
        $user = $this->createUser('patient');

        $response = $this->postJson('/api/auth/forgot-password', [
            'email' => $user->email
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Password reset link sent to your email'
                ]);

        Mail::assertSent(\Illuminate\Auth\Notifications\ResetPassword::class);
    }

    /** @test */
    public function user_can_reset_password_with_valid_token()
    {
        $user = $this->createUser('patient');
        $token = Password::createToken($user);

        $response = $this->postJson('/api/auth/reset-password', [
            'email' => $user->email,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
            'token' => $token,
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Password has been reset successfully'
                ]);

        // Verify password was changed
        $this->assertTrue(Hash::check('newpassword123', $user->fresh()->password));
    }

    /** @test */
    public function authenticated_user_can_change_password()
    {
        $user = $this->createUser('patient');
        Passport::actingAs($user);

        $response = $this->postJson('/api/auth/change-password', [
            'current_password' => 'password123',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Password changed successfully'
                ]);

        // Verify password was changed
        $this->assertTrue(Hash::check('newpassword123', $user->fresh()->password));
    }

    /** @test */
    public function user_can_send_email_verification()
    {
        Notification::fake();
        $user = $this->createUser('patient', false);
        Passport::actingAs($user);

        $response = $this->postJson('/api/auth/send-email-verification');

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Verification email sent successfully'
                ]);

        $this->assertDatabaseHas('verification_tokens', [
            'user_id' => $user->id,
            'type' => 'email',
        ]);
    }

    /** @test */
    public function user_can_verify_email_with_valid_token()
    {
        $user = $this->createUser('patient', false);
        $verificationToken = VerificationToken::create([
            'user_id' => $user->id,
            'type' => 'email',
            'token' => \Str::uuid(),
            'expires_at' => now()->addHours(24),
        ]);

        $response = $this->postJson('/api/auth/verify-email', [
            'token' => $verificationToken->token
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Email verified successfully'
                ]);

        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    /** @test */
    public function user_can_send_phone_verification()
    {
        Notification::fake();
        $user = $this->createUser('patient');
        Passport::actingAs($user);

        $response = $this->postJson('/api/auth/send-phone-verification');

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Verification code sent to your phone'
                ]);

        $this->assertDatabaseHas('verification_tokens', [
            'user_id' => $user->id,
            'type' => 'phone',
        ]);
    }

    /** @test */
    public function user_can_verify_phone_with_valid_code()
    {
        $user = $this->createUser('patient');
        $verificationToken = VerificationToken::create([
            'user_id' => $user->id,
            'type' => 'phone',
            'token' => '123456',
            'expires_at' => now()->addMinutes(15),
        ]);

        Passport::actingAs($user);

        $response = $this->postJson('/api/auth/verify-phone', [
            'code' => '123456'
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Phone number verified successfully'
                ]);

        $this->assertNotNull($user->fresh()->phone_verified_at);
    }

    /** @test */
    public function rate_limiting_works_on_login_endpoint()
    {
        $user = $this->createUser('patient');

        // Make multiple failed login attempts
        for ($i = 0; $i < 11; $i++) {
            $response = $this->postJson('/api/auth/login', [
                'email' => $user->email,
                'password' => 'wrongpassword',
            ]);

            if ($i < 10) {
                $response->assertStatus(401);
            } else {
                // 11th attempt should be rate limited
                $response->assertStatus(429);
            }
        }
    }

    /** @test */
    public function registration_validation_works()
    {
        $response = $this->postJson('/api/auth/register/patient', [
            'name' => '',
            'email' => 'invalid-email',
            'password' => '123',
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    /** @test */
    public function login_validation_works()
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => '',
            'password' => '',
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['email', 'password']);
    }
}
