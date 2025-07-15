<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        if (!User::where('email', 'superadmin@gmail.com')->exists()) {
            User::create([
                'name' => 'Superadmin User',
                'email' => 'superadmin@gmail.com',
                'password' => bcrypt('superadmin123'),
                'role' => 'superadmin',
                'status' => 'active',
                'id_club' => null,
                'id_division' => null,
            ]);
        }

        // Admin
        if (!User::where('email', 'admin@gmail.com')->exists()) {
            User::create([
                'name' => 'Admin User',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('admin123'),
                'role' => 'admin',
                'status' => 'active',
                'id_club' => null,
                'id_division' => null,
            ]);
        }

        // Ormawa User 1
        if (!User::where('email', 'ormawa1@gmail.com')->exists()) {
            User::create([
                'name' => 'Ormawa User 1',
                'email' => 'ormawa1@gmail.com',
                'password' => bcrypt('ormawa123'),
                'role' => 'ormawa',
                'status' => 'active',
                'id_club' => 1,
                'id_division' => 1,
            ]);
        }

        // Ormawa User 2
        if (!User::where('email', 'ormawa2@gmail.com')->exists()) {
            User::create([
                'name' => 'Ormawa User 2',
                'email' => 'ormawa2@gmail.com',
                'password' => bcrypt('ormawa123'),
                'role' => 'ormawa',
                'status' => 'active',
                'id_club' => 1,
                'id_division' => 2,
            ]);
        }
    }
}
