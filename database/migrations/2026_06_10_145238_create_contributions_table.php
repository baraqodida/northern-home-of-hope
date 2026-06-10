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
  Schema::create('contributions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Links to the member
    $table->decimal('amount', 10, 2);                                // e.g., 1500.00
    $table->string('purpose');                                       // e.g., "Humanitarian Support", "Monthly Fee"
    $table->string('status')->default('completed');                  // completed, pending, failed
    $table->timestamps();                                            // Automatically tracks created_at and updated_at dates
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contributions');
    }
};
