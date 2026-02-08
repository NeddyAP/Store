<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'price' => $this->faker->numberBetween(100, 2000),
            'new_price' => $this->faker->optional(0.5)->numberBetween(50, 1900),
            'spec' => $this->faker->sentence(),
            'qty' => $this->faker->numberBetween(1, 100),
            'sold' => $this->faker->numberBetween(0, 50),
            'view' => $this->faker->numberBetween(0, 1000),
            'img' => $this->faker->imageUrl(640, 480, 'technics', true),
            'desc' => $this->faker->paragraph(),
            'category' => $this->faker->randomElement(['phone', 'laptop', 'computer', 'earphone', 'smart_watch']),
            'color' => $this->faker->colorName(),
            'status' => 'Available',
        ];
    }
}
