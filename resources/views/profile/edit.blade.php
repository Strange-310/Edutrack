@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="text-2xl font-bold mb-6">My Profile</h2>
                
                @if(auth()->check())
                    @php
                        $user = auth()->user();
                    @endphp
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Personal Information -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold mb-4 text-gray-800">Personal Information</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Full Name</label>
                                    <p class="text-lg font-semibold text-gray-900">{{ $user->name }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Email Address</label>
                                    <p class="text-lg font-semibold text-gray-900">{{ $user->email }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Role</label>
                                    <p class="text-lg font-semibold">
                                        @if($user->role === 'admin')
                                            <span class="text-purple-600">Administrator</span>
                                        @elseif($user->role === 'lecturer')
                                            <span class="text-green-600">Lecturer / Faculty</span>
                                        @elseif($user->role === 'student')
                                            <span class="text-blue-600">Student</span>
                                        @else
                                            {{ ucfirst($user->role) }}
                                        @endif
                                    </p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Member Since</label>
                                    <p class="text-lg font-semibold text-gray-900">{{ $user->created_at->format('F j, Y') }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Role Specific Information -->
                        @if($user->role === 'student' && $user->student)
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold mb-4 text-gray-800">Academic Information</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Enrollment Number</label>
                                    <p class="text-lg font-semibold text-gray-900">{{ $user->student->enrollment_number ?? 'Not assigned' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Program / Course</label>
                                    <p class="text-lg font-semibold text-gray-900">{{ $user->student->program ?? 'Not assigned' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Year Level</label>
                                    <p class="text-lg font-semibold text-gray-900">{{ $user->student->year ?? 'Not assigned' }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if($user->role === 'lecturer' && $user->lecturer)
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold mb-4 text-gray-800">Professional Information</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Staff ID</label>
                                    <p class="text-lg font-semibold text-gray-900">{{ $user->lecturer->staff_id ?? 'Not assigned' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Department</label>
                                    <p class="text-lg font-semibold text-gray-900">{{ $user->lecturer->department ?? 'Not assigned' }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Password Change Warning -->
                    @if($user->must_change_password)
                    <div class="mt-6 bg-yellow-50 border-l-4 border-yellow-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    <strong>Password change required!</strong> Please change your password for security reasons.
                                </p>
                                <a href="/change-password" class="mt-2 inline-block text-sm font-medium text-yellow-700 underline">Change Password →</a>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                @else
                    <div class="text-center py-8">
                        <p class="text-red-500 mb-4">You are not logged in!</p>
                        <a href="/login" class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded transition">Login to Your Account</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection