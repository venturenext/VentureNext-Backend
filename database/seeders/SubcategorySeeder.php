<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;

class SubcategorySeeder extends Seeder
{
    public function run(): void
    {
        $subcategories = [
            ['category_name' => 'Technology & Software', 'name' => 'Cloud Services', 'description' => 'AWS, Azure, Google Cloud, and other cloud platforms'],
            ['category_name' => 'Technology & Software', 'name' => 'SaaS Tools', 'description' => 'Business software and productivity tools'],
            ['category_name' => 'Technology & Software', 'name' => 'Developer Tools', 'description' => 'IDEs, version control, CI/CD, and development platforms'],
            ['category_name' => 'Technology & Software', 'name' => 'Design Software', 'description' => 'Adobe, Figma, Sketch, and design tools'],
            ['category_name' => 'Finance & Banking', 'name' => 'Digital Banks', 'description' => 'Online banking and digital-first banks'],
            ['category_name' => 'Finance & Banking', 'name' => 'Investment Platforms', 'description' => 'Stock trading, robo-advisors, and investment apps'],
            ['category_name' => 'Finance & Banking', 'name' => 'Credit Cards', 'description' => 'Credit card offers and cashback programs'],
            ['category_name' => 'Finance & Banking', 'name' => 'Insurance', 'description' => 'Life, health, and general insurance'],
            ['category_name' => 'Health & Wellness', 'name' => 'Fitness Centers', 'description' => 'Gym memberships and fitness studios'],
            ['category_name' => 'Health & Wellness', 'name' => 'Healthcare Services', 'description' => 'Clinics, telemedicine, and health checkups'],
            ['category_name' => 'Health & Wellness', 'name' => 'Mental Wellness', 'description' => 'Therapy, meditation apps, and mental health support'],
            ['category_name' => 'Health & Wellness', 'name' => 'Nutrition & Supplements', 'description' => 'Healthy food delivery and supplements'],
            ['category_name' => 'Travel & Hospitality', 'name' => 'Hotels & Resorts', 'description' => 'Hotel bookings and resort stays'],
            ['category_name' => 'Travel & Hospitality', 'name' => 'Airlines', 'description' => 'Flight tickets and airline miles'],
            ['category_name' => 'Travel & Hospitality', 'name' => 'Travel Experiences', 'description' => 'Tours, activities, and travel packages'],
            ['category_name' => 'Travel & Hospitality', 'name' => 'Car Rentals', 'description' => 'Vehicle rentals and ride-sharing'],
            ['category_name' => 'Food & Dining', 'name' => 'Restaurants', 'description' => 'Dine-in restaurant discounts'],
            ['category_name' => 'Food & Dining', 'name' => 'Food Delivery', 'description' => 'Food delivery apps and services'],
            ['category_name' => 'Food & Dining', 'name' => 'Cafes & Coffee', 'description' => 'Coffee shops and cafes'],
            ['category_name' => 'Food & Dining', 'name' => 'Groceries', 'description' => 'Grocery delivery and supermarkets'],
            ['category_name' => 'Education & Learning', 'name' => 'Online Courses', 'description' => 'Udemy, Coursera, LinkedIn Learning'],
            ['category_name' => 'Education & Learning', 'name' => 'Certifications', 'description' => 'Professional certifications and exams'],
            ['category_name' => 'Education & Learning', 'name' => 'Language Learning', 'description' => 'Language apps and courses'],
            ['category_name' => 'Education & Learning', 'name' => 'Books & E-books', 'description' => 'Digital and physical books'],
            ['category_name' => 'E-commerce & Shopping', 'name' => 'Fashion & Apparel', 'description' => 'Clothing, shoes, and accessories'],
            ['category_name' => 'E-commerce & Shopping', 'name' => 'Electronics', 'description' => 'Gadgets, phones, and electronics'],
            ['category_name' => 'E-commerce & Shopping', 'name' => 'Home & Living', 'description' => 'Furniture, decor, and home essentials'],
            ['category_name' => 'E-commerce & Shopping', 'name' => 'Beauty & Personal Care', 'description' => 'Cosmetics, skincare, and grooming'],
            ['category_name' => 'Entertainment & Lifestyle', 'name' => 'Streaming Services', 'description' => 'Netflix, Spotify, Disney+, and more'],
            ['category_name' => 'Entertainment & Lifestyle', 'name' => 'Events & Concerts', 'description' => 'Tickets for events and live shows'],
            ['category_name' => 'Entertainment & Lifestyle', 'name' => 'Gaming', 'description' => 'Video games, consoles, and gaming subscriptions'],
            ['category_name' => 'Entertainment & Lifestyle', 'name' => 'Hobbies & Crafts', 'description' => 'Arts, crafts, and hobby supplies'],
        ];

        foreach ($subcategories as $subcategoryData) {
            $category = Category::where('name', $subcategoryData['category_name'])->first();

            if ($category) {
                Subcategory::create([
                    'category_id' => $category->id,
                    'name' => $subcategoryData['name'],
                    'description' => $subcategoryData['description'],
                    'meta_title' => $subcategoryData['name'] . ' Perks',
                    'meta_description' => $subcategoryData['description'],
                    'is_active' => true,
                    'display_order' => 0,
                ]);
            }
        }

        $this->command->info('✅ Subcategories seeded successfully!');
    }
}
