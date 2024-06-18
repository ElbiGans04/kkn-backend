<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MahasiswaSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mahasiswa')->insert(
            [
                [
                    'nim' => '11211300012',
                    'nama' => 'Rhafael Bijaksana',
                    'tanggal_lahir' => '2003-04-12',
                    'alamat' => 'JL. RAYA LEGOK',
                    'nomor_telephone' => '085156433873',
                    'id_gender' => '1',
                    'id_kelas' => '1',
                    'id_prodi' => '1',
                    'id_user' => '4',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nim' => '11211300013',
                    'nama' => 'Ujang Sutisna',
                    'tanggal_lahir' => '2003-05-12',
                    'alamat' => 'JL. RAYA SEPATAN',
                    'nomor_telephone' => '085156433873',
                    'id_gender' => '1',
                    'id_kelas' => '1',
                    'id_prodi' => '1',
                    'id_user' => '5',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nim' => '11211300014',
                    'nama' => 'Vina Avianingsih',
                    'tanggal_lahir' => '2003-04-12',
                    'alamat' => 'JL. RAYA KOTBUM',
                    'nomor_telephone' => '089382991919',
                    'id_gender' => '1',
                    'id_kelas' => '1',
                    'id_prodi' => '1',
                    'id_user' => '6',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nim' => '11211300015',
                    'nama' => 'Mohammad Rayhand Noefikri',
                    'tanggal_lahir' => '2003-04-12',
                    'alamat' => 'JL. RAYA MAUK',
                    'nomor_telephone' => '089382991919',
                    'id_gender' => '1',
                    'id_kelas' => '1',
                    'id_prodi' => '1',
                    'id_user' => '7',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]
        );
    }
}
