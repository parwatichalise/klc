<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         
         User::create([
            'firstname' => 'Admin',
            'lastname'=>'Ad',
            'email' => 'admin@gmail.com',
            'username'=>'admin',
            'contact'=>'9845012345',
            'password' => bcrypt('123456'), 
            'role' => 'admin', 
        ]);

        
        User::create([
            'firstname' => 'User',
            'lastname'=>'Ur',
            'email' => 'user@gmail.com',
            'username'=>'user',
            'contact'=>'9845098765',
            'password' => bcrypt('123456'), 
            'role' => 'user', 
        ]);

        User::create([
            'firstname' => 'Teacher',
            'lastname' => 'Tch',
            'email' => 'teacher@gmail.com',
            'username' => 'teacher',
            'contact' => '9845045678',
            'password' => bcrypt('123456'),
            'role' => 'teacher',
        ]);
    }
}
