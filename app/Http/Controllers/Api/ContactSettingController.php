<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactSetting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ContactSettingController extends Controller
{
    public function show(): JsonResponse
    {
        $settings = ContactSetting::singleton();
        return response()->json($settings);
    }

    public function update(Request $request): JsonResponse
    {
        $settings = ContactSetting::singleton();
        $rules = [
            'address' => 'nullable|string',
            'phones' => 'nullable|array',
            'phones.*' => 'string|max:50',
            'emails' => 'nullable|array',
            'emails.*' => 'email',
            'socials' => 'nullable|array',
            'socials.*.name' => 'required_with:socials|string',
            'socials.*.url' => 'required_with:socials|url',
            'whatsapp' => 'nullable|string',
            'map_embed' => 'nullable|string'
        ];

        $validated = $request->validate($rules);

        // Guardar arrays como JSON automáticamente por casts
        $settings->update($validated);

        return response()->json($settings);
    }
}
