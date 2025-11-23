<?php
namespace Database\Seeders;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'site_name', 'value' => 'PerkPal', 'type' => 'string', 'group_name' => 'general'],
            ['key' => 'site_tagline', 'value' => 'Exclusive Perks for Startups', 'type' => 'string', 'group_name' => 'general'],
            ['key' => 'contact_email', 'value' => 'hello@perkpal.com', 'type' => 'string', 'group_name' => 'general'],
            ['key' => 'lead_notification_email', 'value' => 'hello@perkpal.com', 'type' => 'string', 'group_name' => 'notifications'],
        ];
        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
        $this->command->info('✅ Settings seeded successfully!');
    }
}
