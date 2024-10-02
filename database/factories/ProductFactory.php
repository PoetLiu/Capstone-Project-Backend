<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'brand' => fake()->company(),
            'name' => fake()->sentence(nbWords:3),
            'description' => fake()->sentence(),
            'specifications' =>  fake()->sentence(),
            'price' => fake()->randomNumber(2),
            'onsale_price' => fake()->optional(0.1)->randomNumber(2),
            'is_featured' => fake()->boolean(50),
            'category_id' => fake()->numberBetween(1, 10),
            'stock' => fake()->numberBetween(1, 99),
        ];
    }
}
