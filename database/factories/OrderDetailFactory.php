<?php

namespace Database\Factories;

use App\Models\OrderDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderDetailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderDetail::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'color' => $this->faker->colorName(),
            'qty' => $this->faker->numberBetween(1, 10),
            'total' => $this->faker->numberBetween(100, 1000),
            // Foreign keys: id_user, id_order, id_product will be provided by tests
        ];
    }
}
