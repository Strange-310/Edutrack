<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    protected $table = 'attendance';
    
    protected $fillable = [
        'student_id',
        'course_id',
        'date',
        'present',        // Add this
        'classes_attended',
        'total_classes',
        'semester',
        'academic_year',
    ];
    
    protected $casts = [
        'date' => 'date',
        'present' => 'boolean',  // Cast to boolean
    ];
    
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    
    public function getPercentageAttribute()
    {
        if ($this->total_classes == 0) return 0;
        return round(($this->classes_attended / $this->total_classes) * 100);
    }
    
    public function getRiskLevelAttribute()
    {
        $missed = $this->total_classes - $this->classes_attended;
        
        if ($missed <= 1) return 'Low';
        if ($missed <= 3) return 'Medium';
        return 'High';
    }
}