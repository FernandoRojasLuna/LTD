<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BannerMockController extends Controller
{
    private $banners = [
        [
            'id' => 1,
            'title' => 'Laboratorio de Transformación Digital',
            'subtitle' => 'Innovación y tecnología para el futuro',
            'image' => 'https://images.unsplash.com/photo-1581472723648-909f4851d4ae?w=1200&h=600&fit=crop',
            'description' => 'Desarrollamos soluciones tecnológicas innovadoras que transforman negocios y crean impacto social.',
            'link' => '#about',
            'order' => 1,
            'is_active' => true,
            'created_at' => '2025-09-01T10:00:00Z',
            'updated_at' => '2025-09-01T10:00:00Z'
        ],
        [
            'id' => 2,
            'title' => 'Proyectos Innovadores',
            'subtitle' => 'Creando el futuro con tecnología',
            'image' => 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?w=1200&h=600&fit=crop',
            'description' => 'Explora nuestros proyectos de vanguardia en inteligencia artificial, IoT y desarrollo web.',
            'link' => '#projects',
            'order' => 2,
            'is_active' => true,
            'created_at' => '2025-09-01T10:00:00Z',
            'updated_at' => '2025-09-01T10:00:00Z'
        ],
        [
            'id' => 3,
            'title' => 'Únete a Nosotros',
            'subtitle' => 'Sé parte de la innovación',
            'image' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=1200&h=600&fit=crop',
            'description' => 'Únete a nuestro equipo de desarrolladores, investigadores y visionarios tecnológicos.',
            'link' => '#contact',
            'order' => 3,
            'is_active' => true,
            'created_at' => '2025-09-01T10:00:00Z',
            'updated_at' => '2025-09-01T10:00:00Z'
        ]
    ];

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json($this->banners);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'required|string',
            'description' => 'nullable|string',
            'link' => 'nullable|string',
            'order' => 'integer|min:0',
            'is_active' => 'boolean'
        ]);

        $newBanner = array_merge($validated, [
            'id' => count($this->banners) + 1,
            'created_at' => now()->toISOString(),
            'updated_at' => now()->toISOString()
        ]);

        return response()->json($newBanner, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        $banner = collect($this->banners)->firstWhere('id', (int)$id);
        
        if (!$banner) {
            return response()->json(['message' => 'Banner not found'], 404);
        }
        
        return response()->json($banner);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'required|string',
            'description' => 'nullable|string',
            'link' => 'nullable|string',
            'order' => 'integer|min:0',
            'is_active' => 'boolean'
        ]);

        $banner = collect($this->banners)->firstWhere('id', (int)$id);
        
        if (!$banner) {
            return response()->json(['message' => 'Banner not found'], 404);
        }

        $updatedBanner = array_merge($banner, $validated, [
            'updated_at' => now()->toISOString()
        ]);

        return response()->json($updatedBanner);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        return response()->json(null, 204);
    }

    /**
     * Get active banners for public display
     */
    public function public(): JsonResponse
    {
        $activeBanners = collect($this->banners)->where('is_active', true)->values();
        return response()->json($activeBanners);
    }
}
