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
        $categories = [
            ['name' => 'Laptops', 'description' => 'Notebooks and portable computers'],
            ['name' => 'Desktops', 'description' => 'Desktop computers and workstations'],
            ['name' => 'Monitors', 'description' => 'Computer monitors and displays'],
            ['name' => 'Peripherals', 'description' => 'Keyboards, mice, and other accessories'],
            ['name' => 'Software', 'description' => 'Software licenses and applications'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
            ]);
        }
    }
}
