<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of products with pagination and filtering.
     * GET /api/products?page=1&per_page=12&category_id=2&search=query&sort_by=name&order=asc
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // Search by name, description, or SKU
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
        }

        // Filter by category
        if ($request->has('category_id') && !empty($request->category_id)) {
            $query->where('category_id', $request->category_id);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $order = $request->get('order', 'desc');
        $query->orderBy($sortBy, $order);

        // Pagination
        $perPage = $request->get('per_page', 12);
        $products = $query->paginate($perPage);

        return response()->json($products);
    }

    /**
     * Store a newly created product in storage.
     * POST /api/admin/products (requires auth + admin role)
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
            'specifications' => 'nullable|array',
        ]);

        // Handle image upload
        $imagePath = $request->file('image')->store('products', 'public');
        $validated['image_url'] = asset('storage/' . $imagePath);

        $product = Product::create($validated);

        return response()->json($product, Response::HTTP_CREATED);
    }

    /**
     * Display the specified product.
     * GET /api/products/{id}
     */
    public function show(Product $product)
    {
        return response()->json($product->load('category'));
    }

    /**
     * Update the specified product in storage.
     * PUT /api/admin/products/{id} (requires auth + admin role)
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'stock' => 'required|integer|min:0',
            'specifications' => 'nullable|array',
        ]);

        // Handle image upload if provided
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image_url'] = asset('storage/' . $imagePath);
        }

        $product->update($validated);

        return response()->json($product);
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
