<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Infrastructure\Models\Service;
use App\Infrastructure\Models\Pricing;
use App\Infrastructure\Models\Clinic;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DentalPlanBuilderIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_services_with_tooth_specific_flag()
    {
        $service = Service::create([
            'name' => 'Tooth Extraction',
            'slug' => 'tooth-extraction',
            'description' => 'Simple tooth extraction',
            'category' => 'surgical',
            'duration_minutes' => 30,
            'is_tooth_specific' => true,
        ]);

        $this->assertDatabaseHas('services', [
            'name' => 'Tooth Extraction',
            'is_tooth_specific' => true,
        ]);

        $this->assertTrue($service->is_tooth_specific);
    }

    /** @test */
    public function it_can_create_pricing_with_tooth_modifiers()
    {
        $service = Service::create([
            'name' => 'Tooth Extraction',
            'slug' => 'tooth-extraction',
            'description' => 'Simple tooth extraction',
            'category' => 'surgical',
            'duration_minutes' => 30,
            'is_tooth_specific' => true,
        ]);

        $clinic = Clinic::factory()->create();

        $pricing = Pricing::create([
            'service_id' => $service->id,
            'clinic_id' => $clinic->id,
            'base_price' => 100.00,
            'currency' => 'USD',
            'valid_from' => now(),
            'valid_until' => now()->addYear(),
            'tooth_modifier' => [
                'molar' => 1.5,
                'premolar' => 1.2,
                'canine' => 1.1,
                'incisor' => 1.0,
            ],
        ]);

        $this->assertDatabaseHas('pricing', [
            'service_id' => $service->id,
            'base_price' => 100.00,
        ]);

        // Test tooth modifier calculations
        $this->assertEqualsWithDelta(100.00, $pricing->calculateSingleToothPrice(11), 0.01); // Incisor
        $this->assertEqualsWithDelta(110.00, $pricing->calculateSingleToothPrice(13), 0.01); // Canine
        $this->assertEqualsWithDelta(120.00, $pricing->calculateSingleToothPrice(14), 0.01); // Premolar
        $this->assertEqualsWithDelta(150.00, $pricing->calculateSingleToothPrice(16), 0.01); // Molar
    }

    /** @test */
    public function it_correctly_identifies_tooth_types_by_fdi_codes()
    {
        $service = Service::create([
            'name' => 'Test Service',
            'slug' => 'test-service', 
            'description' => 'Test service for tooth type identification',
            'category' => 'general',
            'duration_minutes' => 30,
            'is_tooth_specific' => true,
        ]);
        
        $clinic = Clinic::factory()->create();
        
        $pricing = Pricing::factory()->create([
            'service_id' => $service->id,
            'clinic_id' => $clinic->id,
        ]);

        // Test FDI code mapping to tooth types
        $this->assertEquals('incisor', $pricing->getToothType(11)); // Central incisor
        $this->assertEquals('incisor', $pricing->getToothType(22)); // Lateral incisor
        $this->assertEquals('canine', $pricing->getToothType(13)); // Canine
        $this->assertEquals('premolar', $pricing->getToothType(14)); // First premolar
        $this->assertEquals('premolar', $pricing->getToothType(45)); // Second premolar
        $this->assertEquals('molar', $pricing->getToothType(16)); // First molar
        $this->assertEquals('molar', $pricing->getToothType(37)); // Second molar
        $this->assertEquals('molar', $pricing->getToothType(48)); // Third molar
    }

    /** @test */
    public function it_can_query_tooth_specific_services()
    {
        // Create tooth-specific service
        $toothSpecificService = Service::create([
            'name' => 'Crown Placement',
            'slug' => 'crown-placement',
            'description' => 'Dental crown placement',
            'category' => 'restorative',
            'duration_minutes' => 60,
            'is_tooth_specific' => true,
        ]);

        // Create general service
        $generalService = Service::create([
            'name' => 'Consultation',
            'slug' => 'consultation',
            'description' => 'General dental consultation',
            'category' => 'general',
            'duration_minutes' => 30,
            'is_tooth_specific' => false,
        ]);

        // Test tooth-specific scope
        $toothSpecificServices = Service::toothSpecific()->get();
        $this->assertCount(1, $toothSpecificServices);
        $this->assertEquals('Crown Placement', $toothSpecificServices->first()->name);

        // Test general query
        $allServices = Service::all();
        $this->assertCount(2, $allServices);
    }

    /** @test */
    public function it_handles_missing_tooth_modifiers_gracefully()
    {
        $service = Service::create([
            'name' => 'Test Service',
            'slug' => 'test-service',
            'description' => 'Test service without modifiers',
            'category' => 'general',
            'duration_minutes' => 30,
            'is_tooth_specific' => true,
        ]);

        $clinic = Clinic::factory()->create();

        $pricing = Pricing::create([
            'service_id' => $service->id,
            'clinic_id' => $clinic->id,
            'base_price' => 100.00,
            'currency' => 'USD',
            'valid_from' => now(),
            'valid_until' => now()->addYear(),
            'tooth_modifier' => null, // No modifiers
        ]);

        // Should return base price for all teeth when no modifiers exist
        $this->assertEqualsWithDelta(100.00, $pricing->calculateSingleToothPrice(11), 0.01); // Incisor
        $this->assertEqualsWithDelta(100.00, $pricing->calculateSingleToothPrice(16), 0.01); // Molar
        $this->assertEqualsWithDelta(100.00, $pricing->calculateSingleToothPrice(99), 0.01); // Invalid tooth
    }

    /** @test */
    public function it_calculates_complex_pricing_scenarios()
    {
        $service = Service::create([
            'name' => 'Composite Filling',
            'slug' => 'composite-filling',
            'description' => 'Tooth-colored filling',
            'category' => 'restorative',
            'duration_minutes' => 45,
            'is_tooth_specific' => true,
        ]);

        $clinic = Clinic::factory()->create();

        $pricing = Pricing::create([
            'service_id' => $service->id,
            'clinic_id' => $clinic->id,
            'base_price' => 80.00,
            'currency' => 'USD',
            'valid_from' => now(),
            'valid_until' => now()->addYear(),
            'tooth_modifier' => [
                'molar' => 1.75,     // 75% increase for molars
                'premolar' => 1.25,  // 25% increase for premolars
                'canine' => 1.15,    // 15% increase for canines
                'incisor' => 1.0,    // Base price for incisors
            ],
        ]);

        // Test mixed teeth calculations
        $this->assertEqualsWithDelta(80.00, $pricing->calculateSingleToothPrice(11), 0.01); // Incisor: 80 * 1.0 = 80
        $this->assertEqualsWithDelta(92.00, $pricing->calculateSingleToothPrice(13), 0.01); // Canine: 80 * 1.15 = 92
        $this->assertEqualsWithDelta(100.00, $pricing->calculateSingleToothPrice(14), 0.01); // Premolar: 80 * 1.25 = 100
        $this->assertEqualsWithDelta(140.00, $pricing->calculateSingleToothPrice(16), 0.01); // Molar: 80 * 1.75 = 140

        // Test different quadrants
        $this->assertEqualsWithDelta(80.00, $pricing->calculateSingleToothPrice(21), 0.01); // Upper left incisor
        $this->assertEqualsWithDelta(140.00, $pricing->calculateSingleToothPrice(36), 0.01); // Lower left molar
        $this->assertEqualsWithDelta(100.00, $pricing->calculateSingleToothPrice(44), 0.01); // Lower right premolar
    }

    /** @test */
    public function database_migrations_applied_correctly()
    {
        // Test that is_tooth_specific column exists in services table
        $this->assertTrue(\Schema::hasColumn('services', 'is_tooth_specific'));
        
        // Test that tooth_modifier column exists in pricing table
        $this->assertTrue(\Schema::hasColumn('pricing', 'tooth_modifier'));
        
        // Test that cancellation fields exist in treatment_plans table
        $this->assertTrue(\Schema::hasColumn('treatment_plans', 'cancelled_at'));
        $this->assertTrue(\Schema::hasColumn('treatment_plans', 'cancellation_reason'));
    }
}