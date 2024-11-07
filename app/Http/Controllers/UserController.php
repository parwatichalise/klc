<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    //
    public function index(){
        return view('user.user');
    }
    
public function list()
{
    $users = User::all(); // Fetch all users, adjust as needed for your application logic
    return view('user.list', compact('users'));
}

}