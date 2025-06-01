<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'term',
        'name',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * A semester can have many transactions.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * A semester can have many course-student enrollments.
     */
    public function courseStudents()
    {
        return $this->hasMany(CourseStudent::class);
    }

    // Helper to get the active semester
    public static function getActiveSemester()
    {
        return static::where('is_active', true)->first();
    }
}
