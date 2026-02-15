<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginLogoutTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_logout()
    {
        // 1. Create a user
        $user = User::factory()->create();

        // 2. Act as the user
        $this->actingAs($user);

        // 3. Verify user is authenticated
        $this->assertAuthenticated();

        // 4. Call the logout route
        $response = $this->get(route('user.logout'));

        // 5. Assert redirection to '/'
        $response->assertRedirect('/');

        // 6. Assert user is no longer authenticated
        $this->assertGuest();
    }
}
