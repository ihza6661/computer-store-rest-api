<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'totalProducts' => \App\Models\Product::count(),
                'totalCategories' => \App\Models\Category::count(),
                'newContacts' => \App\Models\Contact::where('status', 'new')->count(),
                'totalUsers' => \App\Models\User::count(),
            ],
            'recentContacts' => \App\Models\Contact::orderBy('created_at', 'desc')
                ->limit(5)
                ->get(['id', 'name', 'email', 'category', 'status', 'created_at']),
        ]);
    })->name('admin.dashboard');

    // Products management
    Route::get('/admin/products', function () {
        return Inertia::render('Admin/Products/Index');
    })->name('admin.products.index');

    Route::get('/admin/products/create', function () {
        $categories = \App\Models\Category::select('id', 'name')->get();

        return Inertia::render('Admin/Products/Create', [
            'categories' => $categories,
        ]);
    })->name('admin.products.create');

    Route::get('/admin/products/{product}/edit', function ($product) {
        $productData = \App\Models\Product::with('category')->findOrFail($product);
        $categories = \App\Models\Category::select('id', 'name')->get();

        return Inertia::render('Admin/Products/Edit', [
            'product' => $productData,
            'categories' => $categories,
        ]);
    })->name('admin.products.edit');

    // Categories management
    Route::get('/admin/categories', function () {
        return Inertia::render('Admin/Categories/Index');
    })->name('admin.categories.index');

    Route::get('/admin/categories/create', function () {
        return Inertia::render('Admin/Categories/Create');
    })->name('admin.categories.create');

    Route::get('/admin/categories/{category}/edit', function ($category) {
        $categoryData = \App\Models\Category::findOrFail($category);

        return Inertia::render('Admin/Categories/Edit', [
            'category' => $categoryData,
        ]);
    })->name('admin.categories.edit');

    // Contacts management
    Route::get('/admin/contacts', function () {
        return Inertia::render('Admin/Contacts/Index', [
            'contacts' => \App\Models\Contact::paginate(15),
        ]);
    })->name('admin.contacts.index');

    Route::get('/admin/contacts/{contact}', function ($contact) {
        $contactData = \App\Models\Contact::findOrFail($contact);

        // Mark as read when viewing
        if ($contactData->status === 'new') {
            $contactData->update(['status' => 'read']);
        }

        return Inertia::render('Admin/Contacts/Show', [
            'contact' => $contactData,
        ]);
    })->name('admin.contacts.show');

    // Users management
    Route::get('/admin/users', function () {
        return Inertia::render('Admin/Users/Index', [
            'users' => \App\Models\User::select('id', 'name', 'email', 'role', 'created_at')
                ->paginate(15),
        ]);
    })->name('admin.users.index');

    Route::get('/admin/users/{user}/edit', function ($user) {
        $userData = \App\Models\User::findOrFail($user);

        return Inertia::render('Admin/Users/Edit', [
            'user' => $userData,
        ]);
    })->name('admin.users.edit');

    // Products CRUD routes (POST/PUT/DELETE)
    Route::post('/admin/products', [ProductController::class, 'store'])
        ->name('admin.products.store');
    Route::post('/admin/products/{product}', [ProductController::class, 'update'])
        ->name('admin.products.update');
    Route::delete('/admin/products/{product}', [ProductController::class, 'destroy'])
        ->name('admin.products.destroy');

    // Categories CRUD routes (POST/PUT/DELETE)
    Route::post('/admin/categories', [CategoryController::class, 'store'])
        ->name('admin.categories.store');
    Route::post('/admin/categories/{category}', [CategoryController::class, 'update'])
        ->name('admin.categories.update');
    Route::delete('/admin/categories/{category}', [CategoryController::class, 'destroy'])
        ->name('admin.categories.destroy');

    // Users CRUD routes (POST/PUT/DELETE)
    Route::post('/admin/users', [UserController::class, 'store'])
        ->name('admin.users.store');
    Route::post('/admin/users/{user}', [UserController::class, 'update'])
        ->name('admin.users.update');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])
        ->name('admin.users.destroy');

    // Contacts reply route (PUT)
    Route::post('/admin/contacts/{contact}', [ContactController::class, 'update'])
        ->name('admin.contacts.update');
});
