<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_product_index()
    {
        $admin = Admin::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        Product::factory()->count(3)->create();

        $response = $this->actingAs($admin, 'admin')
            ->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.product.index');
        $response->assertViewHas('products');
    }
}
