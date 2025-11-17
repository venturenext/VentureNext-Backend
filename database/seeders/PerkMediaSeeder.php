<?php

namespace Database\Seeders;

use App\Models\Perk;
use App\Models\PerkMedia;
use Illuminate\Database\Seeder;

class PerkMediaSeeder extends Seeder
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
            // Logo (only if none exists)
            $hasLogo = $perk->media()->where('media_type', 'logo')->exists();
            if (!$hasLogo) {
                $slug = $perk->slug ?? 'perk';
                PerkMedia::create([
                    'perk_id' => $perk->id,
                    'media_type' => 'logo',
                    'file_path' => "perks/logos/{$slug}-logo.png",
                    'file_name' => "{$slug}-logo.png",
                    'mime_type' => 'image/png',
                    'file_size' => 45000,
                    'alt_text' => $perk->partner_name . ' Logo',
                    'display_order' => 0,
                ]);
            }

            // Banner (only if none exists)
            $hasBanner = $perk->media()->where('media_type', 'banner')->exists();
            if (!$hasBanner) {
                $slug = $perk->slug ?? 'perk';
                PerkMedia::create([
                    'perk_id' => $perk->id,
                    'media_type' => 'banner',
                    'file_path' => "perks/banners/{$slug}-banner.jpg",
                    'file_name' => "{$slug}-banner.jpg",
                    'mime_type' => 'image/jpeg',
                    'file_size' => 230000,
                    'alt_text' => $perk->title . ' Banner',
                    'display_order' => 0,
                ]);
            }
        }

        $this->command->info('✅ Perk media seeded successfully!');
    }
}
