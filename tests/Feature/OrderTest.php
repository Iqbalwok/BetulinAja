<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\PartnerProfile;
use App\Models\PartnerSkill;
use App\Models\PricingRule;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_create_order_with_auto_assignment(): void
    {
        $customer = User::factory()->customer()->create();

        $category = ServiceCategory::factory()->create();
        $service = Service::factory()->create([
            'category_id' => $category->id,
            'base_price' => 80000,
        ]);

        PricingRule::factory()->create([
            'service_id' => $service->id,
            'category_id' => $category->id,
            'base_price' => 80000,
            'travel_fee_per_km' => 5000,
            'difficulty_fee_medium' => 20000,
            'platform_commission_rate' => 15.00,
        ]);

        // Create eligible verified online technician near customer
        $partner = User::factory()->partner()->create();
        $profile = PartnerProfile::factory()->create([
            'user_id' => $partner->id,
            'verification_status' => 'verified',
            'availability_status' => 'online',
            'current_latitude' => -6.1820,
            'current_longitude' => 106.8220,
        ]);

        PartnerSkill::create([
            'partner_id' => $profile->id,
            'category_id' => $category->id,
            'experience_years' => 5,
        ]);

        $response = $this->actingAs($customer, 'sanctum')->postJson('/api/v1/orders', [
            'service_id' => $service->id,
            'problem_description' => 'Kerusakan pada unit AC tidak dingin sama sekali.',
            'address_text' => 'Jl. Kebon Sirih No. 10, Jakarta Pusat',
            'latitude' => -6.1830,
            'longitude' => 106.8230,
            'difficulty_level' => 'medium',
            'assignment_method' => 'auto',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'customer_id' => $customer->id,
                    'service_id' => $service->id,
                    'status' => 'assigned',
                    'partner_id' => $partner->id,
                ],
            ]);

        $this->assertDatabaseHas('orders', [
            'customer_id' => $customer->id,
            'partner_id' => $partner->id,
            'status' => 'assigned',
        ]);

        $this->assertDatabaseHas('order_status_histories', [
            'new_status' => 'assigned',
        ]);
    }

    public function test_customer_cannot_view_another_customers_order(): void
    {
        $customerA = User::factory()->customer()->create();
        $customerB = User::factory()->customer()->create();

        $orderA = Order::factory()->create(['customer_id' => $customerA->id]);

        $response = $this->actingAs($customerB, 'sanctum')->getJson("/api/v1/orders/{$orderA->id}");

        $response->assertStatus(403);
    }

    public function test_customer_can_cancel_pending_order(): void
    {
        $customer = User::factory()->customer()->create();
        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($customer, 'sanctum')->postJson("/api/v1/orders/{$order->id}/cancel", [
            'cancellation_reason' => 'Perbaikan sudah tidak dibutuhkan lagi.',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'status' => 'cancelled',
                    'cancellation_reason' => 'Perbaikan sudah tidak dibutuhkan lagi.',
                    'cancelled_by' => 'customer',
                ],
            ]);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'cancelled',
        ]);
    }
}
