<?php

// Script to create test products in production

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Get Laptop category
$laptopCategory = App\Models\Category::where('name', 'Laptop')->first();

if (!$laptopCategory) {
    echo "Error: Laptop category not found\n";
    exit(1);
}

echo "Using category: {$laptopCategory->name} (ID: {$laptopCategory->id})\n\n";

// Check if products already exist
$existing = App\Models\Product::withTrashed()->whereIn('sku', ['MBP-M3-16-001', 'DELL-XPS15-001'])->get();

if ($existing->count() > 0) {
    echo "Found {$existing->count()} existing products:\n";
    foreach ($existing as $p) {
        echo "- {$p->sku}: {$p->name}";
        if ($p->deleted_at) {
            echo " (DELETED - Restoring...)\n";
            $p->restore();
            echo "  Restored successfully!\n";
        } else {
            echo " (ACTIVE)\n";
        }
    }
} else {
    echo "Creating new test products...\n\n";
    
    // Create Product 1
    try {
        $product1 = App\Models\Product::create([
            'sku' => 'MBP-M3-16-001',
            'name' => 'MacBook Pro M3 16" 2024',
            'category_id' => $laptopCategory->id,
            'brand' => 'Apple',
            'price' => 35000000,
            'stock' => 5,
            'description' => 'High performance laptop with M3 chip',
            'image_url' => 'https://via.placeholder.com/300',
            'specifications' => json_encode(['processor' => 'M3', 'ram' => '16GB']),
        ]);
        echo "✓ Created: {$product1->sku} (ID: {$product1->id})\n";
    } catch (\Exception $e) {
        echo "✗ Error creating product 1: {$e->getMessage()}\n";
    }
    
    // Create Product 2
    try {
        $product2 = App\Models\Product::create([
            'sku' => 'DELL-XPS15-001',
            'name' => 'Dell XPS 15 9530',
            'category_id' => $laptopCategory->id,
            'brand' => 'Dell',
            'price' => 28000000,
            'stock' => 3,
            'description' => 'Premium ultrabook for professionals',
            'image_url' => 'https://via.placeholder.com/300',
            'specifications' => json_encode(['processor' => 'Intel i7', 'ram' => '16GB']),
        ]);
        echo "✓ Created: {$product2->sku} (ID: {$product2->id})\n";
    } catch (\Exception $e) {
        echo "✗ Error creating product 2: {$e->getMessage()}\n";
    }
}

echo "\n✅ Done! Test products are ready.\n";
