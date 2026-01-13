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
        Schema::create('abouts', function (Blueprint $table) {
            $table->id();
            $table->string('pages')->nullable(); 
            $table->string('title')->nullable(); 
            $table->string('sub_title')->nullable(); 
            $table->string('year')->nullable();
            $table->string('slug')->nullable();
            $table->string('image')->nullable(); 
            $table->string('header_title')->nullable(); 
            $table->string('header_subtitle')->nullable(); 
            $table->longText('amenities')->nullable(); 
            $table->boolean('status')->default(1);
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abouts');
    }
};
