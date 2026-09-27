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
        Schema::table('osis_chairman_candidates', function (Blueprint $table) {
            if (!Schema::hasColumn('osis_chairman_candidates', 'vice_class')) {
                $table->string('vice_class')->nullable()->after('vice_name');
            }
            if (!Schema::hasColumn('osis_chairman_candidates', 'slogan')) {
                $table->string('slogan')->nullable()->after('class');
            }
            if (!Schema::hasColumn('osis_chairman_candidates', 'work_program')) {
                $table->text('work_program')->nullable()->after('misi');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('osis_chairman_candidates', function (Blueprint $table) {
            $columnsToDrop = [];
            if (Schema::hasColumn('osis_chairman_candidates', 'vice_class')) {
                $columnsToDrop[] = 'vice_class';
            }
            if (Schema::hasColumn('osis_chairman_candidates', 'slogan')) {
                $columnsToDrop[] = 'slogan';
            }
            if (Schema::hasColumn('osis_chairman_candidates', 'work_program')) {
                $columnsToDrop[] = 'work_program';
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
