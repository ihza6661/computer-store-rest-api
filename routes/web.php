<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LogoutController::class, 'store'])->name('logout');
});

// Admin routes (require auth + admin role)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', function () {
        return Inertia::render('Admin/Dashboard');
    })->name('admin.dashboard');

    // Products management
    Route::get('/admin/products', function () {
        return Inertia::render('Admin/Products/Index');
    })->name('admin.products.index');

    Route::get('/admin/products/create', function () {
        return Inertia::render('Admin/Products/Create');
    })->name('admin.products.create');

    Route::get('/admin/products/{product}/edit', function ($product) {
        return Inertia::render('Admin/Products/Edit', ['productId' => $product]);
    })->name('admin.products.edit');

    // Categories management
    Route::get('/admin/categories', function () {
        return Inertia::render('Admin/Categories/Index');
    })->name('admin.categories.index');

    Route::get('/admin/categories/create', function () {
        return Inertia::render('Admin/Categories/Create');
    })->name('admin.categories.create');

    Route::get('/admin/categories/{category}/edit', function ($category) {
        return Inertia::render('Admin/Categories/Edit', ['categoryId' => $category]);
    })->name('admin.categories.edit');

    // Contacts management
    Route::get('/admin/contacts', function () {
        return Inertia::render('Admin/Contacts/Index');
    })->name('admin.contacts.index');

    Route::get('/admin/contacts/{contact}', function ($contact) {
        return Inertia::render('Admin/Contacts/Show', ['contactId' => $contact]);
    })->name('admin.contacts.show');

    // Users management
    Route::get('/admin/users', function () {
        return Inertia::render('Admin/Users/Index');
    })->name('admin.users.index');

    Route::get('/admin/users/{user}/edit', function ($user) {
        return Inertia::render('Admin/Users/Edit', ['userId' => $user]);
    })->name('admin.users.edit');
});
