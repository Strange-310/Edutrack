<?php

namespace App\Services;

use App\Models\Student;

class RiskAssessmentService
{
    public function calculate(Student $student)
    {
        $totalClasses = 11;
        
        // ✅ Use plural relationship names: attendances
        $attendanceRecords = $student->attendances;
        
        // If no attendance records, return default
        if ($attendanceRecords->isEmpty()) {
            return [
                'level' => 'Not Assessed',
                'score' => 0,
                'attendance_risk' => 'No Data',
                'cat_risk' => 'No Data',
                'courses_analyzed' => 0,
            ];
        }
        
        $attendanceRiskLevels = [];
        $catRiskLevels = [];
        $totalRiskScore = 0;
        
        // Calculate risk per course
        foreach ($attendanceRecords as $attendance) {
            $attended = $attendance->classes_attended;
            $missed = $totalClasses - $attended;
            
            // ✅ Use plural relationship: cats
            $catRecord = $student->cats()
                ->where('course_id', $attendance->course_id)
                ->latest()
                ->first();
                
            $catScore = $catRecord ? $catRecord->score : 0;
            
            // Attendance risk for this course
            if ($missed <= 1) {
                $attendanceRisk = 'Low';
                $attendanceScore = 1;
            } elseif ($missed <= 3) {
                $attendanceRisk = 'Medium';
                $attendanceScore = 2;
            } else {
                $attendanceRisk = 'High';
                $attendanceScore = 3;
            }
            $attendanceRiskLevels[] = $attendanceRisk;
            
            // CAT risk for this course
            if ($catScore >= 20) {
                $catRisk = 'Low';
                $catScore = 1;
            } elseif ($catScore >= 10) {
                $catRisk = 'Medium';
                $catScore = 2;
            } else {
                $catRisk = 'High';
                $catScore = 3;
            }
            $catRiskLevels[] = $catRisk;
            
            // Add to total risk score
            $totalRiskScore += ($attendanceScore + $catScore);
        }
        
        // Determine overall attendance risk (worst case)
        $overallAttendanceRisk = $this->getWorstRisk($attendanceRiskLevels);
        
        // Determine overall CAT risk (worst case)
        $overallCatRisk = $this->getWorstRisk($catRiskLevels);
        
        // Calculate average risk score
        $avgRiskScore = $totalRiskScore / (count($attendanceRecords) * 2);
        
        // Final combined risk
        if ($overallAttendanceRisk === 'High' || $overallCatRisk === 'High') {
            $level = 'High Risk';
        } elseif ($overallAttendanceRisk === 'Medium' || $overallCatRisk === 'Medium') {
            $level = 'Medium Risk';
        } else {
            $level = 'Low Risk';
        }
        
        return [
            'level' => $level,
            'score' => round($avgRiskScore * 33.33), // Convert to percentage
            'attendance_risk' => $overallAttendanceRisk,
            'cat_risk' => $overallCatRisk,
            'courses_analyzed' => count($attendanceRecords),
        ];
    }
    
    private function getWorstRisk(array $risks)
    {
        if (in_array('High', $risks)) return 'High';
        if (in_array('Medium', $risks)) return 'Medium';
        return 'Low';
    }
}