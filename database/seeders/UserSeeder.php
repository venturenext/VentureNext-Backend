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
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@perkpal.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
        ]);

        // Create Content Editors
        User::create([
            'name' => 'Content Editor',
            'email' => 'editor@perkpal.com',
            'password' => Hash::make('password'),
            'role' => 'content_editor',
        ]);

        User::create([
            'name' => 'John Editor',
            'email' => 'john@perkpal.com',
            'password' => Hash::make('password'),
            'role' => 'content_editor',
        ]);

        $this->command->info('✅ Users seeded successfully!');
    }
}
