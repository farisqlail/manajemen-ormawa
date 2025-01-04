<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProkerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Data contoh untuk proker
        $prokers = [
            [
                'id_club' => 1,
                'name' => 'Proker A',
                'document_lpj' => 'document_lpj_a.pdf',
                'budget' => 10000000,
                'target_event' => Carbon::createFromFormat('Y-m-d', '2025-01-15'),
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_club' => 1,
                'name' => 'Proker B',
                'document_lpj' => 'document_lpj_b.pdf',
                'budget' => 15000000,
                'target_event' => Carbon::createFromFormat('Y-m-d', '2025-02-20'),
                'status' => 'approved',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_club' => 2,
                'name' => 'Proker C',
                'document_lpj' => 'document_lpj_c.pdf',
                'budget' => 20000000,
                'target_event' => Carbon::createFromFormat('Y-m-d', '2025-03-10'),
                'status' => 'rejected',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Tambahkan lebih banyak data sesuai kebutuhan
        ];

        // Insert data ke tabel prokers
        DB::table('prokers')->insert($prokers);
    }
}
