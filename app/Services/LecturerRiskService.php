<?php

namespace App\Services;

use App\Models\Student;

class LecturerRiskService
{
    public function calculate(Student $student)
    {
        $attendanceRate = $this->calculateAttendanceRate($student);
        $catScore = $this->calculateCatScore($student);
        $missedPercentage = $this->calculateMissedPercentage($student);

        // =========================
        // WEIGHTED RISK SCORE
        // =========================
        $riskScore =
            ($attendanceRate * 0.5) +
            (($catScore / 30) * 100 * 0.4) +
            ((100 - $missedPercentage) * 0.1);

        $riskScore = round($riskScore, 1);

        // =========================
        // CLASSIFICATION
        // =========================
        if ($riskScore >= 75) {
            $level = 'Low Risk';
        } elseif ($riskScore >= 50) {
            $level = 'Medium Risk';
        } else {
            $level = 'High Risk';
        }

        return [
            'risk_score' => $riskScore,
            'level' => $level,
            'attendance_rate' => $attendanceRate,
            'cat_score' => $catScore,
            'missed_percentage' => $missedPercentage,
        ];
    }

    public function calculateAttendanceRate(Student $student)
    {
        $total = 0;
        $attended = 0;

        foreach ($student->courses as $course) {

            $attendedCount = $course->attendances()
                ->where('student_id', $student->id)
                ->count();

            $maxClasses = $course->max_classes ?? 11;

            $attended += $attendedCount;
            $total += $maxClasses;
        }

        return $total > 0
            ? round(($attended / $total) * 100, 1)
            : 0;
    }

    public function calculateCatScore(Student $student)
    {
        $total = 0;
        $count = 0;

        foreach ($student->courses as $course) {

            if (isset($course->pivot->cat_score)) {
                $total += $course->pivot->cat_score;
                $count++;
            }
        }

        return $count > 0
            ? round($total / $count, 1)
            : 0;
    }

    public function calculateMissedPercentage(Student $student)
    {
        $totalMissed = 0;
        $totalClasses = 0;

        foreach ($student->courses as $course) {

            $attended = $course->attendances()
                ->where('student_id', $student->id)
                ->count();

            $maxClasses = $course->max_classes ?? 11;

            $missed = max(0, $maxClasses - $attended);

            $totalMissed += $missed;
            $totalClasses += $maxClasses;
        }

        return $totalClasses > 0
            ? round(($totalMissed / $totalClasses) * 100, 1)
            : 0;
    }
}