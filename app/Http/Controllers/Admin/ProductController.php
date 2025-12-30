<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Store a newly created product in storage.
     * POST /admin/products
     * 
     * Supports multiple image uploads (1-10 images)
     * First image becomes primary automatically
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sku' => 'required|string|unique:products,sku',
            'images' => 'required|array|min:1|max:10',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'stock' => 'required|integer|min:0',
            'specifications' => 'nullable|array',
            'specifications.processor' => 'nullable|string|max:255',
            'specifications.gpu' => 'nullable|string|max:255',
            'specifications.ram' => 'nullable|string|max:100',
            'specifications.storage' => 'nullable|string|max:255',
            'specifications.display' => 'nullable|string|max:255',
            'specifications.keyboard' => 'nullable|string|max:255',
            'specifications.battery' => 'nullable|string|max:255',
            'specifications.warranty' => 'nullable|string|max:255',
            'specifications.condition' => 'nullable|string|in:excellent,good,fair',
            'specifications.extras' => 'nullable|string|max:500',
            'specifications.original_price' => 'nullable|numeric|min:0',
            'specifications.features' => 'nullable|string|max:1000',
        ]);

        $primaryImageUrl = null;
        $primaryImageThumbnailUrl = null;

        // Create the product first (without images)
        $product = Product::create([
            'name' => $validated['name'],
            'category_id' => $validated['category_id'],
            'brand' => $validated['brand'] ?? null,
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'sku' => $validated['sku'],
            'stock' => $validated['stock'],
            'specifications' => $validated['specifications'] ?? null,
            'image_url' => '', // Temporary, will be updated with primary image
            'image_thumbnail_url' => null,
        ]);

        // Handle multiple image uploads
        if ($request->hasFile('images')) {
            $images = $request->file('images');

            foreach ($images as $index => $image) {
                try {
                    $uploadedFile = Cloudinary::uploadApi()->upload($image->getRealPath(), [
                        'folder' => 'computer-store-products',
                        'transformation' => [
                            'width' => 1000,
                            'height' => 1000,
                            'crop' => 'limit',
                            'quality' => 'auto',
                            'fetch_format' => 'auto',
                        ],
                    ]);

                    $imageUrl = $uploadedFile['secure_url'];
                    $isPrimary = ($index === 0); // First image is primary

                    // Save to primary image URL for quick access
                    if ($isPrimary) {
                        $primaryImageUrl = $imageUrl;
                        $primaryImageThumbnailUrl = $imageUrl;
                    }

                    // Create product_image entry
                    $product->images()->create([
                        'image_url' => $imageUrl,
                        'image_thumbnail_url' => $imageUrl,
                        'sort_order' => $index,
                        'is_primary' => $isPrimary,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Admin: Failed to upload product image to Cloudinary: '.$e->getMessage(), [
                        'product_id' => $product->id,
                        'product_name' => $validated['name'],
                        'image_index' => $index,
                        'error' => $e->getMessage(),
                    ]);

                    // If any image fails, delete the product and all uploaded images
                    $product->images()->delete();
                    $product->delete();

                    return back()->withErrors([
                        'images' => 'Failed to upload one or more images. Please try again.',
                    ])->withInput();
                }
            }
        }

        // Update product with primary image URL for fast access (denormalization)
        $product->update([
            'image_url' => $primaryImageUrl,
            'image_thumbnail_url' => $primaryImageThumbnailUrl,
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully with '.$product->images()->count().' images!');
    }

    /**
     * Update the specified product in storage.
     * PUT /admin/products/{product}
     * 
     * Supports adding new images (up to 10 total per product)
     * New images will NOT be set as primary by default
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sku' => 'required|string|unique:products,sku,'.$product->id,
            'images' => 'nullable|array|max:10',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'stock' => 'required|integer|min:0',
            'specifications' => 'nullable|array',
            'specifications.processor' => 'nullable|string|max:255',
            'specifications.gpu' => 'nullable|string|max:255',
            'specifications.ram' => 'nullable|string|max:100',
            'specifications.storage' => 'nullable|string|max:255',
            'specifications.display' => 'nullable|string|max:255',
            'specifications.keyboard' => 'nullable|string|max:255',
            'specifications.battery' => 'nullable|string|max:255',
            'specifications.warranty' => 'nullable|string|max:255',
            'specifications.condition' => 'nullable|string|in:excellent,good,fair',
            'specifications.extras' => 'nullable|string|max:500',
            'specifications.original_price' => 'nullable|numeric|min:0',
            'specifications.features' => 'nullable|string|max:1000',
        ]);

        // Handle new image uploads if provided
        if ($request->hasFile('images')) {
            $currentImageCount = $product->images()->count();
            $newImagesCount = count($request->file('images'));

            // Check if total would exceed 10 images
            if ($currentImageCount + $newImagesCount > 10) {
                return back()->withErrors([
                    'images' => "Cannot exceed 10 images per product. You have {$currentImageCount} images and tried to add {$newImagesCount} more.",
                ])->withInput();
            }

            $images = $request->file('images');
            $nextSortOrder = $product->images()->max('sort_order') + 1;

            foreach ($images as $index => $image) {
                try {
                    $uploadedFile = Cloudinary::uploadApi()->upload($image->getRealPath(), [
                        'folder' => 'computer-store-products',
                        'transformation' => [
                            'width' => 1000,
                            'height' => 1000,
                            'crop' => 'limit',
                            'quality' => 'auto',
                            'fetch_format' => 'auto',
                        ],
                    ]);

                    $imageUrl = $uploadedFile['secure_url'];

                    // Create product_image entry (NOT primary by default)
                    $product->images()->create([
                        'image_url' => $imageUrl,
                        'image_thumbnail_url' => $imageUrl,
                        'sort_order' => $nextSortOrder + $index,
                        'is_primary' => false,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Admin: Failed to upload product image to Cloudinary during update: '.$e->getMessage(), [
                        'product_id' => $product->id,
                        'product_name' => $validated['name'],
                        'image_index' => $index,
                        'error' => $e->getMessage(),
                    ]);

                    return back()->withErrors([
                        'images' => 'Failed to upload one or more images. Please try again.',
                    ])->withInput();
                }
            }

            // Ensure primary image URL in products table is still valid
            $primaryImage = $product->images()->where('is_primary', true)->first();
            if ($primaryImage) {
                $validated['image_url'] = $primaryImage->image_url;
                $validated['image_thumbnail_url'] = $primaryImage->image_thumbnail_url;
            }
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified product from storage.
     * DELETE /admin/products/{product}
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }
}
