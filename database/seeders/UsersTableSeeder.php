<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Membuat user admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'), // Ganti dengan password yang aman
            'role' => 'admin',
            'status' => 'active',
            'id_club' => null, // Admin tidak memiliki klub
            'id_division' => null, // Admin tidak memiliki divisi
        ]);

        // Membuat beberapa user ormawa
        User::create([
            'name' => 'Ormawa User 1',
            'email' => 'ormawa1@example.com',
            'password' => bcrypt('password'), // Ganti dengan password yang aman
            'role' => 'ormawa',
            'status' => 'active',
            'id_club' => 1, // Ganti dengan ID klub yang valid
            'id_division' => 1, // Ganti dengan ID divisi yang valid
        ]);

        User::create([
            'name' => 'Ormawa User 2',
            'email' => 'ormawa2@example.com',
            'password' => bcrypt('password'), // Ganti dengan password yang aman
            'role' => 'ormawa',
            'status' => 'active',
            'id_club' => 1, // Ganti dengan ID klub yang valid
            'id_division' => 2, // Ganti dengan ID divisi yang valid
        ]);
    }
}
