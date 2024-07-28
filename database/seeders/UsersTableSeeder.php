<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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

        $data = [
            ['name'=>'Guru1','email'=>'guru1@gmail.com', 'role'=>'Guru', 'password'=> $password],
            ['name'=>'Guru2','email'=>'guru2@gmail.com', 'role'=>'Guru', 'password'=> $password],
            ['name'=>'Guru3','email'=>'guru3@gmail.com', 'role'=>'Guru', 'password'=> $password],
            ['name'=>'Guru4','email'=>'guru4@gmail.com', 'role'=>'Guru', 'password'=> $password],
            ['name'=>'Guru5','email'=>'guru5@gmail.com', 'role'=>'Guru', 'password'=> $password],
        ];

        User::insert($data);
    }
}
