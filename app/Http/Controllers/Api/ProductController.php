<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of products with pagination and filtering.
     * GET /api/products?page=1&per_page=12&category_id=2&search=query&sort_by=name&order=asc&min_price=1000000&max_price=5000000&condition=New&brand[]=ASUS&brand[]=Lenovo
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // Search by name, description, or SKU
        if ($request->has('search') && ! empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->has('category_id') && ! empty($request->category_id)) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by brand (supports multiple brands)
        if ($request->has('brand') && ! empty($request->brand)) {
            $brands = is_array($request->brand) ? $request->brand : [$request->brand];
            $query->whereIn('brand', $brands);
        }

        // Filter by price range
        if ($request->has('min_price') && ! empty($request->min_price)) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price') && ! empty($request->max_price)) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter by condition (stored in specifications JSON)
        if ($request->has('condition') && ! empty($request->condition)) {
            $query->whereJsonContains('specifications->condition', $request->condition);
        }

        // Filter by stock availability
        if ($request->has('in_stock') && $request->in_stock === 'true') {
            $query->where('stock', '>', 0);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $order = $request->get('order', 'desc');
        $query->orderBy($sortBy, $order);

        // Pagination
        $perPage = $request->get('per_page', 12);
        $products = $query->paginate($perPage);

        return ProductResource::collection($products)
            ->response()
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Store a newly created product in storage.
     * POST /api/admin/products (requires auth + admin role)
     * 
     * Supports multiple image uploads (max 10 images)
     * First image becomes the primary image automatically
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
                    Log::error('API: Failed to upload product image to Cloudinary: '.$e->getMessage(), [
                        'product_id' => $product->id,
                        'product_name' => $validated['name'],
                        'image_index' => $index,
                        'error' => $e->getMessage(),
                    ]);

                    // If any image fails, delete the product and all uploaded images
                    $product->images()->delete();
                    $product->delete();

                    return response()->json([
                        'message' => 'Failed to upload one or more images. Please try again.',
                        'errors' => ['images' => ['Failed to upload images to Cloudinary.']],
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                }
            }
        }

        // Update product with primary image URL for fast access
        $product->update([
            'image_url' => $primaryImageUrl,
            'image_thumbnail_url' => $primaryImageThumbnailUrl,
        ]);

        return new ProductResource($product->load('images'));
    }

    /**
     * Display the specified product.
     * GET /api/products/{id}
     */
    public function show(Product $product)
    {
        return new ProductResource($product->load(['category', 'images']));
    }

    /**
     * Update the specified product in storage.
     * PUT /api/admin/products/{id} (requires auth + admin role)
     * 
     * Supports adding multiple new images (max 10 total images per product)
     * If images are provided, they will be added to existing images
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
        ]);

        // Handle new image uploads if provided
        if ($request->hasFile('images')) {
            $currentImageCount = $product->images()->count();
            $newImagesCount = count($request->file('images'));
            
            // Check if total would exceed 10 images
            if ($currentImageCount + $newImagesCount > 10) {
                return response()->json([
                    'message' => 'Cannot exceed 10 images per product.',
                    'errors' => ['images' => ["Product already has {$currentImageCount} images. Cannot add {$newImagesCount} more."]],
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
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

                    // Create product_image entry
                    $product->images()->create([
                        'image_url' => $imageUrl,
                        'image_thumbnail_url' => $imageUrl,
                        'sort_order' => $nextSortOrder + $index,
                        'is_primary' => false, // New images are not primary by default
                    ]);
                } catch (\Exception $e) {
                    Log::error('API: Failed to upload product image to Cloudinary during update: '.$e->getMessage(), [
                        'product_id' => $product->id,
                        'product_name' => $validated['name'],
                        'image_index' => $index,
                        'error' => $e->getMessage(),
                    ]);

                    return response()->json([
                        'message' => 'Failed to upload one or more images. Please try again.',
                        'errors' => ['images' => ['Failed to upload images to Cloudinary.']],
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                }
            }

            // Update primary image URL in products table for fast access
            $primaryImage = $product->images()->where('is_primary', true)->first();
            if ($primaryImage) {
                $validated['image_url'] = $primaryImage->image_url;
                $validated['image_thumbnail_url'] = $primaryImage->image_thumbnail_url;
            }
        }

        $product->update($validated);

        return new ProductResource($product->load('images'));
    }

    /**
     * Remove the specified product from storage.
     * DELETE /api/admin/products/{id} (requires auth + admin role)
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
