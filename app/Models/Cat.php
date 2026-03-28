<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cat extends Model
{
    protected $table = 'cat';
    
    protected $fillable = [
        'student_id',
        'course_id',
        'score',
        'cat_name',
        'semester',
        'academic_year',
    ];
    
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    
    public function getRiskLevelAttribute()
    {
        if ($this->score >= 20) return 'Low';
        if ($this->score >= 10) return 'Medium';
        return 'High';
    }
}