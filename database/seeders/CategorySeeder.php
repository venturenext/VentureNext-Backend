<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Technology & Software',
                'description' => 'Exclusive deals on software, apps, cloud services, and tech gadgets',
                'icon' => '💻',
                'meta_title' => 'Technology & Software Perks',
                'meta_description' => 'Access exclusive discounts on software, SaaS tools, cloud services, and technology products',
                'is_active' => true,
                'display_order' => 1,
            ],
            [
                'name' => 'Finance & Banking',
                'description' => 'Special offers from banks, fintech, investment platforms, and financial services',
                'icon' => '💰',
                'meta_title' => 'Finance & Banking Perks',
                'meta_description' => 'Get exclusive deals on banking services, investment platforms, and financial tools',
                'is_active' => true,
                'display_order' => 2,
            ],
            [
                'name' => 'Health & Wellness',
                'description' => 'Discounts on fitness, healthcare, mental wellness, and lifestyle services',
                'icon' => '🏃',
                'meta_title' => 'Health & Wellness Perks',
                'meta_description' => 'Access exclusive health, fitness, and wellness perks for a better lifestyle',
                'is_active' => true,
                'display_order' => 3,
            ],
            [
                'name' => 'Travel & Hospitality',
                'description' => 'Exclusive travel deals, hotel discounts, flight offers, and travel experiences',
                'icon' => '✈️',
                'meta_title' => 'Travel & Hospitality Perks',
                'meta_description' => 'Discover amazing travel deals, hotel discounts, and exclusive travel experiences',
                'is_active' => true,
                'display_order' => 4,
            ],
            [
                'name' => 'Food & Dining',
                'description' => 'Restaurant discounts, food delivery offers, and culinary experiences',
                'icon' => '🍽️',
                'meta_title' => 'Food & Dining Perks',
                'meta_description' => 'Enjoy exclusive restaurant deals, food delivery discounts, and dining experiences',
                'is_active' => true,
                'display_order' => 5,
            ],
            [
                'name' => 'Education & Learning',
                'description' => 'Online courses, certifications, books, and educational resources',
                'icon' => '📚',
                'meta_title' => 'Education & Learning Perks',
                'meta_description' => 'Access exclusive deals on courses, certifications, and educational platforms',
                'is_active' => true,
                'display_order' => 6,
            ],
            [
                'name' => 'E-commerce & Shopping',
                'description' => 'Discounts on online shopping, retail stores, and e-commerce platforms',
                'icon' => '🛒',
                'meta_title' => 'E-commerce & Shopping Perks',
                'meta_description' => 'Get exclusive shopping deals and discounts from top e-commerce platforms',
                'is_active' => true,
                'display_order' => 7,
            ],
            [
                'name' => 'Entertainment & Lifestyle',
                'description' => 'Streaming services, events, concerts, and entertainment experiences',
                'icon' => '🎭',
                'meta_title' => 'Entertainment & Lifestyle Perks',
                'meta_description' => 'Discover exclusive entertainment deals, event tickets, and lifestyle experiences',
                'is_active' => true,
                'display_order' => 8,
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                $category
            );
        }

        $this->command->info('✅ Categories seeded successfully!');
    }
}
