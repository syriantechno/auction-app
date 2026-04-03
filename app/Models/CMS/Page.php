<?php

namespace App\Models\CMS;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'hero_image',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'content' => 'array',
    ];
}
