<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use App\Services\OrderNumberService;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'order_number' => OrderNumberService::generate(),
            'customer_id' => User::factory()->customer(),
            'partner_id' => null,
            'service_id' => Service::factory(),
            'problem_description' => fake()->sentence(8),
            'address_text' => fake()->address(),
            'landmark' => 'Patokan pos satpam',
            'latitude' => -6.1818,
            'longitude' => 106.8223,
            'scheduled_at' => now()->addDay(),
            'assignment_method' => 'auto',
            'status' => 'pending',
            'base_price' => 75000,
            'distance_km' => 5.2,
            'travel_fee' => 26000,
            'difficulty_level' => 'medium',
            'difficulty_fee' => 25000,
            'additional_fee' => 0,
            'total_estimate' => 126000,
            'final_price' => 126000,
            'platform_commission' => 18900,
            'partner_earning' => 107100,
        ];
    }
}
