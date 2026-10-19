<?php

namespace Database\Factories;

use App\Models\PartnerProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PartnerProfileFactory extends Factory
{
    protected $model = PartnerProfile::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->partner(),
            'description' => 'Teknisi berpengalaman dan profesional.',
            'id_card_number' => fake()->numerify('################'),
            'verification_status' => 'verified',
            'availability_status' => 'online',
            'current_latitude' => -6.1818,
            'current_longitude' => 106.8223,
            'average_rating' => 4.8,
            'total_reviews' => 12,
            'completed_jobs' => 25,
            'years_experience' => 5,
            'balance' => 500000,
        ];
    }
}
