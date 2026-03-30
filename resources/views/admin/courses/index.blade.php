@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto mt-6 bg-white p-6 shadow rounded">

        <h1 class="text-2xl font-bold mb-4">Courses</h1>

        <table class="w-full border">
            <tr class="bg-gray-100">
                <th class="p-2">Name</th>
                <th class="p-2">Code</th>
                <th class="p-2">Lecturer</th>
                <th class="p-2">Action</th>
            </tr>

            @foreach($courses as $course)
            <tr class="text-center">
                <td class="p-2">{{ $course->name }}</td>
                <td class="p-2">{{ $course->code }}</td>
                <td class="p-2">
                    {{ $course->lecturer?->user?->name ?? 'Not Assigned' }}
                </td>
                <td class="p-2">
                    <a href="{{ route('admin.courses.edit', $course->id) }}"
                       class="bg-yellow-500 text-white px-3 py-1 rounded">
                        Change Lecturer
                    </a>
                </td>
            </tr>
            @endforeach
        </table>

    </div>
@endsection