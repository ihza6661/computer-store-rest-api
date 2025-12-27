<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Cloudinary\Cloudinary;

class ProductImageController extends Controller
{
    /**
     * Delete a product image
     * 
     * Business Logic:
     * - Cannot delete the last image (minimum 1 required)
     * - If deleting primary image, auto-assign next image as primary
     * - Must update products.image_url (denormalization strategy)
     */
    public function destroy(Product $product, ProductImage $image)
    {
        // Verify image belongs to product
        if ($image->product_id !== $product->id) {
            return back()->with('error', 'Image tidak ditemukan untuk produk ini.');
        }

        // Check if this is the last image
        $imageCount = $product->images()->count();
        if ($imageCount <= 1) {
            return back()->with('error', 'Tidak dapat menghapus gambar terakhir. Produk harus memiliki minimal 1 gambar.');
        }

        $isPrimary = $image->is_primary;
        
        // Delete from Cloudinary
        try {
            $cloudinary = new Cloudinary([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                    'api_key' => env('CLOUDINARY_API_KEY'),
                    'api_secret' => env('CLOUDINARY_API_SECRET'),
                ]
            ]);

            // Extract public_id from URL
            $imageUrl = $image->image_url;
            $publicId = $this->extractPublicIdFromUrl($imageUrl);
            
            if ($publicId) {
                $cloudinary->uploadApi()->destroy($publicId);
            }
        } catch (\Exception $e) {
            // Log error but continue deletion from database
            Log::warning('Failed to delete image from Cloudinary: ' . $e->getMessage());
        }

        // Delete from database
        $image->delete();

        // If we deleted the primary image, assign new primary
        if ($isPrimary) {
            // Get next available image (by sort_order)
            $newPrimary = $product->images()->orderBy('sort_order')->first();
            
            if ($newPrimary) {
                $newPrimary->update(['is_primary' => true]);
                
                // CRITICAL: Update denormalized fields in products table
                $product->update([
                    'image_url' => $newPrimary->image_url,
                    'image_thumbnail_url' => $newPrimary->image_thumbnail_url
                ]);
            }
        }

        return back()->with('success', 'Gambar berhasil dihapus.');
    }

    /**
     * Set an image as primary
     * 
     * Business Logic:
     * - Remove primary flag from all other images
     * - Set selected image as primary
     * - MUST update products.image_url (denormalization - CRITICAL)
     */
    public function setPrimary(Product $product, ProductImage $image)
    {
        // Verify image belongs to product
        if ($image->product_id !== $product->id) {
            return back()->with('error', 'Image tidak ditemukan untuk produk ini.');
        }

        // Remove primary from all other images
        $product->images()->update(['is_primary' => false]);

        // Set this image as primary
        $image->update(['is_primary' => true]);

        // CRITICAL: Update denormalized fields in products table
        $product->update([
            'image_url' => $image->image_url,
            'image_thumbnail_url' => $image->image_thumbnail_url
        ]);

        return back()->with('success', 'Gambar utama berhasil diubah.');
    }

    /**
     * Extract Cloudinary public_id from URL
     * Example: https://res.cloudinary.com/demo/image/upload/v1234/folder/image.jpg
     * Returns: folder/image
     */
    private function extractPublicIdFromUrl($url)
    {
        if (!$url) {
            return null;
        }

        // Parse URL and extract public_id
        $parts = explode('/upload/', $url);
        if (count($parts) < 2) {
            return null;
        }

        $afterUpload = $parts[1];
        
        // Remove version number (v1234567890/)
        $afterUpload = preg_replace('/^v\d+\//', '', $afterUpload);
        
        // Remove file extension
        $publicId = preg_replace('/\.[^.]+$/', '', $afterUpload);
        
        return $publicId;
    }
}
