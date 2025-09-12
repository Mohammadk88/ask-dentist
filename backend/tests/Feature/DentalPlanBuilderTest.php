<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Livewire\DentalPlanBuilder;
use App\Infrastructure\Models\User;
use App\Infrastructure\Models\Doctor;
use App\Infrastructure\Models\Patient;
use App\Infrastructure\Models\TreatmentRequest;
use App\Infrastructure\Models\TreatmentPlan;
use App\Infrastructure\Models\Service;
use App\Infrastructure\Models\Pricing;
use App\Infrastructure\Models\Clinic;
use App\Infrastructure\Models\DoctorClinic;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class DentalPlanBuilderTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $doctorUser;
    protected Doctor $doctor;
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
        // Create doctor user and doctor
        $this->doctorUser = User::factory()->create([
            'role' => 'Doctor',
            'email_verified_at' => now(),
        ]);

        $this->doctor = Doctor::factory()->create([
            'user_id' => $this->doctorUser->id,
            'verified_at' => now(),
        ]);

        // Create clinic and associate doctor
        $this->clinic = Clinic::factory()->create();
        DoctorClinic::factory()->create([
            'doctor_id' => $this->doctor->id,
            'clinic_id' => $this->clinic->id,
        ]);

        // Create patient user and patient
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

        // Create some test services with tooth-specific options
        Service::factory()->create([
            'name' => 'Tooth Extraction',
            'is_tooth_specific' => true,
        ]);

        Service::factory()->create([
            'name' => 'General Consultation',
            'is_tooth_specific' => false,
        ]);

        Service::factory()->create([
            'name' => 'Root Canal Treatment',
            'is_tooth_specific' => true,
        ]);

        // Create pricing for services with tooth modifiers
        $service = Service::where('is_tooth_specific', true)->first();
        Pricing::factory()->create([
            'service_id' => $service->id,
            'clinic_id' => $this->clinic->id,
            'base_price' => 100.00,
            'tooth_modifier' => [
                'molar' => 1.5,      // 50% increase for molars
                'premolar' => 1.2,   // 20% increase for premolars
                'canine' => 1.1,     // 10% increase for canines
                'incisor' => 1.0,    // Base price for incisors
            ],
        ]);
    }

    /** @test */
    public function it_can_render_component()
    {
        $this->actingAs($this->doctorUser);

        Livewire::test(DentalPlanBuilder::class, [
            'treatmentRequest' => $this->treatmentRequest,
            'existingPlan' => null,
        ])
        ->assertOk()
        ->assertViewIs('livewire.dental-plan-builder');
    }

    /** @test */
    public function it_initializes_with_empty_state()
    {
        $this->actingAs($this->doctorUser);

        Livewire::test(DentalPlanBuilder::class, [
            'treatmentRequest' => $this->treatmentRequest,
            'existingPlan' => null,
        ])
        ->assertSet('selectedTeeth', [])
        ->assertSet('selectedServices', [])
        ->assertSet('stages', [])
        ->assertSet('currentTab', 'teeth')
        ->assertSet('totalCost', 0);
    }

    /** @test */
    public function it_can_select_and_deselect_teeth()
    {
        $this->actingAs($this->doctorUser);

        Livewire::test(DentalPlanBuilder::class, [
            'treatmentRequest' => $this->treatmentRequest,
            'existingPlan' => null,
        ])
        ->call('toggleTooth', 11) // Select upper right central incisor
        ->assertSet('selectedTeeth', [11])
        ->call('toggleTooth', 21) // Select upper left central incisor
        ->assertSet('selectedTeeth', [11, 21])
        ->call('toggleTooth', 11) // Deselect first tooth
        ->assertSet('selectedTeeth', [21]);
    }

    /** @test */
    public function it_can_select_tooth_range()
    {
        $this->actingAs($this->doctorUser);

        Livewire::test(DentalPlanBuilder::class, [
            'treatmentRequest' => $this->treatmentRequest,
            'existingPlan' => null,
        ])
        ->call('selectToothRange', 11, 13) // Select teeth 11, 12, 13
        ->assertSet('selectedTeeth', [11, 12, 13]);
    }

    /** @test */
    public function it_can_clear_all_teeth_selection()
    {
        $this->actingAs($this->doctorUser);

        Livewire::test(DentalPlanBuilder::class, [
            'treatmentRequest' => $this->treatmentRequest,
            'existingPlan' => null,
        ])
        ->call('toggleTooth', 11)
        ->call('toggleTooth', 21)
        ->assertSet('selectedTeeth', [11, 21])
        ->call('clearAllTeeth')
        ->assertSet('selectedTeeth', []);
    }

    /** @test */
    public function it_loads_tooth_specific_services_when_teeth_selected()
    {
        $this->actingAs($this->doctorUser);

        $component = Livewire::test(DentalPlanBuilder::class, [
            'treatmentRequest' => $this->treatmentRequest,
            'existingPlan' => null,
        ])
        ->call('toggleTooth', 11);

        // Check that tooth-specific services are loaded
        $availableServices = $component->get('availableServices');
        $this->assertNotEmpty($availableServices);
        
        // Should include tooth-specific services
        $toothSpecificServices = $availableServices->where('is_tooth_specific', true);
        $this->assertNotEmpty($toothSpecificServices);
    }

    /** @test */
    public function it_can_add_service_to_selected_teeth()
    {
        $this->actingAs($this->doctorUser);

        $service = Service::where('is_tooth_specific', true)->first();

        Livewire::test(DentalPlanBuilder::class, [
            'treatmentRequest' => $this->treatmentRequest,
            'existingPlan' => null,
        ])
        ->call('toggleTooth', 11)
        ->call('toggleTooth', 21)
        ->call('addServiceToSelectedTeeth', $service->id)
        ->assertSet('selectedServices', [
            [
                'service_id' => $service->id,
                'service_name' => $service->name,
                'teeth' => [11, 21],
                'cost_per_tooth' => 100.00, // Base price from pricing
                'total_cost' => 200.00,     // 2 teeth × $100
                'notes' => '',
            ]
        ]);
    }

    /** @test */
    public function it_calculates_correct_tooth_modifier_pricing()
    {
        $this->actingAs($this->doctorUser);

        $service = Service::where('is_tooth_specific', true)->first();

        Livewire::test(DentalPlanBuilder::class, [
            'treatmentRequest' => $this->treatmentRequest,
            'existingPlan' => null,
        ])
        ->call('toggleTooth', 16) // Upper right first molar (should have 1.5x modifier)
        ->call('addServiceToSelectedTeeth', $service->id)
        ->assertSet('selectedServices', [
            [
                'service_id' => $service->id,
                'service_name' => $service->name,
                'teeth' => [16],
                'cost_per_tooth' => 150.00, // $100 × 1.5 molar modifier
                'total_cost' => 150.00,
                'notes' => '',
            ]
        ]);
    }

    /** @test */
    public function it_can_remove_service_configuration()
    {
        $this->actingAs($this->doctorUser);

        $service = Service::where('is_tooth_specific', true)->first();

        Livewire::test(DentalPlanBuilder::class, [
            'treatmentRequest' => $this->treatmentRequest,
            'existingPlan' => null,
        ])
        ->call('toggleTooth', 11)
        ->call('addServiceToSelectedTeeth', $service->id)
        ->assertCount('selectedServices', 1)
        ->call('removeServiceConfiguration', 0)
        ->assertSet('selectedServices', []);
    }

    /** @test */
    public function it_can_create_treatment_stage()
    {
        $this->actingAs($this->doctorUser);

        $service = Service::where('is_tooth_specific', true)->first();

        Livewire::test(DentalPlanBuilder::class, [
            'treatmentRequest' => $this->treatmentRequest,
            'existingPlan' => null,
        ])
        ->call('toggleTooth', 11)
        ->call('addServiceToSelectedTeeth', $service->id)
        ->call('createStage', 'Initial Treatment', 'First stage of treatment')
        ->assertCount('stages', 1)
        ->assertSet('stages.0.title', 'Initial Treatment')
        ->assertSet('stages.0.description', 'First stage of treatment')
        ->assertCount('stages.0.services', 1)
        ->assertSet('selectedServices', []); // Services should be moved to stage
    }

    /** @test */
    public function it_calculates_correct_total_cost()
    {
        $this->actingAs($this->doctorUser);

        $service = Service::where('is_tooth_specific', true)->first();

        $component = Livewire::test(DentalPlanBuilder::class, [
            'treatmentRequest' => $this->treatmentRequest,
            'existingPlan' => null,
        ])
        ->call('toggleTooth', 11) // Incisor - 1.0x modifier = $100
        ->call('toggleTooth', 16) // Molar - 1.5x modifier = $150
        ->call('addServiceToSelectedTeeth', $service->id)
        ->call('createStage', 'Stage 1', 'First stage');

        // Total should be $250 ($100 + $150)
        $this->assertEquals(250.00, $component->get('totalCost'));
    }

    /** @test */
    public function it_can_save_treatment_plan()
    {
        $this->actingAs($this->doctorUser);

        $service = Service::where('is_tooth_specific', true)->first();

        Livewire::test(DentalPlanBuilder::class, [
            'treatmentRequest' => $this->treatmentRequest,
            'existingPlan' => null,
        ])
        ->set('planTitle', 'Comprehensive Treatment Plan')
        ->set('planDescription', 'Detailed treatment plan for patient')
        ->set('diagnosis', 'Multiple dental issues requiring staged treatment')
        ->call('toggleTooth', 11)
        ->call('addServiceToSelectedTeeth', $service->id)
        ->call('createStage', 'Stage 1', 'Initial treatment')
        ->call('savePlan')
        ->assertEmitted('plan-saved');

        // Verify plan was saved to database
        $this->assertDatabaseHas('treatment_plans', [
            'treatment_request_id' => $this->treatmentRequest->id,
            'doctor_id' => $this->doctor->id,
            'title' => 'Comprehensive Treatment Plan',
            'status' => 'draft',
        ]);
    }

    /** @test */
    public function it_can_submit_treatment_plan()
    {
        $this->actingAs($this->doctorUser);

        $service = Service::where('is_tooth_specific', true)->first();

        Livewire::test(DentalPlanBuilder::class, [
            'treatmentRequest' => $this->treatmentRequest,
            'existingPlan' => null,
        ])
        ->set('planTitle', 'Treatment Plan for Submission')
        ->set('planDescription', 'Ready for patient review')
        ->set('diagnosis', 'Treatment diagnosis')
        ->call('toggleTooth', 11)
        ->call('addServiceToSelectedTeeth', $service->id)
        ->call('createStage', 'Stage 1', 'Treatment stage')
        ->call('submitPlan')
        ->assertEmitted('plan-submitted');

        // Verify plan was submitted
        $this->assertDatabaseHas('treatment_plans', [
            'treatment_request_id' => $this->treatmentRequest->id,
            'doctor_id' => $this->doctor->id,
            'status' => 'submitted',
        ]);
    }

    /** @test */
    public function it_loads_existing_plan_data()
    {
        $this->actingAs($this->doctorUser);

        // Create an existing plan
        $existingPlan = TreatmentPlan::factory()->create([
            'treatment_request_id' => $this->treatmentRequest->id,
            'doctor_id' => $this->doctor->id,
            'clinic_id' => $this->clinic->id,
            'title' => 'Existing Plan',
            'description' => 'Previously saved plan',
            'diagnosis' => 'Previous diagnosis',
            'status' => 'draft',
            'services' => [
                [
                    'service_id' => 1,
                    'service_name' => 'Test Service',
                    'teeth' => [11, 21],
                    'cost_per_tooth' => 100.00,
                    'total_cost' => 200.00,
                    'notes' => '',
                ]
            ],
            'total_cost' => 200.00,
        ]);

        Livewire::test(DentalPlanBuilder::class, [
            'treatmentRequest' => $this->treatmentRequest,
            'existingPlan' => $existingPlan,
        ])
        ->assertSet('planTitle', 'Existing Plan')
        ->assertSet('planDescription', 'Previously saved plan')
        ->assertSet('diagnosis', 'Previous diagnosis')
        ->assertSet('totalCost', 200.00);
    }

    /** @test */
    public function it_validates_required_fields_before_saving()
    {
        $this->actingAs($this->doctorUser);

        Livewire::test(DentalPlanBuilder::class, [
            'treatmentRequest' => $this->treatmentRequest,
            'existingPlan' => null,
        ])
        ->call('savePlan')
        ->assertHasErrors(['planTitle', 'planDescription', 'diagnosis']);
    }

    /** @test */
    public function it_requires_services_before_submission()
    {
        $this->actingAs($this->doctorUser);

        Livewire::test(DentalPlanBuilder::class, [
            'treatmentRequest' => $this->treatmentRequest,
            'existingPlan' => null,
        ])
        ->set('planTitle', 'Plan Title')
        ->set('planDescription', 'Plan Description')
        ->set('diagnosis', 'Diagnosis')
        ->call('submitPlan')
        ->assertHasErrors(['stages']);
    }

    /** @test */
    public function it_autosaves_to_local_storage()
    {
        $this->actingAs($this->doctorUser);

        $service = Service::where('is_tooth_specific', true)->first();

        Livewire::test(DentalPlanBuilder::class, [
            'treatmentRequest' => $this->treatmentRequest,
            'existingPlan' => null,
        ])
        ->call('toggleTooth', 11)
        ->call('addServiceToSelectedTeeth', $service->id)
        ->assertDispatched('save-to-localStorage', [
            'key' => 'dentalPlan_' . $this->treatmentRequest->id,
            'data' => [
                'selectedTeeth' => [11],
                'selectedServices' => [
                    [
                        'service_id' => $service->id,
                        'service_name' => $service->name,
                        'teeth' => [11],
                        'cost_per_tooth' => 100.00,
                        'total_cost' => 100.00,
                        'notes' => '',
                    ]
                ]
            ]
        ]);
    }
}