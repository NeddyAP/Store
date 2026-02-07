<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_new_item_to_cart()
    {
        // Create a user
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'phone' => '081234567890',
            'address' => 'Test Address',
        ]);

        // Create a product
        $product = Product::create([
            'name' => 'Test Product',
            'price' => 10000,
            'qty' => 10,
            'spec' => 'Test Spec',
            'category' => 'phone',
            'status' => 'Available',
        ]);

        // Authenticate the user
        $this->actingAs($user);

        // Add item to cart
        $response = $this->post(route('cart.add', $product->id), [
            'qty' => 2,
            'color' => 'Red',
        ]);

        // Assert redirect
        $response->assertStatus(302);

        // Assert cart creation
        $this->assertDatabaseHas('carts', [
            'id_user' => $user->id,
            'id_product' => $product->id,
            'qty' => 2,
            'color' => 'Red',
            'total' => 20000,
        ]);
    }
}
