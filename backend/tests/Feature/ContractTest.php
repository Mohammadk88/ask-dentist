<?php

use Tests\Concerns\ContractTestingHelpers;

uses(ContractTestingHelpers::class);

describe('Authentication Contract Tests', function () {
    beforeEach(function () {
        $this->setUpPassportClient();
    });
    
    it('POST /auth/register matches OpenAPI specification', function () {
        $requestData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'patient',
            'phone' => '+1234567890',
        ];
        
        $response = $this->makeApiRequest('POST', '/api/v1/auth/register', $requestData);
        
        $this->assertResponseMatchesOpenApiSpec(
            $response->baseResponse,
            'POST',
            '/auth/register',
            $response->getStatusCode()
        );
    });
    
    it('POST /auth/login matches OpenAPI specification', function () {
        $user = \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);
        
        $requestData = [
            'email' => 'test@example.com',
            'password' => 'password123',
        ];
        
        $response = $this->makeApiRequest('POST', '/api/v1/auth/login', $requestData);
        
        $this->assertResponseMatchesOpenApiSpec(
            $response->baseResponse,
            'POST',
            '/auth/login',
            $response->getStatusCode()
        );
    });
    
    it('POST /auth/logout matches OpenAPI specification', function () {
        $response = $this->makeAuthenticatedApiRequest('POST', '/api/v1/auth/logout');
        
        $this->assertResponseMatchesOpenApiSpec(
            $response->baseResponse,
            'POST',
            '/auth/logout',
            $response->getStatusCode()
        );
    });
    
    it('GET /auth/me matches OpenAPI specification', function () {
        $response = $this->makeAuthenticatedApiRequest('GET', '/api/v1/auth/me');
        
        $this->assertResponseMatchesOpenApiSpec(
            $response->baseResponse,
            'GET',
            '/auth/me',
            $response->getStatusCode()
        );
    });
});

describe('Doctor Contract Tests', function () {
    it('GET /doctors matches OpenAPI specification', function () {
        // Create test doctors
        \App\Models\User::factory()->count(3)->create(['role' => 'doctor']);
        
        $response = $this->makeApiRequest('GET', '/api/v1/doctors');
        
        $this->assertResponseMatchesOpenApiSpec(
            $response->baseResponse,
            'GET',
            '/doctors',
            $response->getStatusCode()
        );
    });
    
    it('GET /doctors/{id} matches OpenAPI specification', function () {
        $doctor = \App\Models\User::factory()->create(['role' => 'doctor']);
        
        $response = $this->makeApiRequest('GET', "/api/v1/doctors/{$doctor->id}");
        
        $this->assertResponseMatchesOpenApiSpec(
            $response->baseResponse,
            'GET',
            '/doctors/{id}',
            $response->getStatusCode()
        );
    });
    
    it('GET /doctors/{id}/availability matches OpenAPI specification', function () {
        $doctor = \App\Models\User::factory()->create(['role' => 'doctor']);
        
        $response = $this->makeApiRequest('GET', "/api/v1/doctors/{$doctor->id}/availability");
        
        $this->assertResponseMatchesOpenApiSpec(
            $response->baseResponse,
            'GET',
            '/doctors/{id}/availability',
            $response->getStatusCode()
        );
    });
});

describe('Consultation Contract Tests', function () {
    it('POST /consultations matches OpenAPI specification', function () {
        $doctor = \App\Models\User::factory()->create(['role' => 'doctor']);
        
        $requestData = [
            'doctor_id' => $doctor->id,
            'symptoms' => 'Tooth pain',
            'preferred_date' => now()->addDays(1)->format('Y-m-d'),
            'preferred_time' => '10:00',
            'urgency' => 'medium',
        ];
        
        $response = $this->makeAuthenticatedApiRequest('POST', '/api/v1/consultations', $requestData);
        
        $this->assertResponseMatchesOpenApiSpec(
            $response->baseResponse,
            'POST',
            '/consultations',
            $response->getStatusCode()
        );
    });
    
    it('GET /consultations matches OpenAPI specification', function () {
        $response = $this->makeAuthenticatedApiRequest('GET', '/api/v1/consultations');
        
        $this->assertResponseMatchesOpenApiSpec(
            $response->baseResponse,
            'GET',
            '/consultations',
            $response->getStatusCode()
        );
    });
    
    it('GET /consultations/{id} matches OpenAPI specification', function () {
        $consultation = \App\Models\TreatmentRequest::factory()->create();
        
        $response = $this->makeAuthenticatedApiRequest('GET', "/api/v1/consultations/{$consultation->id}");
        
        $this->assertResponseMatchesOpenApiSpec(
            $response->baseResponse,
            'GET',
            '/consultations/{id}',
            $response->getStatusCode()
        );
    });
});

describe('Treatment Plan Contract Tests', function () {
    it('POST /consultations/{id}/treatment-plan matches OpenAPI specification', function () {
        $consultation = \App\Models\TreatmentRequest::factory()->create();
        
        $requestData = [
            'diagnosis' => 'Dental caries',
            'treatment_steps' => [
                [
                    'procedure' => 'Dental filling',
                    'estimated_duration' => 60,
                    'cost' => 150.00
                ]
            ],
            'total_cost' => 150.00,
            'estimated_completion' => now()->addWeeks(2)->format('Y-m-d'),
            'notes' => 'Simple restoration required',
        ];
        
        $response = $this->makeAuthenticatedApiRequest(
            'POST', 
            "/api/v1/consultations/{$consultation->id}/treatment-plan", 
            $requestData
        );
        
        $this->assertResponseMatchesOpenApiSpec(
            $response->baseResponse,
            'POST',
            '/consultations/{id}/treatment-plan',
            $response->getStatusCode()
        );
    });
});

describe('Error Response Contract Tests', function () {
    it('404 errors match OpenAPI specification', function () {
        $response = $this->makeApiRequest('GET', '/api/v1/doctors/99999');
        
        $this->assertResponseMatchesOpenApiSpec(
            $response->baseResponse,
            'GET',
            '/doctors/{id}',
            404
        );
    });
    
    it('401 errors match OpenAPI specification', function () {
        $response = $this->makeApiRequest('GET', '/api/v1/auth/me');
        
        $this->assertResponseMatchesOpenApiSpec(
            $response->baseResponse,
            'GET',
            '/auth/me',
            401
        );
    });
    
    it('422 validation errors match OpenAPI specification', function () {
        $response = $this->makeApiRequest('POST', '/api/v1/auth/register', [
            'email' => 'invalid-email'
        ]);
        
        $this->assertResponseMatchesOpenApiSpec(
            $response->baseResponse,
            'POST',
            '/auth/register',
            422
        );
    });
});