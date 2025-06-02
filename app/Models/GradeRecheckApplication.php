<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\GradeRecheckStatus; // Import the Enum

class GradeRecheckApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_id',
        'full_name',
        'postal_address',
        'date_of_birth',
        'telephone',
        'email',
        'sponsorship_status',
        'course_code',
        'course_title',
        'course_lecturer_name',
        'receipt_no',
        'payment_confirmation_path',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'status' => GradeRecheckStatus::class, // Cast to the Enum
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
