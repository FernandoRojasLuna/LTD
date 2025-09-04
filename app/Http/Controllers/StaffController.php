<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $staff = Staff::active()->ordered()->get();
            
            return response()->json($staff, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener el staff',
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
                'position' => 'required|string|max:255',
                'bio' => 'nullable|string',
                'image' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:255',
                'linkedin' => 'nullable|url|max:255',
                'github' => 'nullable|url|max:255',
                'active' => 'boolean',
                'order' => 'integer|min:0'
            ]);

            $staff = Staff::create($validated);

            return response()->json([
                'message' => 'Miembro del staff creado exitosamente',
                'staff' => $staff
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear el miembro del staff',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Staff $staff): JsonResponse
    {
        try {
            return response()->json($staff, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener el miembro del staff',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Staff $staff): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'position' => 'sometimes|required|string|max:255',
                'bio' => 'nullable|string',
                'image' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:255',
                'linkedin' => 'nullable|url|max:255',
                'github' => 'nullable|url|max:255',
                'active' => 'boolean',
                'order' => 'integer|min:0'
            ]);

            $staff->update($validated);

            return response()->json([
                'message' => 'Miembro del staff actualizado exitosamente',
                'staff' => $staff->fresh()
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el miembro del staff',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Staff $staff): JsonResponse
    {
        try {
            $staff->delete();

            return response()->json([
                'message' => 'Miembro del staff eliminado exitosamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar el miembro del staff',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
