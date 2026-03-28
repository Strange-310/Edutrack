<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Lecturer;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Services\RiskAssessmentService;


class AdminController extends Controller
{
    public function assignCourseToLecturer(Request $request)
{
    $request->validate([
        'course_id' => 'required|exists:courses,id',
        'lecturer_id' => 'required|exists:lecturers,id',
    ]);

    $course = Course::findOrFail($request->course_id);

    $course->update([
        'lecturer_id' => $request->lecturer_id
    ]);

    return back()->with('success','Course assigned to lecturer.');
}

    public function revokeCourseFromLecturer(Request $request)
{
    $request->validate([
        'course_id' => 'required|exists:courses,id',
    ]);

    $course = Course::findOrFail($request->course_id);

    $course->update([
        'lecturer_id' => null
    ]);

    return back()->with('success','Course revoked.');
}
public function assignCourseToStudent(Request $request)
{
    $request->validate([
        'student_id' => 'required|exists:students,id',
        'course_id' => 'required|exists:courses,id',
    ]);

    $student = Student::findOrFail($request->student_id);

    $student->courses()->syncWithoutDetaching([$request->course_id]);

    return back()->with('success','Student enrolled successfully.');
}

    


public function storeCourse(Request $request)
{
    $request->validate([
        'name' => 'required|string',
        'code' => 'required|string|unique:courses,code',
        'lecturer_id' => 'nullable|exists:lecturers,id'
    ]);

    Course::create([
        'name' => $request->name,
        'code' => $request->code,
        'lecturer_id' => $request->lecturer_id
    ]);

    return back()->with('success','Course created successfully.');
}


public function storeStudents(Request $request)
{
    $request->validate([
        'name' => 'required|string',
        'email' => 'required|email|unique:users,email',
        'enrollment_number' => 'required|unique:students,enrollment_number',
        'program' => 'required|string',
        'year' => 'required|integer|min:1|max:8',
        'socioeconomic_info' => 'nullable|string',
    ]);

    // Create user
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make('12345678'),
        'role' => 'student',
        'must_change_password' => true,
    ]);

    // Create student profile properly
    $user->student()->create([
        'enrollment_number' => $request->enrollment_number,
        'program' => $request->program,
        'year' => $request->year,
        'socioeconomic_info' => $request->socioeconomic_info,
    ]);

    return back()->with('success', 'Student registered successfully.');
}

public function dashboard(RiskAssessmentService $riskService)
{
    $courses = Course::with('lecturer')->get();
    $lecturers = Lecturer::with('user')->get();
    $students = Student::with('courses')->get();

    foreach ($students as $student) {
        $risk = $riskService->calculate($student);

        $student->risk_score = $risk['score'];
        $student->risk_level = $risk['level'];
    }

    return view('admin.dashboard', compact('courses','lecturers','students'));
}
}