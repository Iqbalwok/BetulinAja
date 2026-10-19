<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'customer_id' => User::factory()->customer(),
            'partner_id' => User::factory()->partner(),
            'rating' => 5,
            'comment' => 'Pelayanan sangat memuaskan, teknisi cepat dan ramah.',
            'is_visible' => true,
        ];
    }
}
