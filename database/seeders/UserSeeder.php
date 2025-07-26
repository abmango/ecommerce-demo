<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {        

        $users = [
            [
                'name' => 'Daniel Admin', 
                'email' => 'dmhernandez.sistemas@gmail.com',
                'email_verified_at' => Carbon::now()->toDateTimeLocalString(),
                'password' => Hash::make('root123'),
                'role' => 'admin'
            ],
            [
                'name' => 'Daniel Customer', 
                'email' => 'dmyh96@gmail.com',
                'email_verified_at' => Carbon::now()->toDateTimeLocalString(),
                'password' => Hash::make('root123'),
                'role' => 'user'                
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
