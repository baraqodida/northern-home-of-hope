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
            // This safely injects the group relationship column into the users table
            $table->foreignId('group_id')
                  ->nullable()
                  ->after('status')
                  ->constrained('groups')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // This rolls back the changes cleanly if needed
            $table->dropForeign(['group_id']);
            $table->dropColumn('group_id');
        });
    }
};