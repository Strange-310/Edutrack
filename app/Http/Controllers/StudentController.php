<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        if (!$user->student) {
            abort(403, 'Student profile not found.');
        }

        // Eager load courses + lecturer + lecturer's user
        $student = $user->student()
            ->with(['courses.lecturer.user'])
            ->first();

        return view('student.dashboard', compact('student'));
    }
}