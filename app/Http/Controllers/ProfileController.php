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
        /** @var \App\Models\User $user */
        $user = Auth::user();  
    
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'contact' => 'required|string|max:20',
        ]);
    
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->contact = $request->contact;
    
        $user->save();
    
        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function index()
    {
        $user = Auth::user();
    
        if ($user) {
            return view('student.profile', [
                'studentName' => $user->firstname,
                'email' => $user->email,
            ]);
        } else {
            return view('student.profile', [
                'studentName' => 'Unknown Student',
                'email' => 'N/A',
            ]);
        }
    }
}
