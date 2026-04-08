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
        'seo_title',
        'seo_description',
        'seo_keywords',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'content' => 'array',
    ];
}
