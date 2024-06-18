<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DosenSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('dosen')->insert(
            [
                [
                    'nid' => '2392019',
                    'nama' => 'BUDI SANTOSO',
                    'tanggal_lahir' => '1981-12-01',
                    'alamat' => 'JL. SURAMADU NO 90 TANGERANG',
                    'id_gender' => '1',
                    'id_user' => '1',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nid' => '239403',
                    'nama' => 'LARASWATI',
                    'tanggal_lahir' => '1991-12-01',
                    'alamat' => 'JL. PABUARAN NO 90 TANGERANG',
                    'id_gender' => '2',
                    'id_user' => '2',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                // Admin
                [
                    'nid' => '29190192',
                    'nama' => 'NUNUNG',
                    'tanggal_lahir' => '1989-10-01',
                    'alamat' => 'JL. PABUARAN NO 90 TANGERANG',
                    'id_gender' => '2',
                    'id_user' => '3',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]
        );
    }
}
