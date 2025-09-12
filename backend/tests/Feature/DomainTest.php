<?php

describe('Dentist Selection Domain Logic', function () {
    beforeEach(function () {
        // Set up test data
        $this->patient = \App\Models\User::factory()->create(['role' => 'patient']);
        $this->dentist1 = \App\Models\User::factory()->create([
            'role' => 'doctor',
            'name' => 'Dr. Smith',
            'specialization' => 'general_dentistry',
        ]);
        $this->dentist2 = \App\Models\User::factory()->create([
            'role' => 'doctor', 
            'name' => 'Dr. Jones',
            'specialization' => 'orthodontics',
        ]);
        $this->dentist3 = \App\Models\User::factory()->create([
            'role' => 'doctor',
            'name' => 'Dr. Wilson', 
            'specialization' => 'oral_surgery',
        ]);
    });
    
    it('should filter dentists by specialization', function () {
        $response = $this->actingAs($this->patient)->getJson('/api/v1/doctors?specialization=orthodontics');
        
        $response->assertStatus(200);
        $responseData = $response->json();
        
        expect($responseData['data'])->toHaveCount(1);
        expect($responseData['data'][0]['name'])->toBe('Dr. Jones');
        expect($responseData['data'][0]['specialization'])->toBe('orthodontics');
    });
    
    it('should filter dentists by availability', function () {
        // Create availability for dentist1
        \App\Models\DoctorAvailability::create([
            'doctor_id' => $this->dentist1->id,
            'day_of_week' => 1, // Monday
            'start_time' => '09:00',
            'end_time' => '17:00',
            'is_available' => true,
        ]);
        
        $response = $this->actingAs($this->patient)->getJson('/api/v1/doctors?available_on=monday');
        
        $response->assertStatus(200);
        $responseData = $response->json();
        
        expect($responseData['data'])->toHaveCount(1);
        expect($responseData['data'][0]['id'])->toBe($this->dentist1->id);
    });
    
    it('should filter dentists by location proximity', function () {
        // Set up clinic locations
        $clinic1 = \App\Models\Clinic::factory()->create([
            'name' => 'Downtown Clinic',
            'latitude' => 40.7128,
            'longitude' => -74.0060, // New York City
        ]);
        
        $clinic2 = \App\Models\Clinic::factory()->create([
            'name' => 'Suburban Clinic',
            'latitude' => 40.8176,
            'longitude' => -73.9482, // Bronx
        ]);
        
        // Associate dentists with clinics
        $this->dentist1->update(['clinic_id' => $clinic1->id]);
        $this->dentist2->update(['clinic_id' => $clinic2->id]);
        
        $response = $this->actingAs($this->patient)->getJson(
            '/api/v1/doctors?latitude=40.7128&longitude=-74.0060&radius=10'
        );
        
        $response->assertStatus(200);
        $responseData = $response->json();
        
        // Should find dentist1 (Downtown) but not dentist2 (too far)
        expect($responseData['data'])->toHaveCount(1);
        expect($responseData['data'][0]['id'])->toBe($this->dentist1->id);
    });
    
    it('should rank dentists by rating and experience', function () {
        // Set up ratings and experience
        $this->dentist1->update(['rating' => 4.8, 'years_experience' => 15]);
        $this->dentist2->update(['rating' => 4.5, 'years_experience' => 8]);
        $this->dentist3->update(['rating' => 4.9, 'years_experience' => 20]);
        
        $response = $this->actingAs($this->patient)->getJson('/api/v1/doctors?sort=recommended');
        
        $response->assertStatus(200);
        $responseData = $response->json();
        
        // Should be sorted by composite score (rating + experience)
        expect($responseData['data'][0]['id'])->toBe($this->dentist3->id); // Highest rating + most experience
        expect($responseData['data'][1]['id'])->toBe($this->dentist1->id); // Good rating + good experience
        expect($responseData['data'][2]['id'])->toBe($this->dentist2->id); // Lowest composite score
    });
    
    it('should handle emergency consultation requests', function () {
        $requestData = [
            'doctor_id' => $this->dentist1->id,
            'symptoms' => 'Severe tooth pain',
            'urgency' => 'emergency',
            'preferred_date' => now()->addHours(2)->format('Y-m-d'),
            'preferred_time' => now()->addHours(2)->format('H:i'),
        ];
        
        $response = $this->actingAs($this->patient)->postJson('/api/v1/consultations', $requestData);
        
        $response->assertStatus(201);
        $responseData = $response->json();
        
        expect($responseData['data']['urgency'])->toBe('emergency');
        expect($responseData['data']['status'])->toBe('pending');
        
        // Should trigger emergency notification to dentist
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $this->dentist1->id,
            'type' => 'App\Notifications\EmergencyConsultationRequest',
        ]);
    });
});

