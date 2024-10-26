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
        Schema::table('options', function (Blueprint $table) {
            $table->decimal('mb_value', 2, 1)->nullable()->after('cf_value');
            $table->decimal('md_value', 2, 1)->nullable()->after('mb_value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('options', function (Blueprint $table) {
            $table->dropColumn(['mb_value', 'md_value']);
        });
    }
};
