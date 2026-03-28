<?php

namespace App\Services;

class RiskAssessmentService
{
    public function calculateRisk($student)
    {
        $riskScore = 0;

        // Example logic
        if ($student->year > 3) {
            $riskScore += 10;
        }

        if ($student->socioeconomic_info === 'low') {
            $riskScore += 20;
        }

        return $riskScore;
    }
}