<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Course;

class EnrollAllStudentsSeeder extends Seeder
{
    public function run(): void
    {
        $courses = Course::all();
        
        if ($courses->isEmpty()) {
            $this->command->error('No courses found! Please create courses first.');
            return;
        }
        
        $students = Student::all();
        
        if ($students->isEmpty()) {
            $this->command->error('No students found!');
            return;
        }
        
        foreach ($students as $student) {
            $enrolledCount = 0;
            
            foreach ($courses as $course) {
                // Check if already enrolled
                if (!$student->courses()->where('course_id', $course->id)->exists()) {
                    $student->courses()->attach($course->id, [
                        'semester' => 'Semester 1',
                        'academic_year' => 2024,
                        'grade' => null,
                    ]);
                    $enrolledCount++;
                }
            }
            
            $this->command->info("Student {$student->user->name} enrolled in {$enrolledCount} new courses. Total: {$student->courses->count()} courses");
        }
        
        $this->command->info('✅ All students have been enrolled in all courses!');
    }
}