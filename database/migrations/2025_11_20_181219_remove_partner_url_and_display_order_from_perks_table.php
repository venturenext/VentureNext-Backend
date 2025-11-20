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
        Schema::table('perks', function (Blueprint $table) {
            $table->dropColumn(['partner_url', 'display_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perks', function (Blueprint $table) {
            $table->string('partner_url')->nullable()->after('partner_name');
            $table->unsignedInteger('display_order')->default(0)->after('is_featured');
        });
    }
};
