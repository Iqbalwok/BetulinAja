<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\PartnerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_review_completed_order(): void
    {
        $customer = User::factory()->customer()->create();
        $partner = User::factory()->partner()->create();
        $profile = PartnerProfile::factory()->create([
            'user_id' => $partner->id,
            'average_rating' => 0,
            'total_reviews' => 0,
        ]);

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'partner_id' => $partner->id,
            'status' => 'completed',
        ]);

        $response = $this->actingAs($customer, 'sanctum')->postJson('/api/v1/reviews', [
            'order_id' => $order->id,
            'rating' => 5,
            'comment' => 'Pengerjaan sangat rapi dan tepat waktu!',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'order_id' => $order->id,
                    'rating' => 5,
                    'comment' => 'Pengerjaan sangat rapi dan tepat waktu!',
                ],
            ]);

        $this->assertDatabaseHas('reviews', [
            'order_id' => $order->id,
            'customer_id' => $customer->id,
            'partner_id' => $partner->id,
            'rating' => 5,
        ]);

        $profile->refresh();
        $this->assertEquals(5.0, (float) $profile->average_rating);
        $this->assertEquals(1, $profile->total_reviews);
    }

    public function test_cannot_review_uncompleted_order(): void
    {
        $customer = User::factory()->customer()->create();
        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => 'in_progress',
        ]);

        $response = $this->actingAs($customer, 'sanctum')->postJson('/api/v1/reviews', [
            'order_id' => $order->id,
            'rating' => 5,
            'comment' => 'Bagus',
        ]);

        $response->assertStatus(403);
    }
}
