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
        'meta_description',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'is_published' => 'boolean',
        // NOTE: content is raw HTML — NOT json-cast
    ];

    /** Pages that appear in a menu */
    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }
}
