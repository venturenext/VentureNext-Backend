<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            SubcategorySeeder::class,
            SettingSeeder::class,
            StaticPageSeeder::class,
            JournalPostSeeder::class,
            PerkSeeder::class,
            PerkMediaSeeder::class,
            PerkSeoSeeder::class,
            PerkStatisticsSeeder::class,
            LeadSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('🎉 Database seeded successfully!');
        $this->command->info('');
        $this->command->info('Admin Login:');
        $this->command->info('Email: admin@perkpal.com');
        $this->command->info('Password: password');
    }
}
