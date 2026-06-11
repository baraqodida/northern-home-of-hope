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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            // Link to the user who performed the action
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // What happened (e.g., "CREATE", "UPDATE")
            $table->string('action');
            // The specific table (Contribution) and the record ID
            $table->string('target_model')->default('Contribution');
            $table->unsignedBigInteger('target_id');
            // Details of the transaction
            $table->text('details');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};