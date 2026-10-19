<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\PartnerProfile;
use App\Models\PartnerSkill;
use App\Models\ServiceCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        $categories = ServiceCategory::pluck('id', 'slug')->toArray();

        // 1. Demo Customer
        $customer = User::firstOrCreate(
            ['email' => 'customer@demo.id'],
            [
                'name' => 'Budi Pratama',
                'phone' => '081234567890',
                'password' => Hash::make('demo123456'),
                'role' => 'customer',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        Address::firstOrCreate(
            ['user_id' => $customer->id, 'label' => 'Rumah'],
            [
                'address' => 'Jl. Menteng Raya No. 45, RT 02/RW 03',
                'landmark' => 'Pagar hitam samping minimarket',
                'city' => 'Jakarta Pusat',
                'district' => 'Menteng',
                'latitude' => -6.1880,
                'longitude' => 106.8340,
                'is_primary' => true,
            ]
        );

        Address::firstOrCreate(
            ['user_id' => $customer->id, 'label' => 'Kantor'],
            [
                'address' => 'Gedung Sentra Mulia Lt. 5, Jl. Rasuna Said Kav. X-6',
                'landmark' => 'Dekat halte Transjakarta',
                'city' => 'Jakarta Selatan',
                'district' => 'Setiabudi',
                'latitude' => -6.2180,
                'longitude' => 106.8300,
                'is_primary' => false,
            ]
        );

        // 2. Demo Partner (Verified & Online - AC & Listrik)
        $partner1 = User::firstOrCreate(
            ['email' => 'partner@demo.id'],
            [
                'name' => 'Agus Santoso',
                'phone' => '081298765432',
                'password' => Hash::make('demo123456'),
                'role' => 'partner',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        $profile1 = PartnerProfile::updateOrCreate(
            ['user_id' => $partner1->id],
            [
                'description' => 'Teknisi spesialis AC dan instalasi listrik rumah tangga berpengalaman lebih dari 8 tahun.',
                'id_card_number' => '3171011508850001',
                'verification_status' => 'verified',
                'verification_note' => 'Dokumen KTP dan sertifikat kompetensi BNSP terverifikasi valid.',
                'availability_status' => 'online',
                'current_latitude' => -6.1820,
                'current_longitude' => 106.8230,
                'average_rating' => 4.90,
                'total_reviews' => 28,
                'completed_jobs' => 42,
                'years_experience' => 8,
                'balance' => 1500000.00,
            ]
        );

        if (isset($categories['service-ac'])) {
            PartnerSkill::updateOrCreate(['partner_id' => $profile1->id, 'category_id' => $categories['service-ac']], ['experience_years' => 8]);
        }
        if (isset($categories['kelistrikan'])) {
            PartnerSkill::updateOrCreate(['partner_id' => $profile1->id, 'category_id' => $categories['kelistrikan']], ['experience_years' => 6]);
        }

        // 3. Partner 2 (Plumbing Specialist)
        $partner2 = User::firstOrCreate(
            ['email' => 'bambang.plumbing@demo.id'],
            [
                'name' => 'Bambang Wijaya',
                'phone' => '081298765433',
                'password' => Hash::make('demo123456'),
                'role' => 'partner',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        $profile2 = PartnerProfile::updateOrCreate(
            ['user_id' => $partner2->id],
            [
                'description' => 'Ahli perpipaan, pompa air, dan saluran mampet. Melayani area Jakarta Pusat dan Selatan.',
                'id_card_number' => '3171021003880002',
                'verification_status' => 'verified',
                'verification_note' => 'KTP dan SKCK terverifikasi.',
                'availability_status' => 'online',
                'current_latitude' => -6.2100,
                'current_longitude' => 106.8150,
                'average_rating' => 4.85,
                'total_reviews' => 19,
                'completed_jobs' => 31,
                'years_experience' => 5,
                'balance' => 950000.00,
            ]
        );

        if (isset($categories['plumbing'])) {
            PartnerSkill::updateOrCreate(['partner_id' => $profile2->id, 'category_id' => $categories['plumbing']], ['experience_years' => 5]);
        }

        // 4. Partner 3 (Pending verification)
        $partner3 = User::firstOrCreate(
            ['email' => 'eko.pending@demo.id'],
            [
                'name' => 'Eko Prasetyo',
                'phone' => '081298765434',
                'password' => Hash::make('demo123456'),
                'role' => 'partner',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        PartnerProfile::updateOrCreate(
            ['user_id' => $partner3->id],
            [
                'description' => 'Teknisi perbaikan elektronik rumah tangga dan kulkas.',
                'id_card_number' => '3171032512920003',
                'verification_status' => 'pending',
                'availability_status' => 'offline',
                'years_experience' => 3,
                'balance' => 0.00,
            ]
        );
    }
}
