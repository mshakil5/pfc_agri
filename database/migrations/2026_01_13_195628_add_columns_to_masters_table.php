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
        Schema::table('masters', function (Blueprint $table) {
            // Add your new fields here
            $table->string('pages')->nullable()->after('id'); 
            $table->string('feature_image')->nullable()->after('long_description'); 
            $table->text('extra1')->nullable()->after('feature_image'); 
            $table->text('extra2')->nullable()->after('extra1'); 
            $table->boolean('status')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('masters', function (Blueprint $table) {
            //
        });
    }
};