describe('Plan Acceptance Cascade Workflow', function () {
    beforeEach(function () {
        $this->patient = \App\Models\User::factory()->create(['role' => 'patient']);
        $this->dentist = \App\Models\User::factory()->create(['role' => 'doctor']);
        
        $this->consultation = \App\Models\TreatmentRequest::factory()->create([
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->dentist->id,
            'status' => 'confirmed',
        ]);
        
        $this->treatmentPlan = \App\Models\TreatmentPlan::factory()->create([
            'consultation_id' => $this->consultation->id,
            'status' => 'pending_approval',
            'total_cost' => 500.00,
            'estimated_completion' => now()->addWeeks(2),
        ]);
    });
    
    it('should handle treatment plan acceptance workflow', function () {
        // Patient accepts the treatment plan
        $response = $this->actingAs($this->patient)->putJson(
            "/api/v1/treatment-plans/{$this->treatmentPlan->id}/accept"
        );
        
        $response->assertStatus(200);
        
        // Verify plan status updated
        $this->treatmentPlan->refresh();
        expect($this->treatmentPlan->status)->toBe('approved');
        
        // Verify consultation status updated
        $this->consultation->refresh();
        expect($this->consultation->status)->toBe('treatment_approved');
        
        // Should create payment record
        $this->assertDatabaseHas('payments', [
            'treatment_plan_id' => $this->treatmentPlan->id,
            'amount' => 500.00,
            'status' => 'pending',
        ]);
        
        // Should notify dentist
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $this->dentist->id,
            'type' => 'App\Notifications\TreatmentPlanApproved',
        ]);
        
        // Should schedule appointments
        $this->assertDatabaseHas('appointments', [
            'consultation_id' => $this->consultation->id,
            'status' => 'scheduled',
        ]);
    });
    
    it('should handle treatment plan rejection workflow', function () {
        $response = $this->actingAs($this->patient)->putJson(
            "/api/v1/treatment-plans/{$this->treatmentPlan->id}/reject",
            ['reason' => 'Cost too high']
        );
        
        $response->assertStatus(200);
        
        // Verify plan status updated
        $this->treatmentPlan->refresh();
        expect($this->treatmentPlan->status)->toBe('rejected');
        expect($this->treatmentPlan->rejection_reason)->toBe('Cost too high');
        
        // Verify consultation status updated
        $this->consultation->refresh();
        expect($this->consultation->status)->toBe('plan_rejected');
        
        // Should notify dentist
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $this->dentist->id,
            'type' => 'App\Notifications\TreatmentPlanRejected',
        ]);
        
        // Should not create payment records
        $this->assertDatabaseMissing('payments', [
            'treatment_plan_id' => $this->treatmentPlan->id,
        ]);
    });
    
    it('should handle treatment plan modification requests', function () {
        $response = $this->actingAs($this->patient)->putJson(
            "/api/v1/treatment-plans/{$this->treatmentPlan->id}/request-modification",
            ['modifications_requested' => 'Please reduce the cost']
        );
        
        $response->assertStatus(200);
        
        // Verify plan status updated
        $this->treatmentPlan->refresh();
        expect($this->treatmentPlan->status)->toBe('modification_requested');
        expect($this->treatmentPlan->modifications_requested)->toBe('Please reduce the cost');
        
        // Should notify dentist
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $this->dentist->id,
            'type' => 'App\Notifications\TreatmentPlanModificationRequested',
        ]);
    });
    
    it('should handle cascade when appointment is completed', function () {
        // First accept the plan
        $this->treatmentPlan->update(['status' => 'approved']);
        $this->consultation->update(['status' => 'treatment_approved']);
        
        // Create appointment
        $appointment = \App\Models\Appointment::factory()->create([
            'consultation_id' => $this->consultation->id,
            'status' => 'scheduled',
            'scheduled_at' => now()->subHours(2),
        ]);
        
        // Mark appointment as completed
        $response = $this->actingAs($this->dentist)->putJson(
            "/api/v1/appointments/{$appointment->id}/complete",
            ['notes' => 'Treatment completed successfully']
        );
        
        $response->assertStatus(200);
        
        // Verify appointment status
        $appointment->refresh();
        expect($appointment->status)->toBe('completed');
        expect($appointment->completion_notes)->toBe('Treatment completed successfully');
        
        // Verify consultation status
        $this->consultation->refresh();
        expect($this->consultation->status)->toBe('completed');
        
        // Should notify patient
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $this->patient->id,
            'type' => 'App\Notifications\TreatmentCompleted',
        ]);
        
        // Should trigger review request
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $this->patient->id,
            'type' => 'App\Notifications\ReviewRequest',
        ]);
    });
    
    it('should handle payment failure cascade', function () {
        // Accept plan
        $this->treatmentPlan->update(['status' => 'approved']);
        
        // Create payment
        $payment = \App\Models\Payment::factory()->create([
            'treatment_plan_id' => $this->treatmentPlan->id,
            'amount' => 500.00,
            'status' => 'pending',
        ]);
        
        // Simulate payment failure
        $response = $this->actingAs($this->patient)->putJson(
            "/api/v1/payments/{$payment->id}/update-status",
            ['status' => 'failed', 'failure_reason' => 'Insufficient funds']
        );
        
        $response->assertStatus(200);
        
        // Verify payment status
        $payment->refresh();
        expect($payment->status)->toBe('failed');
        expect($payment->failure_reason)->toBe('Insufficient funds');
        
        // Should update consultation status
        $this->consultation->refresh();
        expect($this->consultation->status)->toBe('payment_failed');
        
        // Should cancel any scheduled appointments
        $this->assertDatabaseHas('appointments', [
            'consultation_id' => $this->consultation->id,
            'status' => 'cancelled',
        ]);
        
        // Should notify both parties
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $this->patient->id,
            'type' => 'App\Notifications\PaymentFailed',
        ]);
        
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $this->dentist->id,
            'type' => 'App\Notifications\PaymentFailed',
        ]);
    });
});