<?php

namespace App\Http\Controllers;

use App\Enums\StatusPersetujuan;
use App\Http\Requests\AkademikDataRequest;
use App\Http\Requests\BimbinganDataRequest;
use App\Models\Akademik;
use App\Models\Bimbingan;
use App\Models\Kelompok;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AkademikController extends Controller
{
     /**
     * Return All Data
     */
    public function GetAllAkademikData()
    {
        return Akademik::with(['mahasiswa'])->get();
    }

    /**
     * Return Spesific Data
     */
    public function GetAkademikData(string $id)
    {
        $detail = Akademik::where('id_bimbingan', $id)->with(['mahasiswa'])->first();
        return isset($detail) ? $detail : response([
            'message' => 'Data tidak ditemukan'
        ], 404);
    }

    /**
     * Save Data to database
     */
    public function StoreAkademikData(AkademikDataRequest $request)
    {
        $data = $request->validated();
        $kelompok = Mahasiswa::where(
            [
                ['nim', $data['nim_mahasiswa']],
            ]
        )->first();

        // Check apakah dosen sudah menjadi pembimbing dimanapun
        if (!isset($kelompok)) {
            return response(
                [
                    'message' => 'Mahasiswa tidak ditemukan'
                ],
                404
            );
        }

        $akademik = Akademik::where(
            [
                ['nim_mahasiswa', $data['nim_mahasiswa']],
            ]
        )->first();

        // Check apakah dosen sudah menjadi pembimbing dimanapun
        if (isset($akademik)) {
            return response(
                [
                    'message' => 'Data akademik telah ditambahkan'
                ],
                400
            );
        }

        DB::transaction(function () use ($data, $kelompok) {
            // Buat Kelompok
            $akademik2 = new Akademik();
            $akademik2->ipk = $data['ipk'];
            $akademik2->semester_saat_ini = $data['semester_saat_ini'];
            $akademik2->sks = $data['sks'];
            $akademik2->nim_mahasiswa = $kelompok['nim'];
            $akademik2->save();
        });

        return response([
            "message" => "Berhasil membuat data akademik"
        ], 201);
    }

    /**
     * Update Data to database
     */
    public function UpdateAkademikData($id, AkademikDataRequest $request)
    {
        $data = $request->validated();

        $kelompok = Akademik::where(
            [
                ['id_akademik', $id],
                ['nim_mahasiswa', $data['nim_mahasiswa']],
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
            $kelompok['sks'] = $data['sks'];
            $kelompok['ipk'] = $data['ipk'];
            $kelompok['semester_saat_ini'] = $data['semester_saat_ini'];
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
    public function DeleteAkademikData($id)
    {
        $kelompok = Akademik::where(
            [
                ['id_akademik', $id],
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
