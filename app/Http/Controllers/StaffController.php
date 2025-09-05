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
            // LOG: registrar llaves recibidas y presencia de archivo (temporal)
            \Log::info('StaffController.store incoming keys: ' . implode(',', array_keys($request->all())));
            \Log::info('StaffController.store content-type: ' . $request->header('content-type'));
            \Log::info('StaffController.store hasFile image (before validate): ' . ($request->hasFile('image') ? 'yes' : 'no'));
            if ($request->hasFile('image')) {
                $f = $request->file('image');
                \Log::info('StaffController.store file originalName: ' . $f->getClientOriginalName() . ' size:' . $f->getSize() . ' mime:' . $f->getMimeType());
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'position' => 'required|string|max:255',
                'bio' => 'nullable|string',
                    'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'email' => 'nullable|email|max:255',
                'linkedin' => 'nullable|url|max:255',
                'github' => 'nullable|url|max:255',
                'active' => 'boolean',
                'order' => 'integer|min:0'
            ]);

            // LOG: registrar llaves recibidas y presencia de archivo (temporal)
            \Log::info('StaffController.store request keys: ' . implode(',', array_keys($request->all())));
            \Log::info('StaffController.store hasFile image: ' . ($request->hasFile('image') ? 'yes' : 'no'));
            \Log::info('StaffController.store raw active input: ' . var_export($request->input('active'), true));

            // Si se subió un archivo de imagen, guardarlo en public/storage/staff
            if ($request->hasFile('image')) {
                $uploadPath = public_path('storage/staff');
                // Diagnostic logs
                \Log::info('StaffController.store uploadPath: ' . $uploadPath);
                \Log::info('StaffController.store is_dir(uploadPath): ' . (is_dir($uploadPath) ? 'yes' : 'no'));
                \Log::info('StaffController.store is_writable(uploadPath): ' . (is_writable($uploadPath) ? 'yes' : 'no'));
                \Log::info('StaffController.store public/storage realpath: ' . @realpath(public_path('storage')));
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                $extension = $request->file('image')->getClientOriginalExtension();
                $filename = \Illuminate\Support\Str::random(40) . '.' . $extension;
                $request->file('image')->move($uploadPath, $filename);
                $validated['image'] = 'staff/' . $filename;
            }

            $staff = Staff::create($validated);

            return response()->json([
                'message' => 'Miembro del staff creado exitosamente',
                'staff' => $staff
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('StaffController.store validation failed: ' . json_encode($e->errors()));
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
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'email' => 'nullable|email|max:255',
                'linkedin' => 'nullable|url|max:255',
                'github' => 'nullable|url|max:255',
                'active' => 'boolean',
                'order' => 'integer|min:0'
            ]);

            // Si se subió una nueva imagen, eliminar la anterior y guardar la nueva
            if ($request->hasFile('image')) {
                // Eliminar imagen anterior si existe
                if ($staff->image) {
                    $oldPath = public_path('storage/' . ltrim($staff->image, '/'));
                    if (file_exists($oldPath)) {
                        @unlink($oldPath);
                    }
                }

                $uploadPath = public_path('storage/staff');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                $extension = $request->file('image')->getClientOriginalExtension();
                $filename = \Illuminate\Support\Str::random(40) . '.' . $extension;
                $request->file('image')->move($uploadPath, $filename);
                $validated['image'] = 'staff/' . $filename;
            }

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
