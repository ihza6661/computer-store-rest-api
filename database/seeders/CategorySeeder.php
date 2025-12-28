<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define all 11 categories in Indonesian with their old slugs for mapping
        $categories = [
            ['old_slug' => 'smartphones', 'name' => 'Smartphone', 'description' => 'iPhone dengan garansi resmi TAM dan smartphone Android terpercaya. Cicilan 0% tersedia dari 12+ bank.'],
            ['old_slug' => 'laptops', 'name' => 'Laptop', 'description' => 'Laptop performa tinggi untuk gaming, bisnis, dan penggunaan sehari-hari. Dari notebook portabel hingga mesin gaming bertenaga.'],
            ['old_slug' => 'desktop-computers', 'name' => 'Komputer Desktop', 'description' => 'Sistem desktop lengkap dan komponen untuk workstation profesional, gaming rig, dan kantor rumah.'],
            ['old_slug' => 'monitors-displays', 'name' => 'Monitor & Display', 'description' => 'Monitor profesional, display gaming, dan layar ultrawide. Tersedia pilihan 4K, curved, dan high refresh rate.'],
            ['old_slug' => 'computer-components', 'name' => 'Komponen Komputer', 'description' => 'Prosesor CPU, kartu grafis, RAM, motherboard, storage drive, dan casing PC untuk rakit custom.'],
            ['old_slug' => 'keyboards-mice', 'name' => 'Keyboard & Mouse', 'description' => 'Keyboard mechanical, mouse wireless, peripheral gaming, dan perangkat input ergonomis dari brand ternama.'],
            ['old_slug' => 'audio-headsets', 'name' => 'Audio & Headset', 'description' => 'Headset gaming, headphone studio, mikrofon, dan speaker untuk pengalaman audio yang imersif.'],
            ['old_slug' => 'networking-equipment', 'name' => 'Perangkat Jaringan', 'description' => 'Router, switch, sistem WiFi mesh, dan aksesori jaringan untuk konektivitas rumah dan kantor.'],
            ['old_slug' => 'storage-solutions', 'name' => 'Penyimpanan Data', 'description' => 'Hard drive eksternal, SSD, sistem NAS, dan solusi cloud storage untuk backup dan ekspansi.'],
            ['old_slug' => 'software-licenses', 'name' => 'Software & Lisensi', 'description' => 'Sistem operasi, productivity suite, software kreatif, antivirus, dan tools pengembangan profesional.'],
            ['old_slug' => 'gaming-accessories', 'name' => 'Aksesori Gaming', 'description' => 'Controller, racing wheel, VR headset, kursi gaming, dan peralatan streaming untuk para gamers.'],
        ];

        // Update existing categories in-place (preserves foreign key relationships)
        foreach ($categories as $index => $categoryData) {
            $category = Category::where('slug', $categoryData['old_slug'])->first();
            
            if ($category) {
                // Update existing category
                $category->update([
                    'name' => $categoryData['name'],
                    'slug' => Str::slug($categoryData['name']),
                    'description' => $categoryData['description'],
                ]);
                $this->command->info("✅ Updated: {$categoryData['old_slug']} → {$categoryData['name']}");
            } else {
                // Create new category if not found
                Category::create([
                    'name' => $categoryData['name'],
                    'slug' => Str::slug($categoryData['name']),
                    'description' => $categoryData['description'],
                ]);
                $this->command->info("➕ Created: {$categoryData['name']}");
            }
        }

        $total = Category::count();
        $this->command->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->command->info("✨ Total categories: {$total}");
    }
}
