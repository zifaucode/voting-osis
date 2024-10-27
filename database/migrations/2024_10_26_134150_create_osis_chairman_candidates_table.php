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
        Schema::create('osis_chairman_candidates', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('vice_name')->nullable();
            $table->string('sequence_number')->nullable();
            $table->string('class')->nullable();
            $table->text('visi')->nullable();
            $table->text('misi')->nullable();
            $table->string('image')->nullable();
            $table->bigInteger('vote_total')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('osis_chairman_candidates');
    }
};
