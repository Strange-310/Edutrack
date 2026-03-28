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

    {{-- RISK ASSESSMENT SECTION --}}
    <div class="bg-gradient-to-r from-purple-50 to-blue-50 shadow-lg rounded-xl p-6 mb-8 border border-purple-100">
        <h2 class="text-xl font-bold mb-4 text-gray-800">🎯 Your Academic Risk Assessment</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg p-4 shadow-sm">
                <p class="text-sm text-gray-600 mb-1">Overall Risk Level</p>
                <p class="text-2xl font-bold 
                    @if($student->risk_level === 'High Risk') text-red-600
                    @elseif($student->risk_level === 'Medium Risk') text-yellow-600
                    @elseif($student->risk_level === 'Low Risk') text-green-600
                    @else text-gray-600
                    @endif">
                    {{ $student->risk_level ?? 'Not Assessed' }}
                </p>
            </div>
            
            <div class="bg-white rounded-lg p-4 shadow-sm">
                <p class="text-sm text-gray-600 mb-1">Risk Score</p>
                <p class="text-2xl font-bold text-blue-600">
                    {{ $student->risk_score ?? 0 }}%
                </p>
                <p class="text-xs text-gray-500 mt-1">Higher score = higher risk</p>
            </div>
            
            <div class="bg-white rounded-lg p-4 shadow-sm">
                <p class="text-sm text-gray-600 mb-1">📊 Attendance Risk</p>
                <p class="text-xl font-bold 
                    @if(isset($risk) && $risk['attendance_risk'] === 'High') text-red-600
                    @elseif(isset($risk) && $risk['attendance_risk'] === 'Medium') text-yellow-600
                    @elseif(isset($risk) && $risk['attendance_risk'] === 'Low') text-green-600
                    @else text-gray-600
                    @endif">
                    {{ $risk['attendance_risk'] ?? 'N/A' }}
                </p>
            </div>
            
            <div class="bg-white rounded-lg p-4 shadow-sm">
                <p class="text-sm text-gray-600 mb-1">📝 CAT Performance Risk</p>
                <p class="text-xl font-bold 
                    @if(isset($risk) && $risk['cat_risk'] === 'High') text-red-600
                    @elseif(isset($risk) && $risk['cat_risk'] === 'Medium') text-yellow-600
                    @elseif(isset($risk) && $risk['cat_risk'] === 'Low') text-green-600
                    @else text-gray-600
                    @endif">
                    {{ $risk['cat_risk'] ?? 'N/A' }}
                </p>
            </div>
        </div>
        
        @if(isset($risk) && $risk['courses_analyzed'] > 0)
            <div class="mt-4 text-sm text-gray-600 text-center">
                <p>📚 Analyzed {{ $risk['courses_analyzed'] }} course(s) | 
                <span class="text-green-600">✓ Low Risk</span> | 
                <span class="text-yellow-600">⚠️ Medium Risk</span> | 
                <span class="text-red-600">🔴 High Risk</span></p>
            </div>
        @endif
    </div>

    {{-- COURSES SECTION --}}
    <h3 class="text-xl font-bold mb-3">📚 My Enrollments</h3>

    @if($student->courses->count() > 0)
        @foreach($student->courses as $course)
            @php
                $attendance = $student->attendances->where('course_id', $course->id)->first();
                $cat = $student->cats->where('course_id', $course->id)->first();
                
                $attendancePercentage = $attendance ? ($attendance->classes_attended / $attendance->total_classes) * 100 : 0;
                $attendanceRisk = 'No Data';
                $attendanceColor = 'gray';
                
                if ($attendance) {
                    $missed = $attendance->total_classes - $attendance->classes_attended;
                    if ($missed <= 1) {
                        $attendanceRisk = 'Low Risk';
                        $attendanceColor = 'green';
                    } elseif ($missed <= 3) {
                        $attendanceRisk = 'Medium Risk';
                        $attendanceColor = 'yellow';
                    } else {
                        $attendanceRisk = 'High Risk';
                        $attendanceColor = 'red';
                    }
                }
                
                $catRisk = 'No Data';
                $catColor = 'gray';
                
                if ($cat) {
                    if ($cat->score >= 20) {
                        $catRisk = 'Low Risk';
                        $catColor = 'green';
                    } elseif ($cat->score >= 10) {
                        $catRisk = 'Medium Risk';
                        $catColor = 'yellow';
                    } else {
                        $catRisk = 'High Risk';
                        $catColor = 'red';
                    }
                }
            @endphp
            
            <div class="border rounded-lg p-4 mb-4 hover:shadow-lg transition-shadow 
                @if($attendanceRisk === 'High Risk' || $catRisk === 'High Risk') border-red-200 bg-red-50
                @elseif($attendanceRisk === 'Medium Risk' || $catRisk === 'Medium Risk') border-yellow-200 bg-yellow-50
                @else border-gray-200 bg-white
                @endif">
                
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <h4 class="font-bold text-lg">{{ $course->name }}</h4>
                        <p class="text-sm text-gray-600">Code: {{ $course->code }}</p>
                        <p class="text-sm text-gray-600">Lecturer: {{ $course->lecturer->user->name ?? 'Not Assigned' }}</p>
                        <p class="text-sm text-gray-600">Semester: {{ $course->pivot->semester ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-600">Grade: {{ $course->pivot->grade ?? 'Not graded' }}</p>
                    </div>
                    
                    <div class="text-right">
                        @if($attendanceRisk === 'High Risk' || $catRisk === 'High Risk')
                            <span class="inline-block px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-semibold">
                                ⚠️ Needs Attention
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 pt-4 border-t">
                    <div>
                        <p class="font-semibold mb-2">📊 Attendance</p>
                        @if($attendance)
                            <div class="flex justify-between text-sm mb-1">
                                <span>Classes Attended:</span>
                                <span class="font-bold">{{ $attendance->classes_attended }}/{{ $attendance->total_classes }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $attendancePercentage }}%"></div>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span>Percentage:</span>
                                <span class="font-bold">{{ round($attendancePercentage) }}%</span>
                            </div>
                            <div class="mt-2">
                                <span class="text-sm px-2 py-1 rounded-full 
                                    @if($attendanceColor === 'green') bg-green-100 text-green-800
                                    @elseif($attendanceColor === 'yellow') bg-yellow-100 text-yellow-800
                                    @elseif($attendanceColor === 'red') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ $attendanceRisk }}
                                </span>
                            </div>
                        @else
                            <p class="text-gray-500 text-sm">No attendance data available</p>
                        @endif
                    </div>
                    
                    <div>
                        <p class="font-semibold mb-2">📝 CAT Performance</p>
                        @if($cat)
                            <div class="flex justify-between text-sm mb-1">
                                <span>CAT Score:</span>
                                <span class="font-bold">{{ $cat->score }}/30</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                                <div class="bg-purple-600 h-2 rounded-full" style="width: {{ ($cat->score / 30) * 100 }}%"></div>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span>Percentage:</span>
                                <span class="font-bold">{{ round(($cat->score / 30) * 100) }}%</span>
                            </div>
                            <div class="mt-2">
                                <span class="text-sm px-2 py-1 rounded-full 
                                    @if($catColor === 'green') bg-green-100 text-green-800
                                    @elseif($catColor === 'yellow') bg-yellow-100 text-yellow-800
                                    @elseif($catColor === 'red') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ $catRisk }}
                                </span>
                            </div>
                        @else
                            <p class="text-gray-500 text-sm">No CAT data available</p>
                        @endif
                    </div>
                </div>
                
                @if($attendanceRisk === 'High Risk' || $catRisk === 'High Risk')
                    <div class="mt-4 p-3 bg-orange-100 border-l-4 border-orange-500 rounded">
                        <p class="text-sm text-orange-800">
                            <strong>💡 Recommendation:</strong> 
                            @if($attendanceRisk === 'High Risk')
                                Your attendance needs improvement. Aim to attend at least 85% of classes.
                            @endif
                            @if($catRisk === 'High Risk')
                                @if($attendanceRisk === 'High Risk')
                                    Also, your CAT score is low.
                                @else
                                    Your CAT score is low.
                                @endif
                                Consider seeking additional help from your lecturer.
                            @endif
                        </p>
                    </div>
                @elseif($attendanceRisk === 'Medium Risk' || $catRisk === 'Medium Risk')
                    <div class="mt-4 p-3 bg-yellow-100 border-l-4 border-yellow-500 rounded">
                        <p class="text-sm text-yellow-800">
                            <strong>📌 Note:</strong> You're showing some signs of risk. Stay consistent with attendance and CAT preparation.
                        </p>
                    </div>
                @endif
            </div>
        @endforeach
    @else
        <div class="bg-gray-50 border rounded-lg p-8 text-center">
            <p class="text-gray-500">No enrollments yet. Please contact the academic office.</p>
        </div>
    @endif

</div>
@endsection