<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    
   
    protected $table = 'students'; // This is the table name in the database

    protected $fillable = ['name', 'email', 'password']; 
}
