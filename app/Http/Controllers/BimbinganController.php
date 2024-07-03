<?php

namespace App\Http\Controllers;

use App\Enums\StatusPersetujuan;
use App\Http\Requests\BimbinganDataRequest;
use App\Models\Bimbingan;
use App\Models\Kelompok;
use App\Models\ProgramKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BimbinganController extends Controller
{
    /**
     * Return All Data
     */
    public function GetAllBimbinganData()
    {
        return Bimbingan::with([])->get();
    }

    /**
     * Return Spesific Data
     */
    public function GetBimbinganData(string $id)
    {
        $detail = Bimbingan::where('id_bimbingan', $id)->with([])->first();
        return isset($detail) ? $detail : response([
            'message' => 'Data tidak ditemukan'
        ], 404);
    }

    /**
     * Save Data to database
     */
    public function StoreBimbinganData(BimbinganDataRequest $request)
    {
        $data = $request->validated();
        $kelompok = Kelompok::where(
            [
                ['id_dospem', $request->user()['id']],
                ['id_kelompok', $data['id_kelompok']],
                ['approve', StatusPersetujuan::approve->value],
            ]
        )->first();

        // Check apakah dosen sudah menjadi pembimbing dimanapun
        if (!isset($kelompok)) {
            return response(
                [
                    'message' => 'Kelompok tidak ditemukan'
                ],
                404
            );
        }

        $proker = ProgramKerja::where(
            [
                ['id_kelompok', $kelompok['id_kelompok']],
                ['approve', StatusPersetujuan::approve->value],
            ]
        )->first();

        if (!isset($proker)) {
            return response(
                [
                    'message' => 'Harap Membuat Program Kerja Terlebih Dahulu'
                ],
                404
            );
        }


        DB::transaction(function () use ($data, $kelompok, $request) {
            // Buat Kelompok
            $bimbingan = new Bimbingan();
            $bimbingan->judul = $data['title'];
            $bimbingan->body = $data['body'];
            $bimbingan->tanggal_bimbingan = $data['tanggal_bimbingan'];
            $bimbingan->link_bimbingan = $data['link_bimbingan'];
            $bimbingan->id_kelompok = $kelompok['id_kelompok'];
            $bimbingan->id_dospem = $request->user()['id'];
            $bimbingan->save();
        });

        return response([
            "message" => "Berhasil membuat bimbingan"
        ], 201);
    }

    /**
     * Update Data to database
     */
    public function UpdateBimbinganData($id, BimbinganDataRequest $request)
    {
        $data = $request->validated();
        $kelompok = Bimbingan::where(
            [
                ['id_bimbingan', $id],
                ['id_dospem', $request->user()['id']],
                ['id_kelompok', $data['id_kelompok']],
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
            $kelompok['judul'] = $data['title'];
            $kelompok['body'] = $data['body'];
            $kelompok['tanggal_bimbingan'] = $data['tanggal_bimbingan'];
            $kelompok['link_bimbingan'] = $data['link_bimbingan'];
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
    public function DeleteBimbinganData($id, Request $request)
    {
        $data = $data = $request->validate([
            'id_kelompok' => ['required', 'numeric']
        ]);
        $kelompok = Bimbingan::where(
            [
                ['id_bimbingan', $id],
                ['id_dospem', $request->user()['id']],
                ['id_kelompok', $data['id_kelompok']],
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
