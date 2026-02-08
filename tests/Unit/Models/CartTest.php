<?php

namespace Tests\Unit\Models;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a cart can be created using the factory.
     */
    public function test_cart_can_be_created_with_factory()
    {
        $cart = Cart::factory()->create();

        $this->assertModelExists($cart);
        $this->assertDatabaseCount('carts', 1);

        // Check if related models are created
        $this->assertModelExists(User::find($cart->id_user));
        $this->assertModelExists(Product::find($cart->id_product));
    }

    /**
     * Test cart attributes.
     */
    public function test_cart_attributes()
    {
        $cart = Cart::factory()->create([
            'color' => 'Red',
            'qty' => '10',
            'total' => 1000,
        ]);

        $this->assertEquals('Red', $cart->color);
        $this->assertEquals('10', $cart->qty);
        $this->assertEquals(1000, $cart->total);
        $this->assertIsInt($cart->total);
        $this->assertIsString($cart->qty);
    }
}
