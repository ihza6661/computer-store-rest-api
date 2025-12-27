<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration moves existing product images from the products table
     * to the new product_images table for multi-image support.
     */
    public function up(): void
    {
        // Get all products that have an image_url
        $products = DB::table('products')
            ->whereNotNull('image_url')
            ->where('image_url', '!=', '')
            ->get();

        foreach ($products as $product) {
            // Create a product_image entry for each existing product image
            DB::table('product_images')->insert([
                'product_id' => $product->id,
                'image_url' => $product->image_url,
                'image_thumbnail_url' => $product->image_thumbnail_url,
                'sort_order' => 0,
                'is_primary' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     * 
     * WARNING: This will delete all migrated images from product_images table.
     * Only run this if you need to roll back the migration.
     */
    public function down(): void
    {
        // Delete all product images that were created during migration
        // (identified by is_primary = true and sort_order = 0)
        DB::table('product_images')
            ->where('is_primary', true)
            ->where('sort_order', 0)
            ->delete();
    }
};
