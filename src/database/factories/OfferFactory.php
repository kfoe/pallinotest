<?php

namespace Database\Factories;

use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Offer>
 */
class OfferFactory extends Factory
{
    public function definition(): array
    {
        return [
            'external_id' => $this->faker->unique()->randomNumber(),
            'product' => $this->faker->name(),
            'price' => $this->faker->randomFloat(2),
            'currency' => $this->faker->currencyCode(),
            'description' => $this->faker->text(),
            'shop_id' => Shop::factory(),
        ];
    }
}
