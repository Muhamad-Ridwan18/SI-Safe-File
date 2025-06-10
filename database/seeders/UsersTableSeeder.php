<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
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
        $userId = Str::uuid();

        // Insert dummy user (admin)
        $userData = [
            [
                'id' => $userId,
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'role' => 'Admin',
                'password' => $password,
            ],
        ];

        User::insert($userData);

        // Insert dummy categories
        $categoryData = [];
        for ($i = 0; $i < 20; $i++) {
            $categoryData[] = [
                'id' => Str::uuid(), // gunakan UUID berbeda untuk setiap kategori
                'name' => 'Category ' . strtoupper(Str::random(5)),
            ];
        }

        Category::insert($categoryData);
    }

}
