<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

// Public API routes (with standard rate limiting: 60 req/min)
Route::middleware(['throttle:api'])->group(function () {
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{product}', [ProductController::class, 'show']);
    Route::get('/categories', [CategoryController::class, 'index']);
});

// Contact form with stricter rate limiting (3 req/min)
Route::middleware(['throttle:contacts'])->group(function () {
    Route::post('/contacts', [ContactController::class, 'store']);
});

// Admin API routes (require session-based authentication + higher rate limit: 120 req/min)
Route::middleware(['auth:web', 'throttle:api-authenticated'])->group(function () {
    // Products management (standard authenticated limit)
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

    // Users management
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
});
