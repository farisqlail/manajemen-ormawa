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
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
            'status' => 'active',
            'id_club' => null, 
            'id_division' => null, 
        ]);

        User::create([
            'name' => 'Ormawa User 1',
            'email' => 'ormawa1@gmail.com',
            'password' => bcrypt('ormawa123'),
            'role' => 'ormawa',
            'status' => 'active',
            'id_club' => 1, 
            'id_division' => 1, 
        ]);

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
