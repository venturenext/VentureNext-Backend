<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerkSeo extends Model
{
    use HasFactory;

    protected $table = 'perk_seo';

    protected $fillable = [
        'perk_id',
        'meta_title',
        'meta_description',
        'canonical_url',
        'og_image',
        'og_title',
        'og_description',
        'twitter_card',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'schema_json',
        'keywords',
    ];

    protected $casts = [
        'schema_json' => 'array',
    ];

    /**
     * Relationships
     */
    public function perk()
    {
        return $this->belongsTo(Perk::class);
    }

    /**
     * Methods
     */
    public function generateSchemaMarkup()
    {
        if ($this->schema_json) {
            return json_encode($this->schema_json, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        // Generate default schema.org JSON-LD markup
        $perk = $this->perk;
        if (!$perk) {
            return null;
        }

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Offer',
            'name' => $perk->title,
            'description' => $perk->short_description ?? $perk->description,
            'url' => url("/perks/{$perk->slug}"),
            'seller' => [
                '@type' => 'Organization',
                'name' => $perk->partner_name,
            ],
        ];

        if ($perk->valid_from) {
            $schema['validFrom'] = $perk->valid_from->toIso8601String();
        }

        if ($perk->valid_until) {
            $schema['validThrough'] = $perk->valid_until->toIso8601String();
        }

        return json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
