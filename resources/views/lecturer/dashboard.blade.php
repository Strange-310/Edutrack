@extends('layouts.app')

@section('content')

<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Lecturer Dashboard</h2>
    <p class="text-gray-600">Manage courses and monitor student performance.</p>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

    <div class="bg-white shadow rounded p-5">
        <h3 class="text-gray-500 text-sm">Total Courses</h3>
        <p class="text-2xl font-bold text-blue-600">
            {{ $courses ? $courses->count() : 0 }}
        </p>
    </div>

    <div class="bg-white shadow rounded p-5">
        <h3 class="text-gray-500 text-sm">Total Students</h3>
        <p class="text-2xl font-bold text-green-600">
            {{ $courses->sum(function($course){
                return $course->enrollments->count();
            }) }}
        </p>
    </div>

    <div class="bg-white shadow rounded p-5">
        <h3 class="text-gray-500 text-sm">At Risk Students</h3>
        <p class="text-2xl font-bold text-red-600">
            0
        </p>
    </div>

</div>
<h2>My Courses</h2>

@foreach($courses as $course)
    <div class="border p-4 mb-4">
        <h3>{{ $course->name }} ({{ $course->code }})</h3>

        <h4>Students</h4>

        @foreach($course->students as $student)
            <form method="POST" action="{{ route('lecturer.grade') }}">
                @csrf

                <input type="hidden" name="student_id" value="{{ $student->id }}">
                <input type="hidden" name="course_id" value="{{ $course->id }}">

                <p>{{ $student->user->name }}</p>

                <input type="text"
                       name="grade"
                       value="{{ $student->pivot->grade }}"
                       placeholder="Enter Grade">

                <button type="submit">Save</button>
            </form>
            <hr>
        @endforeach
    </div>
@endforeach

<!-- Courses Table -->
<div class="bg-white shadow rounded p-6">
    <h3 class="text-lg font-semibold mb-4">Your Courses</h3>

    <table class="w-full border-collapse">
        <thead>
            <tr class="bg-gray-200 text-left">
                <th class="p-3">Course Name</th>
                <th class="p-3">Course Code</th>
                <th class="p-3">Enrolled Students</th>
            </tr>
        </thead>
        <tbody>
            @forelse($courses as $course)
            <tr class="border-t hover:bg-gray-50">
                <td class="p-3">{{ $course->name }}</td>
                <td class="p-3">{{ $course->code }}</td>
                <td class="p-3">{{ $course->enrollments->count() }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="p-3 text-center text-gray-500">
                    No courses assigned yet.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
