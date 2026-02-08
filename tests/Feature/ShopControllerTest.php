<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the shop index page loads correctly with products.
     *
     * @return void
     */
    public function test_shop_page_loads_correctly()
    {
        // Create 10 products with distinct created_at timestamps
        $products = Product::factory()->count(10)->sequence(function ($sequence) {
            return ['created_at' => now()->addMinutes($sequence->index)];
        })->create();

        // Make request to shop page
        $response = $this->get(route('shop'));

        // Assert status is 200
        $response->assertStatus(200);

        // Assert view is correct
        $response->assertViewIs('front.shop.index');

        // Assert view data
        $response->assertViewHas(['products', 'random']);

        // Get view data
        $viewProducts = $response->viewData('products');
        $viewRandom = $response->viewData('random');

        // Assert products are paginated (6 per page)
        $this->assertEquals(6, $viewProducts->perPage());
        $this->assertEquals(10, $viewProducts->total());

        // Assert products are ordered by created_at desc
        // The last created product should be first in the list
        $this->assertEquals($products->last()->id, $viewProducts->first()->id);

        // Assert random products are returned (should be a collection)
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $viewRandom);

        // Assert we have some random products
        $this->assertGreaterThan(0, $viewRandom->count());
    }
}
