<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Condition;


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
            'condition_id' => function () {
                return Condition::inRandomOrder()->first()->id;
            },
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
