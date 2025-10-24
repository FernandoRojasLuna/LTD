<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Technology;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

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
        try {
            // Validación condicional: si se envía archivo validar como file, si no permitir string (clase o ruta)
            $rules = [
                'name' => 'required|string|max:255',
                'color' => 'string|max:7',
                'description' => 'nullable|string',
                'is_active' => 'boolean'
            ];
            if ($request->hasFile('icon')) {
                $rules['icon'] = 'file|mimes:jpg,jpeg,png,gif,svg,webp|max:2048';
            } else {
                $rules['icon'] = 'nullable|string|max:255';
            }
            $validated = $request->validate($rules);

            // Si se subió un icono como archivo, guardarlo usando Storage (storage/app/public/technologies)
            if ($request->hasFile('icon')) {
                $file = $request->file('icon');
                $extension = $file->getClientOriginalExtension();
                $filename = \Illuminate\Support\Str::random(40) . '.' . $extension;
                
                // Guardar en storage/app/public/technologies (accesible vía /storage/technologies)
                $path = Storage::disk('public')->putFileAs('technologies', $file, $filename);
                $validated['icon'] = $path; // Guarda 'technologies/filename.ext'
            }

            $technology = Technology::create($validated);
            return response()->json($technology, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear la tecnología',
                'error' => $e->getMessage()
            ], 500);
        }
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
        try {
            // Validación condicional en update: si se envía archivo validar como file, si no permitir string
            $rules = [
                'name' => 'required|string|max:255',
                'color' => 'string|max:7',
                'description' => 'nullable|string',
                'is_active' => 'boolean'
            ];
            if ($request->hasFile('icon')) {
                $rules['icon'] = 'file|mimes:jpg,jpeg,png,gif,svg,webp|max:2048';
            } else {
                $rules['icon'] = 'nullable|string|max:255';
            }
            $validated = $request->validate($rules);

            // Si se subió un nuevo icono como archivo, eliminar el anterior y guardar el nuevo
            if ($request->hasFile('icon')) {
                // Eliminar icono anterior si existe
                if ($technology->icon) {
                    Storage::disk('public')->delete($technology->icon);
                }
                
                // Guardar nuevo icono
                $file = $request->file('icon');
                $extension = $file->getClientOriginalExtension();
                $filename = \Illuminate\Support\Str::random(40) . '.' . $extension;
                $path = Storage::disk('public')->putFileAs('technologies', $file, $filename);
                $validated['icon'] = $path; // Guarda 'technologies/filename.ext'
            }

            $technology->update($validated);
            return response()->json($technology);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar la tecnología',
                'error' => $e->getMessage()
            ], 500);
        }
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
