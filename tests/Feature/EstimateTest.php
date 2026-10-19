<?php

namespace Tests\Feature;

use App\Models\PricingRule;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EstimateTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_calculate_price_estimate(): void
    {
        $category = ServiceCategory::factory()->create();
        $service = Service::factory()->create([
            'category_id' => $category->id,
            'base_price' => 75000,
        ]);

        PricingRule::factory()->create([
            'service_id' => $service->id,
            'category_id' => $category->id,
            'base_price' => 75000,
            'travel_fee_per_km' => 5000,
            'minimum_travel_fee' => 10000,
            'difficulty_fee_light' => 0,
            'difficulty_fee_medium' => 25000,
            'difficulty_fee_heavy' => 60000,
            'platform_commission_rate' => 15.00,
        ]);

        $response = $this->postJson('/api/v1/estimates', [
            'service_id' => $service->id,
            'difficulty_level' => 'medium',
            'distance_km' => 4.0, // 4km * 5000 = 20,000 travel fee
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'base_price' => 75000,
                    'travel_fee' => 20000,
                    'difficulty_level' => 'medium',
                    'difficulty_fee' => 25000,
                    'total_estimate' => 120000,
                    'platform_commission' => 18000,
                    'partner_earning' => 102000,
                    'currency' => 'IDR',
                ],
            ]);
    }
}
