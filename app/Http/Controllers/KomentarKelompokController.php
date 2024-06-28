<?php

namespace App\Http\Controllers;

use App\Http\Requests\KomentarKelompokDataRequest;
use App\Models\Kelompok;
use App\Models\KomentarKelompok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KomentarKelompokController extends Controller
{
    /**
     * Return All Data
     */
    public function GetAllKomentarKelompokData()
    {
        return KomentarKelompok::with(['dosen_pembimbing', 'kelompok'])->get();
    }

    /**
     * Return Spesific Data
     */
    public function GetKomentarKelompokData(string $id)
    {
        $detail = KomentarKelompok::where('id_komentar_kelompok', $id)->with(['dosen_pembimbing', 'kelompok'])->first();
        return isset($detail) ? $detail : response([
            'message' => 'Data tidak ditemukan'
        ], 404);
    }

    /**
     * Save Data to database
     */
    public function StoreKomentarKelompokData(KomentarKelompokDataRequest $request)
    {
        $data = $request->validated();
        $kelompok = Kelompok::where(
            [
                ['id_dospem', $request->user()['id']],
                ['id_kelompok', $data['id_kelompok']],
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


        DB::transaction(function () use ($data, $kelompok, $request) {
            // Buat Kelompok
            $komentarBaru = new KomentarKelompok();
            $komentarBaru->judul_komentar = $data['title'];
            $komentarBaru->body_komentar = $data['body'];
            $komentarBaru->id_kelompok = $kelompok['id_kelompok'];
            $komentarBaru->id_dospem = $request->user()['id'];
            $komentarBaru->save();
        });

        return response([
            "message" => "Berhasil membuat komentar kelompok"
        ], 201);
    }

    /**
     * Update Data to database
     */
    public function UpdateKomentarKelompokData($id, KomentarKelompokDataRequest $request)
    {
        $data = $request->validated();
        $kelompok = KomentarKelompok::where(
            [
                ['id_komentar_kelompok', $id],
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
            $kelompok['judul_komentar'] = $data['title'];
            $kelompok['body_komentar'] = $data['body'];
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
    public function DeleteKomentarKelompokData($id, Request $request)
    {
        $data = $data = $request->validate([
            'id_kelompok' => ['required', 'numeric']
        ]);
        $kelompok = KomentarKelompok::where(
            [
                ['id_komentar_kelompok', $id],
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
