<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $categoryMap = ServiceCategory::pluck('id', 'slug')->toArray();

        $services = [
            // AC
            [
                'category_slug' => 'service-ac',
                'name' => 'Cuci AC Split (0.5 - 2 PK)',
                'slug' => 'cuci-ac-split',
                'description' => 'Pembersihan filter, evaporator, dan outdoor unit menggunakan jet cleaner bertekanan tinggi.',
                'base_price' => 75000,
                'estimated_duration_minutes' => 45,
                'sort_order' => 1,
            ],
            [
                'category_slug' => 'service-ac',
                'name' => 'Bongkar Pasang AC',
                'slug' => 'bongkar-pasang-ac',
                'description' => 'Pelepasan unit lama dan instalasi unit baru lengkap dengan perapihan pipa dan vakum.',
                'base_price' => 250000,
                'estimated_duration_minutes' => 120,
                'sort_order' => 2,
            ],
            [
                'category_slug' => 'service-ac',
                'name' => 'Tambah Freon R32 / R410A',
                'slug' => 'tambah-freon-r32-r410a',
                'description' => 'Pengisian freon ramah lingkungan tipe R32 atau R410A untuk AC yang kurang dingin.',
                'base_price' => 150000,
                'estimated_duration_minutes' => 45,
                'sort_order' => 3,
            ],
            [
                'category_slug' => 'service-ac',
                'name' => 'Perbaikan AC Bocor Air',
                'slug' => 'perbaikan-ac-bocor-air',
                'description' => 'Perbaikan saluran pembuangan air kondensasi yang tersumbat lendir atau kotoran.',
                'base_price' => 120000,
                'estimated_duration_minutes' => 60,
                'sort_order' => 4,
            ],

            // Elektronik
            [
                'category_slug' => 'elektronik-tv',
                'name' => 'Service TV LED Mati Total / Layar Gelap',
                'slug' => 'service-tv-led',
                'description' => 'Pengecekan power supply, penggantian backlight LED, atau modul T-Con.',
                'base_price' => 150000,
                'estimated_duration_minutes' => 90,
                'sort_order' => 1,
            ],
            [
                'category_slug' => 'elektronik-tv',
                'name' => 'Service Mesin Cuci 1 & 2 Tabung',
                'slug' => 'service-mesin-cuci',
                'description' => 'Perbaikan tabung tidak berputar, air tidak keluar, getaran kencang, atau dinamo macet.',
                'base_price' => 125000,
                'estimated_duration_minutes' => 90,
                'sort_order' => 2,
            ],

            // Kelistrikan
            [
                'category_slug' => 'kelistrikan',
                'name' => 'Pencarian & Perbaikan Korsleting Listrik',
                'slug' => 'perbaikan-korsleting-listrik',
                'description' => 'Deteksi titik kebocoran arus atau kabel hangus yang menyebabkan MCB turun terus-menerus.',
                'base_price' => 100000,
                'estimated_duration_minutes' => 60,
                'sort_order' => 1,
            ],
            [
                'category_slug' => 'kelistrikan',
                'name' => 'Pasang Titik Stop Kontak & Saklar Lampu',
                'slug' => 'pasang-stop-kontak',
                'description' => 'Pemasangan stop kontak baru atau penambahan jalur saklar lampu rumah.',
                'base_price' => 50000,
                'estimated_duration_minutes' => 40,
                'sort_order' => 2,
            ],

            // Plumbing
            [
                'category_slug' => 'plumbing',
                'name' => 'Perbaikan Pipa Air Bocor',
                'slug' => 'perbaikan-pipa-bocor',
                'description' => 'Penyambungan dan penggantian pipa PVC / PPR bocor pada dinding atau plafon.',
                'base_price' => 90000,
                'estimated_duration_minutes' => 60,
                'sort_order' => 1,
            ],
            [
                'category_slug' => 'plumbing',
                'name' => 'Pelancaran Saluran Air Mampet',
                'slug' => 'saluran-air-mampet',
                'description' => 'Pembersihan pipa pembuangan wastafel, floor drain, atau toilet yang tersumbat.',
                'base_price' => 150000,
                'estimated_duration_minutes' => 60,
                'sort_order' => 2,
            ],

            // Kulkas
            [
                'category_slug' => 'kulkas-freezer',
                'name' => 'Service Kulkas Tidak Dingin',
                'slug' => 'service-kulkas-tidak-dingin',
                'description' => 'Pengecekan relay, overload, defros termostat, fan evaporator, dan kompresor.',
                'base_price' => 130000,
                'estimated_duration_minutes' => 75,
                'sort_order' => 1,
            ],

            // Komputer
            [
                'category_slug' => 'komputer-laptop',
                'name' => 'Install Ulang & Optimalisasi Laptop',
                'slug' => 'install-ulang-laptop',
                'description' => 'Instalasi sistem operasi bersih, driver resmi, aplikasi standar, dan pembersihan malware.',
                'base_price' => 100000,
                'estimated_duration_minutes' => 90,
                'sort_order' => 1,
            ],

            // Perbaikan Rumah
            [
                'category_slug' => 'perbaikan-rumah',
                'name' => 'Perakitan Perabot & Furnitur Knock-Down',
                'slug' => 'perakitan-furnitur',
                'description' => 'Perakitan lemari, meja kerja, rak buku, atau tempat tidur knock-down IKEA / Informa.',
                'base_price' => 120000,
                'estimated_duration_minutes' => 90,
                'sort_order' => 1,
            ],
        ];

        foreach ($services as $srv) {
            $catId = $categoryMap[$srv['category_slug']] ?? null;
            if ($catId) {
                Service::updateOrCreate(
                    ['slug' => $srv['slug']],
                    [
                        'category_id' => $catId,
                        'name' => $srv['name'],
                        'description' => $srv['description'],
                        'base_price' => $srv['base_price'],
                        'estimated_duration_minutes' => $srv['estimated_duration_minutes'],
                        'sort_order' => $srv['sort_order'],
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
