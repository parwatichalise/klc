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
    $users = User::all(); 
    return view('user.list', compact('users'));
}

}