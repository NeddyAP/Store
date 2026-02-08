<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductSecurityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that product description allows XSS if not sanitized.
     * This test confirms the vulnerability exists.
     *
     * @return void
     */
    public function test_product_description_stored_xss_vulnerability()
    {
        // Create an admin user
        $admin = Admin::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        // Create a product
        $product = Product::create([
            'name' => 'Vulnerable Product',
            'category' => 'laptop',
            'price' => 1000,
            'spec' => 'High Spec',
            'qty' => 10,
            'desc' => 'Initial Description',
            'color' => 'Black',
            'img' => 'test.jpg'
        ]);

        // Malicious payload
        $maliciousDesc = '<script>alert("XSS")</script><p>Safe Content</p>';

        // Act as admin
        $response = $this->actingAs($admin, 'admin')
            ->put(route('products.update', $product->id), [
                'name' => 'Vulnerable Product Updated',
                'category' => 'laptop',
                'price' => 1200,
                'spec' => 'Higher Spec',
                'qty' => 5,
                'desc' => $maliciousDesc,
                'color' => 'Red',
                // img is optional in update
            ]);

        // Assert redirect (success)
        $response->assertRedirect(route('products.index'));

        // Assert database does NOT have the malicious description
        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
            'desc' => $maliciousDesc,
        ]);

        // Assert database has the sanitized description
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'desc' => '<p>Safe Content</p>',
        ]);
    }
}
