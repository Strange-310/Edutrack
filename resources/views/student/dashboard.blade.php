@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">

    <h1 class="text-2xl font-bold mb-4">
        Welcome, {{ $student->user->name }}
    </h1>

    <div class="bg-white shadow p-4 rounded mb-6">
        <p><strong>Enrollment Number:</strong> {{ $student->enrollment_number }}</p>
        <p><strong>Program:</strong> {{ $student->program }}</p>
        <p><strong>Year:</strong> {{ $student->year }}</p>
    </div>

   <h3>My Enrollments</h3>

@forelse($student->courses as $course)
    <div class="border p-3 mb-2">
        <p><strong>Course:</strong> {{ $course->name }} ({{ $course->code }})</p>

        <p><strong>Lecturer:</strong>
            {{ $course->lecturer->user->name ?? 'Not Assigned' }}
        </p>

        <p><strong>Semester:</strong>
            {{ $course->pivot->semester ?? 'N/A' }}
        </p>

        <p><strong>Grade:</strong>
            {{ $course->pivot->grade ?? 'Not graded' }}
        </p>
    </div>
@empty
    <p>No enrollments yet.</p>
@endforelse

</div>
@endsection