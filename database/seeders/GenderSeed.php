<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenderSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('genders')->insert(
            [
                [
                    'nama_gender' => 'LAKI-LAKI',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nama_gender' => 'PEREMPUAN',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]
        );
    }
}
