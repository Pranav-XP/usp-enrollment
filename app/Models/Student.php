<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_id',
        'first_name',
        'last_name',
        'dob',
        'email',
        'phone',
        'program_id',
        'enrollment_year'
    ];

    protected $casts = [
        'dob' => 'date', // Store and retrieve as date
    ];

    // Accessor to format `dob` as "d M Y" (e.g., "15 Mar 2000")
    public function dob(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format('d M Y'),
        );
    }

    // Relationship with User (One-to-One)
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class)->withTimestamps();
    }

     // Relationship with Program (Many-to-One)
     public function program()
     {
         return $this->belongsTo(Program::class);
     }

     //Many to Many courses
     public function courses():BelongsToMany
     {
        return $this->belongsToMany(Course::class, 'course_student', 'student_id', 'course_id')
        ->withPivot('grade', 'status')
        ->withTimestamps();
     }

     public function transactions()
        {
            return $this->hasMany(Transaction::class);
        }
}
