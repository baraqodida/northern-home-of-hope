<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contribution_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contribution_id')->constrained('contributions')->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->string('week_label');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contribution_histories');
    }
};