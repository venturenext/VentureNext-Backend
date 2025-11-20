<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\JournalPost;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class JournalPostSeeder extends Seeder
{
    public function run(): void
    {
        $samples = [
            [
                'title' => 'Boost Your Productivity with These Top Tools',
                'excerpt' => 'Explore the best productivity tools to enhance your workflow and achieve more in less time.',
                'content' => '<p>We curated a list of tools that help founders and remote workers save hours each week...</p>',
                'cover_url' => 'https://i.pinimg.com/1200x/67/20/2a/67202a7e3f5f4b1a9b3b9ad6f4e1a9a8.jpg',
                'category' => 'Productivity',
                'tags' => ['productivity','tools'],
                'author_name' => 'Sarah Mitchell',
                'avatar_url' => 'https://i.pinimg.com/1200x/9a/aa/11/9aaa11aa22bb33cc44dd55ee66ff778.jpg',
                'is_published' => true,
                'published_at' => now()->subDays(10),
            ],
            [
                'title' => 'Mastering Social Media Marketing: A Comprehensive Guide',
                'excerpt' => 'Learn effective social media marketing techniques to grow your brand and engage your audience.',
                'content' => '<p>From content pillars to analytics, this guide covers the essentials...</p>',
                'cover_url' => 'https://i.pinimg.com/1200x/3a/5c/1b/3a5c1b4f6b9d4e2ab3c71a9e5f0a3d21.jpg',
                'category' => 'Marketing',
                'tags' => ['marketing'],
                'author_name' => 'Daniel Wong',
                'avatar_url' => 'https://i.pinimg.com/1200x/ab/cd/ef/abcdef1234567890fedcba0987654321.jpg',
                'is_published' => true,
                'published_at' => now()->subDays(11),
            ],
            [
                'title' => 'Financial Planning for Freelancers: Tips and Strategies',
                'excerpt' => 'Essential planning tips and strategies tailored for freelancers in MY & SG.',
                'content' => '<p>Budgeting, taxes, and long-term planning—here is how to tackle them...</p>',
                'cover_url' => 'https://i.pinimg.com/1200x/5f/9d/8b/5f9d8b9a2f3d47a0b8a2dc01f2e4c3ab.jpg',
                'category' => 'Finance',
                'tags' => ['finance','freelance'],
                'author_name' => 'Aisha Rahman',
                'avatar_url' => 'https://i.pinimg.com/1200x/12/34/56/1234567890abcdef1234567890abcdef.jpg',
                'is_published' => true,
                'published_at' => now()->subDays(12),
            ],
            [
                'title' => 'Top 10 Tools for Asynchronous Collaboration',
                'excerpt' => 'Work effectively across time zones with these collaboration practices and apps.',
                'content' => '<p>Async-first teams thrive with clear documentation, the right tools, and smart rituals...</p>',
                'cover_url' => 'https://i.pinimg.com/1200x/4a/6e/7c/4a6e7c9bd3a24f5a9f7e123b0c8d9ef1.jpg',
                'category' => 'Remote Work',
                'tags' => ['remote','collaboration'],
                'author_name' => 'Kenji Tan',
                'avatar_url' => 'https://i.pinimg.com/1200x/22/33/44/2233445566778899aabbccddeeff0011.jpg',
                'is_published' => true,
                'published_at' => now()->subDays(13),
            ],
            [
                'title' => '5 Essential Steps to Register Your Business in Singapore',
                'excerpt' => 'A concise guide to navigate ACRA requirements and get incorporated fast.',
                'content' => '<p>From choosing your business structure to UEN registration—here are the key steps...</p>',
                'cover_url' => 'https://i.pinimg.com/1200x/f0/1c/2d/f01c2d7abf5d47f2b3a8e90a1c3f4b2e.jpg',
                'category' => 'Business Guide',
                'tags' => ['singapore','acra','legal'],
                'author_name' => 'Grace Lim',
                'avatar_url' => 'https://i.pinimg.com/1200x/66/77/88/66778899aabbccddeeff001122334455.jpg',
                'is_published' => true,
                'published_at' => now()->subDays(14),
            ],
            [
                'title' => 'Design Systems 101 for Early Teams',
                'excerpt' => 'Create a simple, scalable design system to speed up your product delivery.',
                'content' => '<p>Tokens, components, documentation—start small and evolve with your team...</p>',
                'cover_url' => 'https://i.pinimg.com/1200x/8b/2e/44/8b2e44a1c7f04e10a2b9c1d3e5f6a7b8.jpg',
                'category' => 'Design',
                'tags' => ['design','systems'],
                'author_name' => 'Marco Chen',
                'avatar_url' => 'https://i.pinimg.com/1200x/aa/bb/cc/aabbccddeeff00112233445566778899.jpg',
                'is_published' => true,
                'published_at' => now()->subDays(15),
            ],
        ];

        foreach ($samples as $data) {
            $categoryName = $data['category'] ?? null;
            $coverPath = $this->storeFromUrl($data['cover_url'] ?? null, 'journal/covers');
            $avatarPath = $this->storeFromUrl($data['avatar_url'] ?? null, 'journal/authors');

            unset($data['cover_url'], $data['avatar_url']);
            $data['cover_image'] = $coverPath;
            $data['author_avatar'] = $avatarPath;
            unset($data['category']);

            if ($categoryName) {
                $category = Category::where('name', $categoryName)->first();
                $data['category_id'] = $category?->id;
            }

            JournalPost::updateOrCreate(
                ['title' => $data['title']],
                $data
            );
        }

        $this->command?->info('Journal posts seeded.');
    }

    private function storeFromUrl(?string $url, string $dir): ?string
    {
        if (!$url) return null;
        try {
            $resp = Http::timeout(20)->get($url);
            if (!$resp->ok()) return null;
            $ext = pathinfo(parse_url($url, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION) ?: 'jpg';
            $name = Str::uuid()->toString() . '.' . $ext;
            $path = trim($dir, '/') . '/' . $name;
            Storage::disk('public')->put($path, $resp->body());
            return $path;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
