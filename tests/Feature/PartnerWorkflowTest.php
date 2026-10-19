<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\PartnerProfile;
use App\Models\Payment;
use App\Models\User;
use App\Services\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PartnerWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_partner_can_accept_assigned_order(): void
    {
        $partner = User::factory()->partner()->create();
        PartnerProfile::factory()->create(['user_id' => $partner->id]);

        $order = Order::factory()->create([
            'partner_id' => $partner->id,
            'status' => 'assigned',
        ]);

        $response = $this->actingAs($partner, 'sanctum')->postJson("/api/v1/partner/orders/{$order->id}/accept");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'status' => 'accepted',
                ],
            ]);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'accepted',
        ]);
    }

    public function test_partner_can_advance_order_lifecycle_to_completed(): void
    {
        $partner = User::factory()->partner()->create();
        $profile = PartnerProfile::factory()->create([
            'user_id' => $partner->id,
            'balance' => 0,
            'completed_jobs' => 0,
        ]);

        $order = Order::factory()->create([
            'partner_id' => $partner->id,
            'status' => 'accepted',
            'final_price' => 100000,
            'platform_commission' => 15000,
            'partner_earning' => 85000,
        ]);

        PaymentService::createPayment($order);

        // accepted -> on_the_way
        $this->actingAs($partner, 'sanctum')
            ->patchJson("/api/v1/partner/orders/{$order->id}/status", ['status' => 'on_the_way'])
            ->assertStatus(200)
            ->assertJsonPath('data.status', 'on_the_way');

        // on_the_way -> arrived
        $this->actingAs($partner, 'sanctum')
            ->patchJson("/api/v1/partner/orders/{$order->id}/status", ['status' => 'arrived'])
            ->assertStatus(200)
            ->assertJsonPath('data.status', 'arrived');

        // arrived -> in_progress
        $this->actingAs($partner, 'sanctum')
            ->patchJson("/api/v1/partner/orders/{$order->id}/status", ['status' => 'in_progress'])
            ->assertStatus(200)
            ->assertJsonPath('data.status', 'in_progress');

        // in_progress -> completed
        $this->actingAs($partner, 'sanctum')
            ->patchJson("/api/v1/partner/orders/{$order->id}/status", ['status' => 'completed'])
            ->assertStatus(200)
            ->assertJsonPath('data.status', 'completed');

        // Verify payment marked as paid
        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'status' => 'paid',
        ]);

        // Verify partner balance credited
        $profile->refresh();
        $this->assertEquals(85000, (float) $profile->balance);
        $this->assertEquals(1, $profile->completed_jobs);
    }

    public function test_invalid_status_transition_is_rejected(): void
    {
        $partner = User::factory()->partner()->create();
        PartnerProfile::factory()->create(['user_id' => $partner->id]);

        $order = Order::factory()->create([
            'partner_id' => $partner->id,
            'status' => 'assigned',
        ]);

        // Cannot jump from assigned to completed directly
        $response = $this->actingAs($partner, 'sanctum')
            ->patchJson("/api/v1/partner/orders/{$order->id}/status", ['status' => 'completed']);

        $response->assertStatus(422)
            ->assertJson(['success' => false]);
    }
}
