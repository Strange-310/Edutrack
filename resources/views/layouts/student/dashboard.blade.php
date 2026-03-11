@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Student Dashboard</h1>

    <div class="bg-white p-4 rounded shadow">
        <p><strong>Program:</strong> {{ $student->program }}</p>
        <p><strong>Year:</strong> {{ $student->year }}</p>
    </div>

    <div class="mt-6">
        <h2 class="text-xl font-semibold mb-2">Enrolled Courses</h2>
        <table class="w-full bg-white rounded shadow">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-2">Course</th>
                    <th class="p-2">Grade</th>
                </tr>
            </thead>
            <tbody>
                @foreach($enrollments as $enrollment)
                <tr class="border-t">
                    <td class="p-2">{{ $enrollment->course->name }}</td>
                    <td class="p-2">{{ $enrollment->grade ?? 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
