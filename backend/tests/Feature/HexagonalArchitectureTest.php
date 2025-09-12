<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Domain\ValueObjects\Email;
use App\Domain\ValueObjects\Phone;
use App\Domain\ValueObjects\Money;
use App\Models\Specialization;

class HexagonalArchitectureTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // Create a specialization for doctor tests
        Specialization::create([
            'name' => 'General Dentistry',
            'description' => 'General dental care and procedures'
        ]);
    }

    /** @test */
    public function it_can_create_value_objects()
    {
        $email = new Email('test@example.com');
        $this->assertEquals('test@example.com', $email->getValue());
        $this->assertEquals('example.com', $email->getDomain());

        $phone = new Phone('+1234567890');
        $this->assertEquals('+1234567890', $phone->getValue());

        $money = new Money(150.00, 'USD');
        $this->assertEquals(150.00, $money->getAmount());
        $this->assertEquals('USD', $money->getCurrency());
        $this->assertEquals('150.00 USD', $money->format());
    }

    /** @test */
    public function it_validates_email_value_object()
    {
        $this->expectException(\InvalidArgumentException::class);
        new Email('invalid-email');
    }

    /** @test */
    public function it_validates_money_value_object()
    {
        $this->expectException(\InvalidArgumentException::class);
        new Money(-100, 'USD'); // Negative amount should fail
    }

    /** @test */
    public function it_can_register_patient_via_hexagonal_endpoint()
    {
        $response = $this->postJson('/api/v2/auth/register/patient', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
            'phone' => '+1234567890',
            'date_of_birth' => '1990-01-15',
            'gender' => 'male',
            'emergency_contact_name' => 'Jane Doe',
            'emergency_contact_phone' => '+1234567891',
            'consent_treatment' => true,
            'consent_data_sharing' => true,
            'medical_history' => 'No known conditions',
            'allergies' => 'None',
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'message',
            'user' => [
                'id',
                'first_name',
                'last_name',
                'email',
                'role'
            ],
            'patient' => [
                'id',
                'user_id',
                'date_of_birth',
                'gender',
                'age'
            ]
        ]);

        $responseData = $response->json();
        $this->assertEquals('Patient registered successfully', $responseData['message']);
        $this->assertEquals('john.doe@example.com', $responseData['user']['email']);
        $this->assertEquals('patient', $responseData['user']['role']);
        $this->assertEquals('male', $responseData['patient']['gender']);
    }

    /** @test */
    public function it_can_register_doctor_via_hexagonal_endpoint()
    {
        $response = $this->postJson('/api/v2/auth/register/doctor', [
            'first_name' => 'Dr. Sarah',
            'last_name' => 'Johnson',
            'email' => 'dr.sarah@example.com',
            'password' => 'password123',
            'phone' => '+1234567890',
            'specialization_id' => 1,
            'license_number' => 'DL123456',
            'years_of_experience' => '10',
            'consultation_fee' => 150.00,
            'currency' => 'USD',
            'bio' => 'Experienced general dentist',
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'message',
            'user' => [
                'id',
                'first_name',
                'last_name',
                'email',
                'role'
            ],
            'doctor' => [
                'id',
                'user_id',
                'specialization_id',
                'license_number',
                'consultation_fee',
                'is_verified'
            ]
        ]);

        $responseData = $response->json();
        $this->assertEquals('Doctor registered successfully', $responseData['message']);
        $this->assertEquals('dr.sarah@example.com', $responseData['user']['email']);
        $this->assertEquals('doctor', $responseData['user']['role']);
        $this->assertEquals('150.00 USD', $responseData['doctor']['consultation_fee']);
        $this->assertFalse($responseData['doctor']['is_verified']); // Should not be verified initially
    }

    /** @test */
    public function it_prevents_duplicate_email_registration()
    {
        // Register first patient
        $this->postJson('/api/v2/auth/register/patient', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
            'phone' => '+1234567890',
            'date_of_birth' => '1990-01-15',
            'gender' => 'male',
            'emergency_contact_name' => 'Jane Doe',
            'emergency_contact_phone' => '+1234567891',
            'consent_treatment' => true,
            'consent_data_sharing' => true,
        ]);

        // Try to register second patient with same email
        $response = $this->postJson('/api/v2/auth/register/patient', [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'john.doe@example.com', // Same email
            'password' => 'password123',
            'phone' => '+1234567892',
            'date_of_birth' => '1992-05-20',
            'gender' => 'female',
            'emergency_contact_name' => 'John Smith',
            'emergency_contact_phone' => '+1234567893',
            'consent_treatment' => true,
            'consent_data_sharing' => true,
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'Email already exists'
        ]);
    }

    /** @test */
    public function it_can_login_via_hexagonal_endpoint()
    {
        // First register a patient
        $this->postJson('/api/v2/auth/register/patient', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
            'phone' => '+1234567890',
            'date_of_birth' => '1990-01-15',
            'gender' => 'male',
            'emergency_contact_name' => 'Jane Doe',
            'emergency_contact_phone' => '+1234567891',
            'consent_treatment' => true,
            'consent_data_sharing' => true,
        ]);

        // Now try to login
        $response = $this->postJson('/api/v2/auth/login', [
            'email' => 'john.doe@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'user' => [
                'id',
                'first_name',
                'last_name',
                'email',
                'role'
            ],
            'access_token',
            'token_type',
            'expires_in'
        ]);

        $responseData = $response->json();
        $this->assertEquals('Login successful', $responseData['message']);
        $this->assertEquals('john.doe@example.com', $responseData['user']['email']);
        $this->assertEquals('Bearer', $responseData['token_type']);
        $this->assertNotEmpty($responseData['access_token']);
    }

    /** @test */
    public function it_rejects_invalid_login_credentials()
    {
        $response = $this->postJson('/api/v2/auth/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'Invalid credentials'
        ]);
    }
}
