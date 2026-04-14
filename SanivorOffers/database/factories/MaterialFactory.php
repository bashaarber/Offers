<?php

namespace Database\Factories;

use App\Models\Material;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaterialFactory extends Factory
{
    protected $model = Material::class;

    public function definition(): array
    {
        return [
            'name'          => $this->faker->unique()->words(2, true),
            'unit'          => $this->faker->randomElement(['St.', 'm', 'kg', 'Psch.']),
            'price_in'      => $this->faker->randomFloat(2, 0, 500),
            'price_out'     => $this->faker->randomFloat(2, 0, 1000),
            'z_schlosserei' => 0.0,
            'z_pe'          => '0',
            'z_montage'     => '0',
            'z_total'       => 0.0,
            'zeit_cost'     => 0.0,
            'total'         => 0.0,
        ];
    }
}
