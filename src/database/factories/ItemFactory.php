<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;


class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'condition_id' => 1,
            'name' => $this->faker->word(),
            'price' => $this->faker->numberBetween(500, 50000),
            'brand' => $this->faker->randomElement([
                $this->faker->company(),
                $this->faker->word(),
                null
            ]),
            'description' => $this->faker->realText(50),
            'img_url' => 'images/dummy.png',
        ];
    }
}
