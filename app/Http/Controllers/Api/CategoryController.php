<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories (no pagination - should be small list).
     * GET /api/categories
     */
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    /**
     * Store a newly created category in storage.
     * POST /api/admin/categories (requires auth + admin role)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        // Auto-generate slug from name
        $validated['slug'] = Str::slug($validated['name']);

        $category = Category::create($validated);

        return response()->json($category, Response::HTTP_CREATED);
    }

    /**
     * Display the specified category.
     * GET /api/categories/{id}
     */
    public function show(Category $category)
    {
        return response()->json($category->load('products'));
    }

    /**
     * Update the specified category in storage.
     * PUT /api/admin/categories/{id} (requires auth + admin role)
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        // Auto-generate slug from name
        $validated['slug'] = Str::slug($validated['name']);

        $category->update($validated);

        return response()->json($category);
    }

    /**
     * Remove the specified category from storage.
     * DELETE /api/admin/categories/{id} (requires auth + admin role)
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
