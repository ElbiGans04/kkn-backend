<?php

namespace App\Http\Controllers;

use App\Enums\StatusPersetujuan;
use App\Http\Requests\NilaiDataRequest;
use App\Http\Requests\SidangDataRequest;
use App\Models\Anggota;
use App\Models\Mahasiswa;
use App\Models\Nilai;
use Illuminate\Support\Facades\DB;

class NilaiController extends Controller
{
     /**
     * Return All Data
     */
    public function GetAllNilaiData()
    {
        return Nilai::with(['mahasiswa'])->get();
    }

    /**
     * Return Spesific Data
     */
    public function GetNilaiData(string $id)
    {
        $detail = Nilai::where('id_nilai', $id)->with(['mahasiswa'])->first();
        return isset($detail) ? $detail : response([
            'message' => 'Data tidak ditemukan'
        ], 404);
    }

    /**
     * Save Data to database
     */
    public function StoreNilaiData(NilaiDataRequest $request)
    {
        $data = $request->validated();

        $kelompok = Mahasiswa::where(
            [
                [
                    'nim', $data['nim']
                ],
            ]
        )->first();

        if (!isset($kelompok)) {
            return response(
                [
                    'message' => 'Mahasiswa tidak ditemukan'
                ],
                404
            );
        }

        DB::transaction(function () use ($data, $kelompok) {
            $nilai = new Nilai();
            $nilai->nim_mahasiswa = $data['nim'];
            $nilai->nilai = $data['nilai'];
            $nilai->save();
        });

        return response([
            "message" => "Berhasil membuat nilai baru"
        ], 201);
    }

    /**
     * Update Data to database
     */
    public function UpdateNilaiData($id, NilaiDataRequest $request)
    {
        $data = $request->validated();
        $isExist = Nilai::where(
            [
                [
                    'id_nilai', $id
                ],
                [
                    'nim_mahasiswa', $data['nim']
                ],
            ]
        )->first();

        if (!isset($isExist)) {
            return response(
                ['message' => 'Data tidak ditemukan'],
                404
            );
        }

        DB::transaction(function () use ($data, $isExist) {
            $isExist->nim_mahasiswa = $data['nim'];
            $isExist->nilai = $data['nilai'];
            $isExist->save();
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
    public function DeleteNilaiData($id)
    {
        $isExist = Nilai::where(
            [
                [
                    'id_nilai', $id
                ],
            ]
        )->first();

        if (!isset($isExist)) {
            return response(
                ['message' => 'Data tidak ditemukan'],
                404
            );
        }


        DB::transaction(function () use ($isExist, $id) {
            $isExist->delete();
        });

        return response(
            ['message' => 'Data berhasil di hapus'],
            200
        );
    }
}
