<?php

namespace Database\Seeders;

use App\Enums\TipeAkun;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert(
            [
                [
                    'name' => 'BUDI SANTOSO',
                    'email' => 'budi@gmail.com',
                    'tipe_akun' => TipeAkun::dosen_pembimbing,
                    'password' => Hash::make('admin1234'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'LARASWATI',
                    'email' => 'laras@gmail.com',
                    'tipe_akun' => TipeAkun::dosen_pembimbing,
                    'password' => Hash::make('admin1234'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                // admin
                [
                    'name' => 'NUNUNG',
                    'email' => 'nunung@gmail.com',
                    'tipe_akun' => TipeAkun::admin,
                    'password' => Hash::make('admin1234'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                // Mahasiswa
                [
                    'name' => 'RHAFAEL',
                    'email' => 'rhafael@gmail.com',
                    'tipe_akun' => TipeAkun::mahasiswa,
                    'password' => Hash::make('admin1234'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'UJANG',
                    'email' => 'ujang@gmail.com',
                    'tipe_akun' => TipeAkun::mahasiswa,
                    'password' => Hash::make('admin1234'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'VINA',
                    'email' => 'vina@gmail.com',
                    'tipe_akun' => TipeAkun::mahasiswa,
                    'password' => Hash::make('admin1234'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'REYHAN',
                    'email' => 'reyhan@gmail.com',
                    'tipe_akun' => TipeAkun::mahasiswa,
                    'password' => Hash::make('admin1234'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]
        );
    }
}
