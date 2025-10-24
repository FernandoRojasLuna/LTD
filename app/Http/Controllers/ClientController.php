<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $clients = Client::active()->ordered()->get();
            
            return response()->json($clients, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener los clientes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // ...existing code...
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'company' => 'nullable|string|max:255',
                'logo' => 'nullable|file|mimes:jpg,jpeg,png,gif,svg,webp|max:2048',
                'website' => 'nullable|url|max:255',
                'testimonial' => 'nullable|string',
                'testimonial_author' => 'nullable|string|max:255',
                'testimonial_position' => 'nullable|string|max:255',
                'rating' => 'nullable|integer|min:1|max:5',
                'project_count' => 'integer|min:1',
                'active' => 'boolean',
                'order' => 'integer|min:0'
            ]);

            // Si se subió un logo como archivo, guardarlo usando Storage (storage/app/public/clients)
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $extension = $file->getClientOriginalExtension();
                $filename = \Illuminate\Support\Str::random(40) . '.' . $extension;
                
                // Guardar en storage/app/public/clients (accesible vía /storage/clients)
                $path = Storage::disk('public')->putFileAs('clients', $file, $filename);
                $validated['logo'] = $path; // Guarda 'clients/filename.ext'
            }

            $client = Client::create($validated);

            return response()->json([
                'message' => 'Cliente creado exitosamente',
                'client' => $client
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear el cliente',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client): JsonResponse
    {
        try {
            return response()->json($client, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener el cliente',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'company' => 'sometimes|required|string|max:255',
                'logo' => 'nullable|file|mimes:jpg,jpeg,png,gif,svg,webp|max:2048',
                'website' => 'nullable|url|max:255',
                'testimonial' => 'nullable|string',
                'testimonial_author' => 'nullable|string|max:255',
                'testimonial_position' => 'nullable|string|max:255',
                'rating' => 'nullable|integer|min:1|max:5',
                'project_count' => 'integer|min:1',
                'active' => 'boolean',
                'order' => 'integer|min:0'
            ]);

            // Si se subió un nuevo logo como archivo, eliminar el anterior y guardar el nuevo
            if ($request->hasFile('logo')) {
                // Eliminar logo anterior si existe
                if ($client->logo) {
                    Storage::disk('public')->delete($client->logo);
                }
                
                // Guardar nuevo logo
                $file = $request->file('logo');
                $extension = $file->getClientOriginalExtension();
                $filename = \Illuminate\Support\Str::random(40) . '.' . $extension;
                $path = Storage::disk('public')->putFileAs('clients', $file, $filename);
                $validated['logo'] = $path; // Guarda 'clients/filename.ext'
            }

            $client->update($validated);

            return response()->json([
                'message' => 'Cliente actualizado exitosamente',
                'client' => $client->fresh()
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el cliente',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client): JsonResponse
    {
        try {
            $client->delete();

            return response()->json([
                'message' => 'Cliente eliminado exitosamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar el cliente',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get clients with testimonials for public display
     */
    public function testimonials(): JsonResponse
    {
        try {
            $clients = Client::active()
                ->withTestimonials()
                ->ordered()
                ->get();
            
            return response()->json($clients, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener testimonios',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
