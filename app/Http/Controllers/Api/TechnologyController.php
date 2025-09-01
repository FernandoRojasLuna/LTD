<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Technology;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TechnologyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $technologies = Technology::all();
        return response()->json($technologies);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'color' => 'string|max:7',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $technology = Technology::create($validated);
        return response()->json($technology, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Technology $technology): JsonResponse
    {
        return response()->json($technology);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Technology $technology): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'color' => 'string|max:7',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $technology->update($validated);
        return response()->json($technology);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Technology $technology): JsonResponse
    {
        $technology->delete();
        return response()->json(null, 204);
    }

    /**
     * Get active technologies for public display
     */
    public function public(): JsonResponse
    {
        $technologies = Technology::active()->get();
        return response()->json($technologies);
    }
}
