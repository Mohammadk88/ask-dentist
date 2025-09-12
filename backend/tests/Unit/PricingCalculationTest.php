<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Infrastructure\Models\Pricing;
use App\Infrastructure\Models\Service;
use App\Infrastructure\Models\Clinic;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PricingCalculationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_calculates_base_price_for_incisor_teeth()
    {
        $service = Service::factory()->create(['is_tooth_specific' => true]);
        $clinic = Clinic::factory()->create();
        
        $pricing = Pricing::factory()->create([
            'service_id' => $service->id,
            'clinic_id' => $clinic->id,
            'base_price' => 100.00,
            'tooth_modifier' => [
                'molar' => 1.5,
                'premolar' => 1.2,
                'canine' => 1.1,
                'incisor' => 1.0,
            ],
        ]);

        // Test incisor teeth (11-13, 21-23, 31-33, 41-43)
        $this->assertEquals(100.00, $pricing->calculateToothPrice(11));
        $this->assertEquals(100.00, $pricing->calculateToothPrice(21));
        $this->assertEquals(100.00, $pricing->calculateToothPrice(31));
        $this->assertEquals(100.00, $pricing->calculateToothPrice(41));
    }

    /** @test */
    public function it_calculates_modified_price_for_canine_teeth()
    {
        $service = Service::factory()->create(['is_tooth_specific' => true]);
        $clinic = Clinic::factory()->create();
        
        $pricing = Pricing::factory()->create([
            'service_id' => $service->id,
            'clinic_id' => $clinic->id,
            'base_price' => 100.00,
            'tooth_modifier' => [
                'molar' => 1.5,
                'premolar' => 1.2,
                'canine' => 1.1,
                'incisor' => 1.0,
            ],
        ]);

        // Test canine teeth (13, 23, 33, 43)
        $this->assertEquals(110.00, $pricing->calculateToothPrice(13));
        $this->assertEquals(110.00, $pricing->calculateToothPrice(23));
        $this->assertEquals(110.00, $pricing->calculateToothPrice(33));
        $this->assertEquals(110.00, $pricing->calculateToothPrice(43));
    }

    /** @test */
    public function it_calculates_modified_price_for_premolar_teeth()
    {
        $service = Service::factory()->create(['is_tooth_specific' => true]);
        $clinic = Clinic::factory()->create();
        
        $pricing = Pricing::factory()->create([
            'service_id' => $service->id,
            'clinic_id' => $clinic->id,
            'base_price' => 100.00,
            'tooth_modifier' => [
                'molar' => 1.5,
                'premolar' => 1.2,
                'canine' => 1.1,
                'incisor' => 1.0,
            ],
        ]);

        // Test premolar teeth (14-15, 24-25, 34-35, 44-45)
        $this->assertEquals(120.00, $pricing->calculateToothPrice(14));
        $this->assertEquals(120.00, $pricing->calculateToothPrice(15));
        $this->assertEquals(120.00, $pricing->calculateToothPrice(24));
        $this->assertEquals(120.00, $pricing->calculateToothPrice(25));
        $this->assertEquals(120.00, $pricing->calculateToothPrice(34));
        $this->assertEquals(120.00, $pricing->calculateToothPrice(35));
        $this->assertEquals(120.00, $pricing->calculateToothPrice(44));
        $this->assertEquals(120.00, $pricing->calculateToothPrice(45));
    }

    /** @test */
    public function it_calculates_modified_price_for_molar_teeth()
    {
        $service = Service::factory()->create(['is_tooth_specific' => true]);
        $clinic = Clinic::factory()->create();
        
        $pricing = Pricing::factory()->create([
            'service_id' => $service->id,
            'clinic_id' => $clinic->id,
            'base_price' => 100.00,
            'tooth_modifier' => [
                'molar' => 1.5,
                'premolar' => 1.2,
                'canine' => 1.1,
                'incisor' => 1.0,
            ],
        ]);

        // Test molar teeth (16-18, 26-28, 36-38, 46-48)
        $this->assertEquals(150.00, $pricing->calculateToothPrice(16));
        $this->assertEquals(150.00, $pricing->calculateToothPrice(17));
        $this->assertEquals(150.00, $pricing->calculateToothPrice(18));
        $this->assertEquals(150.00, $pricing->calculateToothPrice(26));
        $this->assertEquals(150.00, $pricing->calculateToothPrice(27));
        $this->assertEquals(150.00, $pricing->calculateToothPrice(28));
        $this->assertEquals(150.00, $pricing->calculateToothPrice(36));
        $this->assertEquals(150.00, $pricing->calculateToothPrice(37));
        $this->assertEquals(150.00, $pricing->calculateToothPrice(38));
        $this->assertEquals(150.00, $pricing->calculateToothPrice(46));
        $this->assertEquals(150.00, $pricing->calculateToothPrice(47));
        $this->assertEquals(150.00, $pricing->calculateToothPrice(48));
    }

    /** @test */
    public function it_returns_base_price_for_invalid_tooth_numbers()
    {
        $service = Service::factory()->create(['is_tooth_specific' => true]);
        $clinic = Clinic::factory()->create();
        
        $pricing = Pricing::factory()->create([
            'service_id' => $service->id,
            'clinic_id' => $clinic->id,
            'base_price' => 100.00,
            'tooth_modifier' => [
                'molar' => 1.5,
                'premolar' => 1.2,
                'canine' => 1.1,
                'incisor' => 1.0,
            ],
        ]);

        // Test invalid tooth numbers
        $this->assertEquals(100.00, $pricing->calculateToothPrice(99));
        $this->assertEquals(100.00, $pricing->calculateToothPrice(0));
        $this->assertEquals(100.00, $pricing->calculateToothPrice(-1));
    }

    /** @test */
    public function it_correctly_identifies_tooth_quadrants()
    {
        $service = Service::factory()->create(['is_tooth_specific' => true]);
        $clinic = Clinic::factory()->create();
        
        $pricing = Pricing::factory()->create([
            'service_id' => $service->id,
            'clinic_id' => $clinic->id,
            'base_price' => 100.00,
            'tooth_modifier' => [
                'molar' => 1.5,
                'premolar' => 1.2,
                'canine' => 1.1,
                'incisor' => 1.0,
            ],
        ]);

        // Test quadrant identification through tooth type detection
        // Upper right quadrant (11-18) - incisors
        $this->assertEquals('incisor', $pricing->getToothQuadrant(11));
        $this->assertEquals('incisor', $pricing->getToothQuadrant(12));
        
        // Upper right quadrant (11-18) - canine
        $this->assertEquals('canine', $pricing->getToothQuadrant(13));
        
        // Upper right quadrant (11-18) - premolars
        $this->assertEquals('premolar', $pricing->getToothQuadrant(14));
        $this->assertEquals('premolar', $pricing->getToothQuadrant(15));
        
        // Upper right quadrant (11-18) - molars
        $this->assertEquals('molar', $pricing->getToothQuadrant(16));
        $this->assertEquals('molar', $pricing->getToothQuadrant(17));
        $this->assertEquals('molar', $pricing->getToothQuadrant(18));
    }

    /** @test */
    public function it_handles_missing_tooth_modifier_gracefully()
    {
        $service = Service::factory()->create(['is_tooth_specific' => true]);
        $clinic = Clinic::factory()->create();
        
        $pricing = Pricing::factory()->create([
            'service_id' => $service->id,
            'clinic_id' => $clinic->id,
            'base_price' => 100.00,
            'tooth_modifier' => null, // No modifiers defined
        ]);

        // Should return base price when no modifiers are defined
        $this->assertEquals(100.00, $pricing->calculateToothPrice(16)); // Molar
        $this->assertEquals(100.00, $pricing->calculateToothPrice(11)); // Incisor
    }

    /** @test */
    public function it_handles_partial_tooth_modifier_configuration()
    {
        $service = Service::factory()->create(['is_tooth_specific' => true]);
        $clinic = Clinic::factory()->create();
        
        $pricing = Pricing::factory()->create([
            'service_id' => $service->id,
            'clinic_id' => $clinic->id,
            'base_price' => 100.00,
            'tooth_modifier' => [
                'molar' => 1.5, // Only molar modifier defined
            ],
        ]);

        // Should apply modifier for molar
        $this->assertEquals(150.00, $pricing->calculateToothPrice(16));
        
        // Should return base price for types without modifiers
        $this->assertEquals(100.00, $pricing->calculateToothPrice(11)); // Incisor
        $this->assertEquals(100.00, $pricing->calculateToothPrice(13)); // Canine
        $this->assertEquals(100.00, $pricing->calculateToothPrice(14)); // Premolar
    }

    /** @test */
    public function it_calculates_total_cost_for_multiple_teeth()
    {
        $service = Service::factory()->create(['is_tooth_specific' => true]);
        $clinic = Clinic::factory()->create();
        
        $pricing = Pricing::factory()->create([
            'service_id' => $service->id,
            'clinic_id' => $clinic->id,
            'base_price' => 100.00,
            'tooth_modifier' => [
                'molar' => 1.5,      // $150
                'premolar' => 1.2,   // $120
                'canine' => 1.1,     // $110
                'incisor' => 1.0,    // $100
            ],
        ]);

        // Test calculation for mixed tooth types
        $teeth = [11, 13, 14, 16]; // Incisor, Canine, Premolar, Molar
        $expectedTotal = 100.00 + 110.00 + 120.00 + 150.00; // $480
        
        $actualTotal = collect($teeth)->sum(function ($tooth) use ($pricing) {
            return $pricing->calculateToothPrice($tooth);
        });
        
        $this->assertEquals($expectedTotal, $actualTotal);
    }
}