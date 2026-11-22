<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\PartnerProfile;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EndToEndBookingLifecycleTest extends TestCase
{
    use RefreshDatabase;

    public function test_full_booking_lifecycle_from_customer_to_admin_to_partner(): void
    {
        // 1. Setup Master Data
        $category = ServiceCategory::create([
            'name' => 'Service AC',
            'slug' => 'service-ac',
            'icon' => 'bi-snow',
            'is_active' => true,
        ]);

        $service = Service::create([
            'category_id' => $category->id,
            'name' => 'Cuci AC Standar',
            'slug' => 'cuci-ac-standar',
            'base_price' => 100000,
            'duration_minutes' => 60,
            'is_active' => true,
        ]);

        // 2. Setup Users
        $customer = User::factory()->create([
            'name' => 'Budi Pratama Customer',
            'email' => 'customer@test.id',
            'role' => 'customer',
            'status' => 'active',
        ]);

        $partnerUser = User::factory()->create([
            'name' => 'Agus Teknisi Partner',
            'email' => 'partner@test.id',
            'role' => 'partner',
            'status' => 'active',
        ]);

        $partnerProfile = PartnerProfile::create([
            'user_id' => $partnerUser->id,
            'description' => 'Teknisi AC',
            'id_card_number' => '3171011508850001',
            'verification_status' => 'verified',
            'availability_status' => 'online',
            'current_latitude' => -6.1820,
            'current_longitude' => 106.8230,
            'average_rating' => 4.9,
            'completed_jobs' => 10,
            'years_experience' => 5,
            'balance' => 0,
        ]);

        $admin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@test.id',
            'role' => 'admin',
            'status' => 'active',
        ]);

        // 3. Customer Creates Order with Auto Assignment
        $orderPayload = [
            'service_id' => $service->id,
            'problem_description' => 'AC kamar tidur bocor dan menetes air deras ke lantai',
            'address_text' => 'Jl. Sudirman Kav. 21, Jakarta Pusat',
            'landmark' => 'Lantai 5 Apartemen',
            'latitude' => -6.2180,
            'longitude' => 106.8300,
            'difficulty_level' => 'medium',
            'assignment_method' => 'auto',
        ];

        $createResponse = $this->actingAs($customer, 'sanctum')
            ->postJson('/api/v1/orders', $orderPayload);

        $createResponse->assertStatus(201)
            ->assertJsonPath('success', true);

        $orderId = $createResponse->json('data.id');
        $this->assertNotNull($orderId);

        // 4. Admin Views Order List and Assigns Partner
        $adminOrdersResponse = $this->actingAs($admin, 'sanctum')
            ->getJson('/api/v1/admin/orders');

        $adminOrdersResponse->assertStatus(200)
            ->assertJsonPath('success', true);

        $assignResponse = $this->actingAs($admin, 'sanctum')
            ->postJson("/api/v1/admin/orders/{$orderId}/assign", [
                'partner_id' => $partnerUser->id,
            ]);

        $assignResponse->assertStatus(200);

        $order = Order::find($orderId);
        $this->assertEquals($partnerUser->id, $order->partner_id);
        $this->assertEquals('assigned', $order->status);

        // 5. Partner Views and Accepts Order
        $partnerOrdersResponse = $this->actingAs($partnerUser, 'sanctum')
            ->getJson('/api/v1/partner/orders');

        $partnerOrdersResponse->assertStatus(200);

        $acceptResponse = $this->actingAs($partnerUser, 'sanctum')
            ->postJson("/api/v1/partner/orders/{$orderId}/accept");

        $acceptResponse->assertStatus(200);
        $this->assertEquals('accepted', Order::find($orderId)->status);

        // 6. Partner Advances Lifecycle: on_the_way -> arrived -> in_progress -> completed
        $this->actingAs($partnerUser, 'sanctum')
            ->patchJson("/api/v1/partner/orders/{$orderId}/status", ['status' => 'on_the_way'])
            ->assertStatus(200);
        $this->assertEquals('on_the_way', Order::find($orderId)->status);

        $this->actingAs($partnerUser, 'sanctum')
            ->patchJson("/api/v1/partner/orders/{$orderId}/status", ['status' => 'arrived'])
            ->assertStatus(200);
        $this->assertEquals('arrived', Order::find($orderId)->status);

        $this->actingAs($partnerUser, 'sanctum')
            ->patchJson("/api/v1/partner/orders/{$orderId}/status", ['status' => 'in_progress'])
            ->assertStatus(200);
        $this->assertEquals('in_progress', Order::find($orderId)->status);

        $this->actingAs($partnerUser, 'sanctum')
            ->patchJson("/api/v1/partner/orders/{$orderId}/status", [
                'status' => 'completed',
                'final_price' => 150000,
            ])
            ->assertStatus(200);

        $completedOrder = Order::find($orderId);
        $this->assertEquals('completed', $completedOrder->status);
        $this->assertEquals(150000, (float) $completedOrder->final_price);
        $this->assertNotNull($completedOrder->completed_at);

        // 7. Customer Checks Tracking & Order History
        $trackingResponse = $this->actingAs($customer, 'sanctum')
            ->getJson("/api/v1/orders/{$orderId}");

        $trackingResponse->assertStatus(200)
            ->assertJsonPath('data.status', 'completed')
            ->assertJsonPath('data.partner.name', 'Agus Teknisi Partner');
    }
}
