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
        Schema::create('award_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('award_id')->constrained()->onDelete('cascade');
            $table->string('locale')->index(); // 'en', 'de', etc.
            
            $table->string('title');
            $table->string('organization');
            $table->string('tag');
            $table->text('description');

            $table->unique(['award_id', 'locale']); // Prevents duplicate translations for one award
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('award_translations');
    }
};
