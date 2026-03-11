<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'enrollment_number',
        'program',
        'year',
        'socioeconomic_info',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Student belongs to a user account
    public function user()
{
    return $this->belongsTo(User::class);
}

public function enrollments()
{
    return $this->hasMany(Enrollment::class);
}

    // Student belongs to many courses through enrollments
    public function courses()
    {
        return $this->belongsToMany(
            Course::class,
            'enrollments',
            'student_id',
            'course_id'
        )->withPivot('semester', 'grade')
         ->withTimestamps();
    }

    // Student has many attendance records
    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    // Student has many interventions
    public function interventions()
    {
        return $this->hasMany(Intervention::class);
    }
}
