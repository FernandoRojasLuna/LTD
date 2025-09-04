<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'company',
        'logo',
        'website',
        'testimonial',
        'project_count',
        'active',
        'order'
    ];

    protected $casts = [
        'active' => 'boolean',
        'project_count' => 'integer',
        'order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc')->orderBy('name', 'asc');
    }

    public function scopeWithTestimonials($query)
    {
        return $query->whereNotNull('testimonial');
    }
}