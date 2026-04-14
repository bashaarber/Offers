<?php

namespace Database\Factories;

use App\Models\Element;
use Illuminate\Database\Eloquent\Factories\Factory;

class ElementFactory extends Factory
{
    protected $model = Element::class;

    public function definition(): array
    {
        return [
            'name'        => $this->faker->unique()->words(3, true),
            'quantity'    => 1,
            'isSelected0' => false,
            'isSelected1' => false,
            'isSelected2' => false,
            'isSelected3' => false,
            'isSelected4' => false,
        ];
    }
}
