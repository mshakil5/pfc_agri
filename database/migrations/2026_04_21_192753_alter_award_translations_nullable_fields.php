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
        Schema::table('award_translations', function (Blueprint $table) {
            $table->string('organization')->nullable()->change();
            $table->string('tag')->nullable()->change();
            $table->text('description')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('award_translations', function (Blueprint $table) {
            $table->string('organization')->nullable(false)->change();
            $table->string('tag')->nullable(false)->change();
            $table->text('description')->nullable(false)->change();
        });
    }
};
