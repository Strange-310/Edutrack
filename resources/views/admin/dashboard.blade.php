@extends('layouts.app')

@section('content')

<div 
    x-data="{ 
        section: 'courses',
        showStudentForm: false,
        showLecturerForm: false
    }"
    class="max-w-7xl mx-auto px-6 py-8"
>

    {{-- HEADER --}}
    <div class="mb-10">
        <h2 class="text-3xl font-bold text-gray-800">Admin Dashboard</h2>
        <p class="text-gray-500 mt-1">Manage courses, students and lecturers</p>

        {{-- DASHBOARD CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">

            {{-- COURSES CARD --}}
            <div 
                @click="section='courses'; showStudentForm=false; showLecturerForm=false"
                class="bg-white border-l-4 border-blue-500 p-6 rounded shadow cursor-pointer hover:shadow-md transition"
                :class="{ 'ring-2 ring-blue-500 bg-blue-50': section === 'courses' }"
            >
                <h4 class="text-sm uppercase text-gray-500">Total Courses</h4>
                <p class="text-3xl font-bold text-blue-600 mt-2">{{ $courses->count() }}</p>
            </div>

            {{-- STUDENTS CARD --}}
            <div 
                @click="section='students'; showStudentForm=false; showLecturerForm=false"
                class="bg-white border-l-4 border-green-500 p-6 rounded shadow cursor-pointer hover:shadow-md transition"
                :class="{ 'ring-2 ring-green-500 bg-green-50': section === 'students' }"
            >
                <h4 class="text-sm uppercase text-gray-500">Total Students</h4>
                <p class="text-3xl font-bold text-green-600 mt-2">{{ $students->count() }}</p>
            </div>

            {{-- LECTURERS CARD --}}
            <div 
                @click="section='lecturers'; showStudentForm=false; showLecturerForm=false"
                class="bg-white border-l-4 border-purple-500 p-6 rounded shadow cursor-pointer hover:shadow-md transition"
                :class="{ 'ring-2 ring-purple-500 bg-purple-50': section === 'lecturers' }"
            >
                <h4 class="text-sm uppercase text-gray-500">Total Lecturers</h4>
                <p class="text-3xl font-bold text-purple-600 mt-2">{{ $lecturers->count() }}</p>
            </div>

        </div>
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- ERROR MESSAGE --}}
    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded shadow-sm">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ===================== --}}
    {{-- COURSES SECTION --}}
    {{-- ===================== --}}
    <div x-show="section === 'courses'" x-transition>

        {{-- CREATE COURSE --}}
        <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6 shadow-sm">
            <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">Create New Course</h3>

            <form method="POST" action="{{ route('admin.courses.store') }}">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <input type="text" name="name" placeholder="Course Name" class="input" required>
                    <input type="text" name="code" placeholder="Course Code" class="input" required>

                    <select name="lecturer_id" class="input">
                        <option value="">Assign Later</option>
                        @foreach($lecturers as $lecturer)
                            <option value="{{ $lecturer->id }}">
                                {{ $lecturer->user->name }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                        Create
                    </button>
                </div>
            </form>
        </div>

        {{-- COURSES TABLE --}}
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100 text-sm uppercase text-gray-700">
                    <tr>
                        <th class="p-4 text-left">Course</th>
                        <th class="p-4 text-left">Code</th>
                        <th class="p-4 text-left">Lecturer</th>
                        <th class="p-4 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($courses as $course)
                        <tr class="border-t hover:bg-blue-50 transition">
                            <td class="p-4">{{ $course->name }}</td>
                            <td class="p-4">{{ $course->code }}</td>
                            <td class="p-4">{{ $course->lecturer?->user?->name ?? 'Not Assigned' }}</td>
                            <td>
                                <a href="{{ route('admin.courses.edit', $course->id) }}" 
                                     class="bg-yellow-500 text-white px-2 py-1 rounded">
                                     Change Lecturer
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- ===================== --}}
    {{-- STUDENTS SECTION --}}
    {{-- ===================== --}}
    <div x-show="section === 'students'" x-transition>

        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-semibold">Students</h3>

            <button 
                @click="showStudentForm = !showStudentForm"
                class="btn-green"
            >
                Register New Student
            </button>
        </div>

        {{-- REGITER FORM--}}
        <div x-show="showStudentForm" x-transition
            class="bg-white shadow-xl rounded-2xl p-8 mb-8">

            <form method="POST" action="{{ route('admin.students.store') }}">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    <input type="text" name="name" placeholder="Student Name" class="input" required>
                    <input type="email" name="email" placeholder="Student Email" class="input" required>
                    <input type="text" name="enrollment_number" placeholder="Enrollment Number" class="input" required>
                    <input type="text" name="program" placeholder="Program" class="input" required>
                    <input type="number" name="year" placeholder="Year" class="input" required>
                    <input type="text" name="socioeconomic_info" placeholder="Socioeconomic Info" class="input">

                    <button type="submit" class="btn-green md:col-span-3">
                        Save Student
                    </button>

                </div>
            </form>
        </div>

        {{-- STUDENTS TABLE --}}
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden mb-8">
            <table class="w-full">
                <thead class="bg-gray-100 text-sm uppercase text-gray-700">
                    <tr>
                        <th class="p-4 text-left">Name</th>
                        <th class="p-4 text-left">Email</th>
                        <th class="p-4 text-left">Enrollment</th>
                        <th class="p-4 text-left">Program</th>
                        <th class="p-4 text-left">Year</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                        <tr class="border-t hover:bg-green-50 transition">
                            <td class="p-4">{{ $student->user->name }}</td>
                            <td class="p-4">{{ $student->user->email }}</td>
                            <td class="p-4">{{ $student->enrollment_number }}</td>
                            <td class="p-4">{{ $student->program }}</td>
                            <td class="p-4">{{ $student->year }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>



    {{-- ===================== --}}
    {{-- LECTURERS SECTION --}}
    {{-- ===================== --}}
    <div x-show="section === 'lecturers'" x-transition>
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-semibold">Lecturers</h3>
            <button @click="showLecturerForm = !showLecturerForm" class="btn-purple">Register Lecturer</button>
        </div>

        {{-- REGISTER LECTURER FORM --}}
        <div x-show="showLecturerForm" x-transition class="bg-white shadow-xl rounded-2xl p-8 mb-8">
            <form method="POST" action="{{ route('admin.lecturers.store') }}">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <input type="text" name="name" placeholder="Lecturer Name" class="input" required>
                    <input type="email" name="email" placeholder="Lecturer Email" class="input" required>
                    <input type="text" name="department" placeholder="Department" class="input" required>

                    <button type="submit" class="btn-purple md:col-span-3">Save Lecturer</button>
                </div>

                {{-- Display validation errors --}}
                @if ($errors->any())
                    <div class="mt-4 p-4 bg-red-100 text-red-800 rounded-lg">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </form>
        </div>

        {{-- LECTURERS TABLE --}}
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100 text-sm uppercase text-gray-700">
                    <tr>
                        <th class="p-4 text-left">Name</th>
                        <th class="p-4 text-left">Email</th>
                        <th class="p-4 text-left">Department</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lecturers as $lecturer)
                        <tr class="border-t hover:bg-purple-50 transition">
                            <td class="p-4">{{ $lecturer->user->name }}</td>
                            <td class="p-4">{{ $lecturer->user->email }}</td>
                            <td class="p-4">{{ $lecturer->department }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
