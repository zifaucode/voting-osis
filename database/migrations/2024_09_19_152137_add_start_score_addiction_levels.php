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
        Schema::table('addiction_levels', function (Blueprint $table) {
            $table->integer('start_score')->nullable()->after('solution');
            $table->integer('end_score')->nullable()->after('start_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addiction_levels', function (Blueprint $table) {
            $table->dropColumn(['start_score', 'end_score']);
        });
    }
};
