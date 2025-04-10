<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    // Fillable properties for mass assignment
    protected $fillable = [
        'course_code',
        'course_title',
        'description',
        'cost',
        'semester_1',
        'semester_2',
        'year'
    ];

    public function programs(): BelongsToMany
    {
        return $this->belongsToMany(Program::class, 'course_program')->withTimestamps();
    }

    //Many to Many students
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'course_student', 'course_id', 'student_id')
            ->withPivot('grade', 'enrollment_status')
            ->withTimestamps();
    }

    // Define the relationship between Course and Prerequisite
    public function prerequisites()
    {
        return $this->hasMany(Prerequisite::class, 'course_id'); // assuming 'course_id' is the foreign key
    }

    public function transactions()
    {
        return $this->belongsToMany(Transaction::class, 'course_transaction');
    }
}
