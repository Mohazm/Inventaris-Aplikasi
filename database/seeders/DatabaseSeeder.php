<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;  // Pastikan ini sesuai dengan nama model Anda
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Menambahkan user admin
        User::create([
            'name' => 'Putri',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),  // Gantilah dengan password yang aman
            'role' => 'admin',
        ]);

        // Menambahkan user staff
        User::create([
            'name' => 'Cahya',
            'email' => 'cahya@gmail.com',
            'password' => Hash::make('password'),  // Gantilah dengan password yang aman
            'role' => 'staff',
        ]);

        // Menambahkan kategori
        Category::create([  // Pastikan menggunakan nama model yang benar
            'name' => 'Kebersihan',
        ]);
        Category::create([  // Pastikan menggunakan nama model yang benar
            'name' => 'Olah Raga',
        ]);
        Category::create([  // Pastikan menggunakan nama model yang benar
            'name' => 'Elektronik',
        ]);
    }
}
