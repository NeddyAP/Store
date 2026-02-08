<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartAddTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that adding an existing product to the cart updates the quantity.
     *
     * @return void
     */
    public function test_update_cart_quantity_for_existing_item()
    {
        // 1. Create User
        $user = User::factory()->create();

        // 2. Create Product
        $product = Product::factory()->create([
            'price' => 100,
            'qty' => 50,
        ]);

        // 3. Create existing Cart item
        $initialQty = 2;
        $cart = Cart::factory()->create([
            'id_user' => $user->id,
            'id_product' => $product->id,
            'qty' => $initialQty,
            'total' => $product->price * $initialQty,
        ]);

        // 4. Act as user and make request
        $addQty = 3;
        $response = $this->actingAs($user)
            ->post(route('cart.add', ['id' => $product->id]), [
                'qty' => $addQty,
                'color' => 'red', // assuming color is required or optional
            ]);

        // 5. Assertions
        $response->assertStatus(302); // Redirect back
        $response->assertSessionHas('success', 'Artikel Berhasil Ditambahkan');

        // Reload cart from database
        $cart->refresh();

        // Check if quantity is updated
        $this->assertEquals($initialQty + $addQty, $cart->qty);
    }

    /**
     * Test that adding a new product to the cart creates a new cart item.
     *
     * @return void
     */
    public function test_add_new_item_to_cart()
    {
        // 1. Create User
        $user = User::factory()->create();

        // 2. Create Product
        $product = Product::factory()->create([
            'price' => 100,
            'qty' => 50,
        ]);

        // 3. Act as user and make request
        $qty = 2;
        $color = 'blue';
        $response = $this->actingAs($user)
            ->post(route('cart.add', ['id' => $product->id]), [
                'qty' => $qty,
                'color' => $color,
            ]);

        // 4. Assertions
        $response->assertStatus(302); // Redirect back
        $response->assertSessionHas('success', 'Artikel Berhasil Ditambahkan');

        // Check database
        $this->assertDatabaseHas('carts', [
            'id_user' => $user->id,
            'id_product' => $product->id,
            'qty' => $qty,
            'color' => $color,
            'total' => $product->price * $qty,
        ]);
    }
}
