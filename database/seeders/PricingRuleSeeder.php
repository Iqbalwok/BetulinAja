<?php

namespace Database\Seeders;

use App\Models\PricingRule;
use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class PricingRuleSeeder extends Seeder
{
    public function run(): void
    {
        $categories = ServiceCategory::all();

        foreach ($categories as $cat) {
            PricingRule::updateOrCreate(
                ['category_id' => $cat->id, 'service_id' => null],
                [
                    'name' => "Tarif Standar {$cat->name}",
                    'base_price' => 75000,
                    'travel_fee_per_km' => 5000,
                    'minimum_travel_fee' => 10000,
                    'difficulty_fee_light' => 0,
                    'difficulty_fee_medium' => 25000,
                    'difficulty_fee_heavy' => 60000,
                    'platform_commission_rate' => 15.00,
                    'is_active' => true,
                ]
            );
        }
    }
}
