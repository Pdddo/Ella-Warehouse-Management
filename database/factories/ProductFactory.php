<?php

namespace Database\Factories;

use App\Models\Category;
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
            // Pilih sebuah ID kategori secara acak dari yang sudah ada
            'category_id' => Category::factory(),
            'sku' => fake()->unique()->bothify('SKU-####-????'), // contoh: SKU-5678-hjkl
            'name' => fake()->words(3, true), // contoh: "Minyak Goreng Sawit"
            'description' => fake()->paragraph(),
            'buy_price' => fake()->numberBetween(10000, 100000),
            'sell_price' => fake()->numberBetween(11000, 150000),
            'stock' => fake()->numberBetween(0, 200),
            'min_stock' => 10,
            'unit' => fake()->randomElement(['pcs', 'box', 'kg', 'liter']),
            'rack_location' => 'Rak '.fake()->randomElement(['A', 'B', 'C']).'-'.fake()->numberBetween(1, 5),
        ];
    }
}