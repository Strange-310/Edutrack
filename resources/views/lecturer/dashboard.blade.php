@extends('layouts.app')

@section('content')

@php
    function riskColors($level) {
        return match($level) {
            'High Risk' => 'text-red-600 bg-red-100',
            'Medium Risk' => 'text-orange-500 bg-orange-100',
            default => 'text-green-600 bg-green-100',
        };
    }
@endphp

<div 
    x-data="{ section: 'courses' }"
    class="max-w-7xl mx-auto px-6 py-8"
>

    {{-- HEADER --}}
    <div class="mb-10">
        <h2 class="text-3xl font-bold text-gray-800">Lecturer Dashboard</h2>
        <p class="text-gray-500 mt-1">Monitor courses, students and risk levels</p>

        {{-- DASHBOARD CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">

            {{-- COURSES CARD --}}
            <div 
                @click="section='courses'"
                class="bg-white border-l-4 border-blue-500 p-6 rounded shadow cursor-pointer hover:shadow-md transition"
                :class="{ 'ring-2 ring-blue-500 bg-blue-50': section === 'courses' }"
            >
                <h4 class="text-sm uppercase text-gray-500">Total Courses</h4>
                <p class="text-3xl font-bold text-blue-600 mt-2">{{ $totalCourses }}</p>
            </div>

            {{-- STUDENTS CARD --}}
            <div 
                @click="section='students'"
                class="bg-white border-l-4 border-green-500 p-6 rounded shadow cursor-pointer hover:shadow-md transition"
                :class="{ 'ring-2 ring-green-500 bg-green-50': section === 'students' }"
            >
                <h4 class="text-sm uppercase text-gray-500">Total Students</h4>
                <p class="text-3xl font-bold text-green-600 mt-2">{{ $totalStudents }}</p>
            </div>

            {{-- AT RISK CARD --}}
            <div 
                @click="section='risk'"
                class="bg-white border-l-4 border-red-500 p-6 rounded shadow cursor-pointer hover:shadow-md transition"
                :class="{ 'ring-2 ring-red-500 bg-red-50': section === 'risk' }"
            >
                <h4 class="text-sm uppercase text-gray-500">At Risk Students</h4>
                <p class="text-3xl font-bold text-red-600 mt-2">{{ $atRiskStudents->count() }}</p>
            </div>

        </div>
    </div>

    {{-- COURSES SECTION --}}
    <div x-show="section === 'courses'" x-transition>

        <h3 class="text-xl font-semibold mb-6">My Courses</h3>

        <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100 text-sm uppercase text-gray-700">
                    <tr>
                        <th class="p-4 text-left">Course Name</th>
                        <th class="p-4 text-left">Course Code</th>
                        <th class="p-4 text-left">Enrolled Students</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $course)
                        <tr class="border-t hover:bg-blue-50 transition">
                            <td class="p-4">{{ $course->name }}</td>
                            <td class="p-4">{{ $course->code }}</td>
                            <td class="p-4">{{ $course->students->count() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-4 text-center text-gray-500">
                                No courses assigned.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    {{-- STUDENTS SECTION --}}
    <div x-show="section === 'students'" x-transition>

        <h3 class="text-xl font-semibold mb-6">All Students</h3>

        <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100 text-sm uppercase text-gray-700">
                    <tr>
                        <th class="p-4 text-left">Student Name</th>
                        <th class="p-4 text-left">Risk Level</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($studentCollection as $student)
                        <tr class="border-t hover:bg-gray-50 transition">

                            <td class="p-4">
                                {{ $student->user->name }}
                            </td>

                            <td class="p-4">
                                <span class="px-3 py-1 rounded-full text-sm font-semibold {{ riskColors($student->risk_level) }}">
                                    {{ $student->risk_level }}
                                </span>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="p-4 text-center text-gray-500">
                                No students found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    {{-- AT RISK SECTION --}}
    <div x-show="section === 'risk'" x-transition>

        <h3 class="text-xl font-semibold mb-6 text-red-600">At Risk Students</h3>

        <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100 text-sm uppercase text-gray-700">
                    <tr>
                        <th class="p-4 text-left">Student Name</th>
                        <th class="p-4 text-left">Risk Level</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($atRiskStudents as $student)
                        <tr class="border-t hover:bg-red-50 transition">

                            <td class="p-4">
                                {{ $student->user->name }}

                                <div class="text-xs text-gray-500 mt-1">
                                    ⚠ Missed {{ $student->missed_percentage }}% |
                                    Attendance: {{ $student->attendance_rate }}% |
                                    CAT: {{ $student->cat_score ?? 'N/A' }}
                                </div>
                            </td>

                            <td class="p-4">
                                <span class="px-3 py-1 rounded-full text-sm font-semibold {{ riskColors($student->risk_level) }}">
                                    {{ $student->risk_level }}
                                </span>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="p-4 text-center text-gray-500">
                                No at-risk students.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>

@endsection