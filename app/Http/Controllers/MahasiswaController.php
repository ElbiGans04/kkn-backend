<?php

namespace App\Http\Controllers;

use App\Enums\TipeAkun;
use App\Http\Requests\MahasiswaDataRequest;
use App\Models\Gender;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class MahasiswaController extends Controller
{
     /**
     * Return All Data
     */
    public function GetAllMahasiswaData()
    {
        return Mahasiswa::with(['user'])->get();
    }

    /**
     * Return Spesific Data
     */
    public function GetMahasiswaData(string $id)
    {
        $detail = Mahasiswa::with(['user'])->where('nim', $id)->first();
        return isset($detail) ? $detail : response([
            'message' => 'Data tidak ditemukan'
        ], 404);
    }

    /**
     * Save Data to database
     */
    public function StoreMahasiswaData(MahasiswaDataRequest $request)
    {
        Gate::authorize('allow', Mahasiswa::class);
        $data = $request->validated();
        $isExist = Gender::find($data['id_gender']);
        if (!isset($isExist)) {
            return response(
                [
                    "message" => "Gender Tidak Ditemukan"
                ],
                404
            );
        }

        $isExist2 = Kelas::find($data['id_kelas']);
        if (!isset($isExist2)) {
            return response(
                [
                    "message" => "Kelas Tidak Ditemukan"
                ],
                404
            );
        }

        $isExist3 = Prodi::find($data['id_prodi']);
        if (!isset($isExist3)) {
            return response(
                [
                    "message" => "Prodi Tidak Ditemukan"
                ],
                404
            );
        }

        $isExist4 = Mahasiswa::find($data['nim']);
        if (isset($isExist4)) {
            return response(
                [
                    "message" => "Nim telah terdaftar"
                ],
                400
            );
        }
        

        DB::transaction(function () use ($data) {
            $user = new User();
            $user->email = $data['email'];
            $user->password = $data['password'];
            $user->name = $data['nama'];
            $user->tipe_akun = TipeAkun::mahasiswa->value;
            $user->save();
            // Buat Kelompok
            $mahasiswa = new Mahasiswa();
            $mahasiswa->nim = $data['nim'];
            $mahasiswa->nama = $data['nama'];
            $mahasiswa->tanggal_lahir = $data['tanggal_lahir'];
            $mahasiswa->alamat = $data['alamat'];
            $mahasiswa->nomor_telephone = $data['nomor_telephone'];
            $mahasiswa->id_gender = $data['id_gender'];
            $mahasiswa->id_kelas = $data['id_kelas'];
            $mahasiswa->id_prodi = $data['id_prodi'];
            $mahasiswa->id_user = $user['id'];
            $mahasiswa->save();
        });

        return response([
            "message" => "Berhasil membuat data user"
        ], 201);
    }

    /**
     * Update Data to database
     */
    public function UpdateMahasiswaData($id, MahasiswaDataRequest $request)
    {
        Gate::authorize('allow', Mahasiswa::class);
        $data = $request->validated();
        $isExist = Gender::find($data['id_gender']);
        if (!isset($isExist)) {
            return response(
                [
                    "message" => "Gender Tidak Ditemukan"
                ],
                404
            );
        }

        $isExist2 = Kelas::find($data['id_kelas']);
        if (!isset($isExist2)) {
            return response(
                [
                    "message" => "Kelas Tidak Ditemukan"
                ],
                404
            );
        }

        $isExist3 = Prodi::find($data['id_prodi']);
        if (!isset($isExist3)) {
            return response(
                [
                    "message" => "Prodi Tidak Ditemukan"
                ],
                404
            );
        }

        $isExist4 = Mahasiswa::find($id);
        if (!isset($isExist4)) {
            return response(
                [
                    "message" => "Mahasiswa Tidak Di Temukan"
                ],
                400
            );
        }

        $isExist5 = User::find($isExist4['id_user']);
        if (!isset($isExist5)) {
            return response(
                [
                    "message" => "User Tidak Di Temukan"
                ],
                400
            );
        }

        DB::transaction(function () use ($data, $isExist4, $isExist5) {
            $isExist4->nama = $data['nama'];
            $isExist4->tanggal_lahir = $data['tanggal_lahir'];
            $isExist4->alamat = $data['alamat'];
            $isExist4->nomor_telephone = $data['nomor_telephone'];
            $isExist4->id_gender = $data['id_gender'];
            $isExist4->id_kelas = $data['id_kelas'];
            $isExist4->id_prodi = $data['id_prodi'];
            $isExist4->save();

            $isExist5->email = $data['email'];
            $isExist5->name = $data['nama'];
            $isExist5->save();
        });

        return response(
            [
                "message" => "Berhasil memperbaharui data"
            ],
            200
        );
    }

    /**
     * Delete Data to database
     */
    public function DeleteMahasiswaData($id)
    {
        Gate::authorize('allow', Mahasiswa::class);
        $kelompok = Mahasiswa::find($id);

        // Check apakah dosen sudah menjadi pembimbing dimanapun
        if (!isset($kelompok)) {
            return response(
                [
                    'message' => 'Data tidak ditemukan'
                ],
                404
            );
        }

        $kelompok2 = User::find($kelompok['id_user']);

        // Check apakah dosen sudah menjadi pembimbing dimanapun
        if (!isset($kelompok2)) {
            return response(
                [
                    'message' => 'Data tidak ditemukan'
                ],
                404
            );
        }


        DB::transaction(function () use ($kelompok, $kelompok2) {
            $kelompok->delete();
            $kelompok2->delete();
        });

        return response(
            ['message' => 'Data berhasil di hapus'],
            200
        );
    }
}
