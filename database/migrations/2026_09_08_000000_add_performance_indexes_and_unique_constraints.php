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
        Schema::table('users', function (Blueprint $table) {
            $table->index('code_access', 'idx_users_code_access');
            $table->index('nisn', 'idx_users_nisn');
            $table->index('vote_status', 'idx_users_vote_status');
            $table->index('role', 'idx_users_role');
            $table->index('is_vote', 'idx_users_is_vote');
        });

        Schema::table('total_vote_users', function (Blueprint $table) {
            $table->unique('user_id', 'uniq_total_vote_users_user_id');
            $table->index('candidate_id', 'idx_total_vote_users_candidate_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_code_access');
            $table->dropIndex('idx_users_nisn');
            $table->dropIndex('idx_users_vote_status');
            $table->dropIndex('idx_users_role');
            $table->dropIndex('idx_users_is_vote');
        });

        Schema::table('total_vote_users', function (Blueprint $table) {
            $table->dropUnique('uniq_total_vote_users_user_id');
            $table->dropIndex('idx_total_vote_users_candidate_id');
        });
    }
};
