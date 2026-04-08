<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'category_id',
        'content',
        'featured_image',
        'is_published',
        'published_at',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'seo_schema',
        'seo_score',
    ];

    protected $casts = [
        'content' => 'array',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'seo_schema' => 'json',
        'seo_score' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
