<?php

namespace App\Http\Controllers;

use App\Http\Requests\KelasDataRequest;
use App\Http\Requests\ProdiDataRequest;
use App\Models\Kelas;
use App\Models\Prodi;
use Illuminate\Support\Facades\DB;

class KelasController extends Controller
{
      /**
     * Return All Data
     */
    public function GetAllKelasData()
    {
        return Kelas::all();
    }

    /**
     * Return Spesific Data
     */
    public function GetKelasData(string $id)
    {
        $detail = Kelas::where('id_kelas', $id)->first();
        return isset($detail) ? $detail : response([
            'message' => 'Data tidak ditemukan'
        ], 404);
    }

    /**
     * Save Data to database
     */
    public function StoreKelasData(KelasDataRequest $request)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data) {
            // Buat Kelompok
            $akademik2 = new Kelas();
            $akademik2->nama_kelas = $data['nama_kelas'];
            $akademik2->nama_pa = $data['nama_pa'];
            $akademik2->save();
        });

        return response([
            "message" => "Berhasil membuat data kelas"
        ], 201);
    }

    /**
     * Update Data to database
     */
    public function UpdateKelasData($id, KelasDataRequest $request)
    {
        $data = $request->validated();

        $kelompok = Kelas::where(
            [
                ['id_kelas', $id],
            ]
        )->first();

        // Check apakah dosen sudah menjadi pembimbing dimanapun
        if (!isset($kelompok)) {
            return response(
                [
                    'message' => 'Data tidak ditemukan'
                ],
                404
            );
        }

        DB::transaction(function () use ($data, $kelompok) {
            $kelompok['nama_kelas'] = $data['nama_kelas'];
            $kelompok['nama_pa'] = $data['nama_pa'];
            $kelompok->save();
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
    public function DeleteKelasData($id)
    {
        $kelompok = Kelas::where(
            [
                ['id_kelas', $id],
            ]
        )->first();

        // Check apakah dosen sudah menjadi pembimbing dimanapun
        if (!isset($kelompok)) {
            return response(
                [
                    'message' => 'Data tidak ditemukan'
                ],
                404
            );
        }


        DB::transaction(function () use ($kelompok) {
            $kelompok->delete();
        });

        return response(
            ['message' => 'Data berhasil di hapus'],
            200
        );
    }
}
