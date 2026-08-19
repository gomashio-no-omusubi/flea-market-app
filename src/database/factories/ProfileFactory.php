<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'postcode' => $this->faker->numerify('###-####'),
            'address' => $this->faker->prefecture() . $this->faker->city() . $this->faker->streetAddress(),
            'building' => $this->faker->secondaryAddress(),
            'img_url' => null,
        ];
    }
}
