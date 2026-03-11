<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LecturerController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        if (!$user->lecturer) {
            abort(403, 'Lecturer profile not found.');
        }

        $lecturer = $user->lecturer;

        // Eager load enrollments to avoid N+1 problems
        $courses = $lecturer->courses()->with('enrollments')->get();

        return view('lecturer.dashboard', compact('courses'));
    }
    public function saveGrade(Request $request)
{
    $request->validate([
        'student_id' => 'required',
        'course_id' => 'required',
        'grade' => 'nullable|string'
    ]);

    \DB::table('enrollments')
        ->where('student_id', $request->student_id)
        ->where('course_id', $request->course_id)
        ->update([
            'grade' => $request->grade
        ]);

    return back()->with('success', 'Grade updated successfully.');
}
}
