<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'image',
        'description',
        'link',
        'order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer'
    ];

    // Scope para banners activos
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope para ordenar por posición
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }
}
