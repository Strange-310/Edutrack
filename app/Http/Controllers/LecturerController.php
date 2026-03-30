<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RiskAssessmentService;

class LecturerController extends Controller
{
    public function dashboard(RiskAssessmentService $riskService)
    {
        $lecturer = auth()->user()->lecturer;

        $courses = $lecturer->courses()
            ->with([
                'students.user',
                'students.courses',
                'students.attendances',
                'students.cats'
            ])
            ->get();

        $totalCourses = $courses->count();

        $students = collect();

        foreach ($courses as $course) {
            foreach ($course->students as $student) {
                $students->push($student);
            }
        }

        $students = $students->unique('id')->values();

        $studentCollection = collect();
        $atRiskStudents = collect();

        foreach ($students as $student) {

            $risk = $riskService->calculate($student);

            $student->risk_level = $risk['level'] ?? 'Low Risk';
            $student->missed_percentage = $risk['missed_percentage'] ?? 0;
            $student->attendance_rate = $risk['avg_attendance'] ?? 0;
            $student->cat_score = $risk['avg_cat_score'] ?? 0;

            $studentCollection->push($student);

            if (in_array($student->risk_level, ['Medium Risk', 'High Risk'])) {
                $atRiskStudents->push($student);
            }
        }

        return view('lecturer.dashboard', [
            'courses' => $courses,
            'studentCollection' => $studentCollection,
            'atRiskStudents' => $atRiskStudents,
            'totalCourses' => $totalCourses,
            'totalStudents' => $studentCollection->count(),
            'atRiskCount' => $atRiskStudents->count(),
        ]);
    }

    public function students(RiskAssessmentService $riskService)
    {
        $lecturer = auth()->user()->lecturer;

        $courses = $lecturer->courses()
            ->with([
                'students.user',
                'students.attendances',
                'students.cats'
            ])
            ->get();

        $students = collect();

        foreach ($courses as $course) {
            foreach ($course->students as $student) {
                $students->push($student);
            }
        }

        $students = $students->unique('id')->values();

        foreach ($students as $student) {

            $risk = $riskService->calculate($student);

            $student->risk_level = $risk['level'] ?? 'Low Risk';
            $student->missed_percentage = $risk['missed_percentage'] ?? 0;
        }

        return view('lecturer.students', compact('students'));
    }

    public function courses()
    {
        $lecturer = auth()->user()->lecturer;

        $courses = $lecturer->courses()
            ->withCount('students')
            ->get();

        return view('lecturer.courses', compact('courses'));
    }

    public function atRisk(RiskAssessmentService $riskService)
    {
        $lecturer = auth()->user()->lecturer;

        $courses = $lecturer->courses()
            ->with([
                'students.user',
                'students.attendances',
                'students.cats'
            ])
            ->get();

        $students = collect();

        foreach ($courses as $course) {
            foreach ($course->students as $student) {
                $students->push($student);
            }
        }

        $students = $students->unique('id')->values();

        $atRiskStudents = collect();

        foreach ($students as $student) {

            $risk = $riskService->calculate($student);

            $student->risk_level = $risk['level'] ?? 'Low Risk';
            $student->missed_percentage = $risk['missed_percentage'] ?? 0;

            if (in_array($student->risk_level, ['Medium Risk', 'High Risk'])) {
                $atRiskStudents->push($student);
            }
        }

        return view('lecturer.at-risk', compact('atRiskStudents'));
    }
}