<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_delete_others_cart_item()
    {
        // Create two users
        $userA = User::create([
            'name' => 'User A',
            'email' => 'usera@example.com',
            'password' => bcrypt('password'),
            'phone' => '1234567890',
        ]);

        $userB = User::create([
            'name' => 'User B',
            'email' => 'userb@example.com',
            'password' => bcrypt('password'),
            'phone' => '0987654321',
        ]);

        // Create a product
        $product = Product::create([
            'name' => 'Test Product',
            'category' => 'phone',
            'price' => 100,
            'new_price' => 90,
            'spec' => 'Test Spec',
            'qty' => 10,
            'sold' => 0,
            'view' => 0,
            'status' => 'active',
            'img' => 'test.jpg',
            'desc' => 'Test Description',
            'color' => 'red',
        ]);

        // Create a cart item for User A
        $cartItem = Cart::create([
            'id_user' => $userA->id,
            'id_product' => $product->id,
            'color' => 'red',
            'qty' => '1',
            'total' => 90,
        ]);

        // Login as User B
        $this->actingAs($userB);

        // Attempt to delete User A's cart item
        $response = $this->get(route('cart.delete', $cartItem->id));

        // Assert that the cart item still exists
        $this->assertDatabaseHas('carts', ['id' => $cartItem->id]);
    }

    public function test_user_can_delete_own_cart_item()
    {
        // Create user
        $user = User::create([
            'name' => 'User Own',
            'email' => 'userown@example.com',
            'password' => bcrypt('password'),
            'phone' => '1111111111',
        ]);

        // Create product
        $product = Product::create([
            'name' => 'Own Product',
            'category' => 'phone',
            'price' => 50,
            'qty' => 5,
            'color' => 'blue',
            'img' => 'own.jpg',
            'spec' => 'Test Spec',
            'desc' => 'Test Description',
            'sold' => 0,
            'view' => 0,
            'status' => 'active',
        ]);

        // Create cart item
        $cartItem = Cart::create([
            'id_user' => $user->id,
            'id_product' => $product->id,
            'color' => 'blue',
            'qty' => '1',
            'total' => 50,
        ]);

        // Login
        $this->actingAs($user);

        // Delete
        $this->get(route('cart.delete', $cartItem->id));

        // Assert deleted
        $this->assertDatabaseMissing('carts', ['id' => $cartItem->id]);
    }
}
