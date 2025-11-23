<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use App\Models\Traits\LogsActivity;

class JournalPost extends Model
{
    use HasFactory, HasSlug, LogsActivity;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'cover_image',
        'category_id',
        'tags',
        'author_name',
        'author_avatar',
        'is_published',
        'published_at',
        'meta_title',
        'meta_description',
        'og_title',
        'og_description',
        'og_image',
        'twitter_title',
        'twitter_description',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'tags' => 'array',
        'published_at' => 'datetime',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('title')->saveSlugsTo('slug');
    }

    /**
     * Relationships
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                     ->where(function ($q) { $q->whereNull('published_at')->orWhere('published_at', '<=', now()); });
    }

    public function scopeByCategory($query, $category)
    {
        if ($category) {
            $query->whereHas('category', function ($q) use ($category) {
                $q->where('slug', $category);
            });
        }
        return $query;
    }

    // Helpers
    public function getReadingTime(): int
    {
        $text = strip_tags((string) $this->content);
        if ($text === '') return 0;
        $words = str_word_count($text);
        return (int) max(1, ceil($words / 200));
    }
}
