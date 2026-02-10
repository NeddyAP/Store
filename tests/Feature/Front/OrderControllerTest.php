<?php

namespace Tests\Feature\Front;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test that an authenticated user can create an order.
     *
     * @return void
     */
    public function test_authenticated_user_can_create_order()
    {
        // Create user
        $user = User::factory()->create();

        // Login
        $this->actingAs($user);

        // Create product
        $product = Product::factory()->create([
            'price' => 100,
            'new_price' => 90,
        ]);

        // Create cart item
        $cart = Cart::factory()->create([
            'id_user' => $user->id,
            'id_product' => $product->id,
            'qty' => '2',
            'total' => 180, // 2 * 90
            'color' => 'Red',
        ]);

        // Request data
        $requestData = [
            'country' => 'Test Country',
            'name' => 'Test User',
            'company_name' => 'Test Company',
            'address' => 'Test Address',
            'address2' => 'Apt 1',
            'province' => 'Test Province',
            'zip' => '12345',
            'email' => 'test@example.com',
            'phone' => '1234567890',
            'notes' => 'Test Notes',
            'total' => 180,
        ];

        // Make POST request
        $response = $this->post(route('order.create', ['id' => $user->id]), $requestData);

        // Assert response
        $response->assertStatus(200);
        $response->assertViewIs('front.order.thankyou');

        // Assert Shipping
        $this->assertDatabaseHas('shippings', [
            'id_user' => $user->id,
            'country' => 'Test Country',
            'name' => 'Test User',
            'province' => 'Test Province',
            'email' => 'test@example.com',
        ]);

        // Assert Order
        $this->assertDatabaseHas('orders', [
            'id_user' => $user->id,
            'total' => 180,
            'status_product' => 'Proccess',
            'status_user' => 'Proccess',
        ]);

        // Assert Order Details
        $this->assertDatabaseHas('order_details', [
            'id_user' => $user->id,
            'id_product' => $product->id,
            'color' => 'Red',
            'qty' => '2',
            'total' => 180,
        ]);

        // Assert Cart is empty
        $this->assertDatabaseMissing('carts', [
            'id' => $cart->id,
        ]);
    }

    /**
     * Test that an authenticated user can create an order with an empty cart.
     * (Current behavior: it creates an order with no details)
     *
     * @return void
     */
    public function test_authenticated_user_can_create_order_with_empty_cart()
    {
        // Create user
        $user = User::factory()->create();

        // Login
        $this->actingAs($user);

        // Request data
        $requestData = [
            'country' => 'Test Country',
            'name' => 'Test User',
            'company_name' => 'Test Company',
            'address' => 'Test Address',
            'address2' => 'Apt 1',
            'province' => 'Test Province',
            'zip' => '12345',
            'email' => 'test@example.com',
            'phone' => '1234567890',
            'notes' => 'Test Notes',
            'total' => 0,
        ];

        // Make POST request
        $response = $this->post(route('order.create', ['id' => $user->id]), $requestData);

        // Assert response
        $response->assertStatus(200);
        $response->assertViewIs('front.order.thankyou');

        // Assert Order
        $this->assertDatabaseHas('orders', [
            'id_user' => $user->id,
            'total' => 0,
            'status_product' => 'Proccess',
            'status_user' => 'Proccess',
        ]);

        // Assert Order Details are empty
        // Find the order ID first
        $order = Order::where('id_user', $user->id)->first();
        $this->assertDatabaseMissing('order_details', [
            'id_order' => $order->id,
        ]);
    }

    /**
     * Test that an unauthenticated user cannot create an order.
     *
     * @return void
     */
    public function test_unauthenticated_user_cannot_create_order()
    {
        $response = $this->post(route('order.create', ['id' => 1]), []);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
