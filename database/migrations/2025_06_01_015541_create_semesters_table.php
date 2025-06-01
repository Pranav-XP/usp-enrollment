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
        Schema::create('semesters', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('year'); // e.g., 2025, 2026
            $table->unsignedTinyInteger('term'); // e.g., 1, 2, 3 (for S1, S2, Summer if applicable)
            $table->string('name')->unique(); // e.g., "2025 Semester 1", "2025 Summer"
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(false); // For administrative control

            // Add a unique constraint to prevent duplicate semester entries (e.g., 2025 Semester 1 cannot exist twice)
            $table->unique(['year', 'term']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('semesters');
    }
};
