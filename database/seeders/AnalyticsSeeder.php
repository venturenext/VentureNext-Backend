<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Analytic;
use App\Models\Perk;
use Carbon\Carbon;

class AnalyticsSeeder extends Seeder
{
    public function run(): void
    {
        $perks = Perk::limit(10)->pluck('id')->toArray();

        if (empty($perks)) {
            $this->command->warn('No perks found. Please seed perks first.');
            return;
        }

        $eventTypes = ['impression', 'click', 'form_submission', 'affiliate_click'];
        $this->command->info('Seeding analytics data...');

        // Generate data for the last 30 days
        for ($day = 30; $day >= 0; $day--) {
            $date = Carbon::now()->subDays($day);

            foreach ($perks as $perkId) {
                // Impressions (most common)
                $impressions = rand(50, 200);
                for ($i = 0; $i < $impressions; $i++) {
                    Analytic::create([
                        'perk_id' => $perkId,
                        'event_type' => 'impression',
                        'session_id' => 'session_' . uniqid(),
                        'ip_address' => $this->randomIp(),
                        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                        'referrer' => 'https://google.com',
                        'created_at' => $date->copy()->addMinutes(rand(0, 1439)),
                    ]);
                }

                // Clicks (less than impressions)
                $clicks = rand(10, 50);
                for ($i = 0; $i < $clicks; $i++) {
                    Analytic::create([
                        'perk_id' => $perkId,
                        'event_type' => 'click',
                        'session_id' => 'session_' . uniqid(),
                        'ip_address' => $this->randomIp(),
                        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                        'referrer' => 'https://google.com',
                        'created_at' => $date->copy()->addMinutes(rand(0, 1439)),
                    ]);
                }

                // Form submissions (even less)
                $formSubs = rand(2, 15);
                for ($i = 0; $i < $formSubs; $i++) {
                    Analytic::create([
                        'perk_id' => $perkId,
                        'event_type' => 'form_submission',
                        'session_id' => 'session_' . uniqid(),
                        'ip_address' => $this->randomIp(),
                        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                        'referrer' => 'https://google.com',
                        'created_at' => $date->copy()->addMinutes(rand(0, 1439)),
                    ]);
                }

                // Affiliate clicks
                $affiliateClicks = rand(5, 25);
                for ($i = 0; $i < $affiliateClicks; $i++) {
                    Analytic::create([
                        'perk_id' => $perkId,
                        'event_type' => 'affiliate_click',
                        'session_id' => 'session_' . uniqid(),
                        'ip_address' => $this->randomIp(),
                        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                        'referrer' => 'https://google.com',
                        'created_at' => $date->copy()->addMinutes(rand(0, 1439)),
                    ]);
                }
            }

            $this->command->info("Day -$day completed");
        }

        $this->command->info('Analytics data seeded successfully!');
    }

    private function randomIp(): string
    {
        return rand(1, 255) . '.' . rand(0, 255) . '.' . rand(0, 255) . '.' . rand(0, 255);
    }
}
