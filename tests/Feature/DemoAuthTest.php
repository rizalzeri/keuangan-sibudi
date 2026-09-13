<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class DemoAuthTest extends TestCase
{
    /**
     * Test token login from PortalBUMDes Academy.
     */
    public function test_login_with_valid_token_redirects_to_spj(): void
    {
        $token = 'gy1KE8bbFea3fPciGbteGoR7eHaQE1ojSo4GI8Sx';

        $response = $this->get('/login?token=' . $token);

        $response->assertRedirect('/spj');
        $response->assertSessionHas('success');

        $this->assertAuthenticated();
        $user = auth()->user();
        $this->assertTrue((bool)$user->is_demo);
        $this->assertEquals(1, $user->status);
    }

    /**
     * Test login with invalid token redirects to login with error.
     */
    public function test_login_with_invalid_token_fails(): void
    {
        $response = $this->get('/login?token=token_palsu_12345');

        $response->assertRedirect('/login');
        $response->assertSessionHas('error');
    }

    /**
     * Test demo user can access both SPJ and Pembukuan routes without being blocked.
     */
    public function test_demo_user_can_access_all_modules(): void
    {
        $token = 'gy1KE8bbFea3fPciGbteGoR7eHaQE1ojSo4GI8Sx';
        $this->get('/login?token=' . $token);

        $user = auth()->user();
        $this->assertNotNull($user);

        // Test SPJ route
        $spjResponse = $this->actingAs($user)->get('/spj');
        $spjResponse->assertStatus(200);

        // Test Pembukuan route
        $pembukuanResponse = $this->actingAs($user)->get('/');
        $pembukuanResponse->assertStatus(200);
    }

    /**
     * Test demo reset route resets practice session and extends expiry.
     */
    public function test_demo_reset_route(): void
    {
        $token = 'gy1KE8bbFea3fPciGbteGoR7eHaQE1ojSo4GI8Sx';
        $this->get('/login?token=' . $token);
        $user = auth()->user();

        $response = $this->actingAs($user)->post('/demo/reset');
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertTrue(now()->diffInMinutes($user->demo_expires_at) >= 58);
    }
}
