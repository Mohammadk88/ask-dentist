<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Events\TreatmentPlanSubmitted;
use App\Listeners\CancelCompetingPlans;
use App\Infrastructure\Models\User;
use App\Infrastructure\Models\Doctor;
use App\Infrastructure\Models\Patient;
use App\Infrastructure\Models\TreatmentRequest;
use App\Infrastructure\Models\TreatmentPlan;
use App\Infrastructure\Models\Clinic;
use App\Infrastructure\Models\DoctorClinic;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

class TreatmentPlanSubmissionEventTest extends TestCase
{
    use RefreshDatabase;

    protected User $doctorUser1;
    protected User $doctorUser2;
    protected Doctor $doctor1;
    protected Doctor $doctor2;
    protected User $patientUser;
    protected Patient $patient;
    protected TreatmentRequest $treatmentRequest;
    protected Clinic $clinic;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setupTestData();
    }

    private function setupTestData(): void
    {
        // Create first doctor
        $this->doctorUser1 = User::factory()->create([
            'role' => 'Doctor',
            'email_verified_at' => now(),
        ]);

        $this->doctor1 = Doctor::factory()->create([
            'user_id' => $this->doctorUser1->id,
            'verified_at' => now(),
        ]);

        // Create second doctor
        $this->doctorUser2 = User::factory()->create([
            'role' => 'Doctor',
            'email_verified_at' => now(),
        ]);

        $this->doctor2 = Doctor::factory()->create([
            'user_id' => $this->doctorUser2->id,
            'verified_at' => now(),
        ]);

        // Create clinic and associate doctors
        $this->clinic = Clinic::factory()->create();
        
        DoctorClinic::factory()->create([
            'doctor_id' => $this->doctor1->id,
            'clinic_id' => $this->clinic->id,
        ]);

        DoctorClinic::factory()->create([
            'doctor_id' => $this->doctor2->id,
            'clinic_id' => $this->clinic->id,
        ]);

        // Create patient
        $this->patientUser = User::factory()->create([
            'role' => 'Patient',
            'email_verified_at' => now(),
        ]);

        $this->patient = Patient::factory()->create([
            'user_id' => $this->patientUser->id,
        ]);

        // Create treatment request
        $this->treatmentRequest = TreatmentRequest::factory()->create([
            'patient_id' => $this->patient->id,
            'status' => 'dispatched',
        ]);
    }

    /** @test */
    public function it_dispatches_event_when_treatment_plan_is_submitted()
    {
        Event::fake();

        $plan = TreatmentPlan::factory()->create([
            'treatment_request_id' => $this->treatmentRequest->id,
            'doctor_id' => $this->doctor1->id,
            'clinic_id' => $this->clinic->id,
            'status' => 'draft',
        ]);

        // Simulate plan submission
        $plan->update(['status' => 'submitted']);
        TreatmentPlanSubmitted::dispatch($plan);

        Event::assertDispatched(TreatmentPlanSubmitted::class, function ($event) use ($plan) {
            return $event->treatmentPlan->id === $plan->id;
        });
    }

    /** @test */
    public function it_cancels_competing_draft_plans_when_plan_is_submitted()
    {
        // Create multiple draft plans for the same treatment request
        $plan1 = TreatmentPlan::factory()->create([
            'treatment_request_id' => $this->treatmentRequest->id,
            'doctor_id' => $this->doctor1->id,
            'clinic_id' => $this->clinic->id,
            'status' => 'draft',
        ]);

        $plan2 = TreatmentPlan::factory()->create([
            'treatment_request_id' => $this->treatmentRequest->id,
            'doctor_id' => $this->doctor2->id,
            'clinic_id' => $this->clinic->id,
            'status' => 'draft',
        ]);

        // Submit plan1
        $plan1->update(['status' => 'submitted']);
        
        // Create and handle the event
        $event = new TreatmentPlanSubmitted($plan1);
        $listener = new CancelCompetingPlans();
        $listener->handle($event);

        // Check that plan2 was cancelled but plan1 remains submitted
        $plan1->refresh();
        $plan2->refresh();

        $this->assertEquals('submitted', $plan1->status);
        $this->assertEquals('cancelled', $plan2->status);
        $this->assertNotNull($plan2->cancelled_at);
        $this->assertNotNull($plan2->cancellation_reason);
        $this->assertStringContains('competing plan submission', $plan2->cancellation_reason);
    }

    /** @test */
    public function it_does_not_cancel_already_submitted_plans()
    {
        // Create one draft plan and one already submitted plan
        $draftPlan = TreatmentPlan::factory()->create([
            'treatment_request_id' => $this->treatmentRequest->id,
            'doctor_id' => $this->doctor1->id,
            'clinic_id' => $this->clinic->id,
            'status' => 'draft',
        ]);

        $submittedPlan = TreatmentPlan::factory()->create([
            'treatment_request_id' => $this->treatmentRequest->id,
            'doctor_id' => $this->doctor2->id,
            'clinic_id' => $this->clinic->id,
            'status' => 'submitted',
        ]);

        // Submit the draft plan
        $draftPlan->update(['status' => 'submitted']);
        
        // Create and handle the event
        $event = new TreatmentPlanSubmitted($draftPlan);
        $listener = new CancelCompetingPlans();
        $listener->handle($event);

        // Check that the already submitted plan was not affected
        $submittedPlan->refresh();
        $this->assertEquals('submitted', $submittedPlan->status);
        $this->assertNull($submittedPlan->cancelled_at);
    }

    /** @test */
    public function it_does_not_cancel_plans_for_different_treatment_requests()
    {
        // Create another treatment request
        $anotherTreatmentRequest = TreatmentRequest::factory()->create([
            'patient_id' => $this->patient->id,
            'status' => 'dispatched',
        ]);

        // Create plans for different treatment requests
        $plan1 = TreatmentPlan::factory()->create([
            'treatment_request_id' => $this->treatmentRequest->id,
            'doctor_id' => $this->doctor1->id,
            'clinic_id' => $this->clinic->id,
            'status' => 'draft',
        ]);

        $plan2 = TreatmentPlan::factory()->create([
            'treatment_request_id' => $anotherTreatmentRequest->id,
            'doctor_id' => $this->doctor2->id,
            'clinic_id' => $this->clinic->id,
            'status' => 'draft',
        ]);

        // Submit plan1
        $plan1->update(['status' => 'submitted']);
        
        // Create and handle the event
        $event = new TreatmentPlanSubmitted($plan1);
        $listener = new CancelCompetingPlans();
        $listener->handle($event);

        // Check that plan2 (different treatment request) was not affected
        $plan2->refresh();
        $this->assertEquals('draft', $plan2->status);
        $this->assertNull($plan2->cancelled_at);
    }

    /** @test */
    public function it_does_not_cancel_the_submitted_plan_itself()
    {
        // Create a draft plan
        $plan = TreatmentPlan::factory()->create([
            'treatment_request_id' => $this->treatmentRequest->id,
            'doctor_id' => $this->doctor1->id,
            'clinic_id' => $this->clinic->id,
            'status' => 'draft',
        ]);

        // Submit the plan
        $plan->update(['status' => 'submitted']);
        
        // Create and handle the event
        $event = new TreatmentPlanSubmitted($plan);
        $listener = new CancelCompetingPlans();
        $listener->handle($event);

        // Check that the submitted plan itself was not cancelled
        $plan->refresh();
        $this->assertEquals('submitted', $plan->status);
        $this->assertNull($plan->cancelled_at);
    }

    /** @test */
    public function it_cancels_multiple_competing_draft_plans()
    {
        // Create multiple draft plans from different doctors
        $submittedPlan = TreatmentPlan::factory()->create([
            'treatment_request_id' => $this->treatmentRequest->id,
            'doctor_id' => $this->doctor1->id,
            'clinic_id' => $this->clinic->id,
            'status' => 'draft',
        ]);

        $draftPlan1 = TreatmentPlan::factory()->create([
            'treatment_request_id' => $this->treatmentRequest->id,
            'doctor_id' => $this->doctor2->id,
            'clinic_id' => $this->clinic->id,
            'status' => 'draft',
        ]);

        // Create a third doctor and plan
        $doctorUser3 = User::factory()->create(['role' => 'Doctor']);
        $doctor3 = Doctor::factory()->create(['user_id' => $doctorUser3->id]);
        
        $draftPlan2 = TreatmentPlan::factory()->create([
            'treatment_request_id' => $this->treatmentRequest->id,
            'doctor_id' => $doctor3->id,
            'clinic_id' => $this->clinic->id,
            'status' => 'draft',
        ]);

        // Submit one plan
        $submittedPlan->update(['status' => 'submitted']);
        
        // Create and handle the event
        $event = new TreatmentPlanSubmitted($submittedPlan);
        $listener = new CancelCompetingPlans();
        $listener->handle($event);

        // Check that both competing draft plans were cancelled
        $draftPlan1->refresh();
        $draftPlan2->refresh();

        $this->assertEquals('cancelled', $draftPlan1->status);
        $this->assertEquals('cancelled', $draftPlan2->status);
        $this->assertNotNull($draftPlan1->cancelled_at);
        $this->assertNotNull($draftPlan2->cancelled_at);
    }

    /** @test */
    public function it_handles_event_with_no_competing_plans_gracefully()
    {
        // Create a single plan (no competing plans)
        $plan = TreatmentPlan::factory()->create([
            'treatment_request_id' => $this->treatmentRequest->id,
            'doctor_id' => $this->doctor1->id,
            'clinic_id' => $this->clinic->id,
            'status' => 'draft',
        ]);

        // Submit the plan
        $plan->update(['status' => 'submitted']);
        
        // Create and handle the event
        $event = new TreatmentPlanSubmitted($plan);
        $listener = new CancelCompetingPlans();
        
        // Should not throw any exceptions
        $listener->handle($event);

        // Plan should remain submitted
        $plan->refresh();
        $this->assertEquals('submitted', $plan->status);
    }
}