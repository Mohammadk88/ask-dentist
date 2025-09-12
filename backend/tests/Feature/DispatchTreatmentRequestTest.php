<?php

namespace Tests\Feature;

use App\Application\DTOs\DispatchTreatmentRequestDTO;
use App\Application\UseCases\DispatchTreatmentRequestUseCase;
use App\Infrastructure\Models\Doctor;
use App\Infrastructure\Models\Patient;
use App\Infrastructure\Models\TreatmentRequest;
use App\Infrastructure\Models\TreatmentRequestDoctor;
use App\Infrastructure\Models\User;
use App\Jobs\NotifyDoctorOfTreatmentRequestJob;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class DispatchTreatmentRequestTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private DispatchTreatmentRequestUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->useCase = app(DispatchTreatmentRequestUseCase::class);
        Queue::fake();
    }

    /** @test */
    public function it_dispatches_exactly_5_doctors_when_available()
    {
        // Arrange: Create 10 eligible doctors
        $doctors = $this->createEligibleDoctors(10);
        $treatmentRequest = $this->createTreatmentRequest();

        // Act
        $dto = new DispatchTreatmentRequestDTO($treatmentRequest->id, 5);
        $result = $this->useCase->execute($dto);

        // Assert
        $this->assertEquals(5, $result['total_dispatched']);
        $this->assertCount(5, $result['dispatched_doctors']);
        
        // Verify database records
        $this->assertDatabaseCount('treatment_request_doctors', 5);
        
        // Verify dispatch orders are 1-5
        $dispatchOrders = TreatmentRequestDoctor::where('treatment_request_id', $treatmentRequest->id)
            ->orderBy('dispatch_order')
            ->pluck('dispatch_order')
            ->toArray();
        $this->assertEquals([1, 2, 3, 4, 5], $dispatchOrders);
    }

    /** @test */
    public function it_dispatches_fewer_doctors_when_not_enough_available()
    {
        // Arrange: Create only 3 eligible doctors
        $doctors = $this->createEligibleDoctors(3);
        $treatmentRequest = $this->createTreatmentRequest();

        // Act
        $dto = new DispatchTreatmentRequestDTO($treatmentRequest->id, 5);
        $result = $this->useCase->execute($dto);

        // Assert
        $this->assertEquals(3, $result['total_dispatched']);
        $this->assertDatabaseCount('treatment_request_doctors', 3);
    }

    /** @test */
    public function it_scores_doctors_correctly_by_active_patients_count()
    {
        // Arrange: Create doctors with different active patient counts
        $doctor1 = $this->createDoctorWithActivePatientsCount(1); // Best score
        $doctor2 = $this->createDoctorWithActivePatientsCount(5);
        $doctor3 = $this->createDoctorWithActivePatientsCount(3);
        
        $treatmentRequest = $this->createTreatmentRequest();

        // Act
        $dto = new DispatchTreatmentRequestDTO($treatmentRequest->id, 3);
        $result = $this->useCase->execute($dto);

        // Assert: Doctor with fewest active patients should be first
        $dispatches = TreatmentRequestDoctor::where('treatment_request_id', $treatmentRequest->id)
            ->orderBy('dispatch_order')
            ->get();

        $this->assertEquals($doctor1->id, $dispatches->first()->doctor_id);
        $this->assertEquals(1, $dispatches->first()->dispatch_order);
    }

    /** @test */
    public function it_respects_rating_tiebreaker()
    {
        // Arrange: Create doctors with same active patients but different ratings
        $doctor1 = $this->createDoctorWithRating(2, 4.8); // Same active patients, higher rating
        $doctor2 = $this->createDoctorWithRating(2, 4.2); // Same active patients, lower rating
        
        $treatmentRequest = $this->createTreatmentRequest();

        // Act
        $dto = new DispatchTreatmentRequestDTO($treatmentRequest->id, 2);
        $result = $this->useCase->execute($dto);

        // Assert: Doctor with higher rating should come first
        $dispatches = TreatmentRequestDoctor::where('treatment_request_id', $treatmentRequest->id)
            ->orderBy('dispatch_order')
            ->get();

        $this->assertEquals($doctor1->id, $dispatches->first()->doctor_id);
    }

    /** @test */
    public function it_respects_rotation_fairness()
    {
        // Arrange: Create doctors with recent dispatch history
        $doctor1 = $this->createDoctorWithRecentDispatches(0); // No recent dispatches
        $doctor2 = $this->createDoctorWithRecentDispatches(3); // 3 recent dispatches
        
        $treatmentRequest = $this->createTreatmentRequest();

        // Act
        $dto = new DispatchTreatmentRequestDTO($treatmentRequest->id, 2);
        $result = $this->useCase->execute($dto);

        // Assert: Doctor with fewer recent dispatches should come first
        $dispatches = TreatmentRequestDoctor::where('treatment_request_id', $treatmentRequest->id)
            ->orderBy('dispatch_order')
            ->get();

        $this->assertEquals($doctor1->id, $dispatches->first()->doctor_id);
    }

    /** @test */
    public function it_queues_notification_jobs_for_dispatched_doctors()
    {
        // Arrange
        $doctors = $this->createEligibleDoctors(3);
        $treatmentRequest = $this->createTreatmentRequest();

        // Act
        $dto = new DispatchTreatmentRequestDTO($treatmentRequest->id, 3);
        $result = $this->useCase->execute($dto);

        // Assert: Notification jobs should be queued
        Queue::assertPushed(NotifyDoctorOfTreatmentRequestJob::class, 3);
        
        // Verify notifications are staggered with delays
        Queue::assertPushed(NotifyDoctorOfTreatmentRequestJob::class, function ($job) {
            return $job->delay !== null;
        });
    }

    /** @test */
    public function it_marks_notifications_as_sent()
    {
        // Arrange
        $doctors = $this->createEligibleDoctors(2);
        $treatmentRequest = $this->createTreatmentRequest();

        // Act
        $dto = new DispatchTreatmentRequestDTO($treatmentRequest->id, 2);
        $result = $this->useCase->execute($dto);

        // Assert: All dispatches should have notified_at timestamp
        $dispatches = TreatmentRequestDoctor::where('treatment_request_id', $treatmentRequest->id)->get();
        
        foreach ($dispatches as $dispatch) {
            $this->assertNotNull($dispatch->notified_at);
        }
    }

    /** @test */
    public function it_updates_treatment_request_status_to_dispatched()
    {
        // Arrange
        $doctors = $this->createEligibleDoctors(3);
        $treatmentRequest = $this->createTreatmentRequest();
        $this->assertEquals('pending', $treatmentRequest->status);

        // Act
        $dto = new DispatchTreatmentRequestDTO($treatmentRequest->id, 3);
        $result = $this->useCase->execute($dto);

        // Assert
        $treatmentRequest->refresh();
        $this->assertEquals('reviewing', $treatmentRequest->status);
    }

    /** @test */
    public function it_prevents_double_dispatch()
    {
        // Arrange
        $doctors = $this->createEligibleDoctors(5);
        $treatmentRequest = $this->createTreatmentRequest();
        
        // First dispatch
        $dto = new DispatchTreatmentRequestDTO($treatmentRequest->id, 3);
        $this->useCase->execute($dto);

        // Act & Assert: Second dispatch should fail
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Treatment request has already been dispatched');
        
        $this->useCase->execute($dto);
    }

    /** @test */
    public function it_only_dispatches_to_verified_active_doctors()
    {
        // Arrange
        $verifiedActiveDoctor = $this->createEligibleDoctor();
        $unverifiedDoctor = $this->createUnverifiedDoctor();
        $inactiveDoctor = $this->createInactiveDoctor();
        
        $treatmentRequest = $this->createTreatmentRequest();

        // Act
        $dto = new DispatchTreatmentRequestDTO($treatmentRequest->id, 5);
        $result = $this->useCase->execute($dto);

        // Assert: Only verified active doctor should be dispatched
        $this->assertEquals(1, $result['total_dispatched']);
        $this->assertEquals($verifiedActiveDoctor->id, $result['dispatched_doctors'][0]['doctor_id']);
    }

    /** @test */
    public function it_calculates_dispatch_scores_correctly()
    {
        // Arrange: Create doctor with known metrics
        $doctor = $this->createDoctorWithSpecificMetrics(
            activePatientsCount: 2,
            rating: 4.5,
            yearsExperience: 10
        );
        
        $treatmentRequest = $this->createTreatmentRequest();

        // Act
        $dto = new DispatchTreatmentRequestDTO($treatmentRequest->id, 1);
        $result = $this->useCase->execute($dto);

        // Assert: Score should be calculated and stored
        $dispatch = TreatmentRequestDoctor::where('treatment_request_id', $treatmentRequest->id)->first();
        $this->assertNotNull($dispatch->dispatch_score);
        $this->assertIsFloat($dispatch->dispatch_score);
        $this->assertGreaterThan(0, $dispatch->dispatch_score);
    }

    // Helper methods for creating test data

    private function createEligibleDoctors(int $count): \Illuminate\Database\Eloquent\Collection
    {
        $doctors = new \Illuminate\Database\Eloquent\Collection();
        
        for ($i = 0; $i < $count; $i++) {
            $doctors->push($this->createEligibleDoctor());
        }
        
        return $doctors;
    }

    private function createEligibleDoctor(): Doctor
    {
        $user = User::factory()->create(['status' => 'active']);
        
        return Doctor::factory()->create([
            'user_id' => $user->id,
            'verified_at' => now(),
            'rating' => $this->faker->randomFloat(2, 3.0, 5.0),
            'years_experience' => $this->faker->numberBetween(1, 20),
        ]);
    }

    private function createDoctorWithActivePatientsCount(int $count): Doctor
    {
        $user = User::factory()->create(['status' => 'active']);
        
        $doctor = Doctor::factory()->create([
            'user_id' => $user->id,
            'verified_at' => now(),
            'rating' => 4.0, // Fixed rating to ensure active patients count dominates
            'years_experience' => 10, // Fixed experience to ensure active patients count dominates
        ]);
        
        // Create accepted treatment plans for this doctor (simulating active patients)
        for ($i = 0; $i < $count; $i++) {
            \App\Infrastructure\Models\TreatmentPlan::factory()->create([
                'doctor_id' => $doctor->id,
                'status' => 'accepted'
            ]);
        }
        
        return $doctor;
    }

    private function createDoctorWithRating(int $activePatientsCount, float $rating): Doctor
    {
        $doctor = $this->createDoctorWithActivePatientsCount($activePatientsCount);
        $doctor->update(['rating' => $rating]);
        
        return $doctor;
    }

    private function createDoctorWithRecentDispatches(int $dispatchCount): Doctor
    {
        $user = User::factory()->create(['status' => 'active']);
        
        $doctor = Doctor::factory()->create([
            'user_id' => $user->id,
            'verified_at' => now(),
            'rating' => 4.0, // Fixed rating to ensure rotation dominates
            'years_experience' => 10, // Fixed experience to ensure rotation dominates
        ]);
        
        // Create recent dispatches for this doctor
        for ($i = 0; $i < $dispatchCount; $i++) {
            $otherTreatmentRequest = $this->createTreatmentRequest();
            TreatmentRequestDoctor::create([
                'treatment_request_id' => $otherTreatmentRequest->id,
                'doctor_id' => $doctor->id,
                'dispatch_order' => 1,
                'dispatch_score' => 100.0,
                'status' => 'pending',
                'created_at' => now()->subDays(rand(1, 29)), // Within last 30 days
            ]);
        }
        
        return $doctor;
    }

    private function createDoctorWithSpecificMetrics(int $activePatientsCount, float $rating, int $yearsExperience): Doctor
    {
        $doctor = $this->createDoctorWithActivePatientsCount($activePatientsCount);
        $doctor->update([
            'rating' => $rating,
            'years_experience' => $yearsExperience,
        ]);
        
        return $doctor;
    }

    private function createUnverifiedDoctor(): Doctor
    {
        $user = User::factory()->create(['status' => 'active']);
        
        return Doctor::factory()->create([
            'user_id' => $user->id,
            'verified_at' => null, // Not verified
        ]);
    }

    private function createInactiveDoctor(): Doctor
    {
        $user = User::factory()->create(['status' => 'inactive']); // Inactive user
        
        return Doctor::factory()->create([
            'user_id' => $user->id,
            'verified_at' => now(),
        ]);
    }

    private function createTreatmentRequest(): TreatmentRequest
    {
        $patient = Patient::factory()->create();
        
        return TreatmentRequest::factory()->create([
            'patient_id' => $patient->id,
            'status' => 'pending',
        ]);
    }
}
