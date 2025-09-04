<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

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
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'company' => 'required|string|max:255',
                'logo' => 'nullable|string|max:255',
                'website' => 'nullable|url|max:255',
                'testimonial' => 'nullable|string',
                'project_count' => 'integer|min:1',
                'active' => 'boolean',
                'order' => 'integer|min:0'
            ]);

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
                'logo' => 'nullable|string|max:255',
                'website' => 'nullable|url|max:255',
                'testimonial' => 'nullable|string',
                'project_count' => 'integer|min:1',
                'active' => 'boolean',
                'order' => 'integer|min:0'
            ]);

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
