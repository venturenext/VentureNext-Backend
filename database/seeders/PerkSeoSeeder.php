<?php

namespace Database\Seeders;

use App\Models\Perk;
use App\Models\PerkSeo;
use Illuminate\Database\Seeder;

class PerkSeoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $perks = Perk::all();

        if ($perks->isEmpty()) {
            $this->command->warn('⚠️  No perks found. Please run PerkSeeder first.');
            return;
        }

        foreach ($perks as $perk) {
            $title = $perk->title . ' | PerkPal';
            $desc = $perk->short_description ?: strip_tags(substr($perk->description, 0, 150));
            $slug = $perk->slug ?? 'perk';
            $banner = url("storage/perks/banners/{$slug}-banner.jpg");

            PerkSeo::updateOrCreate(
                ['perk_id' => $perk->id],
                [
                    'perk_id' => $perk->id,
                    'meta_title' => $title,
                    'meta_description' => $desc,
                    'canonical_url' => url("/perks/{$slug}"),
                    'og_image' => $banner,
                    'og_title' => $perk->title,
                    'og_description' => $desc,
                    'twitter_card' => 'summary_large_image',
                    'twitter_title' => $perk->title,
                    'twitter_description' => $desc,
                    'twitter_image' => $banner,
                    'schema_json' => [
                        '@context' => 'https://schema.org',
                        '@type' => 'Offer',
                        'name' => $perk->title,
                        'description' => $desc,
                        'seller' => [
                            '@type' => 'Organization',
                            'name' => $perk->partner_name,
                        ],
                        'availability' => 'https://schema.org/InStock',
                        'validFrom' => now()->toIso8601String(),
                    ],
                    'keywords' => strtolower(implode(', ', [
                        $perk->partner_name,
                        $perk->title,
                        'startup perks',
                        'discounts',
                    ])),
                ]
            );
        }

        $this->command->info('✅ Perk SEO data seeded successfully!');
    }
}
