<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $banners = Banner::ordered()->get();
        return response()->json($banners);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'required', // Puede ser file o string (URL)
            'description' => 'nullable|string',
            'link' => 'nullable|url',
            'order' => 'integer|min:0',
            'is_active' => 'nullable|boolean'
        ]);

        // Convertir is_active a boolean si viene como string
        if (isset($validated['is_active'])) {
            $validated['is_active'] = filter_var($validated['is_active'], FILTER_VALIDATE_BOOLEAN);
        } else {
            $validated['is_active'] = true; // valor por defecto
        }

        // Manejar la imagen
        if ($request->hasFile('image')) {
            $imagePath = $this->uploadImage($request->file('image'));
            $validated['image'] = $imagePath;
        } elseif ($request->has('image') && filter_var($request->image, FILTER_VALIDATE_URL)) {
            $validated['image'] = $request->image;
        } else {
            return response()->json(['error' => 'Se requiere una imagen válida'], 422);
        }

        $banner = Banner::create($validated);
        return response()->json($banner, 201);
    }

    /**
     * Upload image file and return path
     */
    private function uploadImage($file): string
    {
        // Validar el archivo
        if (!$file->isValid()) {
            throw new \Exception('Archivo no válido');
        }

        // Validar tipo de archivo
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file->getMimeType(), $allowedTypes)) {
            throw new \Exception('Tipo de archivo no permitido');
        }

        // Validar tamaño (máximo 5MB)
        if ($file->getSize() > 5 * 1024 * 1024) {
            throw new \Exception('El archivo es muy grande. Máximo 5MB');
        }

        // Generar nombre único
        $extension = $file->getClientOriginalExtension();
        $filename = Str::random(40) . '.' . $extension;

        // Guardar en storage/app/public/banners (accesible vía /storage/banners)
        $path = Storage::disk('public')->putFileAs('banners', $file, $filename);

        // Retornar URL relativa
        return '/storage/banners/' . $filename;
    }

    /**
     * Display the specified resource.
     */
    public function show(Banner $banner): JsonResponse
    {
        return response()->json($banner);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Banner $banner): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'sometimes|required', // Puede ser file o string (URL)
            'description' => 'nullable|string',
            'link' => 'nullable|url',
            'order' => 'integer|min:0',
            'is_active' => 'nullable|boolean'
        ]);

        // Convertir is_active a boolean si viene como string
        if (isset($validated['is_active'])) {
            $validated['is_active'] = filter_var($validated['is_active'], FILTER_VALIDATE_BOOLEAN);
        }

        // Manejar la imagen si se proporciona una nueva
        if ($request->hasFile('image')) {
            // Eliminar imagen anterior si existe y no es una URL
            if ($banner->image && !filter_var($banner->image, FILTER_VALIDATE_URL)) {
                $imagePath = str_replace('/storage/', '', $banner->image);
                Storage::disk('public')->delete($imagePath);
            }
            
            $imagePath = $this->uploadImage($request->file('image'));
            $validated['image'] = $imagePath;
        } elseif ($request->has('image') && filter_var($request->image, FILTER_VALIDATE_URL)) {
            $validated['image'] = $request->image;
        }

        $banner->update($validated);
        return response()->json($banner);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner): JsonResponse
    {
        // Eliminar imagen si existe y no es una URL
        if ($banner->image && !filter_var($banner->image, FILTER_VALIDATE_URL)) {
            $imagePath = str_replace('/storage/', '', $banner->image);
            Storage::disk('public')->delete($imagePath);
        }
        
        $banner->delete();
        return response()->json(null, 204);
    }

    /**
     * Get active banners for public display
     */
    public function public(): JsonResponse
    {
        $banners = Banner::active()->ordered()->get();
        return response()->json($banners);
    }
}
