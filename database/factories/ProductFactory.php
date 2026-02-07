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
            'name' => $this->faker->word,
            'price' => $this->faker->numberBetween(100, 1000),
            'new_price' => $this->faker->optional()->numberBetween(50, 900),
            'spec' => $this->faker->sentence,
            'qty' => $this->faker->numberBetween(1, 100),
            'sold' => $this->faker->numberBetween(0, 50),
            'view' => $this->faker->numberBetween(0, 1000),
            'img' => $this->faker->imageUrl(),
            'desc' => $this->faker->paragraph,
            'category' => $this->faker->randomElement(['phone','laptop','computer','earphone','smart_watch']),
            'color' => $this->faker->safeColorName,
            'status' => 'Available',
        ];
    }
}
