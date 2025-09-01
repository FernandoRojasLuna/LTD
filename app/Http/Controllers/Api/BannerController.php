<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

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
            'image' => 'required|string',
            'description' => 'nullable|string',
            'link' => 'nullable|url',
            'order' => 'integer|min:0',
            'is_active' => 'boolean'
        ]);

        $banner = Banner::create($validated);
        return response()->json($banner, 201);
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
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'required|string',
            'description' => 'nullable|string',
            'link' => 'nullable|url',
            'order' => 'integer|min:0',
            'is_active' => 'boolean'
        ]);

        $banner->update($validated);
        return response()->json($banner);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner): JsonResponse
    {
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
