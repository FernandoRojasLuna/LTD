<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Content extends Model
{
    protected $fillable = [
        'title',
    'subtitle',
        'content',
        'type',
        'image',
        'is_featured',
        'is_active'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean'
    ];

    protected $appends = ['image_url'];

    // Scope para contenido activo
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope para contenido destacado
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // Scope por tipo
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    // No automatic slug generation: subtitle is a separate, user-provided field
    protected static function boot()
    {
        parent::boot();
        // keep default boot behavior; subtitle is provided manually by user
    }

    // Accessor para URL pública de la imagen
    public function getImageUrlAttribute()
    {
        if (!$this->image) return null;
        return asset('storage/' . $this->image);
    }
}
