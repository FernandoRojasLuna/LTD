<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $projects = Project::with('technologies')->ordered()->get();
        return response()->json($projects);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'full_description' => 'nullable|string',
            'image' => 'required|string',
            'gallery' => 'nullable|array',
            'status' => 'required|in:development,deployed,paused,completed',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'url' => 'nullable|url',
            'repository' => 'nullable|url',
            'features' => 'nullable|array',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'order' => 'integer|min:0',
            'technologies' => 'nullable|array',
            'technologies.*' => 'exists:technologies,id'
        ]);

        $technologies = $validated['technologies'] ?? [];
        unset($validated['technologies']);

        $project = Project::create($validated);
        
        if (!empty($technologies)) {
            $project->technologies()->sync($technologies);
        }

        return response()->json($project->load('technologies'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project): JsonResponse
    {
        return response()->json($project->load('technologies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'full_description' => 'nullable|string',
            'image' => 'required|string',
            'gallery' => 'nullable|array',
            'status' => 'required|in:development,deployed,paused,completed',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'url' => 'nullable|url',
            'repository' => 'nullable|url',
            'features' => 'nullable|array',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'order' => 'integer|min:0',
            'technologies' => 'nullable|array',
            'technologies.*' => 'exists:technologies,id'
        ]);

        $technologies = $validated['technologies'] ?? [];
        unset($validated['technologies']);

        $project->update($validated);
        
        if (isset($request->technologies)) {
            $project->technologies()->sync($technologies);
        }

        return response()->json($project->load('technologies'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project): JsonResponse
    {
        $project->delete();
        return response()->json(null, 204);
    }

    /**
     * Get active projects for public display
     */
    public function public(): JsonResponse
    {
        $projects = Project::active()->with('technologies')->ordered()->get();
        return response()->json($projects);
    }

    /**
     * Get projects by status
     */
    public function byStatus(string $status): JsonResponse
    {
        $projects = Project::active()->byStatus($status)->with('technologies')->ordered()->get();
        return response()->json($projects);
    }

    /**
     * Get featured projects
     */
    public function featured(): JsonResponse
    {
        $projects = Project::active()->featured()->with('technologies')->ordered()->get();
        return response()->json($projects);
    }
}
