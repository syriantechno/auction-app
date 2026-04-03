<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = [
        'menu_id',
        'label',
        'url',
        'type',
        'order',
        'parent_id',
        'page_id',
        'target',
        'icon',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('order');
    }

    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    /** Resolved URL: page slug takes priority over manual URL */
    public function getResolvedUrlAttribute(): string
    {
        if ($this->page_id && $this->relationLoaded('page') && $this->page) {
            return '/' . $this->page->slug;
        }
        return $this->url ?? '#';
    }
}
