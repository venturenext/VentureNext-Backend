<?php

namespace Database\Seeders;

use App\Models\PageContent;
use Illuminate\Database\Seeder;

class PageContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contents = [
            // ============ HOMEPAGE ============
            [
                'page_name' => 'homepage',
                'section_type' => 'hero',
                'section_key' => 'homepage_hero',
                'title' => 'Unlock Exclusive Perks',
                'subtitle' => 'Discover amazing deals and benefits designed for modern professionals',
                'content' => null,
                'image_url' => '/images/hero-bg.jpg',
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'page_name' => 'homepage',
                'section_type' => 'cards',
                'section_key' => 'homepage_how_it_works',
                'title' => 'How It Works',
                'subtitle' => 'Getting started with PerkPal is easy',
                'content' => [
                    [
                        'icon' => 'search',
                        'title' => 'Browse Perks',
                        'description' => 'Explore our curated collection of exclusive deals and benefits',
                    ],
                    [
                        'icon' => 'checkmark',
                        'title' => 'Choose Your Favorites',
                        'description' => 'Select the perks that best fit your lifestyle and needs',
                    ],
                    [
                        'icon' => 'gift',
                        'title' => 'Redeem & Enjoy',
                        'description' => 'Claim your perks and start saving immediately',
                    ],
                ],
                'image_url' => null,
                'display_order' => 2,
                'is_active' => true,
            ],
            [
                'page_name' => 'homepage',
                'section_type' => 'list_settings',
                'section_key' => 'homepage_latest_perks',
                'title' => 'Latest Perks',
                'subtitle' => 'Fresh deals added every week',
                'content' => [
                    'show_published' => true,
                    'show_draft' => false,
                    'limit' => 6,
                ],
                'image_url' => null,
                'display_order' => 3,
                'is_active' => true,
            ],
            [
                'page_name' => 'homepage',
                'section_type' => 'list_settings',
                'section_key' => 'homepage_journal',
                'title' => 'From The Journal',
                'subtitle' => 'Insights and stories for the modern professional',
                'content' => [
                    'show_published' => true,
                    'show_draft' => false,
                    'limit' => 3,
                ],
                'image_url' => null,
                'display_order' => 4,
                'is_active' => true,
            ],

            // ============ PERKS PAGE ============
            [
                'page_name' => 'perks',
                'section_type' => 'hero',
                'section_key' => 'perks_hero',
                'title' => 'Perks',
                'subtitle' => 'Find and redeem the best perks for your needs',
                'content' => null,
                'image_url' => '/images/perks-hero.jpg',
                'display_order' => 1,
                'is_active' => true,
            ],

            // ============ JOURNAL PAGE ============
            [
                'page_name' => 'journal',
                'section_type' => 'hero',
                'section_key' => 'journal_hero',
                'title' => 'The Journal',
                'subtitle' => 'Stories, insights, and inspiration for professionals',
                'content' => null,
                'image_url' => '/images/journal-hero.jpg',
                'display_order' => 1,
                'is_active' => true,
            ],

            // ============ PARTNER PAGE ============
            [
                'page_name' => 'partner',
                'section_type' => 'hero',
                'section_key' => 'partner_hero',
                'title' => 'Partner With Us',
                'subtitle' => 'Grow your business with PerkPal',
                'content' => null,
                'image_url' => '/images/partner-hero.jpg',
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'page_name' => 'partner',
                'section_type' => 'cards',
                'section_key' => 'partner_why_partner',
                'title' => 'Why Partner With Us?',
                'subtitle' => 'Join our network of leading brands and gain access to a thriving community of professionals',
                'content' => [
                    [
                        'icon' => 'users',
                        'title' => 'Reach Professionals',
                        'description' => 'Connect with thousands of engaged professionals actively seeking quality services',
                    ],
                    [
                        'icon' => 'trending-up',
                        'title' => 'Increase Visibility',
                        'description' => 'Showcase your brand to a targeted audience ready to explore new offerings',
                    ],
                    [
                        'icon' => 'handshake',
                        'title' => 'Build Relationships',
                        'description' => 'Create lasting connections with customers who value quality and trust',
                    ],
                ],
                'image_url' => null,
                'display_order' => 2,
                'is_active' => true,
            ],
            [
                'page_name' => 'partner',
                'section_type' => 'cards',
                'section_key' => 'partner_how_it_works',
                'title' => 'How It Works',
                'subtitle' => 'Simple steps to become a partner',
                'content' => [
                    [
                        'icon' => 'file-text',
                        'title' => 'Submit Application',
                        'description' => 'Fill out our simple partnership form with your business details',
                    ],
                    [
                        'icon' => 'message-circle',
                        'title' => 'Review & Approval',
                        'description' => 'Our team will review your application and get in touch within 48 hours',
                    ],
                    [
                        'icon' => 'rocket',
                        'title' => 'Launch Your Perks',
                        'description' => 'Once approved, create and launch your exclusive perks to our community',
                    ],
                ],
                'image_url' => null,
                'display_order' => 3,
                'is_active' => true,
            ],
            [
                'page_name' => 'partner',
                'section_type' => 'faq',
                'section_key' => 'partner_faq',
                'title' => 'Frequently Asked Questions',
                'subtitle' => 'Got questions? We have answers',
                'content' => [
                    [
                        'question' => 'What types of businesses can become partners?',
                        'answer' => 'We welcome a wide range of businesses, from restaurants and fitness centers to professional services and retail stores. If you offer value to professionals, we would love to hear from you.',
                    ],
                    [
                        'question' => 'Is there a cost to become a partner?',
                        'answer' => 'We offer flexible partnership models. Contact us to discuss the best option for your business.',
                    ],
                    [
                        'question' => 'How do I track my perk performance?',
                        'answer' => 'Partners receive access to a dashboard with real-time analytics on views, claims, and customer engagement.',
                    ],
                ],
                'image_url' => null,
                'display_order' => 4,
                'is_active' => true,
            ],

            // ============ ABOUT PAGE ============
            [
                'page_name' => 'about',
                'section_type' => 'hero',
                'section_key' => 'about_hero',
                'title' => 'About PerkPal',
                'subtitle' => 'Connecting professionals with exclusive benefits',
                'content' => null,
                'image_url' => '/images/about-hero.jpg',
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'page_name' => 'about',
                'section_type' => 'cards',
                'section_key' => 'about_what_we_do',
                'title' => 'What We Do',
                'subtitle' => 'Our mission is to enhance your professional lifestyle',
                'content' => [
                    [
                        'icon' => 'target',
                        'title' => 'Curate Quality Perks',
                        'description' => 'We carefully select and verify each partner to ensure you receive genuine value',
                    ],
                    [
                        'icon' => 'shield',
                        'title' => 'Build Trust',
                        'description' => 'Every partnership is built on transparency and mutual benefit',
                    ],
                    [
                        'icon' => 'heart',
                        'title' => 'Create Value',
                        'description' => 'We negotiate exclusive deals that make a real difference in your daily life',
                    ],
                ],
                'image_url' => null,
                'display_order' => 2,
                'is_active' => true,
            ],
            [
                'page_name' => 'about',
                'section_type' => 'cards',
                'section_key' => 'about_who_we_serve',
                'title' => 'Who We Serve',
                'subtitle' => 'Supporting professionals at every stage',
                'content' => [
                    [
                        'icon' => 'briefcase',
                        'title' => 'Working Professionals',
                        'description' => 'Access perks that complement your busy lifestyle and career growth',
                    ],
                    [
                        'icon' => 'trending-up',
                        'title' => 'Entrepreneurs',
                        'description' => 'Discover services and tools to help scale your business efficiently',
                    ],
                    [
                        'icon' => 'graduation-cap',
                        'title' => 'Recent Graduates',
                        'description' => 'Start your professional journey with benefits designed for newcomers',
                    ],
                ],
                'image_url' => null,
                'display_order' => 3,
                'is_active' => true,
            ],
            [
                'page_name' => 'about',
                'section_type' => 'faq',
                'section_key' => 'about_faq',
                'title' => 'Frequently Asked Questions',
                'subtitle' => 'Everything you need to know',
                'content' => [
                    [
                        'question' => 'How do I access the perks?',
                        'answer' => 'Simply browse our perks directory, select the ones you like, and follow the redemption instructions. Most perks can be claimed instantly.',
                    ],
                    [
                        'question' => 'Are there any membership fees?',
                        'answer' => 'PerkPal is completely free to use. There are no hidden fees or membership costs.',
                    ],
                    [
                        'question' => 'How often are new perks added?',
                        'answer' => 'We add new perks weekly, ensuring fresh deals and opportunities for our community.',
                    ],
                ],
                'image_url' => null,
                'display_order' => 4,
                'is_active' => true,
            ],

            // ============ CONTACT PAGE ============
            [
                'page_name' => 'contact',
                'section_type' => 'hero',
                'section_key' => 'contact_hero',
                'title' => 'Get In Touch',
                'subtitle' => 'We would love to hear from you',
                'content' => null,
                'image_url' => '/images/contact-hero.jpg',
                'display_order' => 1,
                'is_active' => true,
            ],

            // ============ TERMS OF SERVICE ============
            [
                'page_name' => 'terms',
                'section_type' => 'hero',
                'section_key' => 'terms_hero',
                'title' => 'Terms of Service',
                'subtitle' => 'Last updated: January 2025',
                'content' => null,
                'image_url' => '/images/legal-hero.jpg',
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'page_name' => 'terms',
                'section_type' => 'content',
                'section_key' => 'terms_content',
                'title' => 'Terms & Conditions',
                'subtitle' => null,
                'content' => [
                    'html' => '<h2>1. Acceptance of Terms</h2><p>By accessing and using PerkPal, you accept and agree to be bound by these Terms of Service.</p><h2>2. Use of Service</h2><p>You agree to use this service only for lawful purposes and in accordance with these Terms.</p><h2>3. Intellectual Property</h2><p>All content on this platform is protected by copyright and trademark laws.</p><h2>4. Limitation of Liability</h2><p>PerkPal shall not be liable for any indirect, incidental, or consequential damages.</p>',
                ],
                'image_url' => null,
                'display_order' => 2,
                'is_active' => true,
            ],

            // ============ PRIVACY POLICY ============
            [
                'page_name' => 'privacy',
                'section_type' => 'hero',
                'section_key' => 'privacy_hero',
                'title' => 'Privacy Policy',
                'subtitle' => 'Last updated: January 2025',
                'content' => null,
                'image_url' => '/images/legal-hero.jpg',
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'page_name' => 'privacy',
                'section_type' => 'content',
                'section_key' => 'privacy_content',
                'title' => 'Privacy Policy',
                'subtitle' => null,
                'content' => [
                    'html' => '<h2>1. Information We Collect</h2><p>We collect information you provide directly to us, such as when you create an account or contact us.</p><h2>2. How We Use Your Information</h2><p>We use the information we collect to provide, maintain, and improve our services.</p><h2>3. Information Sharing</h2><p>We do not share your personal information with third parties except as described in this policy.</p><h2>4. Data Security</h2><p>We implement appropriate technical and organizational measures to protect your personal data.</p><h2>5. Your Rights</h2><p>You have the right to access, update, or delete your personal information at any time.</p>',
                ],
                'image_url' => null,
                'display_order' => 2,
                'is_active' => true,
            ],

            // ============ TOP BAR ============
            [
                'page_name' => 'topbar',
                'section_type' => 'logo_title',
                'section_key' => 'topbar_logo',
                'title' => 'PerkPal',
                'subtitle' => null,
                'content' => [
                    'logo_url' => '/images/logo.png',
                    'logo_alt' => 'PerkPal Logo',
                ],
                'image_url' => '/images/logo.png',
                'display_order' => 1,
                'is_active' => true,
            ],
        ];

        foreach ($contents as $content) {
            PageContent::updateOrCreate(
                ['section_key' => $content['section_key']],
                $content
            );
        }

        $this->command->info('Page content seeded successfully!');
    }
}
