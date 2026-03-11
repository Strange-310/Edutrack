<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function calculateRisk($studentId)
{
    $attendanceRate = 60; // sample logic
    $averageGrade = 45;

    if($attendanceRate < 75 || $averageGrade < 50){
        return "At Risk";
    }

    return "Safe";
}

}
