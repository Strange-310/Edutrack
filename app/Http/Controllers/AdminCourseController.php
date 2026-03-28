<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

class AdminCourseController extends Controller
{
    public function show(Course $course)
    {
        // Load the students registered for this course
        $course->load('students'); // Assuming you have a `students()` relation in your Course model

        return view('admin.courses.show', compact('course'));
    }
}