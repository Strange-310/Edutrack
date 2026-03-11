<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'lecturer_id',
    ];

    // A course belongs to one lecturer
    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }

    // A course has many enrollments
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    // A course has many students through enrollments
    public function students()
    {
        return $this->belongsToMany(
            Student::class,
            'enrollments',
            'course_id',
            'student_id'
        )->withPivot('semester', 'grade')
         ->withTimestamps();
    }
}