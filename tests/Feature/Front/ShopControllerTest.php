<?php

namespace Tests\Feature\Front;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_show_method_with_valid_id()
    {
        // Create a product
        $product = Product::create([
            'name' => 'Test Product',
            'price' => 100,
            'spec' => 'Test Spec',
            'qty' => 10,
            'category' => 'laptop',
            'status' => 'Available',
            'desc' => 'Test Description',
            'img' => 'test.jpg'
        ]);

        // Make a GET request
        $response = $this->get(route('shop.detail', $product->id));

        // Assert response
        $response->assertStatus(200);
        $response->assertViewIs('front.shop.single.index');
        $response->assertViewHas('product', $product);
    }

    /** @test */
    public function test_show_method_with_invalid_id()
    {
        // Make a GET request with invalid ID
        $response = $this->get(route('shop.detail', 999999));

        // Assert response is 404
        $response->assertStatus(404);
    }

    /** @test */
    public function test_index_method()
    {
        // Create products
        Product::create([
            'name' => 'Test Product 1',
            'price' => 100,
            'spec' => 'Test Spec',
            'qty' => 10,
            'category' => 'laptop',
            'status' => 'Available',
            'desc' => 'Test Description',
            'img' => 'test1.jpg'
        ]);

        // Make a GET request
        $response = $this->get(route('shop'));

        // Assert response
        $response->assertStatus(200);
        $response->assertViewIs('front.shop.index');
        $response->assertViewHas('products');
        $response->assertViewHas('random');
    }
}
