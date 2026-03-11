@extends('layouts.app')

@section('content')

<div class="max-w-md mx-auto mt-10 bg-white p-6 shadow rounded">

    <h2 class="text-xl font-bold mb-4 text-center">
        Change Your Password
    </h2>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <div class="mb-4">
            <label class="block mb-1">New Password</label>
            <input type="password"
                   name="password"
                   class="w-full border rounded p-2"
                   required>
        </div>

        <div class="mb-4">
            <label class="block mb-1">Confirm Password</label>
            <input type="password"
                   name="password_confirmation"
                   class="w-full border rounded p-2"
                   required>
        </div>

        <button type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded">
            Update Password
        </button>
    </form>

</div>

@endsection