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
        Schema::table('sliders', function (Blueprint $table) {
            $table->dropColumn(['title', 'sub_title', 'hero_badge', 'stat_card', 'buttons']);
        });

        Schema::create('slider_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('slider_id')->constrained()->onDelete('cascade');
            $table->string('locale')->index();

            $table->string('title')->nullable();
            $table->string('sub_title')->nullable();
            $table->string('hero_badge')->nullable();
            $table->longText('buttons')->nullable();
            $table->longText('stat_card')->nullable();

            $table->unique(['slider_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slider_translations');

        Schema::table('sliders', function (Blueprint $table) {
            $table->string('title')->nullable();
            $table->string('sub_title')->nullable();
            $table->string('hero_badge')->nullable();
            $table->longText('stat_card')->nullable();
            $table->longText('buttons')->nullable();
        });
    }
};
