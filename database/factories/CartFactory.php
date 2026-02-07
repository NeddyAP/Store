<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Cart::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id_user' => User::factory(),
            'id_product' => Product::factory(),
            'color' => $this->faker->colorName,
            'qty' => $this->faker->numberBetween(1, 5),
            'total' => $this->faker->randomNumber(4),
        ];
    }
}
