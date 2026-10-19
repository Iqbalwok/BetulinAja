<?php

namespace Database\Seeders;

use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class ServiceCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Service AC',
                'slug' => 'service-ac',
                'description' => 'Cuci AC, perbaikan bocor, isi freon, bongkar pasang oleh teknisi spesialis tata udara berlisensi.',
                'icon' => 'bi-snow',
                'sort_order' => 1,
            ],
            [
                'name' => 'Elektronik & TV',
                'slug' => 'elektronik-tv',
                'description' => 'Perbaikan TV LED/LCD, mesin cuci, microwave, dispenser, dan peralatan elektronik rumah tangga lainnya.',
                'icon' => 'bi-tv',
                'sort_order' => 2,
            ],
            [
                'name' => 'Kelistrikan Rumah',
                'slug' => 'kelistrikan',
                'description' => 'Solusi korsleting listrik, perbaikan MCB, pasang instalasi stop kontak, panel listrik & lampu.',
                'icon' => 'bi-lightning-charge',
                'sort_order' => 3,
            ],
            [
                'name' => 'Plumbing & Pipa Air',
                'slug' => 'plumbing',
                'description' => 'Perbaikan pipa bocor, instalasi toren, keran air patah, pompa air mati, hingga saluran mampet.',
                'icon' => 'bi-droplet',
                'sort_order' => 4,
            ],
            [
                'name' => 'Kulkas & Freezer',
                'slug' => 'kulkas-freezer',
                'description' => 'Service kulkas 1 & 2 pintu, freezer box, showcase tidak dingin, kompresor mati, dan ganti karet pintu.',
                'icon' => 'bi-box-seam',
                'sort_order' => 5,
            ],
            [
                'name' => 'Komputer & Laptop',
                'slug' => 'komputer-laptop',
                'description' => 'Install ulang, pembersihan pasta thermal, ganti SSD/RAM, perbaikan laptop mati total atau lemot.',
                'icon' => 'bi-laptop',
                'sort_order' => 6,
            ],
            [
                'name' => 'Perbaikan Rumah',
                'slug' => 'perbaikan-rumah',
                'description' => 'Pengecatan dinding, perbaikan atap bocor, engsel pintu rusak, perakitan perabot furnitur knock-down.',
                'icon' => 'bi-house-gear',
                'sort_order' => 7,
            ],
        ];

        foreach ($categories as $cat) {
            ServiceCategory::updateOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
