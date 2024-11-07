<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Student;


class ProfileController extends Controller
{
    public function updateProfile(Request $request)
    {
        // Get the authenticated user as a User object
        /** @var \App\Models\User $user */
        $user = Auth::user();  // or auth()->user()
    
        // Validation
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'contact' => 'required|string|max:20',
        ]);
    
        // Update user profile
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->contact = $request->contact;
    
        // Save updated user data
        $user->save();
    
        return redirect()->back()->with('success', 'Profile updated successfully.');
    }



    //This is for Student Profile

    public function index()
    {
        // Fetch the currently authenticated student
        $user = Auth::user();
    
        // Check if the user is logged in and is a student
        if ($user) {
            return view('student.profile', [
                'studentName' => $user->firstname,
                'email' => $user->email,
            ]);
        } else {
            // If no user is logged in, return a fallback
            return view('student.profile', [
                'studentName' => 'Unknown Student',
                'email' => 'N/A',
            ]);
        }
    }
}
