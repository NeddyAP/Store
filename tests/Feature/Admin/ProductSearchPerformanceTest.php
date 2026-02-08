<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProductSearchPerformanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_redundant_product_query_is_not_executed_during_search()
    {
        // Setup admin user
        $admin = Admin::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        $searchTerm = 'something';

        // Create enough products to trigger pagination (18 per page, so 20 is enough)
        // Ensure they match the search term
        Product::factory()->count(20)->create([
            'name' => $searchTerm.' '.Str::random(5),
        ]);

        // Enable query logging
        DB::enableQueryLog();

        // Perform the search request
        $response = $this->actingAs($admin, 'admin')
            ->get(route('products.search', ['search' => $searchTerm]));

        $response->assertStatus(200);

        // Get executed queries
        $queries = DB::getQueryLog();

        // Filter for product selection queries
        $productSelectQueries = array_filter($queries, function ($log) {
            $query = strtolower($log['query']);

            return str_contains($query, 'select') &&
                   str_contains($query, 'products') &&
                   str_contains($query, 'limit 18');
        });

        // We expect:
        // 1. Search results (with WHERE clauses)
        // 2. NO All products query (without WHERE clauses)

        $redundantQueryFound = false;
        foreach ($productSelectQueries as $log) {
            $query = strtolower($log['query']);
            // A query without "like" or specific where clauses is likely the redundant one fetching all products
            if (! str_contains($query, 'like') && str_contains($query, 'order by "created_at" desc')) {
                $redundantQueryFound = true;
                break;
            }
        }

        $this->assertFalse($redundantQueryFound, 'The redundant query fetching all products was found, but it should be removed.');

        // Also verify that pagination links contain the search term
        // The view contains {{ $products->links(...) }}
        // The response content should have `search=something` in the links.
        $response->assertSee('search='.$searchTerm);
    }
}
