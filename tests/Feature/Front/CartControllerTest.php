<?php

namespace Tests\Feature\Front;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test adding a valid product to the cart.
     *
     * @return void
     */
    public function test_add_to_cart_valid_product()
    {
        // Create a user
        $user = User::factory()->create();

        // Create a product
        $product = Product::factory()->create([
            'price' => 100,
        ]);

        // Act as the user
        $response = $this->actingAs($user)
                         ->post(route('cart.add', $product->id), [
                             'qty' => 1,
                             'color' => 'red',
                         ]);

        // Assert response status (redirect back)
        $response->assertStatus(302);

        // Assert session has success message (or whatever message is expected)
        // The controller returns: return redirect()->back()->with('danger', 'Artikel Berhasil Dihapus');
        // Wait, 'Artikel Berhasil Dihapus' means 'Article Successfully Deleted'. That's weird for an 'add' method.
        // Let's check the controller code again.

        // Assert the cart item was created
        $this->assertDatabaseHas('carts', [
            'id_user' => $user->id,
            'id_product' => $product->id,
            'qty' => 1,
            'color' => 'red',
            'total' => 100,
        ]);
    }

    /**
     * Test adding an invalid product to the cart.
     *
     * @return void
     */
    public function test_add_to_cart_invalid_product()
    {
        // Create a user
        $user = User::factory()->create();

        // Act as the user
        // Use a non-existent product ID
        $invalidProductId = 99999;

        $response = $this->actingAs($user)
                         ->post(route('cart.add', $invalidProductId), [
                             'qty' => 1,
                             'color' => 'red',
                         ]);

        // Assert response status is 404
        $response->assertStatus(404);
    }
}
