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
        Schema::create('students', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade'); // Link to users table
            $table->string('student_id')->unique(); // Unique student identifier
            $table->string('first_name');
            $table->string('last_name');
            $table->date('dob');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->foreignId('program_id')->nullable()->constrained('programs')->onDelete('cascade'); // Links to the programs table
            $table->year('enrollment_year'); // Year student enrolled
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
