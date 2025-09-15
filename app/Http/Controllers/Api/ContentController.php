<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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
        $rules = [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'subtitle' => 'nullable|string|max:255',
            'type' => 'string|in:general,service,technology,area',
            'image' => 'nullable|file|mimes:jpg,jpeg,png,gif,svg,webp|max:2048',
            'is_featured' => 'boolean',
            'is_active' => 'boolean'
        ];

        $v = Validator::make($request->all(), $rules);
        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()], 422);
        }

        try {
            $validated = $v->validated();
            // handle file upload
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('contents', 'public');
                $validated['image'] = $path;
            }

            $content = Content::create($validated);
            return response()->json($content, 201);
        } catch (\Throwable $e) {
            $fileInfo = null;
            try {
                if ($request->hasFile('image')) {
                    $f = $request->file('image');
                    $fileInfo = [
                        'client_name' => $f->getClientOriginalName(),
                        'client_mime' => $f->getClientMimeType(),
                        'server_mime' => $f->getMimeType(),
                        'size' => $f->getSize(),
                    ];
                }
            } catch (\Throwable $_) {
                // ignore file inspection errors
            }

            Log::error('ContentController@store error', [
                'message' => $e->getMessage(),
                'request_keys' => array_keys($request->all()),
                'has_file' => $request->hasFile('image'),
                'file_info' => $fileInfo,
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
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
        $rules = [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'subtitle' => 'nullable|string|max:255',
            'type' => 'string|in:general,service,technology,area',
            // use `image` rule which accepts common image types and is more robust
            'image' => 'nullable|image|max:2048',
            'is_featured' => 'boolean',
            'is_active' => 'boolean'
        ];

        $v = Validator::make($request->all(), $rules);
        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()], 422);
        }

        try {
            $validated = $v->validated();
            if ($request->hasFile('image')) {
                // delete old if exists
                if ($content->image) {
                    \Storage::disk('public')->delete($content->image);
                }
                $path = $request->file('image')->store('contents', 'public');
                $validated['image'] = $path;
            }

            $content->update($validated);
            return response()->json($content);
        } catch (\Throwable $e) {
            $fileInfo = null;
            try {
                if ($request->hasFile('image')) {
                    $f = $request->file('image');
                    $fileInfo = [
                        'client_name' => $f->getClientOriginalName(),
                        'client_mime' => $f->getClientMimeType(),
                        'server_mime' => $f->getMimeType(),
                        'size' => $f->getSize(),
                    ];
                }
            } catch (\Throwable $_) {
                // ignore file inspection errors
            }

            Log::error('ContentController@update error', [
                'message' => $e->getMessage(),
                'request_keys' => array_keys($request->all()),
                'has_file' => $request->hasFile('image'),
                'file_info' => $fileInfo,
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
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
