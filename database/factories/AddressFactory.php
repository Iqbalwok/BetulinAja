<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->customer(),
            'label' => 'Rumah',
            'address' => fake()->address(),
            'landmark' => 'Dekat masjid raya',
            'city' => 'Jakarta Pusat',
            'district' => 'Menteng',
            'latitude' => -6.1818,
            'longitude' => 106.8223,
            'is_primary' => true,
        ];
    }
}
