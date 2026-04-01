<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RiskAssessmentService;

class LecturerController extends Controller
{
    public function dashboard(RiskAssessmentService $riskService)
    {
        $lecturer = auth()->user()->lecturer;

        // ✅ Eager load EVERYTHING including risk
        $courses = $lecturer->courses()
            ->with([
                'students.user',
                'students.risk' // 🔥 PRECOMPUTED RISK
            ])
            ->get();

        $totalCourses = $courses->count();

        // ✅ Flatten + remove duplicates
        $students = $courses->flatMap->students->unique('id')->values();

        $studentCollection = collect();
        $atRiskStudents = collect();

        foreach ($students as $student) {

            // ✅ USE STORED RISK (FAST)
            if ($student->risk) {
                $student->risk_level = $student->risk->level;
                $student->attendance_rate = $student->risk->attendance_rate;
                $student->cat_score = $student->risk->cat_score;
                $student->missed_classes = $student->risk->missed_classes;
            } else {
                // ⚠️ fallback (only if job hasn't run yet)
                $risk = $riskService->calculate($student);

                $student->risk_level = $risk['level'] ?? 'Low Risk';
                $student->attendance_rate = $risk['avg_attendance'] ?? 0;
                $student->cat_score = $risk['avg_cat_score'] ?? 0;
                $student->missed_classes = $risk['missed_classes'] ?? 0;
            }

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

    // =========================
    // STUDENTS
    // =========================
    public function students(RiskAssessmentService $riskService)
    {
        $lecturer = auth()->user()->lecturer;

        $courses = $lecturer->courses()
            ->with(['students.user', 'students.risk'])
            ->get();

        $students = $courses->flatMap->students->unique('id')->values();

        foreach ($students as $student) {

            if ($student->risk) {
                $student->risk_level = $student->risk->level;
                $student->attendance_rate = $student->risk->attendance_rate;
            } else {
                $risk = $riskService->calculate($student);
                $student->risk_level = $risk['level'] ?? 'Low Risk';
                $student->attendance_rate = $risk['avg_attendance'] ?? 0;
            }
        }

        return view('lecturer.students', compact('students'));
    }

    // =========================
    // COURSES
    // =========================
    public function courses()
    {
        $lecturer = auth()->user()->lecturer;

        $courses = $lecturer->courses()
            ->withCount('students')
            ->get();

        return view('lecturer.courses', compact('courses'));
    }

    // =========================
    // AT RISK ONLY
    // =========================
    public function atRisk(RiskAssessmentService $riskService)
    {
        $lecturer = auth()->user()->lecturer;

        $courses = $lecturer->courses()
            ->with(['students.user', 'students.risk'])
            ->get();

        $students = $courses->flatMap->students->unique('id')->values();

        $atRiskStudents = collect();

        foreach ($students as $student) {

            if ($student->risk) {
                $student->risk_level = $student->risk->level;
                $student->attendance_rate = $student->risk->attendance_rate;
                $student->cat_score = $student->risk->cat_score;
                $student->missed_classes = $student->risk->missed_classes;
            } else {
                $risk = $riskService->calculate($student);

                $student->risk_level = $risk['level'] ?? 'Low Risk';
                $student->attendance_rate = $risk['avg_attendance'] ?? 0;
                $student->cat_score = $risk['avg_cat_score'] ?? 0;
                $student->missed_classes = $risk['missed_classes'] ?? 0;
            }

            if (in_array($student->risk_level, ['Medium Risk', 'High Risk'])) {
                $atRiskStudents->push($student);
            }
        }

        return view('lecturer.at-risk', compact('atRiskStudents'));
    }
}