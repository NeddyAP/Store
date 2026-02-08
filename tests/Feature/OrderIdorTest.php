<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;

class OrderIdorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_cannot_create_order_for_another_user()
    {
        // 1. Setup
        $attacker = User::create([
            'name' => 'Attacker',
            'email' => 'attacker@example.com',
            'password' => Hash::make('password'),
            'phone' => '1234567890',
            'address' => 'Attacker Address',
        ]);

        $victim = User::create([
            'name' => 'Victim',
            'email' => 'victim@example.com',
            'password' => Hash::make('password'),
            'phone' => '0987654321',
            'address' => 'Victim Address',
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
            'status' => 'Publish',
            'img' => 'test.jpg',
            'desc' => 'Test Description',
            'color' => 'Red'
        ]);

        // Create a cart item for the victim
        $cart = Cart::create([
            'id_user' => $victim->id,
            'id_product' => $product->id,
            'color' => 'Red',
            'qty' => 1,
            'total' => 90,
        ]);

        $payload = [
            'country' => 'Test Country',
            'name' => 'Attacker Name',
            'company_name' => 'Attacker Co',
            'address' => 'Attacker Address',
            'address2' => 'Apt 1',
            'province' => 'Attacker Province',
            'zip' => '12345',
            'email' => 'attacker@example.com',
            'phone' => '1234567890',
            'notes' => 'Test Notes',
            'total' => 100
        ];

        // 2. Act
        // Attacker logs in
        $this->actingAs($attacker);

        // Attacker tries to create order using Victim's ID
        // The URL uses Victim's ID.
        // If vulnerable, this will create order for Victim and delete Victim's cart.
        // If secure (fixed), this should create order for Attacker (ignoring URL ID) and NOT touch Victim's cart.
        $response = $this->post(route('order.create', ['id' => $victim->id]), $payload);

        // 3. Assert
        // Verify that the Victim's cart was NOT deleted
        // This assertion will FAIL if vulnerability exists (because cart IS deleted).
        // This assertion will PASS if vulnerability is fixed (because cart is NOT deleted).
        $this->assertDatabaseHas('carts', [
            'id' => $cart->id,
            'id_user' => $victim->id,
        ]);

        // Verify that NO order was created for the Victim
        $this->assertDatabaseMissing('orders', [
            'id_user' => $victim->id,
        ]);

        // Verify that an order WAS created for the Attacker (with our fix)
        $this->assertDatabaseHas('orders', [
            'id_user' => $attacker->id,
        ]);

        // Also verify Shipping is created for Attacker
        $this->assertDatabaseHas('shippings', [
            'id_user' => $attacker->id,
            'name' => 'Attacker Name',
        ]);
    }
}
