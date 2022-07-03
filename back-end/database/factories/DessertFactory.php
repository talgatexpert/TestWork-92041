<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dessert>
 */
class DessertFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $user = User::inRandomOrder()->first();
        return [
            'name' => $this->faker->name,
            'calories' => $this->faker->randomFloat(1, 0.1, 5000),
            'fat' => $this->faker->randomFloat(1, 0.1, 200),
            'carbs' => $this->faker->randomDigitNotZero(),
            'protein' => $this->faker->randomDigitNotZero(),
            'user_id' => $user->id
        ];
    }
}
