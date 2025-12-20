<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ContactController;

// Public API routes (no authentication required)
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);

Route::get('/categories', [CategoryController::class, 'index']);

Route::post('/contacts', [ContactController::class, 'store']);

// Admin API routes (require session-based authentication)
Route::middleware(['auth:web'])->group(function () {
    // Products management
    Route::post('/admin/products', [ProductController::class, 'store']);
    Route::put('/admin/products/{product}', [ProductController::class, 'update']);
    Route::delete('/admin/products/{product}', [ProductController::class, 'destroy']);

    // Categories management
    Route::post('/admin/categories', [CategoryController::class, 'store']);
    Route::put('/admin/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/admin/categories/{category}', [CategoryController::class, 'destroy']);

    // Contacts management
    Route::get('/admin/contacts', [ContactController::class, 'index']);
    Route::get('/admin/contacts/{contact}', [ContactController::class, 'show']);
    Route::put('/admin/contacts/{contact}', [ContactController::class, 'update']);
    Route::delete('/admin/contacts/{contact}', [ContactController::class, 'destroy']);
});
