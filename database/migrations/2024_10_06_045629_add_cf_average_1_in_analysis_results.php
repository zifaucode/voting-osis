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
        Schema::table('analysis_results', function (Blueprint $table) {
            $table->decimal('cf_average_1', 3, 1)->nullable()->after('addiction_score');
            $table->decimal('cf_average_2', 3, 1)->nullable()->after('cf_average_1');
            $table->decimal('cf_last', 3, 1)->nullable()->after('cf_average_2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('analysis_results', function (Blueprint $table) {
            $table->dropColumn(['cf_average_1', 'cf_average_2', 'cf_last']);
        });
    }
};
