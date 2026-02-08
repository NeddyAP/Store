<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_edit_product_page()
    {
        // Create an admin
        $admin = Admin::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        // Create a product
        $product = Product::factory()->create();

        // Act as admin and visit edit page
        $response = $this->actingAs($admin, 'admin')->get(route('products.edit', $product->id));

        // Assert OK
        $response->assertStatus(200);
        $response->assertViewIs('admin.product.actions.edit');
        $response->assertViewHas('product');
    }

    public function test_admin_view_edit_product_page_not_found()
    {
        // Create an admin
        $admin = Admin::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        // Act as admin and visit edit page for non-existent product
        $response = $this->actingAs($admin, 'admin')->get(route('products.edit', 99999));

        // Expect 404
        $response->assertStatus(404);
    }
}
