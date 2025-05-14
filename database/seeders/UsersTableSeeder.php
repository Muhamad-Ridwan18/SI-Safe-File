<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $password = Hash::make('password');
        $id = Str::uuid();

        $data = [
            [
                'id'=>$id,
                'name'=>'admin',
                'email'=>'admin@gmail.com', 
                'role'=>'Admin', 
                'password'=> $password
            ],
        ];

        User::insert($data);
    }
}
