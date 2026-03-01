<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            ['name' => 'rihab', 'email' => 'rihabsabri21@gmail.com'],
            ['name' => 'sabri', 'email' => 'sabri@gmail.com'],
            ['name' => 'hajar', 'email' => 'hajar@gmail.com'],
            ['name' => 'tariq', 'email' => 'tariq@gmail.com'],
        ];

        foreach ($users as $userData) {
            \App\Models\User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => \Illuminate\Support\Facades\Hash::make($userData['email']),
                ]
            );
        }
    }
}
