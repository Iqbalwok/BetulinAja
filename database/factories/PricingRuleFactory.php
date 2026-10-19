<?php

namespace Database\Factories;

use App\Models\PricingRule;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class PricingRuleFactory extends Factory
{
    protected $model = PricingRule::class;

    public function definition(): array
    {
        return [
            'service_id' => Service::factory(),
            'name' => 'Standar Tarif',
            'base_price' => 75000,
            'travel_fee_per_km' => 5000,
            'minimum_travel_fee' => 10000,
            'difficulty_fee_light' => 0,
            'difficulty_fee_medium' => 25000,
            'difficulty_fee_heavy' => 60000,
            'platform_commission_rate' => 15.00,
            'is_active' => true,
        ];
    }
}
