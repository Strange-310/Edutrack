<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentRisk extends Model
{
    protected $fillable = [
        'student_id',
        'level',
        'score',
        'missed_classes',
        'attendance_rate',
        'cat_score'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
