<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TechnologyController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Rutas públicas (sin autenticación)
Route::prefix('public')->group(function () {
    Route::get('banners', [BannerController::class, 'public']);
    Route::get('contents', [ContentController::class, 'public']);
    Route::get('contents/featured', [ContentController::class, 'featured']);
    Route::get('contents/type/{type}', [ContentController::class, 'byType']);
    Route::get('projects', [ProjectController::class, 'public']);
    Route::get('projects/status/{status}', [ProjectController::class, 'byStatus']);
    Route::get('projects/featured', [ProjectController::class, 'featured']);
    Route::get('technologies', [TechnologyController::class, 'public']);
});

// Rutas API para administración (temporalmente sin autenticación para desarrollo)
Route::apiResource('banners', BannerController::class);
Route::apiResource('contents', ContentController::class);
Route::apiResource('projects', ProjectController::class);
Route::apiResource('technologies', TechnologyController::class);

// Rutas de prueba para banners (sin autenticación ni CSRF)
Route::prefix('test')->group(function () {
    Route::get('banners', [App\Http\Controllers\SimpleBannerController::class, 'index']);
    Route::post('banners', [App\Http\Controllers\SimpleBannerController::class, 'store']);
    Route::get('banners/{id}', [App\Http\Controllers\SimpleBannerController::class, 'show']);
    Route::put('banners/{id}', [App\Http\Controllers\SimpleBannerController::class, 'update']);
    Route::delete('banners/{id}', [App\Http\Controllers\SimpleBannerController::class, 'destroy']);
});
