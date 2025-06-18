<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Menambahkan user admin
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'alyssa',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Menambahkan user staff
        User::firstOrCreate(
            ['email' => 'staff@gmail.com'],
            [
                'name' => 'Helmi Firdaus',
                'password' => Hash::make('password'),
                'role' => 'staff',
            ]
        );

        // Menambahkan kategori
        Category::firstOrCreate(['name' => 'Makanan']);
        Category::firstOrCreate(['name' => 'Perlengkapan']);
        Category::firstOrCreate(['name' => 'Elektronik']);
    }
}
