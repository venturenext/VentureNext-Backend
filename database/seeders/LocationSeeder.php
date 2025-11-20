<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            [
                'name' => 'Global',
                'slug' => 'global',
                'is_active' => true,
            ],
            [
                'name' => 'Malaysia',
                'slug' => 'malaysia',
                'is_active' => true,
            ],
            [
                'name' => 'Singapore',
                'slug' => 'singapore',
                'is_active' => true,
            ],
        ];

        foreach ($locations as $location) {
            Location::updateOrCreate(['slug' => $location['slug']], $location);
        }

        $this->command->info('Locations seeded successfully.');
    }
}
