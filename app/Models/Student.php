<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
            get: fn($value) => Carbon::parse($value)->format('d M Y'),
        );
    }

    // Relationship with User (One-to-One)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTimestamps();
    }

    // Relationship with Program (Many-to-One)
    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    //Many to Many courses
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_student')
            ->using(CourseStudent::class)
            ->withPivot('grade', 'status', 'semester_id') // Add semester_id to pivot
            ->withTimestamps();
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // New: Relationship to StudentHolds
    public function holds(): HasMany
    {
        return $this->hasMany(StudentHold::class);
    }

    /**
     * Accessor to check if the student has any active holds.
     * Use this for quick checks like `$student->is_on_hold`.
     */
    public function getIsOnHoldAttribute(): bool
    {
        // Eager load active holds to avoid N+1 query if checking multiple students
        if ($this->relationLoaded('holds')) {
            return $this->holds->where('is_active', true)->whereNull('released_at')->isNotEmpty();
        }
        // Fallback to query if not eager loaded (less efficient)
        return $this->holds()->active()->exists();
    }

    /**
     * Accessor to get the active hold (if any).
     * Returns the first active hold, or null.
     */
    public function getActiveHoldAttribute(): ?StudentHold
    {
        // Eager load active holds
        if ($this->relationLoaded('holds')) {
            return $this->holds->where('is_active', true)->whereNull('released_at')->first();
        }
        // Fallback to query if not eager loaded
        return $this->holds()->active()->first();
    }
}
