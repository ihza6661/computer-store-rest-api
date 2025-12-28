<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Updates all category names, slugs, and descriptions to Indonesian.
     */
    public function up(): void
    {
        $categories = [
            [
                'old_slug' => 'smartphones',
                'name' => 'Smartphone',
                'slug' => 'smartphone',
                'description' => 'Smartphone terbaru dengan teknologi canggih'
            ],
            [
                'old_slug' => 'laptops',
                'name' => 'Laptop',
                'slug' => 'laptop',
                'description' => 'Laptop untuk kebutuhan kerja dan gaming'
            ],
            [
                'old_slug' => 'desktop-computers',
                'name' => 'Komputer Desktop',
                'slug' => 'komputer-desktop',
                'description' => 'PC desktop untuk berbagai kebutuhan'
            ],
            [
                'old_slug' => 'monitors-displays',
                'name' => 'Monitor & Display',
                'slug' => 'monitor-display',
                'description' => 'Monitor berkualitas tinggi untuk produktivitas'
            ],
            [
                'old_slug' => 'computer-components',
                'name' => 'Komponen Komputer',
                'slug' => 'komponen-komputer',
                'description' => 'Komponen hardware untuk upgrade PC'
            ],
            [
                'old_slug' => 'keyboards-mice',
                'name' => 'Keyboard & Mouse',
                'slug' => 'keyboard-mouse',
                'description' => 'Peripheral input untuk komputer'
            ],
            [
                'old_slug' => 'audio-headsets',
                'name' => 'Audio & Headset',
                'slug' => 'audio-headset',
                'description' => 'Perangkat audio dan headset gaming'
            ],
            [
                'old_slug' => 'networking-equipment',
                'name' => 'Perangkat Jaringan',
                'slug' => 'perangkat-jaringan',
                'description' => 'Router, switch, dan perangkat networking'
            ],
            [
                'old_slug' => 'storage-solutions',
                'name' => 'Penyimpanan Data',
                'slug' => 'penyimpanan-data',
                'description' => 'SSD, HDD, dan solusi penyimpanan lainnya'
            ],
            [
                'old_slug' => 'software-licenses',
                'name' => 'Software & Lisensi',
                'slug' => 'software-lisensi',
                'description' => 'Lisensi software original dan aplikasi'
            ],
            [
                'old_slug' => 'gaming-accessories',
                'name' => 'Aksesori Gaming',
                'slug' => 'aksesori-gaming',
                'description' => 'Aksesoris untuk pengalaman gaming terbaik'
            ]
        ];

        foreach ($categories as $category) {
            DB::table('categories')
                ->where('slug', $category['old_slug'])
                ->update([
                    'name' => $category['name'],
                    'slug' => $category['slug'],
                    'description' => $category['description'],
                    'updated_at' => now()
                ]);
        }
    }

    /**
     * Reverse the migrations.
     * Reverts categories back to English.
     */
    public function down(): void
    {
        $categories = [
            [
                'slug' => 'smartphone',
                'name' => 'Smartphones',
                'old_slug' => 'smartphones',
                'description' => 'Latest smartphones with cutting-edge technology'
            ],
            [
                'slug' => 'laptop',
                'name' => 'Laptops',
                'old_slug' => 'laptops',
                'description' => 'Laptops for work and gaming needs'
            ],
            [
                'slug' => 'komputer-desktop',
                'name' => 'Desktop Computers',
                'old_slug' => 'desktop-computers',
                'description' => 'Desktop PCs for various computing needs'
            ],
            [
                'slug' => 'monitor-display',
                'name' => 'Monitors & Displays',
                'old_slug' => 'monitors-displays',
                'description' => 'High-quality monitors for productivity'
            ],
            [
                'slug' => 'komponen-komputer',
                'name' => 'Computer Components',
                'old_slug' => 'computer-components',
                'description' => 'Hardware components for PC upgrades'
            ],
            [
                'slug' => 'keyboard-mouse',
                'name' => 'Keyboards & Mice',
                'old_slug' => 'keyboards-mice',
                'description' => 'Input peripherals for computers'
            ],
            [
                'slug' => 'audio-headset',
                'name' => 'Audio & Headsets',
                'old_slug' => 'audio-headsets',
                'description' => 'Audio devices and gaming headsets'
            ],
            [
                'slug' => 'perangkat-jaringan',
                'name' => 'Networking Equipment',
                'old_slug' => 'networking-equipment',
                'description' => 'Routers, switches, and networking devices'
            ],
            [
                'slug' => 'penyimpanan-data',
                'name' => 'Storage Solutions',
                'old_slug' => 'storage-solutions',
                'description' => 'SSDs, HDDs, and other storage solutions'
            ],
            [
                'slug' => 'software-lisensi',
                'name' => 'Software & Licenses',
                'old_slug' => 'software-licenses',
                'description' => 'Original software licenses and applications'
            ],
            [
                'slug' => 'aksesori-gaming',
                'name' => 'Gaming Accessories',
                'old_slug' => 'gaming-accessories',
                'description' => 'Accessories for the best gaming experience'
            ]
        ];

        foreach ($categories as $category) {
            DB::table('categories')
                ->where('slug', $category['slug'])
                ->update([
                    'name' => $category['name'],
                    'slug' => $category['old_slug'],
                    'description' => $category['description'],
                    'updated_at' => now()
                ]);
        }
    }
};
