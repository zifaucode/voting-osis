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
            $table->decimal('cf_addiction_value', 2, 1)->nullable()->after('end_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addiction_levels', function (Blueprint $table) {
            $table->dropColumn('cf_addiction_value');
        });
    }
};
