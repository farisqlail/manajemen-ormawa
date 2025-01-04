<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Clubs;

class ClubsTableSeeder extends Seeder
{
    public function run()
    {
        Clubs::create([
            'name' => 'Club A',
            'description' => 'Description for Club A',
            'logo' => 'logos/logo_a.png', 
            'photo_structure' => 'photos/photo_structure_a.png', 
        ]);

        Clubs::create([
            'name' => 'Club B',
            'description' => 'Description for Club B',
            'logo' => 'logos/logo_b.png',
            'photo_structure' => 'photos/photo_structure_b.png',
        ]);
    }
}
