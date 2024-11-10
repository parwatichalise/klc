<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function index(){
        return view('admin.login');
    }

   
    public function store(Request $request): RedirectResponse
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string|min:6',
    ]);

    if (Auth::attempt($request->only('email', 'password'))) {
        $request->session()->regenerate();
        
        $request->session()->forget('answer');

      

Session::forget('answered_questions');
Session::put('solved_count', 0);

 

        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif (Auth::user()->role === 'user' || Auth::user()->role === 'student') {
            return redirect()->route('student.dashboard'); 
        } elseif (Auth::user()->role === 'teacher') {
            return redirect()->route('teacher.profile'); 
        }
    }

    return redirect()->route('login')->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ]);
}
    

    public function logout(Request $request): RedirectResponse
    {
        DB::table('user_answers')->where('user_id', Auth::id())->delete();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
