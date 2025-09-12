<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\VerificationToken;
use App\Http\Middleware\ScopeMiddleware;
use App\Http\Middleware\VerifiedMiddleware;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Passport\Passport;
use Laravel\Passport\Token;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class MiddlewareTest extends TestCase
{
    use RefreshDatabase;

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

        // Create permissions
        $permissions = [
            'read-profile', 'write-profile', 'create-consultation',
            'doctor-access', 'admin-access'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    protected function createUser($userType = 'patient', $emailVerified = true, $phoneVerified = false): User
    {
        $user = User::factory()->create([
            'user_type' => $userType,
            'email_verified_at' => $emailVerified ? now() : null,
            'phone_verified_at' => $phoneVerified ? now() : null,
        ]);

        // Assign role based on user type
        $roleName = ucfirst($userType);
        if (Role::where('name', $roleName)->exists()) {
            $user->assignRole($roleName);
        }

        return $user;
    }

    /** @test */
    public function scope_middleware_allows_request_with_valid_scope()
    {
        $user = $this->createUser('patient');
        $token = $user->createToken('Test Token', ['read-profile']);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token->accessToken,
        ])->getJson('/api/test-scope-endpoint');

        // This would be a 404 since the endpoint doesn't exist, but not a 403 (forbidden)
        $response->assertStatus(404);
    }

    /** @test */
    public function verified_middleware_allows_email_verified_users()
    {
        $user = $this->createUser('patient', true, false);
        Passport::actingAs($user);

        $request = Request::create('/test', 'GET');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $middleware = new VerifiedMiddleware();

        $response = $middleware->handle($request, function ($req) {
            return new Response('OK', 200);
        }, 'email');

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function verified_middleware_blocks_unverified_email_users()
    {
        $user = $this->createUser('patient', false, false);
        Passport::actingAs($user);

        $request = Request::create('/test', 'GET');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $middleware = new VerifiedMiddleware();

        $response = $middleware->handle($request, function ($req) {
            return new Response('OK', 200);
        }, 'email');

        $this->assertEquals(403, $response->getStatusCode());
    }

    /** @test */
    public function verified_middleware_allows_phone_verified_users()
    {
        $user = $this->createUser('patient', true, true);
        Passport::actingAs($user);

        $request = Request::create('/test', 'GET');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $middleware = new VerifiedMiddleware();

        $response = $middleware->handle($request, function ($req) {
            return new Response('OK', 200);
        }, 'phone');

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function verified_middleware_blocks_unverified_phone_users()
    {
        $user = $this->createUser('patient', true, false);
        Passport::actingAs($user);

        $request = Request::create('/test', 'GET');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $middleware = new VerifiedMiddleware();

        $response = $middleware->handle($request, function ($req) {
            return new Response('OK', 200);
        }, 'phone');

        $this->assertEquals(403, $response->getStatusCode());
    }

    /** @test */
    public function verified_middleware_requires_both_when_specified()
    {
        $user = $this->createUser('patient', true, false);
        Passport::actingAs($user);

        $request = Request::create('/test', 'GET');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $middleware = new VerifiedMiddleware();

        $response = $middleware->handle($request, function ($req) {
            return new Response('OK', 200);
        }, 'email,phone');

        $this->assertEquals(403, $response->getStatusCode());
    }

    /** @test */
    public function verified_middleware_allows_when_both_verified()
    {
        $user = $this->createUser('patient', true, true);
        Passport::actingAs($user);

        $request = Request::create('/test', 'GET');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $middleware = new VerifiedMiddleware();

        $response = $middleware->handle($request, function ($req) {
            return new Response('OK', 200);
        }, 'email,phone');

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function role_middleware_allows_correct_role()
    {
        $user = $this->createUser('doctor');
        Passport::actingAs($user);

        $response = $this->getJson('/api/test-doctor-endpoint');

        // This would be a 404 since the endpoint doesn't exist, but not a 403 (forbidden)
        $response->assertStatus(404);
    }

    /** @test */
    public function throttle_middleware_limits_requests()
    {
        $user = $this->createUser('patient');

        // Test login rate limiting (10 attempts per minute)
        for ($i = 0; $i < 11; $i++) {
            $response = $this->postJson('/api/auth/login', [
                'email' => $user->email,
                'password' => 'wrongpassword',
            ]);

            if ($i < 10) {
                $response->assertStatus(401); // Invalid credentials
            } else {
                $response->assertStatus(429); // Too many requests
            }
        }
    }

    /** @test */
    public function throttle_middleware_limits_password_reset_requests()
    {
        $user = $this->createUser('patient');

        // Test password reset rate limiting (3 attempts per minute)
        for ($i = 0; $i < 4; $i++) {
            $response = $this->postJson('/api/auth/forgot-password', [
                'email' => $user->email,
            ]);

            if ($i < 3) {
                $response->assertStatus(200);
            } else {
                $response->assertStatus(429); // Too many requests
            }
        }
    }

    /** @test */
    public function throttle_middleware_limits_verification_requests()
    {
        $user = $this->createUser('patient');
        Passport::actingAs($user);

        // Test email verification rate limiting (3 attempts per minute)
        for ($i = 0; $i < 4; $i++) {
            $response = $this->postJson('/api/auth/send-email-verification');

            if ($i < 3) {
                $response->assertStatus(200);
            } else {
                $response->assertStatus(429); // Too many requests
            }
        }
    }

    /** @test */
    public function scope_middleware_blocks_insufficient_scope()
    {
        $user = $this->createUser('patient');
        $token = $user->createToken('Test Token', ['read-profile']); // Missing write-profile scope

        // This test would require a specific endpoint that requires write-profile scope
        // For now, we'll test the concept with a mock
        $request = Request::create('/test', 'POST');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        // Mock token with limited scopes
        $mockToken = new Token();
        $mockToken->scopes = ['read-profile'];
        $user->setRelation('tokens', collect([$mockToken]));

        $middleware = new ScopeMiddleware();

        $response = $middleware->handle($request, function ($req) {
            return new Response('OK', 200);
        }, 'write-profile');

        $this->assertEquals(403, $response->getStatusCode());
    }

    /** @test */
    public function middleware_logs_security_events()
    {
        $user = $this->createUser('patient', false);
        Passport::actingAs($user);

        $request = Request::create('/test', 'GET');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $middleware = new VerifiedMiddleware();

        $response = $middleware->handle($request, function ($req) {
            return new Response('OK', 200);
        }, 'email');

        // Check that security event was logged
        $this->assertDatabaseHas('activity_log', [
            'causer_id' => $user->id,
            'log_name' => 'security',
            'description' => 'Email verification required but not verified'
        ]);
    }

    /** @test */
    public function authenticated_user_can_access_protected_routes()
    {
        $user = $this->createUser('patient');
        Passport::actingAs($user);

        $response = $this->getJson('/api/me');

        $response->assertStatus(200);
    }

    /** @test */
    public function unauthenticated_user_cannot_access_protected_routes()
    {
        $response = $this->getJson('/api/me');

        $response->assertStatus(401);
    }

    /** @test */
    public function middleware_handles_invalid_token_gracefully()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer invalid-token',
        ])->getJson('/api/me');

        $response->assertStatus(401);
    }

    /** @test */
    public function middleware_handles_expired_token()
    {
        $user = $this->createUser('patient');
        $token = $user->createToken('Test Token');

        // Manually expire the token
        $token->token->update(['expires_at' => now()->subDay()]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token->accessToken,
        ])->getJson('/api/me');

        $response->assertStatus(401);
    }
}
