<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;use App\Models\Student;
use App\Models\StudentRisk;
use App\Services\RiskAssessmentService;

class CalculateStudentRiskJob implements ShouldQueue
{
    public function handle(RiskAssessmentService $riskService)
    {
        $students = Student::with(['attendances', 'cats'])->get();

        foreach ($students as $student) {

            $risk = $riskService->calculate($student);

            StudentRisk::updateOrCreate(
                ['student_id' => $student->id],
                [
                    'level' => $risk['level'],
                    'score' => $risk['score'],
                    'missed_classes' => $risk['missed_classes'] ?? 0,
                    'attendance_rate' => $risk['avg_attendance'] ?? 0,
                    'cat_score' => $risk['avg_cat_score'] ?? 0,
                ]
            );
        }
    }
}