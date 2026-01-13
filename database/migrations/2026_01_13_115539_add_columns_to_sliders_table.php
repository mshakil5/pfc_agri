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
        // Add your new columns here
            $table->string('hero_badge')->nullable()->after('sub_title'); 
            $table->longText('stat_card')->nullable()->after('hero_badge'); 
            $table->longText('buttons')->nullable()->after('stat_card'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            //
        });
    }
};
