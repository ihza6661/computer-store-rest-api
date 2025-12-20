<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define all 10 categories explicitly
        $categories = [
            ['name' => 'Laptops', 'description' => 'High-performance laptops for gaming, business, and everyday use. From ultraportable notebooks to powerful gaming machines.'],
            ['name' => 'Desktop Computers', 'description' => 'Complete desktop systems and components for professional workstations, gaming rigs, and home offices.'],
            ['name' => 'Monitors & Displays', 'description' => 'Professional monitors, gaming displays, and ultrawide screens. 4K, curved, and high refresh rate options available.'],
            ['name' => 'Computer Components', 'description' => 'CPU processors, graphics cards, RAM, motherboards, storage drives, and PC cases for custom builds.'],
            ['name' => 'Keyboards & Mice', 'description' => 'Mechanical keyboards, wireless mice, gaming peripherals, and ergonomic input devices from top brands.'],
            ['name' => 'Audio & Headsets', 'description' => 'Gaming headsets, studio headphones, microphones, and speakers for immersive audio experiences.'],
            ['name' => 'Networking Equipment', 'description' => 'Routers, switches, WiFi mesh systems, and network accessories for home and office connectivity.'],
            ['name' => 'Storage Solutions', 'description' => 'External hard drives, SSDs, NAS systems, and cloud storage solutions for backup and expansion.'],
            ['name' => 'Software & Licenses', 'description' => 'Operating systems, productivity suites, creative software, antivirus, and professional development tools.'],
            ['name' => 'Gaming Accessories', 'description' => 'Controllers, racing wheels, VR headsets, gaming chairs, and streaming equipment for gamers.'],
        ];

        // Create each category individually to ensure all 10 are created
        foreach ($categories as $index => $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
            ]);
            
            // Log progress for debugging
            $count = $index + 1;
            $this->command->info("Created category {$count}/10: {$category['name']}");
        }
        
        $total = Category::count();
        $this->command->info("Total categories created: {$total}");
    }
}
