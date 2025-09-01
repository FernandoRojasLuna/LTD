<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Content extends Model
{
    protected $fillable = [
        'title',
        'slug',
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

    // Generar slug automáticamente
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($content) {
            if (empty($content->slug)) {
                $content->slug = Str::slug($content->title);
            }
        });
    }
}
