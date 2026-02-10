<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Admin;
use App\Models\User;
use App\Models\Shipping;

class ShippingControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_shippings()
    {
        // Create an admin user
        $admin = Admin::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        // Create a regular user for the shipping record
        $user = User::factory()->create();

        // Create a shipping record
        Shipping::create([
            'id_user' => $user->id,
            'name' => 'John Doe',
            'company_name' => 'Acme Corp',
            'address' => '123 Main St',
            'province' => 'Test Province',
            'zip' => '12345',
            'country' => 'Test Country',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'notes' => 'Test notes',
        ]);

        // Act as the admin
        $this->actingAs($admin, 'admin');

        // Perform the search
        $response = $this->get('/admin/shipping/search?search=Acme');

        // Assert the response is successful
        $response->assertStatus(200);
        $response->assertViewIs('admin.shipping.index');
        $response->assertViewHas('shippings');

        // Assert the shipping record is in the view data
        $shippings = $response->viewData('shippings');
        $this->assertNotEmpty($shippings);
        $this->assertEquals('Acme Corp', $shippings->first()->company_name);
    }
}
