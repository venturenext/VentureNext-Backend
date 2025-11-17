<?php

namespace Database\Seeders;

use App\Models\Perk;
use App\Models\PerkStatistic;
use Illuminate\Database\Seeder;

class PerkStatisticsSeeder extends Seeder
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

        foreach ($perks as $index => $perk) {
            $base = 500 + ($index * 150);
            $views = $base + 120;
            $claims = (int) floor($views * (0.05 + ($index % 3) * 0.01));
            $couponCopies = (int) floor($views * 0.08);
            $externalClicks = (int) floor($views * 0.12);
            $conversion = $views > 0 ? (($claims + $couponCopies + $externalClicks) / $views) * 100 : 0;

            PerkStatistic::updateOrCreate(
                ['perk_id' => $perk->id],
                [
                    'perk_id' => $perk->id,
                    'view_count' => $views,
                    'unique_views' => (int) floor($views * 0.8),
                    'last_viewed_at' => now()->subHours(max(1, 6 - $index)),
                    'claim_count' => $claims,
                    'last_claimed_at' => now()->subHours(max(2, 10 - $index)),
                    'coupon_copy_count' => $couponCopies,
                    'external_link_clicks' => $externalClicks,
                    'conversion_rate' => round($conversion, 2),
                    'average_time_on_page' => 120 + ($index * 5),
                ]
            );
        }

        $this->command->info('✅ Perk statistics seeded successfully!');
    }
}
