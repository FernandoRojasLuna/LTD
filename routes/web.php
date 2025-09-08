<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Test routes for banners without auth
Route::get('/test-banners', function () {
    return response()->file(public_path('test-banners.html'));
})->name('test.banners');

Route::get('/simple-banners', function () {
    return response()->file(public_path('test-banners.html'));
})->name('simple.banners');

// Banner API routes for testing (no auth required)
Route::prefix('test-banners-api')->group(function () {
    Route::get('/', [App\Http\Controllers\Api\BannerMockController::class, 'index'])->name('test.banners.api');
    Route::post('/store', [App\Http\Controllers\Api\BannerMockController::class, 'store'])->name('test.banners.store');
    Route::put('/{banner}', [App\Http\Controllers\Api\BannerMockController::class, 'update'])->name('test.banners.update');
    Route::delete('/{banner}', [App\Http\Controllers\Api\BannerMockController::class, 'destroy'])->name('test.banners.destroy');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    
    // Admin routes (protected)
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/banners', function () {
            return Inertia::render('Admin/BannersIntegrated');
        })->name('banners');
        
        Route::get('/contents', function () {
            return Inertia::render('Admin/Contents');
        })->name('contents');
        
        Route::get('/projects', function () {
            return Inertia::render('Admin/Projects');
        })->name('projects');
        
        Route::get('/technologies', function () {
            return Inertia::render('Admin/Technologies');
        })->name('technologies');
        
        Route::get('/staff', function () {
            return Inertia::render('Admin/Staff');
        })->name('staff');
        
        Route::get('/clients', function () {
            return Inertia::render('Admin/Clients');
        })->name('clients');

        Route::get('/contacts', function () {
            return Inertia::render('Admin/ContactSettings');
        })->name('contacts');
    });
});
