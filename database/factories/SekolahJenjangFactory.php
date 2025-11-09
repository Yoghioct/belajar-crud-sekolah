<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\SekolahJenjang;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SekolahJenjang>
 */
class SekolahJenjangFactory extends Factory
{
    protected $model = SekolahJenjang::class;

    public function definition(): array
    {
        return [
            'code' => strtoupper($this->faker->unique()->lexify('???')),
            'name' => $this->faker->words(2, true),
            'order_number' => $this->faker->numberBetween(0, 100),
            'is_active' => $this->faker->boolean(80),
        ];
    }
}

