<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('page_contents', function (Blueprint $table) {
            $table->id();
            $table->string('page_name'); // homepage, perks, journal, partner, about, contact, terms, privacy, topbar
            $table->string('section_type'); // hero, cards, content, faq, list_settings, logo_title
            $table->string('section_key')->unique(); // unique identifier e.g., homepage_hero
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->json('content')->nullable(); // flexible JSON data for cards, FAQs, etc.
            $table->string('image_url')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('page_name');
            $table->index('section_type');
            $table->index(['page_name', 'display_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_contents');
    }
};
