<?php

namespace Database\Seeders;

use App\Models\Pricing;
use App\Models\Service;
use App\Models\Clinic;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PricingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing services and clinics
        $services = Service::all();
        $clinics = Clinic::all();

        if ($services->isEmpty()) {
            $this->command->error('No services found. Please run ServiceSeeder first.');
            return;
        }

        if ($clinics->isEmpty()) {
            $this->command->error('No clinics found. Please ensure clinics exist.');
            return;
        }

        // Base pricing for different service categories
        $basePrices = [
            'general' => [50, 150],        // $50-150
            'preventive' => [30, 120],     // $30-120
            'restorative' => [100, 800],   // $100-800
            'cosmetic' => [200, 2000],     // $200-2000
            'orthodontic' => [500, 5000],  // $500-5000
            'surgical' => [300, 3000],     // $300-3000
            'endodontic' => [400, 1200],   // $400-1200
            'periodontal' => [150, 600],   // $150-600
            'pediatric' => [40, 150],      // $40-150
            'emergency' => [100, 500],     // $100-500
        ];

        $currencies = ['USD', 'EUR', 'TRY'];

        $pricingCount = 0;

        foreach ($clinics as $clinic) {
            foreach ($services as $service) {
                // Get price range for this service category
                $priceRange = $basePrices[$service->category] ?? [50, 300];
                $basePrice = rand($priceRange[0], $priceRange[1]);

                // Select a random currency
                $currency = $currencies[array_rand($currencies)];

                // Adjust price based on currency
                if ($currency === 'EUR') {
                    $basePrice *= 0.85; // EUR is usually slightly less than USD
                } elseif ($currency === 'TRY') {
                    $basePrice *= 30; // TRY is much higher numbers
                }

                // Random discount (20% chance)
                $discount = rand(0, 100) < 20 ? rand(5, 25) : 0;

                // Random negotiable flag (30% chance)
                $isNegotiable = rand(0, 100) < 30;

                Pricing::create([
                    'clinic_id' => $clinic->id,
                    'service_id' => $service->id,
                    'base_price' => round($basePrice, 2),
                    'currency' => $currency,
                    'discount_percentage' => $discount,
                    'valid_from' => now()->subDays(rand(0, 30)),
                    'valid_until' => rand(0, 100) < 80 ? now()->addMonths(rand(3, 12)) : null,
                    'is_negotiable' => $isNegotiable,
                    'notes' => $isNegotiable ? 'Price may vary based on complexity and patient needs.' : null,
                    'conditions' => $discount > 0 ? [
                        ['condition' => 'New Patient Discount', 'description' => 'Applicable for first-time patients only']
                    ] : null,
                ]);

                $pricingCount++;
            }
        }

        $this->command->info('Pricing seeded successfully!');
        $this->command->info("Created {$pricingCount} pricing entries for {$clinics->count()} clinics and {$services->count()} services");
    }
}
