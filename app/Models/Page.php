<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'is_active',
        'hero_image',
        'is_published',
        'meta_description'
    ];

    protected $casts = [
        'content' => 'json',
        'is_active' => 'boolean',
        'is_published' => 'boolean',
    ];
}
