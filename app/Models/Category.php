<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Category extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'meta_title',
        'meta_description',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    /**
     * Get slug options
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    /**
     * Relationships
     */
    public function subcategories()
    {
        return $this->hasMany(Subcategory::class)->orderBy('name');
    }

    public function perks()
    {
        return $this->hasMany(Perk::class);
    }

    public function journalPosts()
    {
        return $this->hasMany(JournalPost::class);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }

    public function scopeWithActiveSubcategories($query)
    {
        return $query->with(['subcategories' => function ($q) {
            $q->where('is_active', true)->orderBy('name');
        }]);
    }

    /**
     * Methods
     */
    public function getActivePerksCount()
    {
        return $this->perks()->active()->count();
    }

    public function getActiveSubcategoriesCount()
    {
        return $this->subcategories()->where('is_active', true)->count();
    }
}
