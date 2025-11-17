<?php
namespace Database\Seeders;
use App\Models\{Category, Perk, Subcategory};
use Illuminate\Database\Seeder;

class PerkSeeder extends Seeder
{
    public function run(): void
    {
        $samples = [
            [
                'category' => 'Technology & Software',
                'subcategory' => 'Cloud Services',
                'title' => 'AWS Credits - $1,000 Free for Startups',
                'description' => '<p>Get $1,000 in AWS credits for your startup</p>',
                'short_description' => 'Free $1,000 AWS credits for startups',
                'partner_name' => 'Amazon Web Services',
                'partner_url' => 'https://aws.amazon.com',
                'redeem_type' => 'external_link',
                'external_url' => 'https://aws.amazon.com/activate',
                'location' => 'global',
                'valid_until' => now()->addMonths(6),
                'is_featured' => true,
                'status' => 'published',
            ],
            [
                'category' => 'Technology & Software',
                'subcategory' => 'SaaS Tools',
                'title' => 'Notion for Startups - 6 Months Free',
                'description' => '<p>Organize your work with Notion. Free 6 months for startups.</p>',
                'short_description' => '6 months free Notion for startups',
                'partner_name' => 'Notion',
                'partner_url' => 'https://www.notion.so',
                'redeem_type' => 'coupon_code',
                'coupon_code' => 'NOTION6FREE',
                'location' => 'global',
                'valid_until' => now()->addMonths(3),
                'is_featured' => false,
                'status' => 'published',
            ],
            [
                'category' => 'Finance & Banking',
                'subcategory' => 'Digital Banks',
                'title' => 'Wise Business - Free International Transfer',
                'description' => '<p>Open Wise Business and get your first transfer fee-free.</p>',
                'short_description' => 'First international transfer fee-free',
                'partner_name' => 'Wise',
                'partner_url' => 'https://wise.com',
                'redeem_type' => 'external_link',
                'external_url' => 'https://wise.com/business',
                'location' => 'global',
                'valid_until' => now()->addMonths(4),
                'is_featured' => false,
                'status' => 'published',
            ],
            [
                'category' => 'Health & Wellness',
                'subcategory' => 'Fitness Centers',
                'title' => 'GymPass - 20% Off Annual Plan',
                'description' => '<p>Stay fit with GymPass. 20% off annual plan for members.</p>',
                'short_description' => '20% off GymPass annual plan',
                'partner_name' => 'GymPass',
                'partner_url' => 'https://www.gympass.com',
                'redeem_type' => 'lead_form',
                'location' => 'singapore',
                'valid_until' => now()->addMonths(2),
                'is_featured' => false,
                'status' => 'published',
            ],
            [
                'category' => 'Travel & Hospitality',
                'subcategory' => 'Hotels & Resorts',
                'title' => 'Hotels.com - 15% Off Select Properties',
                'description' => '<p>Enjoy 15% off select hotel bookings on Hotels.com</p>',
                'short_description' => '15% off select hotel bookings',
                'partner_name' => 'Hotels.com',
                'partner_url' => 'https://www.hotels.com',
                'redeem_type' => 'coupon_code',
                'coupon_code' => 'STAY15',
                'location' => 'global',
                'valid_until' => now()->addMonths(1),
                'is_featured' => true,
                'status' => 'published',
            ],
            [
                'category' => 'Education & Learning',
                'subcategory' => 'Online Courses',
                'title' => 'Udemy - Up to 70% Off Courses',
                'description' => '<p>Level up your skills with discounts on popular Udemy courses.</p>',
                'short_description' => 'Up to 70% off Udemy courses',
                'partner_name' => 'Udemy',
                'partner_url' => 'https://www.udemy.com',
                'redeem_type' => 'external_link',
                'external_url' => 'https://www.udemy.com/coupons',
                'location' => 'malaysia',
                'valid_until' => now()->addMonths(5),
                'is_featured' => false,
                'status' => 'published',
            ],
        ];

        foreach ($samples as $data) {
            $category = Category::where('name', $data['category'])->first();
            $subcategory = Subcategory::where('name', $data['subcategory'])->first();

            if (!$category || !$subcategory) {
                $this->command->warn("⚠️  Skipping perk '{$data['title']}' — category/subcategory not found.");
                continue;
            }

            Perk::create([
                'category_id' => $category->id,
                'subcategory_id' => $subcategory->id,
                'title' => $data['title'],
                'description' => $data['description'],
                'short_description' => $data['short_description'] ?? null,
                'partner_name' => $data['partner_name'],
                'partner_url' => $data['partner_url'] ?? null,
                'redeem_type' => $data['redeem_type'],
                'coupon_code' => $data['coupon_code'] ?? null,
                'external_url' => $data['external_url'] ?? null,
                'location' => $data['location'] ?? 'global',
                'valid_until' => $data['valid_until'] ?? null,
                'is_featured' => $data['is_featured'] ?? false,
                'is_active' => true,
                'status' => $data['status'] ?? 'published',
                'published_at' => ($data['status'] ?? 'published') === 'published' ? now() : null,
            ]);
        }

        $this->command->info('✅ Sample perks seeded!');
    }
}
