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
            ['category_name' => 'Technology & Software', 'name' => 'Cloud Services'],
            ['category_name' => 'Technology & Software', 'name' => 'SaaS Tools'],
            ['category_name' => 'Technology & Software', 'name' => 'Developer Tools'],
            ['category_name' => 'Technology & Software', 'name' => 'Design Software'],
            ['category_name' => 'Finance & Banking', 'name' => 'Digital Banks'],
            ['category_name' => 'Finance & Banking', 'name' => 'Investment Platforms'],
            ['category_name' => 'Finance & Banking', 'name' => 'Credit Cards'],
            ['category_name' => 'Finance & Banking', 'name' => 'Insurance'],
            ['category_name' => 'Health & Wellness', 'name' => 'Fitness Centers'],
            ['category_name' => 'Health & Wellness', 'name' => 'Healthcare Services'],
            ['category_name' => 'Health & Wellness', 'name' => 'Mental Wellness'],
            ['category_name' => 'Health & Wellness', 'name' => 'Nutrition & Supplements'],
            ['category_name' => 'Travel & Hospitality', 'name' => 'Hotels & Resorts'],
            ['category_name' => 'Travel & Hospitality', 'name' => 'Airlines'],
            ['category_name' => 'Travel & Hospitality', 'name' => 'Travel Experiences'],
            ['category_name' => 'Travel & Hospitality', 'name' => 'Car Rentals'],
            ['category_name' => 'Food & Dining', 'name' => 'Restaurants'],
            ['category_name' => 'Food & Dining', 'name' => 'Food Delivery'],
            ['category_name' => 'Food & Dining', 'name' => 'Cafes & Coffee'],
            ['category_name' => 'Food & Dining', 'name' => 'Groceries'],
            ['category_name' => 'Education & Learning', 'name' => 'Online Courses'],
            ['category_name' => 'Education & Learning', 'name' => 'Certifications'],
            ['category_name' => 'Education & Learning', 'name' => 'Language Learning'],
            ['category_name' => 'Education & Learning', 'name' => 'Books & E-books'],
            ['category_name' => 'E-commerce & Shopping', 'name' => 'Fashion & Apparel'],
            ['category_name' => 'E-commerce & Shopping', 'name' => 'Electronics'],
            ['category_name' => 'E-commerce & Shopping', 'name' => 'Home & Living'],
            ['category_name' => 'E-commerce & Shopping', 'name' => 'Beauty & Personal Care'],
            ['category_name' => 'Entertainment & Lifestyle', 'name' => 'Streaming Services'],
            ['category_name' => 'Entertainment & Lifestyle', 'name' => 'Events & Concerts'],
            ['category_name' => 'Entertainment & Lifestyle', 'name' => 'Gaming'],
            ['category_name' => 'Entertainment & Lifestyle', 'name' => 'Hobbies & Crafts'],
        ];

        foreach ($subcategories as $subcategoryData) {
            $category = Category::where('name', $subcategoryData['category_name'])->first();

            if ($category) {
                Subcategory::create([
                    'category_id' => $category->id,
                    'name' => $subcategoryData['name'],
                    'is_active' => true,
                ]);
            }
        }

        $this->command->info('✅ Subcategories seeded successfully!');
    }
}
