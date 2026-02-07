<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_method_displays_product_detail_page()
    {
        // Arrange
        $product = Product::factory()->create([
            'name' => 'Test Product',
            'category' => 'phone',
            'img' => 'test-image.jpg',
        ]);

        // Tag the product so that the view's tag loop has something to iterate over
        $product->tag('Red');

        // Create some other products to populate the 'random' section
        Product::factory()->count(5)->create();

        // Act
        $response = $this->get(route('shop.detail', $product->id));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('front.shop.single.index');
        $response->assertViewHas('product');
        $response->assertViewHas('random');
        $response->assertSee('Test Product');

        // Assert that the tag is visible
        $response->assertSee('Red');
    }
}
