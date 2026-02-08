<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;
use App\Models\Shipping;

class AdminUserShippingSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_shipping_search_is_scoped_to_user()
    {
        // Create admin
        $admin = Admin::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
        ]);

        // Create user A
        $userA = User::create([
            'name' => 'User A',
            'email' => 'a@example.com',
            'password' => Hash::make('password'),
            'phone' => '1234567890',
            'address' => 'Address A',
        ]);

        // Create user B
        $userB = User::create([
            'name' => 'User B',
            'email' => 'b@example.com',
            'password' => Hash::make('password'),
            'phone' => '0987654321',
            'address' => 'Address B',
        ]);

        // Create shipping for User A
        Shipping::create([
            'id_user' => $userA->id,
            'company_name' => 'Company A',
            'country' => 'Country A',
            'name' => 'User A',
            'email' => 'a@example.com',
            'phone' => '1234567890',
            'address' => 'Address A',
            'province' => 'Province A',
            'zip' => '12345',
        ]);

        // Create shipping for User B (matches search term 'Country B' or 'Company B')
        Shipping::create([
            'id_user' => $userB->id,
            'company_name' => 'Company B',
            'country' => 'Country B',
            'name' => 'User B',
            'email' => 'b@example.com',
            'phone' => '0987654321',
            'address' => 'Address B',
            'province' => 'Province B',
            'zip' => '54321',
        ]);

        // Perform search for 'Company B' while viewing User A's shippings
        // If the code has a leak, it would find User B's shipping because 'Company B' matches the query.
        $response = $this->actingAs($admin, 'admin')
            ->get(route('user.shipping.search', ['id' => $userA->id, 'search' => 'Company B']));

        $response->assertStatus(200);

        // Assert that User B's shipping is NOT present
        $response->assertDontSee('Company B');
        $response->assertDontSee('Country B');

        // Now search for 'Company A' while viewing User A's shippings to ensure search still works
        $responseA = $this->actingAs($admin, 'admin')
            ->get(route('user.shipping.search', ['id' => $userA->id, 'search' => 'Company A']));

        $responseA->assertStatus(200);
        $responseA->assertSee('Company A');
        $responseA->assertSee('Country A');
    }
}
