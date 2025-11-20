<?php
namespace Database\Seeders;
use App\Models\StaticPage;
use Illuminate\Database\Seeder;

class StaticPageSeeder extends Seeder
{
    public function run(): void
    {
        StaticPage::updateOrCreate(
            ['title' => 'About Us'],
            [
                'title' => 'About Us',
                'content' => '<h1>About PerkPal</h1><p>Your gateway to exclusive perks and deals.</p>',
                'excerpt' => 'Learn more about PerkPal',
                'meta_title' => 'About PerkPal',
                'meta_description' => 'Learn about PerkPal mission and vision',
                'is_active' => true,
            ]
        );

        StaticPage::updateOrCreate(
            ['title' => 'Contact Us'],
            [
                'title' => 'Contact Us',
                'content' => '<h1>Contact</h1><p>Email: hello@perkpal.com</p>',
                'excerpt' => 'Get in touch',
                'meta_title' => 'Contact PerkPal',
                'meta_description' => 'Contact PerkPal team',
                'is_active' => true,
            ]
        );

        $this->command->info('✅ Static pages seeded!');
    }
}
