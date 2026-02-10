<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that an authenticated user can view their cart contents.
     */
    public function test_user_can_view_cart_contents(): void
    {
        // Create a user
        $user = User::factory()->create();

        // Create a product
        $product = Product::factory()->create([
            'name' => 'Test Product',
            'price' => 100,
            'new_price' => 90,
            'img' => 'test-image.jpg',
            'category' => 'phone',
        ]);

        // Create a cart item for the user
        $cart = Cart::factory()->create([
            'id_user' => $user->id,
            'id_product' => $product->id,
            'qty' => 2,
            'total' => 180, // 2 * 90
        ]);

        // Act as the user
        $response = $this->actingAs($user)
                         ->get(route('cart'));

        // Assert response status
        $response->assertStatus(200);

        // Assert view is correct
        $response->assertViewIs('front.cart.index');

        // Assert view data
        $response->assertViewHas('carts', function ($carts) use ($product, $cart) {
            // Check if the collection contains the cart item
            $cartItem = $carts->first();

            // Note: The controller uses join, so attributes from products are available on the cart object
            return $carts->count() === 1 &&
                   $cartItem->id === $cart->id &&
                   $cartItem->name === $product->name &&
                   $cartItem->price === $product->price &&
                   $cartItem->new_price === $product->new_price &&
                   $cartItem->img === $product->img &&
                   $cartItem->category === $product->category;
        });
    }

    /**
     * Test that unauthenticated user is redirected to login.
     */
    public function test_unauthenticated_user_cannot_view_cart(): void
    {
        $response = $this->get(route('cart'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /**
     * Test that user can delete their own cart item.
     */
    public function test_user_can_delete_own_cart_item(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['category' => 'phone']);
        $cartItem = Cart::factory()->create([
            'id_user' => $user->id,
            'id_product' => $product->id,
        ]);

        $this->actingAs($user)
             ->get(route('cart.delete', $cartItem->id))
             ->assertRedirect();

        $this->assertDatabaseMissing('carts', ['id' => $cartItem->id]);
    }

    /**
     * Test that user cannot delete other user's cart item.
     */
    public function test_user_cannot_delete_other_user_cart_item(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $product = Product::factory()->create(['category' => 'phone']);
        $cartItem = Cart::factory()->create([
            'id_user' => $user1->id,
            'id_product' => $product->id,
        ]);

        $this->actingAs($user2)
             ->get(route('cart.delete', $cartItem->id))
             ->assertRedirect();

        $this->assertDatabaseHas('carts', ['id' => $cartItem->id]);
    }

    /**
     * Test that unauthenticated user cannot delete cart item.
     */
    public function test_unauthenticated_user_cannot_delete_cart_item(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['category' => 'phone']);
        $cartItem = Cart::factory()->create([
            'id_user' => $user->id,
            'id_product' => $product->id,
        ]);

        $this->get(route('cart.delete', $cartItem->id))
             ->assertRedirect(route('login'));

        $this->assertDatabaseHas('carts', ['id' => $cartItem->id]);
    }
}
