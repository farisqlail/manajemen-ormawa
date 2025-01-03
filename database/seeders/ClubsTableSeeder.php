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
        ]);

        Clubs::create([
            'name' => 'Club B',
            'description' => 'Description for Club B',
        ]);
    }
}
