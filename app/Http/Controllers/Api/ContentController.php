<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ContentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $contents = Content::all();
        return response()->json($contents);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'string|in:general,service,technology,area',
            'image' => 'nullable|string',
            'is_featured' => 'boolean',
            'is_active' => 'boolean'
        ]);

        $content = Content::create($validated);
        return response()->json($content, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Content $content): JsonResponse
    {
        return response()->json($content);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Content $content): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'string|in:general,service,technology,area',
            'image' => 'nullable|string',
            'is_featured' => 'boolean',
            'is_active' => 'boolean'
        ]);

        $content->update($validated);
        return response()->json($content);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Content $content): JsonResponse
    {
        $content->delete();
        return response()->json(null, 204);
    }

    /**
     * Get active content for public display
     */
    public function public(): JsonResponse
    {
        $contents = Content::active()->get();
        return response()->json($contents);
    }

    /**
     * Get featured content
     */
    public function featured(): JsonResponse
    {
        $contents = Content::active()->featured()->get();
        return response()->json($contents);
    }

    /**
     * Get content by type
     */
    public function byType(string $type): JsonResponse
    {
        $contents = Content::active()->ofType($type)->get();
        return response()->json($contents);
    }
}
