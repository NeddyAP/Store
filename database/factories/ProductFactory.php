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
            'category' => $this->faker->randomElement(['phone','laptop','computer','earphone','smart_watch']),
            'price' => $this->faker->numberBetween(100, 1000),
            'new_price' => $this->faker->numberBetween(80, 900),
            'spec' => $this->faker->sentence,
            'qty' => $this->faker->numberBetween(1, 100),
            'sold' => $this->faker->numberBetween(0, 50),
            'view' => $this->faker->numberBetween(0, 1000),
            'status' => 'Available',
            'img' => $this->faker->imageUrl(),
            'desc' => $this->faker->paragraph,
            'color' => $this->faker->colorName,
        ];
    }
}
