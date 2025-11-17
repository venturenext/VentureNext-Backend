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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perk_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('lead_type', ['perk_claim', 'partner_inquiry', 'contact_form', 'contact']);
            $table->string('name');
            $table->string('email');
            $table->string('company')->nullable();
            $table->string('phone', 50)->nullable();
            $table->text('message')->nullable();
            $table->json('metadata')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('referrer', 500)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('perk_id');
            $table->index('lead_type');
            $table->index('email');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
