<?php

namespace Database\Seeders;

use App\Models\Lead;
use App\Models\Perk;
use Illuminate\Database\Seeder;

class LeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $perk = Perk::first();

        // Sample Perk Claim Leads
        Lead::updateOrCreate(
            ['email' => 'john.doe@example.com', 'lead_type' => 'perk_claim'],
            [
                'perk_id' => $perk?->id,
                'name' => 'John Doe',
                'company' => 'TechStartup Inc',
                'phone' => '+1234567890',
                'message' => 'Interested in claiming AWS credits for our startup',
                'metadata' => ['source' => 'web', 'device' => 'desktop'],
                'ip_address' => '192.168.1.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                'referrer' => 'https://google.com',
            ]
        );

        Lead::updateOrCreate(
            ['email' => 'jane.smith@startup.com', 'lead_type' => 'perk_claim'],
            [
                'perk_id' => $perk?->id,
                'name' => 'Jane Smith',
                'company' => 'InnovateNow',
                'phone' => '+1987654321',
                'message' => 'Would like to redeem the cloud credits',
                'metadata' => ['source' => 'mobile', 'device' => 'ios'],
                'ip_address' => '192.168.1.2',
                'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0)',
            ]
        );

        // Sample Partner Inquiry Leads
        Lead::updateOrCreate(
            ['email' => 'michael@partner.com', 'lead_type' => 'partner_inquiry'],
            [
                'perk_id' => null,
                'name' => 'Michael Chen',
                'company' => 'Partner Solutions LLC',
                'phone' => '+1555123456',
                'message' => 'We would like to explore partnership opportunities with PerkPal',
                'metadata' => ['source' => 'web', 'interest' => 'partnership'],
                'ip_address' => '192.168.1.3',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)',
                'referrer' => 'https://linkedin.com',
            ]
        );

        // Sample Contact Form Leads
        Lead::updateOrCreate(
            ['email' => 'sarah@email.com', 'lead_type' => 'contact_form'],
            [
                'perk_id' => null,
                'name' => 'Sarah Johnson',
                'phone' => '+1444567890',
                'message' => 'I have a question about how to use the platform',
                'metadata' => ['source' => 'web', 'page' => 'contact'],
                'ip_address' => '192.168.1.4',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            ]
        );

        Lead::updateOrCreate(
            ['email' => 'david.wilson@company.com', 'lead_type' => 'contact_form'],
            [
                'perk_id' => null,
                'name' => 'David Wilson',
                'company' => 'Enterprise Corp',
                'phone' => '+1333789012',
                'message' => 'Interested in enterprise plans',
                'metadata' => ['source' => 'web', 'interest' => 'enterprise'],
                'ip_address' => '192.168.1.5',
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64)',
            ]
        );

        $this->command->info('✅ Leads seeded successfully!');
    }
}
