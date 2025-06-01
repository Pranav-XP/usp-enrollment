<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CourseStudent extends Pivot
{
    protected $table = 'course_student';

    protected $fillable = [
        'student_id',
        'course_id',
        'grade',
        'status',
        'semester_id',
    ];

    // Define relationships to the main models
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    /**
     * Get the letter grade for the course enrollment based on numerical grade.
     *
     * @return string
     */
    public function getLetterGradeAttribute(): string
    {
        $numericalGrade = $this->grade;

        // Handle cases where grade is null or not set
        if ($numericalGrade === null) {
            return 'N/A';
        }

        // Apply the grading scale
        if ($numericalGrade >= 4.5) return 'A+';
        if ($numericalGrade >= 4.0) return 'A';
        if ($numericalGrade >= 3.5) return 'B+';
        if ($numericalGrade >= 3.0) return 'B';
        if ($numericalGrade >= 2.5) return 'C+';
        if ($numericalGrade >= 2.0) return 'C';
        if ($numericalGrade >= 1.5) return 'R';
        if ($numericalGrade >= 1.0) return 'D'; // Assuming D for 1.0, DX if needed
        if ($numericalGrade >= 0) return 'E'; // Assuming E for 0, EX if needed

        return 'Invalid'; // Fallback for unexpected numerical values
    }
}
