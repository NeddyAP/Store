<?php

namespace Tests\Unit\Models;

use App\Models\OrderDetail;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Shipping;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderDetailTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Test that an order detail can be created using the factory.
     */
    public function test_order_detail_can_be_created_with_factory()
    {
        // Create dependencies
        $user = User::factory()->create();
        $product = Product::factory()->create();

        // Create Shipping manually
        $shipping = Shipping::create([
             'id_user' => $user->id,
             'country' => 'Test Country',
             'name' => 'Test Name',
             'company_name' => 'Test Company',
             'address' => 'Test Address',
             'province' => 'Test Province',
             'zip' => '12345',
             'email' => 'test@example.com',
             'phone' => '1234567890',
        ]);

        // Manually create an order
        $order = Order::create([
             'code' => 'TEST-' . uniqid(),
             'id_user' => $user->id,
             'id_shipping' => $shipping->id,
             'total' => 100,
             'status_product' => 'Pending',
             'status_user' => 'Pending',
        ]);

        // Create OrderDetail using factory
        $orderDetail = OrderDetail::factory()->create([
            'id_user' => $user->id,
            'id_order' => $order->id,
            'id_product' => $product->id,
        ]);

        $this->assertModelExists($orderDetail);
        $this->assertDatabaseCount('order_details', 1);

        $this->assertEquals($user->id, $orderDetail->id_user);
        $this->assertEquals($order->id, $orderDetail->id_order);
        $this->assertEquals($product->id, $orderDetail->id_product);
    }

    /**
     * Test order detail fillable attributes.
     */
    public function test_order_detail_fillable_attributes()
    {
        $orderDetail = new OrderDetail();
        $expectedFillable = ['id_user', 'id_order', 'id_product', 'color', 'qty', 'total'];

        $this->assertEquals($expectedFillable, $orderDetail->getFillable());
    }

    /**
     * Test that an order detail can be created using create method.
     */
    public function test_order_detail_can_be_created_explicitly()
    {
        // Create dependencies
        $user = User::factory()->create();
        $product = Product::factory()->create();

        // Create Shipping manually
        $shipping = Shipping::create([
             'id_user' => $user->id,
             'country' => 'Test Country',
             'name' => 'Test Name',
             'company_name' => 'Test Company',
             'address' => 'Test Address',
             'province' => 'Test Province',
             'zip' => '12345',
             'email' => 'test@example.com',
             'phone' => '1234567890',
        ]);

        // Manually create an order
        $order = Order::create([
             'code' => 'TEST-' . uniqid(),
             'id_user' => $user->id,
             'id_shipping' => $shipping->id,
             'total' => 100,
             'status_product' => 'Pending',
             'status_user' => 'Pending',
        ]);

        // Create OrderDetail using create
        $orderDetail = OrderDetail::create([
            'id_user' => $user->id,
            'id_order' => $order->id,
            'id_product' => $product->id,
            'color' => 'Blue',
            'qty' => 2,
            'total' => 50,
        ]);

        $this->assertModelExists($orderDetail);
        $this->assertDatabaseCount('order_details', 1);

        $this->assertEquals($user->id, $orderDetail->id_user);
        $this->assertEquals($order->id, $orderDetail->id_order);
        $this->assertEquals($product->id, $orderDetail->id_product);
        $this->assertEquals('Blue', $orderDetail->color);
        $this->assertEquals(2, $orderDetail->qty);
        $this->assertEquals(50, $orderDetail->total);
    }
}
