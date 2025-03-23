<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prerequisite extends Model
{
    protected $fillable = ['course_id', 'prerequisite_groups'];

    protected $casts = [
        'prerequisite_groups' => 'array', // Automatically converts JSON to array
    ];

    public function course() {
        return $this->belongsTo(Course::class);
    }
}
