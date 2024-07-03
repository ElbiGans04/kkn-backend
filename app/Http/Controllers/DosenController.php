<?php

namespace App\Http\Controllers;

use App\Enums\TipeAkun;
use App\Http\Requests\DosenDataRequest;
use App\Http\Requests\MahasiswaDataRequest;
use App\Models\Dosen;
use App\Models\Gender;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class DosenController extends Controller
{
     /**
     * Return All Data
     */
    public function GetAllDosenData()
    {
        return Dosen::with(['user'])->get();
    }

    /**
     * Return Spesific Data
     */
    public function GetDosenData(string $id)
    {
        $detail = Dosen::with(['user'])->where('nid', $id)->first();
        return isset($detail) ? $detail : response([
            'message' => 'Data tidak ditemukan'
        ], 404);
    }

    /**
     * Save Data to database
     */
    public function StoreDosenData(DosenDataRequest $request)
    {
        Gate::authorize('allow', Dosen::class);
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

        $isExist4 = Dosen::find($data['nid']);
        if (isset($isExist4)) {
            return response(
                [
                    "message" => "Nid telah terdaftar"
                ],
                400
            );
        }
        

        DB::transaction(function () use ($data) {
            $user = new User();
            $user->email = $data['email'];
            $user->password = $data['password'];
            $user->name = $data['nama'];
            $user->tipe_akun = TipeAkun::dosen_pembimbing->value;
            $user->save();
            // Buat Kelompok
            $dosen = new Dosen();
            $dosen->nid = $data['nid'];
            $dosen->nama = $data['nama'];
            $dosen->tanggal_lahir = $data['tanggal_lahir'];
            $dosen->alamat = $data['alamat'];
            $dosen->id_gender = $data['id_gender'];
            $dosen->id_user = $user['id'];
            $dosen->save();
        });

        return response([
            "message" => "Berhasil membuat data Dosen"
        ], 201);
    }

    /**
     * Update Data to database
     */
    public function UpdateDosenData($id, DosenDataRequest $request)
    {
        Gate::authorize('allow', Dosen::class);
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

        $isExist4 = Dosen::find($id);
        if (!isset($isExist4)) {
            return response(
                [
                    "message" => "Dosen Tidak Di Temukan"
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
            $isExist4->id_gender = $data['id_gender'];
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
    public function DeleteDosenData($id)
    {
        Gate::authorize('allow', Dosen::class);
        $kelompok = Dosen::find($id);

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
