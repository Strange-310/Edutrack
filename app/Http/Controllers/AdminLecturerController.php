<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Lecturer;

class AdminLecturerController extends Controller
{
public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'department' => 'required|string|max:255',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt('password'),
        'role' => 'lecturer', // optional but recommended
    ]);

    Lecturer::create([
        'user_id' => $user->id,
        'department' => $request->department,
    ]);

    // ✅ Redirect to dashboard to ensure new lecturer is loaded
    return redirect()->route('admin.dashboard')
        ->with('success', 'Lecturer created successfully');
}
}