<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    // Removed 'course_id' since we now use a pivot table
    protected $fillable = ['student_id', 'reference_number', 'amount', 'status'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // Many-to-many relationship with courses through course_transaction pivot table
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_transaction');
    }
}
