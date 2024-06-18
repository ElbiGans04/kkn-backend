<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelasSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kelas')->insert(
            [
                [
                    'nama_kelas' => 'TI SE 21 M',
                    'nama_pa' => 'NUNUNG',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nama_kelas' => 'TI SE 21 P',
                    'nama_pa' => 'NUNUNG',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]
        );
    }
}
