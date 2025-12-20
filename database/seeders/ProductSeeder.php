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
        
        // High-End Gaming Laptops
        Product::create([
            'name' => 'ASUS ROG Strix G16 (2024)',
            'description' => 'Powerful gaming laptop featuring Intel Core i9-14900HX and NVIDIA RTX 4070. 240Hz display for ultra-smooth gaming. RGB lighting and premium cooling system.',
            'price' => 35199984,
            'sku' => 'ASUS-ROG-G16-2024',
            'image_url' => 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=500&h=500&fit=crop&auto=format',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=200&h=200&fit=crop&auto=format',
            'stock' => 12,
            'specifications' => json_encode([
                'processor' => 'Intel Core i9-14900HX (24 cores, up to 5.8GHz)',
                'gpu' => 'NVIDIA GeForce RTX 4070 8GB GDDR6',
                'ram' => '32GB DDR5-5600',
                'storage' => '1TB PCIe Gen4 NVMe SSD',
                'display' => '16" WQXGA (2560x1600) 240Hz IPS',
                'battery' => '90Wh',
                'weight' => '5.5 lbs',
                'ports' => 'Thunderbolt 4, USB-A 3.2, HDMI 2.1, Ethernet',
            ]),
            'category_id' => 1,
        ]);

        Product::create([
            'name' => 'MSI Raider GE78 HX 13V',
            'description' => 'Flagship gaming laptop with Cherry MX mechanical keyboard and Mini LED display. Ultimate performance for gaming enthusiasts and content creators.',
            'price' => 55999984,
            'sku' => 'MSI-RAID-GE78-13V',
            'image_url' => 'https://images.unsplash.com/photo-1625019030820-e4ed970a6c95?w=500&h=500&fit=crop&auto=format',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1625019030820-e4ed970a6c95?w=200&h=200&fit=crop&auto=format',
            'stock' => 5,
            'specifications' => json_encode([
                'processor' => 'Intel Core i9-13980HX (24 cores, up to 5.6GHz)',
                'gpu' => 'NVIDIA GeForce RTX 4090 16GB GDDR6',
                'ram' => '64GB DDR5-5600',
                'storage' => '2TB PCIe Gen4 NVMe SSD',
                'display' => '17" UHD (3840x2160) 144Hz Mini LED',
                'keyboard' => 'Per-Key RGB Cherry MX Mechanical',
                'battery' => '99.9Wh',
                'weight' => '6.8 lbs',
            ]),
            'category_id' => 1,
        ]);

        // Business & Professional Laptops
        Product::create([
            'name' => 'Dell XPS 15 (2024)',
            'description' => 'Premium ultrabook with stunning OLED display. Perfect for professionals, developers, and content creators who demand power and portability.',
            'price' => 30399984,
            'sku' => 'DELL-XPS15-2024',
            'image_url' => 'https://images.unsplash.com/photo-1593642632823-8f785ba67e45?w=500&h=500&fit=crop&auto=format',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1593642632823-8f785ba67e45?w=200&h=200&fit=crop&auto=format',
            'stock' => 18,
            'specifications' => json_encode([
                'processor' => 'Intel Core i7-13700H (14 cores)',
                'gpu' => 'NVIDIA GeForce RTX 4050 6GB',
                'ram' => '16GB DDR5',
                'storage' => '512GB NVMe SSD',
                'display' => '15.6" 3.5K (3456x2160) OLED Touch',
                'battery' => '86Wh (up to 12 hours)',
                'weight' => '4.23 lbs',
                'warranty' => '3 years Premium Support',
            ]),
            'category_id' => 1,
        ]);

        Product::create([
            'name' => 'MacBook Pro 16" M3 Max',
            'description' => 'Apple\'s most powerful laptop with M3 Max chip. Exceptional performance for video editing, 3D rendering, and software development.',
            'price' => 55984000,
            'sku' => 'APPLE-MBP16-M3MAX',
            'image_url' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=500&h=500&fit=crop&auto=format',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=200&h=200&fit=crop&auto=format',
            'stock' => 8,
            'specifications' => json_encode([
                'processor' => 'Apple M3 Max (16-core CPU)',
                'gpu' => '40-core GPU',
                'ram' => '48GB Unified Memory',
                'storage' => '1TB SSD',
                'display' => '16.2" Liquid Retina XDR (3456x2234)',
                'battery' => 'Up to 22 hours',
                'ports' => '3x Thunderbolt 4, HDMI 2.1, SDXC',
                'weight' => '4.7 lbs',
            ]),
            'category_id' => 1,
        ]);

        // Budget & Student Laptops
        Product::create([
            'name' => 'HP Pavilion 15 Plus',
            'description' => 'Affordable laptop with solid performance for students and home users. Great battery life and modern design.',
            'price' => 10399984,
            'sku' => 'HP-PAV15-PLUS',
            'image_url' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=500&h=500&fit=crop&auto=format',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=200&h=200&fit=crop&auto=format',
            'stock' => 35,
            'specifications' => json_encode([
                'processor' => 'AMD Ryzen 5 7530U (6 cores)',
                'ram' => '8GB DDR4',
                'storage' => '512GB NVMe SSD',
                'display' => '15.6" FHD (1920x1080) IPS',
                'battery' => '51Wh',
                'weight' => '3.75 lbs',
                'color' => 'Natural Silver',
            ]),
            'category_id' => 1,
        ]);

        Product::create([
            'name' => 'Acer Aspire 5',
            'description' => 'Excellent value laptop for everyday computing. Perfect for students, remote workers, and casual users.',
            'price' => 7999984,
            'sku' => 'ACER-ASP5-2024',
            'image_url' => 'https://images.unsplash.com/photo-1484788984921-03950022c9ef?w=500&h=500&fit=crop&auto=format',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1484788984921-03950022c9ef?w=200&h=200&fit=crop&auto=format',
            'stock' => 42,
            'specifications' => json_encode([
                'processor' => 'Intel Core i3-1215U (6 cores)',
                'ram' => '8GB DDR4',
                'storage' => '256GB NVMe SSD',
                'display' => '15.6" FHD (1920x1080)',
                'battery' => '48Wh',
                'weight' => '3.88 lbs',
                'os' => 'Windows 11 Home',
            ]),
            'category_id' => 1,
        ]);

        // === DESKTOP COMPUTERS === //







        // === MONITORS === //



        Product::create([
            'name' => 'LG UltraFine 32UQ85BN 4K Monitor',
            'description' => 'Professional 32" 4K monitor with Thunderbolt connectivity. Perfect for creative professionals and Mac users.',
            'price' => 14399984,
            'sku' => 'LG-32UQ85BN',
            'image_url' => 'https://images.unsplash.com/photo-1611532736579-6b16e2b50449?w=500&h=500&fit=crop&auto=format',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1611532736579-6b16e2b50449?w=200&h=200&fit=crop&auto=format',
            'stock' => 14,
            'specifications' => json_encode([
                'size' => '31.5 inch',
                'resolution' => '3840x2160 (4K UHD)',
                'panel_type' => 'IPS Black',
                'refresh_rate' => '60Hz',
                'color_accuracy' => 'DCI-P3 95%, sRGB 99%',
                'brightness' => '400 nits',
                'ports' => 'Thunderbolt 4, DisplayPort, HDMI, USB-C 90W PD',
                'ergonomics' => 'Height/Tilt/Pivot/Swivel',
            ]),
            'category_id' => 3,
        ]);



        Product::create([
            'name' => 'Dell P2723DE 27" Business Monitor',
            'description' => 'Reliable business monitor with USB-C connectivity and daisy-chaining. Excellent for productivity and office work.',
            'price' => 6879984,
            'sku' => 'DELL-P2723DE',
            'image_url' => 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=500&h=500&fit=crop&auto=format',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=200&h=200&fit=crop&auto=format',
            'stock' => 28,
            'specifications' => json_encode([
                'size' => '27 inch',
                'resolution' => '2560x1440 (QHD)',
                'panel_type' => 'IPS',
                'refresh_rate' => '60Hz',
                'brightness' => '350 nits',
                'ports' => 'USB-C 65W PD, DisplayPort, HDMI, USB Hub',
                'features' => 'Daisy-Chain, KVM, ComfortView Plus',
                'warranty' => '3 years Premium Panel Exchange',
            ]),
            'category_id' => 3,
        ]);

        // === COMPUTER COMPONENTS === //

        Product::create([
            'name' => 'Intel Core i9-14900K Processor',
            'description' => 'Flagship desktop CPU with 24 cores. Exceptional performance for gaming, streaming, and content creation.',
            'price' => 9439984,
            'sku' => 'INTEL-I9-14900K',
            'image_url' => 'https://images.unsplash.com/photo-1555617981-dac3880eac6e?w=500&h=500&fit=crop&auto=format',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1555617981-dac3880eac6e?w=200&h=200&fit=crop&auto=format',
            'stock' => 24,
            'specifications' => json_encode([
                'cores' => '24 (8P + 16E)',
                'threads' => '32',
                'base_clock' => '3.2 GHz',
                'max_turbo' => '6.0 GHz',
                'tdp' => '125W (253W Turbo)',
                'socket' => 'LGA 1700',
                'cache' => '36MB Intel Smart Cache',
                'memory_support' => 'DDR5-5600, DDR4-3200',
            ]),
            'category_id' => 4,
        ]);

        Product::create([
            'name' => 'AMD Ryzen 9 7950X3D',
            'description' => 'Ultimate gaming CPU with 3D V-Cache technology. Unmatched gaming performance with excellent multi-threaded capabilities.',
            'price' => 9599984,
            'sku' => 'AMD-R9-7950X3D',
            'image_url' => 'https://images.unsplash.com/photo-1591488320449-011701bb6704?w=500&h=500&fit=crop&auto=format',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1591488320449-011701bb6704?w=200&h=200&fit=crop&auto=format',
            'stock' => 18,
            'specifications' => json_encode([
                'cores' => '16',
                'threads' => '32',
                'base_clock' => '4.2 GHz',
                'boost_clock' => '5.7 GHz',
                'tdp' => '120W',
                'socket' => 'AM5',
                'cache' => '128MB 3D V-Cache',
                'memory_support' => 'DDR5-5200',
            ]),
            'category_id' => 4,
        ]);

        Product::create([
            'name' => 'NVIDIA GeForce RTX 4090 24GB',
            'description' => 'Ultimate graphics card for gaming and AI. DLSS 3.5 with Ray Reconstruction for stunning visuals.',
            'price' => 25599984,
            'sku' => 'NVIDIA-RTX4090',
            'image_url' => 'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?w=500&h=500&fit=crop&auto=format',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?w=200&h=200&fit=crop&auto=format',
            'stock' => 9,
            'specifications' => json_encode([
                'gpu' => 'NVIDIA Ada Lovelace',
                'cuda_cores' => '16384',
                'vram' => '24GB GDDR6X',
                'memory_bus' => '384-bit',
                'boost_clock' => '2.52 GHz',
                'tdp' => '450W',
                'ports' => '3x DisplayPort 1.4a, 1x HDMI 2.1',
                'features' => 'DLSS 3.5, Ray Tracing, Reflex',
            ]),
            'category_id' => 4,
        ]);

        Product::create([
            'name' => 'Corsair Vengeance DDR5 32GB (2x16GB)',
            'description' => 'High-performance DDR5 RAM optimized for Intel and AMD platforms. Low-profile design with heat spreader.',
            'price' => 2079984,
            'sku' => 'CORSAIR-DDR5-32GB',
            'image_url' => 'https://images.unsplash.com/photo-1618472659851-7a9e0e6f9a5e?w=500&h=500&fit=crop&auto=format',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1618472659851-7a9e0e6f9a5e?w=200&h=200&fit=crop&auto=format',
            'stock' => 45,
            'specifications' => json_encode([
                'capacity' => '32GB (2x16GB)',
                'speed' => '6000MHz',
                'cas_latency' => 'CL30',
                'voltage' => '1.35V',
                'type' => 'DDR5',
                'intel_xmp' => '3.0',
                'amd_expo' => 'Certified',
                'warranty' => 'Lifetime',
            ]),
            'category_id' => 4,
        ]);

        Product::create([
            'name' => 'Samsung 990 PRO 2TB NVMe SSD',
            'description' => 'Blazing-fast PCIe 4.0 SSD with exceptional endurance. Perfect for gaming, content creation, and professional work.',
            'price' => 2879984,
            'sku' => 'SAMSUNG-990PRO-2TB',
            'image_url' => 'https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?w=500&h=500&fit=crop&auto=format',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?w=200&h=200&fit=crop&auto=format',
            'stock' => 32,
            'specifications' => json_encode([
                'capacity' => '2TB',
                'interface' => 'PCIe 4.0 x4, NVMe 2.0',
                'form_factor' => 'M.2 2280',
                'sequential_read' => '7,450 MB/s',
                'sequential_write' => '6,900 MB/s',
                'tbw' => '1,200 TBW',
                'warranty' => '5 years',
            ]),
            'category_id' => 4,
        ]);

        // === KEYBOARDS & MICE === //

        Product::create([
            'name' => 'Logitech MX Master 3S Wireless Mouse',
            'description' => 'Premium productivity mouse with MagSpeed scroll wheel. Silent clicks and multi-device connectivity for professionals.',
            'price' => 1599984,
            'sku' => 'LOGITECH-MXM3S',
            'image_url' => 'https://images.unsplash.com/photo-1527814050087-3793815479db?w=500&h=500&fit=crop&auto=format',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1527814050087-3793815479db?w=200&h=200&fit=crop&auto=format',
            'stock' => 38,
            'specifications' => json_encode([
                'connection' => 'Bluetooth, Logi Bolt USB',
                'sensor' => '8000 DPI',
                'buttons' => '7 programmable buttons',
                'battery' => '70 days (full charge in 3 hours)',
                'weight' => '141g',
                'features' => 'Multi-device, Silent Clicks, MagSpeed Wheel',
                'warranty' => '2 years',
            ]),
            'category_id' => 5,
        ]);

        Product::create([
            'name' => 'Razer DeathAdder V3 Pro',
            'description' => 'Legendary ergonomic gaming mouse with Focus Pro 30K sensor. Lightweight design for competitive gaming.',
            'price' => 2399984,
            'sku' => 'RAZER-DAV3-PRO',
            'image_url' => 'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?w=500&h=500&fit=crop&auto=format',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?w=200&h=200&fit=crop&auto=format',
            'stock' => 27,
            'specifications' => json_encode([
                'connection' => 'Wireless (2.4GHz), Bluetooth, Wired',
                'sensor' => 'Focus Pro 30K Optical',
                'dpi' => '30,000',
                'buttons' => '5 programmable buttons',
                'weight' => '63g',
                'battery' => '90 hours',
                'polling_rate' => '4000Hz (wireless)',
            ]),
            'category_id' => 5,
        ]);

        Product::create([
            'name' => 'Keychron Q6 Pro Mechanical Keyboard',
            'description' => 'Premium full-size wireless mechanical keyboard with hot-swappable switches. Aluminum CNC body with QMK/VIA support.',
            'price' => 3440000,
            'sku' => 'KEYCHRON-Q6PRO',
            'image_url' => 'https://images.unsplash.com/photo-1595225476474-87563907a212?w=500&h=500&fit=crop&auto=format',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1595225476474-87563907a212?w=200&h=200&fit=crop&auto=format',
            'stock' => 19,
            'specifications' => json_encode([
                'layout' => 'Full-size 108 keys',
                'switches' => 'Hot-swappable (Gateron G Pro Red included)',
                'connection' => 'Wireless (2.4GHz, Bluetooth 5.1), Wired USB-C',
                'construction' => 'CNC Aluminum',
                'battery' => '4000mAh',
                'backlight' => 'South-facing RGB',
                'firmware' => 'QMK/VIA',
                'compatibility' => 'macOS, Windows, Linux',
            ]),
            'category_id' => 5,
        ]);

        Product::create([
            'name' => 'Corsair K70 RGB Pro Mechanical Keyboard',
            'description' => 'Tournament-grade mechanical gaming keyboard with Cherry MX switches. Durable aluminum frame with per-key RGB.',
            'price' => 2719984,
            'sku' => 'CORSAIR-K70RGB',
            'image_url' => 'https://images.unsplash.com/photo-1541140532154-b024d705b90a?w=500&h=500&fit=crop&auto=format',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1541140532154-b024d705b90a?w=200&h=200&fit=crop&auto=format',
            'stock' => 31,
            'specifications' => json_encode([
                'switches' => 'Cherry MX Red/Speed/Brown',
                'layout' => 'Full-size US QWERTY',
                'backlight' => 'Per-key RGB (16.8M colors)',
                'frame' => 'Anodized Aluminum',
                'keycaps' => 'PBT Double-shot',
                'connection' => 'USB-C Detachable',
                'polling_rate' => '1000Hz',
                'features' => 'Dedicated media keys, Tournament switch',
            ]),
            'category_id' => 5,
        ]);

        // === AUDIO & HEADSETS === //

        Product::create([
            'name' => 'SteelSeries Arctis Nova Pro Wireless',
            'description' => 'Premium multi-system wireless headset with active noise cancellation. Dual battery system for unlimited gameplay.',
            'price' => 5599984,
            'sku' => 'STEELSERIES-NOVA-PRO',
            'image_url' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&h=500&fit=crop&auto=format',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=200&h=200&fit=crop&auto=format',
            'stock' => 16,
            'specifications' => json_encode([
                'connection' => 'Wireless 2.4GHz + Bluetooth',
                'drivers' => 'Hi-Res 40mm Neodymium',
                'frequency_response' => '10Hz - 40kHz',
                'battery' => 'Dual hot-swappable (44 hours total)',
                'anc' => '4-mic Active Noise Cancellation',
                'compatibility' => 'PC, PS5, Xbox, Switch, Mobile',
                'features' => 'GameDAC Gen 2, Infinity Power System',
            ]),
            'category_id' => 6,
        ]);

        Product::create([
            'name' => 'Sony WH-1000XM5 Headphones',
            'description' => 'Industry-leading noise-canceling headphones. Perfect for music, work, and travel with 30-hour battery.',
            'price' => 6399984,
            'sku' => 'SONY-WH1000XM5',
            'image_url' => 'https://images.unsplash.com/photo-1546435770-a3e426bf472b?w=500&h=500&fit=crop&auto=format',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1546435770-a3e426bf472b?w=200&h=200&fit=crop&auto=format',
            'stock' => 22,
            'specifications' => json_encode([
                'connection' => 'Bluetooth 5.2, 3.5mm',
                'drivers' => '30mm',
                'anc' => '8 microphones with V1 processor',
                'battery' => '30 hours with ANC, 40 hours without',
                'quick_charge' => '3 min = 3 hours playback',
                'codecs' => 'LDAC, AAC, SBC',
                'features' => 'Speak-to-Chat, Multipoint, Wear Detection',
            ]),
            'category_id' => 6,
        ]);

        Product::create([
            'name' => 'HyperX Cloud Alpha Wireless',
            'description' => 'Gaming headset with record-breaking 300-hour battery life. DTS Headphone:X spatial audio for immersive gaming.',
            'price' => 3199984,
            'sku' => 'HYPERX-ALPHA-WIRELESS',
            'image_url' => 'https://images.unsplash.com/photo-1484704849700-f032a568e944?w=500&h=500&fit=crop&auto=format',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1484704849700-f032a568e944?w=200&h=200&fit=crop&auto=format',
            'stock' => 25,
            'specifications' => json_encode([
                'connection' => 'Wireless 2.4GHz USB-C',
                'drivers' => 'Dual Chamber 50mm',
                'battery' => 'Up to 300 hours',
                'frequency_response' => '15Hz - 21kHz',
                'weight' => '286g',
                'compatibility' => 'PC, PS5, PS4, Switch',
                'features' => 'DTS Headphone:X, Detachable Mic',
            ]),
            'category_id' => 6,
        ]);

        Product::create([
            'name' => 'Blue Yeti USB Microphone',
            'description' => 'Professional USB condenser microphone for streaming, podcasting, and voiceover. Four pickup patterns.',
            'price' => 2079984,
            'sku' => 'BLUE-YETI-USB',
            'image_url' => 'https://images.unsplash.com/photo-1589003077984-894e133dabab?w=500&h=500&fit=crop&auto=format',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1589003077984-894e133dabab?w=200&h=200&fit=crop&auto=format',
            'stock' => 34,
            'specifications' => json_encode([
                'connection' => 'USB (Plug & Play)',
                'sample_rate' => '48kHz/16-bit',
                'polar_patterns' => 'Cardioid, Bidirectional, Omnidirectional, Stereo',
                'frequency_response' => '20Hz - 20kHz',
                'controls' => 'Gain, Mute, Pattern, Headphone Volume',
                'mount' => 'Desktop stand included',
                'compatibility' => 'PC, Mac, PS5, PS4',
            ]),
            'category_id' => 6,
        ]);

        // === NETWORKING === //

        Product::create([
            'name' => 'ASUS RT-AX88U Pro Gaming Router',
            'description' => 'High-performance WiFi 6 router with 2.5G WAN port. Optimized for gaming with AiMesh support.',
            'price' => 4799984,
            'sku' => 'ASUS-RTAX88U-PRO',
            'image_url' => 'https://images.unsplash.com/photo-1606904825846-647eb07f5be2?w=500&h=500&fit=crop&auto=format',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1606904825846-647eb07f5be2?w=200&h=200&fit=crop&auto=format',
            'stock' => 18,
            'specifications' => json_encode([
                'wifi_standard' => 'WiFi 6 (802.11ax)',
                'speed' => 'AX6000 (4804 + 1148 Mbps)',
                'coverage' => 'Up to 3000 sq ft',
                'ports' => '2.5G WAN, 8x Gigabit LAN, 2x USB 3.2',
                'features' => 'AiMesh, AiProtection Pro, Gaming Port',
                'processor' => 'Quad-core 2.0GHz',
                'ram' => '1GB',
                'warranty' => '3 years',
            ]),
            'category_id' => 7,
        ]);

        Product::create([
            'name' => 'TP-Link Deco XE75 Mesh WiFi System',
            'description' => 'Whole-home WiFi 6E mesh system. Covers up to 7200 sq ft with seamless roaming and parental controls.',
            'price' => 6399984,
            'sku' => 'TPLINK-DECO-XE75',
            'image_url' => 'https://images.unsplash.com/photo-1628519144643-d9f2e47f72aa?w=500&h=500&fit=crop&auto=format',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1628519144643-d9f2e47f72aa?w=200&h=200&fit=crop&auto=format',
            'stock' => 21,
            'specifications' => json_encode([
                'wifi_standard' => 'WiFi 6E (802.11ax)',
                'speed' => 'AXE5400 (2402 + 2402 + 574 Mbps)',
                'units' => '3-pack',
                'coverage' => 'Up to 7200 sq ft',
                'capacity' => '200+ devices',
                'ports' => '3x Gigabit LAN per unit',
                'security' => 'WPA3, TP-Link HomeShield',
                'features' => 'AI-Driven Mesh, IoT Network',
            ]),
            'category_id' => 7,
        ]);

        // === STORAGE SOLUTIONS === //

        Product::create([
            'name' => 'WD My Book 8TB External Hard Drive',
            'description' => 'High-capacity desktop external storage with hardware encryption. Perfect for backups and media libraries.',
            'price' => 2879984,
            'sku' => 'WD-MYBOOK-8TB',
            'image_url' => 'https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?w=500&h=500&fit=crop&auto=format',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?w=200&h=200&fit=crop&auto=format',
            'stock' => 29,
            'specifications' => json_encode([
                'capacity' => '8TB',
                'interface' => 'USB 3.2 Gen 1',
                'rpm' => '5400 RPM',
                'features' => '256-bit AES Hardware Encryption, Password Protection',
                'software' => 'WD Backup, WD Discovery',
                'compatibility' => 'Windows, macOS (reformatting required)',
                'warranty' => '3 years',
            ]),
            'category_id' => 8,
        ]);

        Product::create([
            'name' => 'Samsung T7 Shield 2TB Portable SSD',
            'description' => 'Rugged portable SSD with IP65 water and dust resistance. Super-fast transfers up to 1050 MB/s.',
            'price' => 3519984,
            'sku' => 'SAMSUNG-T7-SHIELD-2TB',
            'image_url' => 'https://images.unsplash.com/photo-1531492746076-161ca9bcad58?w=500&h=500&fit=crop&auto=format',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1531492746076-161ca9bcad58?w=200&h=200&fit=crop&auto=format',
            'stock' => 36,
            'specifications' => json_encode([
                'capacity' => '2TB',
                'interface' => 'USB 3.2 Gen 2 (10Gbps)',
                'read_speed' => '1,050 MB/s',
                'write_speed' => '1,000 MB/s',
                'durability' => 'IP65 rated, 3m drop resistant',
                'security' => 'AES 256-bit hardware encryption',
                'dimensions' => '88 x 59 x 13 mm',
                'weight' => '98g',
            ]),
            'category_id' => 8,
        ]);

        Product::create([
            'name' => 'Synology DS923+ NAS 4-Bay',
            'description' => 'Professional 4-bay NAS with AMD Ryzen processor. Ideal for home offices, media servers, and data backup.',
            'price' => 9599984,
            'sku' => 'SYNOLOGY-DS923',
            'image_url' => 'https://images.unsplash.com/photo-1597733336794-12d05021d510?w=500&h=500&fit=crop&auto=format',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1597733336794-12d05021d510?w=200&h=200&fit=crop&auto=format',
            'stock' => 12,
            'specifications' => json_encode([
                'bays' => '4 (hot-swappable)',
                'processor' => 'AMD Ryzen R1600 Dual-core 2.6GHz',
                'ram' => '4GB DDR4 ECC (expandable to 32GB)',
                'network' => '2x 1GbE',
                'max_capacity' => '72TB (with expansion unit)',
                'features' => 'RAID 0/1/5/6/10, Hot Spare, Snapshot',
                'os' => 'DSM 7',
                'warranty' => '3 years',
            ]),
            'category_id' => 8,
        ]);

        // === SOFTWARE === //

        Product::create([
            'name' => 'Microsoft Office 2021 Professional Plus',
            'description' => 'Perpetual license for Office suite. Includes Word, Excel, PowerPoint, Outlook, Publisher, and Access.',
            'price' => 7039984,
            'sku' => 'MSFT-OFFICE-2021-PRO',
            'image_url' => 'https://images.unsplash.com/photo-1633419461186-7d40a38105ec?w=500&h=500&fit=crop&auto=format',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1633419461186-7d40a38105ec?w=200&h=200&fit=crop&auto=format',
            'stock' => 150,
            'specifications' => json_encode([
                'license_type' => 'Perpetual (one-time purchase)',
                'devices' => '1 PC',
                'applications' => ['Word', 'Excel', 'PowerPoint', 'Outlook', 'Publisher', 'Access', 'Teams'],
                'cloud_storage' => 'None (desktop only)',
                'platforms' => 'Windows 11, Windows 10',
                'updates' => 'Security updates only',
                'commercial_use' => 'Yes',
            ]),
            'category_id' => 9,
        ]);

        Product::create([
            'name' => 'Adobe Creative Cloud All Apps (1 Year)',
            'description' => 'Complete creative suite subscription. All Adobe apps including Photoshop, Premiere Pro, After Effects, and more.',
            'price' => 10558080,
            'sku' => 'ADOBE-CC-ALL-1YR',
            'image_url' => 'https://images.unsplash.com/photo-1626785774625-ddcddc3445e9?w=500&h=500&fit=crop&auto=format',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1626785774625-ddcddc3445e9?w=200&h=200&fit=crop&auto=format',
            'stock' => 75,
            'specifications' => json_encode([
                'subscription_length' => '12 months',
                'included_apps' => [
                    'Photoshop', 'Illustrator', 'InDesign', 'Premiere Pro',
                    'After Effects', 'Lightroom', 'XD', 'Animate',
                    'Dreamweaver', 'Audition', 'Acrobat Pro', 'Dimension',
                ],
                'cloud_storage' => '100GB',
                'adobe_fonts' => 'Unlimited access',
                'platforms' => 'Windows, macOS',
                'devices' => '2 active installations',
            ]),
            'category_id' => 9,
        ]);

        Product::create([
            'name' => 'Windows 11 Pro (Digital License)',
            'description' => 'Professional version of Windows 11 with enhanced security and management features for businesses.',
            'price' => 3199984,
            'sku' => 'WIN11-PRO-DIGITAL',
            'image_url' => 'https://images.unsplash.com/photo-1629654291663-b91ad427698f?w=500&h=500&fit=crop&auto=format',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1629654291663-b91ad427698f?w=200&h=200&fit=crop&auto=format',
            'stock' => 250,
            'specifications' => json_encode([
                'license_type' => 'Full Retail (Digital)',
                'devices' => '1 PC',
                'features' => [
                    'BitLocker Encryption',
                    'Remote Desktop',
                    'Hyper-V',
                    'Windows Sandbox',
                    'Group Policy Management',
                ],
                'requirements' => 'TPM 2.0, UEFI, Secure Boot',
                'language' => 'Multi-language',
                'delivery' => 'Instant digital delivery',
            ]),
            'category_id' => 9,
        ]);

        Product::create([
            'name' => 'Malwarebytes Premium (3 Devices, 1 Year)',
            'description' => 'Advanced anti-malware protection with real-time threat detection. Protects against ransomware, spyware, and viruses.',
            'price' => 1279984,
            'sku' => 'MALWAREBYTES-3DEV-1YR',
            'image_url' => 'https://images.unsplash.com/photo-1563906267088-b029e7101114?w=500&h=500&fit=crop&auto=format',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1563906267088-b029e7101114?w=200&h=200&fit=crop&auto=format',
            'stock' => 120,
            'specifications' => json_encode([
                'subscription_length' => '12 months',
                'devices' => '3 (Windows, Mac, Android, iOS)',
                'features' => [
                    'Real-time Protection',
                    'Ransomware Protection',
                    'Web Protection',
                    'Exploit Protection',
                ],
                'scanning' => 'Fast, customizable scans',
                'support' => '24/7 customer support',
                'platforms' => 'Windows, macOS, Android, iOS',
            ]),
            'category_id' => 9,
        ]);

        Product::create([
            'name' => 'Visual Studio Professional 2022',
            'description' => 'Professional IDE for .NET and C++ developers. Advanced debugging, profiling, and collaboration features.',
            'price' => 7984000,
            'sku' => 'MSVS-2022-PRO',
            'image_url' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=500&h=500&fit=crop&auto=format',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=200&h=200&fit=crop&auto=format',
            'stock' => 85,
            'specifications' => json_encode([
                'license_type' => 'Annual subscription',
                'platforms' => 'Windows',
                'languages' => ['.NET', 'C++', 'C#', 'F#', 'Python', 'JavaScript', 'TypeScript'],
                'features' => [
                    'IntelliCode AI',
                    'CodeLens',
                    'Live Share',
                    'Git Integration',
                    'Debugging & Diagnostics',
                ],
                'includes' => 'Azure DevOps, GitHub Codespaces hours',
                'devices' => '1 user, multiple devices',
            ]),
            'category_id' => 9,
        ]);

        // === GAMING ACCESSORIES === //

        Product::create([
            'name' => 'Xbox Elite Wireless Controller Series 2',
            'description' => 'Premium gaming controller with interchangeable components. Adjustable tension thumbsticks and paddles.',
            'price' => 2879984,
            'sku' => 'XBOX-ELITE-V2',
            'image_url' => 'https://images.unsplash.com/photo-1606144042614-b2417e99c4e3?w=500&h=500&fit=crop&auto=format',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1606144042614-b2417e99c4e3?w=200&h=200&fit=crop&auto=format',
            'stock' => 26,
            'specifications' => json_encode([
                'connection' => 'Wireless + USB-C',
                'battery' => 'Up to 40 hours',
                'customization' => 'Interchangeable thumbsticks, D-pads, paddles',
                'features' => 'Hair Trigger Locks, Adjustable Tension',
                'compatibility' => 'Xbox Series X|S, Xbox One, PC, Mobile',
                'charging' => 'Rechargeable battery + USB-C cable',
                'warranty' => '1 year',
            ]),
            'category_id' => 10,
        ]);

        Product::create([
            'name' => 'Logitech G923 Racing Wheel',
            'description' => 'Realistic racing wheel with TRUEFORCE force feedback. Includes responsive pedals for immersive sim racing.',
            'price' => 6399984,
            'sku' => 'LOGITECH-G923',
            'image_url' => 'https://images.unsplash.com/photo-1593305841991-05c297ba4575?w=500&h=500&fit=crop&auto=format',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1593305841991-05c297ba4575?w=200&h=200&fit=crop&auto=format',
            'stock' => 14,
            'specifications' => json_encode([
                'force_feedback' => 'TRUEFORCE (up to 4000Hz)',
                'rotation' => '900 degrees',
                'pedals' => '3-pedal set with progressive brake',
                'compatibility' => 'PS5, PS4, PC',
                'features' => 'Dual clutch launch, LED RPM indicator',
                'mounting' => 'Clamp or hard mount',
                'warranty' => '2 years',
            ]),
            'category_id' => 10,
        ]);

        Product::create([
            'name' => 'Meta Quest 3 VR Headset (512GB)',
            'description' => 'Next-gen mixed reality headset with improved performance and optics. Standalone VR with PC VR compatibility.',
            'price' => 10399984,
            'sku' => 'META-QUEST3-512GB',
            'image_url' => 'https://images.unsplash.com/photo-1622979135225-d2ba269cf1ac?w=500&h=500&fit=crop&auto=format',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1622979135225-d2ba269cf1ac?w=200&h=200&fit=crop&auto=format',
            'stock' => 18,
            'specifications' => json_encode([
                'storage' => '512GB',
                'display' => '2064 x 2208 per eye, 4K+ Infinite Display',
                'refresh_rate' => 'Up to 120Hz',
                'processor' => 'Snapdragon XR2 Gen 2',
                'controllers' => 'Touch Plus Controllers (included)',
                'passthrough' => 'Full-color mixed reality',
                'battery' => '2.2 hours average',
                'features' => 'Hand tracking, PC VR via Link/Air Link',
            ]),
            'category_id' => 10,
        ]);

        Product::create([
            'name' => 'Elgato Stream Deck MK.2',
            'description' => 'Studio controller with 15 LCD keys for streamers and content creators. Customizable macros and integrations.',
            'price' => 2399984,
            'sku' => 'ELGATO-STREAMDECK-MK2',
            'image_url' => 'https://images.unsplash.com/photo-1614624532983-4ce03382d63d?w=500&h=500&fit=crop&auto=format',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1614624532983-4ce03382d63d?w=200&h=200&fit=crop&auto=format',
            'stock' => 31,
            'specifications' => json_encode([
                'keys' => '15 customizable LCD keys',
                'connection' => 'USB-C',
                'integration' => 'OBS, Streamlabs, Twitch, YouTube, Spotify, Philips Hue',
                'software' => 'Stream Deck Software',
                'features' => 'Multi-actions, Folders, Profiles',
                'stand' => 'Removable faceplate + adjustable stand',
                'compatibility' => 'Windows, macOS',
            ]),
            'category_id' => 10,
        ]);

        Product::create([
            'name' => 'Secretlab Titan Evo 2024 Gaming Chair',
            'description' => 'Award-winning ergonomic gaming chair with NEO Hybrid Leatherette. Full metal 4D armrests and magnetic head pillow.',
            'price' => 9104000,
            'sku' => 'SECRETLAB-TITAN-EVO-2024',
            'image_url' => 'https://images.unsplash.com/photo-1598550476439-6847785fcea6?w=500&h=500&fit=crop&auto=format',
            'image_thumbnail_url' => 'https://images.unsplash.com/photo-1598550476439-6847785fcea6?w=200&h=200&fit=crop&auto=format',
            'stock' => 9,
            'specifications' => json_encode([
                'size' => 'Regular (5\'5" - 6\'1", up to 220 lbs)',
                'upholstery' => 'NEO Hybrid Leatherette',
                'backrest' => 'Pebble seat base with CloudSwap tech',
                'armrests' => 'Full Metal 4D',
                'lumbar' => '4-way L-ADAPT Lumbar Support',
                'recline' => '85° to 165°',
                'base' => 'ADC12 Aluminum',
                'warranty' => '5 years',
            ]),
            'category_id' => 10,
        ]);
    }
}
