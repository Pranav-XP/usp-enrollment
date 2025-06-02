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
        Schema::create('grade_recheck_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade'); // Link to the Course being rechecked

            // Section A: Personal Details (some might be redundant if fetched from Student model)
            // Storing them here for the record as per form, but validate against student data.
            $table->string('full_name');
            $table->string('postal_address')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('telephone');
            $table->string('email');
            $table->string('sponsorship_status'); // 'Private' or 'Sponsored'

            // Section B: Request Details
            $table->string('course_code'); // Redundant with course_id but included as per form
            $table->string('course_title'); // Redundant with course_id but included as per form
            $table->string('course_lecturer_name');
            $table->string('receipt_no')->nullable();
            $table->string('payment_confirmation_path')->nullable(); // Path to the uploaded file

            // Application Status
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->text('admin_notes')->nullable(); // For admin feedback

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_recheck_applications');
    }
};
