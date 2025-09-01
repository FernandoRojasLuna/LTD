<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Project extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'full_description',
        'image',
        'gallery',
        'status',
        'start_date',
        'end_date',
        'url',
        'repository',
        'features',
        'is_featured',
        'is_active',
        'order'
    ];

    protected $casts = [
        'gallery' => 'array',
        'features' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
        'order' => 'integer'
    ];

    // Relación con tecnologías
    public function technologies()
    {
        return $this->belongsToMany(Technology::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc')->orderBy('created_at', 'desc');
    }

    // Accessors
    public function getStatusLabelAttribute()
    {
        $labels = [
            'development' => 'En Desarrollo',
            'deployed' => 'Desplegado',
            'paused' => 'Pausado',
            'completed' => 'Completado'
        ];
        return $labels[$this->status] ?? 'Desconocido';
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'development' => 'blue',
            'deployed' => 'green',
            'paused' => 'orange',
            'completed' => 'purple'
        ];
        return $colors[$this->status] ?? 'gray';
    }

    // Generar slug automáticamente
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($project) {
            if (empty($project->slug)) {
                $project->slug = Str::slug($project->name);
            }
        });
    }
}
