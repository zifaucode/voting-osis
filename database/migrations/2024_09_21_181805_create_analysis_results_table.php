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
        Schema::create('analysis_results', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('addiction_level_id')->nullable();
            $table->text('symptom_name')->nullable();
            $table->integer('cf_score')->nullable();
            $table->integer('addiction_score')->nullable();
            $table->text('rules_value_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analysis_results');
    }
};
