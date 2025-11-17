<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class JournalPost extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'cover_image',
        'category',
        'tags',
        'author_name',
        'author_avatar',
        'is_published',
        'published_at',
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

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                     ->where(function ($q) { $q->whereNull('published_at')->orWhere('published_at', '<=', now()); });
    }

    public function scopeByCategory($query, $category)
    {
        if ($category) { $query->where('category', $category); }
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
