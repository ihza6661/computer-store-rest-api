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
        // === LAPTOPS === //
        
        // Gaming Laptops
        Product::create([
            'name' => 'Asus TUF Gaming A15 FA507NV',
            'description' => 'Powerful gaming laptop with Ryzen 7 and RTX 4060. Features 144Hz FHD display for smooth gaming. Includes official warranty until December 2025. Bonus: Bag, Wireless Mouse, and Original Office License.',
            'price' => 15100000,
            'sku' => 'ASUS-TUF-A15-FA507NV',
            'image_url' => 'https://res.cloudinary.com/drcy0kzdm/image/upload/v1766311990/r-tech-products/zvuoi8p9tulelucbjpi8.jpg',
            'image_thumbnail_url' => 'https://res.cloudinary.com/drcy0kzdm/image/upload/v1766311990/r-tech-products/zvuoi8p9tulelucbjpi8.jpg',
            'stock' => 1,
            'specifications' => [
                'processor' => 'AMD Ryzen 7-7735HS',
                'gpu' => 'NVIDIA GeForce RTX 4060 8GB',
                'ram' => '16GB',
                'storage' => '512GB SSD',
                'display' => '15.6" FHD (1920x1080) 144Hz',
                'keyboard' => 'Backlit',
                'warranty' => 'Official warranty until December 2025',
                'condition' => 'Used',
                'extras' => 'Bag, Wireless Mouse, Original Office License',
                'original_price' => '19970000',
            ],
            'category_id' => 1,
        ]);

        Product::create([
            'name' => 'Asus ROG Strix G513QM',
            'description' => 'High-performance gaming laptop with AMD Ryzen 9 and RTX 3060. 144Hz FHD display for excellent gaming experience. Quality controlled with 3-month warranty. Bonus: Bag, Wireless Mouse, and Original Office License.',
            'price' => 14300000,
            'sku' => 'ASUS-ROG-G513QM',
            'image_url' => 'https://res.cloudinary.com/drcy0kzdm/image/upload/v1766311994/r-tech-products/scb7kobsdwfr0r4es389.jpg',
            'image_thumbnail_url' => 'https://res.cloudinary.com/drcy0kzdm/image/upload/v1766311994/r-tech-products/scb7kobsdwfr0r4es389.jpg',
            'stock' => 0,
            'specifications' => [
                'processor' => 'AMD Ryzen 9-5900HX',
                'gpu' => 'NVIDIA GeForce RTX 3060 6GB',
                'ram' => '16GB',
                'storage' => '1TB SSD',
                'display' => '15.6" FHD (1920x1080) 144Hz',
                'keyboard' => 'Backlit',
                'warranty' => '3 months from store',
                'condition' => 'Used',
                'extras' => 'Bag, Wireless Mouse, Original Office License',
            ],
            'category_id' => 1,
        ]);

        // Budget & Student Laptops
        Product::create([
            'name' => 'Acer Aspire A314-23M',
            'description' => 'Reliable laptop with AMD Ryzen 5 processor for everyday tasks. Quality controlled with 3-month store warranty. Bonus: Bag and Wireless Mouse.',
            'price' => 5900000,
            'sku' => 'ACER-ASP-A314-23M',
            'image_url' => 'https://res.cloudinary.com/drcy0kzdm/image/upload/v1766311997/r-tech-products/ltnhslfg95oeyve5zeot.jpg',
            'image_thumbnail_url' => 'https://res.cloudinary.com/drcy0kzdm/image/upload/v1766311997/r-tech-products/ltnhslfg95oeyve5zeot.jpg',
            'stock' => 0,
            'specifications' => [
                'processor' => 'AMD Ryzen 5-7520U',
                'gpu' => 'AMD Radeon Graphics',
                'ram' => '8GB',
                'storage' => '512GB SSD',
                'display' => '14" FHD (1920x1080)',
                'warranty' => '3 months from store',
                'condition' => 'Used',
                'extras' => 'Bag, Wireless Mouse',
            ],
            'category_id' => 1,
        ]);

        Product::create([
            'name' => 'HP Laptop 14 EM0014',
            'description' => 'Compact 14-inch laptop with AMD Ryzen 3 processor. Backlit keyboard and ample storage. Quality controlled with 3-month warranty. Bonus: Bag and Wireless Mouse.',
            'price' => 5500000,
            'sku' => 'HP-14-EM0014',
            'image_url' => 'https://res.cloudinary.com/drcy0kzdm/image/upload/v1766312004/r-tech-products/jfyludkwm0l7wrko58i9.jpg',
            'image_thumbnail_url' => 'https://res.cloudinary.com/drcy0kzdm/image/upload/v1766312004/r-tech-products/jfyludkwm0l7wrko58i9.jpg',
            'stock' => 1,
            'specifications' => [
                'processor' => 'AMD Ryzen 3-7320U',
                'gpu' => 'AMD Radeon Graphics',
                'ram' => '8GB',
                'storage' => '512GB SSD',
                'display' => '14" FHD (1920x1080)',
                'keyboard' => 'Backlit',
                'warranty' => '3 months from store',
                'condition' => 'Used',
                'extras' => 'Bag, Wireless Mouse',
            ],
            'category_id' => 1,
        ]);

        Product::create([
            'name' => 'Lenovo Ideapad Slim 3i',
            'description' => 'Sleek and portable 14-inch laptop with Intel Core i3. Official warranty until March 2027. Perfect for students and office work. Bonus: Bag and Wireless Mouse.',
            'price' => 5000000,
            'sku' => 'LENOVO-SLIM3I',
            'image_url' => 'https://res.cloudinary.com/drcy0kzdm/image/upload/v1766312013/r-tech-products/vwp1dth0z1ofudnlqgzi.jpg',
            'image_thumbnail_url' => 'https://res.cloudinary.com/drcy0kzdm/image/upload/v1766312013/r-tech-products/vwp1dth0z1ofudnlqgzi.jpg',
            'stock' => 0,
            'specifications' => [
                'processor' => 'Intel Core i3-1215U',
                'gpu' => 'Intel UHD Graphics',
                'ram' => '8GB',
                'storage' => '256GB SSD',
                'display' => '14" FHD (1920x1080)',
                'keyboard' => 'Backlit',
                'warranty' => 'Official warranty until March 2027',
                'condition' => 'Used',
                'extras' => 'Bag, Wireless Mouse',
            ],
            'category_id' => 1,
        ]);

        Product::create([
            'name' => 'Acer Aspire Lite 14',
            'description' => 'Ultra-affordable laptop with Intel N150 processor for basic computing needs. Official warranty until April 2026. Ideal for students. Bonus: Bag and Wireless Mouse.',
            'price' => 4000000,
            'sku' => 'ACER-LITE14',
            'image_url' => 'https://res.cloudinary.com/drcy0kzdm/image/upload/v1766312023/r-tech-products/rsmsztfqreldj9czimmi.jpg',
            'image_thumbnail_url' => 'https://res.cloudinary.com/drcy0kzdm/image/upload/v1766312023/r-tech-products/rsmsztfqreldj9czimmi.jpg',
            'stock' => 1,
            'specifications' => [
                'processor' => 'Intel N150',
                'gpu' => 'Intel Graphics',
                'ram' => '8GB',
                'storage' => '256GB SSD',
                'display' => '14" FHD (1920x1080)',
                'warranty' => 'Official warranty until April 2026',
                'condition' => 'Used',
                'extras' => 'Bag, Wireless Mouse',
            ],
            'category_id' => 1,
        ]);

        Product::create([
            'name' => 'Asus Vivobook E410M',
            'description' => 'Entry-level laptop with Intel Celeron processor. Features numeric keypad for productivity. Quality controlled with 3-month warranty. Bonus: Bag and Wireless Mouse.',
            'price' => 3300000,
            'sku' => 'ASUS-E410M',
            'image_url' => 'https://res.cloudinary.com/drcy0kzdm/image/upload/v1766312028/r-tech-products/hgkdkh4ekt24kxektyv1.jpg',
            'image_thumbnail_url' => 'https://res.cloudinary.com/drcy0kzdm/image/upload/v1766312028/r-tech-products/hgkdkh4ekt24kxektyv1.jpg',
            'stock' => 1,
            'specifications' => [
                'processor' => 'Intel Celeron N4020',
                'gpu' => 'Intel UHD Graphics',
                'ram' => '4GB',
                'storage' => '512GB SSD',
                'display' => '14" HD (1366x768)',
                'features' => 'Numeric Keypad',
                'warranty' => '3 months from store',
                'condition' => 'Used',
                'extras' => 'Bag, Wireless Mouse',
            ],
            'category_id' => 1,
        ]);

        Product::create([
            'name' => 'MacBook Pro M1 2020',
            'description' => 'Premium Apple laptop with M1 chip. Excellent for creative professionals and developers. 13-inch Retina display with True Tone. Quality controlled with 3-month warranty. Bonus: Bag, USB-C Hub, Original Accessories.',
            'price' => 12500000,
            'sku' => 'APPLE-MBP-M1-2020',
            'image_url' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=1000&q=80',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=500&q=60',
            'stock' => 1,
            'specifications' => [
                'processor' => 'Apple M1 Chip (8-core CPU)',
                'gpu' => 'Apple M1 GPU (7-core)',
                'ram' => '8GB Unified Memory',
                'storage' => '256GB SSD',
                'display' => '13.3" Retina (2560x1600)',
                'battery' => 'Up to 17 hours',
                'warranty' => '3 months from store',
                'condition' => 'Used - Excellent',
                'extras' => 'Bag, USB-C Hub, Original Charger',
            ],
            'category_id' => 1,
        ]);

        // === DESKTOP COMPUTERS === //
        // Add desktop products here when available

        // === MONITORS === //
        // Add monitor products here when available

        // === COMPUTER COMPONENTS === //
        // Add component products here when available

        // === KEYBOARDS & MICE === //
        // Add keyboard and mouse products here when available

        // === AUDIO & HEADSETS === //
        // Add audio products here when available

        // === NETWORKING === //
        // Add networking products here when available

        // === STORAGE SOLUTIONS === //
        // Add storage products here when available

        // === SOFTWARE === //
        // Add software products here when available

        // === GAMING ACCESSORIES === //
        // Add gaming accessory products here when available
    }
}
