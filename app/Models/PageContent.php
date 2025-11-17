<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_name',
        'section_type',
        'section_key',
        'title',
        'subtitle',
        'content',
        'image_url',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'content' => 'array',
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    /**
     * Scope for filtering by page name
     */
    public function scopeByPage($query, string $pageName)
    {
        return $query->where('page_name', $pageName);
    }

    /**
     * Scope for filtering by section type
     */
    public function scopeByType($query, string $sectionType)
    {
        return $query->where('section_type', $sectionType);
    }

    /**
     * Scope for active sections only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordering by display order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order', 'asc');
    }

    /**
     * Get all sections for a specific page
     */
    public static function getPageSections(string $pageName, bool $activeOnly = true)
    {
        $query = static::byPage($pageName)->ordered();

        if ($activeOnly) {
            $query->active();
        }

        return $query->get();
    }

    /**
     * Get a specific section by key
     */
    public static function getByKey(string $sectionKey)
    {
        return static::where('section_key', $sectionKey)->first();
    }

    /**
     * Update or create a section
     */
    public static function updateOrCreateSection(array $data)
    {
        return static::updateOrCreate(
            ['section_key' => $data['section_key']],
            $data
        );
    }
}
