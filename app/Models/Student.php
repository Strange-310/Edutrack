<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'enrollment_number',
        'program',
        'year',
        'socioeconomic_info',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'enrollments')
            ->withPivot('grade', 'semester')
            ->withTimestamps();
    }

    // ✅ FIX: Keep plural for relationships (matches controller)
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    // ✅ FIX: Keep plural for relationships (matches controller)
    public function cats()
    {
        return $this->hasMany(Cat::class);
    }

    // Helper to get latest CAT score for a course
    public function getLatestCatScore($courseId)
    {
        return $this->cats()
            ->where('course_id', $courseId)
            ->latest()
            ->first();
    }

    // Get attendance for a specific course
    public function getAttendance($courseId)
    { 
        return $this->attendances()
            ->where('course_id', $courseId)
            ->first();
    }
    
    // ✅ NEW: Get all courses with attendance and CAT data
    public function getCoursesWithRisk()
    {
        $coursesData = [];
        
        foreach ($this->courses as $course) {
            $attendance = $this->attendances()
                ->where('course_id', $course->id)
                ->first();
                
            $cat = $this->cats()
                ->where('course_id', $course->id)
                ->latest()
                ->first();
                
            $coursesData[] = [
                'course' => $course,
                'attendance' => $attendance,
                'cat' => $cat,
            ];
        }
        
        return $coursesData;
    }
}