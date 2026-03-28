<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RiskAssessmentService;

class StudentController extends Controller
{
    public function dashboard(RiskAssessmentService $riskService)
    {
        $user = auth()->user();

        if (!$user || !$user->student) {
            abort(403, 'Student profile not found. Please ensure you are logged in as a student.');
        }

        // Load ALL required relationships for risk calculation
        // ✅ Using plural names: attendances, cats
        $student = $user->student()
            ->with(['courses.lecturer.user', 'attendances', 'cats'])
            ->first();

        // Check if student was loaded properly
        if (!$student) {
            abort(404, 'Student data not found.');
        }

        // Calculate risk using the service
        $risk = $riskService->calculate($student);
        
        // Attach risk data to student for display
        $student->risk_level = $risk['level'];
        $student->risk_score = $risk['score'] ?? 0;
        $student->attendance_risk = $risk['attendance_risk'] ?? 'N/A';
        $student->cat_risk = $risk['cat_risk'] ?? 'N/A';
        $student->courses_analyzed = $risk['courses_analyzed'] ?? 0;

        return view('student.dashboard', compact('student', 'risk'));
    }
}