<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Technology extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'color',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Relación con proyectos
    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }

    // Scope para tecnologías activas
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Generar slug automáticamente
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($technology) {
            if (empty($technology->slug)) {
                $technology->slug = Str::slug($technology->name);
            }
        });
    }
}
