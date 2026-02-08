<?php

namespace Tests\Feature\Performance;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class HomePerformanceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Benchmark the home page.
     *
     * @return void
     */
    public function test_home_page_benchmark()
    {
        // Use file cache driver for more realistic performance testing
        config(['cache.default' => 'file']);
        Cache::flush();

        // Seed the database
        Product::factory()->count(1000)->create();

        // Warm up the cache
        $this->get('/');

        $start = microtime(true);

        $response = $this->get('/');

        $end = microtime(true);
        $duration = $end - $start;

        $response->assertStatus(200);

        echo "\nHome page load time (cached): ".number_format($duration * 1000, 2)."ms\n";
    }
}
