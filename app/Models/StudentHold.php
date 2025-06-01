<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\QueryException;

class StudentHold extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'reason',
        'description',
        'placed_by_user_id',
        'placed_at',
        'released_at',
        'is_active',
    ];

    protected $casts = [
        'placed_at' => 'datetime',
        'released_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function placedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'placed_by_user_id');
    }

    /**
     * Scope a query to only include active holds.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->whereNull('released_at');
    }
}
