<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);
        return [
            'category_id' => ServiceCategory::factory(),
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'description' => fake()->paragraph(1),
            'base_price' => fake()->randomElement([50000, 75000, 100000, 150000, 200000]),
            'estimated_duration_minutes' => 60,
            'is_active' => true,
            'sort_order' => 0,
        ];
    }
}
