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
        Schema::create('courses', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->string('course_code')->unique(); // Unique course code
            $table->string('course_title'); // Course title
            $table->integer('year'); // Example: Year
            
            $table->text('description')->nullable(); // Course description
            $table->decimal('cost', 8, 2); // Course cost

            // Adding two boolean columns to track whether the course is offered in Semester 1 or Semester 2
            $table->boolean('semester_1')->default(false); // True if offered in Semester 1
            $table->boolean('semester_2')->default(false); // True if offered in Semester 2
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
