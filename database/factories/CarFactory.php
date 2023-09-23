<?php

namespace Database\Factories;

use App\Models\Car;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * Class CarFactory
 * @package Database\Factories
 */
class CarFactory extends Factory
{
    protected $model = Car::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'vin' => $this->faker->unique()->regexify('[A-Za-z0-9]{17}'),
            'mark' => Str::random(6),
        ];
    }
}
