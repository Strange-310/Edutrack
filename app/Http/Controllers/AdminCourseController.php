<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Lecturer;


class AdminCourseController extends Controller
{
    public function show(Course $course)
    {
        // Load the students registered for this course
        $course->load('students'); // Assuming you have a `students()` relation in your Course model

        return view('admin.courses.show', compact('course'));
    }
    public function edit($id)
{
    $course = Course::findOrFail($id);
    $lecturers = Lecturer::all();

    return view('admin.courses.edit', compact('course', 'lecturers'));
}
public function update(Request $request, $id)
{
    $course = Course::findOrFail($id);

    $request->validate([
        'lecturer_id' => 'required|exists:lecturers,id',
    ]);

    $course->update([
        'lecturer_id' => $request->lecturer_id,
    ]);

    return redirect()->route('admin.dashboard', ['section' => 'courses'])
    ->with('success', 'Lecturer reassigned successfully.');
}
public function index()
{
    $courses = Course::with('lecturer.user')->get();
    $lecturers = Lecturer::with('user')->get();

    return view('admin.courses.index', compact('courses', 'lecturers'));
}
public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:50|unique:courses,code',
        'lecturer_id' => 'nullable|exists:lecturers,id'
    ]);

    \App\Models\Course::create([
        'name' => $request->name,
        'code' => $request->code,
        'lecturer_id' => $request->lecturer_id
    ]);

    return back()->with('success', 'Course created successfully.');
}
}