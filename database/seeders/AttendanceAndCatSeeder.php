<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Course;
use App\Models\Attendance;
use App\Models\Cat;
use Carbon\Carbon;

class AttendanceAndCatSeeder extends Seeder
{
    public function run(): void
    {
        $students = Student::all();
        $courses = Course::all();
        
        $currentSemester = 'Semester 1';
        $currentYear = 2024;

        foreach ($students as $student) {
            foreach ($courses as $course) {
                // Random attendance (0-11 classes attended)
                $attended = rand(5, 11);
                $totalClasses = 11;
                $missed = $totalClasses - $attended;
                
                // Calculate present status based on attendance percentage
                $percentage = ($attended / $totalClasses) * 100;
                $present = $percentage >= 85; // 85% attendance requirement
                
                // Create attendance record
                Attendance::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'course_id' => $course->id,
                        'semester' => $currentSemester,
                    ],
                    [
                        'classes_attended' => $attended,
                        'total_classes' => $totalClasses,
                        'academic_year' => $currentYear,
                        'date' => Carbon::now(),
                        'present' => $present, // Add present status
                        'updated_at' => Carbon::now(),
                        'created_at' => Carbon::now(),
                    ]
                );

                // Random CAT score (0-30)
                $catScore = rand(0, 30);
                
                // Check if cat table has a present field too
                $catColumns = \Schema::getColumnListing('cat');
                
                $catData = [
                    'student_id' => $student->id,
                    'course_id' => $course->id,
                    'score' => $catScore,
                    'cat_name' => 'CAT 1',
                    'semester' => $currentSemester,
                    'academic_year' => $currentYear,
                    'updated_at' => Carbon::now(),
                    'created_at' => Carbon::now(),
                ];
                
                // Add date if column exists
                if (in_array('date', $catColumns)) {
                    $catData['date'] = Carbon::now();
                }
                
                // Add present if column exists
                if (in_array('present', $catColumns)) {
                    $catData['present'] = $catScore >= 10; // Example: pass if score >= 10
                }
                
                Cat::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'course_id' => $course->id,
                        'cat_name' => 'CAT 1',
                        'semester' => $currentSemester,
                    ],
                    $catData
                );
            }
        }
        
        $this->command->info('Attendance and CAT data seeded successfully!');
        $this->command->info('Total attendance records: ' . Attendance::count());
        $this->command->info('Total CAT records: ' . Cat::count());
    }
}