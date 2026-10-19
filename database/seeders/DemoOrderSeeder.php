<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\PartnerEarning;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Service;
use App\Models\User;
use App\Services\OrderNumberService;
use Illuminate\Database\Seeder;

class DemoOrderSeeder extends Seeder
{
    public function run(): void
    {
        $customer = User::where('email', 'customer@demo.id')->first();
        $partner = User::where('email', 'partner@demo.id')->first();
        $acService = Service::where('slug', 'cuci-ac-split')->first();
        $elecService = Service::where('slug', 'perbaikan-korsleting-listrik')->first();
        $plumbingService = Service::where('slug', 'perbaikan-pipa-bocor')->first();

        if (!$customer || !$partner || !$acService) {
            return;
        }

        // 1. Completed Order
        $order1 = Order::firstOrCreate(
            ['order_number' => 'BA202610180001'],
            [
                'customer_id' => $customer->id,
                'partner_id' => $partner->id,
                'service_id' => $acService->id,
                'problem_description' => 'AC kamar utama hembusan anginnya kecil dan agak bau apek, butuh dicuci.',
                'address_text' => 'Jl. Menteng Raya No. 45, RT 02/RW 03, Jakarta Pusat',
                'landmark' => 'Pagar hitam samping minimarket',
                'latitude' => -6.1880,
                'longitude' => 106.8340,
                'scheduled_at' => now()->subDay(),
                'assignment_method' => 'auto',
                'status' => 'completed',
                'base_price' => 75000,
                'distance_km' => 2.3,
                'travel_fee' => 11500,
                'difficulty_level' => 'light',
                'difficulty_fee' => 0,
                'additional_fee' => 0,
                'total_estimate' => 86500,
                'final_price' => 86500,
                'platform_commission' => 12975,
                'partner_earning' => 73525,
                'accepted_at' => now()->subDay()->addMinutes(10),
                'started_at' => now()->subDay()->addMinutes(45),
                'completed_at' => now()->subDay()->addHours(2),
            ]
        );

        Payment::updateOrCreate(
            ['order_id' => $order1->id],
            [
                'payment_method' => 'cash',
                'amount' => 86500,
                'status' => 'paid',
                'paid_at' => now()->subDay()->addHours(2),
            ]
        );

        Review::updateOrCreate(
            ['order_id' => $order1->id],
            [
                'customer_id' => $customer->id,
                'partner_id' => $partner->id,
                'rating' => 5,
                'comment' => 'Pak Agus sangat teliti, hasil cuci AC bersih dan tidak ada air menetes di lantai.',
                'is_visible' => true,
            ]
        );

        PartnerEarning::updateOrCreate(
            ['order_id' => $order1->id],
            [
                'partner_id' => $partner->id,
                'type' => 'earning',
                'amount' => 86500,
                'platform_commission' => 12975,
                'net_amount' => 73525,
                'description' => "Penghasilan dari pesanan #{$order1->order_number}",
                'status' => 'settled',
            ]
        );

        // 2. Active In-Progress Order
        if ($elecService) {
            $order2 = Order::firstOrCreate(
                ['order_number' => 'BA202610190001'],
                [
                    'customer_id' => $customer->id,
                    'partner_id' => $partner->id,
                    'service_id' => $elecService->id,
                    'problem_description' => 'MCB listrik lantai 2 turun tiap kali menyalakan pompa air atau kulkas.',
                    'address_text' => 'Jl. Menteng Raya No. 45, Jakarta Pusat',
                    'latitude' => -6.1880,
                    'longitude' => 106.8340,
                    'scheduled_at' => now(),
                    'assignment_method' => 'manual',
                    'status' => 'in_progress',
                    'base_price' => 100000,
                    'distance_km' => 2.3,
                    'travel_fee' => 11500,
                    'difficulty_level' => 'medium',
                    'difficulty_fee' => 25000,
                    'additional_fee' => 0,
                    'total_estimate' => 136500,
                    'final_price' => 136500,
                    'platform_commission' => 20475,
                    'partner_earning' => 116025,
                    'accepted_at' => now()->subMinutes(50),
                    'started_at' => now()->subMinutes(15),
                ]
            );

            OrderStatusHistory::firstOrCreate(
                ['order_id' => $order2->id, 'new_status' => 'in_progress'],
                [
                    'changed_by' => $partner->id,
                    'old_status' => 'arrived',
                    'note' => 'Sedang memeriksa jalur kabel pada instalasi pompa.',
                ]
            );
        }

        // 3. Available Order Searching for Technician
        if ($plumbingService) {
            Order::firstOrCreate(
                ['order_number' => 'BA202610190002'],
                [
                    'customer_id' => $customer->id,
                    'partner_id' => null,
                    'service_id' => $plumbingService->id,
                    'problem_description' => 'Pipa sambungan di bawah bak cuci piring bocor rembes ke lemari dapur.',
                    'address_text' => 'Jl. Menteng Raya No. 45, Jakarta Pusat',
                    'latitude' => -6.1880,
                    'longitude' => 106.8340,
                    'scheduled_at' => now()->addHours(3),
                    'assignment_method' => 'auto',
                    'status' => 'searching_technician',
                    'base_price' => 90000,
                    'distance_km' => 3.5,
                    'travel_fee' => 17500,
                    'difficulty_level' => 'medium',
                    'difficulty_fee' => 25000,
                    'additional_fee' => 0,
                    'total_estimate' => 132500,
                    'final_price' => 132500,
                    'platform_commission' => 19875,
                    'partner_earning' => 112625,
                ]
            );
        }
    }
}
