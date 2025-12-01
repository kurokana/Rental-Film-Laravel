<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Owner
        User::create([
            'name' => 'Owner Rental Film',
            'email' => 'owner@rentalfilm.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'phone' => '081234567890',
            'address' => 'Jl. Raya Utama No. 1, Jakarta',
        ]);

        // Pegawai
        User::create([
            'name' => 'Staff Rental Film',
            'email' => 'staff@rentalfilm.com',
            'password' => Hash::make('password'),
            'role' => 'pegawai',
            'phone' => '081234567891',
            'address' => 'Jl. Raya Utama No. 2, Jakarta',
        ]);

        User::create([
            'name' => 'Staff Rental Film 2',
            'email' => 'staff2@rentalfilm.com',
            'password' => Hash::make('password'),
            'role' => 'pegawai',
            'phone' => '081234567892',
            'address' => 'Jl. Raya Utama No. 3, Jakarta',
        ]);

        // User biasa
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '081234567893',
            'address' => 'Jl. Sudirman No. 10, Jakarta',
        ]);

        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '081234567894',
            'address' => 'Jl. Thamrin No. 20, Jakarta',
        ]);

        User::create([
            'name' => 'Bob Wilson',
            'email' => 'bob@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '081234567895',
            'address' => 'Jl. Gatot Subroto No. 30, Jakarta',
        ]);
    }
}
