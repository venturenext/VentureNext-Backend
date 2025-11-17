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
        Schema::create('perk_statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perk_id')->unique()->constrained()->onDelete('cascade');

            // View Statistics
            $table->integer('view_count')->default(0);
            $table->integer('unique_views')->default(0);
            $table->timestamp('last_viewed_at')->nullable();

            // Claim Statistics
            $table->integer('claim_count')->default(0);
            $table->timestamp('last_claimed_at')->nullable();

            // Click Statistics
            $table->integer('coupon_copy_count')->default(0);
            $table->integer('external_link_clicks')->default(0);

            // Engagement Metrics
            $table->decimal('conversion_rate', 5, 2)->default(0); // Percentage
            $table->integer('average_time_on_page')->default(0); // Seconds

            $table->timestamps();

            $table->index('perk_id');
            $table->index('view_count');
            $table->index('claim_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perk_statistics');
    }
};
