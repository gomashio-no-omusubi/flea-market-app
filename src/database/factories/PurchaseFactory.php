<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Item;

class PurchaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 1,
            'item_id' => Item::factory(),
            'payment_method' => $this->faker->randomElement(['コンビニ支払い', 'カード支払い']),
            'shipping_postcode' => $this->faker->numerify('###-####'),
            'shipping_address' => $this->faker->prefecture() . $this->faker->city() . $this->faker->streetAddress(),
            'shipping_building' => $this->faker->secondaryAddress(),
        ];
    }
}
