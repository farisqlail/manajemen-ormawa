<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Division;

class DivisionsTableSeeder extends Seeder
{
    public function run()
    {
        Division::create([
            'name' => 'Division 1',
            'id_clubs' => 1, // Ganti dengan ID klub yang valid
        ]);

        Division::create([
            'name' => 'Division 2',
            'id_clubs' => 1, // Ganti dengan ID klub yang valid
        ]);

        Division::create([
            'name' => 'Division 3',
            'id_clubs' => 2, // Ganti dengan ID klub yang valid
        ]);
    }
}
