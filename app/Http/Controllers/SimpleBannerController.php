<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SimpleBannerController extends Controller
{
    private function getDefaultBanners()
    {
        return [
            [
                'id' => 1,
                'title' => 'Laboratorio de Transformación Digital',
                'subtitle' => 'Innovación y tecnología para el futuro',
                'image' => 'https://images.unsplash.com/photo-1518709268805-4e9042af2176?w=1200&h=600&fit=crop',
                'description' => 'Descubre el futuro de la tecnología en nuestro laboratorio de innovación digital.',
                'order' => 1,
                'is_active' => true,
                'created_at' => '2025-09-01T10:00:00Z',
                'updated_at' => '2025-09-01T10:00:00Z'
            ],
            [
                'id' => 2,
                'title' => 'Proyectos Innovadores',
                'subtitle' => 'Creando el futuro con tecnología',
                'image' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=1200&h=600&fit=crop',
                'description' => 'Explora nuestros proyectos de vanguardia en inteligencia artificial, IoT y desarrollo web.',
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
                'order' => 3,
                'is_active' => true,
                'created_at' => '2025-09-01T10:00:00Z',
                'updated_at' => '2025-09-01T10:00:00Z'
            ]
        ];
    }

    private function getBannersFilePath()
    {
        return storage_path('app/test_banners.json');
    }

    private function getBanners()
    {
        $filePath = $this->getBannersFilePath();
        
        if (file_exists($filePath)) {
            $content = file_get_contents($filePath);
            $banners = json_decode($content, true);
            
            if ($banners && is_array($banners)) {
                return $banners;
            }
        }
        
        return $this->getDefaultBanners();
    }

    private function saveBanners($banners)
    {
        $filePath = $this->getBannersFilePath();
        file_put_contents($filePath, json_encode($banners, JSON_PRETTY_PRINT));
    }

    private function getNextAvailableOrder($banners)
    {
        // Obtener todos los órdenes existentes
        $existingOrders = [];
        foreach ($banners as $banner) {
            if (isset($banner['order']) && $banner['order'] > 0) {
                $existingOrders[] = (int)$banner['order'];
            }
        }
        
        // Ordenar los números existentes
        sort($existingOrders);
        
        // Encontrar el primer hueco disponible
        $expectedOrder = 1;
        foreach ($existingOrders as $order) {
            if ($order == $expectedOrder) {
                $expectedOrder++;
            } else if ($order > $expectedOrder) {
                // Encontramos un hueco
                break;
            }
        }
        
        return $expectedOrder;
    }

    private function getNextId()
    {
        $banners = $this->getBanners();
        $maxId = 0;
        foreach ($banners as $banner) {
            if ($banner['id'] > $maxId) {
                $maxId = $banner['id'];
            }
        }
        return $maxId + 1;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $banners = $this->getBanners();
        
        // Ordenar por el campo 'order'
        usort($banners, function($a, $b) {
            $orderA = isset($a['order']) ? (int)$a['order'] : 0;
            $orderB = isset($b['order']) ? (int)$b['order'] : 0;
            
            if ($orderA == $orderB) {
                // Si tienen el mismo orden, ordenar por ID
                return $a['id'] - $b['id'];
            }
            
            return $orderA - $orderB;
        });
        
        return response()->json($banners);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        // Simple validation
        if (!$request->has('title') || !$request->has('image')) {
            return response()->json(['error' => 'Title and image are required'], 400);
        }

        $banners = $this->getBanners();
        
        // Determinar el orden automáticamente si no se proporciona
        $order = $request->input('order');
        if (!$order || $order <= 0) {
            $order = $this->getNextAvailableOrder($banners);
        }
        
        $newBanner = [
            'id' => $this->getNextId(),
            'title' => $request->input('title'),
            'subtitle' => $request->input('subtitle'),
            'image' => $request->input('image'),
            'description' => $request->input('description'),
            'order' => $order,
            'is_active' => $request->input('is_active', true),
            'created_at' => now()->toISOString(),
            'updated_at' => now()->toISOString()
        ];

        $banners[] = $newBanner;
        $this->saveBanners($banners);

        return response()->json($newBanner, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        $banners = $this->getBanners();
        $banner = collect($banners)->firstWhere('id', (int)$id);
        
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
        $banners = $this->getBanners();
        $bannerIndex = null;
        
        foreach ($banners as $index => $banner) {
            if ($banner['id'] == (int)$id) {
                $bannerIndex = $index;
                break;
            }
        }
        
        if ($bannerIndex === null) {
            return response()->json(['message' => 'Banner not found'], 404);
        }

        $updatedBanner = array_merge($banners[$bannerIndex], [
            'title' => $request->input('title', $banners[$bannerIndex]['title']),
            'subtitle' => $request->input('subtitle', $banners[$bannerIndex]['subtitle']),
            'image' => $request->input('image', $banners[$bannerIndex]['image']),
            'description' => $request->input('description', $banners[$bannerIndex]['description']),
            'order' => $request->input('order', $banners[$bannerIndex]['order']),
            'is_active' => $request->input('is_active', $banners[$bannerIndex]['is_active']),
            'updated_at' => now()->toISOString()
        ]);

        $banners[$bannerIndex] = $updatedBanner;
        $this->saveBanners($banners);

        return response()->json($updatedBanner);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        $banners = $this->getBanners();
        $bannerIndex = null;
        
        foreach ($banners as $index => $banner) {
            if ($banner['id'] == (int)$id) {
                $bannerIndex = $index;
                break;
            }
        }
        
        if ($bannerIndex === null) {
            return response()->json(['message' => 'Banner not found'], 404);
        }

        array_splice($banners, $bannerIndex, 1);
        $this->saveBanners($banners);

        return response()->json(['message' => 'Banner deleted successfully'], 200);
    }
}
