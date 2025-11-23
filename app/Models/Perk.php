<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use App\Models\Location;
use App\Models\Traits\LogsActivity;

class Perk extends Model
{
    use HasFactory, HasSlug, LogsActivity;

    protected $fillable = [
        'category_id',
        'subcategory_id',
        'title',
        'slug',
        'description',
        'short_description',
        'partner_name',
        'partner_logo',
        'redeem_type',
        'coupon_code',
        'external_url',
        'location',
        'valid_from',
        'valid_until',
        'is_featured',
        'is_active',
        'status',
        'published_at',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'valid_from' => 'date',
        'valid_until' => 'date',
        'published_at' => 'datetime',
    ];

    protected $appends = ['is_valid', 'is_expired'];

    /**
     * Get slug options
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    /**
     * Relationships
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function seo()
    {
        return $this->hasOne(PerkSeo::class);
    }

    public function locationOption()
    {
        return $this->belongsTo(Location::class, 'location', 'slug');
    }

    public function statistics()
    {
        return $this->hasOne(PerkStatistic::class);
    }

    public function media()
    {
        return $this->hasMany(PerkMedia::class);
    }

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                     ->where('status', 'published');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByLocation($query, $location)
    {
        if ($location && $location !== 'all') {
            return $query->where('location', $location);
        }
        return $query;
    }

    public function scopeByCategory($query, $categorySlug)
    {
        if ($categorySlug) {
            return $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }
        return $query;
    }

    public function scopeBySubcategory($query, $subcategorySlug)
    {
        if ($subcategorySlug) {
            return $query->whereHas('subcategory', function ($q) use ($subcategorySlug) {
                $q->where('slug', $subcategorySlug);
            });
        }
        return $query;
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->whereRaw(
                "to_tsvector('english', title || ' ' || description) @@ plainto_tsquery('english', ?)",
                [$search]
            );
        }
        return $query;
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->where(function ($q) {
                         $q->whereNull('published_at')
                           ->orWhere('published_at', '<=', now());
                     });
    }

    public function scopePopular($query)
    {
        return $query->leftJoin('perk_statistics', 'perks.id', '=', 'perk_statistics.perk_id')
                     ->orderBy('perk_statistics.view_count', 'desc')
                     ->select('perks.*');
    }

    /**
     * Accessors
     */
    public function getIsValidAttribute()
    {
        if (!$this->valid_from && !$this->valid_until) {
            return true;
        }

        $now = now();

        if ($this->valid_from && $now->lt($this->valid_from)) {
            return false;
        }

        if ($this->valid_until && $now->gt($this->valid_until)) {
            return false;
        }

        return true;
    }

    public function getIsExpiredAttribute()
    {
        if (!$this->valid_until) {
            return false;
        }

        return now()->gt($this->valid_until);
    }

    /**
     * Methods
     */
    public function incrementViewCount()
    {
        if ($this->statistics) {
            $this->statistics->increment('view_count');
            $this->statistics->update(['last_viewed_at' => now()]);
        }
    }

    public function incrementClaimCount()
    {
        if ($this->statistics) {
            $this->statistics->increment('claim_count');
            $this->statistics->update(['last_claimed_at' => now()]);
        }
    }

    public function getMediaByType($type)
    {
        return $this->media()->where('media_type', $type)->get();
    }

    /**
     * Boot method for model events
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-create related records when perk is created
        static::created(function ($perk) {
            // Create empty SEO record
            $perk->seo()->create([]);

            // Create empty statistics record
            $perk->statistics()->create([]);
        });
    }
}
