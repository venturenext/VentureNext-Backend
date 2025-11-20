<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('journal_posts', function (Blueprint $table) {
            $table->foreignId('category_id')
                ->nullable()
                ->after('cover_image')
                ->constrained()
                ->nullOnDelete();
        });

        DB::statement("
            UPDATE journal_posts
            SET category_id = categories.id
            FROM categories
            WHERE journal_posts.category_id IS NULL
              AND journal_posts.category IS NOT NULL
              AND LOWER(categories.name) = LOWER(journal_posts.category)
        ");

        Schema::table('journal_posts', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }

    public function down(): void
    {
        Schema::table('journal_posts', function (Blueprint $table) {
            $table->string('category', 120)->nullable()->after('cover_image');
        });

        DB::statement("
            UPDATE journal_posts
            SET category = categories.name
            FROM categories
            WHERE journal_posts.category_id = categories.id
        ");

        Schema::table('journal_posts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
        });
    }
};
