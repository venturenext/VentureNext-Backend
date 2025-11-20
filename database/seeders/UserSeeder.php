<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin
        User::updateOrCreate(
            ['email' => 'admin@perkpal.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
            ]
        );

        // Create Content Editors
        User::updateOrCreate(
            ['email' => 'editor@perkpal.com'],
            [
                'name' => 'Content Editor',
                'password' => Hash::make('password'),
                'role' => 'content_editor',
            ]
        );

        User::updateOrCreate(
            ['email' => 'john@perkpal.com'],
            [
                'name' => 'John Editor',
                'password' => Hash::make('password'),
                'role' => 'content_editor',
            ]
        );

        $this->command->info('✅ Users seeded successfully!');
    }
}
