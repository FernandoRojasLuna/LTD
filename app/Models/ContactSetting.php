<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'address',
        'phones',
        'emails',
        'socials',
        'whatsapp',
        'map_embed'
    ];

    protected $casts = [
        'phones' => 'array',
        'emails' => 'array',
        'socials' => 'array'
    ];

    // Helper to get singleton
    public static function singleton()
    {
        return static::first() ?: static::create([]);
    }
}
