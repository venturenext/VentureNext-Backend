<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix OG image URLs yang tersimpan sebagai full URL
        // Ubah dari format: http://localhost:8000/storage/perks/og-images/xxx.jpg
        // Menjadi: perks/og-images/xxx.jpg

        DB::table('perk_seo')
            ->whereNotNull('og_image')
            ->orderBy('id')
            ->each(function ($seo) {
                $ogImage = $seo->og_image;

                // Cek apakah og_image berisi full URL
                if (str_contains($ogImage, '/storage/')) {
                    // Extract path setelah /storage/
                    $path = substr($ogImage, strpos($ogImage, '/storage/') + 9);

                    DB::table('perk_seo')
                        ->where('id', $seo->id)
                        ->update(['og_image' => $path]);
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse this data cleanup
    }
};
