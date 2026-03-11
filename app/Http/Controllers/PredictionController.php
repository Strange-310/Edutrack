<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PredictionController extends Controller
{
    public function predict(Request $request) {
        // RBR (Rule-based) example
        $risk = $request->attendance < 50 ? 'high' : 'low';

        // CBR (Case-based) example
        $similar_students = Student::where('average_score', '<', 50)->get();

        return response()->json([
            'risk' => $risk,
            'similar_students' => $similar_students
        ]);
    }
}