<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

/*
|--------------------------------------------------------------------------
| 🔒 Bloquear ruta /register original
|--------------------------------------------------------------------------
| Si alguien intenta entrar a /register, devolverá error 404
*/
Route::match(['get','post'], '/register', function () {
    abort(404);
});

/*
|--------------------------------------------------------------------------
| 📝 Nueva ruta de registro protegida /registroltd
|--------------------------------------------------------------------------
*/
Route::get('/registroltd', [RegisteredUserController::class, 'create'])
    ->middleware(['guest'])
    ->name('register.custom');

Route::post('/registroltd', [RegisteredUserController::class, 'store'])
    ->middleware(['guest']);

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
        Route::get('/banners', fn() => Inertia::render('Admin/BannersIntegrated'))->name('banners');
        Route::get('/contents', fn() => Inertia::render('Admin/Contents'))->name('contents');
        Route::get('/projects', fn() => Inertia::render('Admin/Projects'))->name('projects');
        Route::get('/technologies', fn() => Inertia::render('Admin/Technologies'))->name('technologies');
        Route::get('/staff', fn() => Inertia::render('Admin/Staff'))->name('staff');
        Route::get('/clients', fn() => Inertia::render('Admin/Clients'))->name('clients');
        Route::get('/contacts', fn() => Inertia::render('Admin/ContactSettings'))->name('contacts');

        // Admin API for contacts listing and marking
        Route::get('/contacts/list', [App\Http\Controllers\ContactController::class, 'index'])->name('contacts.list');
        Route::post('/contacts/{id}/read', [App\Http\Controllers\ContactController::class, 'markRead'])->name('contacts.read');
    });
});

// Public contact form endpoint
Route::post('/contact', [App\Http\Controllers\ContactController::class, 'store'])->name('contact.store');
