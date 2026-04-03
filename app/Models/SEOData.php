<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SEOData extends Model
{
    use HasFactory;

    protected $fillable = [
        'model_type',
        'model_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_title',
        'og_description',
        'structured_data',
        'seo_score',
        'keywords',
        'content_type',
        'generated_at',
        'indexed_at',
        'last_submitted',
    ];

    protected $casts = [
        'structured_data' => 'array',
        'keywords' => 'array',
        'meta_keywords' => 'array',
        'generated_at' => 'datetime',
        'indexed_at' => 'datetime',
        'last_submitted' => 'datetime',
        'seo_score' => 'integer',
    ];

    /**
     * Get the parent model (polymorphic relationship)
     */
    public function model()
    {
        return $this->morphTo();
    }

    /**
     * Scope for content type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('content_type', $type);
    }

    /**
     * Scope for indexed content
     */
    public function scopeIndexed($query)
    {
        return $query->whereNotNull('indexed_at');
    }

    /**
     * Scope for high-scoring content
     */
    public function scopeHighScore($query, int $minScore = 80)
    {
        return $query->where('seo_score', '>=', $minScore);
    }

    /**
     * Check if content is indexed
     */
    public function isIndexed(): bool
    {
        return !is_null($this->indexed_at);
    }

    /**
     * Check if content needs resubmission
     */
    public function needsResubmission(): bool
    {
        if (!$this->last_submitted) {
            return true;
        }

        $content = $this->model;
        if ($content && $content->updated_at) {
            return $content->updated_at->gt($this->last_submitted);
        }

        return false;
    }

    /**
     * Get SEO score color
     */
    public function getScoreColorAttribute(): string
    {
        if ($this->seo_score >= 80) {
            return 'green';
        } elseif ($this->seo_score >= 60) {
            return 'yellow';
        } else {
            return 'red';
        }
    }

    /**
     * Generate meta tags HTML
     */
    public function generateMetaTagsHtml(): string
    {
        $tags = [];

        if ($this->meta_title) {
            $tags[] = "<title>{$this->meta_title}</title>";
            $tags[] = '<meta property="og:title" content="' . htmlspecialchars($this->meta_title) . '">';
        }

        if ($this->meta_description) {
            $tags[] = '<meta name="description" content="' . htmlspecialchars($this->meta_description) . '">';
            $tags[] = '<meta property="og:description" content="' . htmlspecialchars($this->meta_description) . '">';
        }

        if ($this->meta_keywords && !empty($this->meta_keywords)) {
            $tags[] = '<meta name="keywords" content="' . implode(', ', $this->meta_keywords) . '">';
        }

        return implode("\n", $tags);
    }

    /**
     * Generate structured data HTML
     */
    public function generateStructuredDataHtml(): string
    {
        if (empty($this->structured_data)) {
            return '';
        }

        $json = json_encode($this->structured_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        return "<script type=\"application/ld+json\">\n{$json}\n</script>";
    }
}
