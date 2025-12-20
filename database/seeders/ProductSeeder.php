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
        // Laptop Products
        Product::create([
            'name' => 'Dell XPS 13 Plus',
            'description' => 'Ultra-thin and lightweight laptop with stunning InfinityEdge display. Perfect for professionals and students.',
            'price' => 1299.99,
            'sku' => 'DELL-XPS13-2024',
            'image_url' => 'https://images.unsplash.com/photo-1593642632823-8f785ba67e45?w=500&h=500&fit=crop',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1593642632823-8f785ba67e45?w=200&h=200&fit=crop',
            'stock' => 8,
            'specifications' => json_encode([
                'processor' => 'Intel Core i7-1360P',
                'ram' => '16GB LPDDR5',
                'storage' => '512GB SSD',
                'display' => '13.4" FHD+ (1920x1200)',
                'battery' => '52Wh (up to 13 hours)',
                'weight' => '2.79 lbs',
            ]),
            'category_id' => 1, // Laptops
        ]);

        Product::create([
            'name' => 'MacBook Pro 14" M3 Pro',
            'description' => 'Powerful laptop with Apple M3 Pro chip. Ideal for creative professionals and developers.',
            'price' => 1999.99,
            'sku' => 'APPLE-MBP14-M3',
            'image_url' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=500&h=500&fit=crop',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=200&h=200&fit=crop',
            'stock' => 5,
            'specifications' => json_encode([
                'processor' => 'Apple M3 Pro (12-core)',
                'ram' => '18GB Unified Memory',
                'storage' => '512GB SSD',
                'display' => '14.2" Liquid Retina XDR',
                'battery' => 'Up to 17 hours',
                'weight' => '3.5 lbs',
            ]),
            'category_id' => 1, // Laptops
        ]);

        Product::create([
            'name' => 'ASUS VivoBook 15',
            'description' => 'Budget-friendly laptop with excellent performance. Great for everyday computing and light gaming.',
            'price' => 599.99,
            'sku' => 'ASUS-VB15-2024',
            'image_url' => 'https://images.unsplash.com/photo-1588872657840-218e412ee62e?w=500&h=500&fit=crop',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1588872657840-218e412ee62e?w=200&h=200&fit=crop',
            'stock' => 15,
            'specifications' => json_encode([
                'processor' => 'AMD Ryzen 5 5500U',
                'ram' => '8GB DDR4',
                'storage' => '256GB SSD',
                'display' => '15.6" FHD (1920x1080)',
                'battery' => '42Wh',
                'weight' => '3.74 lbs',
            ]),
            'category_id' => 1, // Laptops
        ]);

        // Desktop Products
        Product::create([
            'name' => 'Apple Mac Studio',
            'description' => 'Compact powerhouse for creative professionals. Features M2 Max chip with exceptional performance.',
            'price' => 1999.00,
            'sku' => 'APPLE-MACSTUDIO-M2',
            'image_url' => 'https://images.unsplash.com/photo-1587829191301-b35b7c6dce5f?w=500&h=500&fit=crop',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1587829191301-b35b7c6dce5f?w=200&h=200&fit=crop',
            'stock' => 3,
            'specifications' => json_encode([
                'processor' => 'Apple M2 Max (10-core)',
                'ram' => '32GB Unified Memory',
                'storage' => '512GB SSD',
                'ports' => 'Thunderbolt, USB-C, HDMI',
                'weight' => '3.3 lbs',
                'warranty' => '1 Year Apple Care',
            ]),
            'category_id' => 2, // Desktops
        ]);

        Product::create([
            'name' => 'CORSAIR Obsidian Series 500D RGB SE',
            'description' => 'Premium gaming desktop case with tempered glass. Perfect for high-end gaming builds.',
            'price' => 179.99,
            'sku' => 'CORSAIR-500D-RGB',
            'image_url' => 'https://images.unsplash.com/photo-1587148882107-e93b67b0c5ab?w=500&h=500&fit=crop',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1587148882107-e93b67b0c5ab?w=200&h=200&fit=crop',
            'stock' => 12,
            'specifications' => json_encode([
                'form_factor' => 'Mid Tower',
                'material' => 'Steel + Tempered Glass',
                'front_panel_io' => '2x USB 3.0, 1x USB-C',
                'motherboard_support' => 'Up to E-ATX',
                'max_gpu_length' => '405mm',
                'max_cpu_cooler_height' => '160mm',
            ]),
            'category_id' => 2, // Desktops
        ]);

        Product::create([
            'name' => 'Intel Core i9-13900KS',
            'description' => 'High-end desktop CPU with 24 cores. Ideal for gaming, content creation, and streaming.',
            'price' => 699.00,
            'sku' => 'INTEL-I9-13900KS',
            'image_url' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=500&h=500&fit=crop',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=200&h=200&fit=crop',
            'stock' => 7,
            'specifications' => json_encode([
                'cores' => '24 (8P + 16E)',
                'threads' => '32',
                'base_frequency' => '3.2 GHz',
                'max_turbo' => '6.2 GHz',
                'tdp' => '150W',
                'socket' => 'LGA 1700',
            ]),
            'category_id' => 2, // Desktops
        ]);

        // Monitor Products
        Product::create([
            'name' => 'LG UltraWide 38" Curved Gaming Monitor',
            'description' => 'Immersive 38-inch curved ultrawide display. Perfect for gaming, video editing, and multitasking.',
            'price' => 1399.99,
            'sku' => 'LG-38UW-CURVED',
            'image_url' => 'https://images.unsplash.com/photo-1593642532400-2682a8a4f2a8?w=500&h=500&fit=crop',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1593642532400-2682a8a4f2a8?w=200&h=200&fit=crop',
            'stock' => 4,
            'specifications' => json_encode([
                'size' => '38 inch',
                'resolution' => '3840x1600 (21:9)',
                'panel_type' => 'VA',
                'refresh_rate' => '144Hz',
                'response_time' => '1ms',
                'brightness' => '400 nits',
            ]),
            'category_id' => 3, // Monitors
        ]);

        Product::create([
            'name' => 'Dell U2724D Professional Monitor',
            'description' => 'Premium 27-inch 4K monitor for professionals. Excellent color accuracy and build quality.',
            'price' => 599.99,
            'sku' => 'DELL-U2724D',
            'image_url' => 'https://images.unsplash.com/photo-1611532736579-6b16e2b50449?w=500&h=500&fit=crop',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1611532736579-6b16e2b50449?w=200&h=200&fit=crop',
            'stock' => 9,
            'specifications' => json_encode([
                'size' => '27 inch',
                'resolution' => '3840x2160 (4K)',
                'panel_type' => 'IPS',
                'refresh_rate' => '60Hz',
                'color_gamut' => '99% sRGB',
                'brightness' => '350 nits',
            ]),
            'category_id' => 3, // Monitors
        ]);

        Product::create([
            'name' => 'BenQ EW2780U 4K Monitor',
            'description' => 'High-quality 4K monitor at an affordable price. Great for photo editing and general use.',
            'price' => 399.99,
            'sku' => 'BENQ-EW2780U',
            'image_url' => 'https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?w=500&h=500&fit=crop',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?w=200&h=200&fit=crop',
            'stock' => 11,
            'specifications' => json_encode([
                'size' => '27.9 inch',
                'resolution' => '3840x2160 (4K)',
                'panel_type' => 'IPS',
                'refresh_rate' => '60Hz',
                'brightness' => '350 nits',
                'response_time' => '5ms',
            ]),
            'category_id' => 3, // Monitors
        ]);

        // Peripherals Products
        Product::create([
            'name' => 'Logitech MX Master 3S',
            'description' => 'Premium wireless mouse with advanced features. Perfect for professionals.',
            'price' => 99.99,
            'sku' => 'LOGITECH-MXM3S',
            'image_url' => 'https://images.unsplash.com/photo-1527814050087-3793815479db?w=500&h=500&fit=crop',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1527814050087-3793815479db?w=200&h=200&fit=crop',
            'stock' => 25,
            'specifications' => json_encode([
                'connection' => 'Wireless (2.4GHz + Bluetooth)',
                'dpi' => '4000',
                'buttons' => '8',
                'battery_life' => '70 days',
                'weight' => '141g',
                'warranty' => '3 years',
            ]),
            'category_id' => 4, // Peripherals
        ]);

        Product::create([
            'name' => 'Corsair K95 Platinum XT Mechanical Keyboard',
            'description' => 'Premium mechanical gaming keyboard with RGB backlighting. Excellent build quality and responsiveness.',
            'price' => 229.99,
            'sku' => 'CORSAIR-K95-PLAT',
            'image_url' => 'https://images.unsplash.com/photo-1587829191301-b35b7c6dce5f?w=500&h=500&fit=crop',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1587829191301-b35b7c6dce5f?w=200&h=200&fit=crop',
            'stock' => 18,
            'specifications' => json_encode([
                'switch_type' => 'Cherry MX Red',
                'layout' => 'Full Size (104 keys)',
                'backlight' => 'Per-Key RGB',
                'connection' => 'Wireless + Wired',
                'battery_life' => '24 hours',
                'macro_keys' => '6',
            ]),
            'category_id' => 4, // Peripherals
        ]);

        Product::create([
            'name' => 'SteelSeries Arctis 7P Wireless Headset',
            'description' => 'Premium wireless gaming headset with excellent sound quality. Compatible with PS5 and PC.',
            'price' => 179.99,
            'sku' => 'STEELSERIES-ARC7P',
            'image_url' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&h=500&fit=crop',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=200&h=200&fit=crop',
            'stock' => 14,
            'specifications' => json_encode([
                'connection' => 'Wireless (2.4GHz)',
                'drivers' => '40mm',
                'frequency_response' => '20Hz - 20kHz',
                'impedance' => '32 Ohms',
                'battery_life' => '24 hours',
                'weight' => '250g',
            ]),
            'category_id' => 4, // Peripherals
        ]);

        // Software Products
        Product::create([
            'name' => 'Microsoft Office 365 Personal',
            'description' => 'Annual subscription to Microsoft Office. Includes Word, Excel, PowerPoint, and OneDrive storage.',
            'price' => 69.99,
            'sku' => 'MSFT-OFFICE365-1YR',
            'image_url' => 'https://images.unsplash.com/photo-1584438190454-21b2f00d5e59?w=500&h=500&fit=crop',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1584438190454-21b2f00d5e59?w=200&h=200&fit=crop',
            'stock' => 100,
            'specifications' => json_encode([
                'subscription_length' => '12 months',
                'devices_supported' => 'Unlimited',
                'onedrive_storage' => '1TB',
                'features' => ['Word', 'Excel', 'PowerPoint', 'Outlook', 'Teams'],
                'platforms' => ['Windows', 'macOS', 'iOS', 'Android'],
            ]),
            'category_id' => 5, // Software
        ]);

        Product::create([
            'name' => 'Adobe Creative Cloud 1 Year',
            'description' => 'Complete creative suite with Photoshop, Illustrator, Premiere Pro, and more. Professional tools for designers and creators.',
            'price' => 619.88,
            'sku' => 'ADOBE-CC-1YR',
            'image_url' => 'https://images.unsplash.com/photo-1561070791-2526d30994b5?w=500&h=500&fit=crop',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1561070791-2526d30994b5?w=200&h=200&fit=crop',
            'stock' => 50,
            'specifications' => json_encode([
                'subscription_length' => '12 months',
                'included_apps' => [
                    'Photoshop',
                    'Illustrator',
                    'Premiere Pro',
                    'After Effects',
                    'Lightroom',
                    'XD',
                    'InDesign',
                ],
                'cloud_storage' => '100GB',
                'platforms' => ['Windows', 'macOS'],
            ]),
            'category_id' => 5, // Software
        ]);

        Product::create([
            'name' => 'JetBrains IntelliJ IDEA Ultimate 1 Year',
            'description' => 'Professional IDE for Java, Kotlin, and other languages. Perfect for enterprise development.',
            'price' => 499.00,
            'sku' => 'JETBRAINS-IDEA-1YR',
            'image_url' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=500&h=500&fit=crop',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=200&h=200&fit=crop',
            'stock' => 75,
            'specifications' => json_encode([
                'subscription_length' => '12 months',
                'supported_languages' => ['Java', 'Kotlin', 'Groovy', 'Scala', 'Python'],
                'features' => [
                    'Code Intelligence',
                    'Debugging',
                    'Profiling',
                    'Version Control',
                    'Git Integration',
                ],
                'platforms' => ['Windows', 'macOS', 'Linux'],
            ]),
            'category_id' => 5, // Software
        ]);
    }
}
