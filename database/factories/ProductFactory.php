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
            'price' => $this->faker->randomNumber(4),
            'new_price' => $this->faker->randomNumber(4),
            'spec' => $this->faker->sentence,
            'qty' => $this->faker->randomNumber(2),
            'sold' => 0,
            'view' => 0,
            'status' => 1,
            'img' => $this->faker->imageUrl(),
            'desc' => $this->faker->paragraph,
            'color' => $this->faker->colorName,
        ];
    }
}
