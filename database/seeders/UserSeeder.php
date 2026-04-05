<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'phone' => '6281234567890',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'avatar' => null,
        ]);

        User::create([
            'name' => 'Landowner User',
            'phone' => '6281234567891',
            'password' => Hash::make('password123'),
            'role' => 'landowner',
            'avatar' => null,
        ]);

        User::create([
            'name' => 'Buyer User',
            'phone' => '6281234567892',
            'password' => Hash::make('password123'),
            'role' => 'buyer',
            'avatar' => null,
        ]);
    }
}
