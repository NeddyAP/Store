<?php

namespace Tests\Feature\Front;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_home_page_best_sellers_logic()
    {
        // Create products with varying 'sold' counts
        $highSoldProducts = Product::factory()->count(3)->create(['sold' => 100]);
        $mediumSoldProducts = Product::factory()->count(3)->create(['sold' => 50]);
        $lowSoldProducts = Product::factory()->count(4)->create(['sold' => 10]);

        // Make a GET request to the home page
        $response = $this->get(route('home'));

        // Assert response status is 200
        $response->assertStatus(200);

        // Assert the view has the 'bestSeller' variable
        $response->assertViewHas('bestSeller');

        // Get the bestSeller collection from the view
        $bestSellers = $response->viewData('bestSeller');

        // Assert that the bestSeller collection contains exactly 6 items
        $this->assertCount(6, $bestSellers);

        // Assert that the items in bestSeller are the ones with the highest sold counts
        // The first 3 should be from highSoldProducts
        foreach ($highSoldProducts as $product) {
            $this->assertTrue($bestSellers->contains($product));
        }

        // The next 3 should be from mediumSoldProducts
        foreach ($mediumSoldProducts as $product) {
            $this->assertTrue($bestSellers->contains($product));
        }

        // None of the lowSoldProducts should be in the bestSellers
        foreach ($lowSoldProducts as $product) {
            $this->assertFalse($bestSellers->contains($product));
        }

        // Assert ordering is correct (descending by sold)
        $previousSold = 1000;
        foreach ($bestSellers as $product) {
            $this->assertTrue($product->sold <= $previousSold);
            $previousSold = $product->sold;
        }
    }
}
