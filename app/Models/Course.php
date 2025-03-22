<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
     // Fillable properties for mass assignment
     protected $fillable = [
        'course_code', 'course_title', 'description', 'cost', 'semester_1', 'semester_2',
    ];

    public function programs()
    {
        return $this->belongsToMany(Program::class, 'course_program');
    }

    // Define a many-to-many relationship with prerequisites
    public function prerequisites()
    {
        return $this->belongsToMany(Course::class, 'course_prerequisite', 'course_id', 'prerequisite_course_id');
    }

    // Get courses that depend on this one as a prerequisite
    public function dependentCourses()
    {
        return $this->belongsToMany(Course::class, 'course_prerequisite', 'prerequisite_course_id', 'course_id');
    }
}
