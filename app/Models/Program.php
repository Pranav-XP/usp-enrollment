<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    // Define the fillable properties for mass assignment
    protected $fillable = [
        'program_code',
        'name',
        'description',
        'duration',
    ];

    /**
     * Get the students associated with the program.
     */
    public function students()
    {
        // A program has many students
        return $this->hasMany(Student::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_program');
    }
}
