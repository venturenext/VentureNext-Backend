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
        Schema::create('perk_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perk_id')->constrained()->onDelete('cascade');
            $table->enum('media_type', ['logo', 'banner', 'gallery']);
            $table->string('file_path', 500);
            $table->string('file_name');
            $table->string('mime_type', 100)->nullable();
            $table->integer('file_size')->nullable();
            $table->string('alt_text')->nullable();
            $table->integer('display_order')->default(0);
            $table->timestamp('created_at')->useCurrent();

            $table->index('perk_id');
            $table->index('media_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perk_media');
    }
};
