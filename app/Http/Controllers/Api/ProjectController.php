<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

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
            'image' => 'required', // Puede ser file o string (URL)
            'status' => 'required|in:development,deployed,paused,completed',
            'start_date' => 'nullable|date', // Opcional: Fecha de inicio del proyecto
            'end_date' => 'nullable|date|after_or_equal:start_date', // Opcional: Fecha de finalización
            'url' => 'nullable|url',
            'repository' => 'nullable|url',
            'features' => 'nullable|array',
            'features.*' => 'string|min:1', // Asegurar que cada feature no esté vacía
            'technologies' => 'nullable|array',
            'technologies.*' => 'integer|exists:technologies,id',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'order' => 'integer|min:0'
        ]);

        // Convertir booleans si vienen como string
        if (isset($validated['is_featured'])) {
            $validated['is_featured'] = filter_var($validated['is_featured'], FILTER_VALIDATE_BOOLEAN);
        } else {
            $validated['is_featured'] = false;
        }

        if (isset($validated['is_active'])) {
            $validated['is_active'] = filter_var($validated['is_active'], FILTER_VALIDATE_BOOLEAN);
        } else {
            $validated['is_active'] = true;
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

        $project = Project::create($validated);

        // Asociar tecnologías si se proporcionan
        if (isset($validated['technologies'])) {
            $project->technologies()->sync($validated['technologies']);
        }

        return response()->json($project->load('technologies'), 201);
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
        
        // Crear directorio si no existe
        $uploadPath = public_path('storage/projects');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Mover archivo
        $file->move($uploadPath, $filename);

        // Retornar URL relativa
        return '/storage/projects/' . $filename;
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
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'full_description' => 'nullable|string',
            'image' => 'sometimes|required', // Puede ser file o string (URL)
            'status' => 'sometimes|required|in:development,deployed,paused,completed',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'url' => 'nullable|url',
            'repository' => 'nullable|url',
            'features' => 'nullable|array',
            'features.*' => 'string|min:1', // Asegurar que cada feature no esté vacía
            'technologies' => 'nullable|array',
            'technologies.*' => 'integer|exists:technologies,id',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'order' => 'integer|min:0'
        ]);

        // Convertir booleans si vienen como string
        if (isset($validated['is_featured'])) {
            $validated['is_featured'] = filter_var($validated['is_featured'], FILTER_VALIDATE_BOOLEAN);
        }

        if (isset($validated['is_active'])) {
            $validated['is_active'] = filter_var($validated['is_active'], FILTER_VALIDATE_BOOLEAN);
        }

        // Manejar la imagen si se proporciona una nueva
        if ($request->hasFile('image')) {
            // Eliminar imagen anterior si existe y no es una URL
            if ($project->image && !filter_var($project->image, FILTER_VALIDATE_URL)) {
                $oldImagePath = public_path($project->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            
            $imagePath = $this->uploadImage($request->file('image'));
            $validated['image'] = $imagePath;
        } elseif ($request->has('image') && filter_var($request->image, FILTER_VALIDATE_URL)) {
            $validated['image'] = $request->image;
        }

        $project->update($validated);

        // Actualizar tecnologías si se proporcionan
        if (isset($validated['technologies'])) {
            $project->technologies()->sync($validated['technologies']);
        }

        return response()->json($project->load('technologies'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project): JsonResponse
    {
        // Eliminar imagen si existe y no es una URL
        if ($project->image && !filter_var($project->image, FILTER_VALIDATE_URL)) {
            $imagePath = public_path($project->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
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
