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
        'seo_title',
        'seo_description',
        'seo_keywords',
        'seo_schema',
        'seo_score',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'is_published' => 'boolean',
        'content'      => 'json',
        'seo_schema'   => 'json',
        'seo_score'    => 'integer',
    ];

    /** Pages that appear in a menu */
    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }
}
