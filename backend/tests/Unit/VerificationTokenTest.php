<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\VerificationToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class VerificationTokenTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function verification_token_belongs_to_user()
    {
        $user = User::factory()->create();
        $token = VerificationToken::create([
            'user_id' => $user->id,
            'type' => 'email',
            'token' => 'test-token',
            'expires_at' => now()->addHours(24),
        ]);

        $this->assertEquals($user->id, $token->user->id);
        $this->assertEquals($user->email, $token->user->email);
    }

    /** @test */
    public function verification_token_can_be_email_type()
    {
        $user = User::factory()->create();
        $token = VerificationToken::create([
            'user_id' => $user->id,
            'type' => 'email',
            'token' => \Str::uuid(),
            'expires_at' => now()->addHours(24),
        ]);

        $this->assertEquals('email', $token->type);
        $this->assertTrue(\Str::isUuid($token->token));
    }

    /** @test */
    public function verification_token_can_be_phone_type()
    {
        $user = User::factory()->create();
        $token = VerificationToken::create([
            'user_id' => $user->id,
            'type' => 'phone',
            'token' => '123456',
            'expires_at' => now()->addMinutes(15),
        ]);

        $this->assertEquals('phone', $token->type);
        $this->assertEquals('123456', $token->token);
        $this->assertEquals(6, strlen($token->token));
    }

    /** @test */
    public function verification_token_can_check_if_expired()
    {
        $user = User::factory()->create();

        $expiredToken = VerificationToken::create([
            'user_id' => $user->id,
            'type' => 'email',
            'token' => 'expired-token',
            'expires_at' => now()->subHour(),
        ]);

        $validToken = VerificationToken::create([
            'user_id' => $user->id,
            'type' => 'email',
            'token' => 'valid-token',
            'expires_at' => now()->addHour(),
        ]);

        $this->assertTrue($expiredToken->isExpired());
        $this->assertFalse($validToken->isExpired());
    }

    /** @test */
    public function verification_token_can_check_if_valid()
    {
        $user = User::factory()->create();

        $expiredToken = VerificationToken::create([
            'user_id' => $user->id,
            'type' => 'email',
            'token' => 'expired-token',
            'expires_at' => now()->subHour(),
            'used_at' => null,
        ]);

        $usedToken = VerificationToken::create([
            'user_id' => $user->id,
            'type' => 'email',
            'token' => 'used-token',
            'expires_at' => now()->addHour(),
            'used_at' => now(),
        ]);

        $validToken = VerificationToken::create([
            'user_id' => $user->id,
            'type' => 'email',
            'token' => 'valid-token',
            'expires_at' => now()->addHour(),
            'used_at' => null,
        ]);

        $this->assertFalse($expiredToken->isValid());
        $this->assertFalse($usedToken->isValid());
        $this->assertTrue($validToken->isValid());
    }

    /** @test */
    public function verification_token_can_be_marked_as_used()
    {
        $user = User::factory()->create();
        $token = VerificationToken::create([
            'user_id' => $user->id,
            'type' => 'email',
            'token' => 'test-token',
            'expires_at' => now()->addHours(24),
        ]);

        $this->assertNull($token->used_at);
        $this->assertTrue($token->isValid());

        $token->markAsUsed();

        $this->assertNotNull($token->fresh()->used_at);
        $this->assertFalse($token->fresh()->isValid());
    }

    /** @test */
    public function verification_token_scope_finds_valid_tokens()
    {
        $user = User::factory()->create();

        // Create valid token
        $validToken = VerificationToken::create([
            'user_id' => $user->id,
            'type' => 'email',
            'token' => 'valid-token',
            'expires_at' => now()->addHours(24),
            'used_at' => null,
        ]);

        // Create expired token
        VerificationToken::create([
            'user_id' => $user->id,
            'type' => 'email',
            'token' => 'expired-token',
            'expires_at' => now()->subHour(),
            'used_at' => null,
        ]);

        // Create used token
        VerificationToken::create([
            'user_id' => $user->id,
            'type' => 'email',
            'token' => 'used-token',
            'expires_at' => now()->addHours(24),
            'used_at' => now(),
        ]);

        $validTokens = VerificationToken::valid()->get();

        $this->assertEquals(1, $validTokens->count());
        $this->assertEquals($validToken->id, $validTokens->first()->id);
    }

    /** @test */
    public function verification_token_scope_finds_by_type()
    {
        $user = User::factory()->create();

        VerificationToken::create([
            'user_id' => $user->id,
            'type' => 'email',
            'token' => 'email-token',
            'expires_at' => now()->addHours(24),
        ]);

        VerificationToken::create([
            'user_id' => $user->id,
            'type' => 'phone',
            'token' => '123456',
            'expires_at' => now()->addMinutes(15),
        ]);

        $emailTokens = VerificationToken::ofType('email')->get();
        $phoneTokens = VerificationToken::ofType('phone')->get();

        $this->assertEquals(1, $emailTokens->count());
        $this->assertEquals(1, $phoneTokens->count());
        $this->assertEquals('email', $emailTokens->first()->type);
        $this->assertEquals('phone', $phoneTokens->first()->type);
    }

    /** @test */
    public function verification_token_scope_finds_for_user()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        VerificationToken::create([
            'user_id' => $user1->id,
            'type' => 'email',
            'token' => 'user1-token',
            'expires_at' => now()->addHours(24),
        ]);

        VerificationToken::create([
            'user_id' => $user2->id,
            'type' => 'email',
            'token' => 'user2-token',
            'expires_at' => now()->addHours(24),
        ]);

        $user1Tokens = VerificationToken::forUser($user1->id)->get();
        $user2Tokens = VerificationToken::forUser($user2->id)->get();

        $this->assertEquals(1, $user1Tokens->count());
        $this->assertEquals(1, $user2Tokens->count());
        $this->assertEquals($user1->id, $user1Tokens->first()->user_id);
        $this->assertEquals($user2->id, $user2Tokens->first()->user_id);
    }

    /** @test */
    public function verification_token_generates_random_phone_code()
    {
        $code1 = VerificationToken::generatePhoneCode();
        $code2 = VerificationToken::generatePhoneCode();

        $this->assertEquals(6, strlen($code1));
        $this->assertEquals(6, strlen($code2));
        $this->assertTrue(is_numeric($code1));
        $this->assertTrue(is_numeric($code2));
        $this->assertNotEquals($code1, $code2); // Should be different (very unlikely to be same)
    }

    /** @test */
    public function verification_token_generates_unique_email_token()
    {
        $token1 = VerificationToken::generateEmailToken();
        $token2 = VerificationToken::generateEmailToken();

        $this->assertTrue(\Str::isUuid($token1));
        $this->assertTrue(\Str::isUuid($token2));
        $this->assertNotEquals($token1, $token2);
    }

    /** @test */
    public function verification_token_has_correct_fillable_attributes()
    {
        $token = new VerificationToken();

        $expectedFillable = [
            'user_id', 'type', 'token', 'expires_at', 'used_at'
        ];

        $this->assertEquals($expectedFillable, $token->getFillable());
    }

    /** @test */
    public function verification_token_has_correct_casts()
    {
        $token = new VerificationToken();

        $expectedCasts = [
            'id' => 'int',
            'expires_at' => 'datetime',
            'used_at' => 'datetime',
        ];

        $this->assertEquals($expectedCasts, $token->getCasts());
    }

    /** @test */
    public function verification_token_can_find_valid_token_for_user()
    {
        $user = User::factory()->create();

        $validToken = VerificationToken::create([
            'user_id' => $user->id,
            'type' => 'email',
            'token' => 'valid-token',
            'expires_at' => now()->addHours(24),
            'used_at' => null,
        ]);

        // Create an invalid token for the same user
        VerificationToken::create([
            'user_id' => $user->id,
            'type' => 'email',
            'token' => 'expired-token',
            'expires_at' => now()->subHour(),
            'used_at' => null,
        ]);

        $foundToken = VerificationToken::findValidToken($user->id, 'email', 'valid-token');

        $this->assertNotNull($foundToken);
        $this->assertEquals($validToken->id, $foundToken->id);
    }

    /** @test */
    public function verification_token_returns_null_for_invalid_token()
    {
        $user = User::factory()->create();

        VerificationToken::create([
            'user_id' => $user->id,
            'type' => 'email',
            'token' => 'expired-token',
            'expires_at' => now()->subHour(),
            'used_at' => null,
        ]);

        $foundToken = VerificationToken::findValidToken($user->id, 'email', 'expired-token');

        $this->assertNull($foundToken);
    }
}
