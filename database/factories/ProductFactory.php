<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

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
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'price' => $this->faker->numberBetween(100, 1000),
            'new_price' => $this->faker->optional(0.3)->numberBetween(50, 90),
            'spec' => $this->faker->sentence,
            'qty' => $this->faker->numberBetween(1, 100),
            'sold' => $this->faker->numberBetween(0, 50),
            'view' => $this->faker->numberBetween(0, 500),
            'img' => $this->faker->word . '.jpg',
            'desc' => $this->faker->paragraph,
            'category' => $this->faker->randomElement(['phone', 'laptop', 'computer', 'earphone', 'smart_watch']),
            'color' => $this->faker->optional()->colorName,
            'status' => 'Available',
        ];
    }
}
