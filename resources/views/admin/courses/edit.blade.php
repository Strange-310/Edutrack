
@extends('layouts.app')

@section('content')

    <div class="max-w-xl mx-auto mt-10 bg-white p-6 shadow rounded">

        <h2 class="text-xl font-bold mb-4">Edit Course Lecturer</h2>

    

        <form method="POST" action="{{ route('admin.courses.update', $course->id) }}">
            @csrf
            @method('PUT')

            <p class="mb-3">
                <strong>Course:</strong> {{ $course->name }}
            </p>

            <label class="block mb-2">Select Lecturer</label>

            <select name="lecturer_id" class="border p-2 rounded w-full">
                @foreach($lecturers as $lecturer)
                    <option value="{{ $lecturer->id }}"
                        {{ $course->lecturer_id == $lecturer->id ? 'selected' : '' }}>
                        {{ $lecturer->user->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit"
                class="bg-blue-500 text-white px-4 py-2 mt-4 rounded w-full">
                Update Lecturer
            </button>
        </form>

    </div>

@endsection