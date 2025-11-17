<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('perks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('subcategory_id')->nullable()->constrained()->onDelete('set null');

            // Basic Information
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('short_description', 500)->nullable();

            // Partner Information
            $table->string('partner_name');
            $table->string('partner_logo')->nullable();
            $table->string('partner_url', 500)->nullable();

            // Redemption Configuration
            $table->enum('redeem_type', ['external_link', 'coupon_code', 'lead_form']);
            $table->string('coupon_code', 100)->nullable();
            $table->string('external_url', 500)->nullable();

            // Location & Validity
            $table->enum('location', ['malaysia', 'singapore', 'global'])->default('global');
            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable();

            // Status & Display
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->integer('display_order')->default(0);

            // Timestamps
            $table->timestamps();
            $table->timestamp('published_at')->nullable();

            // Indexes
            $table->index('slug');
            $table->index('category_id');
            $table->index('subcategory_id');
            $table->index('status');
            $table->index('is_active');
            $table->index('is_featured');
            $table->index('location');
            $table->index('published_at');
        });

        // Add full-text search index for PostgreSQL
        DB::statement("CREATE INDEX perks_search_idx ON perks USING GIN(to_tsvector('english', title || ' ' || description))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perks');
    }
};
