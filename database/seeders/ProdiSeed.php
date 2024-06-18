<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdiSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('prodi')->insert(
            [
                [
                    'nama_prodi' => 'TEKNIK INFORMATIKA',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nama_prodi' => 'SISTEM INFORMASI',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]
        );
    }
}
