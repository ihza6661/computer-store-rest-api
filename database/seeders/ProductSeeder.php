<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ===== DATABASE COMPUTER - REAL PRODUCTS ===== //
        // Official products from Database Computer Pontianak
        // Apple Authorized Reseller | 12+ Bank Partnerships

        // === SMARTPHONES (Category ID: 1) === //
        
        // iPhone Products - Apple Authorized Reseller with TAM Warranty
        Product::create([
            'name' => 'iPhone 15 Pro Max 256GB',
            'description' => 'iPhone 15 Pro Max dengan chipset A17 Pro terbaru dan kamera profesional 48MP. Garansi Resmi TAM 1 Tahun. Cicilan 0% tersedia dari 12+ bank partner kami (BCA, Mandiri, BNI, BRI, CIMB, dll).',
            'price' => 24999000,
            'sku' => 'DB-IP15PM-256',
            'image_url' => 'https://images.unsplash.com/photo-1695048133142-1a20484d2569?auto=format&fit=crop&q=80&w=800',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1695048133142-1a20484d2569?auto=format&fit=crop&q=80&w=400',
            'stock' => 2,
            'specifications' => [
                'processor' => 'Apple A17 Pro Chip',
                'gpu' => '6-core GPU',
                'storage' => '256GB',
                'display' => '6.7" Super Retina XDR with ProMotion (120Hz)',
                'camera' => '48MP Main + 12MP Ultra Wide + 12MP Telephoto',
                'warranty' => 'Garansi Resmi TAM 1 Tahun',
                'condition' => 'New',
                'extras' => 'Cicilan 0% dari 12+ bank • Bonus: Tempered Glass + Case',
            ],
            'category_id' => 1,
        ]);

        Product::create([
            'name' => 'iPhone 15 128GB',
            'description' => 'iPhone 15 dengan chipset A16 Bionic dan Dynamic Island. Garansi Resmi TAM 1 Tahun. Cicilan 0% tersedia dari 12+ bank partner. Best seller untuk produktivitas dan gaming.',
            'price' => 11249000,
            'sku' => 'DB-IP15-128',
            'image_url' => 'https://images.unsplash.com/photo-1696446701796-da61225697cc?auto=format&fit=crop&q=80&w=800',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1696446701796-da61225697cc?auto=format&fit=crop&q=80&w=400',
            'stock' => 3,
            'specifications' => [
                'processor' => 'Apple A16 Bionic Chip',
                'gpu' => '5-core GPU',
                'storage' => '128GB',
                'display' => '6.1" Super Retina XDR',
                'camera' => '48MP Main + 12MP Ultra Wide',
                'warranty' => 'Garansi Resmi TAM 1 Tahun',
                'condition' => 'New',
                'extras' => 'Cicilan 0% dari 12+ bank • Bonus: Tempered Glass + Case',
            ],
            'category_id' => 1,
        ]);

        Product::create([
            'name' => 'iPhone 13 128GB',
            'description' => 'iPhone 13 dengan chipset A15 Bionic, pilihan terbaik untuk pelajar dan mahasiswa. Garansi Resmi TAM 1 Tahun. Cicilan 0% tersedia. Value for money terbaik di kelasnya.',
            'price' => 8249000,
            'sku' => 'DB-IP13-128',
            'image_url' => 'https://images.unsplash.com/photo-1632661674596-df8be070a5c5?auto=format&fit=crop&q=80&w=800',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1632661674596-df8be070a5c5?auto=format&fit=crop&q=80&w=400',
            'stock' => 2,
            'specifications' => [
                'processor' => 'Apple A15 Bionic Chip',
                'gpu' => '4-core GPU',
                'storage' => '128GB',
                'display' => '6.1" Super Retina XDR',
                'camera' => '12MP Dual Camera System',
                'warranty' => 'Garansi Resmi TAM 1 Tahun',
                'condition' => 'New',
                'extras' => 'Best seller pelajar & mahasiswa • Cicilan 0% tersedia',
            ],
            'category_id' => 1,
        ]);

        // === LAPTOPS (Category ID: 2) === //
        
        // Gaming & High-Performance Laptops
        Product::create([
            'name' => 'Asus TUF Gaming A15 Ryzen 7 RTX 4060',
            'description' => 'Laptop gaming powerful dengan Ryzen 7 dan RTX 4060 untuk gaming dan content creation. Display 144Hz untuk pengalaman gaming smooth. Garansi toko 3 bulan. Bonus lengkap!',
            'price' => 15100000,
            'sku' => 'DB-ASUS-TUF-A15',
            'image_url' => 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?auto=format&fit=crop&q=80&w=800',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?auto=format&fit=crop&q=80&w=400',
            'stock' => 1,
            'specifications' => [
                'processor' => 'AMD Ryzen 7-7735HS',
                'gpu' => 'NVIDIA GeForce RTX 4060 8GB',
                'ram' => '16GB DDR5',
                'storage' => '512GB NVMe SSD',
                'display' => '15.6" FHD (1920x1080) 144Hz',
                'keyboard' => 'RGB Backlit',
                'warranty' => 'Garansi toko 3 bulan',
                'condition' => 'Used - Excellent',
                'extras' => 'Tas Laptop + Mouse Wireless + Cooling Pad',
            ],
            'category_id' => 2,
        ]);

        Product::create([
            'name' => 'Lenovo IdeaPad Gaming 3 Ryzen 5 RTX 3050',
            'description' => 'Gaming laptop value for money dengan performa solid untuk gaming AAA dan editing. Display 120Hz untuk visual smooth. Perfect untuk gamers pemula. Bonus lengkap!',
            'price' => 9500000,
            'sku' => 'DB-LENOVO-GAMING3',
            'image_url' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?auto=format&fit=crop&q=80&w=800',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?auto=format&fit=crop&q=80&w=400',
            'stock' => 1,
            'specifications' => [
                'processor' => 'AMD Ryzen 5-5600H',
                'gpu' => 'NVIDIA GeForce RTX 3050 4GB',
                'ram' => '8GB DDR4 (upgradeable to 16GB)',
                'storage' => '512GB NVMe SSD',
                'display' => '15.6" FHD (1920x1080) 120Hz',
                'keyboard' => 'Backlit',
                'warranty' => 'Garansi toko 3 bulan',
                'condition' => 'Used - Excellent',
                'extras' => 'Tas Laptop + Mouse Wireless',
            ],
            'category_id' => 2,
        ]);

        // Student & Office Laptops
        Product::create([
            'name' => 'Asus Vivobook 14 Core i5 Gen 11',
            'description' => 'Laptop student & office yang ringan dan powerful. Cocok untuk kuliah online, kerja kantoran, dan multimedia. Display FHD untuk visual jernih. Garansi toko 3 bulan.',
            'price' => 6500000,
            'sku' => 'DB-ASUS-VIVO14-I5',
            'image_url' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?auto=format&fit=crop&q=80&w=800',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?auto=format&fit=crop&q=80&w=400',
            'stock' => 2,
            'specifications' => [
                'processor' => 'Intel Core i5-1135G7 (Gen 11)',
                'gpu' => 'Intel Iris Xe Graphics',
                'ram' => '8GB DDR4',
                'storage' => '512GB NVMe SSD',
                'display' => '14" FHD (1920x1080)',
                'weight' => '1.6kg (Ringan & Portable)',
                'warranty' => 'Garansi toko 3 bulan',
                'condition' => 'Used - Very Good',
                'extras' => 'Tas Laptop + Mouse Wireless',
            ],
            'category_id' => 2,
        ]);

        Product::create([
            'name' => 'HP 14s Core i3 Gen 10',
            'description' => 'Laptop entry-level dengan performa reliable untuk tugas sehari-hari. Perfect untuk pelajar SMA/mahasiswa. Design slim dan elegan. Harga terjangkau dengan kualitas terjamin.',
            'price' => 4500000,
            'sku' => 'DB-HP-14S-I3',
            'image_url' => 'https://images.unsplash.com/photo-1484788984921-03950022c9ef?auto=format&fit=crop&q=80&w=800',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1484788984921-03950022c9ef?auto=format&fit=crop&q=80&w=400',
            'stock' => 2,
            'specifications' => [
                'processor' => 'Intel Core i3-1005G1 (Gen 10)',
                'gpu' => 'Intel UHD Graphics',
                'ram' => '4GB DDR4 (upgradeable to 16GB)',
                'storage' => '256GB SSD',
                'display' => '14" HD (1366x768)',
                'weight' => '1.5kg',
                'warranty' => 'Garansi toko 3 bulan',
                'condition' => 'Used - Good',
                'extras' => 'Tas Laptop + Mouse',
            ],
            'category_id' => 2,
        ]);

        Product::create([
            'name' => 'Lenovo ThinkPad L14 Core i5 Gen 10',
            'description' => 'Business laptop profesional dengan build quality premium. Keyboard ThinkPad legendaris untuk produktivitas tinggi. Cocok untuk profesional dan pengusaha.',
            'price' => 7200000,
            'sku' => 'DB-LENOVO-THINKPAD-L14',
            'image_url' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?auto=format&fit=crop&q=80&w=800',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?auto=format&fit=crop&q=80&w=400',
            'stock' => 1,
            'specifications' => [
                'processor' => 'Intel Core i5-10210U (Gen 10)',
                'gpu' => 'Intel UHD Graphics',
                'ram' => '8GB DDR4',
                'storage' => '256GB NVMe SSD',
                'display' => '14" FHD (1920x1080) IPS',
                'features' => 'Fingerprint Reader • Webcam Privacy Shutter',
                'warranty' => 'Garansi toko 3 bulan',
                'condition' => 'Used - Excellent',
                'extras' => 'Tas Laptop + Mouse Wireless + Original Charger',
            ],
            'category_id' => 2,
        ]);
    }
}
