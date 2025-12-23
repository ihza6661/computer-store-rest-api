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
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sku' => 'required|string|unique:products,sku',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'stock' => 'required|integer|min:0',
        ]);

        // Handle image upload to Cloudinary
        if ($request->hasFile('image')) {
            try {
                $uploadedFile = Cloudinary::uploadApi()->upload($request->file('image')->getRealPath(), [
                    'folder' => 'r-tech-products',
                    'transformation' => [
                        'width' => 1000,
                        'height' => 1000,
                        'crop' => 'limit',
                        'quality' => 'auto',
                        'fetch_format' => 'auto',
                    ],
                ]);

                $validated['image_url'] = $uploadedFile['secure_url'];
                $validated['image_thumbnail_url'] = $uploadedFile['secure_url'];
            } catch (\Exception $e) {
                Log::error('Failed to upload product image to Cloudinary: '.$e->getMessage(), [
                    'product_name' => $validated['name'],
                    'sku' => $validated['sku'],
                    'error' => $e->getMessage(),
                ]);

                return back()->withErrors([
                    'image' => 'Failed to upload image. Please try again.',
                ])->withInput();
            }
        }

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully!');
    }

    /**
     * Update the specified product in storage.
     * PUT /admin/products/{product}
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sku' => 'required|string|unique:products,sku,'.$product->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'stock' => 'required|integer|min:0',
        ]);

        // Handle image upload to Cloudinary if provided
        if ($request->hasFile('image')) {
            try {
                $uploadedFile = Cloudinary::uploadApi()->upload($request->file('image')->getRealPath(), [
                    'folder' => 'r-tech-products',
                    'transformation' => [
                        'width' => 1000,
                        'height' => 1000,
                        'crop' => 'limit',
                        'quality' => 'auto',
                        'fetch_format' => 'auto',
                    ],
                ]);

                $validated['image_url'] = $uploadedFile['secure_url'];
                $validated['image_thumbnail_url'] = $uploadedFile['secure_url'];
            } catch (\Exception $e) {
                Log::error('Failed to upload product image to Cloudinary during update: '.$e->getMessage(), [
                    'product_id' => $product->id,
                    'product_name' => $validated['name'],
                    'sku' => $validated['sku'],
                    'error' => $e->getMessage(),
                ]);

                return back()->withErrors([
                    'image' => 'Failed to upload image. Please try again.',
                ])->withInput();
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
