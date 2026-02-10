<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductControllerPerformanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_controller_constructor_performance()
    {
        // Enable query log
        DB::enableQueryLog();

        // Instantiate the controller
        $controller = new ProductController();

        // Get executed queries
        $queries = DB::getQueryLog();

        // There should be NO queries executed in the constructor
        $this->assertCount(0, $queries, 'Redundant queries found in ProductController constructor');
    }
}
