<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_cannot_access_partner_endpoints(): void
    {
        $customer = User::factory()->customer()->create();

        $response = $this->actingAs($customer, 'sanctum')->getJson('/api/v1/partner/dashboard');

        $response->assertStatus(403);
    }

    public function test_partner_cannot_access_admin_endpoints(): void
    {
        $partner = User::factory()->partner()->create();

        $response = $this->actingAs($partner, 'sanctum')->getJson('/api/v1/admin/dashboard');

        $response->assertStatus(403);
    }

    public function test_admin_can_access_admin_endpoints(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin, 'sanctum')->getJson('/api/v1/admin/dashboard');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }
}
